<?php
	// On inilialise les variables permettant l'identification de notre compte phpMyAdmin
    $server = "127.0.0.1";
    $login = "eccsbrkr_root";
    $mdp = "KF1jonb8_009";
    $db = "eccsbrkr_gestion_voley";
    
    
    
    // Upload d'une image
    if(isset($_FILES['Photo'])){
        $errors= array();
        $file_name = $_FILES['Photo']['name'];
        $file_size = $_FILES['Photo']['size'];
        $file_tmp = $_FILES['Photo']['tmp_name'];
        $file_type = $_FILES['Photo']['type'];
        $file_ext = strtolower(end(explode('.',$_FILES['Photo']['name'])));

        $extensions= array("jpeg","jpg","png");

        if(in_array($file_ext,$extensions)=== false){
            $errors[]="Format invalide";
        }

        if(empty($errors)==true) {
            move_uploaded_file($file_tmp,"projetPhotos/".$file_name);
            echo "Success";
        }else{
            print_r($errors);
        }
    }
    # ici, on récupère les données entrées par l'utilisateur
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $Photo = $_POST["Photo"];
    $DateNaiss = $_POST["DatedeNaissance"];
    $Taille = $_POST["Taille"];
    $Poids = $_POST["Poids"];
    $PostePref = $_POST["PostePref"];
    $Notes = $_POST["Notes"];


        
    // rand(0,99999999);

    //  On accède à nos données sur notre compte phpMyAdmin, grâce aux méthodes de PDO (qui permet l'accès à la BDD)
    try{ $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);
        //  Instancie la connexion (rapporte les erreurs sous forme d'exceptions)
        $bdco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
        
        
        $sth = $bdco->prepare("INSERT INTO joueur(Nom, Prenom, Photo, Date_de_naissance, Taille, Poids, Poste_prefere, Notes) VALUES(:nom, :prenom, :Photo, :DDT, :Taille, :Poids, :PostePref, :Notes)");
        $sth->bindParam(':nom',$nom);
        $sth->bindParam(':prenom',$prenom);
        $sth->bindParam(':Photo',$file_name);
        $sth->bindParam(':DDT',$DateNaiss);
        $sth->bindParam(':Taille',$Taille);
        $sth->bindParam(':Poids',$Poids);
        $sth->bindParam(':PostePref',$PostePref);
        $sth->bindParam(':Notes',$Notes);
        
        //  éxecution de la requête
        $sth->execute();
        
        $temporisation = 2; 
        echo "Ajout de joueur réalisé avec succès, vous allez être redirigé vers la page de consultation des joueurs dans 2 secondes.";
        //  redirection vers la page de consultation des joueurs
        header("Location: consultationJoueur.php");
	}
    catch(Exception $e){
        echo 'Erreur : '.$e->getMessage();
    }


?>
