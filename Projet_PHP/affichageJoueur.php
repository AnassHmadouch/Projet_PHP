<!--Page d'affichage du Joueur sélectionné dans la page consultationJoueur.php-->
<?php
    session_start();
    
    if(empty($_SESSION['logged_in'])){
        header("Location: index.html");
    }
    // Variables d'indenfication à la BDD
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
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Informations joueur</title>
		<link rel="stylesheet" href="css.css">
	</head>
    <body>
        <?php 
            $repertoire = "/projetPhotos/";  // Variable contenant le chemin relatif vers le répertoire photo 
            if (isset($_GET["numero_de_licence"])) { // Vérification de récupération du numéro de licence du joueurs
                $numero_de_licence = $_GET["numero_de_licence"];
                $req = $bdco->prepare("SELECT * FROM joueur WHERE numero_de_licence = ?");
                $req->execute([$numero_de_licence]);
                while ($Joueur = $req->fetch()) {
                    echo '<div class="pageAffichage">
			                <form class ="form" method="POST" action="affichageJoueur.php">
		                    <div class="DateHeure">
		                        <div class="title"> Joueur </div>
		                        <div class="statut"> ' . $Joueur['Statut'] . '</div>
		                    </div>
				            <div class="DeuxClasses">
				                <div class="informations">
    				                <div class="subtitle"> '. $Joueur["Nom"] . ' ' . $Joueur["Prenom"] . '</div>
    				                <div class="subtitle"> N° Licence : '. $Joueur["numero_de_licence"] . '</div>
    				                <div class="subtitle"> Poste : '. $Joueur['Poste_prefere']. '</div>
    				                <div class="subtitle"> Taille : '. $Joueur['Taille'] . ' cm </div>
    				                <div class="subtitle"> Poids : '. $Joueur['Poids'] . ' Kg </div>
    				                <div class="subtitle"> Notes : '.$Joueur['Notes'] . '</div>
    				            </div>
    				            <img src="' . $repertoire.$Joueur['Photo'] . '" alt="" height="200" width="200">'; // Affichage de l'image du Joueur avec une concaténation du chemin relatif et du nom de la photo (issue de la BDD)
				    echo    ' </div><br>
        			    	<div class="DeuxClasses">
        			    		<a class="stylebouton" href="javascript:history.back()">Retour</a>
        			    		<a class="stylebouton" href="modificationJoueur.php?numero_de_licence='.$Joueur["numero_de_licence"].'">Modifier</a>
            			    </div>';
                    echo '</form></div>';
                }
            } else {
                // Si le numéro de licence n'a pas été reçu, un mesage d'erreur s'aaffiche
                echo "Erreur d'affichage du Joueur";
            }
        ?>
    </body>
</html>