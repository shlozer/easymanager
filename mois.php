<?php
    session_start();
    require_once('connexion_bdd.php');
    
    if (empty($_SESSION['direct_access_reconnexion']))
    //if ($_SESSION['premiere_connexion'] != 'ok' )
    {
            
        if (isset($_GET['vue_mois_mois']) && isset($_GET['vue_mois_annee']))
           {$direct_access_reconnexion = 'mois.php?vue_mois_mois='.$_GET['vue_mois_mois'].'&vue_mois_annee='.$_GET['vue_mois_annee']; echo '1';}
        else 
           {$direct_access_reconnexion = 'mois.php';  echo '2';}
           echo 'ici';
        include('check_session_open.php'); 
        $_SESSION['direct_access_reconnexion'] = $direct_access_reconnexion;
    }
    
    //print_r ($_SESSION);
    if (isset($_SESSION['id'])){}
    else
    {
    $req = $bdd->prepare('SELECT * FROM users WHERE user = :user');
    $req->execute(array(
    'user' => htmlspecialchars($_POST['user_comp'])));
    $req_user = $req->fetch();
    
    //$ispasscorrect = password_verify($_POST['pass_comp'], $req_user['pass']);
    $_SESSION['user'] = $_POST['user_comp'];
    $_SESSION['pass'] = $_POST['pass_comp'];
    
    $_SESSION['bad_pass'] = 0;    
    if ($_SESSION['pass'] != $req_user['pass'])
    {           
        //session_destroy();
        $_SESSION['bad_pass'] = 1;
        //print_r($_SESSION);
        header('index_badpass.php');
        exit();
    }
    //$_SESSION['premiere_connexion'] = '';

    $_SESSION['nom'] = $req_user['nom'];
    $_SESSION['prenom'] = $req_user['prenom'];
    $_SESSION['id'] = $req_user['id'];
    }
    //redirection vers la page où était le visiteur 
    //sauf si c'est la page mois.php car c'est la page actuelle
    if ((!empty($_SESSION['direct_access_reconnexion'])) && ($_SESSION['direct_access_reconnexion'] != 'mois.php'))
    {
        //echo $_SESSION['direct_access_reconnexion'];
        //echo 'ici2';
        header ($_SESSION['direct_access_reconnexion']);
        exit;
    }

?>
<!DOCTYPE html>
<html>
<head>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <?php
        if (isset($_GET['vue_mois_mois']) && isset($_GET['vue_mois_annee']))
        {
            $_SESSION['vue_mois']['mois'] = $_GET['vue_mois_mois'];
            $_SESSION['vue_mois']['annee'] = $_GET['vue_mois_annee'];
        }     
        if (!isset($_SESSION['vue_mois']))
        {
            $date_auj= getdate();
            $_SESSION['vue_mois']['mois'] = $date_auj['mon'];
            $_SESSION['vue_mois']['annee'] = $date_auj['year'];
        }
        $mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        $mois_fr = $mois_fr_tab[date("n", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'], 1 , $_SESSION['vue_mois']['annee']))];
        echo '<title>Vue du mois de '. $mois_fr . ' '. $_SESSION['vue_mois']['annee'].'</title>';
    ?>
    <meta charset="UTF-8">
    <meta name="description" content="Visualisation des tâches du mois">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="header-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/mois-style.css">
</head>
<body>  
<?php
    include('header.php');
?>    
<?php
    //echo __FILE__;
    $mois_fr_tab_maj = array("","JANVIER","FEVRIER","MARS","AVRIL","MAI","JUIN","JUILLET","AOUT","SEPTEMBRE","OCTOBRE","NOVEMBRE","DECEMBRE"); 
    $mois_fr_maj = $mois_fr_tab_maj[date("n", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'], 1 , $_SESSION['vue_mois']['annee']))];
?>
<h2 id="titre_vue_mois"><?php echo $mois_fr_maj . ' '. $_SESSION['vue_mois']['annee'];?></h3>
<div id="trois_objets_principaux">
<!-- boutons fleche gauche(mois precedent) -->    
<div id="objet_1">
<?php echo '<a id = "fleche_mois_precedent" href="mois.php?vue_mois_mois='.date("n", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] - 1 , 1, $_SESSION['vue_mois']['annee']))
        .'&vue_mois_annee='.date("Y", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] - 1 , 1, $_SESSION['vue_mois']['annee'])).'">'
        .'<img src="/images/fleche_mois_precedent.png" alt="mois_precedent" /></a>';
?>
</div>
<?php
//création du tableau qui contient l'ensemble des données sur 6 semaines

$jour_du_1 = (date("w", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'], 1, $_SESSION['vue_mois']['annee'])) + 6) % 7;
$table_vue_mois[$jour_du_1]['jour_tab'] = 1;
$table_vue_mois[$jour_du_1]['mois_tab'] = 'a';
$j=0;
for ($i = $jour_du_1 - 1; $i >= 0; $i--) 
{
    //$table_vue_mois[$i]['jour_tab'] = date("j", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'], 1 - $i, $_SESSION['vue_mois']['annee']));
    $table_vue_mois[$i]['jour_tab'] = date("j", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'], $j, $_SESSION['vue_mois']['annee']));
    $table_vue_mois[$i]['mois_tab'] = 'p';
    $j--;
}
$j=2;
for ($i = $jour_du_1 + 1; $i <= date("j", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] + 1, 0, $_SESSION['vue_mois']['annee'])) - 1 + $jour_du_1; $i++) 
{
    //$table_vue_mois[i] = $i - $jour_du_1 + 1;
    $table_vue_mois[$i]['jour_tab'] = date("j", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'], $j, $_SESSION['vue_mois']['annee']));
    $table_vue_mois[$i]['mois_tab'] = 'a';
    $j++;
}
$dernier_i = $i;
$j=1;
for ($i = $dernier_i ; $i <= 41; $i++) 
{
    $table_vue_mois[$i]['jour_tab'] = date("j", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] + 1, $j, $_SESSION['vue_mois']['annee']));
    $table_vue_mois[$i]['mois_tab'] = 's';
    $j++;
}
if ($_SESSION['id'] != 0)
    {$req2 = $bdd->prepare('SELECT COUNT(*)FROM evt_jour 
                      WHERE id = :id and debut_evt_jour >= :debut_evt_min_sql and fin_evt_jour <= :fin_evt_max_sql');}
else{
    $req2 = $bdd->prepare('SELECT COUNT(*)FROM evt_jour 
                      WHERE debut_evt_jour >= :debut_evt_min_sql and fin_evt_jour <= :fin_evt_max_sql');
    }

echo '<div id="objet_2">';
echo '<table><tr><thead><tr><th>LUNDI</th><th>MARDI</th><th>MERCREDI</th><th>JEUDI</th><th>VENDREDI</th><th>SAMEDI</th><th>DIMANCHE</th></tr></thead>';
echo '<tbody><tr>';
for ($i = 0; $i <= 41; $i++)
{
    switch($table_vue_mois[$i]['mois_tab'])
    {
        case 'a':
            $debut_evt_min = date('Y-m-d H:i:s', mktime(0, 0, 0, $_SESSION['vue_mois']['mois'], $table_vue_mois[$i]['jour_tab'], $_SESSION['vue_mois']['annee']));
            $fin_evt_max = date('Y-m-d H:i:s', mktime(23, 59, 59, $_SESSION['vue_mois']['mois'], $table_vue_mois[$i]['jour_tab'], $_SESSION['vue_mois']['annee']));
        break;
        case 'p':
            $debut_evt_min = date('Y-m-d H:i:s', mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] - 1, $table_vue_mois[$i]['jour_tab'], $_SESSION['vue_mois']['annee']));
            $fin_evt_max = date('Y-m-d H:i:s', mktime(23, 59, 59, $_SESSION['vue_mois']['mois'] - 1, $table_vue_mois[$i]['jour_tab'], $_SESSION['vue_mois']['annee']));    
        break;
        case 's':
            $debut_evt_min = date('Y-m-d H:i:s', mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] + 1, $table_vue_mois[$i]['jour_tab'], $_SESSION['vue_mois']['annee']));
            $fin_evt_max = date('Y-m-d H:i:s', mktime(23, 59, 59, $_SESSION['vue_mois']['mois'] + 1, $table_vue_mois[$i]['jour_tab'], $_SESSION['vue_mois']['annee']));
        break;
    }
    if ($_SESSION['id'] != 0)    
        {$req2->execute(array(
        'id' => $_SESSION['id'],
        'debut_evt_min_sql' => $debut_evt_min,
        'fin_evt_max_sql'   => $fin_evt_max,));}
    else {
        {$req2->execute(array(
            'debut_evt_min_sql' => $debut_evt_min,
            'fin_evt_max_sql'   => $fin_evt_max,));}
    }                          
    $req_nb_evt_jour = $req2->fetch();
    //if ($req_nb_evt_jour == null) {$req_nb_evt_jour = 0;}
    //$req2->closecursor();
    // construction de la case du tableau avec le lien vers la vue jour
    echo '<td class = "';
    if ($table_vue_mois[$i]['mois_tab'] == 'a')
        {echo 'jour_noir">';}
    else 
        {echo 'jour_gris">';}
    
    echo '<a href ="jour.php?vue_jour_jour=';
    echo $table_vue_mois[$i]['jour_tab'];
    
    echo '&vue_jour_mois=';
    switch ($table_vue_mois[$i]['mois_tab'])
    {
        case 'a':
        echo $_SESSION['vue_mois']['mois'];
        break;
        case 'p':
        echo date("n", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] - 1, 1 , $_SESSION['vue_mois']['annee']));
        break;
        case 's':
        echo date("n", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] + 1, 1 , $_SESSION['vue_mois']['annee']));
        break;
    }
    
    echo '&vue_jour_annee=';
    if ($table_vue_mois[$i]['mois_tab'] = 'a' || 
    ($table_vue_mois[$i]['mois_tab'] = 'p' && $_SESSION['vue_mois']['mois'] != 1) ||
    ($table_vue_mois[$i]['mois_tab'] = 's' && $_SESSION['vue_mois']['mois'] != 12))
    {
        echo $_SESSION['vue_mois']['annee'];
    }
    else if 
    ($table_vue_mois[$i]['mois_tab'] = 'p' && $_SESSION['vue_mois']['mois'] = 1)
    {
        echo $_SESSION['vue_mois']['annee'] - 1;
    }
    else if 
    ($table_vue_mois[$i]['mois_tab'] = 's' && $_SESSION['vue_mois']['mois'] = 12)
    {
        echo $_SESSION['vue_mois']['annee'] + 1;
    }
    echo '">'.$table_vue_mois[$i]['jour_tab'];
    if ($req_nb_evt_jour['COUNT(*)'] > 0)
    {
        //print_r ($req_nb_evt_jour);
        echo ' -> '. $req_nb_evt_jour['COUNT(*)'] . ' tâches';
    }
    echo '</a></td>';

    if (($i % 7) == 6) {echo '</tr>';}
    if ((($i % 7) == 6)  && ($i < 41)) {echo '<tr>';}

}
echo '</tbody></table>';
?>
</div>
<!-- bouton fleche doite (mois suivant)-->
<div id="objet_3">
<?php        
        echo '<a id = "fleche_mois_suivant" href="mois.php?vue_mois_mois='.date("n", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] + 1 , 1, $_SESSION['vue_mois']['annee']))
        .'&vue_mois_annee='.date("Y", mktime(0, 0, 0, $_SESSION['vue_mois']['mois'] + 1 , 1, $_SESSION['vue_mois']['annee'])).'">'
        .'<img src="/images/fleche_mois_suivant.png" alt="mois_suivant" /></a>';
?>
</div></div>

<!-- nouvelle tache
<a id="bouton_creation_tache" href ="creation_tache.php">Créer une nouvelle tâche</a>-->
<div id="deux_boutons_bas">
<div id="bouton_creation_tache"><a  href ="creation_tache.php"><img src="images/ajout_tache.png" /></a>
</div>
<!--<br /><br /><br /><br /><br /><br />-->
<!-- aller à un mois défini par l'utilisateur-->
<div id="choix_mois_annee">
<form method="get" action="mois.php">
    Aller à:
    <select name="vue_mois_mois" id="vue_mois_mois">
    <?php 
        $mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        for ($i = 1; $i <= 12; $i++) 
       {
           echo '<option value="';
           echo $i.'"';
           if ((isset($_SESSION['vue_mois']['mois'])) && (isset($_SESSION['vue_mois']['annee']))
            && (date("n",mktime(0,0,0,$_SESSION['vue_mois']['mois'],1,$_SESSION['vue_mois']['annee'])) == $i))
           {echo ' selected';}
           echo '>'.$mois_fr_tab[$i].'</option>';
       }
       ?>
    </select>
    <select name="vue_mois_annee" id="vue_mois_annee">
    <?php 
        for ($i = 2010; $i <= 2030; $i++) 
        {
            echo '<option value="';
            echo $i.'"';
            if ((isset($_SESSION['vue_mois']['mois'])) &&(isset($_SESSION['vue_mois']['annee']))
            && (date("Y",mktime(0,0,0,$_SESSION['vue_mois']['mois'],1,$_SESSION['vue_mois']['annee'])) == $i))
           {echo ' selected';}
            echo '>'.$i.'</option>';
        } 
    ?>
    </select>
    <input type="submit" value="-->" />
</form>
</div></div>


<?php
    include('footer.php');
?>  
</body>          