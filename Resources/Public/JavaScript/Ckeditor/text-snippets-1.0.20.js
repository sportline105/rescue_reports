import * as Core from '@ckeditor/ckeditor5-core';
import * as UI from '@ckeditor/ckeditor5-ui';
import { html } from 'lit';
import { unsafeHTML } from 'lit/directives/unsafe-html.js';
import Modal from '@typo3/backend/modal.js';

export class TextSnippets extends Core.Plugin {
    static get pluginName() {
        return 'TextSnippets';
    }

    init() {
        const editor = this.editor;
        const ajaxUrl = globalThis.TYPO3?.settings?.ajaxUrls?.rescue_reports_snippets ?? null;

        const loadSnippets = () => {
            if (!ajaxUrl) {
                return Promise.resolve([]);
            }

            return fetch(ajaxUrl, {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`Failed to load text snippets: ${response.status}`);
                    }

                    return response.json();
                })
                .then((items) => Array.isArray(items) ? items : [])
                .catch(() => []);
        };

        editor.ui.componentFactory.add('textsnippets', locale => {
            const view = new UI.ButtonView(locale);

            view.set({
                label: 'Textbausteine',
                withText: true,
                tooltip: true,
            });

            view.on('render', () => {
                if (!view.element) {
                    return;
                }

                const preventToolbarFocus = (event) => {
                    event.preventDefault();
                    this.focusEditableWithoutScroll(editor);
                };

                view.element.addEventListener('mousedown', preventToolbarFocus);
                view.element.addEventListener('pointerdown', preventToolbarFocus);
            });

            view.on('execute', async () => {
                const items = await loadSnippets();

                if (items.length === 0) {
                    globalThis.alert('Keine Textbausteine vorhanden.');
                    return;
                }

                const scrollState = this.captureScrollState(editor);
                this.focusEditableWithoutScroll(editor);
                await this.waitForNextTask();

                const snippet = await this.openSnippetModal(items, editor, scrollState);
                if (snippet === null) {
                    return;
                }

                const snippetHtml = this.getSnippetHtml(snippet);
                if (snippetHtml === '') {
                    globalThis.alert('Der ausgewählte Textbaustein ist leer.');
                    return;
                }

                const viewFragment = editor.data.processor.toView(snippetHtml);
                const modelFragment = editor.data.toModel(viewFragment);

                editor.model.change(() => {
                    editor.model.insertContent(modelFragment, editor.model.document.selection);
                });

                this.focusEditableWithoutScroll(editor);
            });

            return view;
        });
    }

    openSnippetModal(items, editor, scrollState) {
        return new Promise((resolve) => {
            let selectedSnippet = null;

            // Freeze ALL scroll containers with overflow:hidden BEFORE Modal.advanced().
            //
            // TYPO3 v14 modal uses a native <dialog> element. When dialog.close() is
            // called, the browser synchronously returns focus to the element that was
            // active when dialog.showModal() was called — WITHOUT preventScroll:true.
            // This happens BEFORE typo3-modal-hidden fires, so any rAF-based lock is
            // too late and causes a one-frame scroll flash.
            //
            // The only reliable fix: use overflow:hidden so the browser's native focus
            // restoration cannot scroll anything. We unfreeze synchronously inside the
            // typo3-modal-hidden handler, before the browser paints.
            const unfreeze = this.freezeScrollContainers(scrollState);

            const bindModalBehavior = (modalElement) => {
                globalThis.requestAnimationFrame(() => {
                    const select = modalElement.querySelector('[data-rescue-snippet-select]');
                    const preview = modalElement.querySelector('[data-rescue-snippet-preview]');

                    if (!select || !preview) {
                        return;
                    }

                    const updatePreview = () => {
                        const selectedIndex = typeof select.selectedIndex === 'number' ? select.selectedIndex : -1;
                        const snippet = items[selectedIndex] ?? null;
                        const snippetHtml = snippet ? this.getSnippetHtml(snippet) : '';
                        preview.innerHTML = snippetHtml !== '' ? snippetHtml : '[Keine Vorschau verfügbar]';
                    };

                    select.addEventListener('change', updatePreview);
                    select.addEventListener('input', updatePreview);
                    updatePreview();
                    this.focusElementWithoutScroll(select);
                });
            };

            const dismissModal = () => {
                // Move focus to the editor BEFORE Modal.dismiss() so the native
                // <dialog> records the editor as the return-focus target.
                this.focusEditableWithoutScroll(editor);

                globalThis.setTimeout(() => {
                    Modal.dismiss();
                }, 0);
            };

            const modal = Modal.advanced({
                title: 'Textbaustein auswählen',
                size: Modal.sizes.medium,
                content: this.buildModalContent(items),
                callback: bindModalBehavior,
                buttons: [
                    {
                        text: 'Abbrechen',
                        btnClass: 'btn-default',
                        name: 'cancel',
                        trigger: () => {
                            selectedSnippet = null;
                            dismissModal();
                        },
                    },
                    {
                        text: 'Einfügen',
                        btnClass: 'btn-primary',
                        name: 'ok',
                        trigger: () => {
                            const select = modal.querySelector('[data-rescue-snippet-select]');
                            const selectedIndex = select && typeof select.selectedIndex === 'number'
                                ? select.selectedIndex
                                : -1;
                            selectedSnippet = items[selectedIndex] ?? null;
                            dismissModal();
                        },
                    },
                ],
            });

            modal.addEventListener('typo3-modal-hidden', () => {
                // At this point dialog.close() has already run — focus was restored
                // by the browser, but overflow:hidden prevented any scroll.
                //
                // Now unfreeze SYNCHRONOUSLY: restores overflow + scrollTop in one
                // JS task. The browser paints AFTER this handler completes, so it
                // sees the correct scroll position. No flash, no rAF needed.
                this.focusEditableWithoutScroll(editor);
                unfreeze();
                resolve(selectedSnippet);
            }, { once: true });
        });
    }

    buildModalContent(items) {
        const initialPreviewHtml = this.getSnippetHtml(items[0]);

        return html`
            <div class="mb-3">
                <label class="form-label" for="rescue-reports-snippet-select">Textbaustein</label>
                <select id="rescue-reports-snippet-select" class="form-select" data-rescue-snippet-select>
                    ${items.map((item, index) => html`
                        <option value=${String(index)}>
                            ${item.title}${item.category ? ` (${item.category})` : ''}
                        </option>
                    `)}
                </select>
            </div>
            <div>
                <label class="form-label" for="rescue-reports-snippet-preview">Vorschau</label>
                <div
                    id="rescue-reports-snippet-preview"
                    class="form-control"
                    data-rescue-snippet-preview
                    style="min-height: 9rem; overflow: auto;"
                >
                    ${initialPreviewHtml !== '' ? unsafeHTML(initialPreviewHtml) : '[Keine Vorschau verfügbar]'}
                </div>
            </div>
        `;
    }

    getSnippetHtml(snippet) {
        if (typeof snippet?.html === 'string' && snippet.html.trim() !== '') {
            return snippet.html;
        }

        if (typeof snippet?.content === 'string' && snippet.content.trim() !== '') {
            return snippet.content;
        }

        return '';
    }

    waitForNextTask() {
        return new Promise((resolve) => {
            globalThis.setTimeout(resolve, 0);
        });
    }

    captureScrollState(editor) {
        const doc = globalThis.document;
        const entries = [];
        const seen = new Set();

        const remember = (element) => {
            if (!element || seen.has(element)) {
                return;
            }

            seen.add(element);
            entries.push({
                element,
                top: element.scrollTop,
                left: element.scrollLeft,
            });
        };

        let current = doc?.activeElement ?? null;
        while (current && current instanceof Element) {
            if (current.scrollHeight > current.clientHeight || current.scrollWidth > current.clientWidth) {
                remember(current);
            }
            current = current.parentElement;
        }

        const editableElement = typeof editor.ui?.getEditableElement === 'function'
            ? editor.ui.getEditableElement()
            : null;
        current = editableElement ?? null;
        while (current && current instanceof Element) {
            if (current.scrollHeight > current.clientHeight || current.scrollWidth > current.clientWidth) {
                remember(current);
            }
            current = current.parentElement;
        }

        if (doc?.documentElement) {
            remember(doc.documentElement);
        }

        if (doc?.body) {
            remember(doc.body);
        }

        return {
            entries,
            windowX: globalThis.scrollX ?? 0,
            windowY: globalThis.scrollY ?? 0,
            rootTop: doc?.documentElement?.scrollTop ?? 0,
            rootLeft: doc?.documentElement?.scrollLeft ?? 0,
        };
    }

    // Sets overflow:hidden on all scroll containers so nothing can scroll
    // (including the browser's native <dialog> focus-restoration scroll).
    // Returns a synchronous unfreeze() that restores overflow + scrollTop
    // in the same JS task, guaranteeing no paint occurs in between.
    freezeScrollContainers(scrollState) {
        const doc = globalThis.document;
        const root = doc?.documentElement ?? null;
        const body = doc?.body ?? null;
        const saved = new Map();

        const freeze = (element) => {
            if (!(element instanceof Element) || saved.has(element)) {
                return;
            }
            saved.set(element, {
                overflow: element.style.overflow,
                scrollBehavior: element.style.scrollBehavior,
            });
            element.style.overflow = 'hidden';
            element.style.scrollBehavior = 'auto';
        };

        scrollState.entries.forEach(({ element }) => freeze(element));
        if (root) {
            freeze(root);
        }
        if (body) {
            freeze(body);
        }

        return () => {
            // Step 1: restore overflow (elements become scrollable again).
            saved.forEach(({ overflow, scrollBehavior }, element) => {
                element.style.overflow = overflow;
                element.style.scrollBehavior = scrollBehavior;
            });

            // Step 2: immediately restore scroll positions in the same task.
            // The browser paints after this entire function returns, so no
            // intermediate state is ever shown to the user.
            scrollState.entries.forEach(({ element, top, left }) => {
                if (typeof element.scrollTo === 'function') {
                    element.scrollTo({ top, left, behavior: 'auto' });
                } else {
                    element.scrollTop = top;
                    element.scrollLeft = left;
                }
            });

            if (root && typeof root.scrollTo === 'function') {
                root.scrollTo({ top: scrollState.rootTop, left: scrollState.rootLeft, behavior: 'auto' });
            } else if (root) {
                root.scrollTop = scrollState.rootTop;
                root.scrollLeft = scrollState.rootLeft;
            }

            if (typeof globalThis.scrollTo === 'function') {
                globalThis.scrollTo({ left: scrollState.windowX, top: scrollState.windowY, behavior: 'auto' });
            }
        };
    }

    disableSmoothScroll(element) {
        if (!(element instanceof HTMLElement) && !(element instanceof Element)) {
            return () => {};
        }

        const previousBehavior = element.style.scrollBehavior;
        element.style.scrollBehavior = 'auto';

        return () => {
            element.style.scrollBehavior = previousBehavior;
        };
    }

    focusElementWithoutScroll(element) {
        if (!element || typeof element.focus !== 'function') {
            return;
        }

        try {
            element.focus({ preventScroll: true });
        } catch {
            element.focus();
        }
    }

    focusEditableWithoutScroll(editor) {
        const editableElement = typeof editor.ui?.getEditableElement === 'function'
            ? editor.ui.getEditableElement()
            : null;

        if (!editableElement) {
            return;
        }

        this.focusElementWithoutScroll(editableElement);
    }
}

export default TextSnippets;
