<div id="volet">

    <h2 id="volet_titre" class="center">RTT</h2>

    <?php
    function activer_item($url_page){
        $url_complete = $_SERVER['REQUEST_URI'];
        if (strpos($url_complete, $url_page)){echo 'class="active"';}
    }?>

    <div id="accueil">
        <a href="index.php" <?php activer_item('index.php'); ?> >
            <span class="glyphicon glyphicon-home"></span> Accueil
        </a>
    </div>

    <?php if(isset($_SESSION['id']) && !empty($_SESSION['id'])){ ?>
            <div id="mes_messages">
                <a href="mes_messages.php" <?php activer_item('mes_messages.php'); ?>>
                    <span class="glyphicon glyphicon-envelope"></span> Mes messages
                </a>
            </div>

        <?php if ($_SESSION["membre_orga"] == 0) { ?>
            <div id="mes_matchs">
                <a href="mes_matchs.php" <?php activer_item('mes_matchs.php'); ?>>
                    <span class="glyphicon glyphicon-thumbs-up"></span> Mes matchs
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

