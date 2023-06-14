<?php
require "assets/php/header.php";
loggedVerif();

if (!isEleve() && !isProf()) {
    header("Location: profil.php");
    die;
}



if (isEleve()) {
    $req = $db->prepare("SELECT * FROM stage,entreprise,statutstage WHERE idEleve = :id AND entreprise.idEntreprise = stage.idEntreprise AND statutstage.idStatutStage=stage.idStatutStage");
    $req->execute([
        "id" => $_SESSION["user"]["idUtil"]
    ]);
    $stages = $req->fetchAll();
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
                                        <a href=""
                                           class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium"
                                           aria-current="page">Dashboard</a>
                                        <?php if (isProf()){
                                            echo ' <a href="gestion-prof.php"
                                               class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Gestion Stage</a>';
                                        } else {
                                            echo ' <a href="profil.php"
                                                  class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Mon
                                            profil </a>';
                                        }
                                        ?>


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
                    <h1 class="text-3xl font-bold tracking-tight text-white">Dashboard</h1>
                </div>
            </header>
        </div>

        <main class="-mt-32">
            <div class="mx-auto max-w-7xl px-4 pb-12 pt-12 sm:px-6 lg:px-8 bg-white rounded-lg border-black">
                <?php if (count($stages) !== 0): ?>
                    <div class="overflow-hidden bg-white shadow-xl sm:rounded-md border-t-red-600 mb-12">
                        <ul role="list" class="divide-y divide-gray-200 border-black">
                            <?php foreach ($stages as $stage): ?>
                                <li>
                                    <a href="edit-fiche.php?id=<?= $stage["idStage"] ?>" class="block hover:bg-gray-50">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <p class="truncate text-sm font-medium text-indigo-600"><?= $stage["titreStage"] ?? "- Sans nom -" ?></p>
                                                <div class="ml-2 flex flex-shrink-0">
                                                    <?php if(!$stage["libStatutStage"]) ?>
                                                    <p class="inline-flex rounded-full bg-blue-300 px-2 text-xs font-semibold leading-5 text-blue-900">
                                                        <?=$stage["libStatutStage"] ?></p>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-gray-500">
                                                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                                             viewBox="0 0 20 20" fill="currentColor"
                                                             aria-hidden="true">
                                                            <path
                                                                d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.465 7.465 0 0114.5 16z"/>
                                                        </svg>
                                                        <?= $stage["nomEntreprise"] ?>
                                                    </p>
<!--                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:ml-6 sm:mt-0">-->
<!--                                                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"-->
<!--                                                             viewBox="0 0 20 20" fill="currentColor"-->
<!--                                                             aria-hidden="true">-->
<!--                                                            <path fill-rule="evenodd"-->
<!--                                                                  d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"-->
<!--                                                                  clip-rule="evenodd"/>-->
<!--                                                        </svg>-->
<!--                                                        Distanciel-->
<!--                                                    </p>-->
                                                </div>
                                                <?php if ($stage["dateDebutStage"] != null): ?>
                                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                    <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                              d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                    <p>
                                                        Commence le
                                                        <?= $stage["dateDebutStage"] ?>
                                                    </p>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                <?php endif; ?>

                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">Créer une fiche de stage</h3>
                    <p class="mt-1 text-sm text-gray-500">Si tu veux créer une fiche de stage, c'est avec le bouton
                        juste en dessous.</p>
                    <div class="mt-6">
                        <a type="button" href="create-fiche.php"
                           class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path
                                    d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"></path>
                            </svg>
                            Créer ma fiche de renseignements
                        </a>
                    </div>
                </div>

        </main>
    </div>
    </body>

<?php require "assets/php/footer.php" ?>