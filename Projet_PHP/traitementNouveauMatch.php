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
?>
<?php
    if (empty($_POST['lieu']) || empty($_POST['date']) || empty($_POST['equipe']) || empty($_POST['horaire'])) {
        $temporisation = 2;
        echo 'Erreur : Tous les champs doivent être remplis';
        header("refresh:$temporisation;url=ajout_match.php");
        exit;
    } else {
        $lieu = $_POST['lieu'];
        $date = $_POST['date'];
        $idEquipe = intval($_POST['equipe']);
        $horaire = $_POST['horaire'];
        $domicile = intval($_POST['domicile']);
        $stmt = $bdco->prepare('INSERT INTO matchs (lieu_match, date_match, id_adversaire, heure_match, domicile) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$lieu, $date, $idEquipe, $horaire, $domicile]);
        $temporisation = 2;
        echo "Match ajouté avec succès, vous allez être redirigé vers la page de feuille de Match dans 2 secondes.";
        header("refresh:$temporisation;url=feuilleDeMatch.php");
    }
?>