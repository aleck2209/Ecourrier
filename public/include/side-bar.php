<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <!-- DEBUT SIDE-BAR -->
    <nav class="side-bar">
        <div>
            <div class="logo">
                <figcaption>
                    <img src="../images/logo.png" alt="log de la BEAC">
                </figcaption>
                <p>BEAC</p>
            </div>
            <div class="gestion-courrier">
                <ul>
                    <li>
                        <a href="#">Tableau de Bord</a>
                    </li>
                    <li>
                        <a href="cas-fichier.php">Enregistrer Courrier</a>
                    </li>
                    <li>
                        <a href="#">Courrier Enregistrer</a>
                        <!-- DEBUT DROPDOWN -->
                        <ul class="gestion-courrier__dropdown">
                            <li>
                                <a href="#">Courrier Départ</a>
                            </li>
                            <li>
                                <a href="#">Courrier Arrivé</a>
                            </li>
                        </ul>
                        <!-- FIN DROPDOWN -->
                    </li>
                    <li>
                        <a href="notification-recu.php">Notifications</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="gestion-utilisateur">
            <ul>
                <li>
                    <a href="compte.php">Compte</a>
                </li>
                <li>
                    <a href="#">Déconnexion</a>
                </li>
                <li>
                    <a href="liste-utilisateur.php">Administration</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- FIN SIDEBAR -->
</body>
</html>