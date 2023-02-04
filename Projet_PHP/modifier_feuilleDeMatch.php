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
<html>
    <head>
        <meta charset="utf-8">
        <title>Feuille de Match</title>
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
        <link rel="stylesheet" href="css.css">
        <?php 
          include_once("Fonctions.php");
          echo menu();
        ?>
     </head>
    <body>
        <div class="pageFeuilleMatch">
            <div class="titrePage">Feuille de Match</div>
            <form class="divMatchSelect" method="POST" action="modifier_participer.php" onsubmit="return nbrSelectionJoueurs();">
                <?php
                    if (isset($_GET["id_Match"]) && $_GET["id_Match"] != 0) {
                        $idMatch = intval($_GET["id_Match"]);
                        echo '<div class="pageFeuilleListe">
                              <div class="ListeEtTitre">
                              <div class="sousTitrePage" style="text-align: center;">Joueurs anciennement associés à ce match</div>
                                <ul class="ullisteNomsJoueur">';
                        $stmt2 = $bdco->prepare('SELECT * FROM joueur INNER JOIN participer ON joueur.numero_de_licence = participer.numero_de_licence WHERE participer.id_Match = ?');
                        $stmt2->execute([$idMatch]);
                        while ($Joueur = $stmt2->fetch()) {
                          echo '<li class="lilisteNomsJoueur">' . $Joueur['Prenom'] . ' ' . $Joueur['Nom'] . '</li>';
                        }
                        echo '</ul></div>';
                        $stmt = $bdco->prepare('SELECT * FROM joueur WHERE Statut = "Actif"');
                        $stmt->execute();
                        echo '<div class="TableauEtTitre"><div class="sousTitrePage" style="text-align: center;">Nouvelle sélection</div><br>
                              <div class="tableauFeuille"><table class="tableFeuilleMatch"><theade><tr>
                                    <th>Selectionner</th>
                                    <th>Nom Prenom</th>
                                    <th>Titulaire</th>
                                    <th>Remplaçant</th>
                               </tr></thead>';
                        while ($Joueur = $stmt->fetch()) {
                          echo '<tbody><tr>
                            <th><input class="choixJoueur" type="checkbox" name="joueurs[]" value="' . $Joueur['numero_de_licence'] . '"></th>
                            <th>'.$Joueur['Prenom'] . ' ' . $Joueur['Nom'].'</th>
                            <th><input type="radio" value="titulaire" name="statut_' . $Joueur['numero_de_licence'] . '"></th>
                            <th><input type="radio" value="remplacant" name="statut_' . $Joueur['numero_de_licence'] . '"></th>
                            <input type="hidden" name="idJoueur" value="' . $Joueur['numero_de_licence'] . '">
                            <input type="hidden" name="id_Match" value="' . $_GET["id_Match"] . '">
                          </tr>';
                        }
                        echo '</tbody></table></div></div></div>';
                        echo '<div class="btnAjoutFeuilleMatch">
                                <input class="styleboutonAjoutJoueur" type="submit" value="valider">
                            </div>';
                    }
                ?>
            </form>
        </div>
    </body>
</html>

<script>
    function nbrSelectionJoueurs() {
        var boutonsCheckBox = document.getElementsByTagName('input');
        var nbJoueursSelectionnes = 0;
        var nbJoueurTitulaireSelectionne = 0;
        for (var i = 0; i < boutonsCheckBox.length; i++) {
            if (boutonsCheckBox[i].checked && boutonsCheckBox[i].type == 'checkbox') {
                nbJoueursSelectionnes++;
                var idJoueur = boutonsCheckBox[i].value;
                var boutonsRadio = document.getElementsByName('statut_' + idJoueur);
                for (var j = 0; j < boutonsRadio.length; j++) {
                    if (boutonsRadio[j].checked && boutonsRadio[j].value == "titulaire") {
                        nbJoueurTitulaireSelectionne++;
                    }
                }
            }
        }
        if (nbJoueursSelectionnes < 7 || nbJoueursSelectionnes > 12) {
            alert('Veuillez vérifiez que vous ayez bien sélectionné 7 joueurs titulaires minimum et 12 joueurs maximum ');
            return false;
        }
        if (nbJoueurTitulaireSelectionne < 7) {
            alert("Veuillez sélectionner au moins 7 joueurs titulaires pour valider la sélection");
            return false;
        }
        for (var i = 0; i < boutonsCheckBox.length; i++) {
            if (boutonsCheckBox[i].type == "checkbox" && boutonsCheckBox[i].checked) {
                var statutSelectionne = false;
                var idJoueur = boutonsCheckBox[i].value;
                var boutonsRadio = document.getElementsByName('statut_' + idJoueur);
                for (var j = 0; j < boutonsRadio.length; j++) {
                    if (boutonsRadio[j].checked) {
                        statutSelectionne = true;
                    }
                }
                if (!statutSelectionne) {
                    alert("Veuillez sélectionner un statut pour chaque joueur sélectionné");
                    return false;
                }
            }
        }
            return true;
    }
</script>
