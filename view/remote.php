<form method="post" action="" id="remoteform">
<input type="hidden" name="tab" value="remote">
<input type="hidden" id="process" name="process" value="">
<br />
<?php echo tr('tab_remote_choose_settings','Choose your settings for remote access to your files. If you want to access the server over the Internet, you have to set up your router for incoming outside connections.')?>
<br>
<br>
<ul id="rmtSecurityTab" class="shadetabs">
    <li><a href="#" rel="rmt1" class="selected"><?php echo tr('tab_remote_security','Security')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="rmt1" class="tabcontent">
        <br>
        <?php echo tr('tab_remote_user_password','User Password')?>:&nbsp;
        <input type="password" id="userPasswd" name="userPasswd" size="40" value="<?php echo $rmtAccess['remoteUserPassword']?>">
        <br>
    </div>
</div>

<br>

<ul id="rmtDeliveryQualityTab" class="shadetabs">
    <li><a href="#" rel="rmt2" class="selected"><?php echo tr('tab_remote_content_delivery','Content Delivery')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="rmt2" class="tabcontent">
        <br>
        <?php echo tr('tab_remote_preferred_delivery_quality','Preferred delivery quality')?>:&nbsp;
        <select id="deliveryQuality" name="deliveryQuality">
        	
        	<?php foreach ($quality as $key=>$val) { ?>
			<option value="<?php echo $key?>"<?php echo $key==$rmtAccess['preferredRemoteDeliveryQuality']?" selected":""?>><?php echo $val?></option>
			<?php } ?>
        	
        </select>
        <br>
    </div>
</div>

<br>

<ul id="rmtInternetAccessTab" class="shadetabs">
    <li><a href="#" rel="rmt3" class="selected"><?php echo tr('tab_remote_internet_access','Internet access')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="rmt3" class="tabcontent">
        <br>
        <input type="checkbox" name="portMapping" value="1"<?php echo $rmtAccess['portMappingEnabled']=="true"?" checked":""?>> <?php echo tr('tab_remote_port_mapping','Automatically configure your router to allow incoming internet connections')?>
        <br>
        <br>
        <?php echo tr('tab_remote_external_address','External address')?>:&nbsp;
        <input type="text" id="extAddress" name="extAddress" size="64" value="<?php echo $rmtAccess['externalAddress']?>">
        <br>
        <br>
        <input type="submit" id="checkPortMapping" name="checkPortMapping" value="<?php echo tr('button_check_port_mapping','Check connectivity status')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
		<span id="checkPortMappingMsg" class="checkPortMappingMsg"></span>
    </div>
</div>

<div style="float: left;">
	<?php foreach ($interfaces as $key=>$val) { ?>
	<?php if($key==$boundNIC['boundNICName']) echo '<a href="http://'.substr($val, 0, -7).':23424/mediabrowser/" target="_blank">'.tr('tab_remote_open_mediabrowser','Open MediaBrowser').'</a>'; ?>
	<?php }?>
</div>
<div align="right" style="float: right;">
    <span id="savingMsg" class="savingMsg"></span>
    <input type="submit" id="reset" name="reset" value="<?php echo tr('button_reset','Reset')?>" onclick=indexes.expandit(5) class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
    <input type="submit" id="submit" name="save" value="<?php echo tr('button_save','Save')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
</div>
<br />
</form>
