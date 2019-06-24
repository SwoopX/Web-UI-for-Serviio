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
            $profiles = array();
            foreach ($_POST as $key=>$val) {
                if (substr($key, 0, 5)=="name_") {
                    $uuid = substr($key, 5);
                    if ($uuid!="") {
                        $ipAddress = getPostVar("ipAddress_${uuid}", "");
                        $name = $val;
                        $profile = getPostVar("profile_${uuid}", "1"); // Generic DLNA profile
                        $enabled = getPostVar("enabled_${uuid}", "");
                        $access = getPostVar("access_${uuid}", "1");
                        $profiles[] = array($uuid, $ipAddress, $name, $profile, $enabled, $access);
                    }
                }
            }
            $bound_nic = getPostVar("bound_nic", "");
            $rendererEnabledByDefault = getPostVar("rendererEnabledByDefault", "0")==1?"true":"false";
            $defaultAccessGroupId = getPostVar("defaultAccessGroupId", "1");
            
            $errorCode = $serviio->putStatus($profiles, $bound_nic, $rendererEnabledByDefault, $defaultAccessGroupId);
            return $errorCode;
        }
        
        /*****************************************************************/
        /*****************************************************************/
        elseif (getPostVar("process", "") == "start") {
            $statusResponse = $serviio->getStatus();
            if ($statusResponse["serverStatus"] == "STOPPED") {
                $errorCode = $serviio->postAction("startServer");
                return $errorCode;
            }
        }
        
        /*****************************************************************/
        /*****************************************************************/
        elseif (getPostVar("process", "") == "stop") {
            $statusResponse = $serviio->getStatus();
            if ($statusResponse["serverStatus"] == "STARTED") {
                $errorCode = $serviio->postAction("stopServer");
                return $errorCode;
            }
        }
    }

    function status_icon($status)
    {
        $icon = 'orange';
        if ($status=='INACTIVE') {
            $icon = 'red';
        } else if ($status=='ACTIVE') {
            $icon = 'green';
        }
        return "<img src='images/bullet_${icon}.png' alt='${status}'>";
    }
    
    $statusResponse = $serviio->getStatus();
    $profiles = $serviio->getReferenceData('profiles');
    $interfaces = $serviio->getReferenceData('networkInterfaces');
    $accesses = $serviio->getReferenceData('accessGroups');
    $serviio->getApplication();
?>