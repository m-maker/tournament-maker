<?php
	function liste_tournois($dpt){
		global $db;
		$req_liste_tournois = $bdd->prepare('SELECT * FROM tournois INNER JOIN lieux ON tournois.event_lieu = lieux.lieu_id WHERE lieu_dpt_id = :departement_id');
		$req_liste_tournois->execute(array(
			'departement_id' => $dpt
			));

		while ($res_liste_tournois = $req_liste_tournois->fetch()){
			?>
				<div>
					<div class="header_tournoi">
						<?php echo $res_liste_tournois['event_titre']; ?>
					</div>
					<div class="body_tournoi">
						<div class="logo_tournoi">
							<img class="img-responsive" src='images/<?php echo $res_liste_tournois['img'];?>' alt="Tournoi">
						</div>
						<div class="corps_tournois">
							<
						</div>	
				</div>
		}	
	}
?>