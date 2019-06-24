<form method="post" action="" id="settingsform" accept-charset="utf-8">
<input type="hidden" name="tab" value="settings">
<input type="hidden" id="process" name="process" value="">
<br />
<ul id="consolesettingstab" class="shadetabs">
    <li><a href="#" rel="conset1" class="selected"><?php echo tr('tab_console_settings','Console settings')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="conset1" class="tabcontent">
        <?php echo tr('tab_console_settings_languages_description','Select preferred language of the Console (needs Console restart).')?>
        <br>
        <br>
        <select name="language">
        <?php foreach ($languages as $key=>$val) { ?>
        <option value="<?php echo $key?>"<?php echo $key==$settings["language"]?" selected":""?>><?php echo $val?></option>
        <?php } ?>
        </select>
        <br>
        <br>
        <input type="checkbox" name="checkForUpdates" value="1"<?php echo $settings["checkForUpdates"]=="true"?" checked":""?>> <?php echo tr('tab_console_settings_check_for_new_versions','Check for new versions')?><br>
        <br>
    </div>
</div>

<div align="right">
    <span id="savingMsg" class="savingMsg"></span>
    <input type="submit" id="reset" name="reset" value="<?php echo tr('button_reset','Reset')?>" onclick="return confirm('<?php echo tr('status_message_reset','Are you sure you want to reset changes?')?>')" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
    <input type="submit" id="submit" name="save" value="<?php echo tr('button_save','Save')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
</div>
</form>
