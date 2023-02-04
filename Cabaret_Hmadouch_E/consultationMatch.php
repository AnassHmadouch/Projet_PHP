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
    $stmt = $bdco->prepare('SELECT * FROM matchs');
    $stmt->execute();
?>

<!DOCTYPE HTML>
<html>
	<head>
	    <title>Matchs</title>
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
		<link rel="stylesheet" href="css.css">
        <?php 
          include_once("Fonctions.php");
          echo menu();
        ?>
	</head>
	<body>
	    <div class="PageListeMatch">
			<div class="TitrePage">Liste des matchs </div>
            <div class="sousTitrePage">
                Matchs à venir :
            </div>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Equipe adverse</th>
                        <th>Feuille Match</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt3 = $bdco->prepare('SELECT * FROM matchs WHERE date_match > CURRENT_DATE');
                        $stmt3->execute();
                        while ($match = $stmt3->fetch()) { ?>
                        <tr>
                            <td><?php echo $match['lieu_match']; ?></td>
                            <td><?php echo $match['date_match']; ?></td>
                            <td>
                                <?php
                                    $stmt2 = $bdco->prepare('SELECT * FROM equipe_adverse WHERE id_adversaire = ?');
                                    $stmt2->execute([$match['id_adversaire']]);
                                    $equipe_adverse = $stmt2->fetch();
                                    echo $equipe_adverse['nom_adversaire'];
                                ?>
                            </td>
                            <?php
                                $stmt4 = $bdco->prepare('SELECT count(*) FROM participer WHERE id_Match = ?');
                                $stmt4->execute([$match['id_Match']]);
                                $NbjoueurParticipe = $stmt4->fetchColumn();
                                if ($NbjoueurParticipe > 0){
                                    echo '<td><a href="modifier_feuilleDeMatch.php?id_Match=' . $match['id_Match'] . '">Afficher</a></td>';
                                }else{
                                    echo '<td><a href="feuilleDeMatch.php?id_Match=' . $match['id_Match'] . '">Feuille</a></td>';
                                }
                            ?>
                            <td><a href="modificationMatch.php?id_Match=<?php echo $match['id_Match']; ?>"><img src="/projetPhotos/modifier.png" alt="" height="25" width="25"></a></td>
                            <td><a onClick=" javascript:return confirm('Voulez-vous supprimer ce match ?')" href="suppressionMatch.php?id_Match=<?php echo $match['id_Match']; ?>"><img src="/projetPhotos/supprimer.png" alt="" height="25" width="25"></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br>
            <div class="sousTitrePage">
                Matchs précedent :
            </div>
            <br>
             <table>
                <thead>
                    <tr>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Equipe adverse</th>
                        <th>Résultat</th>
                        <th>Evaluation</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt3 = $bdco->query('SELECT * FROM matchs WHERE date_match <= CURRENT_DATE');
                        while ($match = $stmt3->fetch()) { ?>
                        <tr>
                            <td><?php echo $match['lieu_match']; ?></td>
                            <td><?php echo $match['date_match']; ?></td>
                            <td>
                                <?php
                                    $stmt2 = $bdco->prepare('SELECT * FROM equipe_adverse WHERE id_adversaire = ?');
                                    $stmt2->execute([$match['id_adversaire']]);
                                    $equipe_adverse = $stmt2->fetch();
                                    echo $equipe_adverse['nom_adversaire'];
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ($match['resultat'] !== NULL) {
                                        echo '<form action="submit_result.php" method="POST">';
                                        echo '<select id="result" name="result">';
                                        echo '<option value="Gagné"'; if ($match['resultat'] == 'Gagné') {echo 'selected';} echo '>Gagné</option>';
                                        echo '<option value="Perdu"'; if ($match['resultat'] == 'Perdu') {echo 'selected';} echo '>Perdu</option>';
                                        echo '<option value="Nul"'; if ($match['resultat'] == 'Nul') {echo 'selected';} echo '>Nul</option>';
                                        echo '</select>';
                                        echo '<input type="hidden" value="' . $match['id_Match'] . '">';
                                        echo '<input type="submit" value="Valider">';
    							        echo '</form>';
                                    }else{
                                ?>
                                <form action="submit_result.php" method="POST">
                                    <select id="result" name="result">
                                        <option value="Gagné">Gagné</option>
                                        <option value="Perdu">Perdu</option>
                                        <option value="Nul">Nul</option>
                                    </select>
                                    <input type="hidden" name="idMatch" value="<?php echo $match['id_Match']; ?>">
                                    <input type="submit" value="Valider"/>
                                </form>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="EvaluationResultat.php?id=<?php echo $match['id_Match']; ?>">Evaluer</a>
                            </td>
                            <td><a href="modificationMatch.php?id_Match=<?php echo $match['id_Match']; ?>"><img src="/projetPhotos/modifier.png" alt="" height="25" width="25"></a></td>
                            <td><a onClick=" javascript:return confirm('Voulez-vous supprimer ce match ?')" href="suppressionMatch.php?id_Match=<?php echo $match['id_Match']; ?>"><img src="/projetPhotos/supprimer.png" alt="" height="25" width="25"></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
			<button class="styleboutonAjoutJoueur"><a class=" ajoutJoueur" href="ajout_match.php">Ajout match</a></button>
        </div>
    </body>
</html>