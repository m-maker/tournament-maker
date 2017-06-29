<?php
include ('../conf.php');
include('head.php');

if ($_SESSION["membre_orga"] != 1){
	header("Location: ../index.php");
}
if (!isset($_SESSION["id"])){
	header("Location: ../connexion.php");
}

$liste_comptes = recupCompteOrga($_SESSION["id"]);
$liste_tournois = liste_tournois_orga($_SESSION["id"]);

?>


    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
});
</script>
<?php
    $_SESSION['gerant_lieu_id'] = 1;
    $format = 'Y-m-d';
	// $parametres_fonction = $_POST['nom_fonction'];
	$nom_fonction = "planning_complexe";
	$parametres_fonction;
	// $parametres_fonction = $_POST['parametres_fonction'];
	if (!isset($_POST['gerant_lieu_id']) AND !isset($_SESSION['gerant_lieu_id'])){
		header('locaton:index.php');
	}
	elseif (isset($_POST['gerant_lieu_id'])){
		$_SESSION['gerant_lieu_id'] = $_POST['gerant_lieu_id'];
	}
	elseif (!isset($_SESSION['gerant_lieu_id'])){
		location('index.php');
	}
		
	if ($nom_fonction = "planning_complexe"){

		$parametres_fonction['lieu_id'] = $_SESSION['gerant_lieu_id'];
		$heure_min = 10;
		$heure_max = 23.0;
		if (isset($_POST['jour'])){
			$date_min = DateTime::createFromFormat('Y-m-j', $_POST['jour']);
		}
		else{
			$date_min = new DateTime;
		}
		$date_max = clone($date_min);
		$date_max->add( new DateInterval('P0D'));

		// on récupère un tableau avec la liste des terrains.
		$req_terrains = $db->prepare('SELECT * FROM terrains WHERE terrain_lieu_id = :lieu_id');
		$req_terrains->execute(array(
			'lieu_id' => $parametres_fonction['lieu_id']
			));
		$res_terrains = $req_terrains->fetchAll();
		$nb_terrain = count($res_terrains);

		// Pour chaque terrain, on y associes ses créneaux.
		foreach ($res_terrains as $key => $val) {

			// Récupération des créneaux pour un terrain.
			$req_liste_creneaux = $db->prepare('SELECT * FROM creneaux WHERE creneau_terrain_id = :terrain_id');
			$req_liste_creneaux->execute(array(
				'terrain_id' => $val['id']
				));
			$res_liste_creneaux = $req_liste_creneaux->fetchAll();

			// Ajout du tableau des créneaux dans le tableau des terrains
			$res_terrains[$key]['creneaux'] = $res_liste_creneaux;
			//on récupère la liste des events et on l'ajoute au tableau des terrains
			$liste_event_terrain = liste_event_terrain($val['id']);
			$res_terrains[$key]['liste_event'] = $liste_event_terrain;

			// On génère un champ créneaux_morts contenant toutes les demi-heure à ne pas afficher à l'écran car elles sont regroupées dans un event. ex: 19h00-21h00 -> 1 crénau (19h00-19h30) + 3 créneaux morts
			foreach ($res_liste_creneaux as $key_creneau => $value_creneau) {
				if ($value_creneau['creneau_statut_id'] == 2){
					$date_debut = DateTime::createFromFormat('Y-m-j H:i:s', $value_creneau['creneau_datetime']);
					$date_debut->add( new DateInterval ('PT30M'));
					$date_fin =	DateTime::createFromFormat('Y-m-j H:i:s', $value_creneau['creneau_datetime_fin']);
					while ($date_debut < $date_fin){
						$liste_creneaux_morts[$date_debut->format('Y-m-j H:i:s')] = 1;
						$date_debut->add( new DateInterval ('PT30M'));
					}
				}
				
			}
			if (isset($liste_creneaux_morts)){
				$res_terrains[$key]['creneaux_morts'] = $liste_creneaux_morts;
				unset($liste_creneaux_morts);
			}

		}
	}
	?>
		    <div class="tableau"> 
				<table>
					<?php 
						entete_complexe($date_min, $date_max,  $parametres_fonction['lieu_id'], $res_terrains);
						//$nom_fonction($date_min, $date_max, $lieu_id, $res_nb_terrains);
						for ($heure = $heure_min; $heure < $heure_max; $heure= $heure + 0.5) {
							?>
								<tr>
									<?php
										$jour = clone($date_min);								
										while ($jour <= $date_max){
											if ( intval($heure) == $heure){
												$minutes = "00";
											}
											else{
												$minutes = "30";
											}
											$datetime_string = $jour->format('Y-n-j').' '.intval($heure).':'.$minutes.':00';
											$date_case = DateTime::createFromFormat('Y-n-j H:i:s', $datetime_string);
											//$nom_fonction($parametres_fonction);$datetime_string = '2017-06-23 17:00:00';
											$date_case_string = $date_case->format('Y-m-j H:i:s');
											case_complexe($date_case_string, $res_terrains);
											unset($date_case);
											$jour->add( new DateInterval('P1D'));
										}
										unset($jour);
									?>
								</tr>
							<?php
						} 
					?>
				</table>
			</div>
		</div>
        
    </div>
</div>








<div class="modal fade" id="modal_form_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<form action="planning_organiser_event_traitement.php" method="post">
	<input type="hidden" id="input_event_date" name="event_date" value="">
	<input type="hidden" id="input_heure_debut" name="heure_debut" value="">
	<input type="hidden" id="input_terrain_id" name="terrain_id" value="">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h2 class="modal-title" id="myModalLabel"></h2>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	</div>
        	<div class="modal-body">

				<div id="div_type_event">
						<select class="form-control" style="padding: 1px; height: 20px; margin: auto;" id="type_event" name="type_event">
							<option value ="match">Match ouvert à tous</option>
							<option value ="rencontre">Rencontre d'équipes</option>
							<!-- <option value ="libre">Ouvrir le créneau pour les réservations</option> -->
						</select>
					</span>
				</div>

				<div class="modal_horaire">
					<hr/>
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-6">
								<span>Heure de début:  </span> <br/>
								<span id="modal_heure_debut"></span>
							</div>
							<div class="col-sm-6">
								<span>Heure de fin: </span> <br/>
								<select class="form-control" id="input_heure_fin_match" style="padding: 1px; height: 20px;" name="modal_heure_fin">
									<?php
										for ($i=8; $i <= 24; $i = $i +0.5) {
											if (intval($i) == $i){
												if ($i < 10){
													$modal_heure_fin = '0'.$i.':00';
												}	
												else{
													$modal_heure_fin = $i.':00';
												}
											}
											else{
												if ($i < 10){
													$modal_heure_fin = '0'.intval($i).':30';
												}	
												else{
													$modal_heure_fin = intval($i).':30';
												}	
											}

											?>
												<option value="<?php echo $modal_heure_fin; ?>"> <?php echo $modal_heure_fin; ?> </option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<hr/>

				<div id="modal_effectif">
					<div id="modal_effectif_match" class="container-fluid affiche">
						<div class="row">
							<div class="col-sm-6">
								<span>Nombre de joueurs requis: </span><br/>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="joueurs_requis">
										<option value="8"> 8 </option>
										<option value="10"> 10 </option>
										<option value="12"> 12 </option>
									</select>
								</span>
							</div>
							<div class="col-sm-6">
								<span>Nombre de joueurs déjà présents: </span>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="joueurs_presents">
										<?php
											for ($i=0; $i <= 12 ; $i++) { 
												?> 
													<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
												<?php

											}
										?>
									</select>		
								</span>
							</div>
						</div>
					</div>

					<div id="modal_effectif_rencontre" class="container-fluid" style="display: none;">
						<div class="row">
							<div class="col-sm-6">
								<span>Nombre d'équipes maximum: </span> <br/><br/>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="nb_equipes">
										<?php
											for ($i=2; $i <= 16 ; $i++) { 
												?> 
													<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
												<?php
											}
										?>
									</select>
								</span>
							</div>
							<div class="col-sm-6">
								<span>Nombre de joueurs minimum par équipe: </span><br/>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="joueurs_equipe_min">
										<?php
											for ($i=0; $i <= 7 ; $i++) { 
												?> 
													<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
												<?php
											}
										?>
									</select>
								</span>
							</div>
						</div>
					</div>
				</div>

				<hr/>

				<div id="modal_prix" class="container-fluid">
				<div class="row">
					<div class="col-sm-6">
						<label style="margin-right: 2%;" for="radio_equipe">Prix par équipe
			    			<input id="radio_equipe" type="radio" name="event_tarification_equipe" value="1">
                        </label>
                        <br/>
						<label for="radio_joueur">Prix par joueur
							<input id="radio_joueur" type="radio" name="event_tarification_equipe" value="0" checked="checked">
	                    </label>
					</div>
					<div id="tarif" class="col-sm-6"> 
						<select class="form-control" style="padding: 1px; height: 20px;" name="tarif">
			    			<?php
							    for ($i=5; $i < 60; $i=$i+0.5) { 
									?>
							    	<option value='<?php echo $i; ?>'>
											<?php echo $i.' € '; ?>
							    		</option>
									<?php
							    }
							?>
						</select>
					</div>
				</div>
				</div>
				<hr/>

				<div id="cb" class="container-fluid">
					<span>Paiement en ligne: </span>
					<input class="pay-clic" id="paiement_ok" type="radio" name="paiement" value="1" checked="checked">
					<label style="margin-right: 2%;"' for="paiement_ok">Oui</label>
					<input class="pay-clic" id="paiement_refus" type="radio" name="paiement" value="0">
					<label for="paiement_refus">Non</label>
				</div>
				<div id="section-rib_match" class="espace-top" >
                    <div class="espace-bot">
                        Selectionnez un compte :
                        <select style="padding: 1px; height: 20px;" name="select-compte" id="select-compte-match" class="form-control">
                          	<?php 
                          		foreach ($liste_comptes as $unCompte){ 
                           			?>
                       				<option value="<?php echo $unCompte["compte_id"]; ?>"><?php echo $unCompte['compte_nom'] . ' ' . $unCompte["compte_prenom"] . ' - ' . $unCompte['compte_rib_iban']; ?></option>
                                    <?php 
                                }
                            ?>
                        	<option value="new" id="opt-new-match">Nouveau compte..</option>
                        </select>
                    </div>
                                        <div id="new-compte-match" style="display: none;">
                                            <label for="input_event_rib ">Création d'un nouveau compte</label>
                                            <div class="ligne">
                                                <input type="text" class="form-control" style="padding: 1px; height: 20px;" id="input_compte_nom_match" name="compte_nom" placeholder="Nom du titulaire du compte">
                                                <input type="text" class="form-control" style="padding: 1px; height: 20px;" id="input_compte_prenom_match" name="compte_prenom" placeholder="Prénom du titulaire du compte">
                                            </div>
                                            <div class="ligne">
                                                <select class="form-control align-select" style="padding: 1px; height: 20px;" name="jour">
                                                    <optgroup label="Jour de naissance"></optgroup>
                                                    <?php for ($i = 1; $i < 31; $i++) { ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control align-select" name="mois" placeholder="Mois">
                                                    <optgroup label="Mois de naissance"></optgroup>
                                                    <?php foreach ($tab_dates as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value ?></option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control align-select" name="annee" placeholder="Année">
                                                    <optgroup label="Année de naissance"></optgroup>
                                                    <?php for ($i = date("Y") - 19; $i > date("Y") - 100; $i--) { ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <input type="text" class="form-control" id=input_compte_adresse_l1_match" name="compte_adresse" placeholder="Adresse du titulaire du compte">
                                            <input type="text" class="form-control" id="input_compte_adresse_l2_match" name="compte_adresse_2" placeholder="Complément d'adresse (Optionnel)">
                                            <div class="ligne">
                                                <input type="text" class="form-control" id="input_compte_code_postal_match" name="compte_cp" placeholder="Code Postal du titulaire du compte">
                                                <input type="text" class="form-control" id="input_compte_ville_match" name="compte_ville" placeholder="Ville du tutulaire du compte">
                                            </div>
                                            <input type="text" class="form-control" id="input_event_rib_match" name="compte_rib_bic" placeholder="Code BIC : DAAEFRPP (Optionnel)">
                                            <input type="text" class="form-control" id="input_event_rib_iban_match" name="compte_rib_iban" placeholder="Code IBAN : FR763XXXXXXXXXXX4567890185">
                                        </div>
                                    </div>
				<hr/>
				
				<div class="form-group center">
		    		<p>Descriptif</p>
                    <textarea class="form-control" name="event_descriptif" rows="3" style="width: 80%; margin: auto;" placeholder="Match organisé par ADN-five, tous niveaux acceptés et super ambiance"></textarea>
			  		<!-- LOGO -->
                   	<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                    <!--<label for="file" class="label-file">Choisir une icone pour le tournoi</label>
                    <input type="file" name="icone" id="file" style="display: none;"/>-->
				</div>

						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<p>Inscription</p>
							    	<input id="match_public" class="clic-radio" type="radio" name="restriction" value="0" checked="checked">
							    	<label for="match_public">Match publique</label>
							   		<input id="match_prive" class="clic-radio" type="radio" name="restriction" value="1">
							   		<label for="match_prive">Match privé (avec mot de passe)</label>
							   		<br/>
							   		<input style="display: none;" type="text" class="form-control" id="input_event_pass" name="event_pass" placeholder="Mot de passe. ex:******">
						    	</div>


	<input type="submit" name="">

			</div>
		</div>

	</div>

	</form>
</div>


<?php
	function planning_popover(){
		?>
			<div>
				<span> De XX:HH à <input type="time" name="heure_fin"></span>
			</div>
		<?php
		
	}
?>	


        <!-- FOOTER -->
		<?php include('footer.php') ?>
<script type="text/javascript">

$(document).ready(function(){
  $("#jour option").click(function(){
    $.ajax({type:"POST", data: $("#jour").serialize(), url:"planning_ajax.php",
      success: function(data){
        $("#post_planning").html(data);
      },
      error: function(){
        $("#post_planning").html('Une erreur est survenue.');
      }
    });
  });
});

$(".clic-radio").click(function () {
    var id = $(this).attr('id');
    var input_pass = $("#input_event_pass");
    if (id === "match_prive")
        input_pass.show();
    else{
        input_pass.val("");
        input_pass.hide();
    }
});
var heure_debut;

$(".boutton").click(function() {
	heure_debut = $(".creneau_heure_debut", this).attr("value");
	$("#modal_heure_debut").html(heure_debut);
	$('#input_heure_debut').val(heure_debut);
	date = $('#jour').val();
	$('#input_event_date').val(date);
	terrain_id = $(".creneau_terrain_id", this).attr("value");
	$('#input_terrain_id').val(terrain_id);
});

$("#type_event option").click(function(){
	if ($(this).val() == "rencontre"){
		$("#modal_effectif_match").hide();
		$("#modal_effectif_rencontre").show();
	}
	else{
		$("#modal_effectif_match").show();
		$("#modal_effectif_rencontre").hide();
	}
});

$("#cb input").click(function(){
	if ($(this).val() == "1"){
		$("#section-rib_match").show();
	}
	else{
		$("#section-rib_match").hide();
	}
});

$("#select-compte-match option").click(function(){
	if ($(this).val() == "new"){
		$("#new-compte-match").show();
	}
	else{
		$("#new-compte-match").hide();
	}
});

</script>

