-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 10 nov. 2026 à 18:03
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
-- Structure de la table `ass_budgetaires`
--

DROP TABLE IF EXISTS `ass_budgetaires`;
CREATE TABLE IF NOT EXISTS `ass_budgetaires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dotation` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_numero` (`compte`)
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ass_budgetaires`
--

INSERT INTO `ass_budgetaires` (`id`, `compte`, `libelle`, `dotation`) VALUES
(156, '70611', 'Vente de repas Subv', 1000000000.00),
(157, '70612', 'Vente petit déjner Subv,', 250000000.00),
(158, '70613', 'Vente de repas express', 0.00),
(159, '70614', 'Loyers subventionnés', 280000000.00),
(160, '70615', 'Loyers « passager »', 0.00),
(161, '70616', 'Vente de médicaments', 10000000.00),
(162, '70617', 'Consultations externes (imagerie médicale+divers)', 90000000.00),
(163, '70618', 'Vente tickets / consultation', 2000000.00),
(164, '70731', 'Location panneaux pub', 21000000.00),
(165, '70732', 'Location terrain de foot', 0.00),
(166, '70733', 'Location salles de spectacle', 2000000.00),
(167, '70734', 'Location « Cantines »', 31000000.00),
(168, '70735', 'Location Centre Commercial', 10000000.00),
(169, '70736', 'Location kiosques', 2000000.00),
(170, '70781', 'Imprimerie du COUD', 0.00),
(171, '70782', 'Vente dossiers d\'appel d\'offres', 3000000.00),
(172, '70783', 'Vente de guides des œuvres', 1000000.00),
(173, '7181', 'Contribution Etat du Sénégal', 25120015375.00),
(174, '7182', 'Contribution autres Etats et Organismes', 5000000.00),
(175, '7183', 'Contributions versées par des tiers', 2000000.00),
(176, '7582', 'Indemnités d\'assurances reçues', 0.00),
(177, '7583', 'Autres Produits Divers', 5000000.00),
(178, '7584', 'Retenues imputables à des tiers', 15000000.00),
(179, '7585', 'Reprises sur caution', 15000000.00),
(180, '822', 'Produits des cessions d\'immobilisations corporelles', 9597350.00),
(181, '845', 'Dons et libéralités obtenus', 5000000.00),
(182, '604111', 'Maçonnerie', 75000000.00),
(183, '604112', 'Droguerie- Peinture', 73000000.00),
(184, '604113', 'Petit outillage', 15000000.00),
(185, '604114', 'Plomberie Sanitaire', 100000000.00),
(186, '604115', 'Petit Matériel électrique', 100000000.00),
(187, '604116', 'Quincaillerie', 100000000.00),
(188, '604117', 'Menuiserie bois', 80000000.00),
(189, '604118', 'Menuiserie Métallique', 60000000.00),
(190, '604119', 'Fournitures consommées', 70000000.00),
(191, '604121', 'Petit Matériel cuisine restaurant', 15000000.00),
(192, '604122', 'Literie draps couvertures', 88000000.00),
(193, '604123', 'Petit matériel ludique', 90000000.00),
(194, '604124', 'Pieces détachées Auto', 45000000.00),
(195, '604125', 'Linge Tissus Mercerie', 19000000.00),
(196, '604126', 'Vetement de travail', 70000000.00),
(197, '604127', 'Petit matériel d\'entretien', 39000000.00),
(198, '604128', 'Petit matériel et équipement de décoration', 0.00),
(199, '604191', 'Produits pharma étudiants', 160000000.00),
(200, '604192', 'Produits pharma personnel', 10000000.00),
(201, '604193', 'Produits dentaires', 30000000.00),
(202, '604194', 'Produits medicaux', 30000000.00),
(203, '604195', 'Petit matériel médical', 30000000.00),
(204, '604196', 'Produits laboratoire', 20000000.00),
(205, '60421', 'Carburant Lubrifiant', 140000000.00),
(206, '6043', 'Produits d\' entretien', 120000000.00),
(207, '60441', 'Fournitures d\'imprimerie', 0.00),
(208, '60478', 'Autres fournitures de bureau', 140000000.00),
(209, '60472', 'Petit matériel Informatique', 20000000.00),
(210, '60473', 'Imprimés', 140000000.00),
(211, '60477', 'Consommables informatiques', 0.00),
(212, '60481', 'Petit matériel de communication', 0.00),
(213, '6051', 'Eau', 300000000.00),
(214, '6052', 'Electricité', 80000000.00),
(215, '614', 'Transport Personnel', 0.00),
(216, '618', 'Autres Frais de Transport', 30000000.00),
(217, '6211', 'Sous traitance restauration', 5860000000.00),
(218, '6222', 'Loyer batiment', 40000000.00),
(219, '6223', 'Locations de matériels et outillages', 30000000.00),
(220, '623', 'Redevances de credit-bail et contrats assimilés', 0.00),
(221, '62411', 'Entretien cours et jardins', 4000000.00),
(222, '62412', 'Entretien Curage', 30000000.00),
(223, '62413', 'Désinfection Cités', 30000000.00),
(224, '62414', 'Entretien décharge ordures', 45000000.00),
(225, '624151', 'Entretien et réfection bâtiments admin', 2720745000.00),
(226, '624152', 'Entretien et aménagements', 705000000.00),
(227, '624211', 'Entretien poste de transform', 0.00),
(228, '624212', 'Entretien groupe électrogene', 5000000.00),
(229, '624213', 'Entretien pompe à eau', 10000000.00),
(230, '624223', 'Entretien matériel cuisine rest', 20000000.00),
(231, '624224', 'Entretien matériel incendie', 5000000.00),
(232, '624225', 'Entretien Véhicule', 22000000.00),
(233, '624226', 'Entretien matériel medical', 5000000.00),
(234, '62481', 'blanchissage', 35000000.00),
(235, '62482', 'Autres entretiens et réparations', 5000000.00),
(236, '6252', 'Assurance matériel de transport', 15000000.00),
(237, '62581', 'Assurance incendie', 0.00),
(238, '6261', 'Etudes et Recherches', 44000000.00),
(239, '6265', 'Documentation générale', 2000000.00),
(240, '62781', 'Autre activité de comm', 20000000.00),
(241, '6271', 'Annonces, insertions', 5000000.00),
(242, '6277', 'Frais de colloques, séminaires, conférences', 25000000.00),
(243, '6281', 'Frais de téléphone', 30000000.00),
(244, '6288', 'Autres charges de publicité et relations publiques', 5000000.00),
(245, '6324', 'Honoraires internes', 0.00),
(246, '63251', 'Frais d\' actes', 20000000.00),
(247, '63252', 'Frais de contentieux', 0.00),
(248, '6327', 'Rémunérations des autres prestations de services', 5000000.00),
(249, '632801', 'Frais de représentation', 0.00),
(250, '631', 'Frais bancaires', 2000000.00),
(251, '633', 'Frais formation personnel', 20000000.00),
(252, '634', 'Redevances pour brevets, licences, logiciels et droits similaires', 14000000.00),
(253, '635811', 'Remboursement frais lunettes', 5000000.00),
(254, '635812', 'Analyses consultations étudiants', 150000000.00),
(255, '635813', 'Soins externes étudiants', 20000000.00),
(256, '635814', 'Hospitalisation étudiants', 70000000.00),
(257, '635815', 'Subventions accordées aux amicales', 65000000.00),
(258, '635816', 'Autres subventions accordées', 70000000.00),
(259, '63830', 'Activités sportives', 67000000.00),
(260, '63831', 'Activités culturelles', 64000000.00),
(261, '63832', 'Réception', 3000000.00),
(262, '6384', 'Mission', 20000000.00),
(263, '6388', 'Charges externes diverses', 270000000.00),
(264, '641', 'Impôts et taxes directs', 0.00),
(265, '646', 'Droit d\'enregistrement', 200000.00),
(266, '647', 'Pénalités et amendes fiscales', 0.00),
(267, '6580', 'Charges Diverses', 80000000.00),
(268, '6581', 'Indemnités de fonction et autres rémunérations d\'administrateurs', 26597350.00),
(269, '6585', 'Secours Activités Sociales', 70000000.00),
(270, '6584', 'Indemnisation sous-traitants restaurants', 0.00),
(271, '66111', 'Rémunération personnel permanent', 11000000000.00),
(272, '66112', 'Rémunération personnel Temporaire', 2100000000.00),
(273, '6617', 'Médaille de travail', 10000000.00),
(274, '6638', 'Indemnités Avantages', 60000000.00),
(275, '664', 'Charges sociales', 500000000.00),
(276, '66841', 'Hospitalisation personnel', 25000000.00),
(277, '66842', 'Analyses consultations personnel', 40000000.00),
(278, '66843', 'Accouchement personnel', 10000000.00),
(279, '66844', 'Soins externes personnel', 5000000.00),
(280, '6688', 'Charges sociales diverses', 80070375.00),
(281, '674', 'Charges Financieres', 0.00),
(282, '68131', 'Dotation aux amort. Mat. Cuisine', 0.00),
(283, '68132', 'Dotation aux amort. Mat. D\' exploitation', 0.00),
(284, '68133', 'Dotation aux amort. Mat. Bureau', 0.00),
(285, '68134', 'Dotation aux amort. Mat. Cités', 0.00),
(286, '68135', 'Dotation aux amort. Ag. Installation', 0.00),
(287, '68136', 'Dotation aux amort. Mat.Transport', 0.00),
(288, '68137', 'Dotation aux amort. Charges à rétablir', 0.00),
(289, '14', 'Subvention d\'Investissement', 1321000000.00),
(290, '23131', 'Refection batiment d\'exploitation', 0.00),
(291, '2340', 'Installation Téléphonique', 0.00),
(292, '2381', 'Travaux Amenagements Installations', 0.00),
(293, '2390', 'Bâtiments en cours', 0.00),
(294, '2415', 'Matériel et outillage de cuisine et restaurant', 50000000.00),
(295, '2416', 'Matériel et outillage thermique Appareil Froid', 80000000.00),
(296, '2417', 'Matériel et outillage Atelier', 10000000.00),
(297, '2418', 'Matériel et outillage incendie', 15000000.00),
(298, '2440', 'Matériel et Mobilier de bureau', 200000000.00),
(299, '2442', 'Materiel et outillage informatique', 140000000.00),
(300, '24481', 'Materiel et mobilier de cités', 420000000.00),
(301, '2450', 'Matériel de transport', 116000000.00),
(302, '2484', 'Matériel Services Médicaux', 220000000.00),
(303, '2482', 'Matériel Activités Culturelles', 40000000.00),
(304, '2483', 'Matériel Mob Foyer Universitaire', 15000000.00),
(305, '2488', 'Divers matériels et mobiliers', 15000000.00),
(306, '2752', 'Depot et cautionnement Electricite', 0.00),
(307, '2753', 'Depot et cautionnement Eau', 0.00),
(308, '2758', 'Autres Depot cautionnement', 0.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_compte`
--

INSERT INTO `bud_compte` (`idCompte`, `numCompte`, `code`, `dateSys`, `libelle`, `idCp`) VALUES
(1, '141', 'SE', '2025-04-16 08:54:17', 'Subvention d\'Equipement', 1),
(2, '162', 'EDEC', '2025-04-16 08:54:17', 'Emprunts et dettes aupres des etablissements de credits', 2),
(3, '23131', 'RBE', '2025-04-16 08:54:17', 'Refection batiment d\'exploitation', 3),
(4, '2340', 'IT', '2025-04-16 08:54:17', 'Installation Téléphonique', 3),
(5, '2381', 'TAI', '2025-04-16 08:54:17', 'Travaux Amenagements Installations', 3),
(6, '2390', 'BEC', '2025-04-16 08:54:17', 'Bâtiments en cours', 3),
(7, '2410', 'MOA', '2025-04-16 08:54:17', 'Machine Outillage Atelier', 4),
(8, '2415', 'MCR', '2025-04-16 08:54:17', 'Matériel et outillage de cuisine et restaurant', 4),
(9, '2416', 'MTAF', '2025-04-16 08:54:17', 'Matériel et outillage thermique Appareil Froid', 4),
(10, '2417', 'MOA', '2025-04-16 08:54:17', 'Matériel et outillage Atelier', 4),
(11, '2418', 'MI', '2025-04-16 08:54:17', 'Matériel et outillage incendie', 4),
(12, '2441', 'MMB', '2025-04-16 08:54:17', 'Materiel Mobilier de Bureau', 4),
(13, '2442', 'MInf', '2025-04-16 08:54:17', 'Materiel et outillage informatique', 4),
(14, '2448', 'MMC', '2025-04-16 08:54:17', 'Materiel Mobilier des Cites', 4),
(15, '2450', 'MT', '2025-04-16 08:54:17', 'Matériel de transport', 4),
(16, '2481', 'MSM', '2025-04-16 08:54:17', 'Materiel Services Medicaux', 4),
(17, '2482', 'MAC', '2025-04-16 08:54:17', 'Matériel Activités Culturelles', 4),
(18, '2483', 'MMFYU', '2025-04-16 08:54:17', 'Matériel Mob Foyer Universitaire', 4),
(19, '2752', 'DCElec', '2025-04-16 08:54:17', 'Depot et cautionnement Electricite', 5),
(20, '2753', 'DCEau', '2025-04-16 08:54:17', 'Depot et cautionnement Eau', 5),
(21, '2758', 'ADC', '2025-04-16 08:54:17', 'Autres Depot cautionnement', 5),
(22, '47111', '', '2025-04-16 08:54:17', 'Vente de Repas Subv', 6),
(23, '47112', '', '2025-04-16 08:54:17', 'Vente Petit Dejeuner Subv', 6),
(24, '4721', '', '2025-04-16 08:54:17', 'Vente Tickets/Consultation', 6),
(25, '4722', '', '2025-04-16 08:54:17', 'Ventes cartes transport USSEIN', 6),
(26, '4723', '', '2025-04-16 08:54:17', 'Vente de medicaments', 6),
(27, '4724', '', '2025-04-16 08:54:17', 'Vente du Guide des oeuvres', 6),
(28, '4731', '', '2025-04-16 08:54:17', 'Loyers Subventionnes', 6),
(29, '4732', '', '2025-04-16 08:54:17', 'Caution/Loyer', 6),
(30, '604111', 'Mac', '2025-04-16 08:54:17', 'Maçonnerie', 7),
(31, '604112', 'DP', '2025-04-16 08:54:17', 'Droguerie- Peinture', 7),
(32, '604113', 'PO', '2025-04-16 08:54:17', 'Petit outillage', 7),
(33, '604114', 'PS', '2025-04-16 08:54:17', 'Plomberie Sanitaire', 7),
(34, '604115', 'PME', '2025-04-16 08:54:17', 'Petit Matériel électrique', 7),
(35, '604116', 'Qinq', '2025-04-16 08:54:17', 'Quincaillerie', 7),
(36, '604117', 'MB', '2025-04-16 08:54:17', 'Menuiserie bois', 7),
(37, '604118', 'MM', '2025-04-16 08:54:17', 'Menuiserie Métallique', 7),
(38, '604119', 'FC', '2025-04-16 08:54:17', 'Fournitures consommées', 7),
(39, '604121', 'PMCR', '2025-04-16 08:54:17', 'Petit Matériel cuisine restaurant', 7),
(40, '604122', 'LDC', '2025-04-16 08:54:17', 'Literie draps couvertures', 7),
(41, '604123', 'PML', '2025-04-16 08:54:17', 'Petit matériel ludique', 7),
(42, '604124', 'PDA', '2025-04-16 08:54:17', 'Pieces détachées Auto', 7),
(43, '604125', 'LTM', '2025-04-16 08:54:17', 'Linge Tissus Mercerie', 7),
(44, '604126', 'VT', '2025-04-16 08:54:17', 'Vetement de travail', 7),
(45, '604191', 'PPE', '2025-04-16 08:54:17', 'Produits pharma étudiants', 7),
(46, '604192', 'PPP', '2025-04-16 08:54:17', 'Produits pharma personnel', 7),
(47, '604193', 'PD', '2025-04-16 08:54:17', 'Produits dentaires', 7),
(48, '604194', 'PM', '2025-04-16 08:54:17', 'Produits medicaux', 7),
(49, '604195', 'PMM', '2025-04-16 08:54:17', 'Petit matériel médical', 7),
(50, '604196', 'PL', '2025-04-16 08:54:17', 'Produits laboratoire', 7),
(51, '60421', 'CL', '2025-04-16 08:54:17', 'Carburant Lubrifiant', 7),
(52, '6043', 'PE', '2025-04-16 08:54:17', 'Produits d\' entretien', 7),
(53, '60472', 'PMI', '2025-04-16 08:54:17', 'Petit matériel Informatique', 7),
(54, '60473', 'Imp', '2025-04-16 08:54:17', 'Imprimés', 7),
(55, '60478', 'AFB', '2025-04-16 08:54:17', 'Autres fournitures de bureau', 7),
(56, '6051', 'Eau', '2025-04-16 08:54:17', 'Eau', 7),
(57, '6052', 'Elec', '2025-04-16 08:54:17', 'Electricité', 7),
(58, '614', 'TP', '2025-04-16 08:54:17', 'Transport Personnel', 8),
(59, '616', 'AP', '2025-04-16 08:54:17', 'Affranchissements Postaux', 8),
(60, '618', 'AFT', '2025-04-16 08:54:17', 'Autres Frais de Transport', 8),
(61, '6211', 'STR', '2025-04-16 08:54:17', 'Sous traitance restauration', 9),
(62, '6222', 'LB', '2025-04-16 08:54:17', 'Loyer batiment', 9),
(63, '623', 'RCB', '2025-04-16 08:54:17', 'Redevances de credit-bail et contrats assimilés', 9),
(64, '62411', 'ECJ', '2025-04-16 08:54:17', 'Entretien cours et jardins', 9),
(65, '62412', 'EC', '2025-04-16 08:54:17', 'Entretien Curage', 9),
(66, '62413', 'DC', '2025-04-16 08:54:17', 'Désinfection Cités', 9),
(67, '62414', 'EDO', '2025-04-16 08:54:17', 'Entretien décharge ordures', 9),
(68, '624211', 'EPT', '2025-04-16 08:54:17', 'Entretien poste de transform', 9),
(69, '624212', 'EGE', '2025-04-16 08:54:17', 'Entretien groupe électrogene', 9),
(70, '624213', 'EPE', '2025-04-16 08:54:17', 'Entretien pompe à eau', 9),
(71, '624214', 'EMaC', '2025-04-16 08:54:17', 'Entretien Materiel Cites', 9),
(72, '624215', 'EMoC', '2025-04-16 08:54:17', 'Entretien Mobilier Cites', 9),
(73, '624216', 'EO', '2025-04-16 08:54:17', 'Entretien Outillage', 9),
(74, '624217', 'EAF', '2025-04-16 08:54:17', 'Entretien Appareil Froid', 9),
(75, '624218', 'ET', '2025-04-16 08:54:17', 'Entretien Telephonique', 9),
(76, '624221', 'EMB', '2025-04-16 08:54:17', 'Entretien Materiel de Bureau', 9),
(77, '624222', 'EMInf', '2025-04-16 08:54:17', 'Entretien Materiel Informatique', 9),
(78, '624223', 'EMC', '2025-04-16 08:54:17', 'Entretien matériel cuisine rest', 9),
(79, '624224', 'EMInc', '2025-04-16 08:54:17', 'Entretien matériel incendie', 9),
(80, '624225', 'EV', '2025-04-16 08:54:17', 'Entretien Véhicule', 9),
(81, '624226', 'EMM', '2025-04-16 08:54:17', 'Entretien matériel medical', 9),
(82, '62481', 'Blanc', '2025-04-16 08:54:17', 'blanchissage', 9),
(83, '6252', 'AV', '2025-04-16 08:54:17', 'Assurance matériel de transport', 9),
(84, '6258', 'AI', '2025-04-16 08:54:17', 'Assurance Incendie', 9),
(85, '6261', 'ER', '2025-04-16 08:54:17', 'Etudes et Recherches', 9),
(86, '62650', 'DG', '2025-04-16 08:54:17', 'Documentation Generale', 9),
(87, '6270', 'AAC', '2025-04-16 08:54:17', 'Autres activites de COM', 9),
(88, '6271', 'PI', '2025-04-16 08:54:17', 'Annonces, insertions', 9),
(89, '6277', 'CSC', '2025-04-16 08:54:17', 'Frais de colloques, séminaires, conférences', 9),
(90, '62781', 'AAC', '2025-04-16 08:54:17', 'Autre activité de comm', 9),
(91, '6281', 'CT', '2025-04-16 08:54:17', 'Frais de téléphone', 9),
(92, '6283', 'Fax', '2025-04-16 08:54:17', 'Fax', 9),
(93, '6288', 'PRP', '2025-04-16 08:54:17', 'Autres charges de publicité et relations publiques', 9),
(94, '6324', 'HI', '2025-04-16 08:54:17', 'Honoraires internes', 10),
(95, '6325', 'FA', '2025-04-16 08:54:17', 'Frais dactes', 10),
(96, '632801', 'FR', '2025-04-16 08:54:17', 'Frais de représentation', 10),
(97, '633', 'FFP', '2025-04-16 08:54:17', 'Frais formation personnel', 10),
(98, '635811', 'RFL', '2025-04-16 08:54:17', 'Remboursement frais lunettes', 10),
(99, '635812', 'ACE', '2025-04-16 08:54:17', 'Analyses consultations étudiants', 10),
(100, '635813', 'SEE', '2025-04-16 08:54:17', 'Soins externes étudiants', 10),
(101, '635814', 'HE', '2025-04-16 08:54:17', 'Hospitalisation étudiants', 10),
(102, '635815', 'SA', '2025-04-16 08:54:17', 'Subventions accordées aux amicales', 10),
(103, '63830', 'AS', '2025-04-16 08:54:17', 'Activités sportives', 10),
(104, '63831', 'AC', '2025-04-16 08:54:17', 'Activités culturelles', 10),
(105, '63832', 'Rec', '2025-04-16 08:54:17', 'Réception', 10),
(106, '6384', 'Mis', '2025-04-16 08:54:17', 'Mission', 10),
(107, '6462', 'DE', '2025-04-16 08:54:17', 'Droits dEnregistrement', 11),
(108, '658', 'CD', '2025-04-16 08:54:17', 'Charges Diverses', 12),
(109, '6581', 'IF', '2025-04-16 08:54:17', 'Indemnités de fonction et autres rémunérations d\'administrateurs', 12),
(110, '6582', 'SAS', '2025-04-16 08:54:17', 'Secours Activites Sociales', 12),
(111, '6584', 'ISR', '2025-04-16 08:54:17', 'Indemnisation sous-traitants restaurants', 12),
(112, '6598', 'ACP', '2025-04-16 08:54:17', 'Autres Charges Provisionnees', 12),
(113, '66111', 'RPP', '2025-04-16 08:54:17', 'Rémunération personnel permanent', 13),
(114, '66112', 'RPT', '2025-04-16 08:54:17', 'Rémunération personnel Temporaire', 13),
(115, '6617', 'MT', '2025-04-16 08:54:17', 'Médaille de travail', 13),
(116, '6638', 'IA', '2025-04-16 08:54:17', 'Indemnités Avantages', 13),
(117, '66841', 'HP', '2025-04-16 08:54:17', 'Hospitalisation personnel', 13),
(118, '66842', 'ACP', '2025-04-16 08:54:17', 'Analyses consultations personnel', 13),
(119, '66843', 'AP', '2025-04-16 08:54:17', 'Accouchement personnel', 13),
(120, '66844', 'SEP', '2025-04-16 08:54:17', 'Soins externes personnel', 13),
(121, '671', 'CF', '2025-04-16 08:54:17', 'Charges Financieres', 14),
(122, '68131', 'DAMCu', '2025-04-16 08:54:17', 'Dotation aux amort. Mat. Cuisine', 15),
(123, '68132', 'DAME', '2025-04-16 08:54:17', 'Dotation aux amort. Mat. D\' exploitation', 15),
(124, '68133', 'DAMB', '2025-04-16 08:54:17', 'Dotation aux amort. Mat. Bureau', 15),
(125, '68134', 'DAMCi', '2025-04-16 08:54:17', 'Dotation aux amort. Mat. Cités', 15),
(126, '68135', 'DAAI', '2025-04-16 08:54:17', 'Dotation aux amort. Ag. Installation', 15),
(127, '68136', 'DAMT', '2025-04-16 08:54:17', 'Dotation aux amort. Mat.Transport', 15),
(128, '68137', 'DACR', '2025-04-16 08:54:17', 'Dotation aux amort. Charges à rétablir', 15),
(129, '70611', 'VRS', '2025-04-16 08:54:17', 'Vente de repas Subv', 16),
(130, '70612', 'VPDS', '2025-04-16 08:54:17', 'Vente petit déjner Subv,', 16),
(131, '70613', 'VRE', '2025-04-16 08:54:17', 'Vente de repas express', 16),
(132, '70614', 'LS', '2025-04-16 08:54:17', 'Loyers subventionnés', 16),
(133, '70615', 'LP', '2025-04-16 08:54:17', 'Loyers « passager »', 16),
(134, '70616', 'VM', '2025-04-16 08:54:17', 'Vente de médicaments', 16),
(135, '70617', 'CE', '2025-04-16 08:54:17', 'Consultations externes (imagerie médicale+divers)', 16),
(136, '70618', 'VTC', '2025-04-16 08:54:17', 'Vente tickets / consultation', 16),
(137, '706622', 'VP', '2025-04-16 08:54:17', 'Vente de Protheses', 16),
(138, '707121', 'PE', '2025-04-16 08:54:17', 'Part Electricite', 16),
(139, '70731', 'LPP', '2025-04-16 08:54:17', 'Location panneaux pub', 16),
(140, '70732', 'LSST', '2025-04-16 08:54:17', 'Location terrain de foot', 16),
(141, '70733', 'LSS', '2025-04-16 08:54:17', 'Location salles de spectacle', 16),
(142, '70734', 'LC', '2025-04-16 08:54:17', 'Location « Cantines »', 16),
(143, '70735', 'LCC', '2025-04-16 08:54:17', 'Location Centre Commercial', 16),
(144, '70781', 'IC', '2025-04-16 08:54:17', 'Imprimerie du COUD', 16),
(145, '70782', 'VAO', '2025-04-16 08:54:17', 'Vente dossiers d\'appel d\'offres', 16),
(146, '70783', 'VGBO', '2025-04-16 08:54:17', 'Vente de guides des œuvres', 16),
(147, '70784', 'USS', '2025-04-16 08:54:17', 'Vente Cartes Transport USSEIN', 16),
(148, '7181', 'CES', '2025-04-16 08:54:17', 'Contribution Etat du Sénégal', 17),
(149, '7182', 'CAEO', '2025-04-16 08:54:17', 'Contribution autres Etats et Organismes', 17),
(150, '7183', 'CP', '2025-04-16 08:54:17', 'Contributions versées par des tiers', 17),
(151, '7184', 'RCE', '2025-04-16 08:54:17', 'Rallonge Contribution Etat du Senegal', 17),
(152, '754', 'PRSA', '2025-04-16 08:54:17', 'Produits Resultant de Subventions Amorties', 18),
(153, '7583', 'PD', '2025-04-16 08:54:17', 'Autres Produits Divers', 18),
(154, '7584', 'RIT', '2025-04-16 08:54:17', 'Retenues imputables à des tiers', 18),
(155, '822', 'CIC', '2025-04-16 08:54:17', 'Produits des cessions d\'immobilisations corporelles', 19),
(156, '8651', 'RSI', '2025-04-16 08:54:17', 'Reprise Subvention dInvestiisement', 20),
(158, '70736', NULL, '2026-09-18 18:11:57', 'Location kiosques', 16),
(159, '7582', NULL, '2026-09-18 18:11:57', 'Indemnités d\'assurances reçues', 18),
(160, '7585', NULL, '2026-09-18 18:11:57', 'Reprises sur caution', 18),
(161, '845', NULL, '2026-09-18 18:11:57', 'Dons et libéralités obtenus', NULL),
(162, '604127', NULL, '2026-09-18 18:11:57', 'Petit matériel d\'entretien', 7),
(163, '604128', NULL, '2026-09-18 18:11:57', 'Petit matériel et équipement de décoration', 7),
(164, '60441', NULL, '2026-09-18 18:11:57', 'Fournitures d\'imprimerie', 7),
(165, '60477', NULL, '2026-09-18 18:11:57', 'Consommables informatiques', 7),
(166, '60481', NULL, '2026-09-18 18:11:57', 'Petit matériel de communication', 7),
(167, '6223', NULL, '2026-09-18 18:11:57', 'Locations de matériels et outillages', 9),
(168, '624151', NULL, '2026-09-18 18:11:57', 'Entretien et réfection bâtiments admin', 9),
(169, '624152', NULL, '2026-09-18 18:11:57', 'Entretien et aménagements', 9),
(170, '62482', NULL, '2026-09-18 18:11:57', 'Autres entretiens et réparations', 9),
(171, '62581', NULL, '2026-09-18 18:11:57', 'Assurance incendie', 9),
(172, '6265', NULL, '2026-09-18 18:11:57', 'Documentation générale', 9),
(173, '63251', NULL, '2026-09-18 18:11:57', 'Frais d\' actes', 10),
(174, '63252', NULL, '2026-09-18 18:11:57', 'Frais de contentieux', 10),
(175, '6327', NULL, '2026-09-18 18:11:57', 'Rémunérations des autres prestations de services', 10),
(176, '631', NULL, '2026-09-18 18:11:57', 'Frais bancaires', 10),
(177, '634', NULL, '2026-09-18 18:11:57', 'Redevances pour brevets, licences, logiciels et droits similaires', 10),
(178, '635816', NULL, '2026-09-18 18:11:57', 'Autres subventions accordées', 10),
(179, '6388', NULL, '2026-09-18 18:11:57', 'Charges externes diverses', 10),
(180, '641', NULL, '2026-09-18 18:11:57', 'Impôts et taxes directs', 11),
(181, '646', NULL, '2026-09-18 18:11:57', 'Droit d\'enregistrement', 11),
(182, '647', NULL, '2026-09-18 18:11:57', 'Pénalités et amendes fiscales', 11),
(183, '6580', NULL, '2026-09-18 18:11:57', 'Charges Diverses', 12),
(184, '6585', NULL, '2026-09-18 18:11:57', 'Secours Activités Sociales', 12),
(185, '664', NULL, '2026-09-18 18:11:57', 'Charges sociales', 13),
(186, '6688', NULL, '2026-09-18 18:11:57', 'Charges sociales diverses', 13),
(187, '674', NULL, '2026-09-18 18:11:57', 'Charges Financieres', 14),
(188, '14', NULL, '2026-09-18 18:11:57', 'Subvention d\'Investissement', 1),
(189, '2440', NULL, '2026-09-18 18:11:57', 'Matériel et Mobilier de bureau', 4),
(190, '24481', NULL, '2026-09-18 18:11:57', 'Materiel et mobilier de cités', 4),
(191, '2484', NULL, '2026-09-18 18:11:57', 'Matériel Services Médicaux', 4),
(192, '2488', NULL, '2026-09-18 18:11:57', 'Divers matériels et mobiliers', 4);

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
  `an` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idUser` int DEFAULT NULL,
  `idCompte` int DEFAULT NULL,
  PRIMARY KEY (`idDot`),
  KEY `idUser` (`idUser`),
  KEY `idCompte` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_dotations`
--

INSERT INTO `bud_dotations` (`idDot`, `date`, `volume`, `dateSys`, `type`, `an`, `idUser`, `idCompte`) VALUES
(1, '2026-09-18', 1321000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 188),
(2, '2026-09-18', 50000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 8),
(3, '2026-09-18', 80000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 9),
(4, '2026-09-18', 10000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 10),
(5, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 11),
(6, '2026-09-18', 200000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 189),
(7, '2026-09-18', 140000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 13),
(8, '2026-09-18', 420000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 190),
(9, '2026-09-18', 116000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 15),
(10, '2026-09-18', 40000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 17),
(11, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 18),
(12, '2026-09-18', 220000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 191),
(13, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 192),
(14, '2026-09-18', 75000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 30),
(15, '2026-09-18', 73000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 31),
(16, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 32),
(17, '2026-09-18', 100000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 33),
(18, '2026-09-18', 100000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 34),
(19, '2026-09-18', 100000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 35),
(20, '2026-09-18', 80000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 36),
(21, '2026-09-18', 60000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 37),
(22, '2026-09-18', 70000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 38),
(23, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 39),
(24, '2026-09-18', 88000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 40),
(25, '2026-09-18', 90000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 41),
(26, '2026-09-18', 45000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 42),
(27, '2026-09-18', 19000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 43),
(28, '2026-09-18', 70000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 44),
(29, '2026-09-18', 39000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 162),
(30, '2026-09-18', 160000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 45),
(31, '2026-09-18', 10000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 46),
(32, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 47),
(33, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 48),
(34, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 49),
(35, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 50),
(36, '2026-09-18', 140000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 51),
(37, '2026-09-18', 120000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 52),
(38, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 53),
(39, '2026-09-18', 140000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 54),
(40, '2026-09-18', 140000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 55),
(41, '2026-09-18', 300000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 56),
(42, '2026-09-18', 80000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 57),
(43, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 60),
(44, '2026-09-18', 5860000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 61),
(45, '2026-09-18', 40000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 62),
(46, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 167),
(47, '2026-09-18', 4000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 64),
(48, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 65),
(49, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 66),
(50, '2026-09-18', 45000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 67),
(51, '2026-09-18', 2720745000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 168),
(52, '2026-09-18', 705000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 169),
(53, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 69),
(54, '2026-09-18', 10000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 70),
(55, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 78),
(56, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 79),
(57, '2026-09-18', 22000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 80),
(58, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 81),
(59, '2026-09-18', 35000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 82),
(60, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 170),
(61, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 83),
(62, '2026-09-18', 44000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 85),
(63, '2026-09-18', 2000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 172),
(64, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 88),
(65, '2026-09-18', 25000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 89),
(66, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 90),
(67, '2026-09-18', 30000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 91),
(68, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 93),
(69, '2026-09-18', 2000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 176),
(70, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 173),
(71, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 175),
(72, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 97),
(73, '2026-09-18', 14000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 177),
(74, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 98),
(75, '2026-09-18', 150000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 99),
(76, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 100),
(77, '2026-09-18', 70000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 101),
(78, '2026-09-18', 65000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 102),
(79, '2026-09-18', 70000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 178),
(80, '2026-09-18', 67000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 103),
(81, '2026-09-18', 64000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 104),
(82, '2026-09-18', 3000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 105),
(83, '2026-09-18', 20000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 106),
(84, '2026-09-18', 270000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 179),
(85, '2026-09-18', 200000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 181),
(86, '2026-09-18', 80000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 183),
(87, '2026-09-18', 26597350.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 109),
(88, '2026-09-18', 70000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 184),
(89, '2026-09-18', 11000000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 113),
(90, '2026-09-18', 2100000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 114),
(91, '2026-09-18', 10000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 115),
(92, '2026-09-18', 60000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 116),
(93, '2026-09-18', 500000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 185),
(94, '2026-09-18', 25000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 117),
(95, '2026-09-18', 40000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 118),
(96, '2026-09-18', 10000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 119),
(97, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 120),
(98, '2026-09-18', 80070375.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 186),
(99, '2026-09-18', 250000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 130),
(100, '2026-09-18', 280000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 132),
(101, '2026-09-18', 10000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 134),
(102, '2026-09-18', 90000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 135),
(103, '2026-09-18', 2000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 136),
(104, '2026-09-18', 21000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 139),
(105, '2026-09-18', 2000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 141),
(106, '2026-09-18', 31000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 142),
(107, '2026-09-18', 10000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 143),
(108, '2026-09-18', 2000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 158),
(109, '2026-09-18', 3000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 145),
(110, '2026-09-18', 1000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 146),
(111, '2026-09-18', 25120015375.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 148),
(112, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 149),
(113, '2026-09-18', 2000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 150),
(114, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 153),
(115, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 154),
(116, '2026-09-18', 15000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 160),
(117, '2026-09-18', 9597350.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 155),
(118, '2026-09-18', 5000000.00, '2026-09-18 18:25:15', 'initiale', '2026', 1, 161);

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
  `idMarche` int DEFAULT NULL,
  PRIMARY KEY (`idEng`),
  KEY `idFourn` (`idFourn`),
  KEY `idCompte` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_engagements`
--

INSERT INTO `bud_engagements` (`idEng`, `dateEng`, `type_eng`, `objet`, `bc`, `montant`, `dateSys`, `idFourn`, `idCompte`, `idUser`, `idMarche`) VALUES
(15, '2026-08-30', 'Services', 'service formation informatique', NULL, 850000.00, '2026-08-30 12:30:43', 4, 2, 0, 3),
(16, '2026-09-01', 'Fourniture de biens', 'irurir hfhfhf', NULL, 950000.00, '2026-09-01 17:30:34', 4, 18, 0, 4),
(17, '2026-09-03', 'Travaux', 'travaux de reffection DI', NULL, 750000.00, '2026-09-03 12:39:15', 5, 110, 0, 5),
(18, '2026-11-07', 'DRP', 'papier photocopie', NULL, 2000000.00, '2026-11-07 17:15:02', 4, 18, 0, 7),
(19, '2026-11-09', 'Marché', 'travaux bureau', NULL, 25610000.00, '2026-11-09 15:54:48', 4, 11, 0, 8);

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
  `idMarche` int DEFAULT NULL,
  PRIMARY KEY (`idEng`),
  KEY `idFourn` (`idFourn`),
  KEY `idCompte` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `numAgrement` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fichierAgrement` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idFourn`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_fournisseur`
--

INSERT INTO `bud_fournisseur` (`idFourn`, `numFourn`, `adresse`, `nom`, `nature`, `ninea`, `rccm`, `email`, `observations`, `dateSys`, `contact`, `numAgrement`, `fichierAgrement`) VALUES
(1, 'EMD', 'malicounda', 'Cellule information', 'repreneur', NULL, NULL, NULL, NULL, '2025-04-16 11:27:02', '784413400', NULL, NULL),
(2, 'EL', 'dakar', 'ELTON', 'bénéficiaire', NULL, NULL, NULL, NULL, '2025-04-24 11:35:11', '764019147', NULL, NULL),
(3, 'YYY', 'mbour', 'bud', 'beneficiaire', NULL, NULL, NULL, NULL, '2026-05-13 10:40:52', '754019647', NULL, NULL),
(4, 'F001', 'mbour', 'MAYFAY GLOBAL BUSINESS', 'Entreprise', '78336339722', 'SB-SJHSJS-4252', 'diopelhadjimadiop@gmail.com', 'dhdd dhdhhd', '2026-08-29 20:01:52', '221774412344', NULL, NULL),
(5, 'atla-001', 'Dakar', 'ATLAS', 'Entreprise', 'AA23456789JJ', 'SB-SJHSJS-4252', 'diopelhadjimadiop@gmail.com', 'bdgd dhh', '2026-09-03 11:28:05', '221774412344', NULL, NULL),
(6, 'atla-0078', 'Dakar', 'ATLAS 2', 'Association', 'AA23456789JJ', 'SB-SJHSJS-4252', 'diopelhadjimadiop@gmail.com', 'test', '2026-09-15 10:51:37', '221774412344', 'yeye-etet', 'uploads/agrements/agrement_6aa92339604c6.pdf');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_operations`
--

INSERT INTO `bud_operations` (`idOp`, `typeOp`, `dateOp`, `montant`, `numFact`, `dateSys`, `idEng`, `idBordereau`) VALUES
(11, 'paiement', '2026-11-09', 5000000, 'erty fghj', '2026-11-09 16:04:36', 19, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `priv_dba` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sexe` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_mdp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `date_sys` date NOT NULL,
  `an` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `log` (`log`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bud_users`
--

INSERT INTO `bud_users` (`idUser`, `nom`, `log`, `mdp`, `email`, `priv`, `priv_dba`, `telephone`, `sexe`, `type_mdp`, `statut`, `date_sys`, `an`) VALUES
(1, 'Madiop DIOP', '936076/M', '9ead80632f1a0ff63cc214fa50b034ae7f48dde4', 'diopelhadjimadiop@gmail.com', 'admin', NULL, NULL, 'M', 'updated', 1, '2025-04-16', NULL),
(2, 'Modou Waly FAYE', '902000/A', 'f6a7651443d5867f394fe61ab082aac01c3c25fd', 'fayefaye@gmail.com', 'sag', NULL, NULL, NULL, 'updated', 1, '2025-04-23', NULL),
(3, 'Ibrahima DIOP', '936009/C', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'khalil@hotmail.com', 'op', NULL, NULL, NULL, 'updated', 1, '2025-04-29', NULL),
(4, 'Papa Amath Ndiaye', '934343/H', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'amathcoud@gmail.com', 'admin', NULL, '771565419', 'M', 'updated', 1, '0000-00-00', '2026'),
(5, 'Mamadou diop', '80024/M', '9ead80632f1a0ff63cc214fa50b034ae7f48dde4', 'diopmamadou@gmail.com', 'Cf_D', NULL, '+221784413400', 'M', 'default', 1, '0000-00-00', '2026'),
(7, 'El Hadji Madiop diop', 'op_all', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'op_all@gmail.com', 'op_all', NULL, '+221784413400', 'F', 'updated', 1, '0000-00-00', '2026'),
(8, 'op val', 'op_val', '9ead80632f1a0ff63cc214fa50b034ae7f48dde4', 'op_val@gmail.com', 'op_val', NULL, '+221784413400', 'M', 'default', 1, '0000-00-00', '2026'),
(9, 'eng val', 'eng_val', '11af43dbc3e4d14f498633eba99515ce2d3fd9fc', 'eng_val@gmail.com', 'eng_val', NULL, '+221764019647', 'F', 'updated', 1, '0000-00-00', '2026'),
(10, 'drp', 'drp', 'f2e593008db5ab3395b2c88aab307c4794532fe1', 'drp@coud.sn', 'drp', NULL, '7844413400', 'M', 'updated', 1, '0000-00-00', '2026'),
(11, 'direction', 'direction', '57077e98504c8948f5cf8fde85ac095816ddf412', 'direction@coud.sn', 'direction', NULL, NULL, 'G', 'updated', 1, '0000-00-00', '2026'),
(12, 'controle', 'controle', '2ffa4200de5cfb515381db208e55b57f15edc28a', 'control@coud.sn', 'controle', 'dba_controle', NULL, 'G', 'updated', 1, '0000-00-00', '2026'),
(13, 'dba_direction', 'dba_direction', 'acdcc293ee0879f8d9d1456ba34f588ca07c00ef', 'dba_direction@coud.sn', 'direction', 'dba_direction', '7844413400', 'G', 'updated', 1, '0000-00-00', '2024');

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
  `nature` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_marche` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `objet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_fournisseur` int NOT NULL,
  `statut` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `statut_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  KEY `idx_type_marche` (`nature`),
  KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sigm_marches`
--

INSERT INTO `sigm_marches` (`id`, `reference`, `annee`, `montant`, `nature`, `type_marche`, `objet`, `id_fournisseur`, `statut`, `statut_2`, `motif_annulation`, `created_by`, `validated_by`, `cancelled_by`, `date_creation`, `date_validation`, `date_annulation`, `updated_at`) VALUES
(1, 'RF-25-003', '2026', 200000.00, 'Fourniture de biens', '', 'azertyui', 0, 'direction', NULL, NULL, NULL, NULL, NULL, '2026-08-29 11:26:17', '2026-08-29 11:41:54', NULL, '2026-09-25 14:01:15'),
(2, 'EB-2026-0001', '2026', 390000.00, 'Travaux', '', 'sdfghj', 2, 'rejete', NULL, 'manque la facture', NULL, NULL, 10, '2026-08-29 12:22:54', NULL, '2026-09-21 13:13:28', '2026-09-21 13:13:28'),
(3, 'RF-25-001', '2026', 850000.00, 'Services', '', 'service formation informatique', 4, 'engager', NULL, NULL, 10, 10, NULL, '2026-08-29 20:17:56', '2026-08-29 21:03:06', NULL, '2026-08-30 12:32:33'),
(4, 'RF-25-034', '2026', 950000.00, 'Fourniture de biens', '', 'irurir hfhfhf', 4, 'engager', NULL, NULL, 10, 10, NULL, '2026-09-01 17:17:59', '2026-09-01 17:24:10', NULL, '2026-09-01 17:30:34'),
(5, 'RF-25-039', '2026', 750000.00, 'Travaux', '', 'travaux de reffection DI', 5, 'engager', NULL, 'DOSSIER imcomplet', 10, 10, 10, '2026-09-03 11:46:56', '2026-09-03 12:21:11', '2026-09-03 12:10:37', '2026-09-03 12:39:15'),
(6, 'tyh-hhg', '2026', 4500000.00, 'Fourniture de biens', 'DRP', 'machine ordi', 4, 'rejete', NULL, 'test', 10, 11, 10, '2026-09-21 16:20:03', '2026-09-21 16:52:40', '2026-09-21 17:11:22', '2026-09-21 17:11:22'),
(7, 'AA-26-002', '2026', 2000000.00, 'Prestation intellectuelle', 'DRP', 'papier photocopie', 4, 'engager', NULL, NULL, 10, 12, NULL, '2026-11-03 09:37:01', '2026-11-07 16:34:33', NULL, '2026-11-07 17:15:02'),
(8, 'RF-26-424', '2026', 25610000.00, 'Travaux', 'Marché', 'travaux bureau', 4, 'engager', NULL, NULL, 10, 12, NULL, '2026-11-09 15:39:03', '2026-11-09 15:47:43', NULL, '2026-11-09 15:54:48');

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sigm_marche_documents`
--

INSERT INTO `sigm_marche_documents` (`id`, `marche_id`, `nom_original`, `nom_fichier`, `chemin_fichier`, `type_document`, `extension`, `taille`, `statut`, `uploaded_by`, `date_upload`) VALUES
(1, 1, 'BUDGET (3).xlsx', 'MARCHE_1_b14ab61e8703ab9c.xlsx', 'uploads/marches/MARCHE_1_b14ab61e8703ab9c.xlsx', 'pva', 'xlsx', 10115, 'valide', NULL, '2026-08-29 11:26:17'),
(2, 1, 'Marchés.docx', 'MARCHE_1_cedfc03847256981.docx', 'uploads/marches/MARCHE_1_cedfc03847256981.docx', 'facture', 'docx', 104981, 'valide', NULL, '2026-08-29 11:26:17'),
(3, 1, 'facture-001396.pdf', 'MARCHE_1_91d8d58730da1aa7.pdf', 'uploads/marches/MARCHE_1_91d8d58730da1aa7.pdf', 'preforma', 'pdf', 140825, 'valide', NULL, '2026-08-29 11:26:17'),
(4, 2, 'Ameliorations_Medicoud.pdf', 'MARCHE_2_1ab35d75e0934940.pdf', 'uploads/marches/MARCHE_2_1ab35d75e0934940.pdf', 'gf', 'pdf', 143551, 'annule', NULL, '2026-08-29 12:22:54'),
(5, 2, 'facture-001396.pdf', 'MARCHE_2_4634e4200e76d7dc.pdf', 'uploads/marches/MARCHE_2_4634e4200e76d7dc.pdf', 'fd', 'pdf', 140825, 'annule', NULL, '2026-08-29 12:22:54'),
(6, 3, 'Ameliorations_Medicoud.pdf', 'MARCHE_3_a73478e02bc9fa4b.pdf', 'uploads/marches/MARCHE_3_a73478e02bc9fa4b.pdf', 'contrat', 'pdf', 143551, 'valide', 10, '2026-08-29 20:17:56'),
(7, 3, 'facture-001396.pdf', 'MARCHE_3_726bfe2f2f9ad454.pdf', 'uploads/marches/MARCHE_3_726bfe2f2f9ad454.pdf', 'facture', 'pdf', 140825, 'valide', 10, '2026-08-29 20:17:56'),
(8, 4, 'Marchés.docx', 'MARCHE_4_cfb73f1ea4603a3a.docx', 'uploads/marches/MARCHE_4_cfb73f1ea4603a3a.docx', 'devis', 'docx', 104981, 'valide', 10, '2026-09-01 17:17:59'),
(9, 4, 'CPM.docx', 'MARCHE_4_f60f0002318b23a9.docx', 'uploads/marches/MARCHE_4_f60f0002318b23a9.docx', 'pvo', 'docx', 19249, 'valide', 10, '2026-09-01 17:17:59'),
(10, 4, 'Ameliorations_Medicoud.pdf', 'MARCHE_4_69296516184eecce.pdf', 'uploads/marches/MARCHE_4_69296516184eecce.pdf', 'contrat', 'pdf', 143551, 'valide', 10, '2026-09-01 17:17:59'),
(11, 4, 'facture-001396.pdf', 'MARCHE_4_a93ccbdddeecc1e3.pdf', 'uploads/marches/MARCHE_4_a93ccbdddeecc1e3.pdf', 'facture', 'pdf', 140825, 'valide', 10, '2026-09-01 17:17:59'),
(12, 5, 'Ameliorations_Medicoud.pdf', 'MARCHE_5_4d59b9a87224d9e1.pdf', 'uploads/marches/MARCHE_5_4d59b9a87224d9e1.pdf', 'DEVIS', 'pdf', 143551, '', 10, '2026-09-03 11:46:56'),
(13, 5, 'facture-001396.pdf', 'MARCHE_5_f2bda8a1009b909b.pdf', 'uploads/marches/MARCHE_5_f2bda8a1009b909b.pdf', 'FACTURE', 'pdf', 140825, '', 10, '2026-09-03 11:46:56'),
(14, 6, 'Authentification SSO CampusCOUD.docx', 'MARCHE_6_ef7a08524f97cde8.docx', 'uploads/marches/MARCHE_6_ef7a08524f97cde8.docx', 'facture', 'docx', 14717, 'en_attente', 10, '2026-09-21 16:20:03'),
(15, 6, 'demande_coud.pdf', 'MARCHE_6_14877f58b7a52908.pdf', 'uploads/marches/MARCHE_6_14877f58b7a52908.pdf', 'contrat', 'pdf', 555455, 'en_attente', 10, '2026-09-21 16:20:03'),
(16, 7, 'Ameliorations_Medicoud.pdf', 'MARCHE_7_032584a2dd82364f.pdf', 'uploads/marches/MARCHE_7_032584a2dd82364f.pdf', 'contrat', 'pdf', 143551, '', 10, '2026-11-03 09:37:01'),
(17, 7, 'facture-001396.pdf', 'MARCHE_7_48572f75fef87106.pdf', 'uploads/marches/MARCHE_7_48572f75fef87106.pdf', 'facture', 'pdf', 140825, '', 10, '2026-11-03 09:37:01'),
(19, 7, 'projet_these_elhadji.pdf', 'MARCHE_7_22f79eae86116a02.pdf', 'uploads/marches/MARCHE_7_22f79eae86116a02.pdf', 'Autorisation d\'engagement', 'pdf', 126064, '', 0, '2026-11-07 16:00:45'),
(20, 7, 'facture-001396.pdf', 'MARCHE_7_d3249bf940a2cf74.pdf', 'uploads/marches/MARCHE_7_d3249bf940a2cf74.pdf', 'BE cacheté', 'pdf', 140825, '', 0, '2026-11-09 10:57:17'),
(21, 8, 'Ameliorations_Medicoud.pdf', 'MARCHE_8_707db9265a22fb83.pdf', 'uploads/marches/MARCHE_8_707db9265a22fb83.pdf', 'contrat', 'pdf', 143551, '', 10, '2026-11-09 15:39:03'),
(22, 8, 'facture-001396.pdf', 'MARCHE_8_8d3658076f1e974c.pdf', 'uploads/marches/MARCHE_8_8d3658076f1e974c.pdf', 'facture', 'pdf', 140825, '', 10, '2026-11-09 15:39:03'),
(23, 8, 'Liste des comptes.pdf', 'MARCHE_8_eb124fd7764195fa.pdf', 'uploads/marches/MARCHE_8_eb124fd7764195fa.pdf', 'Autorisation d\'engagement', 'pdf', 17190, '', 0, '2026-11-09 15:42:56');

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sigm_marche_historique`
--

INSERT INTO `sigm_marche_historique` (`id`, `marche_id`, `ancien_statut`, `nouveau_statut`, `commentaire`, `user_id`, `date_action`) VALUES
(1, 1, NULL, 'en_attente', 'Création du dossier de marché.', NULL, '2026-08-29 11:26:17'),
(2, 1, 'en_attente', 'valide', 'Dossier validé.', NULL, '2026-08-29 11:41:54'),
(3, 2, NULL, 'en_attente', 'Création du dossier de marché.', NULL, '2026-08-29 12:22:54'),
(4, 2, 'en_attente', 'annule', 'dossier incomplet!', NULL, '2026-08-29 12:24:34'),
(5, 3, NULL, 'en_attente', 'Création du dossier de marché pour le fournisseur : MAYFAY GLOBAL BUSINESS', 10, '2026-08-29 20:17:56'),
(6, 3, 'en_attente', 'valide', 'Dossier validé.', 10, '2026-08-29 21:03:06'),
(7, 4, NULL, 'en_attente', 'Création du dossier de marché pour le fournisseur : MAYFAY GLOBAL BUSINESS', 10, '2026-09-01 17:17:59'),
(8, 4, 'en_attente', 'valide', 'Dossier validé.', 10, '2026-09-01 17:24:10'),
(9, 2, '', 'en_attente', 'Modification du dossier de marché (référence : EB-2026-0001)', 10, '2026-09-01 20:28:39'),
(10, 2, '', 'en_attente', 'Modification du dossier de marché (référence : EB-2026-0001)', 10, '2026-09-01 20:32:27'),
(11, 5, NULL, 'en_attente', 'Création du dossier de marché pour le fournisseur : ATLAS', 10, '2026-09-03 11:46:56'),
(12, 5, 'en_attente', '', 'DOSSIER imcomplet', 10, '2026-09-03 12:10:37'),
(13, 5, '', 'en_attente', 'Modification du dossier de marché (référence : RF-25-039)', 10, '2026-09-03 12:21:04'),
(14, 5, 'en_attente', 'valide', 'Dossier validé.', 10, '2026-09-03 12:21:11'),
(15, 2, 'en_attente', '', 'manque la facture', 10, '2026-09-21 13:13:28'),
(16, 6, NULL, 'en_attente', 'Création du dossier de marché pour le fournisseur : MAYFAY GLOBAL BUSINESS', 10, '2026-09-21 16:20:03'),
(17, 6, '', '', 'Dossier validé et transmis à l\'étape suivante.', 11, '2026-09-21 16:52:40'),
(18, 6, '', '', 'Dossier annulé à l\'étape controle et retourné en entente. Motif : incomplet dossier ;', 12, '2026-09-21 17:01:42'),
(19, 6, '', 'en_attente', 'Modification du dossier de marché (référence : tyh-hhg)', 10, '2026-09-21 17:07:40'),
(20, 6, '', '', 'Dossier annulé à l\'étape en_entente et retourné en entente. Motif : test', 10, '2026-09-21 17:11:22'),
(21, 7, NULL, '', 'Création du dossier de marché pour le fournisseur : MAYFAY GLOBAL BUSINESS', 10, '2026-11-03 09:37:01'),
(22, 7, '', '', 'Dossier validé et transmis à l\'étape suivante.', 10, '2026-11-03 09:37:48'),
(23, 7, '', '', 'Dossier validé et transmis à l\'étape suivante.', 13, '2026-11-07 16:25:37'),
(24, 7, '', '', 'Dossier validé et transmis à l\'étape suivante.', 12, '2026-11-07 16:34:33'),
(25, 8, NULL, '', 'Création du dossier de marché pour le fournisseur : MAYFAY GLOBAL BUSINESS', 10, '2026-11-09 15:39:03'),
(26, 8, '', '', 'Dossier validé et transmis à l\'étape suivante.', 10, '2026-11-09 15:40:57'),
(27, 8, '', '', 'Dossier validé et transmis à l\'étape suivante.', 13, '2026-11-09 15:44:16'),
(28, 8, '', '', 'Dossier validé et transmis à l\'étape suivante.', 12, '2026-11-09 15:47:43');

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
