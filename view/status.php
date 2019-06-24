<form id="statusform" method="post" action="" accept-charset="utf-8">
	<input type="hidden" name="tab" value="status">
	<input type="hidden" id="process" name="process" value="">

<br>
<ul id="serverstatustab" class="shadetabs">
    <li><a href="#" rel="svrstat1" class="selected"><?php echo tr('tab_status_server_status','Server Status')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="svrstat1" class="tabcontent">
        <?php echo tr('tab_status_description','Start/Stop the UPnP/DLNA server. The actual Serviio process is not affected.')?><br>
        <br>
        <input type="submit" name="start" id="start" value="<?php echo tr('button_start_server','Start server')?>" onclick="return confirm('<?php echo tr('status_message_start_server','Are you sure you want to start the server?')?>');" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
        <input type="submit" name="stop" id="stop" value="<?php echo tr('button_stop_server','Stop server')?>" onclick="return confirm('<?php echo tr('status_message_stop_server','Are you sure you want to stop the server?')?>');" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
    </div>
</div>

<ul id="rendererprofiletab" class="shadetabs">
    <li><a href="#" rel="rendprof1" class="selected"><?php echo tr('tab_status_renderer_profile','Renderer Profile')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="rendprof1" class="tabcontent">
<?php echo tr('tab_status_profile_overview','Select an appropriate rendering device profile. It will affect how Serviio communicates with the device. Particular devices may require a particular communication protocol.')?><br>
<br>
<table>
<tr valign="top">
    <td><table id="rendererTable" name="rendererTable" border="0">
    <thead>
        <th></th>
        <th width="20" align="center"></th>
        <th width="130" align="left"><?php echo tr('tab_status_renderer_table_ipaddress','IP Address')?></th>
        <th width="212" align="left"><?php echo tr('tab_status_renderer_table_device_name','Device Name')?></th>
        <th width="60" align="center"><?php echo tr('tab_status_renderer_table_enabled','Enabled')?></th>
        <th width="160" align="center"><?php echo tr('tab_status_renderer_table_access','Access')?></th>
        <th width="310" align="left"><?php echo tr('tab_status_renderer_table_profile','Profile')?></th>
        <th class="scrollbarSpacer"></th>
    </thead>
    <tbody>
    <?php foreach ($statusResponse["renderers"] as $id=>$renderer) { ?>
    <tr id="id_renderer_<?php echo $id?>">
        <td>
            <input type="hidden" id="enabled_<?php echo $id?>" name="enabled_<?php echo $id?>" value="<?php echo $renderer[4]?>">
            <input type="hidden" name="renderer_<?php echo $id?>" value="<?php echo $id?>">
            <input type="hidden" name="name_<?php echo $id?>" value="<?php echo $renderer[1]?>">
            <input type="hidden" name="ipAddress_<?php echo $id?>" value="<?php echo $renderer[0]?>">
            <input type="hidden" name="access_<?php echo $id?>" value="<?php echo $renderer[5]?>">
        </td>
        <td width="20" align="center"><?php echo status_icon($renderer[3])?></td>
        <td width="130" align="left"><?php echo $renderer[0]?></td>
        <td width="212" align="left"><?php echo $renderer[1]?></td>
        <td width="60" align="center">
            <div class="os_switch" id="enabled_<?php echo $id?>" style="cursor: pointer; ">
                <div class="iphone_switch_container" style="height:27px; width:94px; position: relative; overflow: hidden">
                    <img class="iphone_switch" style="height: 27px; width: 94px; background-image: url(images/iphone_switch_16.png); background-position: 0px 50%; " src="images/iphone_switch_container_off.png">
                </div>
            </div>
        </td>
        <td width="160" align="center">
            <select name="access_<?php echo $id?>" <?php echo ($serviio->licenseEdition=="PRO"?'':'disabled="disabled" title="Enabled with PRO License"')?>>
            <?php foreach ($accesses as $key=>$val) {
                if($val=="No_Restriction") {
                    $val=tr('accessGroupFull','No Restriction');
                }
                elseif($val=="Limited_Access") {
                    $val=tr('accessGroupLimited','Limited Access');
                } ?>
                <option value="<?php echo $key?>"<?php echo $key==$renderer[5]?" selected":""?>><?php echo $val?></option>
            <?php } ?>
            </select>
        </td>
        <td width="310" align="left">
            <select name="profile_<?php echo $id?>">
            <?php foreach ($profiles as $key=>$val) { ?>
                <option value="<?php echo $key?>"<?php echo $key==$renderer[2]?" selected":""?>><?php echo $val?></option>
            <?php } ?>
            </select>
        </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
	
	<br>
	
	<table width="880">
		<tr>
			<td>
				<input type="checkbox" name="rendererEnabledByDefault" value="1"<?php echo $statusResponse["rendererEnabledByDefault"]=="true"?" checked":""?>> <?php echo tr('tab_status_enable_renderer_by_default','Enable access for new devices')?>
            </td>
			<td>
				<?php echo tr('tab_status_default_access_group','Default access group').": "?>
				<select name="defaultAccessGroupId" <?php echo ($serviio->licenseEdition=="PRO"?'':'disabled="disabled" title="Enabled with PRO License"')?>>
					<?php foreach ($accesses as $key=>$val) {
						if($val=="No_Restriction") {
							$val=tr('accessGroupFull','No Restriction');
						}
						elseif($val=="Limited_Access") {
							$val=tr('accessGroupLimited','Limited Access');
						} ?>
						<option value="<?php echo $key?>"<?php echo $key==$statusResponse["defaultAccessGroupId"]?" selected":""?>><?php echo $val?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</table>

	<td>
    <td width="100">
    <button type="submit" id="renderer_refresh" name="renderer_refresh" onclick=indexes.expandit(0) class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
        <?php echo tr('button_refresh','Refresh')?>
    </button>
    <br>
    <br>
    <button type="button" id="remove-renderer" name="remove-renderer" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
        <?php echo tr('button_remove','Remove')?>
    </button>
    </td>
</tr>
</table>
<script type="text/javascript">
<!--
var profiles = new Array();
<?php foreach ($profiles as $key=>$val) { ?>
    profiles['<?php echo $key?>'] = '<?php echo $val?>';
<?php } ?>
// -->
</script>
    </div>
</div>

<ul id="networksettingtab" class="shadetabs">
<li><a href="#" rel="netset1" class="selected"><?php echo tr('tab_status_network_settings','Network Settings')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="netset1" class="tabcontent">
		<?php echo tr('tab_status_bound_ip_address','Bound IP address')?>:&nbsp;
		<select name="bound_nic">
			<?php array_unshift($interfaces, tr('tab_status_bound_autodetect','Automatically detected')); foreach ($interfaces as $key=>$val) { ?>
			<option value="<?php if($key=="0") {
                                    echo "";
                                 }
                                 else {
                                    echo $key;
                                 }?>"<?php echo $key==$statusResponse["boundNICName"]?" selected":""?>><?php echo $val?></option>
			<?php } ?>
		</select>
    </div>
</div>




<div align="right">
<span id="savingMsg" class="savingMsg"></span>
<input type="submit" id="reset" name="reset" value="<?php echo tr('button_reset','Reset')?>" onclick="return confirm('<?php echo tr('status_message_reset','Are you sure you want to reset changes?')?>')" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
<input type="submit" id="submit" name="save" value="<?php echo tr('button_save','Save')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
</div>
</form>

<div id="dialog-remove-renderer">
</div>
