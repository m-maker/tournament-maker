<div class="bandeau">

	<div class="dropdown element_bandeau ">
		<div>
		<a href="#" data-toggle="dropdown" >
		  <span class="glyphicon glyphicon-menu-hamburger"></span>
		</a>
		</div>
		<ul class="dropdown-menu dropdown-backdrop menu_liste" aria-labelledby="dropdownMenu2">
			<li><a href="mes_matchs.php">Mes matchs</a></li>
		    <li><a href="#">Paramètres</a></li>
		    <li><a href="gestion_orga.php">Gerer mes tournois</a></li>
		    <li><a href="deconnexion.php">Déconnexion</a></li>
		</ul>
	</div>

	<div class="dropdown element_bandeau ">
	<div class="retour_bandeau">
		<a href="index.php">
			<span class="glyphicon glyphicon-home"></span>
		</a>
	</div>
	</div>

	<div class="logo_bandeau element_bandeau">
		<img src="logo.png" alt="RTT">
	</div>

	<?php 
		if (isset($_SESSION['id'])){
			if (!empty($_SESSION['id'])){
				?>
					<div class="dropdown element_bandeau ">
						<a>
							<span href="#" class="glyphicon glyphicon-bell"></span>
						</a>
					</div>
				<?php
			}
		}

		if (isset($_SESSION['id'])){
			if($_SESSION['membre_orga'] == 1){
				?>
				<div class="dropdown element_bandeau ">
					<div>
						<a href="organiser_tournoi.php" class="btn btn-success">
						Créer un tournois
						</a>
					</div>
				</div>
				<?php
			}
		}

		if(!isset($_SESSION['id'])){
			?>
				<div>
					<a href="connexion.php" class="btn btn-success">Connexion</a>
				</div>
			<?php
		}
	?>
</div>