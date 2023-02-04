<?php

	function menu(){
		echo ' 
			<div id="header">
		      <div class="logo">
		        <div class="titreLogo">Athentical Volley</div>
		      </div>  
		      <nav>
		        </form>
		        <ul>
		          <li>
		            <a href="consultationJoueur.php">Joueurs</a>
		          </li>
		          <li class="dropdown">
		            <a href="#">Matchs</a>
		              <ul>
		                <li><a href="consultationMatch.php">Tous les matchs</a></li>
		                <li><a href="feuilleDeMatch.php">Feuilles de match</a></li>
		              </ul>     
		          </li>
		          <li>
		            <a href="Statistique.php">Statistiques</a>     
		          </li>
		          <li>
		            <a href="index.html">Déconnexion</a>
		          </li>
		        </ul>
		      </nav>
		    </div>';
	}
?>