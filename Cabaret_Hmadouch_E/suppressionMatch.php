<?php
    $server = "127.0.0.1";
    $login = "eccsbrkr_root";
    $mdp = "KF1jonb8_009";
    $db = "eccsbrkr_gestion_voley";
    
    $id_Match = $_GET['id_Match'];

    try {
        $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);
        $bdco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
    }

    $req = $bdco->prepare('DELETE FROM matchs WHERE id_Match = :id_Match');
    $req->bindParam(':id_Match', $id_Match);

    $executionReq = $req->execute();

    header('Location:consultationMatch.php');
?>
