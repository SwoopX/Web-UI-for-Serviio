<?php
/**
* http://www.gen-x-design.com/archives/making-restful-requests-in-php/
*/

class ServiioContentDirectoryService extends RestRequest
{

    protected $host;
    protected $port;

    public $error;
    public $warning;

    public $searchHiddenFiles;
    public $searchForUpdates;
    public $automaticLibraryUpdate;
    public $automaticLibraryUpdateInterval;
    public $maxNumberOfItemsForOnlineFeeds;
    public $onlineFeedExpiryInterval;
    public $onlineContentPreferredQuality;
    public $repository;

    public $profiles;
    public $renderers;
    public $boundNICName;

    public $audioLocalArtExtractorEnabled;
    public $videoLocalArtExtractorEnabled;
    public $videoOnlineArtExtractorEnabled;
    public $videoGenerateLocalThumbnailEnabled;
    public $metadataLanguage;
    public $descriptiveMetadataExtractor;
    public $retrieveOriginalTitle;

    public $descriptiveMetadataExtractors;
    public $browsingCategoriesLanguages;

    public $audioDownmixing;
    public $threadsNumber;
    public $transcodingFolderLocation;
    public $transcodingEnabled;
    public $bestVideoQuality;
	public $subtitlesEnabled;
	public $embeddedSubtitlesExtractionEnabled;
	public $hardSubsEnabled;
	public $hardSubsForced;

    public $numberOfCPUCores;

    public $presentationLanguage;
    public $showParentCategoryTitle;

    public $remoteUserPassword;
    public $preferredRemoteDeliveryQuality;
	public $portMappingEnabled;
	public $externalAddress;

    public $language;
    public $securityPin;
    public $checkForUpdates;

    /**
     */
    public function __construct ($host, $port)
    {
        parent::flush();
        $this->host = $host;
        $this->port = $port;
    }


    /**
     */
    public function getPing()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/cds/ping');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get ping";
            return false;
        }
        if ($xml->errorCode == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     */
    public function getApplication()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/cds/application');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get application";
            return false;
        }
        $currentVersion = (string)$xml->version;
        $updateVersionAvailable = (string)$xml->updateVersionAvailable;

        $edition = (string)$xml->edition;
        $this->lic = array();
        foreach ($xml->license as $licenseDetail) {
            $id = (string)$licenseDetail->id;
            $type = (string)$licenseDetail->licenseType;
            $name = (string)$licenseDetail->licenseName;
            $email = (string)$licenseDetail->licenseEmail;
            $expiresInMinutes = (string)$licenseDetail->expiresInMinutes;
            $lic[$id] = array($id,$type,$name,$email,$expiresInMinutes);
        }

        return array(
                     "version"=>$currentVersion,
                     "updateVersionAvailable"=>$updateVersionAvailable,
                     "edition"=>$edition,
                     "license"=>$this->lic
                     );
    }

	public function getReferenceData($property)
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/refdata/'.$property);
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get ".$property." data";
            return false;
        }
        //return $xml;
        $numberOfCPUCores = 1;
		
		$result = array();
		
        foreach ($xml->values->item as $item) {
            $value = (string)$item->value;
            $name = (string)$item->name;
            $result["${name}"] = $value;
        }
        
		switch ($property) {
			case "cpu-cores":
				$this->numberOfCPUCores = $result;				//delivery.php
				break;
			case "profiles":
				$this->profiles = $result;						//status.php
				break;
			case "metadataLanguages":
				$this->metadataLanguages = $result;				//metadata.php
				break;
			case "browsingCategoriesLanguages":
				$this->browsingCategoriesLanguages = $result;	//presentation.php
				break;
			case "descriptiveMetadataExtractors":
				$this->descriptiveMetadataExtractors = $result;	//metadata.php
				break;
			case "categoryVisibilityTypes":
				$this->categoryVisibilityTypes = $result;		//presentation.php
				break;
			case "onlineRepositoryTypes":
				$this->onlineRepositoryTypes = $result;			//library.php
				break;
			case "onlineContentQualities":
				$this->onlineContentQualities = $result;		//library.php
				break;
			case "accessGroups":
				$this->accessGroups = $result;					//status.php, library.php
				break;
			case "remoteDeliveryQualities":
				$this->remoteDeliveryQualities = $result;		//remote.php
				break;
			case "networkInterfaces":
				$this->boundNICName = $result;					//status.php
				break;
		}
		
        return $result;

        //$this->pvalues = array();
        //foreach ($xml->pvalues->pvalue as $item) {
        //    $name = (string)$item->name;
        //    $value = (string)$item->value;
        //    $this->pvalues[$name] = array($name, $value);
        //}
        //return array($this->pvalues);
    }







    /**
     */
    public function getRepository()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/repository');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get repository";
            return false;
        }
        $repo = array();
        $sf = array();
        $or = array();

        foreach ($xml->sharedFolders as $sharedFolders) {
            foreach ($sharedFolders as $item) {
                $id = (string)$item->id;
                $folderPath = (string)$item->folderPath;
                $supportedFileTypes = array();
                foreach ($item->supportedFileTypes as $types) {
                    foreach ($types as $type) {
                        $supportedFileTypes[] = (string)$type;
                    }
                }
                $descriptiveMetadataSupported = (string)$item->descriptiveMetadataSupported;
                $scanForUpdates = (string)$item->scanForUpdates;
                $accessGroupIds = array();
                foreach ($item->accessGroupIds as $accessGroupId) {
                    foreach ($accessGroupId as $grpId) {
                        $accessGroupIds[] = (string)$grpId;
                    }
                }
                $sf[$id] = array($folderPath, $supportedFileTypes, $descriptiveMetadataSupported, $scanForUpdates, $accessGroupIds);
            }
        }
        $repo[0] = $sf;

        $this->searchHiddenFiles = (string)$xml->searchHiddenFiles;
        $this->searchForUpdates = (string)$xml->searchForUpdates;
        $this->automaticLibraryUpdate = (string)$xml->automaticLibraryUpdate;
        $this->automaticLibraryUpdateInterval = (string)$xml->automaticLibraryUpdateInterval;

        // onlineRepositories
        foreach ($xml->onlineRepositories as $onlineRepositories) {
            foreach ($onlineRepositories as $item) {
                $id = (string)$item->id;
                $repositoryType = (string)$item->repositoryType;
                $contentUrl = (string)$item->contentUrl;
                $fileType = (string)$item->fileType;
                $thumbnailUrl = (string)$item->thumbnailUrl;
                $repositoryName = (string)$item->repositoryName;
                $enabled = (string)$item->enabled;
                $ORaccessGroupIds = array();
                foreach ($item->ORaccessGroupIds as $accessGroupId) {
                    foreach ($accessGroupId as $grpId) {
                        $ORaccessGroupIds[] = (string)$grpId;
                    }
                }
                $or[$id] = array($repositoryType, $contentUrl, $fileType, $thumbnailUrl, $repositoryName, $enabled, $ORaccessGroupIds);
            }
        }
        $repo[1] = $or;
        $this->maxNumberOfItemsForOnlineFeeds = (string)$xml->maxNumberOfItemsForOnlineFeeds;
        $this->onlineFeedExpiryInterval = (string)$xml->onlineFeedExpiryInterval;
        $this->onlineContentPreferredQuality = (string)$xml->onlineContentPreferredQuality;

        $this->repository = $repo;
        return $repo;
    }

    /**
     */
    public function getProfiles()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/refdata/profiles');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get profiles";
            return false;
        }
        $profiles = array();
        foreach ($xml->values->item as $item) {
            $value = (string)$item->value;
            $name = (string)$item->name;
            $profiles["${name}"] = $value;
        }
        $this->profiles = $profiles;
        return $profiles;
    }

    /**
     */
    public function getMetadata()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/metadata');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get metadata";
            return false;
        }
        $audioLocalArtExtractorEnabled = (string)$xml->audioLocalArtExtractorEnabled;
        $videoLocalArtExtractorEnabled = (string)$xml->videoLocalArtExtractorEnabled;
        $videoOnlineArtExtractorEnabled = (string)$xml->videoOnlineArtExtractorEnabled;
        $videoGenerateLocalThumbnailEnabled = (string)$xml->videoGenerateLocalThumbnailEnabled;
        $metadataLanguage = (string)$xml->metadataLanguage;
        $retrieveOriginalTitle = (string)$xml->retrieveOriginalTitle;
        $descriptiveMetadataExtractor = (string)$xml->descriptiveMetadataExtractor;
        $this->audioLocalArtExtractorEnabled = $audioLocalArtExtractorEnabled;
        $this->videoLocalArtExtractorEnabled = $videoLocalArtExtractorEnabled;
        $this->videoOnlineArtExtractorEnabled = $videoOnlineArtExtractorEnabled;
        $this->videoGenerateLocalThumbnailEnabled = $videoGenerateLocalThumbnailEnabled;
        $this->metadataLanguage = $metadataLanguage;
        $this->retrieveOriginalTitle = $retrieveOriginalTitle;
        $this->descriptiveMetadataExtractor = $descriptiveMetadataExtractor;
        return array($audioLocalArtExtractorEnabled, $videoLocalArtExtractorEnabled, $videoOnlineArtExtractorEnabled, $videoGenerateLocalThumbnailEnabled, $metadataLanguage, $descriptiveMetadataExtractor, $retrieveOriginalTitle);
    }

    /**
     */
    public function getDescriptiveMetadataExtractors()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/refdata/descriptiveMetadataExtractors');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get descriptive metadata extractors";
            return false;
        }
        $result = array();
        foreach ($xml->values->item as $item) {
            $value = (string)$item->value;
            $name = (string)$item->name;
            $result["${name}"] = $value;
        }
        $this->descriptiveMetadataExtractors = $result;
        return $result;
    }

    /**
     */
    public function getBrowsingCategoriesLanguages()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/refdata/browsingCategoriesLanguages');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get browsing categories languages";
            return false;
        }
        $result = array();
        foreach ($xml->values->item as $item) {
            $value = (string)$item->value;
            $name = (string)$item->name;
            $result["${name}"] = $value;
        }
        $this->browsingCategoriesLanguages = $result;
        return $result;
    }

    /**
     */
    public function getDelivery()
    {
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/delivery');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get transcoding";
            return false;
        }
		
        $transcoding = $xml->transcoding;
        
        $audioDownmixing = (string)$transcoding->audioDownmixing;
        $threadsNumber = (string)$transcoding->threadsNumber;
        $transcodingFolderLocation = (string)$transcoding->transcodingFolderLocation;
        $transcodingEnabled = (string)$transcoding->transcodingEnabled;
        $bestVideoQuality = (string)$transcoding->bestVideoQuality;
        $this->audioDownmixing = $audioDownmixing;
        $this->threadsNumber = $threadsNumber;
        $this->transcodingFolderLocation = $transcodingFolderLocation;
        $this->transcodingEnabled = $transcodingEnabled;
        $this->bestVideoQuality = $bestVideoQuality;
		
		$subtitles = $xml->subtitles;
		
		$subtitlesEnabled = (string)$subtitles->subtitlesEnabled;
        $embeddedSubtitlesExtractionEnabled = (string)$subtitles->embeddedSubtitlesExtractionEnabled;
        $hardSubsEnabled = (string)$subtitles->hardSubsEnabled;
        $hardSubsForced = (string)$subtitles->hardSubsForced;
		$preferredLanguage = (string)$subtitles->preferredLanguage;
		$this->subtitlesEnabled = $subtitlesEnabled;
        $this->embeddedSubtitlesExtractionEnabled = $embeddedSubtitlesExtractionEnabled;
        $this->hardSubsEnabled = $hardSubsEnabled;
        $this->hardSubsForced = $hardSubsForced;
        $this->preferredLanguage = $preferredLanguage;
        
        return array($audioDownmixing, $threadsNumber, $transcodingFolderLocation, $bestVideoQuality, $transcodingEnabled, $subtitlesEnabled, $embeddedSubtitlesExtractionEnabled, $hardSubsEnabled, $hardSubsForced, $preferredLanguage);
    }

    /**
     */
    public function getCpuCores()
    {
        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/refdata/cpu-cores');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get CPU cores";
            return false;
        }
        $numberOfCPUCores = 1;
        foreach ($xml->values as $entry) {
            $item = $entry->item;
            if ((string)$item->name=="numberOfCPUCores") {
                $numberOfCPUCores = (string)$item->value;
                break;
            }
        }
        $this->numberOfCPUCores = $numberOfCPUCores;
        return $numberOfCPUCores;
    }

    /**
     */
    public function getPresentation()
    {
        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/presentation');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get presentation";
            return false;
        }
        $categories = array();
        foreach ($xml->categories->browsingCategory as $entry) {
            $id = (string)$entry->id; // => A
            $title = (string)$entry->title; // => Audio
            $visibility = (string)$entry->visibility; // => DISPLAYED
            $subCategories = array();
            foreach ($entry->subCategories->browsingCategory as $item) {
                $subId = (string)$item->id; // => A_F
                $subTitle = (string)$item->title; // => Folders
                $subVisibility = (string)$item->visibility; // => DISPLAYED
                $subCategories[$subId] = array($subTitle, $subVisibility);
            }
            $categories[$id] = array($title, $visibility, $subCategories);
        }
        $presentationLanguage = (string)$xml->language;
        $showParentCategoryTitle = (string)$xml->showParentCategoryTitle;
        $this->presentationLanguage = $presentationLanguage;
        $this->showParentCategoryTitle = $showParentCategoryTitle;
        return $categories;
    }

    /**
     */
    public function getCategoryVisibilityTypes()
    {
        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/refdata/categoryVisibilityTypes');
        parent::setVerb('GET');
        parent::execute();
        $xml = simplexml_load_string(parent::getResponseBody());
        if ($xml===false) {
            $this->error = "Cannot get presentation";
            return false;
        }
        $types = array();
        foreach ($xml->values->item as $entry) {
            $types[(string)$entry->name] = (string)$entry->value;
        }
        return $types;
    }


    /**
     */
    public function putLogin($profiles, $ip)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("status"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("boundIPAddress", $ip));
        $Rends = $root->appendChild($xmlDoc->createElement("renderers"));

        foreach ($profiles as $renderer) {
            $Rend = $Rends->appendChild($xmlDoc->createElement("renderer"));
            $Rend->appendChild($xmlDoc->createElement("uuid", $renderer[0]));
            $Rend->appendChild($xmlDoc->createElement("ipAddress", $renderer[1]));
            $Rend->appendChild($xmlDoc->createElement("name", $renderer[2]));
            $Rend->appendChild($xmlDoc->createElement("profileId", $renderer[3]));
            $Rend->appendChild($xmlDoc->createElement("enabled", $renderer[4]));
            $Rend->appendChild($xmlDoc->createElement("accessGroupId", $renderer[5]));
        }

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/status');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putRemoteAccess($passwd, $quality, $mapping, $address)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("remoteAccess"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("remoteUserPassword", $passwd));
        $root->appendChild($xmlDoc->createElement("preferredRemoteDeliveryQuality", $quality));
		$root->appendChild($xmlDoc->createElement("portMappingEnabled", $mapping));
		$root->appendChild($xmlDoc->createElement("externalAddress", $address));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/remote-access');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putConsoleSettings($lang, $secPin, $chkForUpd)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("consoleSettings"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("language", $lang));
        $root->appendChild($xmlDoc->createElement("securityPin", $secPin));
        $root->appendChild($xmlDoc->createElement("checkForUpdates", $chkForUpd));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/console-settings');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putLicenseUpload($data)
    {
        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/license-upload');
        parent::setVerb('PUT');
        parent::setRequestBody($data);
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function postAction($action, $params = '')
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("action"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("name", $action));

        // any params?
        if ($params != '') {
            foreach ($params as $param) {
                $root->appendChild($xmlDoc->createElement("parameter", $param));
            }
        }

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        return $requestBody;
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/action');
        parent::setVerb('POST');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putDelivery($transcoding, $location, $cores, $audio, $quality, $subtitles, $subtitlesextraction, $hardsubsenabled, $hardsubsforced, $language)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("delivery"));

        //create the transcoding element
        $delivery = $root->appendChild($xmlDoc->createElement("transcoding"));

        // create sub element
        $delivery->appendChild($xmlDoc->createElement("audioDownmixing", $audio));
        $delivery->appendChild($xmlDoc->createElement("threadsNumber", $cores));
        $delivery->appendChild($xmlDoc->createElement("transcodingFolderLocation", $location));
        $delivery->appendChild($xmlDoc->createElement("transcodingEnabled", $transcoding));
		$delivery->appendChild($xmlDoc->createElement("bestVideoQuality", $quality));
		
		//create the transcoding element
        $delivery1 = $root->appendChild($xmlDoc->createElement("subtitles"));

        // create sub element
        $delivery1->appendChild($xmlDoc->createElement("subtitlesEnabled", $subtitles));
        $delivery1->appendChild($xmlDoc->createElement("embeddedSubtitlesExtractionEnabled", $subtitlesextraction));
        $delivery1->appendChild($xmlDoc->createElement("hardSubsEnabled", $hardsubsenabled));
        $delivery1->appendChild($xmlDoc->createElement("hardSubsForced", $hardsubsforced));
        $delivery1->appendChild($xmlDoc->createElement("preferredLanguage", $language));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/delivery');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putMetadata($audioLocalArtExtractorEnabled, $videoLocalArtExtractorEnabled, $videoOnlineArtExtractorEnabled,
    $videoGenerateLocalThumbnailEnabled, $metadataLanguage, $descriptiveMetadataExtractor, $retrieveOriginalTitle)
    {
        // create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("metadata"));

        // create sub element
        $root->appendChild($xmlDoc->createElement("audioLocalArtExtractorEnabled", $audioLocalArtExtractorEnabled));
        $root->appendChild($xmlDoc->createElement("videoLocalArtExtractorEnabled", $videoLocalArtExtractorEnabled));
        $root->appendChild($xmlDoc->createElement("videoOnlineArtExtractorEnabled", $videoOnlineArtExtractorEnabled));
        $root->appendChild($xmlDoc->createElement("videoGenerateLocalThumbnailEnabled", $videoGenerateLocalThumbnailEnabled));
        $root->appendChild($xmlDoc->createElement("metadataLanguage", $metadataLanguage));
        $root->appendChild($xmlDoc->createElement("retrieveOriginalTitle", $retrieveOriginalTitle));
        $root->appendChild($xmlDoc->createElement("descriptiveMetadataExtractor", $descriptiveMetadataExtractor));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/metadata');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putRepository($repo)
    {
        //create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("repository"));

        //create a tutorial element
        $sharedFolders = $root->appendChild($xmlDoc->createElement("sharedFolders"));

        /* FOLDERS */
        foreach ($repo[0] as $id=>$entry) {
            $Folder = $sharedFolders->appendChild($xmlDoc->createElement("sharedFolder"));
            if ($entry[4] != "new") {
                $Folder->appendChild($xmlDoc->createElement("id", $id));
            }
            $Folder->appendChild($xmlDoc->createElement("folderPath", $entry[0]));

            $supportedFileTypes = $Folder->appendChild($xmlDoc->createElement("supportedFileTypes"));
            foreach ($entry[1] as $type) {
                $supportedFileTypes->appendChild($xmlDoc->createElement("fileType", $type));
            }

            $Folder->appendChild($xmlDoc->createElement("descriptiveMetadataSupported", $entry[2]));
            $Folder->appendChild($xmlDoc->createElement("scanForUpdates", $entry[3]));

            $accessGroupIds = $Folder->appendChild($xmlDoc->createElement("accessGroupIds"));
            foreach ($entry[5] as $grpId) {
                $accessGroupIds->appendChild($xmlDoc->createElement("id", $grpId));
            }
        }
        $root->appendChild($xmlDoc->createElement("searchHiddenFiles", $this->searchHiddenFiles));
        $root->appendChild($xmlDoc->createElement("searchForUpdates", $this->searchForUpdates));
        $root->appendChild($xmlDoc->createElement("automaticLibraryUpdate", $this->automaticLibraryUpdate));
        $root->appendChild($xmlDoc->createElement("automaticLibraryUpdateInterval", $this->automaticLibraryUpdateInterval));

        /* Online Repositories */
        $sharedFolders = $root->appendChild($xmlDoc->createElement("onlineRepositories"));
        if (isset($repo[1])) {
            foreach ($repo[1] as $id=>$entry) {
                $Folder = $sharedFolders->appendChild($xmlDoc->createElement("onlineRepository"));
                if ($entry[3] != "new") {
                    $Folder->appendChild($xmlDoc->createElement("id", $id));
                }
                $Folder->appendChild($xmlDoc->createElement("repositoryType", $entry[0]));
                $Folder->appendChild($xmlDoc->createElement("contentUrl", str_replace("&", "&amp;", $entry[1])));
                $Folder->appendChild($xmlDoc->createElement("fileType", $entry[2]));
                $Folder->appendChild($xmlDoc->createElement("thumbnailUrl", $entry[6]));
                $Folder->appendChild($xmlDoc->createElement("repositoryName", $entry[4]));
                $Folder->appendChild($xmlDoc->createElement("enabled", $entry[5]));

                $accessGroupIds = $Folder->appendChild($xmlDoc->createElement("accessGroupIds"));
                foreach ($entry[7] as $grpId) {
                    $accessGroupIds->appendChild($xmlDoc->createElement("id", $grpId));
                }
            }
        }
        $root->appendChild($xmlDoc->createElement("maxNumberOfItemsForOnlineFeeds", $this->maxNumberOfItemsForOnlineFeeds));
        $root->appendChild($xmlDoc->createElement("onlineFeedExpiryInterval", $this->onlineFeedExpiryInterval));
        $root->appendChild($xmlDoc->createElement("onlineContentPreferredQuality", $this->onlineContentPreferredQuality));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/repository');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }

    /**
     */
    public function putPresentation($categories, $presentationLanguage, $showParentCategoryTitle)
    {
        //create the xml document
        $xmlDoc = new DOMDocument();

        // add encoding
        $xmlDoc->encoding = "UTF-8";

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("presentation"));

        //create a tutorial element
        $Categories = $root->appendChild($xmlDoc->createElement("categories"));

        /* CATEGORIES */
        foreach ($categories as $id=>$category) {
            $id = str_replace("'", "", $id);
            $Category = $Categories->appendChild($xmlDoc->createElement("browsingCategory"));
            $Category->appendChild($xmlDoc->createElement("id", $id));
            $Category->appendChild($xmlDoc->createElement("visibility", $category[1]));

            $subCategories = $Category->appendChild($xmlDoc->createElement("subCategories"));
            foreach ($category[2] as $subId=>$subcategory) {
                $subId = str_replace("'", "", $subId);
                $subCategory = $subCategories->appendChild($xmlDoc->createElement("browsingCategory"));
                $subCategory->appendChild($xmlDoc->createElement("id", $subId));
                $subCategory->appendChild($xmlDoc->createElement("visibility", $subcategory[1]));
            }
        }
        $root->appendChild($xmlDoc->createElement("language", $presentationLanguage));
        $root->appendChild($xmlDoc->createElement("showParentCategoryTitle", $showParentCategoryTitle));

        /*
        header("Content-Type: text/plain");
        $xmlDoc->formatOutput = true;
        $requestBody = $xmlDoc->saveXML();
        echo $requestBody;
        die();
        */

        parent::flush();
        parent::setUrl('http://'.$this->host.':'.$this->port.'/rest/presentation');
        parent::setVerb('PUT');
        parent::setRequestBody($xmlDoc->saveXML());
        parent::execute();
        return print_r(parent::getResponseBody());
    }
}

/**
 */
function getPostVar($var, $def="")
{
    return isset($_POST[$var])?$_POST[$var]:$def;
}

/**
 */
function XMLToArrayFlat($xml, &$return, $path='', $root=false)
{
    $children = array();
    if ($xml instanceof SimpleXMLElement) {
        $children = $xml->children();
        if ($root) { // we're at root
            $path .= '/'.$xml->getName();
        }
    }
    if (count($children) == 0) {
        $return[$path] = (string)$xml;
        return;
    }
    $seen=array();
    foreach ($children as $child => $value) {
        $childname = ($child instanceof SimpleXMLElement)?$child->getName():$child;
        if (!isset($seen[$childname])) {
            $seen[$childname]=0;
        }
        $seen[$childname]++;
        XMLToArrayFlat($value, $return, $path.'/'.$child.'['.$seen[$childname].']');
    }
}

/**
 */
function tr($token, $def="")
{
    global $language, $translation;
    if (strlen($language)==2 && file_exists("i18n/messages_${language}.properties")) {
        // OK
    } else {
        $language = "en";
    }
    if (!is_array($translation) || count($translation)<1) {
        // Load
        $translation = array();
        $handle = @fopen("i18n/messages_${language}.properties", "r");
        if ($handle) {
            $append = false;
            while (($buffer = fgets($handle, 4096)) !== false) {
                $buffer = trim($buffer);
                if ($append) {
                    if (substr($buffer, strlen($buffer)-1, 1)!="\\") {
                        $append=false;
                    }
                    $translation[$key].= str_replace("\\", "\n", $buffer);
                    continue;
                }
                $pos = strpos($buffer, "=");
                if ($pos!==false) {
                    $key = trim(substr($buffer, 0, $pos));
                    $val = trim(substr($buffer, $pos+1));
                    if (substr($val, strlen($val)-1, 1)=="\\") {
                        $append = true;
                    }
                    $translation[$key] = str_replace("\\", "\n", $val);
                }
            }
            if (!feof($handle)) {
                //echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
    }
    return array_key_exists($token, $translation)?$translation[$token]:($def==""?$token:$def);
}

?>
