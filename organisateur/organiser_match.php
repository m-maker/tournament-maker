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
        
<div class="onglet-contenu">
            <p>
                <span class="filtre"><b> Complexe:</b>
                    <button id="btn_dpt" class="btn btn-default center" data-toggle="modal" data-target="#myModal">
                        <div id="nom_departement" > <?php echo $res_dpt['dpt_nom']; ?>  <b class="caret"></b> </div>
                    </button>
                </span>
            </p>
            <br/>
                <div class="menu_complexe">
                    <div class="center show_complexe acti" id="onglet-all" >
                        Tous (<?php echo compte_event_dpt($dpt); ?>)
                    </div>
                    <?php
                        $i = 0;
                        foreach ($tab_complexes_events as $key => $compl_event) {
                            $lieu = recupLieuById($compl_event[0]);
                            $class="";
                            ?>
                                <div class="center show_complexe onglet_complexe_1" id="onglet-<?php echo $compl_event[0]; ?>" >
                                        <div class="center onglet_complexe_1_1">
                                            <p><?php echo $lieu['lieu_nom']; ?> <br/> <?php echo $lieu['lieu_ville']; ?></p>
                                        </div>
                                        <div class="center onglet_complexe_1_2" style="background-image: url(<?php echo $lieu['lieu_logo']; ?>);">
                                            <!-- <img src="<?php echo $lieu['lieu_logo']; ?>" alt="<?php echo $lieu['lieu_nom']; ?>"> -->
                                        </div>
                                        <div class="center onglet_complexe_1_3 "> 
                                            <p><?php echo '('.$compl_event[1].') '; ?></p>
                                        </div>
                                </div>
                            <?php
                            $i++;
                        }
                    ?>
                </div>
            </div>

		<!-- CONTENU DE LA PAGE -->

        <!-- FOOTER -->
		<?php include('footer.php') ?>


	</body>

</html>