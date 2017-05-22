<?php
    include '../conf.php';
    if ($_SESSION['membre_orga'] == 0)
        header("Location: ../index.php");
    $liste_comptes = recupCompteOrga($_SESSION["id"]);
?>	
<html>
	
	<head>
		<?php include('head.php'); ?>
		<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
		<link rel="stylesheet" href="../css/jquery_perso.css">
		<link rel="stylesheet" type="text/css" href="../css/organiser_tournoi.css">

		<title>Organiser un tournoi</title>
  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script type="text/javascript" src="../js/datepicker.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

	</head>

	<body>

    <?php include 'header.php'; ?>

	<div class="row">
			<div class="form-grand">

				<div class="container" id="container">
                    <div style="background: #f5f5f5;">

						<form enctype="multipart/form-data" class="form-horizontal form-grand" method="post" action="organiser_tournoi_traitement.php">
				  			<fieldset>

				    			<legend class="bold center" id="titre-form">Organiser un tournoi</legend>

				    			<hr>
				    			<div class="form-group form-group-sm center">
				    				<p>Coordonnées</p>
						        	<input type="text" class="form-control" id="input_event_titre" name="event_titre" placeholder="Nom du tournoi">
						        	<br/>
                                    <?php ?>
						        	<input type="text" class="form-control" data-provide="typeahead" id="input_event_lieu_nom" name="event_lieu_nom" placeholder="Nom du lieu qui accueil le tournoi">
                                    <div id="autocomplete" style="position: absolute; display: none; color: black; background: white; border: 1px solid black;">
                                    </div>
						        	<input type="text" class="form-control" id="input_event_adresse" name="event_adresse" placeholder="Adresse du tournoi">
						        	<div class="ligne">
							        	<input type="text" class="form-control" id="input_event_code_postal" name="event_code_postal" placeholder="Code Postal">
							        	<input type="text" class="form-control" id="input_event_ville" name="event_ville" placeholder="Ville">
							        </div>
						    	</div>

						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<!--<div class="center">
						    			<p>Horaires</p>
						    		</div>-->
							    	<div class="ligne espacer">
							    		<div>
							      			<p> Date du tournoi </p>
							  		  		<div class="form-group has-feedback">
    											<label class="control-label">Date</label>
   												<input readonly class="form-control" type="text" name="event_date" id="datepicker">
    											<i class="glyphicon glyphicon-calendar form-control-feedback"></i>
											</div>
										</div>
							    		<div class="sous-section">
							    			<p>Heure du début</p>
							    			<div class="ligne">
									    		<div>
    												<label class="control-label">Heures</label>
									    			<select class="form-control" id="input_heure_debut" name="heure_debut">
									    				<?php
									    					for ($i=0; $i<25 ; $i++) { 			    						
											    				if ($i<10){
											    					$heure = '0'.$i;
											    				}
										 						else {
										   							$heure = $i;
										    					}
											    				?>
											    					<option value='<?php echo $heure; ?>'>
																		<?php echo $heure.'H'; ?>
									    							</option>
									    						<?php
									    					}
									    				?>
									    			</select>
									    		</div>
									    		<div>
    												<label class="control-label">Minutes</label>
													<select class="form-control" id="input_minute_debut" name="minute_debut">
											    			<option value="00">00 min</option>
											   				<option value="15">15 min</option>	
											   				<option value="30">30 min</option>	
											   				<option value="45">45 min</option>	
									    			</select>
									   			</div>
									   		</div>
										</div>
										<div id="fin_tounoi">
					    					<div>
							    				<p>Heure de fin</p>
							    			</div>
							   				<div class="ligne">
								    			<div>
    												<label class="control-label">Heures</label>								
    													<select class="form-control" id="input_heure_fin" name="heure_fin">
										    			<?php
										    			for ($i=0; $i<25 ; $i++) { 
										    				if ($i<10){
																$heure = '0'.$i;
										    				}
										    				else {
										    					$heure = $i;
										    				}
									    					?>
									    						<option value='<?php echo $heure; ?>'>
									    							<?php echo $heure; ?>
									    						</option>
									    					<?php
									    				}
									    				?>
									    			</select>
									   			</div>
									    		<div>
    												<label class="control-label">Minutes</label>
									    			<select class="form-control" id="input_minute_fin" name="minute_fin">
									    				<option value="00">00 min</option>
									    				<option value="15">15 min</option>	
									    				<option value="30">30 min</option>	
									    				<option value="45">45 min</option>	
									    			</select>
								   				</div>
								   			</div>
							 			</div>
					    			</div>
							   	</div>
							    <hr>

						    	<div class="form-group form-group-sm center">
						    	  	<!--<p>Participants</p>-->
						    	  	<div class="ligne espacer">
							   	  		<div>
							   	  			<p>Nombre d'équipes</p>
							   	  			<select class="form-control" id="input_event_nb_equipes" name="event_nb_equipes">
							   	  				<?php
						    	  					for ($i=1; $i<33; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>'><?php echo $i.' équipes'; ?></option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  		<div>
						    	  			<p>Nombre de joueurs min.</p>
						    	  			<select class="form-control" id="input_event_joueurs_min" name="event_joueurs_min">
						    	  				<?php
						    	  					for ($i=0; $i<8; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>'><?php echo $i.' joueurs minimum'; ?></option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  		<div>
						    	  			<p>Nombre de joueurs max.</p>
						    	  			<select class="form-control" id="input_event_joueurs_max" name="event_joueurs_max">
						    	  				<?php
						    	  					for ($i=5; $i<10; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>'><?php echo $i.' joueurs maximum'; ?></option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  	</div>
						     	</div>

						    	
						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<p>Paiement</p>
						    		<label style="margin-right: 2%;" for="radio_equipe">Prix par équipe
						    		<input id="radio_equipe" type="radio" name="event_tarification_equipe" value="1">
                                    </label>
						    		<label for="radio_joueur">Prix par joueur
						    		<input id="radio_joueur" type="radio" name="event_tarification_equipe" value="0" checked="checked">
                                    </label>
						    		<div id="tarif" class="ligne"> 
							    		<select class="form-control" name="tarif">
							    			<?php
							     				for ($i=0; $i < 60; $i=$i+0.5) { 
							    					?>
							    						<option value='<?php echo $i; ?>'>
							    							<?php echo $i.' € '; ?>
							    						</option>
							    					<?php
							    				}
							    			?>
							    		</select>
							    	</div>
							    	<div>
							    		<br/>
								    	<p>Notre service permet de gérer les encaissements des joueurs directement depuis la plateforme. Souhaitez-vous en profiter?</p>
							    		<input class="pay-clic" id="paiement_ok" type="radio" name="paiement" value="1" checked="checked">
							    		<label style="margin-right: 2%;"' for="paiement_ok">Oui, ça m'enlève une grosse épine du pied</label>
							    		<input class="pay-clic" id="paiement_refus" type="radio" name="paiement" value="0">
							    		<label for="paiement_refus">Non merci, j'ai beaucoup de courage!</label>
						    		</div>

						    		<div id="section-rib" class="espace-top">
                                        <div class="espace-bot">
                                            Selectionnez un compte :
                                            <select name="select-compte" id="select-compte" class="form-control">
                                                <option value="new" id="opt-new">Nouveau compte..</option>
                                                <?php foreach ($liste_comptes as $unCompte){ ?>
                                                    <option value="<?php echo $unCompte["compte_id"]; ?>"><?php echo $unCompte['compte_nom'] . ' ' . $unCompte["compte_prenom"] . ' - ' . $unCompte['compte_rib_iban']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div id="new-compte">
                                            <label for="input_event_rib ">Merci de saisir les informations suivantes afin de recueillir les fonds de votre tournoi :</label>
                                            <div class="ligne">
                                                <input type="text" class="form-control" id="input_compte_nom" name="compte_nom" placeholder="Nom du titulaire du compte">
                                                <input type="text" class="form-control" id="input_compte_prenom" name="compte_prenom" placeholder="Prénom du titulaire du compte">
                                            </div>
                                            <input type="text" class="form-control" id=input_compte_adresse_l1" name="compte_adresse" placeholder="Adresse du titulaire du compte">
                                            <input type="text" class="form-control" id="input_compte_adresse_l2" name="compte_adresse_2" placeholder="Complément d'adresse (Optionnel)">
                                            <div class="ligne">
                                                <input type="text" class="form-control" id="input_compte_code_postal" name="compte_cp" placeholder="Code Postal du titulaire du compte">
                                                <input type="text" class="form-control" id="input_compte_ville" name="compte_ville" placeholder="Ville du tutulaire du compte">
                                            </div>
                                            <input type="text" class="form-control" id="input_event_rib" name="compte_rib_bic" placeholder="Code BIC : DAAEFRPP (Optionnel)">
                                            <input type="text" class="form-control" id="input_event_rib_iban" name="compte_rib_iban" placeholder="Code IBAN : FR763XXXXXXXXXXX4567890185">
                                        </div>
                                    </div>
						    	</div>
						    	
						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<p>Descriptif</p>
                                    <textarea class="form-control" name="event_descriptif" rows="3" placeholder="Match organisé par ADN-five, tous niveaux acceptés et super ambiance"></textarea>
						    		<!-- LOGO -->
                                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                    <label for="file" class="label-file">Choisir une icone pour le tournoi</label>
                                    <input type="file" name="icone" id="file" style="display: none;"/>
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

						    	<div class="form-group form-group-sm center">
				    				<button type="submit" name="submit" class="btn btn-success btn-grand espace-top">Ajouter ce tournoi</button>
					    		</div>
				    		</fieldset>
				    	</form>
				    </div>
				</div>
            </div>
		</div>

    <?php include "footer.php"; ?>

    <script src="../js/typeahead.min.js"></script>

    <script type="text/javascript">
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

        $("#select-compte").change(function () {
            var opt_select = $("#select-compte option:selected").val();
            console.log(opt_select);
           if (opt_select == "new")
               $("#new-compte").show();
           else
               $("#new-compte").hide();
        });

        $('.pay-clic').click(function () {
           var id = $(this).attr("id");
           var section_rib = $('#section-rib')
           if (id == "paiement_ok")
               section_rib.show();
           else {
               $('#input_event_rib_iban').val('');
               $('#input_event_rib').val('');
               $('#input_compte_nom').val('');
               $('#input_compte_prenom').val('');
               $('#input_compte_adresse_l1').val('');
               $('#input_compte_adresse_l2').val('');
               $('#input_compte_code_postal').val('');
               $('#input_compte_ville').val('');
               document.getElementById('select-compte').selectedIndex = 0;
               $('#new-compte').show();
               section_rib.hide();
           }
        });

        $('#input_event_lieu_nom').autocomplete({
            source : 'recup_autocomplete.php?nom=' + $('#input_event_lieu_nom').val()
        });

        $('#input_event_lieu_nom').focusout(function () {
            var txt = $('#input_event_lieu_nom').val();

            $.get('recup_autocomplete.php?champ=ville&nom=' + txt, function(data) {
                $("#input_event_ville").val(data);
            });
            $.get('recup_autocomplete.php?champ=cp&nom=' + txt, function(data) {
                $("#input_event_code_postal").val(data);
            });
            $.get('recup_autocomplete.php?champ=adresse&nom=' + txt, function(data) {
                $("#input_event_adresse").val(data);
            });
        })

    </script>

	</body>
</html>
