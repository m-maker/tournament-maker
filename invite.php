<?php 

include 'conf.php';

if (!isset($_SESSION['id']))
	header('Location: index.php');
?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Administrer mes tournois</title>
		<link rel="stylesheet" type="text/css" href="organisateur/css/orga.css">
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->
		<div class="container-fluid">

			<div class="form-invite">

				<a href="index.php" style="color: black; display: block; margin-bottom: 2%;">
					<span style="margin-bottom: 2%; text-align: left; font-size: 20px; background: white; padding: 1%; border-radius: 5px;"> 
						< Retour à l'accueil
					</span>
				</a>

				<!-- Contenu code envoyé -->
				<?php if (isset($_GET['code_team'])){ 
					$code = htmlspecialchars(trim($_GET["code_team"]));
					$equipe = recupEquipeByCode($code);
					if (!empty($equipe)){ ?>

						<div class="cont-info">
							Vous avez été invité à rejoindre l'équipe <span class="bold"><?php echo $equipe['team_nom']; ?></span> qui compte <span class="bold"><?php echo compter_membres($equipe['team_id']); ?></span> joueurs.<br />
						</div>

						<form action="conf_invite?code=<?php echo $code; ?>" method="post">
							<div class="col-md-6">
								<button type="submit" class="btn btn-warning btn-grand" name="no">Refuser l'invitation</button>
							</div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-success btn-grand" name="yes">Accepter l'invitation</button>
							</div>
						</form>

					<?php }else{ ?>

						<h2 class="rouge center"> Le code saisi n'est pas valide ! </h2>
						<div class="cont-info">
							Saisissez le code d'invitation d'une équipe afin de la rejoindre :
						</div>
						<form method="get">
							<input type="text" name="code_team" class="form-control" placeholder="Saisir le code d'invitation" />
							<button type="submit" class="btn btn-success btn-grand">Chercher</button>
						</form>

					<?php } ?>

				<!-- -->
				<?php }else{ ?>
					
					<div class="cont-info">
						Saisissez le code d'invitation d'une équipe afin de la rejoindre :
					</div>
					<form method="get">
						<input type="text" name="code_team" class="form-control" placeholder="Saisir le code d'invitation" />
						<button type="submit" class="btn btn-success btn-grand">Chercher</button>
					</form>

				<?php } ?>

			</div>
		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

		<script type="text/javascript">
		</script>

	</body>

</html>