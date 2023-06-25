<?php require "assets/php/header.php" ?>
<?php loggedVerif(false, "dashboard.php") ?>

<?php

if ((!isset($_POST["email"]) or
        !isset($_POST["password"]) or
        !isset($_POST["password_check"]) or
        !isset($_POST["last_name"]) or
        !isset($_POST["first_name"]) or
        !isset($_POST["titre"])) and isset($_POST["submit"])
) {
    createSessionError("notAllFieldsFilled");
} else if (isset($_POST["submit"])) {
    if ($_POST["password"] != $_POST["password_check"]) {
        createSessionError("notSamePasswords");
    }

    if (userExistWithEmailAddress($_POST["email"])) {
        createSessionError("emailAlreadyUsed");
    }

    if (countSessionErrors() === 0) {
        var_dump($_POST);
        $req = $db->prepare("INSERT INTO utilisateur (titreUtil, nomUtil, prenomUtil, mobileUtil, mailProUtil, mdpUtil) VALUES (:titre, :nom, :prenom, :mobile, :mail, :mdp)");
        $req->execute([
            "titre" => $_POST["titre"],
            "nom" => $_POST["last_name"],
            "prenom" => $_POST["first_name"],
            "mobile" => $_POST["phone_number"]??null,
            "mail" => $_POST["email"],
            "mdp" => password_hash($_POST["password"], PASSWORD_DEFAULT),
        ]);

        $req = $db->prepare("SELECT idUtil, titreUtil, nomUtil, prenomUtil, mobileUtil, mailProUtil, mailPersoUtil FROM utilisateur WHERE mailProUtil = :mail");
        $req->execute([
                "mail" => $_POST["email"]
        ]);
        $rep = $req->fetch();

        if(!$rep) {
            die("This error shouldn't happen but if it does, please contact me: marc.magueur@limayrac.fr");
        }

        $_SESSION["user"] = $rep;
        header("Location: profil.php");
        die("Everithing okay");
    }
}

?>


    <body class="h-full bg-gray-50">
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">S'inscrire</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="index.php" class="font-medium text-indigo-600 hover:text-indigo-500">se connecter</a>
            </p>
        </div>


        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
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
                            <h3 class="text-sm font-medium text-red-800">Oups, je pense qu'il y a une coquille quelque
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

            <div class="bg-white px-4 py-8 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="register.php" method="POST">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse
                            mail (Adresse mail limayrac)</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                   ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                   focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= $_POST["email"] ?? null ?>">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Mot de
                            passe</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password"
                                   required
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                   ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                   focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="password_check" class="block text-sm font-medium leading-6 text-gray-900">Répéter le
                            mot de
                            passe</label>
                        <div class="mt-2">
                            <input id="password_check" name="password_check" type="password"
                                   required
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                   ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                   focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center">
                                <label for="titre" class="sr-only">Country</label>
                                <select id="titre" name="titre" autocomplete="titre"
                                        class="h-full rounded-md border-0 bg-transparent py-0 pl-3 pr-7 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                    <option <?= (($_POST["titre"] ?? '') === "Mr") ? "selected" : "" ?> value="Mr">Mr
                                    </option>
                                    <option <?= (($_POST["titre"] ?? '') === "Mme") ? "selected" : "" ?> value="Mme">
                                        Mme
                                    </option>
                                    <option <?= (($_POST["titre"] ?? '') === "Mlle") ? "selected" : "" ?> value="Mlle">
                                        Mlle
                                    </option>
                                </select>
                            </div>
                            <input type="text" name="last_name" id="last_name" required
                                   value="<?= $_POST["last_name"] ?? null ?>"
                                   class="block w-full rounded-md border-0 py-1.5 pl-16 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="first_name" class="block text-sm font-medium leading-6 text-gray-900">Prénom</label>
                        <div class="mt-2">
                            <input id="first_name" name="first_name" type="text"
                                   required
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                   ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                   focus:ring-indigo-600 sm:text-sm sm:leading-6" value="<?= $_POST["first_name"]??null ?>">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between">
                            <label for="phone_number"
                                   class="block text-sm font-medium leading-6 text-gray-900">Portable</label>
                            <span class="text-sm leading-6 text-gray-500" id="email-optional">Optionnel</span>
                        </div>
                        <div class="mt-2">
                            <input type="text" name="phone_number" id="phone_number"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   aria-describedby="phone-optional" value="<?= $_POST["phone_number"]??null ?>">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm
                                font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline
                                focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                value="submit" name="submit">


                            Confirmer l'inscription
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    </body>


<?php require "assets/php/footer.php" ?>