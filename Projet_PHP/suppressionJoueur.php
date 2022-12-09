<?php

    $server = "localhost";
    $login = "root";
    $mdp = "";
    $db = "projet_php";
    
    $NumLincence = $_GET['numero_de_licence'];

    $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);

    $req = $bdco->prepare('DELETE FROM joueur WHERE numero_de_licence = :numero_de_licence');
    $req->bindParam(':numero_de_licence', $NumLincence);

    $executionReq = $req->execute();

    header('Location:consultationjoueur.php');
?>
