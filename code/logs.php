<?php
	if ($_SERVER["REQUEST_METHOD"]=="POST") {
		/*****************************************************************/
		/*****************************************************************/
		if (isset($_POST["logfile"])) {
			$logfile = $_POST["logfile"];
			$_COOKIE["logfile"] = $logfile;
			setcookie("logfile",$logfile,mktime(12,0,0,9,9,2020),"/");	
			return true;
		}
	}
?>