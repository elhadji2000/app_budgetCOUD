-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 22 avr. 2025 à 14:09
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `budget_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `idCompte` int(11) NOT NULL,
  `numCompte` varchar(100) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `dateSys` datetime DEFAULT current_timestamp(),
  `libelle` varchar(255) DEFAULT NULL,
  `idCp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`idCompte`, `numCompte`, `code`, `dateSys`, `libelle`, `idCp`) VALUES
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
-- Structure de la table `comptep`
--

CREATE TABLE `comptep` (
  `idCp` int(11) NOT NULL,
  `numCp` varchar(50) DEFAULT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `dateSys` datetime DEFAULT current_timestamp(),
  `nature` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comptep`
--

INSERT INTO `comptep` (`idCp`, `numCp`, `libelle`, `dateSys`, `nature`) VALUES
(1, '141', 'Subvention d\'Equipement', '2025-04-16 08:39:53', 'ressource'),
(2, '16', 'Emprunts et dettes assimiles', '2025-04-16 08:39:53', 'emploi'),
(3, '23', 'Batiments Administratifs', '2025-04-16 08:39:53', 'emploi'),
(4, '24', 'Materiel et Outillage', '2025-04-16 08:39:53', 'emploi'),
(5, '275', 'Depot et Cautionnement', '2025-04-16 08:39:53', 'emploi'),
(6, '47', 'Comptes dAttente', '2025-04-16 08:39:53', 'produit'),
(7, '60', 'Achats', '2025-04-16 08:39:53', 'charge'),
(8, '61', 'Transports', '2025-04-16 08:39:53', 'charge'),
(9, '62', 'Services Exterieurs A', '2025-04-16 08:39:53', 'charge'),
(10, '63', 'Services Exterieurs B', '2025-04-16 08:39:53', 'charge'),
(11, '64', 'Impots et Taxes', '2025-04-16 08:39:53', 'charge'),
(12, '65', 'Autres Charges', '2025-04-16 08:39:53', 'charge'),
(13, '66', 'Charges de Personnel', '2025-04-16 08:39:53', 'charge'),
(14, '67', 'Frais Financiers', '2025-04-16 08:39:53', 'charge'),
(15, '68', 'Dotation aux Amortissements', '2025-04-16 08:39:53', 'charge'),
(16, '70', 'Ventes', '2025-04-16 08:39:53', 'produit'),
(17, '71', 'Subvention d\'Exploitation', '2025-04-16 08:39:53', 'produit'),
(18, '75', 'Autres Produits', '2025-04-16 08:39:53', 'produit'),
(19, '82', 'Produits de Cessions', '2025-04-16 08:39:53', 'produit'),
(20, '865', 'Reprise Subvention d\'Investissement', '2025-04-16 08:39:53', 'produit');

-- --------------------------------------------------------

--
-- Structure de la table `dotations`
--

CREATE TABLE `dotations` (
  `idDot` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `volume` decimal(13,2) DEFAULT NULL,
  `dateSys` datetime DEFAULT current_timestamp(),
  `type` varchar(50) DEFAULT NULL,
  `an` year(4) DEFAULT NULL,
  `idUser` int(11) DEFAULT NULL,
  `idCompte` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dotations`
--

INSERT INTO `dotations` (`idDot`, `date`, `volume`, `dateSys`, `type`, `an`, `idUser`, `idCompte`) VALUES
(1, '2025-04-16', 3000000.00, '2025-04-16 09:38:08', 'initiale', '2025', 1, 2),
(2, '2025-04-22', 150000000.00, '2025-04-22 10:53:50', 'initiale', '2025', 1, 3),
(3, '2025-04-22', 100000000.00, '2025-04-22 10:54:16', 'initiale', '2025', 1, 4),
(4, '2025-04-22', 50000000.00, '2025-04-22 10:54:36', 'initiale', '2025', 1, 6),
(5, '2025-04-22', 14000000.00, '2025-04-22 10:55:03', 'initiale', '2025', 1, 7);

-- --------------------------------------------------------

--
-- Structure de la table `engagements`
--

CREATE TABLE `engagements` (
  `idEng` int(11) NOT NULL,
  `dateEng` date DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `bc` varchar(100) DEFAULT NULL,
  `montant` decimal(13,2) DEFAULT NULL,
  `dateSys` datetime DEFAULT current_timestamp(),
  `idFourn` int(11) DEFAULT NULL,
  `idCompte` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `engagements`
--

INSERT INTO `engagements` (`idEng`, `dateEng`, `service`, `libelle`, `bc`, `montant`, `dateSys`, `idFourn`, `idCompte`) VALUES
(1, '2025-04-16', 'RESTO', 'engagement 1', 'F10', 300000.00, '2025-04-16 11:40:18', 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `idFourn` int(11) NOT NULL,
  `numFourn` varchar(50) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `nature` varchar(100) DEFAULT NULL,
  `dateSys` datetime DEFAULT current_timestamp(),
  `contact` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`idFourn`, `numFourn`, `adresse`, `nom`, `nature`, `dateSys`, `contact`) VALUES
(1, 'EMD', 'malicounda', 'diop', 'repreneur', '2025-04-16 11:27:02', '784413400');

-- --------------------------------------------------------

--
-- Structure de la table `operations`
--

CREATE TABLE `operations` (
  `idOp` int(11) NOT NULL,
  `typeOp` varchar(50) NOT NULL,
  `dateOp` date DEFAULT NULL,
  `numFact` varchar(100) DEFAULT NULL,
  `dateSys` datetime DEFAULT current_timestamp(),
  `idEng` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `operations`
--

INSERT INTO `operations` (`idOp`, `typeOp`, `dateOp`, `numFact`, `dateSys`, `idEng`) VALUES
(1, 'paiement', '2025-04-16', '004552', '2025-04-16 12:22:30', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `idUser` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `log` varchar(100) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `priv` varchar(100) DEFAULT NULL,
  `type_mdp` varchar(50) DEFAULT NULL,
  `date_sys` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idUser`, `nom`, `log`, `mdp`, `email`, `priv`, `type_mdp`, `date_sys`) VALUES
(1, 'Madiop DIOP', '936076/M', 'f6a7651443d5867f394fe61ab082aac01c3c25fd', 'diopelhadjimadiop@gmail.com', 'admin', 'updated', '2025-04-16');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`idCompte`),
  ADD UNIQUE KEY `numCompte` (`numCompte`),
  ADD KEY `idCp` (`idCp`);

--
-- Index pour la table `comptep`
--
ALTER TABLE `comptep`
  ADD PRIMARY KEY (`idCp`);

--
-- Index pour la table `dotations`
--
ALTER TABLE `dotations`
  ADD PRIMARY KEY (`idDot`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idCompte` (`idCompte`);

--
-- Index pour la table `engagements`
--
ALTER TABLE `engagements`
  ADD PRIMARY KEY (`idEng`),
  ADD KEY `idFourn` (`idFourn`),
  ADD KEY `idCompte` (`idCompte`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`idFourn`);

--
-- Index pour la table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`idOp`),
  ADD KEY `idEng` (`idEng`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `log` (`log`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
  MODIFY `idCompte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT pour la table `comptep`
--
ALTER TABLE `comptep`
  MODIFY `idCp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `dotations`
--
ALTER TABLE `dotations`
  MODIFY `idDot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `engagements`
--
ALTER TABLE `engagements`
  MODIFY `idEng` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `idFourn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `operations`
--
ALTER TABLE `operations`
  MODIFY `idOp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `compte_ibfk_1` FOREIGN KEY (`idCp`) REFERENCES `comptep` (`idCp`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dotations`
--
ALTER TABLE `dotations`
  ADD CONSTRAINT `dotations_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`),
  ADD CONSTRAINT `dotations_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `compte` (`idCompte`);

--
-- Contraintes pour la table `engagements`
--
ALTER TABLE `engagements`
  ADD CONSTRAINT `engagements_ibfk_1` FOREIGN KEY (`idFourn`) REFERENCES `fournisseur` (`idFourn`),
  ADD CONSTRAINT `engagements_ibfk_2` FOREIGN KEY (`idCompte`) REFERENCES `compte` (`idCompte`);

--
-- Contraintes pour la table `operations`
--
ALTER TABLE `operations`
  ADD CONSTRAINT `operations_ibfk_1` FOREIGN KEY (`idEng`) REFERENCES `engagements` (`idEng`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
