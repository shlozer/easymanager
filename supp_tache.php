<?php
    session_start();
    require_once('connexion_bdd.php');   
    $direct_access_reconnexion = 'mois.php';  
    include('check_session_open.php');      

?>
<!DOCTYPE html>
<html>
<head>
    <title>Traitement de suppression d'une tache</title>
    <meta charset="UTF-8">
    <meta name="description" content="Traitement de suppression d'une tâche">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="header-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/supp_tache-style.css">
</head>
<body>  
<?php
    include('header.php');
?> 
<?php
if ($_GET['confirmation_supp'] != 1)
{
    echo '<p>Voulez-vous supprimer cette tâche?</p>';
    echo '<a href="supp_tache.php?cod_evt='.$_GET['cod_evt'].'&confirmation_supp=1">Oui</a>';
    echo '   <a href="tache.php?cod_evt='.$_GET['cod_evt'].'">Retour</a>';
}
else{
    $req = $bdd->prepare('DELETE FROM evt_jour WHERE cod_evt = :cod_evt');
    $req->execute(array('cod_evt' => $_GET['cod_evt']));
    $req = $bdd->prepare('DELETE FROM evt WHERE cod_evt = :cod_evt');
    $req->execute(array('cod_evt' => $_GET['cod_evt']));
    echo '<p> Tâche supprimée</p>';


}
?> 

<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>

<?php
    include('footer.php');
?>  
</body>          
