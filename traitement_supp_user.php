<?php
    session_start();
    require_once('connexion_bdd.php');    
    $direct_access_reconnexion = 'gestion_users.php';
    include('check_session_open.php'); 


?>
<!DOCTYPE html>
<html>
<head>
    <title>Traitement de suppression d'un user</title>
    <meta charset="UTF-8">
    <meta name="description" content="Traitement de suppression d'un user">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="/styles/header_gestion_users-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/traitement_supp_user-style.css">
</head>
<body>  
<?php
    include('header.php');
?><p> 
<?php
//print_r ($_SESSION);
//print_r ($_POST);

// insertion ds table users 
$error_delete = 0;
try {
$req = $bdd->prepare('DELETE FROM users WHERE id = :id');
$req->execute(array(
    'id' => $_POST['supp_user_id']
));
}
catch (Exception $e) 
{
    echo 'erreur';
    echo $e;
    $error_delete = 1;
}

if ($error_delete != 1){
    echo 'Suppression du user'.'<br />';}
if ($_POST['supp_toutes_taches'] == 'oui')
{
    $error_delete = 0;
    try {
    $req = $bdd->prepare('DELETE FROM evt WHERE id = :id');
    $req->execute(array(
        'id' => $_POST['supp_user_id']
    ));
    }
    catch (Exception $e) 
    {
       echo 'erreur supp evt';
       echo $e;
       $error_delete = 1;
    }
    
    try {
    $req = $bdd->prepare('DELETE FROM evt_jour WHERE id = :id');
    $req->execute(array(
        'id' => $_POST['supp_user_id']
    ));
    }
    catch (Exception $e) 
    {
       echo 'erreur supp evt_jour';
       echo $e;
       $error_delete = 1;
    }
    
    if ($error_delete != 1){
        echo 'Suppression des tâches du user';}
}



?></p>
<a id ="retour_gestion_users" href ="gestion_users.php">Retour</a>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>

<?php
    include('footer.php');
?>  
</body>          
