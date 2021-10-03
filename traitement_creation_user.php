<?php
    session_start();
    require_once('connexion_bdd.php');   
    $direct_access_reconnexion = 'gestion_users.php';
    include('check_session_open.php'); 


?>
<!DOCTYPE html>
<html>
<head>
    <title>Traitement de création d'un user</title>
    <meta charset="UTF-8">
    <meta name="description" content="Traitement de création d'un user">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="/styles/header_gestion_users-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/traitement_creation_user-style.css">
</head>
<body>  
<?php
    include('header.php');
?> 
<p>
<?php
//print_r ($_SESSION);
//print_r ($_POST);

// insertion ds table users 
$error_insert = 0;
try {
$req = $bdd->prepare('INSERT INTO users (user, pass, nom, prenom, mail) 
                        VALUES (:user, :pass, :nom, :prenom, :mail)');
$req->execute(array(
    'user' => $_POST['creation_user_user'],
    'pass' => $_POST['creation_user_pass'],
    'nom' => $_POST['creation_user_nom'],
    'prenom' => $_POST['creation_user_prenom'], 
    'mail' => $_POST['creation_user_mail']
));
}
catch (Exception $e) 
{
    echo 'erreur';
    echo $e;
    if ($e -> errorInfo[1] == 1062){
        echo 'Le user et/ou le mail sont déja utilisés';
        $error_insert = 1;
    } else {
        echo 'Erreur à l\'insertion';
        $error_insert = 1;
    }

}

if ($error_insert != 1){
    echo 'Ajout du user '.$_POST['creation_user_user'];
}?></p>
<a id ="retour_gestion_users" href ="gestion_users.php">Retour</a>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>

<?php
    include('footer.php');
?>  
</body>          
