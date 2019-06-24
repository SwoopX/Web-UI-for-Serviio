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
			$categories = array();
			$presentation_language = getPostVar("presentation_language", "en");
			$showParentCategoryTitle = getPostvar("showParentCategoryTitle", "0")=="1"?"true":"false";
			$numberOfFilesForDynamicCategories = getPostvar("numberOfFilesForDynamicCategories", "10");
			$filterOutSeries = getPostvar("filterOutSeries", "0")=="1"?"true":"false";

			$titles = isset($_POST['titles'])?$_POST['titles']:0;
			$visibilities = isset($_POST['visibility'])?$_POST['visibility']:0;

			if (is_array($titles) && count($titles)>0 && is_array($visibilities) && count($visibilities)>0) {
				foreach ($titles as $id=>$entry) {
					$title = "";
					$visibility = "";
					$subCategories = array();
					foreach ($entry as $sid=>$val) {
						if ($sid!="title") {
							$subVisibility = $visibilities[$id][$sid];
							$subCategories[$sid] = array($val, $subVisibility);
						}
					}
					$title = $titles[$id]['title'];
					$visibility = $visibilities[$id]['visibility'];
					$categories[$id] = array($title, $visibility, $subCategories);
				}
			}
			$errorCode = $serviio->putPresentation($categories, $presentation_language, $showParentCategoryTitle, $numberOfFilesForDynamicCategories, $filterOutSeries);
			return $errorCode;
		}
	}
	
	$categories = $serviio->getPresentation();
	$categoryVisibilityTypes = $serviio->getReferenceData('categoryVisibilityTypes');
	$browsingCategoriesLanguages = $serviio->getReferenceData('browsingCategoriesLanguages');
?>