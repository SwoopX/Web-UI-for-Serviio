<?php
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        include("../config.php");
        include("../lib/RestRequest.inc.php");
        include("../lib/serviio.php");

        // initiate call to service
        $serviio = new ServiioService($serviio_host,$serviio_port);

        /*****************************************************************/
        /*****************************************************************/
        if (getPostVar("process", "") == "upload") {
            
            $data = getPostVar("licenseData", "");
            $filename = getPostVar("filename", "");
            
            if (substr($filename,-4) != ".lic") {
                header("Content-type: text/plain");
                echo '<?xml version="1.0" encoding="UTF-8" ?>';
                echo '<result>';
                echo '<errorCode>560</errorCode>';
                echo '</result>';
                exit;
            }
            
            $errorCode = $serviio->putLicenseUpload($data);
            return $errorCode;
        }
    }
?>