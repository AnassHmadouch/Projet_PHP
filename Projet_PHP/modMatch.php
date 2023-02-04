<?php
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

    if (isset($_POST['id_Match'])) {
        $id_Match = intval($_POST['id_Match']);
        $lieu_match = $_POST['lieu_match'];
        $date_match = $_POST['date_match'];
        $heure_match = $_POST['heure_match'];
        $id_adversaire = intval($_POST['equipe']);
        $domicile = isset($_POST['domicile']) ? 1 : 0;

        $stmt = $bdco->prepare("UPDATE matchs SET lieu_match = ?, date_match = ?, heure_match = ?, id_adversaire = ?, domicile = ? WHERE id_Match = ?");
        $stmt->execute([$lieu_match, $date_match, $heure_match, $id_adversaire, $domicile, $id_Match]);

        header("Location: consultationMatch.php");
    } else {
        echo 'erreur de modification <br>';
        echo $_POST['id_Match'] . '<br>';
        echo $_POST['lieu_match'] . '<br>';
        echo $_POST['date_match'] . '<br>';
        echo $_POST['heure_match'] . '<br>';
        echo $_POST['equipe'] . '<br>';
        echo $_POST['domicile'];
    }
?>