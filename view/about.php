<br />
<div align="center">
<img src="images/logo.png" alt="serviio" />
<br>
<?php $appdata = $serviio->getApplication(); ?>
<b>Serviio <?php $ret = $serviio->getApplication(); echo $ret['edition'];?> v <?php echo $ret['version'];?> <?php echo $ret['licenseType'];?> license</b>
-&nbsp;<b>WebUI v <?php echo $webUIver;?></b>
<br>
<br>
<?php
    echo tr('tab_about_license_to','Licensed To: ');
    echo $ret['licenseName'] . " (" . $ret['licenseEmail'] . ")";
    echo "<br>";
?>

<form method="post" action="" id="licenseform" name="license" accept-charset="utf-8">
<input type="hidden" id="process" name="process" value="">

<span id="uploadLicense">
    <a class="ui-button ui-widget ui-state-default ui-corner-all btn-small"><?php echo tr('dialog_load_license','Load new license')?></a>
    <input type="file" name="upl" id="upl" multiple />
</span>
<br>

</form>
	<span id="savingMsg" class="savingMsg"></span>
<br>
<?php echo tr('tab_about_text','DLNA media streaming server<br>Copyright 2009-2015 Petr Nejedly<br>
Web UI for Serviio maintained by Sascha Eilers<br>
<a href="http://serviio.org">http://serviio.org</a><br><br>
This product may use movie metadata provided bt <a href="http://www.themoviedb.org/">TheMovieDb.org</a>, please consider contributing to the database.<br>
This product may use TV metadata provided by <a href="http://www.thetvdb.com/">TheTVDb.com</a>, please consider contributing to the database.<br>
This product includes portions of <a href="http://www.ffmpeg.org/">FFmpeg</a> (a great video library) which is licensed under <a href="http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html">LGPL v2.1</a>.<br>
This product includes <a href="http://lame.sourceforge.net/">LAME MP3 Encoder</a> (a high quality MPEG Audio Layer III (MP3) encoder) which is licensed under <a href="http://www.gnu.org/licenses/old-licenses/lgpl-2.0.html">LGPL v2.0</a>.<br>
This product includes software developed by <a href="http://www.apache.org/">The Apache Software Foundation</a> which is licensed under <a href="http://www.apache.org/licenses/LICENSE-2.0">ASL v2.0</a>.<br>
This product includes software developed by the <a href="http://www.visigoths.org/">Visigoth Software Society</a>.<br>
This product includes <a href="http://www.restlet.org/">Restlet</a> which is licensed under <a href="http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html">LGPL v2.1</a>.<br>
This product includes <a href="http://www.jthink.net/jaudiotagger/">Jaudiotagger</a> (an audio tagging library) which is licensed under <a href="http://www.gnu.org/copyleft/lesser.html">LGPL</a>.<br>')?>
</div>