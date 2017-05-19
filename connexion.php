<?php include "conf.php"; ?>
<html>
	
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/footer.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<title>Tournois de foot en salle</title>
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->

		<!-- Zone de connexion -->
	    <div class="row space-top">
			<div class="form-grand">

                <div class="form-simple" style="margin-bottom: 15%;">

						<form class="form-horizontal form-grand" method="post" action="connexion_check.php">
				  			<fieldset>

				    			<legend class="center">Acceder a la zone membre</legend>

				    			<div class="form-group">
						        	<input type="text" class="form-control" id="inputPseudo" name="pseudo" placeholder="Votre pseudo/adresse-mail">
						    	</div>

						    	<div class="form-group">
						        	<input type="password" class="form-control" id="inputPass" name="pass" placeholder="*******">
						    	</div>

						    	<div class="form-group center">
				    				<button type="submit" name="submit" class="btn btn-success btn-grand">Se connecter</button>
					    		</div>

				    		</fieldset>
				    	</form>

				</div>

		  	</div>
		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

	</body>

</html>