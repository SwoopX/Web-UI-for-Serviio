<?php
	$types = array('VIDEO','AUDIO','IMAGE');

	if ($_SERVER["REQUEST_METHOD"]=="POST") {
		include("../config.php");
		include("../lib/RestRequest.inc.php");
		include("../lib/serviio.php");

		// initiate call to service
		$serviio = new ServiioService($serviio_host,$serviio_port);

		/*****************************************************************/
		/*****************************************************************/
		if (getPostVar("process", "") == "refresh") {
			$errorCode = $serviio->postAction("forceLibraryRefresh");
			return $errorCode;
		}

		/*****************************************************************/
		/*****************************************************************/
		elseif (getPostVar("process", "") == "OSrefresh") {
			$errorCode = $serviio->postAction("forceOnlineResourceRefresh", array(getPostVar("os_no", "")));
			return $errorCode;
		}
        
        /*****************************************************************/
		/*****************************************************************/
		elseif (getPostVar("process", "") == "checkURL") {
			$errorCode = $serviio->postAction("checkStreamUrl", array(getPostVar("MediaType", ""), getPostVar("SourceURL", "")));
			return $errorCode;
		}

		/*****************************************************************/
		/*****************************************************************/
		elseif (getPostVar("process", "") == "save") {
			$errorCode = 0;
			$lastFId = getPostVar("lastFId", "0");
			$lastOSId = getPostVar("lastOSId", "0");
			$repo = array();
			$accGrpIds = "";
			foreach ($_POST as $key=>$val) {
				if (substr($key, 0, 7)=="folder_") {
					$id = substr($key, 7);
					$items = array();
					$videosel = false;
					foreach ($types as $type) {
						if (getPostVar("${type}_${id}", "0")==1) {
							$items[] = $type;
							if ($type == "VIDEO") {
								$videosel = true;
							}
						}
					}

					// this takes care of the issue where video isn't selected but metadata retrieval is
					if ($videosel == true) {
						$metaval = getPostVar("ONLINE_${id}", "0")==1?"true":"false";
					} else {
						$metaval = "false";
					} 
					$repo[0][$id] = array(
						getPostVar("name_${id}", ""),                       //[0]folderPath
						$items,                                             //[1]fileType
						$metaval,                                           //[2]descriptiveMetadataSupported
						//getPostVar("SCAN_${id}", "0")==1?"true":"false",  //[]scanForUpdates
						getPostVar("folder_${id}", "new"),                  //[3]id
						array(getPostVar("access_${id}", "")));             //[4]accessGroupIds
				}

				// Online Sources
				if (substr($key, 0, 13)=="onlinesource_") {
					$id = substr($key, 13);
					$repo[1][$id] = array(
						getPostVar("os_type_${id}", ""),
						getPostVar("os_url_${id}", ""),
						getPostVar("os_media_${id}", ""),
						getPostVar("onlinesource_${id}", ""),
						getPostVar("os_name_${id}", ""),
						getPostVar("os_stat_${id}", ""),
						getPostVar("os_thumb_${id}", ""),
						array(getPostVar("os_access_${id}", ""))
						);
				}
			}

			$serviio->searchHiddenFiles = getPostVar("addhidden", "0")==1?"true":"false";
			$serviio->searchForUpdates = getPostVar("searchupdates", "0")==1?"true":"false";
			$serviio->automaticLibraryUpdate = getPostVar("autoupdate", "0")==1?"true":"false";
			//$serviio->automaticLibraryUpdateInterval = getPostVar("minutes", "0");

			$serviio->maxNumberOfItemsForOnlineFeeds = getPostVar("maxfeeditems", "20");
			$serviio->onlineFeedExpiryInterval = getPostVar("feedexpiry", "10");
			$serviio->onlineContentPreferredQuality = getPostVar("onlinequality", "LOW");

			$errorCode = $serviio->putRepository($repo);
			return $errorCode;
		}
		
		/*****************************************************************/
		/*****************************************************************/
		elseif (getPostVar("process", "") == "import") {
			
			$backup = getPostVar("backup", "");
            $serviio->putImportExport($backup);
            return;

		}
		
		/*****************************************************************/
		/*****************************************************************/
		elseif (getPostVar("process", "") == "export") {
			
			$errorCode = $serviio->getImportExport();
			return $errorCode;
		}
		
		/*****************************************************************/
		/*****************************************************************/
        //Required for export functionality of the library tab
		elseif(!empty($_POST['filename']) || !empty($_POST['content'])){
			$filename = preg_replace('/[^a-z0-9\-\_\.]/i','',$_POST['filename']);

			// Outputting headers:
			header("Cache-Control: ");
			header("Content-type: text/plain");
			header('Content-Disposition: attachment; filename="'.$filename.'"');

			//workaround for magic quotes and ampersand needs to be decoded as encoding was submit requirement
			//echo str_replace('&amp;','&',str_replace('\\','',$_POST['content']));
			echo str_replace('\\','',$_POST['content']);
			return "";
		}
		
		/********************************************************/
		echo "<xml>Failed to get proper posting value!</xml>";
		return "";

	}
	
	$repo = $serviio->getRepository();
	$serviio->getApplication();
	$accesses = $serviio->getReferenceData('accessGroups');
	$onlineQuality = $serviio->getReferenceData('onlineContentQualities');
	$feedTypes = $serviio->getReferenceData('onlineRepositoryTypes');
?>
