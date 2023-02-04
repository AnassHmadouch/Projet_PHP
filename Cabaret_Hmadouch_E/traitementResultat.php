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
    if (isset($_POST['numero_de_licence']) && isset($_POST['id_match']) && isset($_POST['evaluation_' . $_POST['numero_de_licence']])) {
        $numero_de_licence = intval($_POST['numero_de_licence']);
        $idMatch = intval($_POST['id_match']);
        $evaluation = intval($_POST['evaluation_' . $numero_de_licence]);
        $stmt = $bdco->prepare('UPDATE participer SET Evaluation = ? WHERE numero_de_licence = ? AND id_Match = ?');
        $stmt->execute([$evaluation, $numero_de_licence, $idMatch]);
        header("Location: EvaluationResultat.php?id=$idMatch");
    }
    else {
        echo 'Erreur : Évaluation non envoyé !';
    }
?>