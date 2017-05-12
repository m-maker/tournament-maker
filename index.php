<?php
	include('conf.php');
?>	
<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Tournois de foot en salle</title>
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->

		<!-- BARRE DE RECHERCHE -->

		<form id="form_recherche" action="liste_tournois.php" method="get">
	  		<div id="barre_de_recherche">
	  			<span> Les tournois à coté de chez toi </span>
	    		<input id="input_barre_recherche" type="text" placeholder="Département: 33, 75, 13..." name="dpt">
	  			<button class="btn btn-success btn-xs" type="submit" class="btn btn-default">Go!</button>
	  		</div>
		</form>

		<!-- FOOTER -->
		<?php include('footer.php') ?>
	</body>

</html>