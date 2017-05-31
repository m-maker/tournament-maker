<div id="volet">

    <h3 class="volet-titre"><?php echo $_SESSION["pseudo"]; ?></h3>

    <!--<img src="../img/logo.png" width="100%" height="80" style="margin-bottom: 1%;">-->

    <?php $url_complete = $_SERVER['REQUEST_URI']; ?>

        <div id="accueil">
            <a href="index.php" <?php activer_item('index.php'); ?>>
                <span class="glyphicon glyphicon-home"></span> Accueil
            </a>
        </div>
        <div id="mes_messages">
            <a href="../mes_messages.php" <?php activer_item('mes_messages.php'); ?>>
                <span class="glyphicon glyphicon-envelope"></span> Messages
            </a>
        </div>

        <div id="mes_paiements">
            <a href="organiser_tournoi.php" <?php activer_item('organiser_tournoi.php'); ?>>
                <span class="glyphicon glyphicon-asterisk"></span> Organiser un tournoi
            </a>
        </div>

        <div id="deco">
            <a href="../deconnexion.php">
                <span class="glyphicon glyphicon-ban-circle"></span> Deconnexion
            </a>
        </div>

</div>

