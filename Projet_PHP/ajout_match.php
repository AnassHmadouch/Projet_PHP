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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Ajout d'un match</title>
		<link rel="stylesheet" href="css.css">
	</head>
	<body>
		<div class="pageMatch">
			<form class ="form" method="POST" action="traitementNouveauMatch.php">
				<div class="title"> Match </div>
				<div class="subtitle">Enregistrez un nouveau match</div>
				<div class="input-container">
		    	    <input class="input" type="text" id="lieu" name="lieu" placeholder="Lieu du match" required/>
		    	</div>
		    	<div class="DateHeure">
			    	<div class="input-container">
			        	<input class="input" type="Date" id="date" name="date"placeholder="Date" min=CURRENT_DATE required/>
			    	</div>
			    	<div class="input-container">
			        	<input class="input" type="time" name="horaire" id="horaire" required/>
			    	</div>
			    </div>
			    <fieldset class="PostePref" required>
					<div class="sousTitre">Match à domicile</div>
					<div class="OuiNon">
						<div><input class="radio" type="radio" name="domicile" value="1"/> <label class="labelRadio">OUI</label></div>
	           			<div><input class="radio" type="radio" name="domicile" value="0"/> <label class="labelRadio">NON</label></div>
		           	</div>
		        </fieldset>
				<div class="selectdiv">
				    <select name="equipe">
				        <option value=''> Equipe adverse </option>
				        <?php
						    $stmt = $bdco->prepare('SELECT * FROM equipe_adverse');
						    $stmt->execute();
						    while ($equipe = $stmt->fetch()) {
						    	echo '<option value="' . $equipe['id_adversaire'] . '">' . $equipe['nom_adversaire'] . '</option>';
						    }
						?>
				    </select>
				</div>
				<div class="Inscrire">
		    		<input class="btnInscrire" type=submit value="Ajouter">
		    	</div>
		    	<div class="DeuxClasses">
			    	<div class="rechercher">
			    		<a class="stylebouton" href="consultationMatch.php">Annuler</a>
			    	</div>
			    	<div class="reinitialiser">
			    		<input class="vider" type=reset>
			    	</div>
			    </div>
			</form>
		</div>
	</body>
</html>