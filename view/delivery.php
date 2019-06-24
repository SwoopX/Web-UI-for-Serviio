<form method="post" action="" id="deliveryform" name="delivery" accept-charset="utf-8">
    <input type="hidden" name="tab" value="delivery">
	<input type="hidden" id="process" name="process" value="">
    <br>
    
    <ul id="deliverytabs" class="shadetabs">
	    <li><a href="#" rel="deltab1" class="selected"><?php echo tr('tab_delivery_transcoding','Transcoding')?></a></li>
		<li><a href="#" rel="deltab2"><?php echo tr('tab_delivery_subtitles','Subtitles')?></a></li>
    </ul>
    <div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
    	
    	<div id="deltab1" class="tabcontent">
    
		    <?php echo tr('tab_delivery_transcoding_general_description','When enabled, transcoding enables you to play media files that would not normally be playable by your device. Transcoding only works with a specific Renderer Profile (not the Generic DLNA profile).')?><br>
		    <br>
		    <input type="checkbox" name="transcoding" value="1"<?php echo $serviio->transcodingEnabled=="true"?" checked":""?>> <?php echo tr('tab_delivery_transcoding_enable_transcoding','Enable transcoding')?><br>
		    <br>
		
		    <ul id="generalsettingstab" class="shadetabs">
		        <li><a href="#" rel="genset1" class="selected"><?php echo tr('tab_delivery_transcoding_general_settings','General settings')?></a></li>
		    </ul>
		    <div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
		        <div id="genset1" class="tabcontent">
		            <table>
		                <tr>
		                    <td><?php echo tr('tab_delivery_transcoding_folder_location','Transcoded files location')?>:&nbsp;</td>
		                    <td><input type="text" id="location" name="location" size="40" value="<?php echo $serviio->transcodingFolderLocation?>"></td>
		                    <td><input type="button" id="addTranscodingFolder" name="addTranscodingFolder" value="<?php echo tr('tab_delivery_transcoding_select_folder','Browse...')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" ></td>
		                </tr>
		                <tr>
		                    <td><?php echo tr('tab_delivery_transcoding_threads','Number of CPU cores to use')?>:&nbsp;</td>
		                    <td><select name="cores">
                            <option value=""><?php echo tr('tab_delivery_transcoding_cores','Optimal')?></option>
							<?php foreach($numberOfCPUCores as $key=>$val) {for ($i=1;$i<=$val;$i++) { ?>
		                    <option value="<?php echo $i?>"<?php echo $serviio->threadsNumber==$i?" selected":""?>><?php echo $i?></option>
		                    <?php }} ?>
		                    </select></td><td>&nbsp;</td>
		                </tr>
		            </table>
		        </div>
		    </div>
		
		    <br>
		
		    <ul id="videosettingstab" class="shadetabs">
		        <li><a href="#" rel="vidset1" class="selected"><?php echo tr('tab_delivery_transcoding_video_settings','Video settings')?></a></li>
		    </ul>
		    <div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
		        <div id="vidset1" class="tabcontent">
		            <input type="checkbox" name="quality" value="1"<?php echo $serviio->bestVideoQuality=="true"?" checked":""?>> <?php echo tr('tab_delivery_transcoding_produce_best_quality','Produce the best video quality')?><br><br>
		            <?php echo tr('tab_delivery_transcoding_audio_channels','Audio channels')?>
		            <input type="radio" name="audio" value="original"<?php echo $serviio->audioDownmixing=="true"?"":" checked"?>> <?php echo tr('tab_delivery_transcoding_audio_channels_original','Keep original')?>
		            <input type="radio" name="audio" value="downmix"<?php echo $serviio->audioDownmixing=="true"?" checked":""?>> <?php echo tr('tab_delivery_transcoding_audio_channels_stereo','Downmix to stereo')?>
		        </div>
		    </div>
		    		    
    	</div>
    
		<div id="deltab2" class="tabcontent">
	    	<?php echo tr('tab_delivery_subtitles_general_subtitles_description','When enabled, subtitles stored in external files or embedded in the video files will be served during video playback. If the device requires it or if forced, subtitles can be burned-in onto the video stream, which triggers complete transcoding of the file.')?><br>
		    <br>
            <input type="checkbox" name="subtitles" value="1"<?php echo $serviio->subtitlesEnabled=="true"?" checked":""?>> <?php echo tr('tab_delivery_subtitles_enable_subtitles','Enable subtitles')?>
            <br>
            <input type="checkbox" name="subtitlesextraction" value="1"<?php echo $serviio->embeddedSubtitlesExtractionEnabled=="true"?" checked":""?>> <?php echo tr('tab_delivery_subtitles_embedded_subtitles_extraction_enabled','Enable extraction of subtitles embedded in video files')?>
            <br>
            <input type="checkbox" name="hardsubsenabled" id="hardsubsenabled" value="1"<?php echo $serviio->hardSubsEnabled=="true"||$serviio->hardSubsForced=="true"?" checked":""?>> <?php echo tr('tab_delivery_subtitles_hard_subs_enabled','Enable burned-in subtitles')?>
				<select id="hardsubs" name="hardsubs">
					<option value="enabled"<?php echo $serviio->hardSubsEnabled=="true"?" selected":""?>><?php echo tr('tab_delivery_subtitles_hard_subs_required','Only when required')?></option>
					<option value="forced"<?php echo $serviio->hardSubsForced=="true"?" selected":""?>><?php echo tr('tab_delivery_subtitles_hard_subs_always','Always')?></option>
				</select>
			<br>
			<?php echo tr('tab_delivery_subtitles_character_encoding','Subtitle character encoding').": "?><input type="text" name="characterEncoding" value="<?php echo htmlspecialchars($serviio->hardSubsCharacterEncoding)?>" maxlength="10">
			
			<br>
			<br>
            
            <?php echo tr('tab_delivery_subtitles_preferred_languages','Define a list of preferred languages codes, separated with comma. Subtitles for the specified language codes will have preference.')?><br>
            <br>
            <?php echo tr('tab_delivery_subtitles_language_codes','Preferred language')?>:&nbsp;
			<input type="text" name="language" value="<?php echo  htmlspecialchars($serviio->preferredLanguage)?>" maxlength="64">
            <br>
		</div>
    
    </div>

    <div align="right">
        <span id="savingMsg" class="savingMsg"></span>
        <input type="submit" id="reset" name="reset" value="<?php echo tr('button_reset','Reset')?>" onclick="return confirm('<?php echo tr('status_message_reset','Are you sure you want to reset changes?')?>')" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" >
        <input type="submit" id="submit" name="save" value="<?php echo tr('button_save','Save')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
    </div>
    
</form>

<div id="delivery-dialog-form">
</div>
