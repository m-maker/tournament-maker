<?php
	if (isset($_SESSION['id'])) {
        $compte_notif = Notifications::getCompteNewNotif($_SESSION["id"]);
        $compte_msg = compteMessagesNonVus($_SESSION["id"]);
    }
?>
<link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
<div class="bandeau">

	<div class="dropdown element_bandeau ">
	<div class="cercle">
		<a href="#" data-toggle="dropdown" >
			
			<span class="glyphicon glyphicon-menu-hamburger encercle"></span>
		</a>

		  <ul class="dropdown-menu menu_liste" aria-labelledby="dropdownMenu2">
		  	<img class="center" src="<?php echo $_SESSION["membre_avatar"]; ?>" width="30" /> 
		  	<br/> 
		  	<span><?php echo $_SESSION["pseudo"]; ?></span>
		    <li>
	            <a href="mes_matchs.php" <?php activer_item('mes_matchs.php'); ?>>
	                <span class="glyphicon glyphicon-thumbs-up"></span> Mes tournois
	            </a>
	        </li>
	        <li>
	        	<a href="invite.php" <?php activer_item('invite.php'); ?>>
	                <span class="glyphicon glyphicon-plus-sign"></span> Invitations
	            </a>
	        </li>
	        <li>
	        	<a href="notifs.php" <?php activer_item('notifs.php'); ?>>
	                <span id="notif-compte" class="glyphicon glyphicon-alert"></span><?php if ($compte_notif > 0){
	                        echo '<b>Notifications ('.$compte_notif.')</b>';
	                    }else{
	                        echo 'Notifications ('.$compte_notif.')';} ?>
           		</a>
	        </li>
	        <li>
	        	<a href="mes_messages.php" <?php activer_item('mes_messages.php'); ?>>
	                <span class="glyphicon glyphicon-envelope"> </span><?php if ($compte_msg > 0){
	                        echo '<b>Messagerie ('.$compte_msg.')</b>';
	                    }else{
	                        echo 'Messagerie ('.$compte_msg.')';} ?>
	            </a>
	        </li>
	        <li>
	        	<a href="deconnexion.php">
                    <span class="glyphicon glyphicon-ban-circle"></span> Deconnexion
                </a>
	        </li>
		    <li><a href="#">Paramètres</a></li>
		    <li><a href="#">Mes collègues</a></li>
		  </ul>
	</div>
	</div>

	<div class="logo_bandeau element_bandeau">
		<img src="logo.png" alt="RTT">
	</div>


    <div id="accueil">
        <a href="index.php" <?php activer_item('index.php'); ?> >
            Accueil
        </a>
    </div>
</div>
   
	