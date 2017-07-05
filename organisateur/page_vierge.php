<?php

include '../conf.php';

if ($_SESSION["membre_orga"] != 1){
	header("Location: ../index.php");
}


?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Organiser un match</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php include('volet.php'); ?>

    <!-- CONTENU DE LA PAGE -->
    <div id="corps">
        <h1 id="titre_corps">Organiser un macth</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->


		<!-- CONTENU DE LA PAGE -->

        <!-- FOOTER -->
		<?php include('footer.php') ?>


	</body>

</html>