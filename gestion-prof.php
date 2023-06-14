<?php
require 'assets/php/header.php';
loggedVerif(isProf());
//if (!empty($_POST)){
//
//var_dump($_POST);
////die;
//}


$submit = isset($_POST['submit']);
$idSection = $_POST['nomsection'] ?? 1;
$idAnneeScolaire = $_POST['libannee'] ?? 1;

$req = $db->prepare("SELECT * FROM utilisateur u, enseignant e WHERE u.idUtil=e.idEnseignant");
$req->execute([]);
$prof = $req->fetchAll();
//die(var_dump($prof));

$req = $db->prepare("SELECT * FROM anneescolaire");
$req->execute([]);
$annee = $req->fetchAll();

$req = $db->prepare("SELECT * FROM section");
$req->execute([]);
$classe = $req->fetchAll();


$req = $db->prepare("SELECT u.prenomUtil, u.nomUtil, st.libStatutStage, en.nomEntreprise, p.nomUtil as nomProf, p.prenomUtil as prenomProf, s.idStage
FROM utilisateur u, eleve e,entreprise en,stage s, section sec, inscription ins, anneescolaire anesco, statutstage st, utilisateur p
WHERE u.idUtil=e.idEleve AND 
e.idEleve=s.idEleve AND 
en.idEntreprise=s.idEntreprise AND 
sec.idSection=ins.idSection AND 
e.idEleve=ins.idEleve AND 
ins.idAnneeScolaire=anesco.idAnneeScolaire AND
st.idStatutStage = s.idStatutStage AND sec.idSection = :nomsection AND s.idAnneeScolaire = :libannee AND p.idUtil = s.idEnseignant;");
$req->execute([
        "nomsection" => $idSection,
        "libannee" => $idAnneeScolaire
]);
$eleve = $req->fetchAll();

//var_dump($eleve);


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
                    <h1 class="text-3xl font-bold tracking-tight text-white">Liste des élèves</h1>
                </div>
            </header>
        </div>

        <main class="-mt-32">
            <div class="mx-auto max-w-7xl px-4 pb-12 pt-12 sm:px-6 lg:px-8 bg-white rounded-lg border-black bg-white">

                <div class="mx-auto max-w-4xl">
                    <div class="sm:flex sm:items-center mb-5">
                        <div class="sm:flex-auto">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">Liste eleve</h1>
                            <p class="mt-2 text-sm text-gray-700">Liste des éleves avec le nom de l'entreprise de leurs stage et le
                                status de leurs fiche de renseignement</p>
                        </div>
                    </div>

                    <div>
                        <div class="block">
                            <label for="location" class="text-sm font-medium leading-6 text-gray-900">Classe</label>
                            <label for="paslocation" class="text-sm pl-11 font-medium leading-6 text-gray-900">Annee Scolaire</label>
                        </div>
                        <form action="gestion-prof.php" method="post">
                            <select id="nomsection" name="nomsection"
                                      class="mt-2 rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <?php foreach ($classe as $classes): ?>
                                    <option value="<?= $classes["idSection"] ?>" <?= $idSection==$classes["idSection"]?"selected":"" ?>><?= $classes["nomCourtSection"] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="libannee" name="libannee"
                                    class="mt-2 rounded-md border-0 py-1.5 pl-4 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <?php foreach ($annee as $annees): ?>
                                    <option value="<?= $annees["idAnneeScolaire"] ?>" <?= $idAnneeScolaire==$annees["idAnneeScolaire"]?"selected":"" ?>><?= $annees["libAnneeScolaire"] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Rechercher
                                <svg class="-mr-0.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                    </div>

                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle">
                                <table class="min-w-full border-separate border-spacing-0">
                                    <thead>
                                    <tr>
                                        <th scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">
                                            Nom Prenom
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell">
                                            Entreprise
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter lg:table-cell">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter">
                                            Enseignant référent
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter sm:pr-6 lg:pr-8">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($eleve as $eleves): ?>
<!--                                        --><?php //die(var_dump($eleves)); ?>
                                        <tr>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"><?= $eleves['nomUtil'] ?> <?= $eleves['prenomUtil'] ?></td>
                                            <td class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-gray-500 sm:table-cell"><?= $eleves['nomEntreprise'] ?></td>
                                            <td class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-gray-500 lg:table-cell"><?= $eleves['libStatutStage'] ?></td>
                                            <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500"><?= $eleves['prenomProf'] . " " . $eleves["nomProf"] ?></td>
                                            <td class="relative whitespace-nowrap border-b border-gray-200 py-4 pr-4 pl-3 text-right text-sm font-medium sm:pr-8 lg:pr-8">
                                                <a href="edit-prof.php?id=<?= $eleves["idStage"] ?>" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only"></span></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <!-- More people... -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

        </main>
    </div>
    </body>


<?php require "assets/php/footer.php" ?>