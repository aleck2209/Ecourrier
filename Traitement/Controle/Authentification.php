<?php

function connectToDb($nomServuer,$nomBaseDonnee,$nomUtilisateur,$motDePasse){
    $dsn = "mysql:host=$nomServuer;dbname=$nomBaseDonnee";
    
    try {
        $objet_bd = new PDO($dsn,$nomUtilisateur,$motDePasse
        );
    //Gestion des erreurs hormis la connexion
      $objet_bd ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo 'connexion au serveur mysql réussie...';
    

    } catch (PDOException $e) {
        die('erreur de connection '.$e->getMessage());
    }
    
    return $objet_bd
;

}


// Démarrer la session
session_start();

$message = "";
// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Utiliser l'objet de connexion existant
     $objet_connexion = connectToDb('localhost','ecourrierdb2','Dba','EcourrierDba'); // Supposons que la variable $objet_connexion est disponible ici

    // Récupérer les données soumises par le formulaire
    $matricule = $_POST['nomUtilisateur'] ?? '';  // Utilisation du matricule ici
    $motDePasse = $_POST['mdp'] ?? '';

    // Vérifier que les champs ne sont pas vides
    if (empty($matricule) || empty($motDePasse)) {
        echo "Veuillez remplir tous les champs.";
    } else {
        // Préparer la requête pour récupérer l'utilisateur par son matricule
        $sql = "SELECT * FROM utilisateur WHERE Matricule = :matricule LIMIT 1";
        $stmt = $objet_connexion->prepare($sql);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->execute();

        // Vérifier si l'utilisateur existe
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($utilisateur) {
            // Comparer le mot de passe haché
            if (password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                // Authentification réussie, démarrer la session
                $_SESSION['utilisateur'] = $utilisateur['nom_utilisateur'];
                $_SESSION['matricule'] = $utilisateur['Matricule'];
                
                // Récupérer l'entité de l'utilisateur
                if ($utilisateur['id_entite'] !== null) {
                    $sql_entite = "SELECT * FROM entite_banque WHERE id_entite = :id_entite LIMIT 1";
                    $stmt_entite = $objet_connexion->prepare($sql_entite);
                    $stmt_entite->bindParam(':id_entite', $utilisateur['id_entite']);
                    $stmt_entite->execute();
                    $entite = $stmt_entite->fetch(PDO::FETCH_ASSOC);
                    if ($entite) {
                        $_SESSION['entite'] = $entite['nom_entite'];
                    }
                }

                // Récupérer le pôle de l'utilisateur
                if ($utilisateur['id_pole'] !== null) {
                    $sql_pole = "SELECT * FROM pole WHERE id_pole = :id_pole LIMIT 1";
                    $stmt_pole = $objet_connexion->prepare($sql_pole);
                    $stmt_pole->bindParam(':id_pole', $utilisateur['id_pole']);
                    $stmt_pole->execute();
                    $pole = $stmt_pole->fetch(PDO::FETCH_ASSOC);
                    if ($pole) {
                        $_SESSION['pole'] = $pole['nom_pole'];
                    }
                }

                // Rediriger vers la page d'accueil ou autre page sécurisée
                
                header('../../public/page/tableau-bord');
                exit;
            } else {
                $message= "Mot de passe incorrect.";
            }
        } else {
            $message= "Aucun utilisateur trouvé avec ce matricule.";
        }
    }
}
?>
