CREATE TABLE IF NOT EXISTS tooted (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nimetus VARCHAR(255) NOT NULL,
    kirjeldus TEXT,
    hind DECIMAL(10,2) NOT NULL,
    kategooria VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS galerii (
    id INT AUTO_INCREMENT PRIMARY KEY,
    failinimi VARCHAR(255) NOT NULL,
    kirjeldus TEXT,
    kuupaev DATETIME NOT NULL
);

INSERT INTO tooted (nimetus, kirjeldus, hind, kategooria) VALUES
('Õunad', 'Värsked punased õunad', 2.50, 'Puu- ja köögiviljad'),
('Banaanid', 'Kollased küpsed banaanid', 1.80, 'Puu- ja köögiviljad'),
('Tomatid', 'Mahlased tomatid', 3.20, 'Puu- ja köögiviljad'),
('Piim', 'Täispiim 1L', 1.20, 'Piimatooted'),
('Juust', 'Eesti juust 200g', 3.50, 'Piimatooted'),
('Jogurt', 'Maasikajogurt', 0.90, 'Piimatooted'),
('Kanafilee', 'Värske kanafilee 500g', 5.50, 'Lihatooted'),
('Sink', 'Keeduvorst 300g', 2.80, 'Lihatooted'),
('Saiake', 'Värske saiake', 0.50, 'Pagaritoodet'),
('Leib', 'Rukkileib', 1.50, 'Pagaritoodet'),
('Mahl', 'Õunamahl 1L', 2.00, 'Joogid'),
('Vesi', 'Mineraalvesi 1.5L', 0.80, 'Joogid');
