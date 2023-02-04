<?php
    # On inilialise les variables permettant l'identification de notre compte phpMyAdmin
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

    $req = $bdco->prepare("SELECT * FROM joueur WHERE numero_de_licence = :numero_de_licence");
    $req->bindParam(':numero_de_licence', $_GET['numero_de_licence'], PDO::PARAM_STR);

    $executionReq = $req->execute();

    $Joueur = $req->fetch();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
        <title>Modification de Joueur</title>
        <link rel="stylesheet" href="css.css">
    </head>
    <body>
        <div class="pageModif">
            <form class="form" action="modJoueur.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="NumLicence" value="<?= $Joueur['numero_de_licence']; ?>">
                <div class="title"> Modifier </div>
                <div class="subtitle"> Numéro de licence : <?= $Joueur['numero_de_licence']; ?> </div>
                <div class="input-container">
                    <input class="input" type="text" name="Nom" value= "<?= $Joueur['Nom']; ?>">
                </div>
                <div class="input-container">
                    <input class="input" type="text" name="Prenom" value="<?= $Joueur['Prenom']; ?>">
                </div>
                <div class="Image">
					Votre photo :
			    	<div class="button-wrap">
			        	<label class="buttonImage" for="upload">Télécharger </label>
			        	<input id="upload" type="file" name="Photo" value="<?= $Joueur['Photo']; ?>">
			      	</div>
			    </div>
                <div class="input-container">
		        	<input class="input" type="Date" name="Date_de_naissance"  value="<?= $Joueur['Date_de_naissance']; ?>"/>
		    	</div>
			    <div class="input-container">
		        	<input class="input" type="Number" name="Taille"  value="<?= $Joueur['Taille']; ?>"/>
		    	</div>
		    	<div class="input-container">
		        	<input class="input" type="Number" step="0.01" name="Poids" value="<?= $Joueur['Poids']; ?>"/>
	    		</div>
				<fieldset class="PostePref">
					<div class="sousTitre">Poste Préféré</div>
					<div class="ChoixPoste">
						<div class="DeuxPostes">
							<div>
							    <input type="radio" name="PostePref" value="Passeur" id="Passeur" <?php if ($Joueur['Poste_prefere'] == 'Passeur'){ echo 'checked'; } ?> />
							    <label for="PostePref">Passeur</label>
							</div>
		           			<div>
		           			    <input type="radio" name="PostePref" value="Libero" id="Libero"  <?php if ($Joueur['Poste_prefere'] == 'Libero'){ echo 'checked'; } ?> />
		           			    <label for="PostePref">Libero</label>
		           			</div>
		           		</div>
		           		<div class="DeuxPostes">
							<div>
							    <input type="radio" name="PostePref" value="Attaquant" id="Attaquant" <?php if ($Joueur['Poste_prefere'] == 'Attaquant'){ echo 'checked'; } ?> />
							    <label for="PostePref">Attaquant</label>
							</div>
							<div>
							    <input type="radio" name="PostePref" value="Central" id="Central" <?php if ($Joueur['Poste_prefere'] == 'Central'){ echo 'checked'; } ?>/>
							    <label for="PostePref">Central</label>
							</div>
	           			</div>
	           			<div class="DeuxPostes">
		           			<div>
		           			    <input type="radio" name="PostePref" value="Pointu" id="Pointu" <?php if ($Joueur['Poste_prefere'] == 'Pointu'){ echo 'checked'; } ?>/>
		           			    <label for="PostePref">Pointu</label>
		           			</div>
		           		</div>
	           		</div>
				</fieldset>
				<br/>
                <div class="sousTitre">Notes sur le joueur</div>
	  			<div class="Notes">
	   				<textarea name="Notes" id="Notes"><?= $Joueur['Notes']; ?></textarea>
	   			</div>
	   			<div class="selectdiv">
    	   			<select name="Statut" >
                        <option value="Actif">Actif</option>;
                        <option value="Blessé">Blessé</option>;
                        <option value="Suspendu">Suspendu</option>;
                        <option value="Absent">Absent</option>;
                    </select>
                </div>
	   			<div class="Inscrire">
		    		<input class="btnInscrire" type=submit value="Modifier">
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
        </div>
    </body>
</html>