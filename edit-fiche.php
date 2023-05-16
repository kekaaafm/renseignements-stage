<?php require "assets/php/header.php";
loggedVerif();
if (!isEleve()) {
    header("Location: dashboard.php");
    die;
}

if (empty($_GET["id"])) {
    header("Location: dashboard.php");
}

$req = $db->prepare("SELECT * FROM stage WHERE idstage = :id");
$req->execute([
        "id" => $_GET['id']
]);
$stage = $req->fetch();
$student = fetchEleveData();
$entreprise =fetchEntrepriseData($stage["idEntreprise"]);
$section = fetchclasse($student["idEleve"]);
$responsable = fetchEntrepriseResponsable($entreprise["idEntreprise"]);
$contact = fetchContactData($responsable["idContact"]);


if(isset($_GET['delete'])){
    $req = $db->prepare("DELETE FROM stage WHERE idStage=:id ");
    $req->execute([
        "id" => $_GET['id']
    ]);
    header("Location: dashboard.php");
}
//var_dump($step);

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
                                               value="<?= $_SESSION["user"]["nomUtil"] ?>">
                                    </div>
                                </div>

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
                                               value="<?= $entreprise["nomEntreprise"] ?>">
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
                                               value="<?= $entreprise["telephoneEntreprise"] ?>">
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
                                               value="<?= strtoupper($responsable["nomContact"]) . " " . $responsable["prenomContact"] ?>"
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
                                               value="<?= strtoupper($contact["nomContact"]) . " " . $contact["prenomContact"] ?>"
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
                                               value="<?= $contact["mobileContact"] ?>" disabled
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
                                               value="<?=  $entreprise["numAdrEntreprise"] . " " . $entreprise["libAdrEntreprise"] ?>" disabled
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
                        <a href="edit-fiche.php?id=<?= $_GET["id"]?>&delete=1"
                           class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            Supprimer
                        </a>
                        <a href="dashboard.php"
                           class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Retour
                        </a>
                    </div>
                </div>

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

<?php

function showStep(string $type, string $id, string $name, bool $end)
{


    $html = "";
    if ($type === "current") {
        $html = "<li class=\"relative md:flex md:flex-1\">
                            <!-- Current Step -->
                            <a href=\"#\" class=\"flex items-center px-6 py-4 text-sm font-medium\" aria-current=\"step\">
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
                            <a href=\"#\" class=\"group flex items-center\">
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
      <a href=\"#\" class=\"group flex w-full items-center\">
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