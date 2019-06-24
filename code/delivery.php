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
			$transcoding = getPostVar("transcoding","0")==1?"true":"false";
			$location = getPostVar("location","");
			$cores = getPostVar("cores","");
			$audio = getPostVar("audio","")=="downmix"?"true":"false";
			$quality = getPostVar("quality","0")==1?"true":"false";

			$subtitles = getPostVar("subtitles","0")==1?"true":"false";
			$subtitlesextraction = getPostVar("subtitlesextraction","0")==1?"true":"false";
            $hardsubsenabled = getPostVar("hardsubs","0")=="enabled"?"true":"false";
			$hardsubsforced = getPostVar("hardsubs","0")=="forced"?"true":"false";
			$language = getPostVar("language","");
			$characterEncoding = getPostVar("characterEncoding","");

			$errorCode = $serviio->putDelivery($transcoding,$location,$cores,$audio,$quality,$subtitles,$subtitlesextraction,$hardsubsenabled,$hardsubsforced,$language,$characterEncoding);
			if ($errorCode===false || $errorCode!=0) {
				$message = $serviio->warning;
			}
			return $errorCode;
		}
	}
	
	$serviio->getDelivery();
	$numberOfCPUCores = $serviio->getReferenceData('cpu-cores');
?>