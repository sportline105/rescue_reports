INSERT INTO tx_rescuereports_domain_model_organisation (uid, pid, name, abbreviation) VALUES
(1, 129, 'Freiwillige Feuerwehr', 'FFW'),
(2, 129, 'Berufsfeuerwehr', 'BF'),
(3, 129, 'Werkfeuerwehr', 'WF'),
(4, 129, 'Deutsches Rotes Kreuz', 'DRK'),
(5, 129, 'Johanniter-Unfall-Hilfe', 'JUH'),
(6, 129, 'Malteser Hilfsdienst', 'MHD'),
(7, 129, 'Technisches Hilfswerk', 'THW'),
(8, 129, 'Arbeiter-Samariter-Bund', 'ASB'),
(9, 129, 'Bergwacht', 'BW'),
(10, 129, 'Deutsche Lebens-Rettungs-Gesellschaft', 'DLRG'),
(11, 129, 'Polizei', 'POL');

-- Beispiel-Fahrzeuge
INSERT INTO tx_rescuereports_domain_model_car (uid, pid, name, organization) VALUES
(1, 129, 'ELW 1', 1),
(2, 129, 'TLF 3000', 1),
(3, 129, 'DLAK 23/12', 1),
(4, 129, 'RW 1', 1),
(5, 129, 'GW-L2', 1),
(6, 129, 'LF 8/6', 1),
(7, 129, 'MTF', 1),
(8, 129, 'HLF 20', 1),
(9, 129, 'TSF-W', 1),
(10, 129, 'LF 10', 1),
(11, 129, 'RTW', 4),
(12, 129, 'KTW', 4),
(13, 129, 'NEF', 4),
(14, 129, 'GW-G', 1),
(15, 129, 'MLW 5', 7),
(16, 129, 'GKW', 7),
(17, 129, 'FustW', 11),
(18, 129, 'MTW Polizei', 11);
