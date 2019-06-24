<?php
	if ($_SERVER["REQUEST_METHOD"]=="POST") {
		include("../config.php");
		include("../lib/RestRequest.inc.php");
		include("../lib/serviio.php");

		// initiate call to service
		$serviio = new ServiioService($serviio_host,$serviio_port);

		/*****************************************************************/
		/*****************************************************************/
		if (getPostVar("process", "") == "checkPortMapping") {
			$errorCode = $serviio->postAction("checkPortMapping", '');
			return $errorCode;
		}
		
		/*****************************************************************/
		/*****************************************************************/
		elseif (getPostVar("process", "") == "save") {
			$errorCode = 0;
			$userPasswd = getPostVar("userPasswd","");
			$deliveryQuality = getPostVar("deliveryQuality","");
			$portMapping = getPostVar("portMapping","")==1?"true":"false";
			$extAddress = getPostVar("extAddress","");
		
			$errorCode = $serviio->putRemoteAccess($userPasswd,$deliveryQuality,$portMapping,$extAddress);
			if ($errorCode===false || $errorCode!=0) {
				$message = $serviio->warning;
			}
			return $errorCode;
		}
	}
	
	$rmtAccess = $serviio->getRemoteAccess();
	$quality = $serviio->getReferenceData('remoteDeliveryQualities');
	$interfaces = $serviio->getReferenceData('networkInterfaces');
	$boundNIC = $serviio->getStatus();
?>
