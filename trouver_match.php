<?php
	include "conf.php";

	if(isset($_POST['dpt']) AND !empty($_POST['dpt'])){
        $dpt = htmlspecialchars(trim($_POST['dpt']));
    }
    else{
        $dpt = 33;
    }
	$liste_tournois = liste_tournois($dpt);

	$req_ajout_dpt = $db->prepare("UPDATE membres SET membre_dpt_code = :dpt WHERE id = :id_membre");
	$req_ajout_dpt->bindValue(":dpt", $dpt, PDO::PARAM_STR);
	$req_ajout_dpt->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
	$req_ajout_dpt->execute();

	global $res_dpt_from_liste_tournois;

	if(isset($_SESSION['id'])){
		$user = $db->prepare('SELECT * FROM membres WHERE membres.id = :membre_id');
		$user->execute(array(
			'membre_id' => $_SESSION['id']
		));
	}

	$req_dpt = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
	$req_dpt->execute(array(
		'dpt_code' => $dpt
		));
	$res_dpt = $req_dpt->fetch();

    $req_membre_dpt_code = $db->prepare('UPDATE membres SET membre_dpt_code = :dpt_code WHERE id = :membre_id');
    $req_membre_dpt_code->execute(array(
        'dpt_code' => $dpt,
        'membre_id' => $_SESSION['id']
        ));

	$liste_complexes = liste_lieux($res_dpt['id']);
    $tab_complexes_events = [];
	foreach ($liste_complexes as $key => $value) {
		//var_dump($key);
		//var_dump($value);
		$req_nb_events = $db->prepare('SELECT COUNT(evenements.id) FROM evenements WHERE event_lieu_id = :event_lieu AND event_date >= DATE(NOW())');
		$req_nb_events->execute(array(
			'event_lieu' => $value['id']
			));
		$res_nb_events = $req_nb_events->fetchColumn();
		$tab_complexes_events[] = array( $value['id'], $res_nb_events);
		//var_dump($tab_complexes_events);
		$req_nb_events->closeCursor();
	}
	//arsort($tab_complexes_events, SORT_NUMERIC);
	//var_dump($tab_complexes_events);
	//var_dump($tab_complexes_events); 
?>
<!DOCTYPE html>
<html>
    <head>

    <?php include('head.php'); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/index_mobile.css">
    <link rel="stylesheet" type="text/css" href="css/home_mobile.css">
    <link rel="stylesheet" type="text/css" href="css/trouver_match.css">

        <title>Créer un compte</title>

            <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>  -->
            <script src="jquery-1.12.4.js"></script>
            <script src="jquery-ui.js"></script>
        <script src="bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript">

    $(document).ready(function(){
        $("#formulaire_general_complexe li").click(function(){
            var th = this;
            
            $.ajax({type:"POST", data: $(".input_dpt", this).serialize(), url:"events_ajax.php",
            success: function(data){
              $("#post_events_ajax").html(data);
              //console.log(departement);
            },
            error: function(){
              $("#post").html('Une erreur est survenue.');
            }
          });
          
          $.ajax({type:"POST", data: $(".input_dpt", this).serialize(), url:"trouver_match_ajax.php",
            success: function(data){
              $("#post").html(data);
              var departement = $(".input_dpt", th).val();
              //console.log(departement);
              document.getElementById('btn_departement').innerHTML= departement;
            document.getElementById('btn_departement').click();
            },
            error: function(){
              $("#post").html('Une erreur est survenue.');
            }
          });
          
          return true;
        });
      });


      var numeroInput=1;
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


                $(document).ready(function(){
  $(".show_complexe").click(function(){
    $.ajax({type:"POST", data: $(".complexe_id", this).serialize(), url:"nom_complexe_ajax.php",
            success: function(data){
              $("#nom_complexe").html(data);
              //console.log(departement);
            },
            error: function(){
              $("#nom_complexe").html('Une erreur est survenue.');
            }
          });
  }
            </script>
    </head>

    <body>


        <!-- ********************************           début du bandeau            ******************************** --> 


    <?php include("header.php") ?>

        <!-- ********************************           fin du bandeau          ******************************** -->

        <!-- ********************************               début du formulaire             ********************************-->
        
    <h1>Rejoindre un Match</h1>

<script src="bootstrap-select.min.js"></script>

<!-- Button trigger modal -->
<hr/>
    <div class="div_input" data-toggle="modal" data-target="#myModal">
        <div id="nom_complexe">
            <div>
                <span class="glyphicon glyphicon-pushpin"></span>
                <span>Cliquez pour choisir votre complexe </span>
            </div>
        </div> 
    </div>
<hr/>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="myModalLabel">Choix du complexe</h2>
                <div class="dropdown" >
                <form method="post" id="formulaire_general_complexe">
                    <a href="#" id="btn_departement" data-toggle="dropdown">Département <b class="caret"></b></a>
                    <ul class="dropdown-menu dropdown-menu-center scrollable-menu" aria-labelledby="dropdownMenu2">
                        <?php 
                            foreach (listeDepartements() as $dpt_key => $dpt_value){
                                ?>
                                    <li role="presentation">
                                        <label > 
                                            <div class="btn_dpt" role="menuitem" tabindex="-1"> 
                                                <?php echo '('.$dpt_value["dpt_code"].') '.$dpt_value["dpt_nom"]; ?>
                                                <input type="hidden" name="dpt" onclick="clic_dpt();" value="<?php echo $dpt_value['dpt_code']; ?>" class="badgebox input_dpt">
                                            </div>
                                        </label>
                                    </li>
                                <?php
                            }
                        ?>
                    </ul>
                </form>
                </div>
            </div>

            <div class="modal-body scrollable-menu2" id="post">    
            </div>

            <div class="modal-footer center">
                <button id="valider_complexe" onclick="nomducomplexe();" type="button" class="btn btn-default" data-dismiss="modal">Valider</button>
            </div>
        </div>
    </div>
</div>

<div id="post_events_ajax">
    
</div>
        <!-- ********************************           fin du formulaire           ******************************** -->

  </body>
</html>
