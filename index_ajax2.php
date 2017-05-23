<?php
	include "conf.php";

	$dpt = $_POST['dpt'];
	$liste_tournois = liste_tournois($dpt);

	global $res_dpt_from_liste_tournois;

	if(isset($_SESSION['id'])){
		$user = $db->prepare('SELECT * FROM membres WHERE membre_id = :membre_id');
		$user->execute(array(
			'membre_id' => $_SESSION['id']
		));
	}

	$req_dpt = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
	$req_dpt->execute(array(
		'dpt_code' => $dpt
		));
	$res_dpt = $req_dpt->fetch();

	$liste_complexes = liste_lieux($res_dpt['dpt_id']);

	foreach ($liste_complexes as $key => $value) {
		//var_dump($key);
		//var_dump($value);
		$req_nb_events = $db->prepare('SELECT COUNT(event_id) FROM tournois WHERE event_lieu = :event_lieu');
		$req_nb_events->execute(array(
			'event_lieu' => $value['lieu_id']
			));
		$res_nb_events = $req_nb_events->fetchColumn();
		$tab_complexes_events = array( $value['lieu_id'] => $res_nb_events);
		//var_dump($tab_complexes_events);
		$req_nb_events->closeCursor();
	}
	arsort($tab_complexes_events, SORT_NUMERIC);
	//var_dump($tab_complexes_events); 
?>		
		<button id="btn_dpt" class="btn btn-success" data-toggle="modal" data-target="#myModal">
			<div id="nom_departement" > <?php echo $res_dpt['dpt_nom']; ?>  <b class="caret"></b> </div>
		</button>

 		<div id="menu_liste_complexes" style="margin: 1%;">
			<?php 
				foreach ($tab_complexes_events as $lieu_id => $nb_events) {
					$lieu = recupLieuById($lieu_id);
					?>
	      				<a class="onglet_complexe" data-toggle="tab" href="#<?php echo $lieu['lieu_nom'];?>"><?php echo $lieu['lieu_nom'].' ('.$nb_events.') '; ?></a>
	      			<?php
	      		}
	      	?>
	    </div>

<div id="liste_events" class="tab-content">
	<?php 
		foreach ($tab_complexes_events as $lieu_id => $nb_events) {
			$lieu = recupLieuById($lieu_id);
			$liste_events = liste_tournois_complexe($lieu_id);
			//var_dump($lieu_id);
			//var_dump($liste_events);
			?>
    			<div id="<?php echo $lieu['lieu_id'];?>" class="tab-pane fade in active">
    			<?php
    				foreach ($liste_events as $key => $event) {
                        $heure_debut = format_heure_minute($event['event_heure_debut']);
                        $heure_fin = format_heure_minute($event['event_heure_fin']);
                        $glyph = "glyphicon-eye-open";$prive="Public";$color='vert';
                        if ($event['event_prive'] == 1){$color='rouge';$glyph = "glyphicon-eye-close";$prive="Privé";}
                        $pay = "<span class='rouge'>Refusé</span>";
                        if ($event['event_paiement'] == 1){$pay="<span class='vert'>Accepté</span>";}
                        $desc = $event['event_descriptif'];
                        if ($event['event_descriptif'] == NULL || empty($event['event_descriptif']))
                            $desc = 'Pas de description.';
                        $team = "par équipe";
                        if ($event['event_tarification_equipe'] == 0){$team="par joueur";}

                        echo "<div class='titre-liste-tournoi'>
                            " . $event['event_titre'] . "<br>
                            <p style='font-size: 15px;'>
                                <span class=\"glyphicon glyphicon-calendar\"></span> Le <span class=\"bold\">" . $event['event_date'] . "</span> de
                                <span class=\"bold\">" . $heure_debut . "</span> à <span class=\"bold\">" .$heure_fin . "</span>
                            </p>
                        </div>";

                        ?>

                        <div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
                            <div class="row">

                                <div class="col-lg-4" style="text-align: left;">
                                    <p><span class="glyphicon glyphicon-home"></span> Nom du complexe : <span class="bold"><?php echo $lieu["lieu_nom"];?></span></p>
                                    <p><span class="glyphicon glyphicon-euro"></span> Paiement en ligne : <span class="bold"> <?php echo $pay; ?></span></p>
                                    <p><span class="glyphicon glyphicon-user"></span><span class="bold"> <?php echo compte_equipes($event['event_id']) . ' / ' . $event['event_nb_equipes']; ?></span> équipes inscrites</p>
                                </div>
                                <div class="col-lg-5 espace-top" style="text-align: left;">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    <?php
                                    if (strlen($desc) > 120) {
                                        echo substr($desc, 0, 120)  . '...';
                                    }else{
                                        echo $desc;
                                    } ?>
                                </div>
                                <div class="col-lg-3 prix-team">
                                    <h1 style="margin-top: 1.5%;"><span class="bold"><?php echo $event['event_tarif'] + $param->comission; ?> €</span></h1> <?php ECHO $team; ?><br />
                                    <p class="<?php echo $color; ?>"><span class="glyphicon <?php echo $glyph; ?>"></span> Tournoi <?php echo $prive; ?></p>
                                </div>

                            </div>
                        </div>
							<?php
    				}
    			?>
    			</div>
    		<?php
    	}
    ?>
</div>
			