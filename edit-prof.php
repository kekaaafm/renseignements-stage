<?php
require 'assets/php/header.php';

if (empty($_GET["id"])) {
    header("Location: gestion-prof.php");
    die;
}




$id = $_GET["id"];

$submit = isset($_POST['submit']);
$nomEleve = $_POST['nom'] ?? '';
$prenomEleve = $_POST['prenom'] ?? '';
$emailEleve = $_POST['email'] ?? '';
$statutFiche = $_POST['statut'] ?? '';
$anneeSco = $_POST['annee'] ?? '';
$nomEntreprise = $_POST['nomentreprise'] ?? '';
$numadresseEntreprise = $_POST['numadresse'] ?? '';
$adresseEntreprise = $_POST['adresse'] ?? '';
$villeadresseEntreprise = $_POST['villeadresse'] ?? '';
$nomtuteur = $_POST['nomtuteur'] ?? '';
$prenomtuteur = $_POST['prenomtuteur'] ?? '';
$telMobileEntreprise = $_POST['telentreprise'] ?? '';
$dateDebut = $_POST['datedebut'] ?? '';
$dateFin = $_POST['datefin'] ?? '';
$enseignantReferent = $_POST['enseignant'] ?? '';
$descriptionStage = $_POST['description'] ?? '';

if(isset($_GET['delete'])){
    $req = $db->prepare("DELETE FROM stage WHERE idStage=:id ");
    $req->execute([
        "id" => $_GET['id']
    ]);
    header("Location: gestion-prof.php");
}


if (!empty($_POST)){
//die(var_dump($enseignantReferent));

    $req = $db->prepare("UPDATE utilisateur u, eleve e,entreprise en,stage s,section sec, inscription ins, anneescolaire anesco, statutstage st, contact c
 SET u.nomUtil=:nomEleve, u.prenomUtil=:prenomEleve, u.mailUtil=:mailEleve, s.idStatutStage=:statut, s.idAnneeScolaire=:annee, 
     en.nomEntreprise=:nomEntreprise, en.numAdrEntreprise=:numAdrEntreprise,en.libAdrEntreprise=:adresseEntreprise,en.villeAdrEntreprise=:villeAdrEntreprise,c.nomContact=:nomtuteur,c.prenomContact=:prenomtuteur,c.telMobileContact=:telMobileTuteur,
     s.dateDebutStage=:dateDebut,s.dateFinStage=:dateFin,s.idEnseignant=:enseignant,s.descriptifStage=:description,s.activiteslStage=:description
    
WHERE u.idUtil=e.idEleve AND 
e.idEleve=s.idEleve AND 
en.idEntreprise=s.idEntreprise AND 
sec.idSection=ins.idSection AND 
e.idEleve=ins.idEleve AND 
ins.idAnneeScolaire=anesco.idAnneeScolaire AND
st.idStatutStage = s.idStatutStage AND 
c.idcontact=s.idcontact AND idStage=:idStage;");
    $req->execute([
        "nomEleve" => $nomEleve,
        "prenomEleve" => $prenomEleve,
        "mailEleve" => $emailEleve,
        "statut" => $statutFiche,
        "annee" => $anneeSco,
        "nomEntreprise" => $nomEntreprise,
        "numAdrEntreprise" => $numadresseEntreprise,
        "adresseEntreprise" => $adresseEntreprise,
        "villeAdrEntreprise" => $villeadresseEntreprise,
        "nomtuteur" => $nomtuteur,
        "prenomtuteur" => $prenomtuteur,
        "telMobileTuteur" => $telMobileEntreprise,
        "dateDebut" => $dateDebut,
        "dateFin" => $dateFin,
        "enseignant" => $enseignantReferent,
        "description" =>$descriptionStage,
        "idStage" => $id
    ]);
    header("Location: gestion-prof.php");
}

$req = $db->prepare("SELECT * FROM anneescolaire");
$req->execute([]);
$annee = $req->fetchAll();

$req = $db->prepare("SELECT * FROM statutstage");
$req->execute([]);
$statut = $req->fetchAll();

$req = $db->prepare("SELECT * FROM utilisateur u, enseignant e WHERE u.idUtil=e.idEnseignant");
$req->execute([]);
$prof = $req->fetchAll();


$req = $db->prepare("SELECT *
FROM utilisateur u, eleve e,entreprise en,stage s, section sec, statutstage st, contact c
WHERE u.idUtil=e.idEleve AND 
e.idEleve=s.idEleve AND 
en.idEntreprise=s.idEntreprise AND
st.idStatutStage = s.idStatutStage AND 
c.idcontact=s.idcontact AND idStage=:idStage;");
$req->execute([
    "idStage" => $id
]);
$eleve = $req->fetch();

//die(var_dump($eleve));


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
        </nav>
        <header class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Modification des fiches élèves</h1>
            </div>
        </header>
    </div>
    <main class="-mt-32">
        <div class="mx-auto max-w-7xl px-4 pb-12 pt-12 sm:px-6 lg:px-8 bg-white rounded-lg border-black bg-white">

            <div class="mx-auto max-w-4xl">
                <div class="sm:flex sm:items-center mb-5">
                    <div class="sm:flex-auto">
                        <form method="post">
                            <div class="space-y-12">
                                <div class="border-b border-gray-900/10 pb-12">
                                    <h2 class="text-base font-semibold leading-7 text-gray-900">Fiches élève</h2>
                                    <p class="mt-1 text-sm leading-6 text-gray-600">Voici les informations préremplies
                                        que l'élève a fait de l'entreprise</p>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <a href="gen-pdf.php?id=<?= $eleve["idStage"] ?>"><button type="button"  class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Générer le PDF</button></a>

                            </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">


                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="nom" class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                        <div class="mt-2">
                            <input type="text" name="nom" id="nom" autocomplete="given-name"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= strtoupper($eleve["nomUtil"]) ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="prenom" class="block text-sm font-medium leading-6 text-gray-900">Prenom</label>
                        <div class="mt-2">
                            <input type="text" name="prenom" id="prenom" autocomplete="family-name"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= ucfirst($eleve["prenomUtil"]) ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                            address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= strtolower($eleve["mailUtil"]) ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="statut" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                        <div class="mt-2">
                            <select id="statut" name="statut" autocomplete="country-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <?php
                                foreach ($statut as $statuts) {
                                    echo "<option value='".$statuts["idStatutStage"]."' ". (($eleve["idStatutStage"] === $statuts["idStatutStage"])?"selected":"") . ">" . $statuts["libStatutStage"] . "</option>";
                                }
                                ?>

                            </select>
                        </div>


                    </div>
                    <div class="sm:col-span-3">
                        <label for="annee" class="block text-sm font-medium leading-6 text-gray-900">année</label>
                        <div class="mt-2">
                            <select id="annee" name="annee" autocomplete="country-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <?php
                                foreach ($annee as $annees) {
                                    echo "<option value='".$annees["idAnneeScolaire"]."' ". (($eleve["idAnneeScolaire"] === $annees["idAnneeScolaire"])?"selected":"") .">" . $annees["libAnneeScolaire"] . "</option>";
                                }
                                ?>

                            </select>
                        </div>

                    </div>

                    <div class="col-span-full">
                        <label for="nomentreprise" class="block text-sm font-medium leading-6 text-gray-900">Nom
                            Entreprise</label>
                        <div class="mt-2">
                            <input type="text" name="nomentreprise" id="nomentreprise" autocomplete="nomentreprise"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= strtoupper($eleve["nomEntreprise"]) ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-2 sm:col-start-1">
                        <label for="adresse" class="block text-sm font-medium leading-6 text-gray-900">Numero de l'adresse de
                            l'entreprise</label>
                        <div class="mt-2">
                            <input type="text" name="numadresse" id="numadresse" autocomplete="adresse"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= $eleve["numAdrEntreprise"] ?>">
                        </div>
                    </div>
                    <div class="sm:col-span-2 sm:col-start-1">
                        <label for="adresse" class="block text-sm font-medium leading-6 text-gray-900">Adresse de
                            l'entreprise</label>
                        <div class="mt-2">
                            <input type="text" name="adresse" id="adresse" autocomplete="adresse"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= $eleve["libAdrEntreprise"]  ?>">
                        </div>
                    </div>
                    <div class="sm:col-span-2 sm:col-start-1">
                        <label for="adresse" class="block text-sm font-medium leading-6 text-gray-900">Ville de
                            l'entreprise</label>
                        <div class="mt-2">
                            <input type="text" name="villeadresse" id="villeadresse" autocomplete="adresse"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?=strtoupper($eleve["villeAdrEntreprise"]) ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="tuteur" class="block text-sm font-medium leading-6 text-gray-900">Nom Tuteur</label>
                        <div class="mt-2">
                            <input type="text" name="nomtuteur" id="nomtuteur" autocomplete="tuteur"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= strtoupper($eleve["nomContact"]) ?> ">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="tuteur" class="block text-sm font-medium leading-6 text-gray-900"> Prenom Tuteur</label>
                        <div class="mt-2">
                            <input type="text" name="prenomtuteur" id="prenomtuteur" autocomplete="tuteur"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= ucfirst($eleve["prenomContact"]) ?>">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="telentreprise" class="block text-sm font-medium leading-6 text-gray-900">telephone
                            mobile entreprise</label>
                        <div class="mt-2">
                            <input type="text" name="telentreprise" id="telentreprise" autocomplete="telentreprise"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                value="<?= $eleve["telMobileContact"] ?>" >
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="datedebut" class="block text-sm font-medium leading-6 text-gray-900">Date debut</label>
                        <div class="mt-2">
                            <input type="text" name="datedebut" id="datedebut" autocomplete="datedebut"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= $eleve["dateDebutStage"] ?>">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="datefin" class="block text-sm font-medium leading-6 text-gray-900">Date de
                            fin</label>
                        <div class="mt-2">
                            <input type="text" name="datefin" id="datefin" autocomplete="datefin"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= $eleve["dateFinStage"] ?>">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="enseignant" class="block text-sm font-medium leading-6 text-gray-900">Enseignant
                            référent</label>
                        <div class="mt-2">
                            <select id="enseignant" name="enseignant" autocomplete="enseignant"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <?php
                                foreach ($prof as $profs) {
                                    echo "<option value='".$profs["idEnseignant"]."' ". (($eleve["idEnseignant"] === $profs["idEnseignant"])?"selected":"") .">" . strtoupper($profs["nomUtil"]) . " " . ucfirst($profs["prenomUtil"]) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-full">
                        <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description
                            Stage</label>
                        <div class="mt-2">
                        <textarea id="description" name="description" rows="3"
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        ><?= ucfirst($eleve["descriptifStage"]) ?></textarea>
                        </div>
                    </div>
                </div>






                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="gestion-prof.php"><button type="button"  class="text-sm font-semibold leading-6 text-gray-900">Cancel</button></a>
                    <button type="submit" name="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Save
                    </button>
                </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="edit-prof.php?id=<?= $_GET["id"]?>&delete=1"
                   class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Supprimer
                </a>
        </div>

</div>
</form>
</div>
</div>
</div>
</body>