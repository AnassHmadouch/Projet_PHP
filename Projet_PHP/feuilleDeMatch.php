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
            <form method="GET" action="feuilleDeMatch.php">
    			<div class="Filtre">
        			<div class="selectdiv">
                        <select name="id_Match" id="id_Match">
                            <option value='0'>Sélectionner un match</option>
                            <?php
                                // Récupération de la liste des matchs
                                $stmt = $bdco->prepare('SELECT * FROM matchs WHERE date_match > CURRENT_DATE');
                                $stmt->execute();
                                while ($match = $stmt->fetch()) {
                                echo '<option value="' . $match['id_Match'] . '">' . $match['lieu_match'] . ' - ' . $match['date_match'] . ' - ' . $match['heure_match'] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <input class="btnFiltrer" type="submit" value="OK">
                </div>
            </form>
            <form class="divMatchSelect" method="POST" action="ajout_participer.php" onsubmit="return nbrSelectionJoueurs();">
                <div class="MatchSelect">
                    <label class="sousTitrePage" for="match">Match sélectionné :</label>
                    <?php
                        if (isset($_GET["id_Match"])) {
                          $idMatch = intval($_GET["id_Match"]);
                          $stmt = $bdco->prepare('SELECT * FROM matchs WHERE id_Match = ?');
                          $stmt->execute([$idMatch]);
                          $match = $stmt->fetch();
                          echo  '<div class="nomMatchSelect">' . $match['lieu_match'] . ' - ' . $match['date_match'] . ' - ' . $match['heure_match'] . '</div><br>';
                          echo '<input type="hidden" name="id_Match" value="' . $idMatch . '">';
                        } else {
                          echo '<div class="nomMatchSelect">Veuillez sélectionnez un match</div>';
                        }
                    ?>
                </div>
                <?php
                    if (isset($_GET["id_Match"]) && $_GET["id_Match"] != 0) {
                        $idMatch = intval($_GET["id_Match"]);
                        $stmt = $bdco->query('SELECT * FROM joueur WHERE Statut = "Actif"');
                        echo '<table class="tableFeuilleMatch"><theade><tr>
                                    <th>Selectionner</th>
                                    <th>Nom Prenom</th>
                                    <th>Titulaire</th>
                                    <th>Remplaçant</th>
                               </tr></thead>';
                        while ($Joueur = $stmt->fetch()) {
                          echo '<tbody><tr>';
                          echo '<th><input class="choixJoueur" type="checkbox" name="joueurs[]" value="' . $Joueur['numero_de_licence'] . '"></th>';
                          echo '<th>'.$Joueur['Prenom'] . ' ' . $Joueur['Nom'].'</th>';
                          echo '<th><input type="radio" value="titulaire" name="statut_' . $Joueur['numero_de_licence'] . '"></th>';
                          echo '<th><input type="radio" value="remplacant" name="statut_' . $Joueur['numero_de_licence'] . '"></th>';
                          echo '<input type="hidden" name="idJoueur" value="' . $Joueur['numero_de_licence'] . '">';
                          echo '</tr>';
                        }
                        echo '</tbody></table>';
                        echo '<div class="btnAjoutFeuilleMatch">
                                <input class="styleboutonAjoutJoueur" type="submit" value="valider">
                            </div>';
                    } else {
                        $stmt = $bdco->prepare('SELECT * FROM joueur');
                        $stmt->execute();
                        echo '<ul class="ullisteNomsJoueur">';
                        while ($Joueur = $stmt->fetch()) {
                          echo '<li class="lilisteNomsJoueur">';
                          echo $Joueur['Prenom'] . ' ' . $Joueur['Nom'];
                          echo '</li>';
                        }
                        echo '</ul>';
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
