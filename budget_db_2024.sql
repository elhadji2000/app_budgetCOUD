-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 29 août 2026 à 20:52
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `budget_db_2024`
--

-- --------------------------------------------------------

--
-- Structure de la table `bud_bordereaux`
--

DROP TABLE IF EXISTS `bud_bordereaux`;
CREATE TABLE IF NOT EXISTS `bud_bordereaux` (
  `idBordereau` int NOT NULL AUTO_INCREMENT,
  `numeroBordereau` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idUser` int NOT NULL,
  PRIMARY KEY (`idBordereau`),
  UNIQUE KEY `uk_numero_bordereau` (`numeroBordereau`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_bordereaux`
--

INSERT INTO `bud_bordereaux` (`idBordereau`, `numeroBordereau`, `dateSys`, `idUser`) VALUES
(2, 'BORD26-0002', '2026-06-11 18:25:38', 1),
(6, 'BORD26-0006', '2026-06-12 11:16:30', 1),
(8, 'BORD26-0008', '2026-06-12 11:24:57', 1);

-- --------------------------------------------------------

--
-- Structure de la table `bud_compte`
--

DROP TABLE IF EXISTS `bud_compte`;
CREATE TABLE IF NOT EXISTS `bud_compte` (
  `idCompte` int NOT NULL AUTO_INCREMENT,
  `numCompte` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idCp` int DEFAULT NULL,
  PRIMARY KEY (`idCompte`),
  UNIQUE KEY `numCompte` (`numCompte`),
  KEY `idCp` (`idCp`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_compte`
--

INSERT INTO `bud_compte` (`idCompte`, `numCompte`, `code`, `dateSys`, `libelle`, `idCp`) VALUES
(1, '141', 'SE', '2025-04-16 08:54:17', 'Subvention d\'Equipement', 1),
(2, '162', 'EDEC', '2025-04-16 08:54:17', 'Emprunts et dettes aupres des etablissements de credits', 2),
(3, '23131', 'RBE', '2025-04-16 08:54:17', 'Refection Batiment d\'Exploitation', 3),
(4, '2340', 'IT', '2025-04-16 08:54:17', 'Installation Telephonique', 3),
(5, '2381', 'TAI', '2025-04-16 08:54:17', 'Travaux Amenagement Installations', 3),
(6, '2390', 'BEC', '2025-04-16 08:54:17', 'Batiments en cours', 3),
(7, '2410', 'MOA', '2025-04-16 08:54:17', 'Machine Outillage Atelier', 4),
(8, '2415', 'MCR', '2025-04-16 08:54:17', 'Materiel de Cuisine et Restaurant', 4),
(9, '2416', 'MTAF', '2025-04-16 08:54:17', 'Materiel Thermique Appareil Froid', 4),
(10, '2417', 'MOA', '2025-04-16 08:54:17', 'Materiel et Outillage Atelier', 4),
(11, '2418', 'MI', '2025-04-16 08:54:17', 'Materiel Incendie', 4),
(12, '2441', 'MMB', '2025-04-16 08:54:17', 'Materiel Mobilier de Bureau', 4),
(13, '2442', 'MInf', '2025-04-16 08:54:17', 'Materiel Informatique', 4),
(14, '2448', 'MMC', '2025-04-16 08:54:17', 'Materiel Mobilier des Cites', 4),
(15, '2450', 'MT', '2025-04-16 08:54:17', 'Materiel de Transports', 4),
(16, '2481', 'MSM', '2025-04-16 08:54:17', 'Materiel Services Medicaux', 4),
(17, '2482', 'MAC', '2025-04-16 08:54:17', 'Materiel Activites Culturelles', 4),
(18, '2483', 'MMFYU', '2025-04-16 08:54:17', 'Materiel Mobilier Foyer Univers', 4),
(19, '2752', 'DCElec', '2025-04-16 08:54:17', 'Depot et Cautionnement Electrique', 5),
(20, '2753', 'DCEau', '2025-04-16 08:54:17', 'Depot et Cautionnement Eau', 5),
(21, '2758', 'ADC', '2025-04-16 08:54:17', 'Autres Depots et Cautionnement', 5),
(22, '47111', '', '2025-04-16 08:54:17', 'Vente de Repas Subv', 6),
(23, '47112', '', '2025-04-16 08:54:17', 'Vente Petit Dejeuner Subv', 6),
(24, '4721', '', '2025-04-16 08:54:17', 'Vente Tickets/Consultation', 6),
(25, '4722', '', '2025-04-16 08:54:17', 'Ventes cartes transport USSEIN', 6),
(26, '4723', '', '2025-04-16 08:54:17', 'Vente de medicaments', 6),
(27, '4724', '', '2025-04-16 08:54:17', 'Vente du Guide des oeuvres', 6),
(28, '4731', '', '2025-04-16 08:54:17', 'Loyers Subventionnes', 6),
(29, '4732', '', '2025-04-16 08:54:17', 'Caution/Loyer', 6),
(30, '604111', 'Mac', '2025-04-16 08:54:17', 'Maconnerie', 7),
(31, '604112', 'DP', '2025-04-16 08:54:17', 'Droguerie - Peinture', 7),
(32, '604113', 'PO', '2025-04-16 08:54:17', 'Petit Outillage', 7),
(33, '604114', 'PS', '2025-04-16 08:54:17', 'Plomberie Sanitaire', 7),
(34, '604115', 'PME', '2025-04-16 08:54:17', 'Petit Materiel Electrique', 7),
(35, '604116', 'Qinq', '2025-04-16 08:54:17', 'Quincaillerie', 7),
(36, '604117', 'MB', '2025-04-16 08:54:17', 'Menuiserie Bois', 7),
(37, '604118', 'MM', '2025-04-16 08:54:17', 'Menuiserie Metallique', 7),
(38, '604119', 'FC', '2025-04-16 08:54:17', 'Fournitures Consommees', 7),
(39, '604121', 'PMCR', '2025-04-16 08:54:17', 'Petits Materiels Cuisine Restaurant', 7),
(40, '604122', 'LDC', '2025-04-16 08:54:17', 'Literies Draps Couverture', 7),
(41, '604123', 'PML', '2025-04-16 08:54:17', 'Petits Materiels Ludiques', 7),
(42, '604124', 'PDA', '2025-04-16 08:54:17', 'Pieces Detachees Auto', 7),
(43, '604125', 'LTM', '2025-04-16 08:54:17', 'Linge Tissus Mercerie', 7),
(44, '604126', 'VT', '2025-04-16 08:54:17', 'Vetement de Travail', 7),
(45, '604191', 'PPE', '2025-04-16 08:54:17', 'Produits Pharmaceutiques Etudiants', 7),
(46, '604192', 'PPP', '2025-04-16 08:54:17', 'Produits Pharmaceutiques Personnel', 7),
(47, '604193', 'PD', '2025-04-16 08:54:17', 'Produits Dentaires', 7),
(48, '604194', 'PM', '2025-04-16 08:54:17', 'Produits Medicaux', 7),
(49, '604195', 'PMM', '2025-04-16 08:54:17', 'Petit Materiel Medical', 7),
(50, '604196', 'PL', '2025-04-16 08:54:17', 'Produit Laboratoire', 7),
(51, '60421', 'CL', '2025-04-16 08:54:17', 'Carburant Lubrifiant', 7),
(52, '6043', 'PE', '2025-04-16 08:54:17', 'Produits d\'Entretien', 7),
(53, '60472', 'PMI', '2025-04-16 08:54:17', 'Petits Materiels Informatiques', 7),
(54, '60473', 'Imp', '2025-04-16 08:54:17', 'Imprimes', 7),
(55, '60478', 'AFB', '2025-04-16 08:54:17', 'Autres Fournitures de Bureau', 7),
(56, '6051', 'Eau', '2025-04-16 08:54:17', 'Eau', 7),
(57, '6052', 'Elec', '2025-04-16 08:54:17', 'Electricite', 7),
(58, '614', 'TP', '2025-04-16 08:54:17', 'Transport Personnel', 8),
(59, '616', 'AP', '2025-04-16 08:54:17', 'Affranchissements Postaux', 8),
(60, '618', 'AFT', '2025-04-16 08:54:17', 'Autres Frais de Transport', 8),
(61, '6211', 'STR', '2025-04-16 08:54:17', 'Sous-Traitance Restauration', 9),
(62, '6222', 'LB', '2025-04-16 08:54:17', 'Loyer Batiment', 9),
(63, '623', 'RCB', '2025-04-16 08:54:17', 'Redevances de credit-bail et contrats assimiles', 9),
(64, '62411', 'ECJ', '2025-04-16 08:54:17', 'Entretien Cours et Jardin', 9),
(65, '62412', 'EC', '2025-04-16 08:54:17', 'Entretien Construction', 9),
(66, '62413', 'DC', '2025-04-16 08:54:17', 'Desinfection Cites', 9),
(67, '62414', 'EDO', '2025-04-16 08:54:17', 'Entretien Decharge Ordure', 9),
(68, '624211', 'EPT', '2025-04-16 08:54:17', 'Entretien Poste de Transformation', 9),
(69, '624212', 'EGE', '2025-04-16 08:54:17', 'Entretien Groupe Electrogene', 9),
(70, '624213', 'EPE', '2025-04-16 08:54:17', 'Entretien Pompe a Eau', 9),
(71, '624214', 'EMaC', '2025-04-16 08:54:17', 'Entretien Materiel Cites', 9),
(72, '624215', 'EMoC', '2025-04-16 08:54:17', 'Entretien Mobilier Cites', 9),
(73, '624216', 'EO', '2025-04-16 08:54:17', 'Entretien Outillage', 9),
(74, '624217', 'EAF', '2025-04-16 08:54:17', 'Entretien Appareil Froid', 9),
(75, '624218', 'ET', '2025-04-16 08:54:17', 'Entretien Telephonique', 9),
(76, '624221', 'EMB', '2025-04-16 08:54:17', 'Entretien Materiel de Bureau', 9),
(77, '624222', 'EMInf', '2025-04-16 08:54:17', 'Entretien Materiel Informatique', 9),
(78, '624223', 'EMC', '2025-04-16 08:54:17', 'Entretien Materiel Cuisine', 9),
(79, '624224', 'EMInc', '2025-04-16 08:54:17', 'Entretien Materiel Incendie', 9),
(80, '624225', 'EV', '2025-04-16 08:54:17', 'Entretien Vehicule', 9),
(81, '624226', 'EMM', '2025-04-16 08:54:17', 'Entretien Materiel Medicale', 9),
(82, '62481', 'Blanc', '2025-04-16 08:54:17', 'Blanchissage', 9),
(83, '6252', 'AV', '2025-04-16 08:54:17', 'Assurance Vehicule', 9),
(84, '6258', 'AI', '2025-04-16 08:54:17', 'Assurance Incendie', 9),
(85, '6261', 'ER', '2025-04-16 08:54:17', 'Etudes et Recherches', 9),
(86, '62650', 'DG', '2025-04-16 08:54:17', 'Documentation Generale', 9),
(87, '6270', 'AAC', '2025-04-16 08:54:17', 'Autres activites de COM', 9),
(88, '6271', 'PI', '2025-04-16 08:54:17', 'Publicite Insertion', 9),
(89, '6277', 'CSC', '2025-04-16 08:54:17', 'Frais de colloques, seminaires, conferences', 9),
(90, '62781', 'AAC', '2025-04-16 08:54:17', 'Autres Activites de Communication', 9),
(91, '6281', 'CT', '2025-04-16 08:54:17', 'Communication Telephonique', 9),
(92, '6283', 'Fax', '2025-04-16 08:54:17', 'Fax', 9),
(93, '6288', 'PRP', '2025-04-16 08:54:17', 'Autres Charges de Publicite et Relations Publiques', 9),
(94, '6324', 'HI', '2025-04-16 08:54:17', 'Honoraires Internes', 10),
(95, '6325', 'FA', '2025-04-16 08:54:17', 'Frais dactes', 10),
(96, '632801', 'FR', '2025-04-16 08:54:17', 'Frais de Representation', 10),
(97, '633', 'FFP', '2025-04-16 08:54:17', 'Frais Formation Personnel', 10),
(98, '635811', 'RFL', '2025-04-16 08:54:17', 'Remboursement Frais Lunettes', 10),
(99, '635812', 'ACE', '2025-04-16 08:54:17', 'Analyse Consultations Etudiants', 10),
(100, '635813', 'SEE', '2025-04-16 08:54:17', 'Soins Externes Etudiants', 10),
(101, '635814', 'HE', '2025-04-16 08:54:17', 'Hospitalisation Etudiants', 10),
(102, '635815', 'SA', '2025-04-16 08:54:17', 'Subventions Accordees', 10),
(103, '63830', 'AS', '2025-04-16 08:54:17', 'Activites sportives', 10),
(104, '63831', 'AC', '2025-04-16 08:54:17', 'Activites Culturelles', 10),
(105, '63832', 'Rec', '2025-04-16 08:54:17', 'Reception', 10),
(106, '6384', 'Mis', '2025-04-16 08:54:17', 'Mission', 10),
(107, '6462', 'DE', '2025-04-16 08:54:17', 'Droits dEnregistrement', 11),
(108, '658', 'CD', '2025-04-16 08:54:17', 'Charges Diverses', 12),
(109, '6581', 'IF', '2025-04-16 08:54:17', 'Indemnites de fonction et autres remunerations d\'administrateurs', 12),
(110, '6582', 'SAS', '2025-04-16 08:54:17', 'Secours Activites Sociales', 12),
(111, '6584', 'ISR', '2025-04-16 08:54:17', 'Indemnisation sous-traitants restaurants', 12),
(112, '6598', 'ACP', '2025-04-16 08:54:17', 'Autres Charges Provisionnees', 12),
(113, '66111', 'RPP', '2025-04-16 08:54:17', 'Remuneration Personnel Permanent', 13),
(114, '66112', 'RPT', '2025-04-16 08:54:17', 'Remuneration Personnel Temporaire', 13),
(115, '6617', 'MT', '2025-04-16 08:54:17', 'Medaille de Travail', 13),
(116, '6638', 'IA', '2025-04-16 08:54:17', 'Indemnites Avantages', 13),
(117, '66841', 'HP', '2025-04-16 08:54:17', 'Hospitalisation Personnel', 13),
(118, '66842', 'ACP', '2025-04-16 08:54:17', 'Analyse Consultation Personnel', 13),
(119, '66843', 'AP', '2025-04-16 08:54:17', 'Accouchement Personnel', 13),
(120, '66844', 'SEP', '2025-04-16 08:54:17', 'Soins Externes Personnel', 13),
(121, '671', 'CF', '2025-04-16 08:54:17', 'Charges Financieres', 14),
(122, '68131', 'DAMCu', '2025-04-16 08:54:17', 'Dotation aux Amortissements Materiel Cuisine', 15),
(123, '68132', 'DAME', '2025-04-16 08:54:17', 'Dotation aux Amortissements Materiel d\'Exploitation', 15),
(124, '68133', 'DAMB', '2025-04-16 08:54:17', 'Dotation aux Amortissements Materiel Bureau', 15),
(125, '68134', 'DAMCi', '2025-04-16 08:54:17', 'Dotation aux Amortissements Materiel Cites', 15),
(126, '68135', 'DAAI', '2025-04-16 08:54:17', 'Dotation aux Amortissements Ag Installation', 15),
(127, '68136', 'DAMT', '2025-04-16 08:54:17', 'Dotation aux Amortissements Materiel Transport', 15),
(128, '68137', 'DACR', '2025-04-16 08:54:17', 'Dotation aux Amortissements Charges a Retablir', 15),
(129, '70611', 'VRS', '2025-04-16 08:54:17', 'Vente de Repas Subventionnes', 16),
(130, '70612', 'VPDS', '2025-04-16 08:54:17', 'Vente de Petit Dejeuner Subv', 16),
(131, '70613', 'VRE', '2025-04-16 08:54:17', 'Vente de Repas Express', 16),
(132, '70614', 'LS', '2025-04-16 08:54:17', 'Loyers Subventionnes', 16),
(133, '70615', 'LP', '2025-04-16 08:54:17', 'Loyers \"passager\"', 16),
(134, '70616', 'VM', '2025-04-16 08:54:17', 'Vente de Medicaments', 16),
(135, '70617', 'CE', '2025-04-16 08:54:17', 'Consultations externes', 16),
(136, '70618', 'VTC', '2025-04-16 08:54:17', 'Vente Tickets / Consultation', 16),
(137, '706622', 'VP', '2025-04-16 08:54:17', 'Vente de Protheses', 16),
(138, '707121', 'PE', '2025-04-16 08:54:17', 'Part Electricite', 16),
(139, '70731', 'LPP', '2025-04-16 08:54:17', 'Location Panneaux Pub', 16),
(140, '70732', 'LSST', '2025-04-16 08:54:17', 'Location Terrain de Foot', 16),
(141, '70733', 'LSS', '2025-04-16 08:54:17', 'Location Salle de Spectacle', 16),
(142, '70734', 'LC', '2025-04-16 08:54:17', 'Location \"Cantines\"', 16),
(143, '70735', 'LCC', '2025-04-16 08:54:17', 'Location Centre Commercial', 16),
(144, '70781', 'IC', '2025-04-16 08:54:17', 'Imprimerie du COUD', 16),
(145, '70782', 'VAO', '2025-04-16 08:54:17', 'Vente de dossiers d\'appel d\'offres', 16),
(146, '70783', 'VGBO', '2025-04-16 08:54:17', 'Vente Guides du Benefice des Oeuvres', 16),
(147, '70784', 'USS', '2025-04-16 08:54:17', 'Vente Cartes Transport USSEIN', 16),
(148, '7181', 'CES', '2025-04-16 08:54:17', 'Contribution Etat du Senegal', 17),
(149, '7182', 'CAEO', '2025-04-16 08:54:17', 'Contribution Autres Etats et Organismes', 17),
(150, '7183', 'CP', '2025-04-16 08:54:17', 'Contributions Personneelles', 17),
(151, '7184', 'RCE', '2025-04-16 08:54:17', 'Rallonge Contribution Etat du Senegal', 17),
(152, '754', 'PRSA', '2025-04-16 08:54:17', 'Produits Resultant de Subventions Amorties', 18),
(153, '7583', 'PD', '2025-04-16 08:54:17', 'Autres Produits Divers', 18),
(154, '7584', 'RIT', '2025-04-16 08:54:17', 'Retenus Imputables a des Tiers', 18),
(155, '822', 'CIC', '2025-04-16 08:54:17', 'Produits des Cessions d\'Immobilisations Corporelles', 19),
(156, '8651', 'RSI', '2025-04-16 08:54:17', 'Reprise Subvention dInvestiisement', 20);

-- --------------------------------------------------------

--
-- Structure de la table `bud_comptep`
--

DROP TABLE IF EXISTS `bud_comptep`;
CREATE TABLE IF NOT EXISTS `bud_comptep` (
  `idCp` int NOT NULL AUTO_INCREMENT,
  `numCp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `nature` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'fonctionnement',
  PRIMARY KEY (`idCp`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_comptep`
--

INSERT INTO `bud_comptep` (`idCp`, `numCp`, `libelle`, `dateSys`, `nature`, `type`) VALUES
(1, '141', 'Subvention d\'Equipement', '2025-04-16 08:39:53', 'ressource', 'investissement'),
(2, '16', 'Emprunts et dettes assimiles', '2025-04-16 08:39:53', 'emploi', 'investissement'),
(3, '23', 'Batiments Administratifs', '2025-04-16 08:39:53', 'emploi', 'investissement'),
(4, '24', 'Materiel et Outillage', '2025-04-16 08:39:53', 'emploi', 'investissement'),
(5, '275', 'Depot et Cautionnement', '2025-04-16 08:39:53', 'emploi', 'investissement'),
(6, '47', 'Comptes dAttente', '2025-04-16 08:39:53', 'produit', 'fonctionnement'),
(7, '60', 'Achats', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(8, '61', 'Transports', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(9, '62', 'Services Exterieurs A', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(10, '63', 'Services Exterieurs B', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(11, '64', 'Impots et Taxes', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(12, '65', 'Autres Charges', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(13, '66', 'Charges de Personnel', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(14, '67', 'Frais Financiers', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(15, '68', 'Dotation aux Amortissements', '2025-04-16 08:39:53', 'charge', 'fonctionnement'),
(16, '70', 'Ventes', '2025-04-16 08:39:53', 'produit', 'fonctionnement'),
(17, '71', 'Subvention d\'Exploitation', '2025-04-16 08:39:53', 'produit', 'fonctionnement'),
(18, '75', 'Autres Produits', '2025-04-16 08:39:53', 'produit', 'fonctionnement'),
(19, '82', 'Produits de Cessions', '2025-04-16 08:39:53', 'produit', 'fonctionnement'),
(20, '865', 'Reprise Subvention dInvestissement', '2025-04-16 08:39:53', 'produit', 'fonctionnement');

-- --------------------------------------------------------

--
-- Structure de la table `bud_dotations`
--

DROP TABLE IF EXISTS `bud_dotations`;
CREATE TABLE IF NOT EXISTS `bud_dotations` (
  `idDot` int NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `volume` decimal(13,2) DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `an` year DEFAULT NULL,
  `idUser` int DEFAULT NULL,
  `idCompte` int DEFAULT NULL,
  PRIMARY KEY (`idDot`),
  KEY `idUser` (`idUser`),
  KEY `idCompte` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_dotations`
--

INSERT INTO `bud_dotations` (`idDot`, `date`, `volume`, `dateSys`, `type`, `an`, `idUser`, `idCompte`) VALUES
(12, '2026-03-25', 2000000.00, '2026-03-25 12:56:53', 'initiale', '2026', 1, 5),
(13, '2026-03-26', 5400000.00, '2026-03-26 17:43:54', 'initiale', '2026', 1, 2),
(14, '2026-03-26', 1500000.00, '2026-03-26 21:07:57', 'initiale', '2026', 1, 4),
(15, '2026-03-27', 4000000.00, '2026-03-27 09:38:04', 'initiale', '2026', 1, 143),
(16, '2026-03-27', -1000000.00, '2026-03-27 11:16:33', 'remanier', '2026', 1, 2),
(17, '2025-01-15', 1000000.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 110),
(18, '2025-01-15', 1000002.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 111),
(19, '2025-01-15', 1000003.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 112),
(20, '2025-01-15', 1000004.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 113),
(21, '2025-01-15', 1000005.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 114),
(22, '2025-01-15', 1000006.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 115),
(23, '2025-01-15', 1000007.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 116),
(24, '2025-01-15', 1000008.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 117),
(25, '2025-01-15', 1000009.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 118),
(26, '2025-01-15', 1000010.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 119),
(27, '2025-01-15', 1000011.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 120),
(28, '2025-01-15', 1000012.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 121),
(29, '2025-01-15', 1000013.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 122),
(30, '2025-01-15', 1000014.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 123),
(31, '2025-01-15', 1000015.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 124),
(32, '2025-01-15', 1000016.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 125),
(33, '2025-01-15', 1000017.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 126),
(34, '2025-01-15', 1000018.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 127),
(35, '2025-01-15', 1000019.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 128),
(36, '2025-01-15', 1000020.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 129),
(37, '2025-01-15', 1000021.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 130),
(38, '2025-01-15', 1000022.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 131),
(39, '2025-01-15', 1000023.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 132),
(40, '2025-01-15', 1000024.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 133),
(41, '2025-01-15', 1000025.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 134),
(42, '2025-01-15', 1000026.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 135),
(43, '2025-01-15', 1000027.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 136),
(44, '2025-01-15', 1000028.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 137),
(45, '2025-01-15', 1000029.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 138),
(46, '2025-01-15', 1000030.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 139),
(47, '2025-01-15', 1000031.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 140),
(48, '2025-01-15', 1000032.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 141),
(49, '2025-01-15', 1000033.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 142),
(50, '2025-01-15', 1000035.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 144),
(51, '2025-01-15', 1000036.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 145),
(52, '2025-01-15', 1000037.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 146),
(53, '2025-01-15', 1000038.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 147),
(54, '2025-01-15', 1000039.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 148),
(55, '2025-01-15', 1000040.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 149),
(56, '2025-01-15', 1000041.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 150),
(57, '2025-01-15', 1000042.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 151),
(58, '2025-01-15', 1000043.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 152),
(59, '2025-01-15', 1000044.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 153),
(60, '2025-01-15', 1000045.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 154),
(61, '2025-01-15', 1000046.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 155),
(62, '2025-01-15', 1000047.00, '2026-04-14 09:29:45', 'initiale', '2026', 1, 156);

-- --------------------------------------------------------

--
-- Structure de la table `bud_engagements`
--

DROP TABLE IF EXISTS `bud_engagements`;
CREATE TABLE IF NOT EXISTS `bud_engagements` (
  `idEng` int NOT NULL AUTO_INCREMENT,
  `dateEng` date DEFAULT NULL,
  `type_eng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `objet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `bc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(13,2) DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idFourn` int DEFAULT NULL,
  `idCompte` int DEFAULT NULL,
  `idUser` int NOT NULL,
  PRIMARY KEY (`idEng`),
  KEY `idFourn` (`idFourn`),
  KEY `idCompte` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_engagements`
--

INSERT INTO `bud_engagements` (`idEng`, `dateEng`, `type_eng`, `objet`, `bc`, `montant`, `dateSys`, `idFourn`, `idCompte`, `idUser`) VALUES
(6, '2026-03-26', 'Informatique', 'a regler', 'rrc', 400000.00, '2026-03-26 18:30:16', 1, 2, 0),
(7, '2026-03-26', 'DSAS', 'AA', 'BC', 1000000.00, '2026-03-26 20:19:00', 1, 2, 0),
(8, '2026-03-26', 'DI', 'VV', 'BC', 1000000.00, '2026-03-26 21:09:19', 1, 4, 0),
(10, '2026-03-26', 'DSAS', 'NN', 'BC', 2000000.00, '2026-03-27 11:32:03', 1, 2, 0),
(11, '2026-03-28', 'DSAS', 'arrierer', 'BC', 3000000.00, '2026-03-28 22:28:58', 2, 2, 0),
(12, '2026-04-14', 'Biens et services', 'travaux', NULL, 1000000.00, '2026-04-14 09:02:46', 2, 5, 0),
(13, '2026-05-13', 'Biens et services', 'travaux', NULL, 2000000.00, '2026-05-13 10:48:37', 3, 5, 0);

-- --------------------------------------------------------

--
-- Structure de la table `bud_engagements_temp`
--

DROP TABLE IF EXISTS `bud_engagements_temp`;
CREATE TABLE IF NOT EXISTS `bud_engagements_temp` (
  `idEng` int NOT NULL AUTO_INCREMENT,
  `dateEng` date DEFAULT NULL,
  `type_eng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `objet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `bc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(13,2) DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idFourn` int DEFAULT NULL,
  `idCompte` int DEFAULT NULL,
  `idUser` int NOT NULL,
  PRIMARY KEY (`idEng`),
  KEY `idFourn` (`idFourn`),
  KEY `idCompte` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bud_fournisseur`
--

DROP TABLE IF EXISTS `bud_fournisseur`;
CREATE TABLE IF NOT EXISTS `bud_fournisseur` (
  `idFourn` int NOT NULL AUTO_INCREMENT,
  `numFourn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adresse` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nature` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ninea` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rccm` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observations` text COLLATE utf8mb4_general_ci,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `contact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idFourn`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_fournisseur`
--

INSERT INTO `bud_fournisseur` (`idFourn`, `numFourn`, `adresse`, `nom`, `nature`, `ninea`, `rccm`, `email`, `observations`, `dateSys`, `contact`) VALUES
(1, 'EMD', 'malicounda', 'Cellule information', 'repreneur', NULL, NULL, NULL, NULL, '2025-04-16 11:27:02', '784413400'),
(2, 'EL', 'dakar', 'ELTON', 'bénéficiaire', NULL, NULL, NULL, NULL, '2025-04-24 11:35:11', '764019147'),
(3, 'YYY', 'mbour', 'bud', 'beneficiaire', NULL, NULL, NULL, NULL, '2026-05-13 10:40:52', '754019647'),
(4, 'F001', 'mbour', 'MAYFAY GLOBAL BUSINESS', 'Entreprise', '78336339722', 'SB-SJHSJS-4252', 'diopelhadjimadiop@gmail.com', 'dhdd dhdhhd', '2026-08-29 20:01:52', '221774412344');

-- --------------------------------------------------------

--
-- Structure de la table `bud_operations`
--

DROP TABLE IF EXISTS `bud_operations`;
CREATE TABLE IF NOT EXISTS `bud_operations` (
  `idOp` int NOT NULL AUTO_INCREMENT,
  `typeOp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateOp` date DEFAULT NULL,
  `montant` int NOT NULL,
  `numFact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idEng` int DEFAULT NULL,
  `idBordereau` int DEFAULT NULL,
  PRIMARY KEY (`idOp`),
  KEY `idEng` (`idEng`),
  KEY `idBordereau` (`idBordereau`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_operations`
--

INSERT INTO `bud_operations` (`idOp`, `typeOp`, `dateOp`, `montant`, `numFact`, `dateSys`, `idEng`, `idBordereau`) VALUES
(6, 'paiement', '2026-03-05', 200000, 'Bon', '2026-03-27 09:55:02', 6, 8),
(8, 'paiement', '2026-05-21', 200000, 'testAA', '2026-05-21 10:10:11', 6, 8),
(9, 'paiement', '2026-06-10', 300000, 'testAA', '2026-06-10 09:42:20', 7, NULL),
(10, 'paiement', '2026-06-17', 100000, 'testAA- hege _\'uej', '2026-06-17 10:39:56', 7, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `bud_operations_suppr`
--

DROP TABLE IF EXISTS `bud_operations_suppr`;
CREATE TABLE IF NOT EXISTS `bud_operations_suppr` (
  `idOp` int NOT NULL AUTO_INCREMENT,
  `typeOp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateOp` date DEFAULT NULL,
  `numFact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idEng` int DEFAULT NULL,
  PRIMARY KEY (`idOp`),
  KEY `idEng` (`idEng`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bud_operations_temp`
--

DROP TABLE IF EXISTS `bud_operations_temp`;
CREATE TABLE IF NOT EXISTS `bud_operations_temp` (
  `idOp` int NOT NULL AUTO_INCREMENT,
  `typeOp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateOp` date DEFAULT NULL,
  `numFact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` int NOT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idEng` int DEFAULT NULL,
  PRIMARY KEY (`idOp`),
  KEY `idEng` (`idEng`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bud_ordre_recette`
--

DROP TABLE IF EXISTS `bud_ordre_recette`;
CREATE TABLE IF NOT EXISTS `bud_ordre_recette` (
  `idOr` int NOT NULL AUTO_INCREMENT,
  `dateOr` date DEFAULT NULL,
  `objet_recette` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pieces_annexees` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(13,2) DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idFourn` int DEFAULT NULL,
  `idCompte` int DEFAULT NULL,
  `idUser` int DEFAULT NULL,
  PRIMARY KEY (`idOr`),
  KEY `idFourn` (`idFourn`),
  KEY `idCompte` (`idCompte`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_ordre_recette`
--

INSERT INTO `bud_ordre_recette` (`idOr`, `dateOr`, `objet_recette`, `pieces_annexees`, `montant`, `dateSys`, `idFourn`, `idCompte`, `idUser`) VALUES
(12, '2026-03-30', 'location de stand a mobil', 'quittance 2525/bor 5', 1000000.00, '2026-03-30 18:44:10', 1, 143, 1),
(13, '2026-05-13', 'location de stand a mobil', 'quittance 2525/bor 5', 600000.00, '2026-05-13 10:59:55', 3, 129, 1),
(14, '2026-05-13', 'location de stand a mobil', 'quittance 2525/bor 5', 800000.00, '2026-05-13 11:00:46', 1, 129, 1);

-- --------------------------------------------------------

--
-- Structure de la table `bud_ordre_recette_temp`
--

DROP TABLE IF EXISTS `bud_ordre_recette_temp`;
CREATE TABLE IF NOT EXISTS `bud_ordre_recette_temp` (
  `idOr` int NOT NULL AUTO_INCREMENT,
  `dateOr` date DEFAULT NULL,
  `objet_recette` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pieces_annexees` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `montant` decimal(13,2) DEFAULT NULL,
  `dateSys` datetime DEFAULT CURRENT_TIMESTAMP,
  `idFourn` int DEFAULT NULL,
  `idCompte` int DEFAULT NULL,
  `idUser` int DEFAULT NULL,
  PRIMARY KEY (`idOr`),
  KEY `idFourn` (`idFourn`),
  KEY `idCompte` (`idCompte`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bud_users`
--

DROP TABLE IF EXISTS `bud_users`;
CREATE TABLE IF NOT EXISTS `bud_users` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `log` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `priv` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sexe` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_mdp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `date_sys` date NOT NULL,
  `an` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `log` (`log`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_users`
--

INSERT INTO `bud_users` (`idUser`, `nom`, `log`, `mdp`, `email`, `priv`, `telephone`, `sexe`, `type_mdp`, `statut`, `date_sys`, `an`) VALUES
(1, 'Madiop DIOP', '936076/M', '9ead80632f1a0ff63cc214fa50b034ae7f48dde4', 'diopelhadjimadiop@gmail.com', 'admin', NULL, 'M', 'updated', 1, '2025-04-16', NULL),
(2, 'Modou Waly FAYE', '902000/A', 'f6a7651443d5867f394fe61ab082aac01c3c25fd', 'fayefaye@gmail.com', 'sag', NULL, NULL, 'updated', 1, '2025-04-23', NULL),
(3, 'Ibrahima DIOP', '936009/C', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'khalil@hotmail.com', 'op', NULL, NULL, 'updated', 1, '2025-04-29', NULL),
(4, 'Papa Amath Ndiaye', '934343/H', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'amathcoud@gmail.com', 'admin', '771565419', 'M', 'updated', 1, '0000-00-00', '2026'),
(5, 'Mamadou diop', '80024/M', '9ead80632f1a0ff63cc214fa50b034ae7f48dde4', 'diopmamadou@gmail.com', 'Cf_D', '+221784413400', 'M', 'default', 1, '0000-00-00', '2026'),
(7, 'El Hadji Madiop diop', 'op_all', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'op_all@gmail.com', 'op_all', '+221784413400', 'F', 'updated', 1, '0000-00-00', '2026'),
(8, 'op val', 'op_val', '9ead80632f1a0ff63cc214fa50b034ae7f48dde4', 'op_val@gmail.com', 'op_val', '+221784413400', 'M', 'default', 1, '0000-00-00', '2026'),
(9, 'eng val', 'eng_val', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'eng_val@gmail.com', 'eng_val', '+221764019647', 'F', 'updated', 1, '0000-00-00', '2026'),
(10, 'drp', 'drp', 'f2e593008db5ab3395b2c88aab307c4794532fe1', 'drp@coud.sn', 'drp', '7844413400', 'M', 'updated', 1, '0000-00-00', '2026');

-- --------------------------------------------------------

--
-- Structure de la table `sigm_marches`
--

DROP TABLE IF EXISTS `sigm_marches`;
CREATE TABLE IF NOT EXISTS `sigm_marches` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `annee` year NOT NULL,
  `montant` decimal(15,2) NOT NULL DEFAULT '0.00',
  `type_marche` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_fournisseur` int NOT NULL,
  `statut` enum('en_attente','valide','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `motif_annulation` text COLLATE utf8mb4_unicode_ci,
  `created_by` int DEFAULT NULL,
  `validated_by` int DEFAULT NULL,
  `cancelled_by` int DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_validation` datetime DEFAULT NULL,
  `date_annulation` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  KEY `idx_annee` (`annee`),
  KEY `idx_statut` (`statut`),
  KEY `idx_type_marche` (`type_marche`),
  KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sigm_marches`
--

INSERT INTO `sigm_marches` (`id`, `reference`, `annee`, `montant`, `type_marche`, `objet`, `id_fournisseur`, `statut`, `motif_annulation`, `created_by`, `validated_by`, `cancelled_by`, `date_creation`, `date_validation`, `date_annulation`, `updated_at`) VALUES
(1, 'RF-25-003', '2026', 200000.00, 'Fourniture de biens', 'azertyui', 0, 'valide', NULL, NULL, NULL, NULL, '2026-08-29 11:26:17', '2026-08-29 11:41:54', NULL, '2026-08-29 11:41:54'),
(2, 'EB-2026-0001', '2026', 390000.00, 'Travaux', 'sdfghj', 0, 'annule', 'dossier incomplet!', NULL, NULL, NULL, '2026-08-29 12:22:54', NULL, '2026-08-29 12:24:34', '2026-08-29 12:24:34'),
(3, 'RF-25-001', '2026', 850000.00, 'Services', 'service formation informatique', 4, 'en_attente', NULL, 10, NULL, NULL, '2026-08-29 20:17:56', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `sigm_marche_documents`
--

DROP TABLE IF EXISTS `sigm_marche_documents`;
CREATE TABLE IF NOT EXISTS `sigm_marche_documents` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `marche_id` int UNSIGNED NOT NULL,
  `nom_original` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_fichier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chemin_fichier` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_document` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taille` bigint UNSIGNED NOT NULL DEFAULT '0',
  `statut` enum('en_attente','valide','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `uploaded_by` int DEFAULT NULL,
  `date_upload` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_marche_id` (`marche_id`),
  KEY `idx_statut` (`statut`),
  KEY `idx_type_document` (`type_document`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sigm_marche_documents`
--

INSERT INTO `sigm_marche_documents` (`id`, `marche_id`, `nom_original`, `nom_fichier`, `chemin_fichier`, `type_document`, `extension`, `taille`, `statut`, `uploaded_by`, `date_upload`) VALUES
(1, 1, 'BUDGET (3).xlsx', 'MARCHE_1_b14ab61e8703ab9c.xlsx', 'uploads/marches/MARCHE_1_b14ab61e8703ab9c.xlsx', 'pva', 'xlsx', 10115, 'valide', NULL, '2026-08-29 11:26:17'),
(2, 1, 'Marchés.docx', 'MARCHE_1_cedfc03847256981.docx', 'uploads/marches/MARCHE_1_cedfc03847256981.docx', 'facture', 'docx', 104981, 'valide', NULL, '2026-08-29 11:26:17'),
(3, 1, 'facture-001396.pdf', 'MARCHE_1_91d8d58730da1aa7.pdf', 'uploads/marches/MARCHE_1_91d8d58730da1aa7.pdf', 'preforma', 'pdf', 140825, 'valide', NULL, '2026-08-29 11:26:17'),
(4, 2, 'Ameliorations_Medicoud.pdf', 'MARCHE_2_1ab35d75e0934940.pdf', 'uploads/marches/MARCHE_2_1ab35d75e0934940.pdf', 'gf', 'pdf', 143551, 'annule', NULL, '2026-08-29 12:22:54'),
(5, 2, 'facture-001396.pdf', 'MARCHE_2_4634e4200e76d7dc.pdf', 'uploads/marches/MARCHE_2_4634e4200e76d7dc.pdf', 'fd', 'pdf', 140825, 'annule', NULL, '2026-08-29 12:22:54'),
(6, 3, 'Ameliorations_Medicoud.pdf', 'MARCHE_3_a73478e02bc9fa4b.pdf', 'uploads/marches/MARCHE_3_a73478e02bc9fa4b.pdf', 'contrat', 'pdf', 143551, 'en_attente', 10, '2026-08-29 20:17:56'),
(7, 3, 'facture-001396.pdf', 'MARCHE_3_726bfe2f2f9ad454.pdf', 'uploads/marches/MARCHE_3_726bfe2f2f9ad454.pdf', 'facture', 'pdf', 140825, 'en_attente', 10, '2026-08-29 20:17:56');

-- --------------------------------------------------------

--
-- Structure de la table `sigm_marche_historique`
--

DROP TABLE IF EXISTS `sigm_marche_historique`;
CREATE TABLE IF NOT EXISTS `sigm_marche_historique` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `marche_id` int UNSIGNED NOT NULL,
  `ancien_statut` enum('en_attente','valide','annule') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nouveau_statut` enum('en_attente','valide','annule') COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  `date_action` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_historique_marche` (`marche_id`),
  KEY `idx_historique_date` (`date_action`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sigm_marche_historique`
--

INSERT INTO `sigm_marche_historique` (`id`, `marche_id`, `ancien_statut`, `nouveau_statut`, `commentaire`, `user_id`, `date_action`) VALUES
(1, 1, NULL, 'en_attente', 'Création du dossier de marché.', NULL, '2026-08-29 11:26:17'),
(2, 1, 'en_attente', 'valide', 'Dossier validé.', NULL, '2026-08-29 11:41:54'),
(3, 2, NULL, 'en_attente', 'Création du dossier de marché.', NULL, '2026-08-29 12:22:54'),
(4, 2, 'en_attente', 'annule', 'dossier incomplet!', NULL, '2026-08-29 12:24:34'),
(5, 3, NULL, 'en_attente', 'Création du dossier de marché pour le fournisseur : MAYFAY GLOBAL BUSINESS', 10, '2026-08-29 20:17:56');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bud_compte`
--
ALTER TABLE `bud_compte`
  ADD CONSTRAINT `bud_compte_ibfk_1` FOREIGN KEY (`idCp`) REFERENCES `bud_comptep` (`idCp`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bud_dotations`
--
ALTER TABLE `bud_dotations`
  ADD CONSTRAINT `bud_dotations_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `bud_users` (`idUser`),
  ADD CONSTRAINT `bud_dotations_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `bud_compte` (`idCompte`);

--
-- Contraintes pour la table `bud_engagements`
--
ALTER TABLE `bud_engagements`
  ADD CONSTRAINT `bud_engagements_ibfk_1` FOREIGN KEY (`idFourn`) REFERENCES `bud_fournisseur` (`idFourn`),
  ADD CONSTRAINT `bud_engagements_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `bud_compte` (`idCompte`);

--
-- Contraintes pour la table `bud_engagements_temp`
--
ALTER TABLE `bud_engagements_temp`
  ADD CONSTRAINT `bud_engagements_temp_ibfk_1` FOREIGN KEY (`idFourn`) REFERENCES `bud_fournisseur` (`idFourn`),
  ADD CONSTRAINT `bud_engagements_temp_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `bud_compte` (`idCompte`);

--
-- Contraintes pour la table `bud_operations`
--
ALTER TABLE `bud_operations`
  ADD CONSTRAINT `bud_operations_ibfk_1` FOREIGN KEY (`idEng`) REFERENCES `bud_engagements` (`idEng`);

--
-- Contraintes pour la table `bud_operations_suppr`
--
ALTER TABLE `bud_operations_suppr`
  ADD CONSTRAINT `bud_operations_suppr_ibfk_1` FOREIGN KEY (`idEng`) REFERENCES `bud_engagements` (`idEng`);

--
-- Contraintes pour la table `bud_operations_temp`
--
ALTER TABLE `bud_operations_temp`
  ADD CONSTRAINT `bud_operations_temp_ibfk_1` FOREIGN KEY (`idEng`) REFERENCES `bud_engagements` (`idEng`);

--
-- Contraintes pour la table `bud_ordre_recette`
--
ALTER TABLE `bud_ordre_recette`
  ADD CONSTRAINT `bud_ordre_recette_ibfk_1` FOREIGN KEY (`idFourn`) REFERENCES `bud_fournisseur` (`idFourn`),
  ADD CONSTRAINT `bud_ordre_recette_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `bud_compte` (`idCompte`),
  ADD CONSTRAINT `bud_ordre_recette_ibfk_3` FOREIGN KEY (`idUser`) REFERENCES `bud_users` (`idUser`);

--
-- Contraintes pour la table `bud_ordre_recette_temp`
--
ALTER TABLE `bud_ordre_recette_temp`
  ADD CONSTRAINT `bud_ordre_recette_temp_ibfk_1` FOREIGN KEY (`idFourn`) REFERENCES `bud_fournisseur` (`idFourn`),
  ADD CONSTRAINT `bud_ordre_recette_temp_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `bud_compte` (`idCompte`),
  ADD CONSTRAINT `bud_ordre_recette_temp_ibfk_3` FOREIGN KEY (`idUser`) REFERENCES `bud_users` (`idUser`);

--
-- Contraintes pour la table `sigm_marche_documents`
--
ALTER TABLE `sigm_marche_documents`
  ADD CONSTRAINT `fk_marche_documents` FOREIGN KEY (`marche_id`) REFERENCES `sigm_marches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sigm_marche_historique`
--
ALTER TABLE `sigm_marche_historique`
  ADD CONSTRAINT `fk_historique_marche` FOREIGN KEY (`marche_id`) REFERENCES `sigm_marches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
