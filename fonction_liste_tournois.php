<?php
	function liste_tournois($dpt){
		global $db;
		$req_liste_tournois = $bdd->prepare('SELECT * FROM tournois INNER JOIN lieux ON tournois.event_lieu = lieux.lieu_id WHERE lieu_dpt_id = :departement_id');
		$req_liste_tournois->execute(array(
			'departement_id' => $dpt
			));
		return $req_liste_tournois->fetch();
	}

	foreach ($un_tournoi AS liste_tournois($dpt)){
		?>
			<div>
				<div class="header_tournoi">
					<?php echo $un_tournoi['event_titre']; ?>
				</div>
				<div class="body_tournoi">
					<div class="logo_tournoi">
						<img class="img-responsive" src='images/<?php echo $un_tournoi['event_img'];?>' alt="Tournoi">
					</div>
					<div class="corps_tournois">
						<h2><? echo $un_tournoi['event_date'];?></h2>
						<p><? echo $un_tournois['event_heure_debut']->format('H:m').'-'$un_tournois['event_heure_fin']->format('H:m'); ?></p>
						<h2><?php echo $un_tournois['lieu_nom'];?></h2>
					</div>
					<div class="info_tournoi">
						<p><span class="glyphicon glyphicon-euro">Prix</span><span><?php echo $un_tournois['event_tarif']; ?></span></p>
						<p><span class="glyphicon glyphicon-calendar"> Durée</span><?php echo $un_tournois['event_nb_heure_jeu']; ?></p>
						<p><span class="glyphicon glyphicon-user"> Nombre d'équipes</span><?php echo $un_tournois['event_nb_equipe']; ?></p>
						<!-- statut -->
					</div>
				</div>
			</div>
		<?php
	}
?>