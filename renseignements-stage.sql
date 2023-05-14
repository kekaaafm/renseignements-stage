-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 14 mai 2023 à 22:59
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `renseignements-stage`
--

-- --------------------------------------------------------

--
-- Structure de la table `anneescolaire`
--

CREATE TABLE `anneescolaire` (
  `idAnneeScolaire` int(11) NOT NULL,
  `libAnneeScolaire` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `anneescolaire`
--

INSERT INTO `anneescolaire` (`idAnneeScolaire`, `libAnneeScolaire`) VALUES
(1, '2021/2022'),
(2, '2022/2023'),
(3, '2023/2024');

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `idContact` int(11) NOT NULL,
  `titreContact` varchar(3) NOT NULL,
  `nomContact` varchar(50) NOT NULL,
  `prenomContact` varchar(50) NOT NULL,
  `mobileContact` varchar(15) NOT NULL,
  `fixeContact` varchar(15) NOT NULL,
  `mailContact` varchar(100) NOT NULL,
  `isRespContact` tinyint(1) NOT NULL,
  `isActifContact` tinyint(1) NOT NULL,
  `idFonction` int(11) NOT NULL,
  `idEntreprise` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`idContact`, `titreContact`, `nomContact`, `prenomContact`, `mobileContact`, `fixeContact`, `mailContact`, `isRespContact`, `isActifContact`, `idFonction`, `idEntreprise`) VALUES
(1, 'Mr', 'contactN', 'contactP', '0123456789', '', 'mail@contact.fr', 1, 1, 4, 6),
(2, 'Mr', 'MAGUEUR', 'Marc', '0784158903', '', 'dev@marc-magueur.dev', 1, 1, 23, 7),
(3, 'Mr', 'Bidden', 'Joe', '9874563210', '', 'joe@biden.fr', 1, 1, 249, 8),
(7, 'Mr', 'nom', 'prenom', '0123456789', '', 'test@test.test', 0, 1, 4, 8),
(8, 'Mr', 'test2', 'prénom2', '0606060606', '0505050505', 'mail@mail.mail', 0, 1, 244, 8),
(9, 'Mr', 'test3', 'test3', '99999', '55555', 'test3@gpa2vie.fr', 0, 1, 171, 8),
(10, 'Mr', 'Musk', 'Elon', '0945728934', '0545728934', 'ElonMusk@SpaceX.com', 1, 1, 249, 9);

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

CREATE TABLE `eleve` (
  `idEleve` int(11) NOT NULL,
  `dateNaissanceEleve` date NOT NULL,
  `numAdrEleve` int(11) DEFAULT NULL,
  `libAdrEleve` varchar(50) NOT NULL,
  `codePostalAdrEleve` varchar(10) NOT NULL,
  `villeAdrEleve` varchar(20) NOT NULL,
  `dateRentreeEleve` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `eleve`
--

INSERT INTO `eleve` (`idEleve`, `dateNaissanceEleve`, `numAdrEleve`, `libAdrEleve`, `codePostalAdrEleve`, `villeAdrEleve`, `dateRentreeEleve`) VALUES
(2, '2004-01-17', 7, 'rue ponscapdenier', '31500', 'Toulouse', '2023-04-14'),
(3, '2004-01-17', 7, 'rue du tennis', '31700', 'Blagnac', '2023-04-14'),
(4, '2004-01-17', 69, 'rue', '31', 'blagnac', '2023-04-16'),
(5, '2004-12-17', 7, 'rue', '31500', 'caca', '2023-04-17'),
(6, '2000-01-12', 47, '47', '47', '47', '2023-04-17'),
(7, '0000-00-00', 47, 'test', '31500', 'bbbb', '2023-04-19'),
(8, '1967-08-03', 56, 'Rue de la paix', '93340', 'Paris', '2023-04-21');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `idEnseignant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`idEnseignant`) VALUES
(1);

-- --------------------------------------------------------

--
-- Structure de la table `enseigne`
--

CREATE TABLE `enseigne` (
  `idEnseignant` int(11) NOT NULL,
  `idSection` int(11) NOT NULL,
  `isRsEnseignant` tinyint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `idEntreprise` int(11) NOT NULL,
  `nomEntreprise` varchar(50) NOT NULL,
  `missionEntreprise` varchar(100) NOT NULL,
  `numAdrEntreprise` int(11) NOT NULL,
  `libAdrEntreprise` varchar(50) NOT NULL,
  `codePostalAdrEntreprise` varchar(5) NOT NULL,
  `villeAdrEntreprise` varchar(50) NOT NULL,
  `telephoneEntreprise` varchar(15) NOT NULL,
  `mailEntreprise` varchar(50) NOT NULL,
  `siretEntreprise` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`idEntreprise`, `nomEntreprise`, `missionEntreprise`, `numAdrEntreprise`, `libAdrEntreprise`, `codePostalAdrEntreprise`, `villeAdrEntreprise`, `telephoneEntreprise`, `mailEntreprise`, `siretEntreprise`) VALUES
(6, 'nomentre', 'Mission', 69, 'voie', '31500', 'villeEntre', 'TELEntre', 'MailEntre@prise.fr', 'SIRETEnt'),
(7, 'entreprise fictive', 'je sais pas trop mais cette entreprise s\'occupe de développer ce truc :)', 7, 'rue pons capdenier', '31500', 'Toulouse', '07894158903', '6874@564.aze', '12345'),
(8, 'Test inc', 'faire des tests', 1, 'rue de limayrac', '31500', 'Toulouse', '8745213690', 'entreprise@mail.fr', '1234'),
(9, 'Mcdo', 'Vendre des frites au max et faire grossir les enfants d\'occident.', 78, 'Paix', '37235', 'Nice', '0000000000', 'Mcdo@Mcdo.fr', '3453257');

-- --------------------------------------------------------

--
-- Structure de la table `fonction`
--

CREATE TABLE `fonction` (
  `idFonction` int(11) NOT NULL,
  `libFonction` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fonction`
--

INSERT INTO `fonction` (`idFonction`, `libFonction`) VALUES
(4, 'Administrateur de base de données'),
(5, 'Administrateur système'),
(6, 'Agent de service client'),
(7, 'Analyste d\'affaires'),
(8, 'Analyste de données'),
(9, 'Analyste financier'),
(10, 'Analyste marketing'),
(11, 'Assistant administratif'),
(12, 'Assistant de direction'),
(13, 'Assistant de gestion'),
(14, 'Avocat'),
(15, 'Chargé de communication'),
(16, 'Chef comptable'),
(17, 'Chef de projet'),
(18, 'Commercial'),
(19, 'Community manager'),
(20, 'Consultant en management'),
(21, 'Contrôleur de gestion'),
(22, 'Coordonnateur de projet'),
(23, 'Développeur informatique'),
(24, 'Directeur artistique'),
(25, 'Directeur commercial'),
(26, 'Directeur de la communication'),
(27, 'Directeur de la production'),
(28, 'Directeur des ressources humaines'),
(29, 'Directeur des ventes'),
(30, 'Graphiste'),
(31, 'Ingénieur civil'),
(32, 'Ingénieur en mécanique'),
(33, 'Ingénieur en électricité'),
(34, 'Ingénieur informatique'),
(35, 'Juriste'),
(36, 'Maquettiste'),
(37, 'Médiateur'),
(38, 'Médecin du travail'),
(39, 'Négociateur'),
(40, 'Notaire'),
(41, 'Opérateur de production'),
(42, 'Pharmacien'),
(43, 'Pilote de ligne'),
(44, 'Planificateur'),
(45, 'Product owner'),
(46, 'Programmeur'),
(47, 'Responsable achats'),
(48, 'Responsable de la formation'),
(49, 'Responsable de la logistique'),
(50, 'Responsable de la qualité'),
(51, 'Responsable des opérations'),
(52, 'Responsable des ressources humaines'),
(53, 'Responsable du service après-vente'),
(54, 'Responsable informatique'),
(55, 'Secrétaire'),
(56, 'Stratège marketing'),
(57, 'Technicien de maintenance'),
(58, 'Traducteur'),
(59, 'Vendeur'),
(60, 'Webmaster'),
(61, 'Agent immobilier'),
(62, 'Architecte'),
(63, 'Auditeur interne'),
(64, 'Chef de produit'),
(65, 'Chef de rayon'),
(66, 'Chef de service'),
(67, 'Chef de secteur'),
(68, 'Chef de service après-vente'),
(69, 'Chef de travaux'),
(70, 'Chef d\'atelier'),
(71, 'Coach'),
(72, 'Comptable'),
(73, 'Concepteur-rédacteur'),
(74, 'Conseiller clientèle'),
(75, 'Conseiller en investissements financiers'),
(76, 'Conseiller en recrutement'),
(77, 'Consultant en finance'),
(78, 'Contrôleur de gestion sociale'),
(79, 'Coordinateur marketing'),
(80, 'Designer'),
(81, 'Directeur artistique'),
(82, 'Directeur de la comptabilité'),
(83, 'Directeur de l\'informatique'),
(84, 'Directeur de la recherche et du développement'),
(85, 'Directeur de production'),
(86, 'Directeur de projet'),
(87, 'Directeur des achats'),
(88, 'Directeur des opérations'),
(89, 'Directeur des ressources humaines'),
(90, 'Directeur financier'),
(91, 'Électricien'),
(92, 'Expert-comptable'),
(93, 'Formateur'),
(94, 'Gestionnaire de paie'),
(95, 'Graphiste web'),
(96, 'Ingénieur chimiste'),
(97, 'Ingénieur de recherche'),
(98, 'Ingénieur en génie civil'),
(99, 'Ingénieur en génie des procédés'),
(100, 'Ingénieur en génie électrique'),
(101, 'Ingénieur en génie industriel'),
(102, 'Ingénieur en génie mécanique'),
(103, 'Ingénieur environnemental'),
(104, 'Juriste d\'entreprise'),
(105, 'Logisticien'),
(106, 'Maître d\'hôtel'),
(107, 'Manager de transition'),
(108, 'Manager de transition RH'),
(109, 'Manager de transition financier'),
(110, 'Manager de transition marketing'),
(111, 'Médiateur culturel'),
(112, 'Médecin conseil'),
(113, 'Médecin coordinateur'),
(114, 'Médecin généraliste'),
(115, 'Médecin spécialiste'),
(116, 'Métallurgiste'),
(117, 'Notaire assistant'),
(118, 'Opérateur de saisie'),
(119, 'Orthophoniste'),
(120, 'Ouvrier'),
(121, 'Payeur'),
(122, 'Photographe'),
(123, 'Pilote de ligne commerciale'),
(124, 'Planificateur de production'),
(125, 'Planificateur financier'),
(126, 'Psychologue du travail'),
(127, 'Responsable communication digitale'),
(128, 'Responsable de clientèle'),
(129, 'Responsable de l\'audit'),
(130, 'Responsable de la communication interne'),
(131, 'Responsable de la sécurité'),
(132, 'Responsable de la stratégie'),
(133, 'Responsable des achats informatiques'),
(134, 'Responsable des achats marketing'),
(135, 'Responsable des approvisionnements'),
(136, 'Responsable des études marketing'),
(137, 'Responsable des ressources humaines et des relatio'),
(138, 'Responsable du marketing digital'),
(139, 'Responsable du développement commercial'),
(140, 'Responsable qualité sécurité environnement'),
(141, 'Responsable technique'),
(142, 'Secrétaire comptable'),
(143, 'Spécialiste des relations publiques'),
(144, 'Technicien de laboratoire'),
(145, 'Technicien de maintenance informatique'),
(146, 'Technicien de maintenance industrielle'),
(147, 'Technicien de maintenance mécanique'),
(148, 'Technicien en environnement'),
(149, 'Technicien en informatique'),
(150, 'Technicien en sécurité informatique'),
(151, 'Traducteur-interprète'),
(152, 'Vérificateur'),
(153, 'Agent de sécurité'),
(154, 'Analyste crédit'),
(155, 'Analyste d\'affaires'),
(156, 'Analyste financier'),
(157, 'Analyste marketing'),
(158, 'Analyste programmeur'),
(159, 'Animateur de communauté'),
(160, 'Animateur de vente'),
(161, 'Animateur de soirée'),
(162, 'Assistant administratif'),
(163, 'Assistant commercial'),
(164, 'Assistant comptable'),
(165, 'Assistant de direction'),
(166, 'Assistant marketing'),
(167, 'Assistant ressources humaines'),
(168, 'Attaché commercial'),
(169, 'Auditeur externe'),
(170, 'Avocat d\'affaires'),
(171, 'Chargé d\'affaires'),
(172, 'Chargé d\'études'),
(173, 'Chargé de clientèle'),
(174, 'Chargé de communication'),
(175, 'Chargé de développement'),
(176, 'Chargé de formation'),
(177, 'Chargé de mission'),
(178, 'Chargé de projet'),
(179, 'Chargé de recrutement'),
(180, 'Chargé de relations publiques'),
(181, 'Chef de chantier'),
(182, 'Chef de groupe'),
(183, 'Chef de projet web'),
(184, 'Chef de publicité'),
(185, 'Chef de vente'),
(186, 'Chef de zone'),
(187, 'Coach sportif'),
(188, 'Comédien'),
(189, 'Commercial'),
(190, 'Concepteur de jeux vidéo'),
(191, 'Consultant en management'),
(192, 'Contrôleur de gestion'),
(193, 'Coordinateur de projet'),
(194, 'Décorateur'),
(195, 'Designer d\'intérieur'),
(196, 'Directeur commercial'),
(197, 'Directeur de l\'audit interne'),
(198, 'Directeur de la communication'),
(199, 'Directeur de la logistique'),
(200, 'Directeur de la production informatique'),
(201, 'Directeur de la qualité'),
(202, 'Directeur de magasin'),
(203, 'Directeur de site'),
(204, 'Directeur des achats informatiques et télécoms'),
(205, 'Directeur des affaires réglementaires'),
(206, 'Directeur des opérations informatiques'),
(207, 'Directeur des systèmes d\'information'),
(208, 'Directeur du développement informatique'),
(209, 'Directeur juridique'),
(210, 'Économiste'),
(211, 'Électricien de maintenance'),
(212, 'Enseignant-chercheur'),
(213, 'Esthéticienne'),
(214, 'Expert en communication'),
(215, 'Formateur en informatique'),
(216, 'Gestionnaire administratif'),
(217, 'Graphiste'),
(218, 'Ingénieur aéronautique'),
(219, 'Ingénieur biomédical'),
(220, 'Ingénieur d\'études'),
(221, 'Ingénieur en informatique'),
(222, 'Ingénieur en télécommunications'),
(223, 'Ingénieur géologue'),
(224, 'Ingénieur hydraulique'),
(225, 'Ingénieur nucléaire'),
(226, 'Ingénieur sécurité'),
(227, 'Inspecteur de l\'enseignement'),
(228, 'Juriste en droit social'),
(229, 'Logisticien de production'),
(230, 'Maquilleur'),
(231, 'Manager de projets'),
(232, 'Manager de rayon'),
(233, 'Manager de transition logistique'),
(234, 'Manager de transition opérations'),
(235, 'Manager de transition supply chain'),
(236, 'Manager en assurance qualité'),
(237, 'Médiateur familial'),
(238, 'Médecin du travail'),
(239, 'Métrologue'),
(240, 'Notaire'),
(241, 'Opticien'),
(242, 'Ouvrier agricole'),
(243, 'Peintre en bâtiment'),
(244, 'Pilote d\'essai'),
(245, 'Planificateur de maintenance'),
(246, 'Préparateur de commande'),
(247, 'Psychologue clinicien'),
(248, 'Rédacteur web'),
(249, 'Président directeur général'),
(250, 'Directeur général'),
(251, 'Responsable de section');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `idEleve` int(11) NOT NULL,
  `idAnneeScolaire` int(11) NOT NULL,
  `idSection` int(11) NOT NULL,
  `annee` varchar(2) NOT NULL COMMENT 'Première année : 1A Seconde année : 2A',
  `isRedoublement` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`idEleve`, `idAnneeScolaire`, `idSection`, `annee`, `isRedoublement`) VALUES
(2, 3, 1, '1', 0),
(3, 3, 1, '1', 0),
(4, 3, 1, '1', 0),
(5, 2, 2, '1', 0),
(6, 3, 1, '1', 1),
(7, 1, 1, '1', 0),
(8, 3, 2, '1', 1);

-- --------------------------------------------------------

--
-- Structure de la table `section`
--

CREATE TABLE `section` (
  `idSection` int(11) NOT NULL,
  `nomCourtSection` varchar(50) NOT NULL,
  `nomLongSection` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `section`
--

INSERT INTO `section` (`idSection`, `nomCourtSection`, `nomLongSection`) VALUES
(1, '1SIO', '1SIO'),
(2, '2SIO', '2SIO');

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

CREATE TABLE `stage` (
  `idStage` int(11) NOT NULL,
  `titreStage` varchar(50) DEFAULT NULL,
  `descriptifStage` varchar(50) DEFAULT NULL,
  `anneeStage` int(11) DEFAULT 1 COMMENT '1A : Première année 2A : Seconde année',
  `dateDebutStage` date DEFAULT NULL,
  `dateFinStage` date DEFAULT NULL,
  `dureeHebdoStage` int(11) DEFAULT NULL,
  `activiteslStage` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `lieuStage` varchar(200) DEFAULT NULL,
  `isValideStage` tinyint(1) NOT NULL DEFAULT 0,
  `idEleve` int(11) NOT NULL,
  `idEntreprise` int(11) DEFAULT NULL,
  `idAnneeScolaire` int(11) DEFAULT NULL,
  `idEnseignant` int(11) DEFAULT NULL,
  `idTuteur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `stage`
--

INSERT INTO `stage` (`idStage`, `titreStage`, `descriptifStage`, `anneeStage`, `dateDebutStage`, `dateFinStage`, `dureeHebdoStage`, `activiteslStage`, `lieuStage`, `isValideStage`, `idEleve`, `idEntreprise`, `idAnneeScolaire`, `idEnseignant`, `idTuteur`) VALUES
(3, 'titre stage original', 'salut :)', 1, '0000-00-00', '0000-00-00', 0, 'salut :)', NULL, 0, 4, 8, 2, 1, 9),
(4, 'titre stage original', 'salut :)', 1, '0000-00-00', '0000-00-00', 0, 'salut :)', NULL, 0, 4, 8, 2, 1, 9),
(5, 'Chomage', 'Je vais travailler chez mcdo', 1, '0000-00-00', '0000-00-00', 0, 'Je vais travailler chez mcdo', NULL, 0, 8, 9, 2, 1, 10);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtil` int(11) NOT NULL,
  `titreUtil` varchar(4) NOT NULL COMMENT 'Monsieur : Mr Madame : Mme Mademoiselle : Mlle',
  `nomUtil` varchar(60) NOT NULL,
  `prenomUtil` varchar(60) NOT NULL,
  `mobileUtil` varchar(15) DEFAULT NULL,
  `mailPersoUtil` varchar(50) DEFAULT NULL,
  `mailUtil` varchar(50) NOT NULL,
  `mdpUtil` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtil`, `titreUtil`, `nomUtil`, `prenomUtil`, `mobileUtil`, `mailPersoUtil`, `mailUtil`, `mdpUtil`) VALUES
(1, 'Mr', 'Admin', 'Prof', NULL, NULL, 'dev@marc-magueur.dev', 'NULL'),
(2, 'Mr', 'M', 'Marc', '', '', 'test@test.fr', '$2y$10$Um1ZN8dR1muC1PpfDiUoi.3FVRLUSebvnTO1a/T4gkszRDMlj2VYC'),
(3, 'Mlle', 'M', 'Marc', '', '', 'test@test.com', '$2y$10$.A/dItRHNgyVsPcjTHNB2uzT1QrALtUIYdcGSkm/zDLUsLoP6DfHu'),
(4, 'Mr', 'test', 'test', '', '', 'test@test.test', '$2y$10$2iYzwV9B86Y1DeIv7w.97ufefHAB7quWnYDvTvPSSF1IJjoxeV.WC'),
(6, 'Mr', 'caca', 'pipi', '', '', 'test@test.caca', '$2y$10$mf/9s6YHgEHo3DkIQksYMunlAUHhpL0vurT/v7eiY4lG9P9MCkriO'),
(7, 'Mlle', 'a', 'bcccc', '', '', 'a@a.a', '$2y$10$ktXwxPSaMVqTXVoH8C9GPeQW9oF4LKe7F7B7.9pQb16OLzB2di2aG'),
(8, 'Mr', 'Roger', 'Roger', '0000000000', 'Roger@roger.fr', 'Roger@roger.fr', '$2y$10$IfUI6izt3MtC3nPHOjYgVu2zlIDbTFC392k0XGOAwFZit69XBohg2');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `anneescolaire`
--
ALTER TABLE `anneescolaire`
  ADD PRIMARY KEY (`idAnneeScolaire`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`idContact`),
  ADD KEY `contact_fonction_FK` (`idFonction`),
  ADD KEY `contact_entreprise_FK` (`idEntreprise`);

--
-- Index pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD PRIMARY KEY (`idEleve`);

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`idEnseignant`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`idEntreprise`);

--
-- Index pour la table `fonction`
--
ALTER TABLE `fonction`
  ADD PRIMARY KEY (`idFonction`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`idEleve`,`idAnneeScolaire`,`idSection`),
  ADD KEY `inscription_annee_scolaire_FK` (`idAnneeScolaire`),
  ADD KEY `inscription_section_FK` (`idSection`);

--
-- Index pour la table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`idSection`);

--
-- Index pour la table `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`idStage`),
  ADD KEY `stage_eleve_FK` (`idEleve`),
  ADD KEY `stage_entreprise_FK` (`idEntreprise`),
  ADD KEY `stage_annee_scolaire_FK` (`idAnneeScolaire`),
  ADD KEY `stage_enseignant_FK` (`idEnseignant`),
  ADD KEY `stage_contact_FK` (`idTuteur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtil`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `anneescolaire`
--
ALTER TABLE `anneescolaire`
  MODIFY `idAnneeScolaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `idContact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `idEntreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `fonction`
--
ALTER TABLE `fonction`
  MODIFY `idFonction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT pour la table `section`
--
ALTER TABLE `section`
  MODIFY `idSection` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `stage`
--
ALTER TABLE `stage`
  MODIFY `idStage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUtil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_entreprise_FK` FOREIGN KEY (`idEntreprise`) REFERENCES `entreprise` (`idEntreprise`),
  ADD CONSTRAINT `contact_fonction_FK` FOREIGN KEY (`idFonction`) REFERENCES `fonction` (`idFonction`);

--
-- Contraintes pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD CONSTRAINT `eleve_utilisateur_FK` FOREIGN KEY (`idEleve`) REFERENCES `utilisateur` (`idUtil`);

--
-- Contraintes pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD CONSTRAINT `enseignant_utilisateur_FK` FOREIGN KEY (`idEnseignant`) REFERENCES `utilisateur` (`idUtil`);

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_annee_scolaire_FK` FOREIGN KEY (`idAnneeScolaire`) REFERENCES `anneescolaire` (`idAnneeScolaire`),
  ADD CONSTRAINT `inscription_eleve_FK` FOREIGN KEY (`idEleve`) REFERENCES `eleve` (`idEleve`),
  ADD CONSTRAINT `inscription_section_FK` FOREIGN KEY (`idSection`) REFERENCES `section` (`idSection`);

--
-- Contraintes pour la table `stage`
--
ALTER TABLE `stage`
  ADD CONSTRAINT `stage_annee_scolaire_FK` FOREIGN KEY (`idAnneeScolaire`) REFERENCES `anneescolaire` (`idAnneeScolaire`),
  ADD CONSTRAINT `stage_contact_FK` FOREIGN KEY (`idTuteur`) REFERENCES `contact` (`idContact`),
  ADD CONSTRAINT `stage_eleve_FK` FOREIGN KEY (`idEleve`) REFERENCES `eleve` (`idEleve`),
  ADD CONSTRAINT `stage_enseignant_FK` FOREIGN KEY (`idEnseignant`) REFERENCES `enseignant` (`idEnseignant`),
  ADD CONSTRAINT `stage_entreprise_FK` FOREIGN KEY (`idEntreprise`) REFERENCES `entreprise` (`idEntreprise`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
