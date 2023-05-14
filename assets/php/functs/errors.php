<?php
/**
 * @param string $code
 * @return void
 */
function createSessionError(string $code): void
{
    $_SESSION["errors"][] = $code;
}

/**
 * @return int
 */
function countSessionErrors(): int
{
    return count($_SESSION["errors"]);
}

/**
 * @param string $error
 * @return bool
 */
function isErrorInSesison(string $error): bool
{
    foreach ($_SESSION["errors"] as $e) {
        if ($e == $error) return true;
    }
    return false;
}

/**
 * Fonction qui balance les messages d'erreurs sous forme de string (html)
 * @param string $params
 * @return string
 * @throws Exception
 */
function showSessionErrors(string $params = "color: #de314d;", bool $autoClear = false): string
{
    if (count($_SESSION["errors"]) <= 0) {
        return "";
    }

    $message = "<div class='alert alert-error'>";

    for ($i = 0; $i < count($_SESSION["errors"]); $i++) {
        $error = $_SESSION["errors"][$i];
        if ($i != 0) {
            $message .= "<br>";
        }

        $message .= matchErrorMessage($error);
    }

    if ($autoClear) {
        $_SESSION["errors"] = [];
    }

    $message .= "</div>";
    return $message;
}

function arrayOfErrorsMessages(): array
{
    $msg = [];
    foreach ($_SESSION["errors"] as $error) {
        $msg[] = matchErrorMessage($error);
    }
    return $msg;
}

/**
 * @return void
 */
function clearErrorsMessages(): void
{
    $_SESSION["errors"] = [];
}

function matchErrorMessage(string $error): string
{
    return match ($error) {
        "SACantDoThat" => "Le super admin ne peut pas faire ca :c",
        "notYourQuestion" => "Cette question ne vous appartient pas, vous ne pouvez pas effectuer cette action :(",
        "questionNotFound" => "Cette question n'existe pas/plus",
        "notYourDomain" => "Cette question n'appartient pas à votre ligue",
        "emailAlreadyUsed" => "Cet email est déjà utilisé",
        "LoggedIn" => "Vous êtes déjà connectés, vous ne pouvez pas effectuer cette action",
        "notLoggedIn" => "Vous devez être connecté pour effectuer cette action",
        "notAllFieldsFilled" => "Tous les champs n'ont pas été remplis",
        "notSamePasswords" => "Les deux mots de passes ne sont pas identiques",
        "invalidUserType" => "Le type de compte que tu essaies de créer n'est pas valide :/",
        "invalidLigue" => "La ligue avec laquelle tu essaies d'associer ce compte n'existe pas :)",
        "invalidCreds" => "Les identifiants sont invalides",
        "insufficientPermissions" => "Permissions insuffisantes pour effectuer cette action",
        "invalidYear" => "L'année scolaire de rentrée entrée n'existe pas",
        default => throw new Exception($error),
    };
}