INSERT INTO tx_rescuereports_domain_model_organisation (uid, pid, name, abbreviation) VALUES
(1, yourPID, 'Freiwillige Feuerwehr', 'FFW'),
(2, yourPID, 'Berufsfeuerwehr', 'BF'),
(3, yourPID, 'Werkfeuerwehr', 'WF'),
(4, yourPID, 'Deutsches Rotes Kreuz', 'DRK'),
(5, yourPID, 'Johanniter-Unfall-Hilfe', 'JUH'),
(6, yourPID, 'Malteser Hilfsdienst', 'MHD'),
(7, yourPID, 'Technisches Hilfswerk', 'THW'),
(8, yourPID, 'Arbeiter-Samariter-Bund', 'ASB'),
(9, yourPID, 'Bergwacht', 'BW'),
(10, yourPID, 'Deutsche Lebens-Rettungs-Gesellschaft', 'DLRG'),
(11, yourPID, 'Polizei', 'POL');

-- Beispiel-Fahrzeuge
INSERT INTO tx_rescuereports_domain_model_car (uid, pid, name, organization) VALUES
(1, yourPID, 'ELW 1', 1),
(2, yourPID, 'TLF 3000', 1),
(3, yourPID, 'DLAK 23/12', 1),
(4, yourPID, 'RW 1', 1),
(5, yourPID, 'GW-L2', 1),
(6, yourPID, 'LF 8/6', 1),
(7, yourPID, 'MTF', 1),
(8, yourPID, 'HLF 20', 1),
(9, yourPID, 'TSF-W', 1),
(10, yourPID, 'LF 10', 1),
(11, yourPID, 'RTW', 4),
(12, yourPID, 'KTW', 4),
(13, yourPID, 'NEF', 4),
(14, yourPID, 'GW-G', 1),
(15, yourPID, 'MLW 5', 7),
(16, yourPID, 'GKW', 7),
(17, yourPID, 'FustW', 11),
(18, yourPID, 'MTW Polizei', 11);
