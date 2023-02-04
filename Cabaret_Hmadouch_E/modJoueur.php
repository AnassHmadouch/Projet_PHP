<!--Cette Page correspond à la page de traitement de la modification D'un Joueur-->
<?php
    // Variable d'identification à la BDD
    $server = "127.0.0.1";
    $login = "eccsbrkr_root";
    $mdp = "KF1jonb8_009";
    $db = "eccsbrkr_gestion_voley";
    
    if(isset($_FILES['Photo'])){
        $errors= array();
        $file_name = $_FILES['Photo']['name'];
        $file_size = $_FILES['Photo']['size'];
        $file_tmp = $_FILES['Photo']['tmp_name'];
        $file_type = $_FILES['Photo']['type'];
        $file_ext = strtolower(end(explode('.',$_FILES['Photo']['name'])));

        $extensions= array("jpeg","jpg","png");

        if(in_array($file_ext,$extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }

        if(empty($errors)==true) {
            move_uploaded_file($file_tmp,"projetPhotos/".$file_name);
            echo "Success";
        }else{
            print_r($errors);
        }
    }
    
    // Récupération des données dans des variables
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $DateNaiss = $_POST['Date_de_naissance'];
    $Taille = $_POST['Taille'];
    $Poids = $_POST['Poids'];
    $Photo = $_POST['Photo'];
    $PostePref = $_POST['PostePref'];
    $Notes = $_POST['Notes'];
    $NumLicence = $_POST['NumLicence'];
    $Statut = $_POST['Statut'];
    
    // Connexion à la BDD
    try {
        $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);
        $bdco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
    }
    
    // Requête pour la modification d'un Joueur avec comme paramètre les données récupérées lors de la saisie de l'utilisateur dans le page modificationJoueur.php
    $req = $bdco->prepare('UPDATE joueur SET Nom = :Nom, Prenom = :prenom, Photo = :Photo, Date_de_naissance = :Date_de_naissance, Taille = :taille, Poids = :poids, Poste_prefere = :PostePref, Notes = :Notes, Statut = :Statut WHERE numero_de_licence = :numero_de_licence;');
    
    // Attribution des variables pour chaques paramètres 
    $req->bindParam(':Nom',$nom);
    $req->bindParam(':prenom',$prenom);
    $req->bindParam(':Date_de_naissance',$DateNaiss);
    $req->bindParam(':taille',$Taille);
    $req->bindParam(':poids',$Poids);
    $req->bindParam(':Photo',$file_name);
    $req->bindParam(':PostePref',$PostePref);
    $req->bindParam(':Notes',$Notes);
    $req->bindParam(':numero_de_licence', $NumLicence);
    $req->bindParam(':Statut', $Statut);
    
    // Execution de la requete 
    $executionReq = $req->execute();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Confirmation</title>
    </head>
    <body>
        <h1>Modification enregistrée</h1>
        <?php
            // Mise en place d'une temporaisation et d'une redirection à la fin
            $temp= 2;
            echo "Modification réalisée avec succès, vous allez être redirigé vers la page de consultation des joueurs dans 2 secondes.";
            header("refresh:$temp;url=consultationJoueur.php");
        ?>
    </body>
</html>