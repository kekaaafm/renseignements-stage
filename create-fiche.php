<?php
require 'assets/php/header.php';

loggedVerif();
if (!isEleve()) {
    header("Location: dashboard.php");
}

$student = fetchEleveData();

$section = fetchclasse($student["idEleve"]);

if (empty($_SESSION["createfiche"])) {
    $_SESSION["createfiche"] = [];
}

$step = $_GET["step"] ?? (count($_SESSION["createfiche"]) + 1);

if ($step > count($_SESSION["createfiche"]) + 1) {
    header("Location: create-fiche.php");
}

switch ($step) {
    case 2:
        $id_entreprise = $_GET["id_entreprise"] ?? 0;
        if ($id_entreprise > 0) {
            $req = $db->prepare("SELECT * FROM entreprise WHERE idEntreprise = :id");
            $req->execute([
                "id" => $id_entreprise
            ]);
            $rep = $req->fetch();
            if (!$rep) {
                header("Location: create-fiche.php?step=2");
                die;
            }
            $_SESSION["createfiche"][1] = $id_entreprise;
            header("Location: create-fiche.php?step=3");
        }

        $req = $db->prepare("SELECT * FROM fonction ORDER BY libFonction ASC");
        $req->execute([]);
        $fonctions = $req->fetchAll();

        if ($id_entreprise == 0) {
            if (empty($_SESSION["createfiche"][1])) {
                $req = $db->prepare("SELECT * FROM entreprise");
                $req->execute([]);
                $entreprises = $req->fetchAll();
            } else {
                $req = $db->prepare("SELECT * FROM entreprise WHERE idEntreprise = :idE");
                $req->execute([
                    "idE" => $_SESSION["createfiche"][1]
                ]);
                $entreprises = $req->fetchAll();
            }
        }
        break;
    case 3:
        $id_entreprise = $_SESSION["createfiche"][1];
        if (!empty($_GET["contact"])) {
            $req = $db->prepare("SELECT * FROM contact WHERE idContact = :idC AND idEntreprise = :idE");
            $req->execute([
                "idC" => $_GET["contact"],
                "idE" => $id_entreprise
            ]);
            $rep = $req->fetch();

            if ($rep) {
                $_SESSION["createfiche"][2] = $rep["idContact"];
                header("Location: create-fiche.php?step=04");
            }
        }

        if (!empty($_GET["id_contact"]) && $_GET["id_contact"] == -1) {

            $req = $db->prepare("SELECT * FROM fonction ORDER BY libFonction ASC");
            $req->execute([]);
            $fonctions = $req->fetchAll();

        }

        $req = $db->prepare("SELECT * FROM contact, fonction WHERE idEntreprise = :id and contact.idFonction = fonction.idFonction");
        $req->execute([
            "id" => $id_entreprise
        ]);
        $contacts = $req->fetchAll();
        break;
    case 4:
        $entreprise = fetchEntrepriseData($_SESSION["createfiche"][1]);

        if (!$entreprise) { //Ca ne devrait jamais arriver mais dans le cas ou l'entreprise n'est pas valide, on invalide tout.
            unset($_SESSION["createfiche"][2]);
            unset($_SESSION["createfiche"][1]);
            header("Location: create-fiche.php?step=2");
            die;
        }

//    var_dump("Check Entreprise ");
        break;
    case 5:

        $student = fetchEleveData();
        $entreprise = fetchEntrepriseData($_SESSION["createfiche"][1]);
        $contact = fetchContactData($_SESSION["createfiche"][2]);
        $responsable = fetchEntrepriseResponsable($_SESSION["createfiche"][1]);

        $adrStage = 'ok';
        if ($_SESSION["createfiche"][3] !== ($entreprise["numAdrEntreprise"] . " " . $entreprise["libAdrEntreprise"] . " " . $entreprise["codePostalAdrEntreprise"] . " " . $entreprise["villeAdrEntreprise"])) {
            $adrStage = $_SESSION["createfiche"][3];
        }

        if (!empty($_GET["confirm"]) && $_GET["confirm"] == 1) {
            $req = $db->prepare("INSERT INTO `stage`(`titreStage`, `descriptifStage`, `dateDebutStage`, `dateFinStage`, `dureeHebdoStage`, `activiteslStage`, `lieuStage`, `idEleve`,`idStatutStage`, `idEntreprise`, `idAnneeScolaire`, `idEnseignant`, `idContact`) 
                                        VALUES (:titreStage,:descriptifStage,:dateDebutStage,:dateFinStage,:dureeHebdoStage,:activiteslStage,:lieuStage,:idEleve,:idStatutStage,:idEntreprise,:idAnneeScolaire,:idEnseignant,:idTuteur)");
            $req->execute([
                "titreStage" => $_SESSION["createfiche"][0]["titre"],
                "descriptifStage" => $_SESSION["createfiche"][0]["description"],
                "dateDebutStage" => $_SESSION["createfiche"][0]["datedebut"],
                "dateFinStage" => $_SESSION["createfiche"][0]["datefin"],
                "dureeHebdoStage" => $_SESSION["createfiche"][0]["heures"],
                "activiteslStage" => $_SESSION["createfiche"][0]["description"],
                "lieuStage" => $adrStage,
                "idEleve" => $student["idEleve"],
                "idEntreprise" => $entreprise["idEntreprise"],
                "idStatutStage" => 2,
                "idAnneeScolaire" => 2,
                "idEnseignant" => 1,
                "idTuteur" => $_SESSION["createfiche"][2]
            ]);
            header("Location: dashboard.php");
        }

//        var_dump($entreprise,$contact);
        break;
}
//var_dump($_SESSION["createfiche"]);

if ($_POST) {

//    var_dump($_POST);
    foreach ($_POST as $key => $value) {
        echo "<br> $key";
    }

    switch ($step) {
        case 1:
            if (empty($_POST["datedebut"]) or
                empty($_POST["datefin"]) or
                empty($_POST["heures"]) or
                empty($_POST["description"]) or
                empty($_POST["titre"])) {
                createSessionError("notAllFieldsFilled");
            } else {
                $_SESSION["createfiche"][0] = $_POST;
                header("Location: create-fiche.php?step=2");
            }
            break;
        case 2:
            if ($_GET["id_entreprise"] == -1) {
                if (empty($_POST["nomEntreprise"]) or
                    empty($_POST["siretEntreprise"]) or
                    empty($_POST["telEntreprise"]) or
                    empty($_POST["mailEntreprise"]) or
                    empty($_POST["descriptionEntreprise"]) or
                    empty($_POST["numeroEntreprise"]) or
                    empty($_POST["voieEntreprise"]) or
                    empty($_POST["codePostalEntreprise"]) or
                    empty($_POST["villeEntreprise"]) or
                    empty($_POST["titreContact"]) or
                    empty($_POST["nomContact"]) or
                    empty($_POST["prenomContact"]) or
                    empty($_POST["mobileContact"]) or
                    empty($_POST["mailContact"]) or
                    empty($_POST["fonctionContact"])) {
                    createSessionError("notAllFieldsFilled");
                } else {
                    $req = $db->prepare("
                        INSERT INTO `entreprise`(`nomEntreprise`, `missionEntreprise`, `numAdrEntreprise`, `libAdrEntreprise`, `codePostalAdrEntreprise`, `villeAdrEntreprise`, `telEntreprise`, `mailEntreprise`, `siretEntreprise`) 
                                          VALUES (:nomEntreprise, :missionEntreprise, :numAdrEntreprise, :libAdrEntreprise, :codePostalAdrEntreprise, :villeAdrEntreprise, :telEntreprise, :mailEntreprise, :siretEntreprise)");
                    $req->execute([
                        "nomEntreprise" => $_POST["nomEntreprise"],
                        "missionEntreprise" => $_POST["descriptionEntreprise"],
                        "numAdrEntreprise" => $_POST["numeroEntreprise"],
                        "libAdrEntreprise" => $_POST["voieEntreprise"],
                        "codePostalAdrEntreprise" => $_POST["codePostalEntreprise"],
                        "villeAdrEntreprise" => $_POST["villeEntreprise"],
                        "telEntreprise" => $_POST["telEntreprise"],
                        "mailEntreprise" => $_POST["mailEntreprise"],
                        "siretEntreprise" => $_POST["siretEntreprise"]
                    ]);
                    $req = $db->prepare("SELECT * FROM entreprise WHERE nomEntreprise = :nomEntreprise ORDER BY idEntreprise DESC");
                    $req->execute([
                        "nomEntreprise" => $_POST["nomEntreprise"]
                    ]);
                    $rep = $req->fetch();
//                    var_dump($rep);

                    $req = $db->prepare("
                        INSERT INTO `contact`(`titreContact`, `nomContact`, `prenomContact`, `telMobileContact`, `telFixeContact`, `mailContact`, `isRespContact`, `isActifContact`, `idFonction`, `idEntreprise`) 
                            VALUES (:titreContact,:nomContact,:prenomContact,:mobileContact,:fixeContact,:mailContact,:isRespContact,:isActifContact,:idFonction,:idEntreprise)");
                    $req->execute([
                        "titreContact" => $_POST["titreContact"],
                        "nomContact" => $_POST["nomContact"],
                        "prenomContact" => $_POST["prenomContact"],
                        "mobileContact" => $_POST["mobileContact"],
                        "fixeContact" => $_POST["fixeContact"],
                        "mailContact" => $_POST["mailContact"],
                        "isRespContact" => 1,
                        "isActifContact" => 1,
                        "idFonction" => $_POST["fonctionContact"],
                        "idEntreprise" => $rep["idEntreprise"]
                    ]);
                    $_SESSION["createfiche"][1] = $rep["idEntreprise"];
                    header("Location: create-fiche.php?step=3");
                }
            }
            break;
        case 3:
            if (!empty($_GET["titreContact"]) or
                !empty($_GET["nomContact"]) or
                !empty($_GET["prenomContact"]) or
                !empty($_GET["mobileContact"]) or
                !empty($_GET["mailContact"]) or
                !empty($_GET["fonctionContact"])
            ) {
                createSessionError("notAllFieldsFilled");
            } else {
                var_dump($_SESSION["createfiche"][2]);
                $req = $db->prepare("
                            INSERT INTO `contact`(`titreContact`, `nomContact`, `prenomContact`, `mobileContact`, `telFixeContact`, `mailContact`, `isRespContact`, `isActifContact`, `idFonction`, `idEntreprise`) 
                                VALUES (:titreContact,:nomContact,:prenomContact,:mobileContact,:fixeContact,:mailContact,:isRespContact,:isActifContact,:idFonction,:idEntreprise)");
                $req->execute([
                    "titreContact" => $_POST["titreContact"], //
                    "nomContact" => $_POST["nomContact"], //
                    "prenomContact" => $_POST["prenomContact"], //
                    "mobileContact" => $_POST["mobileContact"], //
                    "fixeContact" => $_POST["fixeContact"], //
                    "mailContact" => $_POST["mailContact"], //
                    "isRespContact" => 0,
                    "isActifContact" => 1,
                    "idFonction" => $_POST["fonctionContact"], //
                    "idEntreprise" => $_SESSION["createfiche"][1]
                ]);

                $req = $db->prepare("SELECT * FROM contact WHERE nomContact = :nomContact AND prenomContact = :prenomContact ORDER BY idContact DESC");
                $req->execute([
                    "nomContact" => $_POST["nomContact"], //
                    "prenomContact" => $_POST["prenomContact"], //
                ]);
                $rep = $req->fetch();
                $_SESSION["createfiche"][2] = $rep["idContact"];
            }
        case 4:
            $_SESSION["createfiche"][3] = $_POST["adresseStage"];
            header("Location: create-fiche.php?step=05");
            die;
    }

}

//var_dump($id_entreprise);

?>


<body class="h-full bg-gray-200">


<div class="min-h-full">
    <div class="bg-gray-800 pb-32">
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="border-b border-gray-700">
                    <div class="flex h-16 items-center justify-between px-4 sm:px-0">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8"
                                     src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                                     alt="Your Company">
                            </div>
                            <div class="hidden md:block">
                                <div class="ml-10 flex items-baseline space-x-4">
                                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->

                                    <a href="dashboard.php"
                                       class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium"
                                       aria-current="page">Dashboard</a>

                                    <a href="profil.php"
                                       class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Mon
                                        profil
                                    </a>

                                    <a href="logout.php"
                                       class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Déconnexion</a>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-4 flex items-center md:ml-6">
                                <button type="button"
                                        class="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                    <span class="sr-only">View notifications</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                    </svg>
                                </button>

                                <!-- Profile dropdown -->
                                <div class="relative ml-3">
                                    <div>
                                        <button type="button"
                                                class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                            <span class="sr-only">Open user menu</span>
                                            <img class="h-8 w-8 rounded-full"
                                                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                                 alt="">
                                        </button>
                                    </div>

                                    <!--
                                      Dropdown menu, show/hide based on menu state.

                                      Entering: "transition ease-out duration-100"
                                        From: "transform opacity-0 scale-95"
                                        To: "transform opacity-100 scale-100"
                                      Leaving: "transition ease-in duration-75"
                                        From: "transform opacity-100 scale-100"
                                        To: "transform opacity-0 scale-95"
                                    -->
                                    <!--                                    <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">-->
                                    <!-- Active: "bg-gray-100", Not Active: "" -->
                                    <!--                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>-->
                                    <!---->
                                    <!--                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>-->
                                    <!---->
                                    <!--                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>-->
                                    <!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="-mr-2 flex md:hidden">
                            <!-- Mobile menu button -->
                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                    aria-controls="mobile-menu" aria-expanded="false">
                                <span class="sr-only">Open main menu</span>
                                <!-- Menu open: "hidden", Menu closed: "block" -->
                                <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                </svg>
                                <!-- Menu open: "block", Menu closed: "hidden" -->
                                <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="border-b border-gray-700 md:hidden" id="mobile-menu">
                <div class="space-y-1 px-2 py-3 sm:px-3">
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <a href="#" class="bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium"
                       aria-current="page">Dashboard</a>

                    <a href="#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Team</a>

                    <a href="#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Projects</a>

                    <a href="#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Calendar</a>

                    <a href="#"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Reports</a>
                </div>
                <div class="border-t border-gray-700 pb-3 pt-4">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full"
                                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                 alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white">Tom Cook</div>
                            <div class="text-sm font-medium leading-none text-gray-400">tom@example.com</div>
                        </div>
                        <button type="button"
                                class="ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <a href="#"
                           class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Your
                            Profile</a>

                        <a href="#"
                           class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Settings</a>

                        <a href="#"
                           class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Sign
                            out</a>
                    </div>
                </div>
            </div>
        </nav>
        <header class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Éditer une fiche de renseignements</h1>
            </div>
        </header>
    </div>

    <main class="-mt-32">
        <div class="mx-auto max-w-7xl px-4 pb-12 pt-12 sm:px-6 lg:px-8 bg-white rounded-lg border-black bg-white">

            <!-- DEBUT NAV -->

            <nav class="mb-8" aria-label="Progress">
                <ol role="list"
                    class="divide-y divide-gray-300 rounded-md border border-gray-300 md:flex md:divide-y-0">

                    <?= showStep((($step == 01) ? "current" : (($step < 01) ? "next" : "past")), "01", "L'étudiant", false) ?>
                    <?= showStep((($step == 02) ? "current" : (($step < 02) ? "next" : "past")), "02", "L'entreprise", false) ?>
                    <?= showStep((($step == 03) ? "current" : (($step < 03) ? "next" : "past")), "03", "Le tuteur", false) ?>
                    <?= showStep((($step == 04) ? "current" : (($step < 04) ? "next" : "past")), "04", "Lieu de stage", false) ?>
                    <?= showStep((($step == 05) ? "current" : (($step < 05) ? "next" : "past")), "05", "Validation", true) ?>
                </ol>
            </nav>


            <!-- FIN NAV -->


            <?php if (countSessionErrors() > 0): ?>
                <div class="rounded-md bg-red-50 p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Oups, je pense qu'il y a une coquille
                                quelque part :(</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    <?php foreach (arrayOfErrorsMessages() as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php switch ($step):
            case 1:?>
                <form method="post" class="mx-auto max-w-2xl" id="profileStudent">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">L'étudiant</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">La plupart de ces champs sont
                                préremplis
                                avec les informations fournies dans ton profil. Si elles sont incorrectes,
                                dirige-toi
                                directement vers ton profil pour les modifier</p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <label for="prenom"
                                           class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                                    <div class="mt-2">
                                        <input type="text" id="prenom" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= $_SESSION["user"]["prenomUtil"] ?>">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="nom"
                                           class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                                    <div class="mt-2">
                                        <input type="text" id="nom" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= $_SESSION["user"]["nomUtil"] ?>">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="nom"
                                           class="block text-sm font-medium leading-6 text-gray-900">Classe</label>
                                    <div class="mt-2">
                                        <input type="text" id="nom" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value=<?= $section["nomCourtSection"] ?>>
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="email"
                                           class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                        de résidence pendant le stage</label>
                                    <div class="mt-2">
                                        <input id="email" type="text" autocomplete="email" disabled
                                               value="<?= $student["numAdrEleve"] . " " . $student["libAdrEleve"] . " " . $student["codePostalAdrEleve"] . " " . $student["villeAdrEleve"] ?>"
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="datedebut"
                                           class="block text-sm font-medium leading-6 text-gray-900">Date de
                                        début de
                                        stage</label>
                                    <div class="mt-2">
                                        <input type="date" id="datedebut" name="datedebut"
                                               value="<?= $_SESSION["createfiche"][0]["datedebut"] ?? "" ?>"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        >
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="datefin"
                                           class="block text-sm font-medium leading-6 text-gray-900">Date de fin
                                        de
                                        stage</label>
                                    <div class="mt-2">
                                        <input type="date" id="datefin" name="datefin"
                                               value="<?= $_SESSION["createfiche"][0]["datefin"] ?? "" ?>"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        >
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="heures"
                                           class="block text-sm font-medium leading-6 text-gray-900">Heures
                                        de travail hebdomadaires</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <input type="number" name="heures" id="heures"
                                               value="<?= $_SESSION["createfiche"][0]["heures"] ?? "" ?>"
                                               class="block w-full rounded-md border-0 py-1.5 pl-3 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm mr-3"
                                                  id="price-currency">heures</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="titre"
                                           class="block text-sm font-medium leading-6 text-gray-900">Titre du
                                        stage</label>
                                    <div class="mt-2">
                                        <input type="text" name="titre" id="titre"
                                               value="<?= $_SESSION["createfiche"][0]["titre"] ?? "" ?>"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="description"
                                           class="block text-sm font-medium leading-6 text-gray-900">Description du
                                        stage</label>
                                    <div class="mt-2">
                                        <textarea type="text" name="description" id="description"
                                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?= $_SESSION["createfiche"][0]["description"] ?? "" ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Sauvegarder
                        </button>
                    </div>
                </form>

            <?php break; ?>
            <?php case 2: ?>
            <?php if ($id_entreprise == 0): ?>
                <div class="mx-auto max-w-2xl">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">L'entreprise</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Si jamais l'entreprise est déjà dans la
                                base de donnée, ses informations se rempliront toutes seules</p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                <div class="sm:col-span-6">
                                    <label for="nomEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Nom
                                        entreprise (tapez au minimum les 3 premières lettres)</label>
                                    <div class="mt-2">
                                        <input type="text" id="nomEntreprise" name="nomEntreprise"
                                               value="<?= !empty($_SESSION["createfiche"][1]) ? $entreprises[0]["nomEntreprise"] : "" ?>"
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>

                                    <div class="sm:col-span-6">
                                        <div>
                                            <div class="mt-6 flow-root">
                                                <ul id="listeEntreprise" role="list"
                                                    class="-my-5 divide-y divide-gray-200">
                                                    <?php foreach ($entreprises as $entreprise): ?>
                                                        <li class="py-4">
                                                            <div class="flex items-center space-x-4">
                                                                <div class="min-w-0 flex-1">
                                                                    <p class="truncate text-sm font-medium text-gray-900">
                                                                        <?= $entreprise["nomEntreprise"] ?></p>
                                                                    <p class="truncate text-sm text-gray-500">
                                                                        <?= $entreprise["missionEntreprise"] ?></p>
                                                                </div>
                                                                <div>
                                                                    <a href="?step=02&id_entreprise=<?= $entreprise["idEntreprise"] ?>"
                                                                       class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Selectionner</a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>


                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a type="submit" href="create-fiche.php?step=2&id_entreprise=-1"
                               class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Je ne trouve pas mon entreprise
                            </a>
                        </div>
                    </div>
                </div>

                <script>
                    $("#nomEntreprise").keypress(function (a) {
                        console.log($("#nomEntreprise ").val());

                        const settings = {
                            "async": true,
                            "crossDomain": true,
                            "url": "api.php?entr=" + $("#nomEntreprise ").val(),
                            "method": "GET",
                            "headers": {},
                            "processData": false,
                            "contentType": false,
                            "mimeType": "multipart/form-data"
                        };

                        $.ajax(settings).done(function (response) {
                            result = (JSON.parse(response));

                            $(" #listeEntreprise ").empty();
                            console.log(result)

                            result.forEach(function (element) {
                                $(" #listeEntreprise ").append(`
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-gray-900">
                                                    ${element.nomEntreprise}</p>
                                                <p class="truncate text-sm text-gray-500">
                                                    ${element.missionEntreprise}</p>
                                            </div>
                                            <div>
                                                <a href="?step=2&id_entreprise=${element.idEntreprise}"
                                                   class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Selectionner</a>
                                            </div>
                                        </div>
                                    </li>`)
                                console.log(element);
                            })
                        });
                    });
                </script>
            <?php else: ?>

                <form method="post" class="mx-auto max-w-2xl">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Création d'une fiche
                                entreprise</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Malheureusement ton entreprise n'a pas été
                                trouvée dans la base de donnée des entrerpises, tu dois donc maintenant créer une fiche
                                entreprise. Si tu penses que c'est une erreur, tu peux toujours <a
                                        class="text-indigo-600" href="create-fiche.php?step=2">réessayer de la
                                    trouver</a></p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                <div class="sm:col-span-3">
                                    <label for="nomEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Nom
                                        entreprise</label>
                                    <div class="mt-2">
                                        <input type="text" id="nomEntreprise" name="nomEntreprise" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="siretEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">SIRET
                                        entreprise</label>
                                    <div class="mt-2">
                                        <input type="text" id="siretEntreprise" name="siretEntreprise" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="telEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Téléphone
                                        entreprise</label>
                                    <div class="mt-2">
                                        <input type="text" id="telEntreprise" name="telEntreprise" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="mailEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mail Entreprise
                                    </label>
                                    <div class="mt-2">
                                        <input type="email" id="mailEntreprise" name="mailEntreprise" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="descriptionEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mission
                                        de l'entreprise (100 chars max)</label>
                                    <div class="mt-2">
                                        <textarea rows="4" name="descriptionEntreprise" id="descriptionEntreprise"
                                                  required
                                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="numeroEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Numéro
                                        adresse postale
                                    </label>
                                    <div class="mt-2">
                                        <input type="number" name="numeroEntreprise" id="numeroEntreprise" required
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="voieEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Voie
                                        adresse postale
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" name="voieEntreprise" id="voieEntreprise" required
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="codePostalEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Code
                                        postal
                                    </label>
                                    <div class="mt-2">
                                        <input type="number" name="codePostalEntreprise" id="codePostalEntreprise"
                                               required
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="villeEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Ville</label>
                                    <div class="mt-2">
                                        <input type="text" name="villeEntreprise" id="villeEntreprise" required
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                            </div>


                        </div>
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Création du responsable legal de
                                l'entreprise</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Même si l'idée m'amuse un peu, une
                                entreprise se doit d'avoir un responsable physique. On parle bien du <b>responsable de
                                    l'organisme</b>, pas ton maitre de stage</p>
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                <div class="sm:col-span-3">
                                    <label for="nomContact" class="block text-sm font-medium leading-6 text-gray-900">Nom
                                        contact</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 flex items-center">
                                            <!--                                            <label for="titreContact" class="sr-only"></label>-->
                                            <select id="titreContact" name="titreContact"
                                                    class="h-full rounded-md border-0 bg-transparent py-0 pl-3 pr-7 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                                <option <?= (($_POST["titre"] ?? '') === "Mr") ? "selected" : "" ?>
                                                        value="Mr">Mr
                                                </option>
                                                <option <?= (($_POST["titre"] ?? '') === "Mme") ? "selected" : "" ?>
                                                        value="Mme">
                                                    Mme
                                                </option>
                                                <option <?= (($_POST["titre"] ?? '') === "Mlle") ? "selected" : "" ?>
                                                        value="Mlle">
                                                    Mlle
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" name="nomContact" id="nomContact" required
                                               value="<?= $_POST["last_name"] ?? null ?>"
                                               class="block w-full rounded-md border-0 py-1.5 pl-16 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="prenomContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Prénom
                                        contact</label>
                                    <div class="mt-2">
                                        <input type="text" id="prenomContact" name="prenomContact" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="mobileContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mobile contact</label>
                                    <div class="mt-2">
                                        <input type="text" id="mobileContact" name="mobileContact" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <div class="flex justify-between">
                                        <label for="fixeContact"
                                               class="block text-sm font-medium leading-6 text-gray-900">Fixe contact</label>
                                        <span class="text-sm leading-6 text-gray-500"
                                              id="email-optional">Optionnel</span>
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" id="fixeContact" name="fixeContact"
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="mailContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mail contact</label>
                                    <div class="mt-2">
                                        <input type="email" id="mailContact" name="mailContact" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="fonctionContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Profession</label>
                                    <select id="fonctionContact" name="fonctionContact"
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <?php foreach ($fonctions as $f): ?>
                                            <option value="<?= $f["idFonction"] ?>"><?= $f["libFonction"] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>


                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button type="submit"
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Sauvegarder
                            </button>
                        </div>
                    </div>
                </form>

            <?php endif; ?>

            <?php break ?>

            <?php case 3: ?>

            <?php if (empty($_GET["id_contact"]) or $_GET["id_contact"] == 0): ?>
                <div class="mx-auto max-w-2xl">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Le tuteur de stage</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Fais CTRL+F pour chercher ton maitre de
                                stage si jamais il y en a trop :)</p>

                            <div class="">
                                <div class="sm:flex sm:items-center">
                                </div>
                                <div class="mt-8 flow-root">
                                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead>
                                                <tr>
                                                    <th scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                                        Name
                                                    </th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Title
                                                    </th>
                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                        <span class="sr-only">Choisir</span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white">
                                                <?php foreach ($contacts as $contact): ?>
                                                    <tr>
                                                        <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                                            <div class="flex items-center">
                                                                <div class="ml-4">
                                                                    <div
                                                                            class="font-medium text-gray-900"><?= $contact["titreContact"] ?>
                                                                        . <?= $contact["nomContact"] ?> <?= $contact["prenomContact"] ?></div>
                                                                    <div
                                                                            class="mt-1 text-gray-500"><?= $contact["mailContact"] ?></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                                            <div
                                                                    class="text-gray-900"><?= $contact["libFonction"] ?></div>
                                                            <!--                                                        <div class="mt-1 text-gray-500">Je sais pas quoi faire mais j'ai ajouté la possibilitée d'avoir 2 lignes ici</div>-->
                                                        </td>
                                                        <td class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                            <a href="?step=3&contact=<?= $contact["idContact"]; ?>"
                                                               class="text-indigo-600 hover:text-indigo-900">Choisir</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a type="submit" href="create-fiche.php?step=3&id_contact=-1"
                               class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Je ne trouve pas mon maitre de stage
                            </a>
                        </div>
                    </div>
                </div>
            <?php elseif (!empty($_GET["id_contact"]) and $_GET["id_contact"] == -1): ?>
                <form method="post" class="mx-auto max-w-2xl">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Création d'une fiche
                                contact</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Malheureusement ton maitre de stage n'a pas
                                été trouvé(e) dans la base de donnée. Tu dois donc créer ton contact. Si tu penses que
                                c'est une erreur, tu peux toujours <a
                                        class="text-indigo-600" href="create-fiche.php?step=03">réessayer de la
                                    trouver</a></p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                <div class="sm:col-span-3">
                                    <label for="nomContact" class="block text-sm font-medium leading-6 text-gray-900">Nom
                                        contact</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 flex items-center">
                                            <!--                                            <label for="titreContact" class="sr-only"></label>-->
                                            <select id="titreContact" name="titreContact"
                                                    class="h-full rounded-md border-0 bg-transparent py-0 pl-3 pr-7 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                                <option <?= (($_POST["titre"] ?? '') === "Mr") ? "selected" : "" ?>
                                                        value="Mr">Mr
                                                </option>
                                                <option <?= (($_POST["titre"] ?? '') === "Mme") ? "selected" : "" ?>
                                                        value="Mme">
                                                    Mme
                                                </option>
                                                <option <?= (($_POST["titre"] ?? '') === "Mlle") ? "selected" : "" ?>
                                                        value="Mlle">
                                                    Mlle
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" name="nomContact" id="nomContact" required
                                               value="<?= $_POST["last_name"] ?? null ?>"
                                               class="block w-full rounded-md border-0 py-1.5 pl-16 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="prenomContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Prénom
                                        contact</label>
                                    <div class="mt-2">
                                        <input type="text" id="prenomContact" name="prenomContact" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="mobileContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mobile contact</label>
                                    <div class="mt-2">
                                        <input type="text" id="mobileContact" name="mobileContact" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <div class="flex justify-between">
                                        <label for="fixeContact"
                                               class="block text-sm font-medium leading-6 text-gray-900">Fixe contact</label>
                                        <span class="text-sm leading-6 text-gray-500"
                                              id="email-optional">Optionnel</span>
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" id="fixeContact" name="fixeContact"
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="mailContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mail contact</label>
                                    <div class="mt-2">
                                        <input type="email" id="mailContact" name="mailContact" required
                                               class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="fonctionContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Profession</label>
                                    <select id="fonctionContact" name="fonctionContact"
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <?php foreach ($fonctions as $f): ?>
                                            <option value="<?= $f["idFonction"] ?>"><?= $f["libFonction"] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>


                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button type="submit"
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Sauvegarder
                            </button>
                        </div>
                    </div>
                </form>
            <?php else: header("Location: create-fiche.php?step=03");
                die; ?>
            <?php endif; ?>

            <?php break; ?>

            <?php case 4: ?>

                <form method="post" class="mx-auto max-w-2xl">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Le lieu de stage</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">La plupart de ces champs sont
                                préremplis
                                avec les informations de l'entreprise. Si elles sont incorrectes,
                                clique sur le bouton pour les modifier</p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                <div class="sm:col-span-6">
                                    <label for="adresseStage"
                                           class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                        de résidence pendant le stage</label>
                                    <div class="mt-2">
                                        <input id="adresseStage" type="text" name="adresseStage" required
                                               value="<?= $entreprise["numAdrEntreprise"] . " " . $entreprise["libAdrEntreprise"] . " " . $entreprise["codePostalAdrEntreprise"] . " " . $entreprise["villeAdrEntreprise"] ?>"
                                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Sauvegarder
                        </button>
                    </div>
                </form>

            <?php break; ?>
            <?php case 5: ?>


                <div class="mx-auto max-w-2xl">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-6">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Validation</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Tu as maintenant rempli tous les champs
                                necessaires pour créer une fiche de renseignements. Nous te conseillons de vérifier
                                l'exactitude des informations entrées avant d'enregistrer ta fiche de
                                renseignements.</p>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12 mt-5">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">L'étudiant</h2>
                            <!--                            <p class="mt-1 text-sm leading-6 text-gray-600">Tu as maintenant rempli tous les champs necessaires pour créer une fiche de renseignements. Nous te conseillons de vérifier l'exactitude des informations entrés avant d'enregistrer ta fiche de renseignements.</p>-->


                            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <label for="nom"
                                           class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                                    <div class="mt-2">
                                        <input type="text" id="nom" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= strtoupper($_SESSION["user"]["nomUtil"]) ?>">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="prenom"
                                           class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                                    <div class="mt-2">
                                        <input type="text" id="prenom" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= ucfirst($_SESSION["user"]["prenomUtil"]) ?>">
                                    </div>
                                </div>


                                <div class="sm:col-span-2">
                                    <label for="classe"
                                           class="block text-sm font-medium leading-6 text-gray-900">Classe</label>
                                    <div class="mt-2">
                                        <input type="text" id="classe" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= $section["nomCourtSection"] ?>">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="adresse"
                                           class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                        de résidence pendant le stage</label>
                                    <div class="mt-2">
                                        <input id="adresse" type="text" disabled
                                               value="<?= $student["numAdrEleve"] . " " . $student["libAdrEleve"] . " " . $student["codePostalAdrEleve"] . " " . $student["villeAdrEleve"] ?>"
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="datedebut"
                                           class="block text-sm font-medium leading-6 text-gray-900">Date de
                                        début de
                                        stage</label>
                                    <div class="mt-2">
                                        <input type="date" id="datedebut" name="datedebut"
                                               value="<?= $_SESSION["createfiche"][0]["datedebut"] ?>" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        >
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="datefin"
                                           class="block text-sm font-medium leading-6 text-gray-900">Date de fin
                                        de
                                        stage</label>
                                    <div class="mt-2">
                                        <input type="date" id="datefin" name="datefin"
                                               value="<?= $_SESSION["createfiche"][0]["datefin"] ?>" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        >
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="heures"
                                           class="block text-sm font-medium leading-6 text-gray-900">Heures
                                        de travail hebdomadaires</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <input type="number" name="heures" id="heures"
                                               value="<?= $_SESSION["createfiche"][0]["heures"] ?>" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 pl-3 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm mr-3"
                                                  id="price-currency">heures</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="numEtud"
                                           class="block text-sm font-medium leading-6 text-gray-900">Numéro de
                                        téléphone</label>
                                    <div class="mt-2">
                                        <input type="text" name="numEtud" id="numEtud"
                                               value="<?= $_SESSION["user"]["mobileUtil"] ?>" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12 mt-5">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">L'organisme</h2>
                            <!--                            <p class="mt-1 text-sm leading-6 text-gray-600">Tu as maintenant rempli tous les champs necessaires pour créer une fiche de renseignements. Nous te conseillons de vérifier l'exactitude des informations entrés avant d'enregistrer ta fiche de renseignements.</p>-->


                            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-6">
                                    <label for="nomEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Nom de l'organisme
                                        <b>signataire de la convention</b></label>
                                    <div class="mt-2">
                                        <input type="text" id="nomEntreprise" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= strtoupper($entreprise["nomEntreprise"]) ?>">
                                    </div>
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="adresse"
                                           class="block text-sm font-medium leading-6 text-gray-900">Adresse</label>
                                    <div class="mt-2">
                                        <input type="text" id="adresse" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= $entreprise["numAdrEntreprise"] . " " . $entreprise["libAdrEntreprise"] ?>">
                                    </div>
                                </div>


                                <div class="sm:col-span-2">
                                    <label for="classe"
                                           class="block text-sm font-medium leading-6 text-gray-900">Téléphone</label>
                                    <div class="mt-2">
                                        <input type="text" id="classe" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= $entreprise["telEntreprise"] ?>">
                                    </div>
                                </div>


                                <div class="sm:col-span-3">
                                    <label for="villeEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">Ville</label>
                                    <div class="mt-2">
                                        <input type="text" id="villeEntreprise" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= $entreprise["codePostalAdrEntreprise"] . " " . $entreprise["villeAdrEntreprise"] ?>">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="siretEntreprise"
                                           class="block text-sm font-medium leading-6 text-gray-900">N° SIRET</label>
                                    <div class="mt-2">
                                        <input type="text" id="siretEntreprise" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                               value="<?= $entreprise["siretEntreprise"] ?>">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="missionOrganisme"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mission de cet
                                        organisme</label>
                                    <div class="mt-2">
                                        <textarea type="text" id="missionOrganisme" disabled
                                                  class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        ><?= $entreprise["missionEntreprise"] ?></textarea>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="nomContact" class="block text-sm font-medium leading-6 text-gray-900">Nom
                                        du responsable de l'organisme</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 flex items-center">
                                            <select id="titreContact" name="titreContact" disabled
                                                    class="h-full rounded-md border-0 bg-transparent py-0 pl-3 pr-7 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                                <option <?= (($responsable["titreContact"] ?? '') === "Mr") ? "selected" : "" ?>
                                                        value="Mr">Mr
                                                </option>
                                                <option <?= (($responsable["titreContact"] ?? '') === "Mme") ? "selected" : "" ?>
                                                        value="Mme">
                                                    Mme
                                                </option>
                                                <option <?= (($responsable["titreContact"] ?? '') === "Mlle") ? "selected" : "" ?>
                                                        value="Mlle">
                                                    Mlle
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" id="nomContact" disabled
                                               value="<?= strtoupper($responsable["nomContact"]) . " " . ucfirst($responsable["prenomContact"]) ?>"
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 pl-16 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="fonctionContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Fonction
                                        responsable</label>
                                    <select id="fonctionContact" name="fonctionContact" disabled
                                            class="bg-slate-100 mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option><?= $responsable["libFonction"] ?></option>
                                    </select>
                                </div>


                            </div>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12 mt-5">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Le tuteur de stage</h2>
                            <!--                            <p class="mt-1 text-sm leading-6 text-gray-600">Tu as maintenant rempli tous les champs necessaires pour créer une fiche de renseignements. Nous te conseillons de vérifier l'exactitude des informations entrés avant d'enregistrer ta fiche de renseignements.</p>-->


                            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="nomContact" class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 flex items-center">
                                            <select id="titreContact" name="titreContact" disabled
                                                    class="h-full rounded-md border-0 bg-transparent py-0 pl-3 pr-7 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                                <option <?= (($contact["titreContact"] ?? '') === "Mr") ? "selected" : "" ?>
                                                        value="Mr">Mr
                                                </option>
                                                <option <?= (($contact["titreContact"] ?? '') === "Mme") ? "selected" : "" ?>
                                                        value="Mme">
                                                    Mme
                                                </option>
                                                <option <?= (($contact["titreContact"] ?? '') === "Mlle") ? "selected" : "" ?>
                                                        value="Mlle">
                                                    Mlle
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" id="nomContact" disabled
                                               value="<?= strtoupper($contact["nomContact"]) . " " . ucfirst($contact["prenomContact"]) ?>"
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 pl-16 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="fonctionContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Fonction</label>
                                    <select id="fonctionContact" name="fonctionContact" disabled
                                            class="bg-slate-100 mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option><?= $contact["libFonction"] ?></option>
                                    </select>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="numContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Numéro de
                                        téléphone</label>
                                    <div class="mt-2">
                                        <input type="text" name="numContact" id="numContact"
                                               value="<?= $contact["telMobileContact"] ?>" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="mailContact"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mail
                                        contact</label>
                                    <div class="mt-2">
                                        <input type="email" name="mailContact" id="mailContact"
                                               value="<?= $contact["mailContact"] ?>" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12 mt-5">
                            <!--                            <h2 class="text-base font-semibold leading-7 text-gray-900"></h2>-->

                            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">


                                <div class="sm:col-span-6">
                                    <label for="AdresseStage"
                                           class="block text-sm font-medium leading-6 text-gray-900">Lieu de stage (si
                                        différent de l'organisme signataire)</label>
                                    <div class="mt-2">
                                        <input type="email" name="AdresseStage" id="AdresseStage"
                                               value="<?= $adrStage ?>" disabled
                                               class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="AdresseStage"
                                           class="block text-sm font-medium leading-6 text-gray-900">Activités
                                        envisagées pour le stagiaire pendant le stage</label>
                                    <div class="mt-2">
                                        <textarea type="text" id="description" disabled
                                                  class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?= $_SESSION["createfiche"][0]["description"] ?></textarea>
                                    </div>
                                </div>


                            </div>
                        </div>


                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <a href="create-fiche.php?step=5&confirm=1"
                           class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Sauvegarder
                        </a>
                    </div>
                </div>

                <?php break ?>
            <?php default: ?>
                    <?php header("Location: create-fiche.php");
                    die; ?>
                    <?php break; ?>
                <?php endswitch; ?>


    </main>
</div>
</body>

<!--<script>-->
<!---->
<!--    $(document).ready(function () {-->
<!--        $("#createStudent").click(function () {-->
<!--            $("#noprofile").hide();-->
<!--            $("#studentProfil").show();-->
<!--        });-->
<!--    });-->
<!---->
<!--</script>-->

<?php

function showStep(string $type, string $id, string $name, bool $end)
{


    $html = "";
    if ($type === "current") {
        $html = "<li class=\"relative md:flex md:flex-1\">
                            <!-- Current Step -->
                            <a href=\"?step=$id\" class=\"flex items-center px-6 py-4 text-sm font-medium\" aria-current=\"step\">
                                <span
                                    class=\"flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-indigo-600\">
                                <span class=\"text-indigo-600\">$id</span>
                                </span>
                                <span class=\"ml-4 text-sm font-medium text-indigo-600\">$name</span>
                            </a>";

    }
    if ($type === "next") {
        $html = "<li class=\"relative md:flex md:flex-1\">
                            <!-- Upcoming Step -->
                            <a href=\"?step=$id\" class=\"group flex items-center\">
                                <span class=\"flex items-center px-6 py-4 text-sm font-medium\">
                                <span
                                    class=\"flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-300 group-hover:border-gray-400\">
                                <span class=\"text-gray-500 group-hover:text-gray-900\">$id</span>
                                </span>
                                <span
                                    class=\"ml-4 text-sm font-medium text-gray-500 group-hover:text-gray-900\">$name</span>
                                </span>
                            </a>";
    }
    if ($type === "past") {
        $html = "<li class=\"relative md:flex md:flex-1\">
      <!-- Completed Step -->
      <a href=\"?step=$id\" class=\"group flex w-full items-center\">
        <span class=\"flex items-center px-6 py-4 text-sm font-medium\">
          <span class=\"flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600 group-hover:bg-indigo-800\">
            <svg class=\"h-6 w-6 text-white\" viewBox=\"0 0 24 24\" fill=\"currentColor\" aria-hidden=\"true\">
              <path fill-rule=\"evenodd\" d=\"M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z\" clip-rule=\"evenodd\" />
            </svg>
          </span>
          <span class=\"ml-4 text-sm font-medium text-gray-900\">$name</span>
        </span>
      </a>";
    }

    if (!$end) {
        $html .= "
                            <!-- Arrow separator for lg screens and up -->
                            <div class=\"absolute right-0 top-0 hidden h-full w-5 md:block\" aria-hidden=\"true\">
                                <svg class=\"h-full w-full text-gray-300\" viewBox=\"0 0 22 80\" fill=\"none\"
                                     preserveAspectRatio=\"none\">
                                    <path d=\"M0 -2L20 40L0 82\" vector-effect=\"non-scaling-stroke\" stroke=\"currentcolor\"
                                          stroke-linejoin=\"round\"/>
                                </svg>
                            </div>";
    }
    $html .= "</li>";
    return $html;


}


?>

<?php require "assets/php/footer.php" ?>
