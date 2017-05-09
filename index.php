<html>
	
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="header.css">
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<title>Tournois de foot en salle</title>
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->

		<!-- BARRE DE RECHERCHE -->
		<form id="form_recherche" action="liste_tournois" method="post">
	  		<div id="barre_de_recherche">
	  			<span> retrouve ton tournois </span>
	    		<input type="text" placeholder="Département: 33, 75, 13...">
	  			<button class="btn btn-success btn-xs" type="submit" class="btn btn-default">Go!</button>
	  		</div>
		</form>

	</body>

</html>