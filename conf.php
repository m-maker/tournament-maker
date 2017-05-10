<?php 

session_start();

function connexionBdd(){
	$hote = "localhost";
	$db = "tournoi_soccer";
	$user = "root";
	$pass = "";
	try
	{
		return $db = new PDO('mysql:host='.$hote.';dbname='.$db.';charset=utf8', $user, $pass);
	}
	catch (Exception $e)
	{
	    die('Erreur : ' . $e->getMessage());
	}
}

$db = connexionBdd();

?>