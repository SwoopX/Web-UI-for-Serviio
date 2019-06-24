<?php

class ServiidbService extends RestRequest
{

    protected $url;

    public $error;
    public $warning;

    public $licenseEdition;

    /**
     */
    public function __construct ($url)
    {
        parent::flush();
        $this->url = $url;
    }

    /**
     */
    public function getVideo()
    {
        parent::setUrl($this->url.'video?client=WEBUI');
        parent::setVerb('GET');
        parent::setContentType('');
        parent::execute();
        //return print_r(parent::getResponseBody());
        return parent::getResponseBody();


        /*$xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get status";
            return false;
        }
        $serverStatus = (string)$xml->serverStatus;
        $boundNICName = (string)$xml->boundNICName;
        $this->renderers = array();
        foreach ($xml->renderers->renderer as $item) {
            $uuid = (string)$item->uuid;
            $ipAddress = (string)$item->ipAddress;
            $name = (string)$item->name;
            $profileId = (string)$item->profileId;
            $status = (string)$item->status;
            $enabled = (string)$item->enabled;
            $accessGroupId = (string)$item->accessGroupId;
            $this->renderers[$uuid] = array($ipAddress, $name, $profileId, $status, $enabled, $accessGroupId);
        }
        return array("serverStatus"=>$serverStatus, "renderers"=>$this->renderers, "boundNICName"=>$boundNICName);*/
    }

}

?>
