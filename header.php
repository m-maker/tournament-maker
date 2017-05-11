<div class="bandeau">

	<div class="dropdown element_bandeau ">
	<div class="cercle">
		<a href="#" data-toggle="dropdown" >
		  <span class="glyphicon glyphicon-menu-hamburger encercle"></span>
		</a>
		<ul class="dropdown-menu menu_liste" aria-labelledby="dropdownMenu2">
			<li><a href="mes_matchs.php">Mes matchs</a></li>
		    <li><a href="#">Paramètres</a></li>
		    <li><a href="#">Mes collègues</a></li>
		    <li><a href="deconnexion.php">Déconnexion</a></li>
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
							<span href="#" class="glyphicon glyphicon-bell encercle"></span>
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
						<button class="btn btn-success">
						Créer un tournois
						</button>
					</div>
				</div>
				<?php
			}
		}
	?>
</div>