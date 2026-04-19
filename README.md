# rescue_reports – TYPO3-Extension für Feuerwehr-Einsatzberichte

Detaillierte Einsatzberichte für Feuerwehren und BOS mit modernem Event-System. Die Extension stellt Frontend-Plugins für Einsatzlisten, Statistiken, ein Sidebar-Widget und einen RSS-Feed bereit. Vollständig optimiert für TYPO3 13 und 14.

**Extension-Key:** `rescue_reports`  
**TYPO3:** 13.0 – 14.99  
**PHP:** 8.2 – 8.99  
**Lizenz:** GPL-2.0-or-later

---

## Installation & Ersteinrichtung

Nach der Installation wird empfohlen, den **Initialierungs-Wizard** in der TYPO3-Backend-Systemwartung (`Wartung` → `Upgrade Wizards`) auszuführen. Dieser richtet folgendes ein:

- Einen Speicherordner für die Extension-Datensätze
- Gängige deutsche BOS-Organisationen (Behörden und Organisationen mit Sicherheitsaufgaben)
- Typische Fahrzeugtypen

Der Wizard ist optional; alle Datensätze lassen sich auch manuell anlegen.

### Site Set

Diese Extension bietet ein vorkonfiguriertes Site Set (`rescue_reports/rescue-reports`) mit allen notwendigen TypoScript-Einstellungen. Bei der Site-Erstellung kann dieses Set automatisch angewendet werden.

---

## Plugins

### 1. Einsatzliste (`tx_rescuereports_eventlist`)

Hauptansicht mit Listenansicht und Detailansicht der Einsätze.

**Aktionen:** `list`, `show`

#### FlexForm-Einstellungen

| Einstellung | Beschreibung | Standard |
|---|---|---|
| **Design** | Template-Variante: Bootstrap oder Foundation | Bootstrap |
| **Standard-Ortsfeuerwehr** | Vorausgewählte Station beim Seitenaufruf | – (keine) |
| **Auswahl der Ortsfeuerwehr im Frontend anzeigen** | Stationsfilter sichtbar schalten | Ja |
| **Anzahl angezeigter Einsätze** | 0 = unbegrenzt | 0 |
| **Datum von / bis** | Feste Datumsbereichseinschränkung | – |
| **Suche aktivieren** | Volltextsuche (Titel, Beschreibung, Ort, Typ, Nummer) | Nein |
| **Seite für Einsatz-Detailansicht** | Zielseite für „Zum Einsatz"-Link | – |
| **Datumsfilter im Frontend anzeigen** | Datumseingabe für Besucher | Nein |
| **Jahresfilter anzeigen** | Jahresauswahl im Frontend | Ja |
| **Jahresfilter: Standardauswahl beim ersten Aufruf** | „Alle Jahre" oder „Aktuelles Jahr" | Alle Jahre |
| **Statistik anzeigen** | Tortendiagramm-Statistik einblenden | Nein |
| **Statistik: Anzahl angezeigter Jahre** | 0 = alle Jahre | 0 |
| **Position der Statistik** | Oberhalb oder unterhalb der Einsatzliste | Unterhalb |
| **Kartenansicht anzeigen** | OpenStreetMap-Karte einblenden | Nein |
| **Kartenhöhe in Pixeln** | | 400 |
| **Standard-Zoomstufe (1–19)** | | 11 |
| **Position der Karte** | Oberhalb oder unterhalb der Einsatzliste | Unterhalb |

#### Jahresfilter-Verhalten

- Ist „Alle Jahre" gewählt, werden Einsätze jahresweise gruppiert mit Sprungmarken-Navigation und optionaler Inline-Statistik je Jahresgruppe.
- Ist „Aktuelles Jahr" gewählt, wird die Liste beim ersten Aufruf automatisch auf das laufende Jahr gefiltert; der Besucher kann per Jahresauswahl wechseln.
- **„Jahresfilter anzeigen"** steuert nur die Sichtbarkeit der Jahresauswahl im Frontend – der Datenbereich und die Gruppierung sind davon unabhängig.

#### Statistik

Die Statistik basiert auf dem Tortendiagramm-Partial (`Statistics/PieChart.html`) und wird sowohl als Block (einzelnes Jahr) als auch inline über jeder Jahresgruppe (alle Jahre) gerendert. Beim Hover über ein Tortenstück erscheint ein CSS-Tooltip mit Kategoriename, zugehörigen Einsatzarten und Anzahl/Prozentsatz. Die Position (ober-/unterhalb) wird über `statisticsPosition` gesteuert.

---

### 2. Statistik (`tx_rescuereports_statistics`)

Eigenständige Statistikseite mit Jahres- und Monatsdiagrammen.

**Aktion:** `statistics`

| Einstellung | Beschreibung | Standard |
|---|---|---|
| **Station** | Filterung auf eine Ortsfeuerwehr | – (alle) |
| **Stationsauswahl im Frontend anzeigen** | | Ja |
| **Anzahl angezeigter Jahre** | 0 = alle | 0 |
| **Monatsvergleich anzeigen** | Monatliches Balkendiagramm | Ja |

---

### 3. Sidebar-Widget (`tx_rescuereports_sidebar`)

Kompaktes Widget für die Seitenleiste – zeigt die letzten N Einsätze.

**Aktion:** `list` (gecacht)

| Einstellung | Beschreibung | Standard |
|---|---|---|
| **Widget-Überschrift** | Freitext | „Letzte Einsätze" |
| **Standard-Ortsfeuerwehr** | 0 = alle | – (alle) |
| **Anzahl angezeigter Einsätze** | | 5 |
| **Design** | Bootstrap oder Foundation | Bootstrap |
| **Seite für Einsatz-Detailansicht** | | – |
| **Seite der vollständigen Einsatzliste** | Ziel des „Alle Einsätze"-Links | – |

---

### 4. RSS-Feed (`tx_rescuereports_rss`)

RSS 2.0-Feed der neuesten Einsätze.

**Aktion:** `rss` (gecacht)  
**Seitentyp:** `typeNum = 100`

| Einstellung | Beschreibung | Standard |
|---|---|---|
| **Feed-Titel** | Freitext | – |
| **Station** | Filterung auf eine Ortsfeuerwehr | – (alle) |
| **Anzahl der Einträge** | | 20 |
| **Seite für Einsatz-Detailansicht** | | – |

---

## Template-Varianten

| Wert | Beschreibung | Verwendung |
|---|---|---|
| `bootstrap` | Bootstrap 5 | Einsatzliste, Detailansicht |
| `foundation` | Foundation | Einsatzliste, Detailansicht |
| `sidebar-bootstrap` | Bootstrap 5 | Sidebar-Widget |
| `sidebar-foundation` | Foundation | Sidebar-Widget |

> Bestehende Plugin-Instanzen mit alten Werten (`standard`, `newdesign`, `sidebar`, `newdesignsidebar`) werden automatisch auf die entsprechenden neuen Werte gemappt.

---

## Template-Variablen Referenz

Nachfolgend ist eine vollständige Referenz aller Template-Variablen pro Plugin-Action dokumentiert. Diese Variablen sind in den entsprechenden Fluid-Templates verfügbar und können für individuelle Anpassungen verwendet werden.

### Einsatzliste (`tx_rescuereports_eventlist`)

#### Action: `list` – Ereignisliste und Sidebar-Widget

| Variable | Typ | Beschreibung |
|---|---|---|
| `events` | `ObjectStorage<Event>` | Alle gefilterten Einsätze (Rohkollektion) |
| `eventItems` | `Array` | Einsätze mit berechneten Stationsnummern |
| `eventItemsByYear` | `Array` | Einsätze gruppiert nach Jahr (Struktur: `[year => [events]]`) |
| `yearGroupsWithStats` | `Array` | Jahre mit eingebetteten Statistiken (für Inline-Statistik) |
| `stations` | `ObjectStorage<Station>` | Verfügbare Stationen (für Dropdown-Filter) |
| `activeStationUid` | `int` | UID der aktuell gewählten Station |
| `defaultStationUid` | `int` | UID der Standard-/Vorausgewählten Station (aus FlexForm) |
| `activeStationName` | `string` | Name der aktuellen Station (für Anzeige) |
| `searchWord` | `string` | Benutzereingabe Suchtext |
| `enableSearch` | `bool` | Suche aktiviert (aus FlexForm) |
| `dateFrom` | `\DateTime` | Startdatum des Filters als DateTime-Objekt |
| `dateTo` | `\DateTime` | Enddatum des Filters als DateTime-Objekt |
| `dateFromStr` | `string` | Startdatum als String (Format: `YYYY-MM-DD`) |
| `dateToStr` | `string` | Enddatum als String (Format: `YYYY-MM-DD`) |
| `enableDateFilter` | `bool` | Datumsfilter im Frontend anzeigen (aus FlexForm) |
| `maxCount` | `int` | Maximale Anzahl angezeigter Einsätze (0 = unbegrenzt) |
| `statistics` | `Array` | Jahresstatistiken mit Kategorie-Daten (für Block-Statistik) |
| `yearGroupsWithStats` | `Array` | Jahre mit eingebetteten Statistiken (für Inline-Anzeige je Jahresgruppe) |
| `showStatistics` | `bool` | Statistik anzeigen (aus FlexForm) |
| `showBlockStatistics` | `bool` | Block-Statistik für aktuelle Filter sichtbar |
| `statisticsPosition` | `string` | Position der Statistik: `'above'` oder `'below'` |
| `enableYearFilter` | `bool` | Jahresauswahl im Frontend anzeigen (aus FlexForm) |
| `selectedYear` | `int \| null` | Aktuell gewähltes Jahr (null = alle Jahre) |
| `availableYears` | `Array<int>` | Array verfügbarer Jahre für Dropdown |
| `showMapView` | `bool` | Kartenansicht anzeigen (aus FlexForm) |
| `mapPosition` | `string` | Position der Karte: `'above'` oder `'below'` |
| `widgetTitle` | `string` | Widget-Überschrift (z. B. „Letzte Einsätze") |
| `detailPageUid` | `int` | Page-UID der Detailansicht (für Links) |
| `listPageUid` | `int` | Page-UID der vollständigen Liste (für Widget-Links) |
| `templateVariant` | `string` | Template-Variante: `'bootstrap'`, `'foundation'`, `'sidebar-bootstrap'`, `'sidebar-foundation'` |
| `settings` | `Array` | FlexForm-Einstellungen (alle Konfigurationsoptionen) |

**Komplexe Datenstrukturen:**

`eventItemsByYear` und `yearGroupsWithStats`:
```
[
  2024 => [
    ['uid' => 123, 'number' => '001', 'title' => 'Wohnhausbrand', ...Event-Properties],
    ['uid' => 124, 'number' => '002', 'title' => 'Verkehrsunfall', ...Event-Properties]
  ],
  2023 => [ ... ]
]
```

`statistics` und eingebettete Statistiken in `yearGroupsWithStats`:
```
[
  'categories' => [
    ['name' => 'Brand', 'count' => 45, 'percentage' => 25.5, 'color' => '#ff0000'],
    ['name' => 'Rettung', 'count' => 78, 'percentage' => 44.3, 'color' => '#00ff00'],
    ...
  ]
]
```

**Event-Objekt Properties:**
Alle `Event`-Objekte enthalten folgende Eigenschaften:
- `uid`, `title`, `description` (HTML), `location`
- `start`, `end` (DateTime), `duration`
- `types` (ObjectStorage<Type>), `categories` (für Statistik)
- `latitude`, `longitude` (für Karte)
- `images` (ObjectStorage<Image>)

---

#### Action: `show` – Event-Detailansicht

| Variable | Typ | Beschreibung |
|---|---|---|
| `event` | `Event` | Das angeforderte Event-Objekt |
| `groupedVehicleData` | `Array` | Fahrzeuge gruppiert nach Brigade und Station |
| `activeStationUid` | `int` | UID der angezeigten Station |
| `defaultStationUid` | `int` | Standard-Station (aus FlexForm) |
| `displayNumber` | `string` | Formatierte Einsatznummer (mit Station-Präfix) |
| `displayPlainNumber` | `string` | Einsatznummer ohne Formatierung |
| `displayStationName` | `string` | Name der angezeigten Station |
| `detailPageUid` | `int` | Aktuelle Page-UID |
| `templateVariant` | `string` | Template-Variante (`'bootstrap'` oder `'foundation'`) |
| `settings` | `Array` | FlexForm-Einstellungen |

**groupedVehicleData Struktur:**
```
[
  [
    'uid' => 1,
    'name' => 'FF Musterstadt',
    'sorting' => 10,
    'stations' => [
      [
        'name' => 'Hauptwache',
        'sorting' => 5,
        'vehicles' => [
          ['name' => 'LF 10', 'image' => ImageObject, 'link' => 'https://example.com'],
          ['name' => 'DLK 23', 'image' => ImageObject, 'link' => 'https://example.com']
        ]
      ],
      [
        'name' => 'Außenstelle Nord',
        'sorting' => 10,
        'vehicles' => [ ... ]
      ]
    ]
  ],
  [ ... weitere Brigaden ... ]
]
```

**Event-Objekt Properties (in Detailansicht):**
- `uid`, `title`, `description` (HTML)
- `location`, `start`, `end` (DateTime), `duration`
- `latitude`, `longitude` (für eventuelle Karteneinbindung)
- `types` (Einsatzarten), `categories`
- `images` (ObjectStorage mit Bildobjekten für Carousel/Lightbox)
- `disableDetail` (bool – Link kann versteckt werden)

---

### Statistik-Plugin (`tx_rescuereports_statistics`)

#### Action: `statistics` – Statistikseite mit Jahres- und Monatsübersicht

| Variable | Typ | Beschreibung |
|---|---|---|
| `statistics` | `Array` | Jahresstatistiken mit Kategorie-Details |
| `monthlyStatistics` | `Array` | Monatsdaten mit SVG-Balkendiagramm |
| `showMonthlyChart` | `bool` | Monatsvergleich anzeigen (aus FlexForm) |
| `stations` | `ObjectStorage<Station>` | Verfügbare Stationen (für Filter) |
| `activeStationUid` | `int` | UID der aktuell gewählten Station |
| `stationUid` | `int` | UID der gefilterten Station (Alias zu `activeStationUid`) |
| `stationName` | `string` | Name der gefilterten Station |
| `showStationFilter` | `bool` | Stationsfilter im Frontend anzeigen (aus FlexForm) |

**statistics (Jahresstatistik):**
```
[
  'categories' => [
    ['name' => 'Brand', 'count' => 120, 'percentage' => 28.5, 'color' => '#ff0000'],
    ['name' => 'Rettung', 'count' => 200, 'percentage' => 47.6, 'color' => '#00ff00'],
    ...
  ]
]
```

**monthlyStatistics (mit SVG-Diagramm):**
```
[
  'svgBarChart' => [
    'viewBox' => '0 0 800 400',
    'gridLines' => [ ... Array von Grid-Linien-Objekten ... ],
    'bars' => [
      [
        'x' => 50,
        'y' => 200,
        'width' => 30,
        'height' => 150,
        'color' => '#ff0000',
        'tooltip' => 'Januar: 45 Einsätze'
      ],
      [ ... weitere Balken für Feb, Mär, etc. ... ]
    ],
    'monthLabels' => [
      ['x' => 65, 'y' => 380, 'text' => 'Jan'],
      ['x' => 115, 'y' => 380, 'text' => 'Feb'],
      [ ... alle 12 Monate ... ]
    ],
    'legend' => [
      ['x' => 650, 'y' => 50, 'color' => '#ff0000', 'label' => 'Brand'],
      [ ... weitere Kategorien ... ]
    ]
  ],
  'mobileRows' => [ ... Mobile-optimierte Darstellung ... ]
]
```

---

### Sidebar-Widget (`tx_rescuereports_sidebar`)

#### Action: `list` – Kompakte Ereignisliste für Sidebar

| Variable | Typ | Beschreibung |
|---|---|---|
| `events` | `ObjectStorage<Event>` | Gefilterte, limitierte Einsätze |
| `activeStationUid` | `int` | UID der Standard-/Aktuellen Station |
| `widgetTitle` | `string` | Widget-Überschrift (z. B. „Letzte Einsätze") |
| `maxCount` | `int` | Maximale Anzahl angezeigter Einsätze (Standard: 5) |
| `detailPageUid` | `int` | Page-UID für „Zum Einsatz"-Link |
| `listPageUid` | `int` | Page-UID für „Alle Einsätze"-Link |
| `templateVariant` | `string` | Template-Variante (`'sidebar-bootstrap'` oder `'sidebar-foundation'`) |
| `settings` | `Array` | FlexForm-Einstellungen |

---

### RSS-Feed (`tx_rescuereports_rss`)

#### Action: `rss` – RSS 2.0 Feed (XML-Format)

| Variable | Typ | Beschreibung |
|---|---|---|
| `events` | `ObjectStorage<Event>` | Gefilterte Einsätze für Feed-Items |
| `feedTitle` | `string` | RSS-Feed-Titel (aus FlexForm oder Fallback) |
| `stationName` | `string` | Station (für Feed-Beschreibung) |
| `detailPageUid` | `int` | Page-UID für Event-Links |

**Verwendete Event-Properties in RSS:**
- `title` – RSS Item-Titel
- `start` – Publikationsdatum (pubDate)
- `description` – RSS Item-Beschreibung (HTML wird mit `<![CDATA[...]]>` eingebunden)
- `uid` – Event-Eindeutigkeit

---

### Partials (Wiederverwendbare Komponenten)

#### `Statistics/PieChart.html` – Tortendiagramm-Statistik

**Eingabe-Variablen:**
| Variable | Typ | Beschreibung |
|---|---|---|
| `statistics` | `Array` | Statistik-Array mit `categories` (siehe `statistics` oben) |
| `title` | `string` | Optionale Überschrift für das Diagramm |

**Rendering:**
- SVG-basiertes, responsives Tortendiagramm
- CSS-Tooltips auf Hover (Kategoriename, Anzahl, Prozentsatz)
- Automatische Farben aus Kategorie-Objekt

---

#### `Map/EventList.html` – OpenStreetMap-Karte

**Eingabe-Variablen:**
| Variable | Typ | Beschreibung |
|---|---|---|
| `events` | `ObjectStorage<Event>` | Einsätze mit Koordinaten (`latitude`, `longitude`) |
| `settings` | `Array` | Enthält `mapHeight`, `mapZoom` aus FlexForm |

**Rendering:**
- Leaflet.js 1.9.4 (CDN)
- Marker für jeden Event mit Koordinaten
- Popup mit Event-Titel und Datum

---

#### `Deployment/Item.html` – Fahrzeug-Anzeige in Detailansicht

**Eingabe-Variablen:**
| Variable | Typ | Beschreibung |
|---|---|---|
| `vehicle` | `Vehicle` | Fahrzeug-Objekt mit `name`, `image`, `link` |

---

## Naming-Konventionen für Template-Variablen

Die Extension folgt konsistenten Namenskonventionen:

| Muster | Beispiele | Bedeutung |
|---|---|---|
| `enable*` oder `show*` | `enableSearch`, `showStatistics`, `showMapView` | Boolean Feature-Flags |
| `*Uid` | `activeStationUid`, `detailPageUid`, `listPageUid` | TYPO3 UID-Referenzen |
| `*Name` oder `active*Name` | `stationName`, `activeStationName` | Anzeigetexte |
| `*Str` | `dateFromStr`, `dateToStr` | String-Varianten von Daten |
| `display*` | `displayNumber`, `displayStationName` | Formatierte Ausgabevariablen |
| `default*` | `defaultStationUid` | Vorbelegte Standard-Werte |
| `selected*` | `selectedYear` | Benutzer-Auswahl (Filter) |

---

## Bildergalerie & Lightbox

### Bootstrap-Template

Die Detailansicht verwendet einen **Bootstrap-Carousel** als Vorschau-Slider. Ein Klick auf ein Bild öffnet die Vollansicht über **GLightbox** (kein jQuery, kein Bootstrap-Modal). Navigation per Mausklick, Tastatur und Touch/Swipe.

GLightbox wird automatisch über jsDelivr-CDN geladen:
```
https://cdn.jsdelivr.net/npm/glightbox@3/dist/css/glightbox.min.css
https://cdn.jsdelivr.net/npm/glightbox@3/dist/js/glightbox.min.js
```

Für Produktionsumgebungen ohne CDN-Zugriff können die Dateien lokal abgelegt und der Pfad in `EventController::showAction()` angepasst werden.

### Foundation-Template

Die Detailansicht verwendet **Owl Carousel** (jQuery-basiert) als Slider. Ein Klick auf ein Bild öffnet GLightbox (identische Lightbox wie Bootstrap-Template). Voraussetzung: jQuery und Owl Carousel müssen im TYPO3-Seitenlayout geladen sein.

---

## Karte (OpenStreetMap)

Erfordert Koordinaten (`lat`/`lng`) im Einsatzdatensatz. Die Karte wird via **Leaflet.js 1.9.4** (CDN) eingebunden. Das Partial `Map/EventList.html` zeigt alle sichtbaren Einsätze als Marker.

Position (ober-/unterhalb der Liste) und Höhe/Zoomstufe sind über FlexForm konfigurierbar.

---

## Domänenmodell (Überblick)

| Tabelle | Beschreibung |
|---|---|
| `tx_rescuereports_domain_model_event` | Einsätze (Titel, Beschreibung, Ort, Koordinaten, Datum, Bilder, Fahrzeuge) |
| `tx_rescuereports_domain_model_station` | Ortsfeuerwehren (Name, Kürzel, Fahrzeuge) |
| `tx_rescuereports_domain_model_brigade` | Feuerwehren / BOS-Organisationen |
| `tx_rescuereports_domain_model_car` | Fahrzeugtypen (Name, Organisation) |
| `tx_rescuereports_domain_model_vehicle` | Fahrzeuge (Name, Bild, Verlinkung) |
| `tx_rescuereports_domain_model_category` | Einsatzkategorien (Titel, Farbe für Statistik) |
| `tx_rescuereports_domain_model_type` | Einsatzarten (Titel, Kategorie) |
| `tx_rescuereports_domain_model_snippet` | Textbausteine für das Backend |

---

## Volltextsuche

Die Suche durchsucht folgende Felder: Titel, Beschreibung, Einsatzort, Einsatzart-Bezeichnung, Einsatznummer. Optional kombinierbar mit Stationsfilter und Datumsbereich.

---

## Technische Hinweise

- **Einsatznummern** werden dynamisch per Station und Jahr berechnet (laufende Nummer `001`, `002`, …) und können stationsspezifische Präfixe tragen.
- **Slug-Routing** für sprechende URLs der Detailansicht ist vorbereitet (mit `slug` und `slug_source` Feldern).
- **RSS-Feed** wird über einen eigenen `typeNum = 100` ausgeliefert; Content-Type `application/rss+xml`.
- **TypoScript-Konfiguration** wird automatisch über TYPO3 13+ Site Sets geladen (`Configuration/Sets/RescueReports/`).
- **Datenbankschema**: Vollständig kompatibel mit TYPO3 13 und 14, mit Unterstützung für:
  - Versionierung (Workspace-Management)
  - Mehrsprachigkeit (L10n)
  - Zeitbasierte Zugriffskontrolle (starttime/endtime)

## To Do

-

## Hinweis zu DataHandler Hooks

Die Extension verwendet aktuell bewusst DataHandler Hooks
(processDatamapClass), da die Migration auf PSR-14 Listener
in TYPO3 13/14 noch nicht stabil funktioniert.

Betroffene Funktionalität:
- automatische Fahrzeugbenennung
- Slug-Generierung für Events

Eine spätere Migration ist geplant.
