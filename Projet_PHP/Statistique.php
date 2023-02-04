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
<?php
    $stmt = $bdco->prepare('SELECT * FROM matchs WHERE resultat = "gagne"');
	$stmt->execute();
	$nbMatchsGagne = $stmt->rowCount();

	$stmt = $bdco->prepare('SELECT * FROM matchs WHERE resultat = "perdu"');
	$stmt->execute();
	$nbMatchsPerdu = $stmt->rowCount();

	$stmt = $bdco->prepare('SELECT * FROM matchs WHERE resultat = "nul"');
	$stmt->execute();
	$nbMatchsNul = $stmt->rowCount();

	$totalMatchs = $nbMatchsGagne + $nbMatchsPerdu + $nbMatchsNul;
	$pourcentageGagne = ($nbMatchsGagne / $totalMatchs) * 100;
	$pourcentagePerdu = ($nbMatchsPerdu / $totalMatchs) * 100;
	$pourcentageNul = ($nbMatchsNul / $totalMatchs) * 100;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Statistique</title>
        <link rel="stylesheet" href="css.css">
        <?php 
          include_once("Fonctions.php");
          echo menu();
        ?>
	</head>
	<body>
	    <div class="pageStat">
    		<div class="TitrePage">Statistiques générales</div>
    		<div class="misePageStat">
    		    <div>
            		<div class="sousTitrePage" style="text-align: center;">Statistiques des matchs</div>
            		<div class="Diagramme">
                		<canvas id="myCanvas" width="400" height="400"></canvas>
                        <script>
                            var canvas = document.getElementById("myCanvas");
                            var ctx = canvas.getContext("2d");
                            var data = [<?= round($pourcentagePerdu) ?>, <?= round($pourcentageGagne) ?>, <?= round($pourcentageNul) ?>];
                            var total = data.reduce(function(a, b) { return a + b; }, 0);
                            var radius = 150;
                            var colors = ["#C1121F", "#669BBC", "#FDF0D5"];
                            var startAngle = 0;
                            var endAngle = 0;
                        
                            for (var i = 0; i < data.length; i++) {
                                endAngle = endAngle + (data[i] / total) * 2 * Math.PI;
                                ctx.beginPath();
                                ctx.moveTo(canvas.width / 2, canvas.height / 2);
                                ctx.arc(canvas.width / 2, canvas.height / 2, radius, startAngle, endAngle);
                                ctx.closePath();
                                ctx.fillStyle = colors[i];
                                ctx.fill();
                                ctx.stroke();
                                startAngle = endAngle;
                            }
                        </script>
                		<div class="Legende">
                			<div class="couleurLegende1">Matchs gagnés (<?php echo round($pourcentageGagne); ?>%)</div>
                			<div class="couleurLegende2">Matchs perdus (<?php echo round($pourcentagePerdu); ?>%)</div>
                			<div class="couleurLegende3">Matchs nuls (<?php echo round($pourcentageNul); ?>%)</div>
                		</div>
                    </div>
                </div>
                <div class="TableauStatistiques">
            		<div class="sousTitrePage" style="text-align: center;">Statistiques des joueurs</div><br>
                    <form class="filtreStat" method="GET" action="Statistique.php">
                        <div>
                            <input class="inputFiltreStat" type="text" name="nom" placeholder="Nom du joueur" required/>
                            <input class="inputFiltreStat" type="text" name="prenom" placeholder="Prénom du joueur" required/>
                            <input class="btnFiltrerStat" type="submit" value="OK">
                            <a class="annulerFiltre" href="Statistique.php">Annuler</a>
                        </div><br>
                    </form>
            		<table>
                        <?php
                            if (isset($_GET["nom"]) && isset($_GET["prenom"])) {
                                $nomJoueur = $_GET["nom"];
                                $prenomJoueur = $_GET["prenom"];
                                $stmt = $bdco->prepare('SELECT * FROM joueur WHERE Nom = ? AND Prenom = ?');
                                $stmt->execute([$nomJoueur, $prenomJoueur]);
                                echo '<table><thead><tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Titulaire</th>
                                                <th>Remplaçant</th>
                                                <th>Moyenne</th>
                                            </tr></thead>';
                    		    while ($joueur = $stmt->fetch()) {
                                    $stmt2 = $bdco->prepare('SELECT COUNT(*) FROM participer WHERE numero_de_licence = ? AND Statut = "titulaire"');
                                    $stmt2->execute([$joueur['numero_de_licence']]);
                                    $nbTitulaire = $stmt2->fetchColumn();
                        
                                    $stmt2 = $bdco->prepare('SELECT COUNT(*) FROM participer WHERE numero_de_licence = ? AND Statut = "remplacant"');
                                    $stmt2->execute([$joueur['numero_de_licence']]);
                                    $nbRemplacant = $stmt2->fetchColumn();
                        
                                    $stmt2 = $bdco->prepare('SELECT AVG(evaluation) FROM participer WHERE numero_de_licence = ?');
                                    $stmt2->execute([$joueur['numero_de_licence']]);
                                    $moyenneEvaluation = $stmt2->fetchColumn();
                                    echo "<tbody><tr>";
                                    echo "<td>" . $joueur['Nom']." </td>
                                          <td> ".$joueur['Prenom']." </td>
                                          <td> ".$nbTitulaire." </td>
                                          <td> ".$nbRemplacant." </td>
                                          <td> ".$moyenneEvaluation."</td>";
                                }
                                echo "</tr></tbody></table>";
                            }else{
                                if ($_GET["nom"] == '' && $_GET["prenom"] == ''){
                                    $stmt = $bdco->query('SELECT * FROM joueur');
                                    echo '<table><thead><tr>
                                                <th>Nom</th>
                	            				<th>Prénom</th>
                	            				<th>Titulaire</th>
                	            				<th>Remplaçant</th>
                	            				<th>Moyenne</th>
                	            		  	</tr></thead>';
                	            		  	
                        			while ($joueur = $stmt->fetch()) {
                        			    $stmt2 = $bdco->prepare('SELECT COUNT(*) FROM participer WHERE numero_de_licence = ? AND Statut = "titulaire"');
                        			    $stmt2->execute([$joueur['numero_de_licence']]);
                        			    $nbTitulaire = $stmt2->fetchColumn();
                        
                        			    $stmt2 = $bdco->prepare('SELECT COUNT(*) FROM participer WHERE numero_de_licence = ? AND Statut = "remplacant"');
                        			    $stmt2->execute([$joueur['numero_de_licence']]);
                        			    $nbRemplacant = $stmt2->fetchColumn();
                        
                        			    $stmt2 = $bdco->prepare('SELECT AVG(evaluation) FROM participer WHERE numero_de_licence = ?');
                        			    $stmt2->execute([$joueur['numero_de_licence']]);
                        			    $moyenneEvaluation = $stmt2->fetchColumn();
                                        echo "<tbody><tr>";
                        			    echo "<td>" . $joueur['Nom']." </td>
                        			        <td> ".$joueur['Prenom']." </td>
                        			        <td> ".$nbTitulaire." </td>
                        			        <td> ".$nbRemplacant." </td>
                        			        <td> ".$moyenneEvaluation."</td>";
                        			}
                                    echo "</tr></tbody></table>";
                                }
                		    }
                		?>
            		</table>
            	</div>
        	</div>
        </div>
	</body>
</html>