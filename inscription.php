<html>
	
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/footer.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<title>S'inscrire sur la plateforme</title>
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->

		<!-- BARRE DE RECHERCHE -->
	    <div class="row space-top">
			<div class="form-grand">

				<div class="panel panel-primary">
				  	<div class="panel-heading">
				    	<h3 class="panel-title">S'inscrire sur la plateforme</h3>
				  	</div>
				  	<div class="panel-body">

						<form class="form-horizontal form-grand" method="post" action="inscription_check.php">
						  	<fieldset>

						    	<legend class="center">Saisissez les infos suivantes :</legend>

						    	<div class="form-group">
						        	<input type="text" class="form-control" id="inputPseudo" name="pseudo" placeholder="Votre pseudo">
						    	</div>

						    	<div class="form-group">
						        	<input type="password" class="form-control" id="inputPass" name="pass" placeholder="*******">
						    	</div>

						    	<div class="form-group">
						        	<input type="tel" class="form-control" id="inputTel" name="tel" placeholder="Votre numéro de telephone" pattern="^[0-9]{10}$">
						    	</div>

						    	<div class="form-group">
						        	<input type="tel" class="form-control" id="inputEmail" name="mail" placeholder="Votre adresse-mail">
						    	</div>

						    	<div class="form-group center">
					          		<label style="margin-right: 2%;">
					            		<input type="radio" name="orga" id="optionsRadios1" value="0" checked="">
					            		Joueur
					          		</label>
					          		<label>
					            		<input type="radio" name="orga" id="optionsRadios2" value="1">
					            		Organisateur
					          		</label>	
							    </div>

						    	<div class="form-group center">
						    		<button type="submit" name="submit" class="btn btn-primary btn-grand">S'inscrire</button>
							        <button type="reset" class="btn btn-default btn-grand">Annuler</button>			        	
							    </div>

						    </fieldset>
						</form>


					</div>
				</div>
			</div>
		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>
	</body>

</html>