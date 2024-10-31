<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Ajouter Profil</title>
</head>
<body>
    <?php
        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.html');
    ?>

    <!-- DEBUT DE LA PAGE AJOUTER PROFIL -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <header class="header">
            <h1>E-COURRIER</h1>
            <p>Administateur</p>
        </header>
        <!-- FIN DU HEADER -->
        <main class="main">
            <!-- DEBUT DE LA BARRE DE NAVIGATION -->
            <nav class="nav-bar">
                <a class="nav-bar__link" href="#">Liste des utilisateurs</a>
                <a class="nav-bar__link" href="#">Ajouter un utilisateur</a>
                <a class="nav-bar__link" href="#">Liste des profils</a>
                <a class="nav-bar__link" href="ajouter-profil.php">Ajouter un profil</a>
                <a class="nav-bar__link" href="#">Utilisateurs supprimés</a>
            </nav>
            <!-- FIN DE LA BARRE DE NAVIGATION -->
            <section class="page-content-add-profil">
                <h2>Ajouter un utilisateur</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-add-profil">
                    <!-- DEBUT INFORMATION LOGIN -->
                    <h3>Informations Login</h3>
                    <div class="fields-add-profil">
                        <div class="field">
                            <label for="nom">Login</label>
                            <input type="text" name="" id="Nom">
                        </div>
                        <div class="field">
                            <label for="accès">Civiliter</label>
                            <select name="" id="">
                                <option value="M">Masculin</option>
                                <option value="F">Feminin</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="Profile">Profile*</label>
                            <select name="" id="" required>
                                <option value="">Nom profil</option>
                                <option value="">Autres</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="nom">Mot de passe*</label>
                            <input type="password" name="" id="Nom" required>
                        </div>
                        <div class="field">
                            <label for="nom">Confirmer mot de passe*</label>
                            <input type="password" name="" id="Nom" required>
                        </div>
                    </div>
                    <div class="btn-add-user">
                        <input type="submit" value="Généner un mot de passe" class="btn-add-user__key-generator">
                    </div>
                    <!-- FIN INFORMATION LOGIN -->

                    <!-- DEBUT INFORMATION UTILISATEUR -->
                    <h3>Informations utilisateur</h3>
                    <div class="fields-add-profil">
                        <div class="field">
                            <label for="nom">Nom</label>
                            <input type="text" name="" id="Nom">
                        </div>
                        <div class="field">
                            <label for="nom">Prenom</label>
                            <input type="text" name="" id="Nom">
                        </div>
                        <div class="field">
                            <label for="nom">Email</label>
                            <input type="email" name="" id="Nom">
                        </div>
                    </div>
                    <!-- FIN INFORMATION UTILISATEUR --> 

                    <!-- DEBUT INFORMATION POST  -->
                    <h3>Informations post</h3>
                    <div class="fields-add-profil">
                        <div class="field">
                            <label for="">Occupation</label>
                            <input type="text" name="" id="Nom">
                        </div>
                        <div class="field">
                            <label for="">Service</label>
                            <select name="" id="">
                                <option value="M">lettre</option>
                                <option value="F">vide</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN INFORMATION POST  -->
                    <div class="btn-add-user">
                        <input type="submit" value="Enregistrer">
                        <input type="reset" value="Annuler">
                    </div>
                </form>
                <!-- FIN DU FORMULAIRE -->
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE AJOUTER PROFIL -->
</body>
</html>