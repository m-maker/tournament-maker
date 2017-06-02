<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 31/05/2017
 * Time: 18:30
 */

include 'conf.php';
if (!isset($_SESSION["id"]))
    header("Location: ../connexion.php");

$id = htmlspecialchars($_POST["id"]);
$compte = Notifications::getCompteNewNotif($id);
if ($_SESSION["id"] == $id){
    if ($compte > 0)
        echo "<b>Notifications (" . Notifications::getCompteNewNotif($id) . ")</b>";
    else
        echo "Notifications (" . Notifications::getCompteNewNotif($id) . ")";
}