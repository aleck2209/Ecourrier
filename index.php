<?php
// Inclure le fichier de traitement
require('Traitement/Controle/Authentification.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/css/style1.css">
        <title>Connexion</title>
    </head>
    <body class="body-login">
        <form action="" class="form-login">
            <h1>Connexion</h1>
            <div class="field-login">
                <label for="nomUtilisateur">Matricule</label>
                <input type="text" name="" id="nomUtilisateur" placeholder="Entrer votre nom utilisateur" required>
            </div>
            <div class="field-login">
                <label for="mdp">Mot de passe</label>
                <input type="password" name="" id="mdp" placeholder="Entre votre mot de passe" required>
            </div>
            <div class="btn-login">
                <input type="submit" value="Connexion">     
            </div>
        </form>
    </body>
</html>