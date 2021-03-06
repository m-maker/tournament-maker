<?php
	include "conf.php";

	$dpt = htmlspecialchars(trim($_POST['dpt']));
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
            <hr/>
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

            <hr/>

        <?php
        $i = 0;
        $boucle = 0;
        ?>
        <div class="cont espace-bot" id="cont-all">
            <p> Les matchs et tournois au <?php echo $lieu['lieu_nom']; ?> </p>
            <?php $liste_all = liste_tournois($dpt);
            //var_dump($liste_all);
            foreach ($liste_all as $event){
                $heure_debut = format_heure_minute($event['event_heure_debut']);
                $heure_fin = format_heure_minute($event['event_heure_fin']);
                $glyph = "glyphicon-eye-open";
                $prive = "Public";
                $color = 'vert';
                if ($event['event_prive'] == 1) {
                $color = 'rouge';
                $glyph = "glyphicon-eye-close";
                $prive = "Privé";
                }
                $pay = "<span class='rouge'>Refusé</span>";
                if ($event['event_paiement'] == 1) {
                $pay = "<span class='vert'>Accepté</span>";
                }
                $desc = $event['event_descriptif'];
                if ($event['event_descriptif'] == NULL || empty($event['event_descriptif'])) {
                $desc = 'Pas de description.';
                }
                $team = "par équipe";
                if ($event['event_tarification_equipe'] == 0) {
                $team = "par joueur";
                }
                $date_tournoi = new DateTime($event['event_date']);
                $date_tournoi = date_lettres($date_tournoi->format("w-d-m-Y"));
                $req_orga = $db->prepare('SELECT * FROM membres INNER JOIN evenements ON membres.id = evenements.event_orga_id WHERE evenements.id = :event_id');
                $req_orga->execute(array(
                    'event_id' => $event[0]
                    ));
                //var_dump($req_orga);
                $orga = $req_orga->fetch();
                ?>

                
                <div class="row">
                    <div class="recap_event center <?php if (isset($event['event_tournoi']) && $event['event_tournoi'] == 0){ echo 'match'; } else { echo 'tournoi';}?> container-fluid">
                        <a href="feuille_de_tournois.php?tournoi=<?php echo $event[0]; ?>">
                            <div class="col-sm-3">
                                <p class="">
                                Le <span ><?php echo $date_tournoi; ?></span>
                                <br/>
                                <span class="heure"><?php echo $heure_debut; ?></span> - <span><?php echo $heure_fin; ?></span>
                                </p>
                            </div>
                            <div class="recap_event_titre col-sm-3" >
                                <p class="<?php var_dump($orga);?>">
                                    <span class="glyphicon <?php echo $glyph; ?>"></span>
                                    <span>
                                        <?php 
                                            if (isset($event['event_tournoi']) && $event['event_tournoi'] == 0){ 
                                                echo 'Match ';
                                            }
                                            else {
                                                echo 'Tournoi ';
                                            }
                                            echo $prive.',';
                                        ?> 
                                        <br/>
                                        Oragnisé par <?php echo $orga['membre_pseudo'] ?>.
                                    </span>
                                </p>
                            </div>
                            <div class="prix col-sm-3">
                                <span class=""><?php echo $event['event_tarif']; ?>€
                                <?php echo $team; ?></span>
                            </div>
                            <div class="col-sm-3">
                                <span class="glyphicon glyphicon-user"></span><span class="">
                                    <?php echo compte_equipes($event[0]) . ' / ' . $event['event_nb_equipes']; ?>
                                </span> 
                                <span>
                                    <?php 
                                        if (isset($event['event_tournoi']) && $event['event_tournoi'] == 0){ 
                                            echo 'Joueurs inscrits';
                                        }
                                        else {
                                            echo 'Équipes inscrites';
                                        }
                                    ?>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
            } ?>
        </div>
		<?php foreach ($tab_complexes_events as $onglet => $tab_complexes_nb_events) {
            //var_dump($lieu_id);
            $lieu = recupLieuById($tab_complexes_nb_events[0]);
            $liste_events = liste_tournois_complexe($tab_complexes_nb_events[0]);
            ?>
                <div style="display:none;" id="cont-onglet-<?php echo $tab_complexes_nb_events[0];?>" class="cont <?php if($i == 0){ echo 'cont-event';} ?>">
                    <div class="espace-bot">
                        <?php
                        if (!empty($liste_events)) {
                            foreach ($liste_events as $key => $event) {
                                //var_dump($key);
                                //var_dump($event);
                                $heure_debut = format_heure_minute($event['event_heure_debut']);
                                $heure_fin = format_heure_minute($event['event_heure_fin']);
                                $glyph = "glyphicon-eye-open";
                                $prive = "Public";
                                $color = 'vert';
                                if ($event['event_prive'] == 1) {
                                    $color = 'rouge';
                                    $glyph = "glyphicon-eye-close";
                                    $prive = "Privé";
                                }
                                $pay = "<span class='rouge'>Refusé</span>"; 
                                if ($event['event_paiement'] == 1) {
                                    $pay = "<span class='vert'>Accepté</span>";
                                }
                                $desc = $event['event_descriptif'];
                                if ($event['event_descriptif'] == NULL || empty($event['event_descriptif'])) {
                                    $desc = 'Pas de description.';
                                }
                                $team = "par équipe";
                                if ($event['event_tarification_equipe'] == 0) {
                                    $team = "par joueur";
                                }
                                $date_tournoi = new DateTime($event['event_date']);
                                $date_tournoi = date_lettres($date_tournoi->format("w-d-m-Y"));
                                ?>
                                <div class="">
                                    <div class="recap_event center <?php if (isset($event['event_tournoi']) && $event['event_tournoi'] == 0){ echo 'match'; } else { echo 'tournoi';}?> container-fluid">
                                        <a href="feuille_de_tournois.php?tournoi=<?php echo $event[0]; ?>">
                                            <div class="col-sm-3">
                                                <p class="bold">
                                                Le <span ><?php echo $date_tournoi; ?></span>
                                                <br/>
                                                <span class="heure"><?php echo $heure_debut; ?></span> - <span><?php echo $heure_fin; ?></span>
                                                </p>
                                            </div>
                                            <div class="recap_event_titre col-sm-3" >
                                                <p class="<?php /*echo $color; */?>">
                                                    <span class="glyphicon <?php echo $glyph; ?>"></span>
                                                    <span>
                                                        <?php 
                                                            if (isset($event['event_tournoi']) && $event['event_tournoi'] == 0){ 
                                                                echo 'Match ';
                                                            }
                                                            else {
                                                                echo 'Tournoi ';
                                                            }
                                                            echo $prive.',';
                                                        ?> 
                                                        <br/>
                                                        Oragnisé par <?php echo $orga['membre_pseudo'] ?>.
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="prix col-sm-3">
                                                <span class="bold"><?php echo $event['event_tarif']; ?>€
                                                <?php echo $team; ?></span>
                                            </div>
                                            <div class="col-sm-3">
                                                <span class="glyphicon glyphicon-user"></span><span class="bold">
                                                    <?php echo compte_equipes($event[0]) . ' / ' . $event['event_nb_equipes']; ?>
                                                </span> 
                                                <span>
                                                    <?php 
                                                        if (isset($event['event_tournoi']) && $event['event_tournoi'] == 0){ 
                                                            echo 'Joueurs inscrits';
                                                        }
                                                        else {
                                                            echo 'Équipes inscrites';
                                                        }
                                                    ?>
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                        else{ 
                            ?>
                                <div class="center" style="color: black; margin-top: 70px;">
                                    <h3>Il n'y a pas de tournois dans ce complexe pour le moment.</h3>
                                </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

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
    		<?php
            $boucle = 1;
    	}
    ?>