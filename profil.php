<?php require "assets/php/header.php";
loggedVerif();

$years = $db->query("SELECT * FROM anneescolaire ORDER BY idAnneeScolaire DESC")->fetchAll();
$sections = $db->query("SELECT * FROM section")->fetchAll();

//Ceci conserne la partie "profil utilisateur"
if ($_POST) {
    if (empty($_POST["prenom"]) or
        empty($_POST["nom"]) or
        empty($_POST["email"])
    ) {
        createSessionError("notAllFieldsFilled");
    }

    if (countSessionErrors() == 0) {


        $req = $db->prepare("UPDATE utilisateur SET prenomUtil = :prenom, nomUtil = :nom, mailUtil = :email, mobileUtil = :mobile, mailPersoUtil = :mailPerso WHERE idUtil = :id");
        $req->execute([
            "prenom" => $_POST["prenom"],
            "nom" => $_POST["nom"],
            "email" => $_POST["email"],
            "mobile" => $_POST["phone"],
            "mailPerso" => $_POST["emailperso"],
            "id" => $_SESSION["user"]["idUtil"]
        ]);

        $req = $db->prepare("SELECT idUtil, titreUtil, nomUtil, prenomUtil, mobileUtil, mailPersoUtil, mailUtil FROM utilisateur WHERE idUtil = :id");
        $req->execute([
            "id" => $_SESSION["user"]["idUtil"]
        ]);
        $rep = $req->fetch();

        if (!$rep) {
            die("This error shouldn't happen but if it does, please contact me: marc.magueur@limayrac.fr");
        }

        $_SESSION["user"] = $rep;
    }

}

//Dans le cas ou on ne traite pas un prof (utilisateur sans catégorie ou étudiant)
if ($_POST && !isProf()) {
//    var_dump($_POST);
    //Si aumoins un input de l'étudiant est rempli ET que tous les inputs de l'étudiant ne sont pas remplis alors

    if (
        empty($_POST["day"]) and
        empty($_POST["year"]) and
        empty($_POST["numero"]) and
        empty($_POST["voie"]) and
        empty($_POST["codePostal"]) and
        empty($_POST["ville"]) and
        empty($_POST["anneeScolaire"]) and
        empty($_POST["mois"]) and
        empty($_POST["section"]
        )
    ) {
        createSessionError("notAllFieldsFilled");
    }

    //J'avais plus rien a faire donc j'ai rajouté de la sécuritée, on check si l'identifiant de l'année scolaire existe bien :)
    //JAMAIS FAIRE CONFIANCE A L'UTILISATEUR FINAL (surtout si c'est moi)
    $req = $db->prepare("SELECT * FROM anneescolaire WHERE idAnneeScolaire = :id");
    $req->execute([
        "id" => $_POST["anneeScolaire"]??0
    ]);
    $rep = $req->fetch();
    if (!$rep and !isEleve()) {
        createSessionError("invalidYear");
    }

    if ($_POST["day"] == 0 or $_POST["year"] < 1900 or $_POST["year"] > date("Y") - 10 ) {
        createSessionError("notAllFieldsFilled");
    }


    if (countSessionErrors() == 0 ) {

        //Si l'étudiant est déjà dans la base de donnée, c'est un update, sinon un create
        if (isEleve()) {
            $req = $db->prepare("UPDATE eleve SET dateNaissanceEleve = :dateanniv, numAdrEleve = :numadr, libAdrEleve = :libadr, codePostalAdrEleve = :codeadr, villeAdrEleve = :villeAdr WHERE idEleve = :id");
            $req->execute([
                "id" => $_SESSION["user"]["idUtil"],
                "dateanniv" => $_POST["year"]."-".$_POST["mois"]."-".$_POST["day"],
                "numadr" => $_POST["numero"],
                "libadr" => $_POST["voie"],
                "codeadr" => $_POST["codePostal"],
                "villeAdr" => $_POST["ville"],
            ]);
        } else {
            $req = $db->prepare("INSERT INTO eleve VALUES (:id, :dateanniv, :numadr, :libadr, :codeadr, :villeAdr, :daterentre)");
            $req->execute([
                "id" => $_SESSION["user"]["idUtil"],
                "dateanniv" => $_POST["year"]."-".$_POST["mois"]."-".$_POST["day"],
                "numadr" => $_POST["numero"],
                "libadr" => $_POST["voie"],
                "codeadr" => $_POST["codePostal"],
                "villeAdr" => $_POST["ville"],
                "daterentre" => date('Y-m-d')
            ]);

            $redoublant = (int)((bool)($_POST["redoublant"]??false));
            $req = $db->prepare("INSERT INTO inscription VALUES (:id, :idannee, :idsec, :annee, :idRedoublement)");
            $req->execute([
                "id" => $_SESSION["user"]["idUtil"],
                "idannee" => $_POST["anneeScolaire"],
                "idsec" => $_POST["section"],
                "annee" => 1,
                "idRedoublement" => $redoublant
            ]);
        }
    }
    header("Location: dashboard.php");
}

if (isEleve()) {
    $student = fetchEleveData();
}

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
                                           class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Dashboard
                                        </a>
                                        <a href="profil.php"
                                           class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium"
                                           aria-current="page">Mon profil</a>
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
                    <h1 class="text-3xl font-bold tracking-tight text-white">Mon profil</h1>
                </div>
            </header>
        </div>

        <main class="-mt-32">
            <div class="mx-auto max-w-7xl px-4 pb-12 pt-12 sm:px-6 lg:px-8 bg-white rounded-lg border-black bg-white">

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
                                <h3 class="text-sm font-medium text-red-800">Oubs, je pense qu'il y a une coquille
                                    quelque
                                    part :(</h3>
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

                <?php if (!isEleve() && !isProf()): ?>

                    <div class="text-center mb-10" id="noprofile">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>

                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Aucun profil de configuré</h3>
                        <p class="mt-1 text-sm text-gray-500">En fonction de si vous êtes un étudiant ou un professeur
                            l'application ne marche pas de la même manière.<br>Dans le cas ou vous êtes un professeur,
                            merci de contacter un administrateur pour configurer votre professeur</p>
                        <div class="mt-6">
                            <button type="button" id="createStudent"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                     aria-hidden="true">
                                    <path
                                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"></path>
                                </svg>
                                Créer mon profil étudiant
                            </button>
                        </div>
                        <hr class="mt-10">
                    </div>


                    <form action="profil.php" method="post" class="mx-auto max-w-2xl" id="profileStudent">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Profil utilisateur</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Ces informations sont les informations
                                    que l'application utilise de façon indépendante du fait d'être professeur ou
                                    étudiant.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="prenom"
                                               class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                                        <div class="mt-2">
                                            <input type="text" name="prenom" id="prenom"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                   value="<?= $_SESSION["user"]["prenomUtil"] ?>">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="nom"
                                               class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                                        <div class="mt-2">
                                            <input type="text" name="nom" id="nom"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                   value="<?= $_SESSION["user"]["nomUtil"] ?>">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                            email professionnelle (@limayrac.fr)</label>
                                        <div class="mt-2">
                                            <input id="email" name="email" type="email" autocomplete="email"
                                                   value="<?= $_SESSION["user"]["mailUtil"] ?>"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <div class="flex justify-between">
                                            <label for="emailperso"
                                                   class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                                email personnelle</label>
                                            <span class="text-sm leading-6 text-gray-500"
                                                  id="email-optional">Optionnel</span>
                                        </div>
                                        <div class="mt-2">
                                            <input id="emailperso" name="emailperso" type="email"
                                                   value="<?= $_SESSION["user"]["mailPersoUtil"] ?>"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Numéro
                                            de téléphone portable</label>
                                        <div class="mt-2">
                                            <div
                                                    class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                                <span
                                                        class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">+33 (0)</span>
                                                <input type="text" name="phone" id="phone"
                                                       value="<?= $_SESSION["user"]["mobileUtil"] ?>"
                                                       class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                                       placeholder="7 00 00 00 00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="studentProfil" class="border-b border-gray-900/10 pb-12" hidden>
                                <h2 class="mt-3 text-base font-semibold leading-7 text-gray-900">Profil étudiant</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Ces informations sont les informations
                                    utiles pour le profil d'un étudiant.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                    <div class="sm:col-span-2 sm:col-start-1">
                                        <label for="day" class="block text-sm font-medium leading-6 text-gray-900">Jour
                                            de naissance</label>
                                        <div class="mt-2">
                                            <input type="number" name="day" id="day"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="mois" class="block text-sm font-medium leading-6 text-gray-900">Mois
                                            de naissance</label>
                                        <div class="mt-2">
                                            <select id="mois" name="mois"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                <option value="01">Janvier</option>
                                                <option value="02">Février</option>
                                                <option value="03">Mars</option>
                                                <option value="04">Avril</option>
                                                <option value="05">Mai</option>
                                                <option value="06">Juin</option>
                                                <option value="07">Juillet</option>
                                                <option value="08">Aout</option>
                                                <option value="09">Septembre</option>
                                                <option value="10">Octobre</option>
                                                <option value="11">Novembre</option>
                                                <option value="12">Décembre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="year" class="block text-sm font-medium leading-6 text-gray-900">Année
                                            de naissance</label>
                                        <div class="mt-2">
                                            <input type="number" name="year" id="year"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="numero" class="block text-sm font-medium leading-6 text-gray-900">Numéro
                                            adresse postale
                                        </label>
                                        <div class="mt-2">
                                            <input type="number" name="numero" id="numero"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label for="voie" class="block text-sm font-medium leading-6 text-gray-900">Voie
                                            adresse postale
                                        </label>
                                        <div class="mt-2">
                                            <input type="text" name="voie" id="voie"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="codePostal"
                                               class="block text-sm font-medium leading-6 text-gray-900">Code
                                            postal
                                        </label>
                                        <div class="mt-2">
                                            <input type="number" name="codePostal" id="codePostal"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="ville" class="block text-sm font-medium leading-6 text-gray-900">Ville</label>
                                        <div class="mt-2">
                                            <input type="text" name="ville" id="ville"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="anneeScolaire"
                                               class="block text-sm font-medium leading-6 text-gray-900">Année
                                            scolaire de rentrée</label>
                                        <div class="mt-2">
                                            <select id="anneeScolaire" name="anneeScolaire"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                <?php foreach ($years as $year): ?>
                                                    <option
                                                            value="<?= $year["idAnneeScolaire"] ?>"><?= $year["libAnneeScolaire"] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="section"
                                               class="block text-sm font-medium leading-6 text-gray-900">Section</label>
                                        <div class="mt-2">
                                            <select id="section" name="section"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                <?php foreach ($sections as $section): ?>
                                                    <option
                                                            value="<?= $section["idSection"] ?>"><?= $section["nomCourtSection"] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <fieldset class="sm:col-span-6">
                                        <legend class="sr-only">Je suis redoublant</legend>
                                        <div class="space-y-5">
                                            <div class="relative flex items-start">
                                                <div class="flex h-6 items-center">
                                                    <input id="redoublant" aria-describedby="comments-description" name="redoublant" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                                </div>
                                                <div class="ml-3 text-sm leading-6">
                                                    <label for="redoublant" class="font-medium text-gray-900">Je suis redoublant</label>
                                                    <span id="comments-description" class="text-gray-500"><span class="sr-only">Je suis redoublant </span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

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



                <?php elseif (isEleve() and !isProf()): ?>

                    <?php $date = explode("-", $student["dateNaissanceEleve"]); ?>

                    <form action="profil.php" method="post" class="mx-auto max-w-2xl" id="profileStudent">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Profil utilisateur</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Ces informations sont les informations
                                    que l'application utilise de façon indépendante du fait d'être professeur ou
                                    étudiant.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="prenom"
                                               class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                                        <div class="mt-2">
                                            <input type="text" name="prenom" id="prenom"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                   value="<?= $_SESSION["user"]["prenomUtil"] ?>">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="nom"
                                               class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                                        <div class="mt-2">
                                            <input type="text" name="nom" id="nom"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                   value="<?= $_SESSION["user"]["nomUtil"] ?>">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                            email professionnelle (@limayrac.fr)</label>
                                        <div class="mt-2">
                                            <input id="email" name="email" type="email" autocomplete="email"
                                                   value="<?= $_SESSION["user"]["mailUtil"] ?>"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <div class="flex justify-between">
                                            <label for="emailperso"
                                                   class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                                email personnelle</label>
                                            <span class="text-sm leading-6 text-gray-500"
                                                  id="email-optional">Optionnel</span>
                                        </div>
                                        <div class="mt-2">
                                            <input id="emailperso" name="emailperso" type="email"
                                                   value="<?= $_SESSION["user"]["mailPersoUtil"] ?>"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Numéro
                                            de téléphone portable</label>
                                        <div class="mt-2">
                                            <div
                                                    class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                                <span
                                                        class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">+33 (0)</span>
                                                <input type="text" name="phone" id="phone"
                                                       value="<?= $_SESSION["user"]["mobileUtil"] ?>"
                                                       class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                                       placeholder="7 00 00 00 00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="mt-3 text-base font-semibold leading-7 text-gray-900">Profil étudiant</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Ces informations sont les informations
                                    utiles pour le profil d'un étudiant.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                    <div class="sm:col-span-2 sm:col-start-1">
                                        <label for="day" class="block text-sm font-medium leading-6 text-gray-900">Jour
                                            de naissance</label>
                                        <div class="mt-2">
                                            <input value="<?= $date[2] ?>" type="number" name="day" id="day"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="mois" class="block text-sm font-medium leading-6 text-gray-900">Mois
                                            de naissance</label>
                                        <div class="mt-2">
                                            <select id="mois" name="mois"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                <option <?= ($date[1] == "01")?'selected':'' ?> value="01">Janvier</option>
                                                <option <?= ($date[1] == "02")?'selected':'' ?> value="02">Février</option>
                                                <option <?= ($date[1] == "03")?'selected':'' ?> value="03">Mars</option>
                                                <option <?= ($date[1] == "04")?'selected':'' ?> value="04">Avril</option>
                                                <option <?= ($date[1] == "05")?'selected':'' ?> value="05">Mai</option>
                                                <option <?= ($date[1] == "06")?'selected':'' ?> value="06">Juin</option>
                                                <option <?= ($date[1] == "07")?'selected':'' ?> value="07">Juillet</option>
                                                <option <?= ($date[1] == "08")?'selected':'' ?> value="08">Aout</option>
                                                <option <?= ($date[1] == "09")?'selected':'' ?> value="09">Septembre</option>
                                                <option <?= ($date[1] == "10")?'selected':'' ?> value="10">Octobre</option>
                                                <option <?= ($date[1] == "11")?'selected':'' ?> value="11">Novembre</option>
                                                <option <?= ($date[1] == "12")?'selected':'' ?> value="12">Décembre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="year" class="block text-sm font-medium leading-6 text-gray-900">Année
                                            de naissance</label>
                                        <div class="mt-2">
                                            <input value="<?= $date[0] ?>" type="number" name="year" id="year"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="numero" class="block text-sm font-medium leading-6 text-gray-900">Numéro
                                            adresse postale
                                        </label>
                                        <div class="mt-2">
                                            <input value="<?= $student["numAdrEleve"] ?>" type="number" name="numero" id="numero"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label for="voie" class="block text-sm font-medium leading-6 text-gray-900">Voie
                                            adresse postale
                                        </label>
                                        <div class="mt-2">
                                            <input value="<?= $student["libAdrEleve"] ?>" type="text" name="voie" id="voie"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="codePostal"
                                               class="block text-sm font-medium leading-6 text-gray-900">Code
                                            postal
                                        </label>
                                        <div class="mt-2">
                                            <input value="<?= $student["codePostalAdrEleve"] ?>" type="number" name="codePostal" id="codePostal"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label for="ville" class="block text-sm font-medium leading-6 text-gray-900">Ville</label>
                                        <div class="mt-2">
                                            <input value="<?= $student["villeAdrEleve"] ?>" type="text" name="ville" id="ville"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <!--                                    <div class="sm:col-span-3">-->
                                    <!--                                        <label for="anneeScolaire"-->
                                    <!--                                               class="block text-sm font-medium leading-6 text-gray-900">Année-->
                                    <!--                                            scolaire de rentrée</label>-->
                                    <!--                                        <div class="mt-2">-->
                                    <!--                                            <select id="anneeScolaire" name="anneeScolaire"-->
                                    <!--                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">-->
                                    <!--                                                --><?php //foreach ($years as $year): ?>
                                    <!--                                                    <option-->
                                    <!--                                                        value="--><?php //= $year["idAnneeScolaire"] ?><!--">--><?php //= $year["libAnneeScolaire"] ?><!--</option>-->
                                    <!--                                                --><?php //endforeach; ?>
                                    <!--                                            </select>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->

                                    <!--                                    <div class="sm:col-span-3">-->
                                    <!--                                        <label for="section"-->
                                    <!--                                               class="block text-sm font-medium leading-6 text-gray-900">Section</label>-->
                                    <!--                                        <div class="mt-2">-->
                                    <!--                                            <select id="section" name="section"-->
                                    <!--                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">-->
                                    <!--                                                --><?php //foreach ($sections as $section): ?>
                                    <!--                                                    <option-->
                                    <!--                                                        value="--><?php //= $section["idSection"] ?><!--">--><?php //= $section["nomCourtSection"] ?><!--</option>-->
                                    <!--                                                --><?php //endforeach; ?>
                                    <!--                                            </select>-->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->

                                    <!--                                    <fieldset class="sm:col-span-6">-->
                                    <!--                                        <legend class="sr-only">Je suis redoublant</legend>-->
                                    <!--                                        <div class="space-y-5">-->
                                    <!--                                            <div class="relative flex items-start">-->
                                    <!--                                                <div class="flex h-6 items-center">-->
                                    <!--                                                    <input id="redoublant" aria-describedby="comments-description" name="redoublant" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">-->
                                    <!--                                                </div>-->
                                    <!--                                                <div class="ml-3 text-sm leading-6">-->
                                    <!--                                                    <label for="redoublant" class="font-medium text-gray-900">Je suis redoublant</label>-->
                                    <!--                                                    <span id="comments-description" class="text-gray-500"><span class="sr-only">Je suis redoublant </span></span>-->
                                    <!--                                                </div>-->
                                    <!--                                            </div>-->
                                    <!--                                        </div>-->
                                    <!--                                    </fieldset>-->

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

                <?php elseif (isProf() and !isEleve()): ?>

                    <form action="profil.php" method="post" class="mx-auto max-w-2xl" id="profileStudent">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Profil utilisateur</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">Ces informations sont les informations
                                    que l'application utilise de façon indépendante du fait d'être professeur ou
                                    étudiant.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="prenom"
                                               class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                                        <div class="mt-2">
                                            <input type="text" name="prenom" id="prenom"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                   value="<?= $_SESSION["user"]["prenomUtil"] ?>">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="nom"
                                               class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                                        <div class="mt-2">
                                            <input type="text" name="nom" id="nom"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                   value="<?= $_SESSION["user"]["nomUtil"] ?>">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                            email professionnelle (@limayrac.fr)</label>
                                        <div class="mt-2">
                                            <input id="email" name="email" type="email" autocomplete="email"
                                                   value="<?= $_SESSION["user"]["mailUtil"] ?>"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <div class="flex justify-between">
                                            <label for="emailperso"
                                                   class="block text-sm font-medium leading-6 text-gray-900">Adresse
                                                email personnelle</label>
                                            <span class="text-sm leading-6 text-gray-500"
                                                  id="email-optional">Optionnel</span>
                                        </div>
                                        <div class="mt-2">
                                            <input id="emailperso" name="emailperso" type="email"
                                                   value="<?= $_SESSION["user"]["mailPersoUtil"] ?>"
                                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Numéro
                                            de téléphone portable</label>
                                        <div class="mt-2">
                                            <div
                                                    class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                                <span
                                                        class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">+33 (0)</span>
                                                <input type="text" name="phone" id="phone"
                                                       value="<?= $_SESSION["user"]["mobileUtil"] ?>"
                                                       class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                                       placeholder="7 00 00 00 00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="studentProfil" class="border-b border-gray-900/10 pb-12" hidden>
                                <h2 class="mt-3 text-base font-semibold leading-7 text-gray-900">Profil professeur</h2>
                                <p class="mt-1 text-sm leading-6 text-gray-600">On va pas se mentir, je sais pas quoi mettre pour l'instant</p>
                            </div>


                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                             <button type="submit"
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Sauvegarder
                            </button>
                        </div>
                    </form>

                <?php endif; ?>

        </main>
    </div>
    </body>

    <script>

        $(document).ready(function () {
            $("#createStudent").click(function () {
                $("#noprofile").hide();
                $("#studentProfil").show();
            });
        });

    </script>

<?php require "assets/php/footer.php" ?>