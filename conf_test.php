<?php
	include('conf.php');

	foreach (listeDepartements() as $key) {
		var_dump($key);
	}
	var_dump(listeDepartements());
?>	