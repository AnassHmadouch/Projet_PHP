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
<!DOCTYPE HTML>
<html>
	<head>
	    <title>Joueurs</title>
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
		<link rel="stylesheet" href="css.css">
        <?php 
          include_once("Fonctions.php");
          echo menu();
        ?>
	</head>
	<body>
		<div class="PageListeJoueur">
			<div class="TitrePage">Liste des Joueurs </div>
			<form action="consultationJoueur.php" method="GET" >
				<div class="Filtre">
					<div class="selectdiv">
						<select name="Poste_prefere" id="Poste_prefere">
							<option value="">Tous les posts</option>
							<option value="Attaquant">Attaquant</option>
							<option value="Pointu">Pointu</option>
							<option value="Passeur">Passeur</option>
							<option value="Central">Central</option>
							<option value="Libero">Libero</option>
						</select>
					</div>
					<input class="btnFiltrer" type="submit" value="Filtrer">
				</div>
			</form>
			<button class="styleboutonAjoutJoueur"><a class=" ajoutJoueur" href="saisiejoueur.html">Ajout de Joueur</a></button>
			<ul>
				<?php
					$repertoire = "/projetPhotos/";
					if (isset($_GET["Poste_prefere"]) && !empty($_GET["Poste_prefere"])) {
						$Poste = $_GET["Poste_prefere"];
						$req = $bdco->prepare("SELECT * FROM joueur WHERE Poste_prefere = ?");
						$req->execute([$Poste]);
						echo '<table><thead><tr>
	            		    	<th>Nom</th>
	            				<th>Prénom</th>
	            				<th>Num licence</th>
	            				<th>Poste</th>
	            				<th>Statut</th>
	            				<th>Modifier</th>
	            				<th>Supprimer</th>
	            				<th>Afficher</th>
	            		  	</tr></thead>';
						while ($Joueur = $req->fetch()) {
							
						echo '<tbody><tr>';
							echo '<td>' . $Joueur['Nom'] . '</td>';
							echo '<td>' . $Joueur['Prenom'] . '</td>';
							echo '<td>' . $Joueur['numero_de_licence'] . '</td>';
							echo '<td>' . $Joueur['Poste_prefere'] . '</td>';
							echo '<td>' . $Joueur['Statut'] . '</td>';
							echo '<td><a href="modificationJoueur.php?numero_de_licence='.$Joueur["numero_de_licence"].'">
							            <img src="/projetPhotos/modifier.png" alt="" height="25" width="25">
							     </a></td>';
							echo "<td><a class=\"lien\" onClick=\" javascript:return confirm('Voulez-vous supprimer ce Joueur ?')\" href=\"suppressionJoueur.php?numero_de_licence=".$Joueur['numero_de_licence']."\"><img src=\"/projetPhotos/supprimer.png\" height=\"25\" width=\"25\"></a></td>";
							echo "<td><a class=\"lien\" href=\"affichageJoueur.php?numero_de_licence=".$Joueur['numero_de_licence']."\">Afficher</a></td>";
						}
						echo '</tr></tbody></table>';
					} else {
						$req = $bdco->prepare("SELECT * FROM joueur");
						$req->execute();
						echo '<table><thead><tr>
	            		    	<th>Nom</th>
	            				<th>Prénom</th>
	            				<th>Num licence</th>
	            				<th>Poste</th>
	            				<th>Date Naiss.</th>
	            				<th>Modifier</th>
	            				<th>Supprimer</th>
	            				<th>Afficher</th>
	            		  	</tr></thead>';
						while ($Joueur = $req->fetch()) {
							
							echo '<tbody><tr>';
							echo '<td>' . $Joueur['Nom'] . '</td>';
							echo '<td>' . $Joueur['Prenom'] . '</td>';
							echo '<td>' . $Joueur['numero_de_licence'] . '</td>';
							echo '<td>' . $Joueur['Poste_prefere'] . '</td>';
							echo '<td>' . $Joueur['Statut'] . '</td>';
							echo '<td><a href="modificationJoueur.php?numero_de_licence='.$Joueur["numero_de_licence"].'">
							            <img src="/projetPhotos/modifier.png" alt="" height="25" width="25">
							     </a></td>';
							echo "<td><a class=\"lien\" onClick=\" javascript:return confirm('Voulez-vous supprimer ce Joueur ?')\" href=\"suppressionJoueur.php?numero_de_licence=".$Joueur['numero_de_licence']."\"><img src=\"/projetPhotos/supprimer.png\" height=\"25\" width=\"25\"></a></td>";
							echo "<td><a class=\"lien\" href=\"affichageJoueur.php?numero_de_licence=".$Joueur['numero_de_licence']."\">Afficher</a></td>";
						}
						echo '</tr></tbody></table>';
					} 
				?>
			</ul>
		</div>
	</body>
</html>