<?php


use Dompdf\Dompdf;
use Dompdf\Options;

require "assets/php/header.php";

$id = $_GET["id"];

$req = $db->prepare("SELECT * FROM anneescolaire");
$req->execute([]);
$annee = $req->fetchAll();

$req = $db->prepare("SELECT * FROM section");
$req->execute([]);
$classe = $req->fetchAll();

$req = $db->prepare("SELECT * FROM utilisateur u, enseignant e WHERE u.idUtil=e.idEnseignant");
$req->execute([]);
$professeur = $req->fetchAll();

$req = $db->prepare("SELECT u.*, e.*, s.*, en.*,  sec.*, ins.*, anesco.*, st.*, c.*, f.*, p.prenomUtil as prenomProf, p.nomUtil as nomProf
FROM utilisateur u, eleve e,entreprise en,stage s, section sec, inscription ins, anneescolaire anesco, statutstage st, contact c, fonction f, utilisateur p
WHERE u.idUtil=e.idEleve AND 
e.idEleve=s.idEleve AND 
en.idEntreprise=s.idEntreprise AND 
sec.idSection=ins.idSection AND 
e.idEleve=ins.idEleve AND 
ins.idAnneeScolaire=anesco.idAnneeScolaire AND
st.idStatutStage = s.idStatutStage AND 
c.idcontact=s.idcontact AND 
p.idUtil = s.idEnseignant AND
f.idfonction=c.idfonction AND idStage=:idStage;");
$req->execute([
    "idStage" => $id
]);
$eleve = $req->fetch();

//echo json_encode($eleve, JSON_PRETTY_PRINT);
//die;

ob_start();
include 'pdf-content.php';
$html = ob_get_contents();
ob_end_clean();
$curl = curl_init();

//echo $html;
//die;

curl_setopt_array($curl, [
    CURLOPT_URL => "http://api.mrkm.dev/PDF/create/HTML",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        "html" => $html,
        "filename" => "test"
    ]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-Token: 9Ysr35Pifbrkh8QNiMwBINPpJhRvj87Q"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; file=output.pdf");
    echo $response;
}
