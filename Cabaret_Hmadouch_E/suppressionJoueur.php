<?php
    session_start();
    
    if(empty($_SESSION['logged_in'])){
        header("Location: index.html");
    }
    $server = "127.0.0.1";
    $login = "eccsbrkr_root";
    $mdp = "KF1jonb8_009";
    $db = "eccsbrkr_gestion_voley";
    
    $NumLincence = $_GET['numero_de_licence'];

    try {
        $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);
        $bdco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
    }

    $req = $bdco->prepare('DELETE FROM joueur WHERE numero_de_licence = :numero_de_licence');
    $req->bindParam(':numero_de_licence', $NumLincence);

    $executionReq = $req->execute();

    $temporisation = 2; 
    echo "Suppression réalisée avec succès, vous allez être redirigé vers la page de consultation des joueurs dans 2 secondes.";
    header("refresh:$temporisation;url=consultationJoueur.php");
?>
