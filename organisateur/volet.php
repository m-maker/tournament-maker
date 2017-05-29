<div id="volet">

    <h2 id="volet_titre" class="center">RTT</h2>

    <?php $url_complete = $_SERVER['REQUEST_URI']; ?>

        <div id="accueil">
            <a href="index.php" <?php if (strpos($url_complete, 'index.php')){echo 'class="active"';} ?>>
                <span class="glyphicon glyphicon-home"></span> Accueil
            </a>
        </div>
        <div id="mes_messages">
            <a href="mes_maessages.php">
                <span class="glyphicon glyphicon-envelope"></span> Mes messages
            </a>
        </div>

        <div id="mes_paiements">
            <a href="organiser_tournoi.php">
                <span class="glyphicon glyphicon-asterisk"></span> Organiser un tournoi
            </a>
        </div>

        <div id="deco">
            <a href="../deconnexion.php">
                <span class="glyphicon glyphicon-ban-circle"></span> Deconnexion
            </a>
        </div>

</div>

