<?php
    # On inilialise les variables permettant l'identification de notre compte phpMyAdmin
    $server = "localhost";
    $login = "root";
    $mdp = "";
    $db = "projet_php";
    
    $bdco = new PDO("mysql:host=$server;dbname=$db",$login,$mdp);

    $req = $bdco->prepare("SELECT * FROM joueur WHERE numero_de_licence = :numero_de_licence");
    $req->bindParam(':numero_de_licence', $_GET['numero_de_licence'], PDO::PARAM_STR);

    $executionReq = $req->execute();

    $Joueur = $req->fetch();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modification de Joueur</title>
    </head>
    <body>
        <h1>Formulaire de modification</h1>
            <form action="modJoueur.php" method="POST">
                <input type="hidden" name="NumLicence" value="<?= $Joueur['numero_de_licence']; ?>">                
                Nom du Joueur : <input type="text" name="Nom" value= "<?= $Joueur['Nom']; ?>">
                <br/><br/>
                Prénom du Joueur : <input type="text" name="Prenom" value="<?= $Joueur['Prenom']; ?>">
                <p>
                Photo du Joueur : <input type="File" name="Photo" value="<?= $Joueur['Photo']; ?>">
                <br/><br/>
                Date de Naissance du Joueur : <input type="Date" name="Date_de_naissance" value="<?= $Joueur['Date_de_naissance']; ?>">
                <p>
                Taille du Joueur : <input type="number" name="Taille" value="<?= $Joueur['Taille']; ?>">
                <br/><br/>
                Poids du Joueur : <input type="number" name="Poids" value="<?= $Joueur['Poids']; ?>">
                <br/><br/>
                Poste preferer du Joueur : <input type="text" name="PostePref" value="<?= $Joueur['Poste_prefere']; ?>">
                <br/><br/>
                <label for="Notes">Notes joueur : </label><br />
                <textarea name="Notes" id="Notes"><?= $Joueur['Notes']; ?></textarea>
                    <br/>
                <p>
                <input type=submit value="Modifier">
                <p>
                <a class="stylebouton" href="consultationjoueur.php">Rechercher</a>
            </form>
    </body>
</html>