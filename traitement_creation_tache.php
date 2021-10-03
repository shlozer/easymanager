<?php
    session_start();
    require_once('connexion_bdd.php');  
    $direct_access_reconnexion = 'creation_tache.php';
    include('check_session_open.php'); 


?>
<!DOCTYPE html>
<html>
<head>
    <title>Traitement de création d'une tache</title>
    <meta charset="UTF-8">
    <meta name="description" content="Traitement de création d'une nouvelle tâche">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="header-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/traitement_creation_tache-style.css">
</head>
<body>  
<?php
    include('header.php');
?> 
<?php
print_r ($_SESSION);
print_r ($_POST);
// verif dates valides
if (!checkdate($_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'], $_POST['creation_tache_deb_annee']))
{
    //$_SESSION['re_creation_tache_titre'] = $_POST['creation_tache_titre'];
    //$_SESSION['re_creation_tache_description'] = $_POST['creation_tache_description'];
    echo 'Date de début de tâche invalide';
    echo '<a href="creation_tache.php?re_creation_tache_titre='. $_POST['creation_tache_titre'] .'&re_creation_tache_description='. $_POST['creation_tache_description'] .'">Revenir</a> pour recréer la tâche';
    include('footer.php');
    exit();
}
if (!checkdate($_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'], $_POST['creation_tache_fin_annee']))
{
    //$_SESSION['re_creation_tache_titre'] = $_POST['creation_tache_titre'];
    //$_SESSION['re_creation_tache_description'] = $_POST['creation_tache_description'];
    echo 'Date de fin de tâche invalide';
    echo '<a href="creation_tache.php?re_creation_tache_titre='. $_POST['creation_tache_titre'] .'&re_creation_tache_description='. $_POST['creation_tache_description'] .'">Revenir</a> pour recréer la tâche';
    include('footer.php');
    exit();
}
// verif date de debut avant date de fin
if (mktime($_POST['creation_tache_deb_heure'], $_POST['creation_tache_deb_min'], 0, 
    $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'], $_POST['creation_tache_deb_annee'])
    > mktime($_POST['creation_tache_fin_heure'], $_POST['creation_tache_fin_min'], 0, 
    $_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'], $_POST['creation_tache_fin_annee']))
{   
    //$_SESSION['re_creation_tache_titre'] = $_POST['creation_tache_titre'];
    //$_SESSION['re_creation_tache_description'] = $_POST['creation_tache_description'];
    echo 'Début de tâche après fin de tâche';
    echo '<a href="creation_tache.php?re_creation_tache_titre='. $_POST['creation_tache_titre'] .'&re_creation_tache_description='. $_POST['creation_tache_description'] .'">Revenir</a> pour recréer la tâche';
    include('footer.php');
    exit();
}
// insertion ds table evt (une seule insertion par tache)
$req = $bdd->prepare('INSERT INTO evt (id, titre, description_tache, debut_evt, fin_evt) 
                        VALUES (:id, :titre, :description_tache, :debut_evt, :fin_evt)');
$req->execute(array(
    'id' => $_POST['creation_tache_id'],
    'titre' => $_POST['creation_tache_titre'],
    'description_tache' => $_POST['creation_tache_description'],
    'debut_evt' => date("Y-m-d H:i:s", 
    mktime($_POST['creation_tache_deb_heure'], $_POST['creation_tache_deb_min'], 0,
            $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'],$_POST['creation_tache_deb_annee'])),
    'fin_evt' => date("Y-m-d H:i:s", 
    mktime($_POST['creation_tache_fin_heure'], $_POST['creation_tache_fin_min'], 0, 
            $_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'],$_POST['creation_tache_fin_annee']))
));
print_r ($req);
// insertion dans evt_jour
$req = $bdd->prepare('SELECT MAX(cod_evt) FROM evt');
$req->execute(array());
$dernier_cod_evt = $req -> fetch();
print_r ($dernier_cod_evt);
$req = $bdd->prepare('INSERT INTO evt_jour (cod_evt, id, titre, description_tache, debut_evt_jour, fin_evt_jour) 
                        VALUES (:cod_evt, :id, :titre, :description_tache, :debut_evt_jour, :fin_evt_jour)');

// cas où la tache est sur la meme journee donc une seule insertion
if (mktime(23, 59, 0,
    $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'],$_POST['creation_tache_deb_annee']) ==
    mktime(23, 59, 0, 
    $_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'],$_POST['creation_tache_fin_annee']))
{
    $req->execute(array(
    'cod_evt' => $dernier_cod_evt['MAX(cod_evt)'],
    'id' => $_POST['creation_tache_id'],
    'titre' => $_POST['creation_tache_titre'],
    'description_tache' => $_POST['creation_tache_description'],
    'debut_evt_jour' => date("Y-m-d H:i:s", 
    mktime($_POST['creation_tache_deb_heure'], $_POST['creation_tache_deb_min'], 0,
            $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'],$_POST['creation_tache_deb_annee'])),
    'fin_evt_jour' => date("Y-m-d H:i:s", 
    mktime($_POST['creation_tache_fin_heure'], $_POST['creation_tache_fin_min'], 0, 
            $_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'],$_POST['creation_tache_fin_annee']))));        
}
// cas ou la tache s'etale sur plus d'une journee
else
{
// premiere insertion avec heure de fin a 23:59 du premier jour    
    //$fin_evt_jour_temp = date("Y-m-d H:i:s", mktime(23, 59, 0,
    //$_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'],$_POST['creation_tache_deb_annee']));
    $req->execute(array(
    'cod_evt' => $dernier_cod_evt['MAX(cod_evt)'],
    'id' => $_POST['creation_tache_id'],
    'titre' => $_POST['creation_tache_titre'],
    'description_tache' => $_POST['creation_tache_description'],
    'debut_evt_jour' => date("Y-m-d H:i:s", 
    mktime($_POST['creation_tache_deb_heure'], $_POST['creation_tache_deb_min'], 0,
        $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'],$_POST['creation_tache_deb_annee'])),
    'fin_evt_jour' => date("Y-m-d H:i:s", mktime(23, 59, 0,
        $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'],$_POST['creation_tache_deb_annee']))));
    $i = 1;
    
    // cas où la tache s'etale sur au moins trois jours donc insertions 0:00 23:59 jusqu'a la veille de la date de fin de tache
    while(mktime(23, 59, 0,
        $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'] + $i, $_POST['creation_tache_deb_annee']) 
        < mktime(23, 59, 0,
        $_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'], $_POST['creation_tache_fin_annee']))
    {
        $req->execute(array(
            'cod_evt' => $dernier_cod_evt['MAX(cod_evt)'],
            'id' => $_POST['creation_tache_id'],
            'titre' => $_POST['creation_tache_titre'],
            'description_tache' => $_POST['creation_tache_description'],
            'debut_evt_jour' => date("Y-m-d H:i:s", 
            mktime(0, 0, 0,$_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'] + $i, $_POST['creation_tache_deb_annee'])),
            'fin_evt_jour' => date("Y-m-d H:i:s", 
            mktime(23, 59, 0, $_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'] + $i, $_POST['creation_tache_deb_annee']))));
        $i++;
    }
    // derniere insertion avec debut a 0:00 et fin = a fin de tache    
    $req->execute(array(
    'cod_evt' => $dernier_cod_evt['MAX(cod_evt)'],
    'id' => $_POST['creation_tache_id'],
    'titre' => $_POST['creation_tache_titre'],
    'description_tache' => $_POST['creation_tache_description'],
    'debut_evt_jour' => date("Y-m-d H:i:s", 
    mktime(0, 0, 0, $_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'],$_POST['creation_tache_fin_annee'])),
    'fin_evt_jour' => date("Y-m-d H:i:s", mktime($_POST['creation_tache_fin_heure'], $_POST['creation_tache_fin_min'], 0,
        $_POST['creation_tache_fin_mois'], $_POST['creation_tache_fin_jour'],$_POST['creation_tache_fin_annee']))));
    
}


?>
<p> Tâche créee</p>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>

<?php
    include('footer.php');
?>  
</body>          
