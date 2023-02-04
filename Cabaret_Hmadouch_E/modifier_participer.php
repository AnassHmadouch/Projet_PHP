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
  if (isset($_POST['id_Match']) && isset($_POST['joueurs'])) {
    $idMatch = intval($_POST['id_Match']);
    $listeJoueurs = $_POST['joueurs'];
    $stmt1 = $bdco->prepare('DELETE FROM participer WHERE id_Match = ?');
    $stmt1->execute([$idMatch]);
    foreach ($listeJoueurs as $numLicence) {
      $Statut = $_POST['statut_' . $numLicence . ''];
      $stmt = $bdco->prepare('INSERT INTO participer (numero_de_licence, id_Match, Statut) VALUES (?, ?, ?)');
      $stmt->execute([$numLicence, $idMatch, $Statut]);
    }
    $temporisation = 2; 
    echo "Sélection réalisée avec succès, vous allez être redirigé dans 2 secondes.";
    header("refresh:$temporisation;url=consultationMatch.php");
  }
  else {
    var_dump($_POST['id_Match']);
    var_dump($_POST['joueurs']);
    echo 'Erreur : formulaire non envoyé !';
  }
?>

