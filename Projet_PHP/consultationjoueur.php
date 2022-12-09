<?php
    $server = "localhost";
    $login = "root";
    $mdp = "";
    $db = "projet_php";

    $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);

    $req = $bdco->prepare("SELECT * FROM joueur");

    $execReq = $req->execute();

    $Joueurs = $req->fetchAll();

    chmod("C:/wamp64/projet-photos", 0755);
    $repertoire = "projet-photos/";
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Recherche</title>
	</head>
	<body>
		<h1>Liste des Joueurs</h1>
		<ul>
			<?php foreach ($Joueurs as $Joueur):

				echo "<a href=\"modificationJoueur.php?numero_de_licence=".$Joueur['numero_de_licence']."\">Modifier</a>";
				echo " ou ";
				echo "<a onClick=\" javascript:return confirm('Voulez-vous supprimer ce Joueur ?')\" href=\"suppressionJoueur.php?numero_de_licence=".$Joueur['numero_de_licence']."\">Supprimer:</a>";
			?>

				<?= $Joueur['Nom'] ?>, 
				<?= $Joueur['Prenom'] ?>,
				<?php echo $repertoire.$Joueur['Photo'] ?>
				<img src="<?php echo $repertoire.$Joueur['Photo'] ?>" alt="" height="42" width="42" />, 
				<?= $Joueur['Date_de_naissance'] ?>,
				<?= $Joueur['Taille'] ?>, 
				<?= $Joueur['Poids'] ?>, 
				<?= $Joueur['Poste_prefere'] ?>, 
				<?= $Joueur['Notes'] ?>, 
				<?= $Joueur['numero_de_licence'] ?>,
				<br>
			<?php endforeach; ?>

		</ul>
			<a class="stylebouton" href="saisiejoueur.html">Ajout de Joueur</a>
	</body>
</html>
