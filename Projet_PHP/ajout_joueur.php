<!DOCTYPE HTML>
<html>
	<head>
		<title>Ajout de Contact</title>
	</head>
	<body>
		<h1>Carnet d'adresses</h1>
		<?php
			# On inilialise les variables permettant l'identification de notre compte phpMyAdmin
		    $server = "localhost";
		    $login = "root";
		    $mdp = "";
		    $db = "projet_php";

		    # ici, on récupère les données entrées par l'utilisateur
		    $nom = $_POST["nom"];
		    $prenom = $_POST["prenom"];
		    $DateNaiss = $_POST["DatedeNaissance"];
		    $Taille = $_POST["Taille"];
		    $Poids = $_POST["Poids"];
		    $PostePref = $_POST["PostePref"];
		    $Notes = $_POST["Notes"];

		    $Photo = $_POST['Photo'];

		        
		    #rand(0,99999999);

		    # On accède à nos données sur notre compte phpMyAdmin, grâce aux méthodes de PDO (qui permet l'accès à la BDD)
		    try{ $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);
		    # Instancie la connexion (rapporte les erreurs sous forme d'exceptions)
		        $bdco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
		    

		        $sth = $bdco->prepare("INSERT INTO joueur(Nom, Prenom, Photo, Date_de_naissance, Taille, Poids, Poste_prefere, Notes)
            		VALUES(:nom, :prenom, :Photo, :DDT, :Taille, :Poids, :PostePref, :Notes)");
		        $sth->bindParam(':nom',$nom);
		        $sth->bindParam(':prenom',$prenom);
		        $sth->bindParam(':Photo',$Photo);
		        $sth->bindParam(':DDT',$DateNaiss);
		        $sth->bindParam(':Taille',$Taille);
		        $sth->bindParam(':Poids',$Poids);
		        $sth->bindParam(':PostePref',$PostePref);
		        $sth->bindParam(':Notes',$Notes);

		        # éxecution de la requête
		        $sth->execute();

		        # redirection vers une page html de remerciement
		       header("Location:form-merci-joueur.html");
        	}
		    catch(Exception $e){
		        echo 'Erreur : '.$e->getMessage();
		    }

		?>
	</body>
</html>