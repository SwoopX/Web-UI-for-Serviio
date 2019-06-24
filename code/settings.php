<?php
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
		include("../config.php");
		include("../lib/RestRequest.inc.php");
		include("../lib/serviio.php");

		// initiate call to service
		$serviio = new ServiioService($serviio_host,$serviio_port);

		/*****************************************************************/
		/*****************************************************************/
		if (getPostVar("process", "") == "save") {
			$errorCode = 0;
			
			$retLang = getPostVar("language","en");
			$retCheckForUpdates = getPostVar("checkForUpdates","0")==1?"true":"false";
			$errorCode = $serviio->putConsoleSettings($retLang,$retCheckForUpdates);

			return $errorCode;
		}
	}
	
	$settings = $serviio->getConsoleSettings();
	$languages = $serviio->getReferenceData('browsingCategoriesLanguages');
?>
