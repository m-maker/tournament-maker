<div class="bandeau">
	<?php 
		if (isset($_SESSION["id"])) {
			?>	
				<div class="col-sm-4">
				</div>
				<div class="col-sm-4 logo_bandeau element_bandeau">
					<img src="logo.png" alt="RTT">
				</div>
				<div class="col-sm-4  element_bandeau  element_bandeau_3">
					<div class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							  <span class="glyphicon glyphicon-user"><?php echo ''.$_SESSION['pseudo']; ?></span>
						</a>
						<ul class="dropdown-menu dropdown-menu-right menu_liste" aria-labelledby="dropdownMenu2">
				            <li><a href="mes_informations.php">Mes Informations</a></li>
				            <li><a href="deconnexion.php">Déconnexion</a></li>
						</ul>
			    	</div>
				</div>
			<?php
		}
		else{
			?>
					<div class="element_bandeau">
						<div class="dropdown element_bandeau ">
							<a>
								<span href="#" class="glyphicon glyphicon-bell"></span>
							</a>
						</div>
					</div>
			<?php
		}	
	?>
</div>