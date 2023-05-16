<?php
session_start();

require "functs/errors.php";

if (!isset($_SESSION["errors"])) {
    $_SESSION["errors"] = [];
}

$dsn = 'mysql:host=localhost;dbname=renseignements-stage'; // contient le nom du serveur et de la bdd
$user = 'root';
$password = '';
try {
    $db = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND =>
        "SET NAMES utf8"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    die("Erreur lors de la connexion SQL : " . $ex->getMessage());
}

/**
 * Fonction qui permet de savoir si un utilisateur est connecté
 * @param bool $shouldBeLoggedIn
 * @return bool|void
 */
function loggedVerif(bool $shouldBeLoggedIn = true, string $redirection = "/")
{
    if ($shouldBeLoggedIn and !$_SESSION["user"]) {
        $_SESSION["errors"][] = "notLoggedIn";
        header("Location: $redirection");
        die("Not logged in :/");
    }
    if (!$shouldBeLoggedIn and isset($_SESSION["user"])) {
        $_SESSION["errors"][] = "LoggedIn";
        header("Location: $redirection");
        die("Logged in where you shouldn't :/");
    }
    return true;
}

function userExistWithEmailAddress(string $email): bool|array
{
    global $db;
    $req = $db->prepare("SELECT * FROM utilisateur WHERE mailUtil = :email");
    $req->execute([
        "email" => $email
    ]);
    $rep = $req->fetch();

    return (bool)$rep;
}

function isEleve(): bool
{
    return (bool)fetchEleveData();
}

function fetchEleveData(): array|bool
{
    global $db;
    $req = $db->prepare("SELECT * FROM eleve WHERE idEleve = :idEleve");
    $req->execute([
        "idEleve" => $_SESSION["user"]["idUtil"]
    ]);
    return $req->fetch();
}

function isProf(): bool
{
    return (bool)fetchProfData();
}

function fetchProfData(): array|bool
{
    global $db;
    $req = $db->prepare("SELECT * FROM enseignant WHERE idEnseignant = :id");
    $req->execute([
        "id" => $_SESSION["user"]["idUtil"]
    ]);
    return $req->fetch();
}

function fetchEntrepriseData(string $id): array|bool
{
    global $db;
    $req = $db->prepare("SELECT * FROM entreprise WHERE idEntreprise = :idE");
    $req->execute([
        "idE" => $id
    ]);
    return $req->fetch();
}

function fetchContactData(string $id): array|bool
{
    global $db;
    $req = $db->prepare("SELECT * FROM contact, fonction WHERE contact.idFonction = fonction.idFonction AND idContact = :idC");
    $req->execute([
        "idC" => $id
    ]);
    return $req->fetch();
}

function fetchEntrepriseResponsable(string $id): array|bool
{
    global $db;
    $req = $db->prepare("SELECT * FROM contact, fonction WHERE contact.idFonction = fonction.idFonction AND idEntreprise = :idC AND isRespContact = 1");
    $req->execute([
        "idC" => $id
    ]);
    return $req->fetch();
}

function fetchclasse(string $idElv): array|bool
{
    global $db;
    $req = $db->prepare("SELECT s.idSection, s.nomCourtSection, s.nomLongSection FROM section as s, eleve as e, inscription as i WHERE e.idEleve = i.idEleve AND i.idSection = s.idSection AND e.idEleve = :id");
    $req->execute([
        "id" => $idElv
    ]);
    return $req->fetch();
}

