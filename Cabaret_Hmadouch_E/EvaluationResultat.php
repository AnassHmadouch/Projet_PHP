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
        <title>Evaluation des matchs</title>
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
        <link rel="stylesheet" href="css.css">
        <?php 
          include_once("Fonctions.php");
          echo menu();
        ?>
    </head>
    <div class="pageEvalResultat">
        <div class="TitrePage">Evaluation des joueurs</div>
        <table>
            <thead>
                <tr>
                    <th>Match</th>
                    <th>Joueur</th>
                    <th>Statut</th>
                    <th>Evaluation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $stmt = $bdco->prepare('SELECT * FROM matchs WHERE id_Match = ?');
                    $stmt->execute([$_GET['id']]);
                    while ($match = $stmt->fetch()) {
                        $stmt2 = $bdco->prepare('SELECT * FROM joueur INNER JOIN participer ON joueur.numero_de_licence = participer.numero_de_licence WHERE participer.id_Match = ?');
                        $stmt2->execute([$_GET['id']]);
                        while ($joueur = $stmt2->fetch()) {
                            echo '<tr>';
                            echo '<td>' . $match['lieu_match'] . ' - ' . $match['date_match'] . '</td>';
                            echo '<td>' . $joueur['Prenom'] . ' ' . $joueur['Nom'] . '</td>';
                            echo '<td>' . $joueur['Statut'] . '</td>';
                            echo '<td>';
                            if (!empty($joueur['evaluation'])) {
                                echo '<form method="POST" action="traitementResultat.php">';
                                echo '<input type="hidden" name="numero_de_licence" value="' . $joueur['numero_de_licence'] . '">';
                                echo '<input type="hidden" name="id_match" value="'.$match['id_Match'] . '">';
                                echo '<select name="evaluation_' . $joueur['numero_de_licence'] . '">';
                                echo '<option value="1"'; if ($joueur['evaluation'] == 1) {echo 'selected';} echo '>1</option>';
                                echo '<option value="2"'; if ($joueur['evaluation'] == 2) {echo 'selected';} echo '>2</option>';
                                echo '<option value="3"'; if ($joueur['evaluation'] == 3) {echo 'selected';} echo '>3</option>';
    							echo '<option value="4"'; if ($joueur['evaluation'] == 4) {echo 'selected';} echo '>4</option>';
    							echo '<option value="5"'; if ($joueur['evaluation'] == 5) {echo 'selected';} echo '>5</option>';
    							echo '</select>';
    							echo '<input type="submit" value="Valider">';
    							echo '</form>';
                            } 
                            else {
                                echo '<form method="POST" action="traitementResultat.php">';
                                echo '<input type="hidden" name="numero_de_licence" value="' . $joueur['numero_de_licence'] . '">';
                                echo '<input type="hidden" name="id_match" value="'.$match['id_Match'] . '">';
                                echo '<select name="evaluation_' . $joueur['numero_de_licence'] . '">';
                                echo '<option value=""></option>';
                                echo '<option value="1">1</option>';
                                echo '<option value="2">2</option>';
                                echo '<option value="3">3</option>';
    							echo '<option value="4">4</option>';
    							echo '<option value="5">5</option>';
    							echo '<input type="submit" value="Valider">';
    							echo '</select>';
    							echo '</form>';
					        }
    						echo '</td>';
    						echo '</tr>';
                        }
                    }
        		?>
    			</tbody>
    		</table>
    		<br>
	    	<div class="rechercher">
	    		<a class="RetourPage" href="consultationMatch.php">Retour</a>
	    	</div>
    	</div>
	</body>
</html>