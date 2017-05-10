<?php 

session_start();

function connexionBdd(){
	$hote = "db681288666.db.1and1.com";
	$db = "db681288666";
	$user = "dbo681288666";
	$pass = "mate-maker2017!";
	try {
		return $db = new PDO('mysql:host='.$hote.';dbname='.$db.';charset=utf8', $user, $pass);
	} catch (Exception $e) {
	    die('<b>Erreur de connexion à la Bdd :</b> <br>' . $e->getMessage());
	}
}

$db = connexionBdd();

?>