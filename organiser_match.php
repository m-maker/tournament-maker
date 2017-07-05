<?php

include 'conf.php';

if (!isset($_SESSION["id"]) OR empty($_SESSION["id"])){
    header("Location: index.php");
}
if ($_SESSION["membre_orga"] == 1){
    header("Location: organisateur/index.php");
}
?>

<html>
	
	<head>
		<?php include('head.php'); ?>

    <link rel="stylesheet" type="text/css" href="css/page_accueil.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/organiser.css">
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

<?php
                    $req = $db->prepare("SELECT membre_dpt_code FROM membres WHERE id = :id");
                    $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
                    $req->execute();
                    $dpt_user = $req->fetchColumn();
                    if ($dpt_user != null){
                        echo '<script>
                            $.post("organiser_match_ajax.php", {dpt:'.$dpt_user.'}, function(data) {
                              $("#post").html(data);
                            });
                        </script>';
                        ?>

                            <div id="post" class="container-fluid center" style="padding: 2%;">
                            </div>
                        <?php
                    }                    
                    else{
                    ?>
                        <div id="post" class="container-fluid center" style="padding: 2%;">
                            <p style="color: black; font-size: 20px;">Selectionnez un département afin de trouver les tournois / matchs</p>
                            <button id="btn_dpt" class="btn btn-default center" data-toggle="modal" data-target="#myModal">
                                <div id="nom_departement" > Département  <b class="caret"></b> </div>
                            </button>
                            <hr/>
                        </div>
                    <?php
                    }
                    ?>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="myModalLabel">Département</h2>
                                    </div>
                                    <div class="modal-body">
                                        <div class="liste_departements" id="liste_departements">
                                            <form id="form_dpt">
                                                <ul>
                                                    <?php
                                                    foreach (listeDepartements() as $key) {
                                                        ?>
                                                        <li>
                                                            <label> <?php echo '('.$key['dpt_code'].') '.$key['dpt_nom']; ?>
                                                                <input type="radio" name="dpt" value="<?php echo $key['dpt_code'] ?>" class="badgebox">
                                                            </label>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="valider" type="button" class="btn btn-default" data-dismiss="modal">Valider</button>
                                    </div>
                                </div>
                            </div>
                        </div>
		<!-- CONTENU DE LA PAGE -->

        <!-- FOOTER -->
		<?php include('footer.php') ?>

	</body>
<script type="text/javascript">
            $(".show1").click(function() {
                $(".show1").removeClass("acti1");
                $(this).addClass("acti1");
                $(".cont1").hide();
                var id = $(this).attr("id");
                if (id == "show-prives")
                    $("#prives").show();
                else if (id == "show-publiques")
                    $("#publiques").show();
            });

</script>
            <script>
                $('.show_complexe').click(function () {
                    var id = $(this).attr("id");
                    var cont = $('.cont');
                    var cont_event;
                    cont.hide();
                    if (id == "onglet-all"){
                        cont_event = $('#cont-all');
                    }else{
                        cont_event = $('#cont-' + id);
                    }
                    cont_event.show();
                    $(".acti").removeClass('acti');
                    $(this).addClass("acti");
                });
            </script>
</html>