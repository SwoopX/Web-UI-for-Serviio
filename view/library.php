<form method="post" action="" id="libraryform" name="library" accept-charset="utf-8">
    <input type="hidden" name="tab" value="library">
    <input type="hidden" id="process" name="process" value="">
    <br>

    <ul id="librarytabs" class="shadetabs">
    <li><a href="#" rel="libtab1" class="selected"><?php echo tr('tab_library_shared_folders','Shared Folders')?></a></li>
    <li><a href="#" rel="libtab2"><?php echo tr('tab_library_online_sources','Online Sources')?></a></li>
    <li><a href="#" rel="libtab3"><?php echo tr('tab_library_installed_plugins','Installed Plugins')?></a></li>
    </ul>
    <div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">

        <div id="libtab1" class="tabcontent">
            <?php echo tr('tab_library_description','Select folders that you want to monitor for media files. Also select type of media files to be shared for each folder. The library can be automatically monitored for new additions and updates to currently shared files.')?><br>
            <br>
            <div style="padding-left: 3px;">
                <button type="button" id="addLocalFolder" name="addLocalFolder" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
                    <?php echo tr('button_add_local','Add local...')?>
                </button>&nbsp;&nbsp;
                <button type="button" id="addPath" name="addPath" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
                    <?php echo tr('button_add_path','Add path...')?>
                </button>&nbsp;&nbsp;
                <button type="button" id="removeFolder" name="removeFolder" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
                    <?php echo tr('button_remove','Remove Selected')?>
                </button>
                <br>
            </div>
            <p></<p>
                <table id="libraryTableFolders" name="libraryTableFolders" border="0">
                    <thead align="center">
                        <th width="4"></th>
                        <th width="578px" align="left"><?php echo tr('tab_library_repository_table_folder','Folder')?></th>
                        <th width="130px" align="center"><?php echo tr('tab_library_repository_table_access','Access')?></th>
                        <th width="30px" align="center"><img src="images/icon_video.png" height="16" alt="<?php echo tr('tab_library_repository_table_share_video','Share video files')?>" title="<?php echo tr('tab_library_repository_table_share_video','Share video files')?>"></th>
                        <th width="30px" align="center"><img src="images/icon_music.png" height="16" alt="<?php echo tr('tab_library_repository_table_share_audio','Share audio files')?>" title="<?php echo tr('tab_library_repository_table_share_audio','Share audio files')?>"></th>
                        <th width="30px" align="center"><img src="images/icon_camera.png" height="16" alt="<?php echo tr('tab_library_repository_table_share_images','Share image files')?>" title="<?php echo tr('tab_library_repository_table_share_images','Share image files')?>"></th>
                        <th width="30px" align="center"><img src="images/document-attribute-m.png" alt="<?php echo tr('tab_library_repository_table_retrieve_descriptive_metadata','Retrieve descriptive metadata')?>" title="<?php echo tr('tab_library_repository_table_retrieve_descriptive_metadata','Retrieve descriptive metadata')?>"></th>
                        <th class="scrollbarSpacer"></th>
                    </thead>
                    <tbody>
                        <?php $midA = 1; foreach ($repo[0] as $id=>$entry) { if ($id>$midA) { $midA = $id; } ?>
                            <tr align="center" id="id_folder_<?php echo $id?>">
                                <td width="4"></td>
                                <td width="578px" align="left" id="path"><?php echo $entry[0]?></td>
                                <td width="130px">
                                <?php
                                    if ($serviio->licenseEdition=="PRO") {
                                        echo '<select name="access_'.$id.'">';
                                        foreach ($accesses as $key=>$val) {
                                            if($val=="No_Restriction") {
                                                $val="No Restriction";
                                            }
                                            elseif($val=="Limited_Access") {
                                                $val="Limited Access";
                                            }
                                            echo '<option value="'.$key.'"'.($key==max($entry[3])?' selected':'').'>'.$val.'</option>';
                                        }
                                        echo '</select>';
                                    }
                                    else {
                                        echo '<select name="access_'.$id.'" disabled="disabled" title="Only enabled with PRO license">';
                                        echo '<option value="1">No_Restrictions</option>';
                                        echo '</select>';
                                        echo '<input type="hidden" id="access_'.$id.'" name="access_'.$id.'" value="1">';
                                    }
                                ?>
                                </td>
                                <?php for ($i=0;$i<count($types);$i++) { $type = $types[$i]; ?>
                                    <td width="30px"><input type="checkbox" name="<?php echo $type."_".$id?>" value="1"<?php echo array_search($type,$entry[1])===false?"":" checked"?>></td>
                                <?php } ?>
                                <td width="30px"><input type="checkbox" name="ONLINE_<?php echo $id?>" value="1"<?php echo $entry[2]=="false"?"":" checked"?>></td>
                                <td>
                                    <input type="hidden" name="folder_<?php echo $id?>" value="<?php echo $id?>">
                                    <input type="hidden" name="name_<?php echo $id?>" value="<?php echo htmlspecialchars($entry[0])?>">
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <input type="hidden" id="lastFId" name="lastFId" value="<?php echo $midA?>">
            <br>
            <input type="checkbox" name="searchupdates" value="1"<?php echo $serviio->searchForUpdates=="true"?" checked":""?>> <?php echo tr('tab_library_search_for_files_updates','Search for updates of currently shared files')?>
            <br>
            <input type="checkbox" name="addhidden" value="1"<?php echo $serviio->searchHiddenFiles=="true"?" checked":""?>> <?php echo tr('tab_library_include_hidden','Include hidden files')?>
            <br>
            <br>

            <ul id="librarystatustabs" class="shadetabs">
            <li><a href="#" rel="libstab1" class="selected"><?php echo tr('tab_library_status_panel','Library Status')?></a></li>
            </ul>
            <div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
                <div id="libstab1" class="tabcontent">
                    <input type="checkbox" name="autoupdate" value="1"<?php echo $serviio->automaticLibraryUpdate=="true"?" checked":""?>> <?php echo tr('tab_library_automatic_update','Keep library automatically updated')?>&nbsp;&nbsp;&nbsp;
                    <input type="submit" id="refresh" name="refresh" value="<?php echo tr('button_refresh_library','Force refresh')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
                    <span id="forceRefreshMsg" class="forceRefreshMsg"></span>
                </div>
            </div>

        </div>

        <div id="libtab2" class="tabcontent">
            <?php echo tr('tab_library_online_sources_description','Define online source that you would like to access. Online sources are constantly monitored for updates and cached for a period of time. It might take a moment for new sources to appear on your device.')?><br>
            <br>
            <div style="padding-left: 3px;">
                <button type="button" id="add_os" name="add_os" class="ui-button ui-widget ui-state-default ui-corner-all btn-small">
                    <?php echo tr('button_add','Add')?>
                </button>&nbsp;&nbsp;
                <button type="button" id="edit_os" name="edit_os" class="ui-button ui-widget ui-state-default ui-corner-all btn-small">
                    <?php echo tr('button_edit','Edit')?>
                </button>&nbsp;&nbsp;
                <button type="button" id="removeOnlineSource" name="removeOnlineSource" class="ui-button ui-widget ui-state-default ui-corner-all btn-small">
                    <?php echo tr('button_remove','Remove')?>
                </button>&nbsp;&nbsp;
                <span id="importOnlineSource">
                    <a class="ui-button ui-widget ui-state-default ui-corner-all btn-small"><?php echo tr('button_import','Import')?></a>
                    <input type="file" name="upl" id="upl" multiple /></span>&nbsp;&nbsp;
                <button type="button" id="exportOnlineSource" name="exportOnlineSource" class="ui-button ui-widget ui-state-default ui-corner-all btn-small">
                    <?php echo tr('button_export','Export')?>
                </button>
                <br>
            </div>
            <p></p>
            <table id="libraryTableOnlineSources" name="libraryTableOnlineSources" border="0">
                <thead>
                    <th width="20px"></th>
                    <th width="120px" align="left"><?php echo tr('tab_library_online_sources_repository_table_type','Type')?></th>
                    <th width="80px" align="left"><?php echo tr('tab_library_online_sources_repository_table_mediatype','Media Type')?></th>
                    <th width="60px" align="center"><?php echo tr('tab_library_online_sources_repository_table_enabled','Enabled')?></th>
                    <th width="130px" align="center"><?php echo tr('tab_library_online_sources_repository_table_access','Access')?></th>
                    <th width="60px" align="center"><?php echo tr('tab_library_online_sources_repository_table_refresh','Refresh')?></th>
                    <th width="80px" align="center"><?php echo tr('tab_library_online_sources_repository_table_serviiolink','ServiioLink')?></th>
                    <th width="340px" align="left"><?php echo tr('tab_library_online_sources_repository_table_url','Name / URL')?></th>
                    <th class="scrollbarSpacer"></th>
                </thead>
                <tbody>
                <?php $midB = 1; foreach ($repo[1] as $id=>$entry) { if ($id>$midB) { $midB = $id; } ?>
                <tr id="id_os_<?php echo $id?>">
                    <td width="20px" class="handle"><span class='ui-icon ui-icon-carat-2-n-s'></span></td>
                    <td width="120px"><span id="os_type_v_<?php echo $id?>">
                        <?php if ($entry[0] == "FEED") {?>
                            <img src="images/icon_feed.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_feed','Feed')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_feed','Feed')?>
                        <?php } else if ($entry[0] == "WEB_RESOURCE") {?>
                            <img src="images/icon_web_resource.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_web_resource','Web resource')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_web_resource','Web resource')?>
                        <?php } else if ($entry[0] == "LIVE_STREAM") {?>
                            <img src="images/icon_satelite_black.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_live_steam','Live stream')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_live_steam','Live stream')?>
                        <?php } ?>
                    </span></td>
                    <td width="80px"><span id="os_media_v_<?php echo $id?>">
                        <?php if ($entry[2] == "VIDEO") {?>
                            <img src="images/icon_video.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_video','Video')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_video','Video')?>
                        <?php } else if ($entry[2] == "AUDIO") {?>
                            <img src="images/icon_music.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_audio','Audio')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_audio','Audio')?>
                        <?php } else if ($entry[2] == "IMAGE") {?>
                            <img src="images/icon_camera.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_images','Image')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_images','Image')?>
                        <?php } ?>
                    </span></td>
                    <td width="60px" align="center">
                        <div class="os_switch" id="os_switch_<?php echo $id?>" style="cursor: pointer; ">
                            <div class="iphone_switch_container" style="height:27px; width:94px; position: relative; overflow: hidden">
                                <img class="iphone_switch" style="height: 27px; width: 94px; background-image: url(images/iphone_switch_16.png); background-position: 0px 50%; " src="images/iphone_switch_container_off.png">
                            </div>
                        </div>
                    </td>
                    <td width="130px" align="center">
                        <?php
                            if ($serviio->licenseEdition=="PRO") {
                                echo '<select name="os_access_'.$id.'">';
                                foreach ($accesses as $key=>$val) {
                                    if($val=="No_Restriction") {
                                        $val="No Restriction";
                                    }
                                    elseif($val=="Limited_Access") {
                                        $val="Limited Access";
                                    }
                                    echo '<option value="'.$key.'"'.($key==max($entry[6])?' selected':'').'>'.$val.'</option>';
                                }
                                echo '</select>';
                            }
                            else {
                                echo '<select name="os_access_'.$id.'" disabled="disabled" title="Only enabled with PRO license">';
                                echo '<option value="1">No Restrictions</option>';
                                echo '<input type="hidden" id="os_access_'.$id.'" name="os_access_'.$id.'" value="1">';
                                echo '</select>';
                            }
                        ?>
                    </td>
                    <td width="60px" align="center"><span id="os_refresh_<?php echo $id?>">
                        <a style="background-color: yellow;" class="refresh-link" os_no="<?php echo $id?>" href="">&nbsp;Refresh&nbsp;</a>
                        </span>
                    </td>
                    <td width="80px" align="center"><span id="os_serviiolink_<?php echo $id?>">
                        <img src="images/icon_serviiolink.gif" height="16" onClick='alert("<?php echo 'serviio://'.strtolower($entry[2]).':'.strtolower($entry[0]).'?url='.htmlspecialchars($entry[1]).'&name='.urlencode($entry[4])?>")'>
                        </span>
                    </td>
                    <td width="340px"><span id="os_name_v_<?php echo $id?>" name="os_name_v_<?php echo $id?>" title="<?php echo htmlspecialchars($entry[1])?>"><?php echo $entry[4]==""?htmlspecialchars($entry[1]):htmlspecialchars($entry[4])?></span></td>
                    <td>
                        <input type="hidden" id="onlinesource_<?php echo $id?>" name="onlinesource_<?php echo $id?>" value="<?php echo $id?>">
                        <input type="hidden" id="os_type_<?php echo $id?>" name="os_type_<?php echo $id?>" value="<?php echo $entry[0]?>">
                        <input type="hidden" id="os_url_<?php echo $id?>" name="os_url_<?php echo $id?>" value="<?php echo htmlspecialchars($entry[1])?>">
                        <input type="hidden" id="os_media_<?php echo $id?>" name="os_media_<?php echo $id?>" value="<?php echo $entry[2]?>">
                        <input type="hidden" id="os_thumb_<?php echo $id?>" name="os_thumb_<?php echo $id?>" value="<?php echo htmlspecialchars($entry[3])?>">
                        <input type="hidden" id="os_name_<?php echo $id?>" name="os_name_<?php echo $id?>" value="<?php echo htmlspecialchars($entry[4])?>">
                        <input type="hidden" id="os_stat_<?php echo $id?>" name="os_stat_<?php echo $id?>" value="<?php echo $entry[5]?>">
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            <input type="hidden" id="lastOSId" name="lastOSId" value="<?php echo $midB?>">
            <br>
            <table>
                <tr><td>
                    <?php echo tr('tab_library_online_sources_max_num_feed_items_to_retrieve','Max. number of feed items to retrieve')?>:&nbsp;
                </td><td>
                    <select name="maxfeeditems">
                        <option value="-1"<?php echo $serviio->maxNumberOfItemsForOnlineFeeds=="-1"?" selected":""?>><?php echo tr('tab_library_online_sources_max_feed_items','Unlimited')?></option>
                        <option value="10"<?php echo $serviio->maxNumberOfItemsForOnlineFeeds=="10"?" selected":""?>>10</option>
                        <option value="20"<?php echo $serviio->maxNumberOfItemsForOnlineFeeds=="20"?" selected":""?>>20</option>
                        <option value="30"<?php echo $serviio->maxNumberOfItemsForOnlineFeeds=="30"?" selected":""?>>30</option>
                        <option value="40"<?php echo $serviio->maxNumberOfItemsForOnlineFeeds=="40"?" selected":""?>>40</option>
                        <option value="50"<?php echo $serviio->maxNumberOfItemsForOnlineFeeds=="50"?" selected":""?>>50</option>
                    </select>
                </td></tr>
                <tr><td>
                    <?php echo tr('tab_library_online_sources_feed_expiry_interval','Feed Expiry Interval (hours)')?>:&nbsp;
                </td><td>
                    <input type="text" name="feedexpiry" value="<?php echo $serviio->onlineFeedExpiryInterval?>" maxlength="5" size="5">
                </td></tr>
                <tr><td>
                    <?php echo tr('tab_library_online_sources_preferred_online_content_quality','Preferred online content quality')?>:&nbsp;
                </td><td>
                    <select name="onlinequality">
                        <?php foreach ($onlineQuality as $key=>$val) { ?>
                            <option value="<?php echo $key?>"<?php echo $key==$serviio->onlineContentPreferredQuality?" selected":""?>><?php echo $val?></option>
                        <?php } ?>
                    </select>
                </td></tr>
            </table>
        </div>


<div id="libtab3" class="tabcontent">
    <?php $onlinePlugin = $serviio->getPlugins(); ?>
    <table>
        <tr>
            <th align="left">Plugin name</th>
            <th align="left">Version</th>
        </tr>
        <?php
            foreach ($onlinePlugin as $key=>$val) {
                echo '<tr>';
                echo '<td>'.$val[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                echo '<td>'.$val[1].'</td>';
                echo '</tr>';
            }
        ?>
    </table>
</div>


    </div>

    <div align="right">
        <span id="savingMsg" class="savingMsg"></span>
        <input type="submit" id="reset" name="reset" value="<?php echo tr('button_reset','Reset')?>" onclick="return confirm('<?php echo tr('status_message_reset','Are you sure you want to reset changes?')?>')" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
        <input type="submit" id="submit" name="save" value="<?php echo tr('button_save','Save')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
    </div>
</form>

<div id="library-dialog-form">
</div>

<div id="OS_Item">
    <fieldset>
        <?php echo tr('tab_library_new_online_source_description','Enter details of the required online source. Select the source type, enter URL of the source and pick type of media the source provides.')?>
        <br>
        <br>
        <form accept-charset="utf-8">
            <input type="hidden" id="osID" name="osID" />
            <table>
            <tr>
                <td><?php echo tr('tab_library_new_online_source_enabled','Enabled')?>:&nbsp;</td>
                <td><input type="checkbox" id="enabled" name="enabled" /></td>
            </tr>
            <tr>
                <td><?php echo tr('tab_library_new_online_source_type','Source type')?>:&nbsp;</td>
                <td>
                    <select id="onlineFeedType" name="onlineFeedType">
                        <?php foreach ($feedTypes as $key=>$val) { ?>
                            <option value="<?php echo $key?>"><?php echo $val?></option>
                        <?php } ?>
                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" id="checkStreamURL" name="checkStreamURL" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
                    <?php echo tr('button_check_stream_url','Check stream URL')?>
                </button>&nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <td><?php echo tr('tab_library_new_online_source_url','Source URL')?>:&nbsp;</td>
                <td><input type="text" id="sourceURL" name="sourceURL" size="60" /></td>
            </tr>
            <tr>
                <td><?php echo tr('tab_library_new_online_source_name','Display Name')?>:&nbsp;</td>
                <td><input type="text" id="name" name="name" size="60" /></td>
            </tr>
            <tr>
                <td><?php echo tr('tab_library_new_online_source_media_type','Media Type')?>:&nbsp;</td>
                <td><input type="radio" id="mediaType" name="mediaType" value="VIDEO" /> <?php echo tr('file_type_video','Video')?>
                    <input type="radio" id="mediaType" name="mediaType" value="AUDIO" /> <?php echo tr('file_type_audio','Audio')?>
                    <input type="radio" id="mediaType" name="mediaType" value="IMAGE" /> <?php echo tr('file_type_image','Image')?>
                </td>
            </tr>
            <tr>
                <td><?php echo tr('tab_library_new_online_source_thumbnail_url','Thumbnail URL')?>:&nbsp;</td>
                <td><input type="text" id="thumbnailURL" name="thumbnailURL" size="60" /></td>
            </tr>
        </table>
        </form>
    </fieldset>
    <div align="right">
        <br>
        <span id="savingMsgDialog" class="savingMsg"></span>
    </div>
</div>

<div id="OSinitial"  style="display: none;">
    <table id="default_os_row">
        <tr id="id_os_0">
            <td width="20px" class="handle"><span class='ui-icon ui-icon-carat-2-n-s'></span></td>
            <td width="120px" align="left"><span id="os_type_v_0">
                <img src="images/icon_feed.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_feed','Feed')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_feed','Feed')?>
            </span></td>
            <td width="80px" align="left"><span id="os_media_v_0">
                    <img src="images/icon_video.png" height="16" alt="<?php echo tr('tab_library_online_sources_repository_table_share_video','Video')?>">&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_video','Video')?>
            </span></td>
            <td width="60px" align="center">
                <div class="os_switch" id="os_switch_0" style="cursor: pointer; ">
                    <div class="iphone_switch_container" style="height:27px; width:94px; position: relative; overflow: hidden">
                        <img class="iphone_switch" style="height: 27px; width: 94px; background-image: url(images/iphone_switch_16.png); background-position: 0px 50%; " src="images/iphone_switch_container_off.png">
                    </div>
                </div>
            </td>
            <td width="130px" align="center">
                <select name="os_access_0">
                    <option value="1" selected>No Restriction</option>
                    <option value="2" >Limited Access</option>
                </select>
            </td>
            <td width="60px" align="center"><span id="os_refresh_0">
                <a style="background-color: yellow;" class="refresh-link" os_no="0" href="">&nbsp;Refresh&nbsp;</a>
                </span>
            </td>
            <td width="80px" align="center"><span id="os_serviiolink_0">
                </span>
            </td>
            <td width="340px" align="left"><span id="os_name_v_0" name="os_name_v_0" title=""></span></td>
            <td>
                <input type="hidden" id="onlinesource_0" name="onlinesource_0" value="0">
                <input type="hidden" id="os_type_0" name="os_type_0" value="FEED">
                <input type="hidden" id="os_url_0" name="os_url_0" value="">
                <input type="hidden" id="os_media_0" name="os_media_0" value="VIDEO">
                <input type="hidden" id="os_thumb_0" name="os_thumb_0" value="">
                <input type="hidden" id="os_name_0" name="os_name_0" value="">
                <input type="hidden" id="os_stat_0" name="os_stat_0" value="">
            </td>
        </tr>
    </table>
</div>

<div id="folderInitial"  style="display: none;">
    <table id="default_folder_row">
        <tr align="center" id="id_folder_0">
            <td width="4"></td>
            <td width="578px" align="left" id="path_0"></td>
            <td width="130px" align="center">
                <select name="access_0">
                    <option value="1" selected>No Restriction</option>
                    <option value="2" >Limited Access</option>
                </select>
            </td>
            <td width="30px" align="center">
                <input type="checkbox" value="1" name="VIDEO_0">
            </td>
            <td width="30px" align="center">
                <input type="checkbox" value="1" name="AUDIO_0">
            </td>
            <td width="30px" align="center">
                <input type="checkbox" value="1" name="IMAGE_0">
            </td>
            <td width="30px" align="center">
                <input type="checkbox" value="1" name="ONLINE_0">
            </td>
            <td>
                <input type="hidden" name="folder_0" value="0">
                <input type="hidden" name="name_0" value="">
            </td>
        </tr>
    </table>
</div>

<div id="dialog-remove-source" style="display: none;">
</div>
