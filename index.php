<?php require "assets/php/header.php" ?>
<?php loggedVerif(false, "dashboard.php") ?>

<?php

    if($_POST) {
        if (empty($_POST["email"]) or empty($_POST["password"])) {
            createSessionError("notAllFieldsFilled");
            header("Location: index.php");
            die;
        }

//        var_dump($_POST);
        $req = $db->prepare("SELECT * FROM utilisateur WHERE mailUtil = :mail");
        $req->execute([
            "mail" => $_POST["email"]
        ]);
        $rep = $req->fetch();

        if (!$rep) {
            createSessionError("invalidCreds");
            header("Location: index.php");
            die;
        }

        if (password_verify($_POST["password"], $rep["mdpUtil"])) {
            $req = $db->prepare("SELECT idUtil, titreUtil, nomUtil, prenomUtil, mobileUtil, mailPersoUtil, mailUtil FROM utilisateur WHERE mailUtil = :mail");
            $req->execute([
                "mail" => $_POST["email"]
            ]);
            $rep = $req->fetch();

            if(!$rep) {
                die("This error shouldn't happen but if it does, please contact me: marc.magueur@limayrac.fr");
            }

            $_SESSION["user"] = $rep;
            if($_SESSION["user"] == isProf()){
                header("Location: gestion-prof.php");
                die("Everithing okay");
            }else{
                header("Location: dashboard.php");
                die("Everithing okay");
            }

        }
        createSessionError("invalidCreds");
    }

?>

    <body class="h-full bg-gray-50">
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Se connecter</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500">créer un compte</a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white px-4 py-8 shadow sm:rounded-lg sm:px-10">
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
                                <h3 class="text-sm font-medium text-red-800">Oups, je pense qu'il y a une coquille :(</h3>
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

                <form class="space-y-6" method="POST">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse
                            mail</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required value="<?= $_POST["email"]??"" ?>"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                   ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                   focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Mot de
                            passe</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                   required
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1
                                   ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                   focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <!--                        <div class="flex items-center">-->
                        <!--                            <input id="remember-me" name="remember-me" type="checkbox"-->
                        <!--                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">-->
                        <!--                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Se souvenir de moi</label>-->
                        <!--                        </div>-->
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Mot de passe
                                oublié</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm
                                font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline
                                focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">


                            Se connecter
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    </body>


<?php require "assets/php/footer.php" ?>