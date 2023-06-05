<body>
<!--<style>-->
<!--    html {-->
<!--        height: 1123px;-->
<!--        width: 794px;-->
<!--    }-->
<!--</style>-->

<h1 style="font-size: 1rem; text-align:right">Fiche de renseignements stage</h1>

<h3 style="font-size: 0.6rem; text-align:center">Cette fiche est à remettre au professeur responsable des stage <strong>le plus tôt possible </strong></h3>
<h3 style="font-size: 0.6rem; text-align: center;text-decoration: underline;"><strong>Aucun départ en stage n'est autorisé sans convention de stage signée</strong></h3>


<h2 style="font-size: 0.8rem">L'ÉTUDIANT :</h2>
<div style="width: auto; max-width: 100%; border: 1px solid black;padding: 10px;">
    <div style="display: inline; justify-content: space-between;">
        <h4 style="display: inline;  white-space: nowrap; margin-right: 165px; padding-right: 66px;font-size: 0.6rem">Nom: <?= strtoupper($eleve["nomUtil"]) ?></h4>
        <h4 style="display: inline;  white-space: nowrap; margin-right: 145px; padding-right: 66px;font-size: 0.6rem">Prenom: <?= strtoupper($eleve["prenomUtil"]) ?></h4>
        <?php
//        var_dump($classe);
         echo "<h4 style='display: inline;  white-space: nowrap;font-size: 0.6rem'>   Classe: ". $eleve["nomCourtSection"] . "</h4>";
        ?>
    </div>
    <h4 style="font-size: 0.6rem" >Adresse de résidence <strong>pendant le stage </strong>: <?=$eleve["numAdrEleve"] ." ". $eleve["libAdrEleve"] ." ". strtoupper($eleve["villeAdrEleve"]) ?></h4>

        <div style="justify-content: space-between; display: flex">
            <h4 style="display: inline;  white-space: nowrap; margin-right: 100px;padding-right: 26px;font-size: 0.6rem">Date du stage du: <?= $eleve["dateDebutStage"] . " au " . $eleve["dateFinStage"] ?></h4>
            <h4 style="display: inline;  white-space: nowrap;margin-right: 80px;padding-right: 26px;font-size: 0.6rem">Téléphone: <?= $eleve["mobileUtil"] ?></h4>
            <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem">Durée de travail hebdomadaire : <?= $eleve["dureeHebdoStage"] ?></h4>
        </div>
</div>

<h2 style="font-size: 0.8rem">L'ORGANISME :</h2>
<div style="width: auto; max-width: 100%; border: 1px solid black;padding: 10px;">
    <h4 style="font-size: 0.6rem">Nom de l'Organisme <span style="text-decoration: underline">signataire de la convention :</span> <?= strtoupper($eleve["nomEntreprise"]) ?></h4>

        <h4 style="font-size: 0.6rem">Adresse : <?=$eleve["numAdrEntreprise"] ." ". $eleve["libAdrEntreprise"] ." ". strtoupper($eleve["villeAdrEntreprise"]) ?> </h4>
    <div style="justify-content: space-between; display: flex">
        <h4 style="display: inline;  white-space: nowrap; margin-right: 100px;padding-right: 69px;font-size: 0.6rem">Téléphone : <?=$eleve["telEntreprise"] ?></h4>
        <h4 style="display: inline;  white-space: nowrap; margin-right: 80px;padding-right: 69px;font-size: 0.6rem">Email : <?=$eleve["mailEntreprise"] ?></h4>
        <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem">N°Siret : <?=$eleve["siretEntreprise"] ?></h4>
    </div>
    <div style="justify-content: space-between; display: flex">
        <h4 style="font-size: 0.6rem">Mission de cet organisme : <?=$eleve["missionEntreprise"] ?></h4>
        <h4 style="display: inline;  white-space: nowrap; margin-right: 20px;padding-right: 35px;font-size: 0.6rem">Nom du responsable de cet Organisme : <?=$eleve["titreContact"] ." ". $eleve["nomContact"] ." ". $eleve["prenomContact"]?></h4>
        <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem" value="<?= $eleve["idFonction"] ?>">Fonction : <?=$eleve["libFonction"] ?></h4>
    </div>
</div>
<div style="justify-content: space-between; display: flex">
    <h4 style="display: inline;  white-space: nowrap; margin-right: 280px;padding-right: 80px;font-size: 0.6rem">Nom du tuteur de stage : <?=$eleve["titreContact"] ." ". $eleve["nomContact"] ." ". $eleve["prenomContact"]?></h4>
    <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem" value="<?= $eleve["idFonction"] ?>">Fonction : <?=$eleve["libFonction"] ?></h4>
</div>
    <div style="justify-content: space-between; display: flex">
    <h4 style="display: inline;  white-space: nowrap;margin-right: 340px;padding-right: 80px;font-size: 0.6rem">Téléphone : <?=$eleve["telMobileContact"] ?></h4>
    <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem">Email : <?=$eleve["mailContact"] ?></h4>
</div>
<h2 style="font-size: 0.8rem">LE LIEU DU STAGE <strong style="text-decoration: underline">(si différent de l'organisme signataire)</strong> :</h2>

<div style="width: auto; max-width: 100%; border: 1px solid black;padding: 10px;">
    <h4 style="font-size: 0.6rem">Nom de l'Organisme :  <?= strtoupper($eleve["nomEntreprise"]) ?></h4>
    <div style="display: inline; justify-content: space-between;">
        <h4 style="display: inline;  white-space: nowrap;margin-right: 340px;padding-right: 80px;font-size: 0.6rem">Adresse : <?=$eleve["lieuStage"] ?></h4>
        <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem">Télephone : <?=$eleve["mobileUtil"] ?></h4>
    </div>
    <div style="display: inline; justify-content: space-between;">
        <h4 style="display: inline;  white-space: nowrap;margin-right: 340px;padding-right: 80px;font-size: 0.6rem">CP,Ville : <?=$eleve["codePostalAdrEntreprise"] ." " . $eleve["villeAdrEntreprise"]?></h4>
        <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem">Fax :</h4>
    </div>
</div>
<h4>    </h4>
<div style="width: auto; max-width: 100%;height: 120px;max-height: 100%; border: 1px solid black;padding: 10px;">
    <h4 style="display: block; white-space: pre-wrap;overflow-wrap: break-word;font-size: 0.6rem;">Activités envisagées pour le stagiaire pendant le stage : <br><?=$eleve["descriptifStage"] ?></h4>
</div>
<h4>    </h4>
<div style="background-color:#C0C0C0;width: auto; max-width: 100%;height: 120px;max-height: 100%; border: 1px solid black;padding: 10px;">
    <div style="display: inline; justify-content: space-between;">
        <h4 style="display: inline;  white-space: nowrap;margin-right: 360px;padding-right: 80px;font-size: 0.6rem">Date :</h4>
        <h4 style="display: inline;white-space: nowrap;font-size: 0.6rem">Signature du professeur responsable :</h4>
</div>
    <h4 style="display: inline;  white-space: nowrap;font-size: 0.6rem" value="<?PHP $eleve["idEnseignant"] ?>">Nom de l'enseignant référent :<?= $eleve["nomUtil"] . " " . $eleve["prenomUtil"]?></h4>
</div>
<h3 style="margin-top: 50px;font-size: 0.6rem; text-align:center">50 rue de Limayrac - BP 45204, 31079 Toulouse Cedex 5 - Tél 05 61 36 08 08 - Accueil@limayrac.fr</h3>
</body>
