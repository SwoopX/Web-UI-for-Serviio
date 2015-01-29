<form method="post" action="" id="metadataform" name="metadata" accept-charset="utf-8">
    <input type="hidden" name="tab" value="metadata" />
    <input type="hidden" id="process" name="process" value="">
    <br>
    <?php echo tr('tab_metadata_description','Media files metadata is constantly monitored and stored. By default all embedded metadata (tags) are extracted from media files. Please select additional extracting methods.')?>
    <br>
    <br>

<ul id="metadatatabs" class="shadetabs">
<li><a href="#" rel="meta1" class="selected"><?php echo tr('file_type_video','Video')?></a></li>
<li><a href="#" rel="meta2"><?php echo tr('file_type_audio','Audio')?></a></li>
<li><a href="#" rel="meta3"><?php echo tr('file_type_image','Image')?></a></li>
</ul>
<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    <div id="meta1" class="tabcontent">
            <input type="checkbox" name="thumbnails" value="1"<?php echo $serviio->videoGenerateLocalThumbnailEnabled=="true"?" checked":""?>> <?php echo tr('tab_metadata_video_enable_local_thumbnail','Generate thumbnails for local videos')?>
            <br>
            <input type="checkbox" name="cover_search" value="1"<?php echo $serviio->videoLocalArtExtractorEnabled=="true"?" checked":""?>> <?php echo tr('tab_metadata_video_local_poster','Look for a local DVD cover image or poster (e.g. dvdcover.jpg, movie.jpg, etc.)')?>
            <br>
            <br>

            <fieldset>

                <legend><?php echo tr('tab_metadata_descriptive_metadata','Descriptive metadata')?></legend>
                <?php echo tr('tab_metadata_descriptive_metadata_description','Serviio includes various ways to retrieve descriptive metadata of your video files. Please pick one or select \'No descriptive metadata\' from the drop-down box.')?>
                <br>
                <br>
                <select name="online_sources">
                    <?php foreach ($descriptiveMetadataExtractors as $key=>$val) { ?>
                    <option value="<?php echo $key?>"<?php echo $key==$serviio->descriptiveMetadataExtractor?" selected":""?>><?php echo $val?></option>
                    <?php } ?>
                </select>
                <br>
                <br>
                <input type="checkbox" name="download_cover" value="1"<?php echo $serviio->videoOnlineArtExtractorEnabled=="true"?" checked":""?>> <?php echo tr('tab_metadata_video_online_retrieve_poster','Retrieve DVD cover image or poster if available')?><br>
                <div align="center"><input type="submit" id="rescan" name="rescan" value="<?php echo tr('button_force_video_metadata','Rescan video metadata')?>" onclick="return confirm('<?php echo tr('status_message_force_rescan_metadata','Are you sure you want to force rescan video metadata?')?>')" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" ></div>
                <br>
                <br>
                <?php echo tr('tab_metadata_metadata_languages_description','Some metadata extractors enable retrieving metadata in a certain language. Select the preferred language.')?>
                <br>
                <br>
                <select name="metadata_language">
                    <?php foreach ($metadataLanguages as $key=>$val) { ?>
                    <option value="<?php echo $key?>"<?php echo $key==$serviio->metadataLanguage?" selected":""?>><?php echo $val?></option>
                    <?php } ?>
                </select> &nbsp; <input type="checkbox" name="orig_title" value="1"<?php echo $serviio->retrieveOriginalTitle=="true"?" checked":""?>> <?php echo tr('tab_metadata_video_use_original_title','Use original title')?>
				
				
				
				<br>
                <br>
				
				<input type="checkbox" name="enable_video_filter" id="enable_video_filter" value="1" <?php echo ($serviio->filterVideosByRating!=""?'checked':'')?>> <?php echo tr('tab_metadata_video_allow_limited_access','Allow only videos with rating less than this for Limited Access group')?> &nbsp; 
				<select name="filter_videos" id="filter_videos" <?php echo ($serviio->licenseEdition=="PRO"?'':'title="Enabled with PRO License"')?>>
                    <?php foreach ($ratings as $key=>$val) { ?>
						<option value="<?php echo $key?>"<?php echo $key==$serviio->filterVideosByRating?" selected":""?>><?php echo $val?></option>
                    <?php } ?>
                </select>
				
				
				
				
				
				
            </fieldset>
    </div>
    <div id="meta2" class="tabcontent">
            <input type="checkbox" name="audio_cover" value="1"<?php echo $serviio->audioLocalArtExtractorEnabled=="true"?" checked":""?>> 
            <?php echo tr('tab_metadata_audio_local_art','Look for a local album art image (e.g. folder.jpg, cover.jpg, front_cover.jpg, etc.)')?>
    </div>
    <div id="meta3" class="tabcontent">
            <input type="checkbox" name="img_thumbnails" value="1"<?php echo $serviio->imageGenerateLocalThumbnailEnabled=="true"?" checked":""?>> 
            <?php echo tr('tab_metadata_image_local_art','Generate thumbnails for local images')?>
    </div>
</div>

    <div align="right">
        <span id="savingMsg" class="savingMsg"></span>
        <input type="submit" id="reset" name="reset" value="<?php echo tr('button_reset','Reset')?>" onclick="return confirm('<?php echo tr('status_message_reset','Are you sure you want to reset changes?')?>')" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
        <input type="submit" id="submit" name="save" value="<?php echo tr('button_save','Save')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
    </div>
</form>
