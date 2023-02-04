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

    if (isset($_POST['modifier'])) {
        $id_Match = $_POST['id_Match'];
        $lieu_match = $_POST['lieu_match'];
        $date_match = $_POST['date_match'];
        $heure_match = $_POST['heure_match'];
        $equipe_adverse = $_POST['id_adversaire'];
        $domicile = isset($_POST['domicile']) ? 1 : 0;

        $stmt = $bdco->prepare("UPDATE matchs SET lieu_match = ?, date_match = ?, heure_match = ?, id_adversaire = ?, domicile = ? WHERE id_Match = ?");
        $stmt->execute([$lieu_match, $date_match, $heure_match, $id_adversaire, $domicile, $id_Match]);

        header("Location: listeMatchs.php");
    } else {
        $id_Match = $_GET['id_Match'];
        $stmt = $bdco->prepare("SELECT * FROM matchs WHERE id_Match = ?");
        $stmt->execute([$id_Match]);
        $match = $stmt->fetch();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
        <title>Modification d'un match</title>
		<link rel="stylesheet" href="css.css">
    </head>
    <body>
        <div class="pageModif">
            <form class ="form" method="POST" action="modMatch.php">
				<div class="title"> Match </div>
				<div class="subtitle">Modifiez un match</div>
				    <input type="hidden" id="id_Match" name="id_Match" value="<?php echo $match['id_Match']; ?>"/>
				<div class="input-container">
		    	    <input class="input" type="text" id="lieu_match" name="lieu_match" placeholder="Lieu du match" value="<?php echo $match['lieu_match']; ?>"/>
		    	</div>
		    	<div class="DateHeure">
			    	<div class="input-container">
			        	<input class="input" dateformat="DD/MM" type="Date" id="date_match" name="date_match" placeholder="Date" value="<?php echo $match['date_match']; ?>"/>
			    	</div>
			    	<div class="input-container">
			        	<input class="input" type="time" name="heure_match" id="heure_match" step="" value="<?php echo $match['heure_match']; ?>"/>
			    	</div>
			    </div>
			    <fieldset class="PostePref">
					<div class="sousTitre">Match à domicile</div>
					<div class="OuiNon">
						<div>
						    <input class="radio" type="radio" name="domicile" value="1"  <?php if ($match['domicile'] == '1'){ echo 'checked'; } ?> />
						    <label for="domicile">OUI</label>
						</div>
	           			<div>
	           			    <input class="radio" type="radio" name="domicile" value="0" <?php if ($match['domicile'] == '0'){ echo 'checked'; } ?> />
	           			    <label for="exterieur">NON</label>
	           			</div>
		           	</div>
		        </fieldset>
				<div class="selectdiv">
				    <select name="equipe">
				        <option hidden> <? $equipe['id_adversaire'] ?> </option>
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
		    		<input class="btnInscrire" type="submit" value="Modifier">
		    	</div>
		    	<div class="DeuxClasses">
			    	<div class="rechercher">
			    		<a class="stylebouton" href="javascript:history.back()">Annuler</a>
			    	</div>
			    	<div class="reinitialiser">
			    		<input class="vider" type=reset>
			    	</div>
			    </div>
			</form>
        </form>
    </body>
</html>