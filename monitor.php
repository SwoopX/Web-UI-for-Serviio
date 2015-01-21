<?php
set_time_limit(0);
include("config.php");
include("lib/RestRequest.inc.php");
include("lib/serviio.php");

$serviio = new ServiioService($serviio_host,$serviio_port);
$arr = $serviio->getSystemStatus();
header('Content-type: application/json');
echo json_encode($arr);
?>
