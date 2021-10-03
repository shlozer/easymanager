<?php
    session_start();
    require_once('connexion_bdd.php');  
    $direct_access_reconnexion = 'creation_tache.php';
    include('check_session_open.php'); 


?>
<!DOCTYPE html>
<html>
<head>
    <title>Création d'une nouvelle tâche</title>
    <meta charset="UTF-8">
    <meta name="description" content="Création d'une nouvelle tâche">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="header-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/creation_tache-style.css">
</head>
<body>  
<?php
    include('header.php');
?> 
<div id="partie_centrale">
<h3> NOUVELLE TACHE</h3>
<form method="post" action="traitement_creation_tache.php" >

<?php
// si c'est l'admin il a des droits sur les taches de tt le monde    
    //print_r ($_SESSION);
    if ($_SESSION['id'] == 0)
    {
        echo 'ici'; echo $_SESSION['id'];
    {$req = $bdd->prepare('SELECT * FROM users');
        $req->execute();
        //$req_tous_user = $req->fetch();
    }
    echo '<fieldset id="form-id"><legend>User</legend>';
    //echo '<label for="creation_tache_id">User</label>';
    echo '<select name="creation_tache_id" id="creation_tache_id">';
    while ($req_tous_user_l = $req -> fetch())
        {
            echo '<option value="'.$req_tous_user_l['id'].'">'.$req_tous_user_l['user'].'</option>';

        }
    echo '</select><br /></fieldset>';

    }
    else
    {
       $_POST['creation_tache_id'] = $_SESSION['id'];
    }
    
?> 
   <fieldset id="form-contenu"><legend>Contenu</legend>
   <label for="creation_tache_titre">Titre</label><input type="text" name="creation_tache_titre" 
            value="<?php if (isset($_GET['re_creation_tache_titre'])) {echo $_GET['re_creation_tache_titre'];} ?>" id="creation_tache_titre" size="90"/><br />
   <label for="creation_tache_description">Description</label><textarea name="creation_tache_description" 
            value="" id="creation_tache_description" cols ="90" rows="10"><?php if (isset($_GET['re_creation_tache_description'])) {echo $_GET['re_creation_tache_description'];} ?></textarea><br />
   </fieldset>
   <fieldset id="form-deb"><legend>Début</legend>
   <label for="creation_tache_deb_jour">Jour</label>
   <select name="creation_tache_deb_jour" id="creation_tache_deb_jour">
        <?php 
        for ($i = 1; $i <= 31; $i++) 
       {
           echo '<option value="';
           if ($i <= 9) {echo '0';}
           echo $i.'">'.$i.'</option>';
       }?>
    </select>
   <label for="creation_tache_deb_mois">Mois</label>
    <select name="creation_tache_deb_mois" id="creation_tache_deb_mois">
       <?php 
        $mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        for ($i = 1; $i <= 12; $i++) 
       {
           echo '<option value="';
           echo $i.'">'.$mois_fr_tab[$i].'</option>';
       }
       ?>
    </select>
    <label for="creation_tache_deb_annee">Année</label>
    <select name="creation_tache_deb_annee" id="creation_tache_deb_annee">
    <?php 
        for ($i = 2010; $i <= 2030; $i++) 
        {
            echo '<option value="';
            echo $i.'">'.$i.'</option>';
        } 
    ?>
    </select>
    <label for="creation_tache_deb_heure">Heure</label>
        <select name="creation_tache_deb_heure" id="creation_tache_deb_heure">
        <?php 
        for ($i = 0; $i <= 23; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0';}
            echo $i.'">'.$i.'</option>';
        } 
        ?>
    </select>
    <label for="creation_tache_deb_min">Minute</label>
        <select name="creation_tache_deb_min" id="creation_tache_deb_min">
        <?php 
        for ($i = 0; $i <= 59; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0';}
            echo $i.'">'.$i.'</option>';
        } 
        ?>
    </select><br />
    </fieldset>
    <fieldset id="form-fin"><legend>Fin</legend>
    <label for="creation_tache_fin_jour">Jour</label>
    <select name="creation_tache_fin_jour" id="creation_tache_fin_jour">
        <?php 
        for ($i = 1; $i <= 31; $i++) 
       {
           echo '<option value="';
           if ($i <= 9) {echo '0';}
           echo $i.'">'.$i.'</option>';
       }?>
    </select>
    <label for="creation_tache_fin_mois">Mois</label>
    <select name="creation_tache_fin_mois" id="creation_tache_fin_mois">
        <?php 
        //$mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        for ($i = 1; $i <= 12; $i++) 
       {
           echo '<option value="';
           echo $i.'">'.$mois_fr_tab[$i].'</option>';
       }
       ?>
    </select>
    <label for="creation_tache_fin_annee">Année</label>
    <select name="creation_tache_fin_annee" id="creation_tache_fin_annee">
        <?php 
        for ($i = 2010; $i <= 2030; $i++) 
        {
            echo '<option value="';
            echo $i.'">'.$i.'</option>';
        } 
        ?>
    </select>
    <label for="creation_tache_fin_heure">Heure</label>
        <select name="creation_tache_fin_heure" id="creation_tache_fin_heure">
        <?php 
        for ($i = 0; $i <= 23; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0';}
            echo $i.'">'.$i.'</option>';
        } 
        ?>
    </select>
    <label for="creation_tache_fin_min">Minute</label>
        <select name="creation_tache_fin_min" id="creation_tache_fin_min">
        <?php 
        for ($i = 0; $i <= 59; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0';}
            echo $i.'">'.$i.'</option>';
        } 
        ?>
    </select><br /> 
    </fieldset>

   
   <input type="submit" value="Créer" />
</form></div>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>


<?php
    include('footer.php');
?>  
</body>          
    