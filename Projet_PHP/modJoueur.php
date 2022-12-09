<?php

    $server = "localhost";
    $login = "root";
    $mdp = "";
    $db = "projet_php";
    
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $DateNaiss = $_POST['Date_de_naissance'];
    $Taille = $_POST['Taille'];
    $Poids = $_POST['Poids'];
    $Photo = $_POST['Photo'];
    $PostePref = $_POST['PostePref'];
    $Notes = $_POST['Notes'];
    $NumLicence = $_POST['NumLicence'];

    $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);

    $req = $bdco->prepare('UPDATE joueur SET Nom = :Nom, Prenom = :prenom, Photo = :Photo, Date_de_naissance = :Date_de_naissance, Taille = :taille, Poids = :poids, Poste_prefere = :PostePref, Notes = :Notes WHERE numero_de_licence = :numero_de_licence;');
    
    $req->bindParam(':Nom',$nom);
    $req->bindParam(':prenom',$prenom);
    $req->bindParam(':Date_de_naissance',$DateNaiss);
    $req->bindParam(':taille',$Taille);
    $req->bindParam(':poids',$Poids);
    $req->bindParam(':Photo',$Photo);
    $req->bindParam(':PostePref',$PostePref);
    $req->bindParam(':Notes',$Notes);
    $req->bindParam(':numero_de_licence', $NumLicence);

    $executionReq = $req->execute();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation</title>
</head>
<body>
    <h1>Modification enregistrée</h1>
    <a class="stylebouton" href="consultationjoueur.php">retour</a>
</body>
</html>