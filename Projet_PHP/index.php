<?php
    session_start();
    if (isset($_POST['submit'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      if ($username == "Cab&Hmd" && $password == "\$iutinfo") {
        
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: consultationJoueur.php');
        exit;
      } else {
        $errorMessage = "Nom d'utilisateur ou mot de passe incorrect";
      }
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Identification</title>
	<link rel="icon" type="image/png" sizes="16x16" href="https://webstockreview.net/images/volleyball-clipart-teal-17.png">
    <link rel="stylesheet" href="css.css">
  </head>
  <body>
    <?php if (isset($errorMessage)) { 
        echo $errorMessage; 
    } ?>
    <div class="connexion">
      <form id="msform" action="index.php" method="post">
        <fieldset>
          <h2 class="fs-title">Connexion</h2>
          <input type="text" id="username" name="username" placeholder="Identifiant" />
          <input type="password" id="password" name="password" placeholder="Mot de passe" />
          <input class="next action-button" type="submit" name="submit" value="connexion"/>
        </fieldset>
      </form>
    </div>
  </body>
</html>
