<div class="bandeau">

	<div class="element_bandeau">
		<div class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <span class="glyphicon glyphicon-menu-hamburger"></span>
		</a>
		<ul class="dropdown-menu  menu_liste" aria-labelledby="dropdownMenu2">
            <?php if (isset($_SESSION["id"])) {
                if ($_SESSION["membre_orga"] == 1) { ?>
                    <li><a href="organisateur">Section Organisateur</a></li>
                <?php } ?>
                <li><a href="mes_matchs.php">Mes Tournois</a></li>
                <li><a href="#">Paramètres</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            <?php }else{ ?>
                <li><a href="connexion.php">Connexion</a></li>
            <?php }?>

		</ul>
        </div>
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
				<div class="element_bandeau ">
					<div>
						<a href="organisateur/organiser_tournoi.php" class="btn btn-success">
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