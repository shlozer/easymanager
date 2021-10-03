<?php
if (empty($_SESSION['user']) || empty($_SESSION['pass']) || empty($_SESSION['nom']) || empty($_SESSION['prenom']))
{
    //echo 'ici3';
    $_SESSION['direct_access_reconnexion'] = $direct_access_reconnexion;
    header('index_reconnect.php');
    exit();

}
$_SESSION['direct_access_reconnexion'] = '';
?>