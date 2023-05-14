<?php
require "assets/php/db.php";

$entr = $_GET["entr"]??"";

$req = $db->prepare("SELECT * FROM entreprise WHERE nomEntreprise LIKE :entr");
$req->execute([
    "entr" => $entr . "%"
]);
die(json_encode($req->fetchAll()));

