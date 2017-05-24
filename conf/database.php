<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 24/05/2017
 * Time: 11:51
 */

function connexionBdd(){
    $hote = "db683268572.db.1and1.com";
    $db = "db683268572";
    $user = "dbo683268572";
    $pass = "mate-maker33!";
    try {
        return new PDO('mysql:host='.$hote.';dbname='.$db.';charset=utf8', $user, $pass);
    } catch (Exception $e) {
        die('<b>Erreur de connexion à la Bdd :</b> <br>' . $e->getMessage());
    }
}