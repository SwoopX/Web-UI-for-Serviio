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

			$audio_cover = getPostVar("audio_cover","0")==1?"true":"false";			// audioLocalArtExtractorEnabled
			$thumbnails = getPostVar("thumbnails","0")==1?"true":"false";			// videoGenerateLocalThumbnailEnabled
			$img_thumbnails = getPostVar("img_thumbnails","0")==1?"true":"false";	// imageGenerateLocalThumbnailEnabled
			$cover_search = getPostVar("cover_search","0")==1?"true":"false";		// videoLocalArtExtractorEnabled
			$online_sources = getPostVar("online_sources","NONE");					// descriptiveMetadataExtractor
			$download_cover = getPostVar("download_cover","0")==1?"true":"false";	// videoOnlineArtExtractorEnabled
			$metadata_language = getPostVar("metadata_language","en");				// metadataLanguage
			$orig_title = getPostVar("orig_title","0")==1?"true":"false";			// retrieveOriginalTitle
            if (getPostVar("enable_video_filter", "0") == "1") {
                $filter_rating = getPostVar("filter_videos","");		            // filterVideosByRating
            } else {
                $filter_rating = "";
            }
			$errorCode = $serviio->putMetadata($audio_cover,$cover_search,$download_cover,$thumbnails,$img_thumbnails,$metadata_language,$online_sources,$orig_title,$filter_rating);

			return $errorCode;
		}

		/*****************************************************************/
		/*****************************************************************/
		elseif (getPostVar("process", "") == "rescan") {
			//Force metadata refresh
			$errorCode = $serviio->postAction("forceVideoFilesMetadataUpdate");
			return $errorCode;
		}
	}
	
	$serviio->getMetadata();
    $serviio->getApplication();
	$descriptiveMetadataExtractors = $serviio->getReferenceData('descriptiveMetadataExtractors');
	$metadataLanguages = $serviio->getReferenceData('metadataLanguages');
    $ratings = $serviio->getReferenceData('ratings');
?>
