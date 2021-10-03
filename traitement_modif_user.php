<?php
    session_start();
    require_once('connexion_bdd.php');    
    $direct_access_reconnexion = 'gestion_users.php';
    include('check_session_open.php'); 


?>
<!DOCTYPE html>
<html>
<head>
    <title>Traitement de modification d'un user</title>
    <meta charset="UTF-8">
    <meta name="description" content="Traitement de modification d'un user">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="/styles/header_gestion_users-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/traitement_modif_user-style.css">
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
$error_modif = 0;
if ($_SESSION['id'] == 0)
{try {
$req = $bdd->prepare('UPDATE users SET mail = :mail WHERE id = :id');
$req->execute(array(
    'mail' => $_POST['modif_mail_user'],
    'id' => $_POST['modif_user_id']
));
}
catch (Exception $e) 
{
    echo 'erreur';
    echo $e;
    $error_modif = 1;
}

}
else{
try {
$req = $bdd->prepare('UPDATE users SET mail = :mail WHERE id = :id');
$req->execute(array(
    'mail' => $_POST['modif_mail_user'],
    'id' => $_POST['id']
));
}
catch (Exception $e) 
{
    echo 'erreur';
    echo $e;
    $error_modif = 1;
}
}

if ($error_modif != 1){
    echo 'Modification du mail effectuée';}

?></p>
<a id ="retour_gestion_users" href ="gestion_users.php">Retour</a>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>

<?php
    include('footer.php');
?>  
</body>          
