<div id="volet">

    <h3 class="volet-titre"><?php echo $_SESSION["pseudo"]; ?></h3>

    <div id="accueil">
        <a href="index.php" <?php activer_item('index.php'); ?> >
            <?php if (isset($_SESSION['id']) && !empty($_SESSION['id']) && $_SESSION["membre_orga"] == 0){ ?>
                <span class="glyphicon glyphicon-search"></span> Trouver des tournois
            <?php }else{ ?>
                <span class="glyphicon glyphicon-home"></span> Accueil
            <?php } ?>
        </a>
    </div>

    <?php if(isset($_SESSION['id']) && !empty($_SESSION['id'])){ ?>
            <div id="mes_messages">
                <a href="mes_messages.php" <?php activer_item('mes_messages.php'); ?>>
                    <span class="glyphicon glyphicon-envelope"></span> Messagerie
                </a>
            </div>

        <?php if ($_SESSION["membre_orga"] == 0) { ?>
            <div id="mes_matchs">
                <a href="mes_matchs.php" <?php activer_item('mes_matchs.php'); ?>>
                    <span class="glyphicon glyphicon-thumbs-up"></span> Mes tournois
                </a>
            </div>
        <?php }else{ ?>
            <div id="mes_paiements" <?php activer_item('organiser_tournoi.php'); ?>>
                <a href="organisateur/organiser_tournoi.php">
                    <span class="glyphicon glyphicon-asterisk"></span> Organiser un tournoi
                </a>
            </div>
        <?php } ?>

            <div id="deco">
                <a href="deconnexion.php">
                    <span class="glyphicon glyphicon-ban-circle"></span> Deconnexion
                </a>
            </div>



        <?php
	}else{
		?>

		<?php
	}

    ?>

</div>

