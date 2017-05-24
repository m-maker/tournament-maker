<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 24/05/2017
 * Time: 11:51
 */

function connexionBdd(){
    $hote = "localhost";
    $db = "tournoi_soccer";
    $user = "root";
    $pass = "";
    try {
        return new PDO('mysql:host='.$hote.';dbname='.$db.';charset=utf8', $user, $pass);
    } catch (Exception $e) {
        die('<b>Erreur de connexion à la Bdd :</b> <br>' . $e->getMessage());
    }
}