<?php 

include('conf.php'); 
if ($_SESSION["orga"] != 1)
	header("Location: index.php");
?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Administrer mes tournois</title>
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->
		<div class="container-fluid">
			
		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>
	</body>

</html>