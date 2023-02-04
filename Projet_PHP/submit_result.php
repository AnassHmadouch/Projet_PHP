<?php
    session_start();
    
    if(empty($_SESSION['logged_in'])){
        header("Location: index.html");
    }
    
    $server = "127.0.0.1";
    $login = "eccsbrkr_root";
    $mdp = "KF1jonb8_009";
    $db = "eccsbrkr_gestion_voley";
    
    try {
        $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);
        $bdco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
    }
    
    if (isset($_POST['idMatch']) && isset($_POST['result'])){
        $id_Match = $_POST['idMatch'];
        $result = $_POST['result'];
        $stmt2 = $bdco->prepare("UPDATE matchs SET resultat = ? WHERE id_Match = ?");
        $stmt2->execute([$result, $id_Match]);
        header('Location: consultationMatch.php');
    } else{
        echo $_POST['result'] . '<br>';
        echo $_POST['idMatch'];
        echo 'ça marche pas...';
    }
?>
