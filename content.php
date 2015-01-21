<?php
	set_time_limit(0);
	include("config.php");
	include("lib/RestRequest.inc.php");
	include("lib/serviio.php");

	$tab = isset($_REQUEST["tab"])?$_REQUEST["tab"]:"";
	if ($tab!="library" && $tab!="metadata" && $tab!="delivery" && $tab!="about" && $tab!="presentation" && $tab!="remote" && $tab!="settings" && $tab!="logs" && $tab!="about") {
		$tab = "status";
	}

	$serviio = new ServiioService($serviio_host,$serviio_port);

	$settings = $serviio->getConsoleSettings();
	$language = $settings["language"];

	include("code/${tab}.php");
	include("view/${tab}.php");
?>
