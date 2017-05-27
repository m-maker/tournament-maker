<?php
	if(isset($_SESSION['id'])){
		if(!empty($_SESSION['id'])){
			?>
				<div id="volet">
						<div id="accueil">
							<a  href="index.php">
							<button class="btn btn-success">
								<span class="glyphicon glyphicon-home">Accueil</span>
							</button>
							</a>
						</div>
						<div id="mes_messages">
							<a href="mes_maessages.php">
								<span class="glyphicon glyphicon-envelope">Mes messages</span>			
							</a>
						</div>
						<div id="mes_matchs">
							<a href="mes_matchs.php">
								<span class="glyphicon glyphicon-thumbs-up">Mes matchs</span>			
							</a>
						</div>
				</div>
			<?php
		}
		else{
			?>
				<div id="volet">
				</div>
			<?php
		}
	}
	else{
		?>
			<div id="volet">
			</div>
		<?php
	}
?>

