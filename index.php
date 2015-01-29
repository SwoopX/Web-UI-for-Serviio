<?php
set_time_limit(0);
include("config.php");
include("lib/RestRequest.inc.php");
include("lib/serviio.php");

// initiate call to service
$serviio = new ServiioService($serviio_host,$serviio_port);

$settings = $serviio->getConsoleSettings();
$language = $settings["language"];
$appInfo = $serviio->getApplication();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo tr('window_title','Serviio console')?> <?php echo $appInfo["version"]?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">

<meta name="format-detection" content="telephone=no"/>
<meta name="format-detection" content="address=no"/>
<meta name="apple-mobile-web-app-capable" content="yes">

<link href="images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

<script type="text/javascript" src="js/Math.uuid.js"></script>
<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" />
<script src="js/download.js" type="text/javascript"></script>
<script src="js/jquery.iphone-switch.js" type="text/javascript"></script>

<link href="css/styles.css" rel="stylesheet" type="text/css" />

<style>
    .ui-widget, .ui-widget button {
        font-family: Verdana,Arial,sans-serif;
        font-size: 0.8em;
    }
    .btn-small {
        font-weight: bold;
        padding: .2em .8em .3em !important;
    }
    #t1 tr td { padding:10px }
    .row-modified {
        background-color: #000 !important;
    }
    .ui-selected {
        background-color: #CACAD4  !important;
    }
</style>

<link rel="stylesheet" type="text/css" href="css/ajaxtabs.css" />
<script type="text/javascript" src="js/ajaxtabs.js">
/***********************************************
 * * Ajax Tabs Content script v2.2- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
 * * This notice MUST stay intact for legal use
 * * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
 * ***********************************************/
</script>

<link rel="stylesheet" type="text/css" href="css/tabcontent.css" />
<script type="text/javascript" src="js/tabcontent.js">
/***********************************************
 * * Tab Content script v2.2- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
 * * This notice MUST stay intact for legal use
 * * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
 * ***********************************************/
</script>

<script src="tree/jquery_folder_tree/jquery.foldertree.js" type="text/javascript"></script>
<link href="tree/jquery_folder_tree/style.css" rel="stylesheet" type="text/css" />
<script src="filetree/jqueryFileTree.js" type="text/javascript"></script>
<link href="filetree/jqueryFileTree.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

function callSelectionDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons, treeview) {
    /*var height="410";
    var width="570";
    if ( dialogDivTagId == "Add_Serviidb_Item") {
        var height="480";
        var width="850";
    }*/
    $("#"+dialogDivTagId)
        .dialog({
            autoOpen: false,
            height: 410,
            width: 570,
            modal: true
        })
        .dialog('option', 'title', dialogTitle)
        .dialog('option', 'buttons', dialogButtons)
        .html(dialogHtml)
        .dialog("open");
        
        if (treeview == "folder") {
            $("#foldertree")
                .folderTree({
                    root: '/',
                    script: 'tree/jquery_folder_tree/jquery.foldertree.php',
                    loadMessage: 'My loading message...'
                })
                .click(function() {
                    var tmp = $(".sel").attr('href');
                    $("#selValue").val(tmp);
                });
        } else if (treeview == "file") {
            $("#filetree")
                .fileTree({
                root: '/',
                script: 'filetree/jqueryFileTree.php',
                loadMessage: 'My loading message...',
                multiFolder: false },function(file) {
                    $("#selValue").val(file);
                });
        }
};

function callDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons) {
    var height="410";
    var width="570";
    if ( dialogDivTagId == "Add_Serviidb_Item") {
        height="480";
        width="850";
    }
    $("#"+dialogDivTagId)
        .dialog({
            autoOpen: false,
            //height: 360,
            //width: 620,
            height: height,
            width: width,
            modal: true
        })
        .dialog('option', 'title', dialogTitle)
        .dialog('option', 'buttons', dialogButtons);
        
    if (dialogHtml != "") {
        $("#"+dialogDivTagId).html(dialogHtml);  
    }
    $("#"+dialogDivTagId).dialog("open");
}

function sendAjaxRequest(e, messageDivTagId, ajaxRequestData) {
    $($(messageDivTagId)).text(ajaxRequestData['initializing']).show();
    //$($(messageDivTagId)).first().show();
    //$($(messageDivTagId)).delay(800).fadeOut("slow");
    $("#debugInfo").text(parseUrl(decodeURIComponent("process=" + $("#process").val() + "&" + ajaxRequestData['data'])));
    $("#debugInfoDate").text(Date());
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: ajaxRequestData['url'],
        dataType: ajaxRequestData['dataType'],
        data: "process=" + $("#process").val() + "&" + ajaxRequestData['data'],
        timeout: 10000,
        success: function(response) {
            $("#debugInfo2Date").text(Date());
            if (response != null) {
                $("#debugInfo2").text(serializeXmlNode(response));
            } else {
                $("#debugInfo2").text(response);
            }
            if ($(response).find("errorCode").text() == 0) {
                $($(messageDivTagId)).text(ajaxRequestData['0']).show();
                $($(messageDivTagId)).delay(2000).fadeOut("slow");
                if ($("#process").val() == "export") {
                    //Generate output file
                    $.generateFile({
                        filename	: 'serviio-online-backup_' + curDate() + '.sob',
                        content		: serializeXmlNode(response),
                        script		: 'code/library.php'
                    });
                }
                if ($("#process").val() == "import" || $("#process").val() == "upload") { // || $(messageDivTagId) == "" || $(messageDivTagId) == "") {
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
                //return false;
            } else {
                $.each(ajaxRequestData, function(index, value) {
                    if ($(response).find("errorCode").text() == index){
                        $($(messageDivTagId)).text("Error " + $(response).find("errorCode").text() + ": " + value).show();
                        $($(messageDivTagId)).delay(2000).fadeOut("slow");
                        //return false;
                    }
                });
            }
        },
        error: function(xhr, textStatus, errorThrown){
            if (xhr.status == 200) {
                $("#debugInfo2Date").text(Date());
                $("#debugInfo2").text("HTTP response code: "+xhr.status);
                $($(messageDivTagId)).text(ajaxRequestData['0']).show();
                $($(messageDivTagId)).delay(2000).fadeOut("slow");
            } else {
                alert("Error: " + textStatus + "\nHTTP response code: " + xhr.status + "\n" + errorThrown);
                $("#debugInfo2Date").text(Date());
                $("#debugInfo2").text("HTTP response code: "+xhr.status).append("<br>").append(""+errorThrown+"");
                $($(messageDivTagId)).text(ajaxRequestData['genericError']).show();
                $($(messageDivTagId)).delay(2000).fadeOut("slow");
            }
        }
    });
    return false;
}

function updateOsSourceRow(newID, sel_row, action) {
    var feedType = "";
    var mediaType = "";
    var spanContent = "";
    
    $("#osID").val(newID);
    
    if (action == "add" || action == "addserviidb") {
        $("#os_refresh_"+newID).html("&nbsp;New&nbsp;");
        $("#os_serviiolink_"+newID+" img").remove();
        $("#onlinesource_"+newID).val("new");
    }
    
    $("input[name=os_url_"+newID+"]").val($("#sourceURL").val());
    $("#os_name_v_"+newID).attr('title', $("#sourceURL").val());
    $("#os_name_"+newID).val($("#name").val());
    feedType = $("#onlineFeedType option:selected").val();
    mediaType = $('input:radio[name=mediaType]:checked').val();
    
    if ($("#name").val()=="") {
        $("#os_name_v_"+newID).text($("#sourceURL").val());
    } else {
        $("#os_name_v_"+newID).text($("#name").val());
    }
    
    var swState = "off";
    
    if ($("#enabled").prop('checked')) {
        $("#os_stat_"+sel_row).val("true");
        swState = "on";
    } else {
        $("#os_stat_"+sel_row).val("false");
    }
    
    if (feedType == "FEED" || feedType == "RSS ATOM FEED") {
        spanContent = "<img src='images/icon_feed.png' height='16' alt='<?php echo tr('tab_library_online_sources_repository_table_share_feed','Feed')?>'>&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_feed','Feed')?>";
        feedType = "FEED";
    } else if (feedType == "WEB_RESOURCE" || feedType == "WEB RESOURCE") {
        spanContent = "<img src='images/icon_web_resource.png' height='16' alt='<?php echo tr('tab_library_online_sources_repository_table_share_web_resource','Web resource')?>'>&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_web_resource','Web resource')?>";
        feedType = "WEB_RESOURCE";
    } else if (feedType == "LIVE_STREAM" || feedType == "LIVE STREAMS") {
        spanContent = "<img src='images/icon_satelite_black.png' height='16' alt='<?php echo tr('tab_library_online_sources_repository_table_share_live_steam','Live stream')?>'>&nbsp;<?php echo tr('tab_library_online_sources_repository_table_share_live_steam','Live stream')?>";
        feedType = "LIVE_STREAM";
    }
    
    $("#os_type_v_"+newID).html(spanContent);
    $("#os_type_"+newID).val(feedType);
    
    spanContent = "";
    
    if (mediaType == "VIDEO") {
        spanContent = "<img src='images/icon_video.png' height='16' alt='<?php echo tr('file_type_video','Video')?>'>&nbsp;<?php echo tr('file_type_video','Video')?>";
    } else if (mediaType == "AUDIO") {
        spanContent = "<img src='images/icon_music.png' height='16' alt='<?php echo tr('file_type_audio','Audio')?>'>&nbsp;<?php echo tr('file_type_audio','Audio')?>";
    } else if (mediaType == "IMAGE") {
        spanContent = "<img src='images/icon_camera.png' height='16' alt='<?php echo tr('file_type_image','Image')?>'>&nbsp;<?php echo tr('file_type_image','Image')?>";
    }
    
    $("#os_media_v_"+newID).html(spanContent);
    $("#os_media_"+newID).val(mediaType);
    
    $("#os_switch_"+newID).iphoneSwitch(swState,
        function() {
            //alert('on');
            $("#os_stat_"+newID).val('true');
        },
        function() {
            //alert('off');
            $("#os_stat_"+newID).val('false');
        },
        {
            switch_on_container_path: 'images/iphone_switch_container_off_16.png'
        }
    );
}

function updateAttributeNames(tableDivTagId, attribute, newID) {
    //$("tr:last", tableDivTagId).find("*["+attribute+"]").addBack().each(function(i, id) {
    $("#" + tableDivTagId + " tr:last").find("*["+attribute+"]").addBack().each(function(i, id) {
        if (jQuery.type($(this).attr(attribute)) !== "undefined") {
        $(this).attr(attribute, function(i, id) {
            var idNameNumber = id.split('_').pop();
            return id.substr(0, id.length - idNameNumber.length) + newID;
        });
        }
    });
}

function curDate() {
    var d = new Date();
    var formattedDate = [d.getDate(), d.getMonth() + 1, d.getFullYear(), d.getHours(), d.getMinutes(), d.getSeconds()];
        $.each(formattedDate, function (key, val) {
            if (val.toString().length == 1) {
                formattedDate.splice(key, 1, "0" + val);
            }
        });
    return formattedDate[2] + "-" + formattedDate[1] + "-" + formattedDate[0] + "-" + formattedDate[3] + "-" + formattedDate[4] + "-" + formattedDate[5];
}

function cloneDefaultTableRow(defaultRow, tableDivTagId) {
    if ($("#" + tableDivTagId + " tbody tr").length != 0) {
        $("#" + defaultRow + " tr:last").clone().insertAfter("#" + tableDivTagId + " tr:last");
    } else {
        $("#" + tableDivTagId + " tbody:last").append($("#" + defaultRow + " tr:last").clone());
    }
}

function rowStyle(tableDivTagId) {
                $("#" + tableDivTagId + " tbody tr:even").removeClass("odd even").addClass("even");
                $("#" + tableDivTagId + " tbody tr:odd").removeClass("odd even").addClass("odd");
            }

/* simple debugging function to display contents of an object */
function print(o) {
  var out = '';
  for (var p in o) {
      out += p + ': ' + o[p] + '\n';
    }
  console.log(out);
}
/* this is to make all browsers work nicely */
function serializeXmlNode(xmlNode) {
    if (typeof window.XMLSerializer != "undefined") {
        return (new window.XMLSerializer()).serializeToString(xmlNode);
    } else if (typeof xmlNode.xml != "undefined") {
        return xmlNode.xml;
    }
    return "";
}

function parseUrl(url) {
    var cleanURL = url.replace(/&/g, "\n");
    return cleanURL
}

</script>
</head>
<body bgcolor="#eeeeee">

<div id="pageHeader" class="headerOff">
    <div id="headerContent">
        <div id="optionBar">
            <div id="wuSites">
                <span><b><?php echo tr('status_message_server_status','Server Status')?>:&nbsp;</b><span id="svrs"></span></span>
                <span><b><?php echo tr('status_message_files_added','Files Added')?>:&nbsp;</b><span id="nofa"></span></span>
                <span><b><?php echo tr('status_message_last_added','Last File Added')?>:&nbsp;</b><span id="lafn"></span></span>
            </div>
        </div>
    </div>
</div>

<hr>
<script type="text/javascript">
$(document).ready(function(){

    CheckStatuses();

    var refreshID = setInterval(function() {
        CheckStatuses();
    },5000);

    function CheckStatuses() {
        $.getJSON("monitor.php", function(json){
            if (json.serverStatus == 'STARTED') {
                $("#svrs").html("<img src='images/bullet_green.png' title='running'>");
            } else {
                $("#svrs").html("<img src='images/bullet_red.png' title='not running'>");
            }
            $("#lafn").text(json.lastAddedFileName); 
            $("#nofa").text(json.numberOfAddedFiles); 
        });
    }
});
</script>

<?php
// Application version check
$message = "";
$serviioVersion=str_replace(".", "", $appInfo["version"]);
$requiredVersion=str_replace(".", "", $version_req);
$vals=array(0 => $serviioVersion, 1 => $requiredVersion);

foreach ($vals as $key=>$val) {
    if (strlen($val) < 3) {
        $vals[$key] = $val."0";
    }
}

if (intval($serviioVersion)<intval($requiredVersion)) {
    if ($message=="") {
        $message = "WARNING: Web UI is optimized for Serviio v".$version_req." but v".$appInfo["version"]." was found. There may be a loss of functionality. Please consider updating.";
    }
} elseif ($appInfo["updateVersionAvailable"] > $appInfo["version"] && $appInfo["updateVersionAvailable"] != "") {
    if ($message=="") {
        $message = "There is a new version of Serviio available - <a href='http://serviio.org'>Click Here</a>";
    }
}
if ($message!="") {
    ?>
<center><font color="red"><b><?php echo $message!=""?$message:""?></b></font></center>
<?php
}
?>
<br />

<div style="padding-left: 10px;">
    <ul id="indextabs" class="shadetabs">
        <li><a href="content.php?tab=status" rel="indexcontainer" class="selected"><?php echo tr('tab_status','Status')?></a></li>
        <li><a href="content.php?tab=library" rel="indexcontainer"><?php echo tr('tab_library','Library')?></a></li>
	    <li><a href="content.php?tab=delivery" rel="indexcontainer"><?php echo tr('tab_delivery','Delivery')?></a></li>
        <li><a href="content.php?tab=metadata" rel="indexcontainer"><?php echo tr('tab_metadata','Metadata')?></a></li>
        <li><a href="content.php?tab=presentation" rel="indexcontainer"><?php echo tr('tab_presentation','Presentation')?></a></li>
        <?php echo ($serviio->licenseEdition=="PRO"||$serviio->licenseType=="EVALUATION"?'<li><a href="content.php?tab=remote" rel="indexcontainer">'.tr('tab_remote','Remote').'</a></li>':'')?>
        <li><a href="content.php?tab=settings" rel="indexcontainer"><?php echo tr('tab_console_settings','Console Settings')?></a></li>
        <li><a href="content.php?tab=logs" rel="indexcontainer" id="logs"><?php echo tr('tab_logs','Logs')?></a></li>
        <li><a href="content.php?tab=about" rel="indexcontainer"><?php echo tr('tab_about','About')?></a></li>
    </ul>

    <div id="indexdivcontainer" style="border:1px solid gray; width:97.5%; margin-bottom: 1em; padding: 10px">
    </div>
</div>

<script type="text/javascript">
var indexes=new ddajaxtabs("indextabs", "indexdivcontainer")
indexes.setpersist(true)
indexes.setselectedClassTarget("link") //"link" or "linkparent"
indexes.init()
indexes.onajaxpageload=function(pageurl) {
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=status")!=-1) {
        var ssTabs=new ddtabcontent("serverstatustab")
        ssTabs.setpersist(true)
        ssTabs.setselectedClassTarget("link") //"link" or "linkparent"
        ssTabs.init()
        var rpTabs=new ddtabcontent("rendererprofiletab")
        rpTabs.setpersist(true)
        rpTabs.setselectedClassTarget("link") //"link" or "linkparent"
        rpTabs.init()
        var nsTabs=new ddtabcontent("networksettingtab")
        nsTabs.setpersist(true)
        nsTabs.setselectedClassTarget("link") //"link" or "linkparent"
        nsTabs.init()
        var sourceId=[];
        var sourceData=[];
        var dialogDivTagId="";
        var dialogHtml="";
        var dialogTitle="";
        var dialogButtons="";
        var ajaxRequestData = {};
        var messageDivTagId="";
        
        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            var $form = $("#statusform");
            
            $(".os_switch").each(function(i, domEle) {
                var itm = domEle.id.substring(8,55);
                var itm_def = "on";
                var itm_stat = $("#enabled_"+itm).val();
                if (itm_stat == "true") {
                    itm_def = "on";
                } else {
                    itm_def = "off";
                }
                $(this).iphoneSwitch(itm_def, 
                    function() {
                        //alert('on');
                        $("#enabled_"+itm).val('true');
                    },
                    function() {
                        //alert('off');
                        $("#enabled_"+itm).val('false');
                    },
                    {
                        switch_on_container_path: 'images/iphone_switch_container_off_16.png'
                    }
                );
            });

            $("#submit").click(function(e) {
				$("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/status.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>",
                    500: "<?php echo tr('error_status_500','Invalid IP address format!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
        
            $("#start").click(function(e) {
                $("#debugInfoDate").text(Date());
                $("#process").val("start");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": "",
                    "url": "code/status.php",
                    "dataType": "xml",
                    "initializing": "",
                    0: ""
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
            $("#stop").click(function(e) {
                $("#debugInfoDate").text(Date());
                $("#process").val("stop");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": "",
                    "url": "code/status.php",
                    "dataType": "xml",
                    "initializing": "",
                    0: ""
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
            $("#reset").click(function(e) {
                location.reload();
                return false;
            });
        
            $( "#rendererTable tbody" )
                .mousedown(function (evt) {
                    if (evt.pageX > 939) {
                        evt.stopImmediatePropagation();
                        return false;
                    }
                })
                .selectable({
                    filter: "tr",
                    distance: 0,
                    cancel: "img,a,select,iphone_switch_container",
                    stop: function(){
                        sourceId=[];
                        sourceData=[];
                        $( ".ui-selected", this ).each(function() {
                            sourceId.push($(this).attr('id'));
                            sourceData.push($(this).children("td:nth-child(3)").text() + "  -  " + $(this).children("td:nth-child(4)").text());
                        }); 
                    }
                });
            
            $("#remove-renderer").click(function(e) {
                if (sourceData.length == 0) {
                    alert("<?php echo tr('status_message_error_remove_renderers','Please select at least one renderer to remove')?>");
                    return false;
                }
                dialogDivTagId = "dialog-remove-renderer";
                dialogHtml = "<span class='ui-icon ui-icon-alert' style='float: left; margin: 0 7px 20px 0;'></span><?php echo tr('dialog_remove_renderer','This will remove the selected renderer. Are you sure?')?><br><br><br>" + sourceData.join("<br>");
                dialogTitle = "<?php echo tr('dialog_remove_selected_renderer','Remove selected Renderer?')?>";
                dialogButtons = {
                    "<?php echo tr('button_delete_renderer','Delete renderer')?>": function() {
                        $.each(sourceId, function( index, value ) {
                            $("#"+value).remove();
                        });
                        $(this).dialog("close");
                    },
                    "<?php echo tr('button_cancel','Cancel')?>": function() {
                        $(this).dialog("close");
                        }
                }
                callDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons);
                return false;
            });
        });
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=library")!=-1) {
        var libTabs=new ddtabcontent("librarytabs")
        libTabs.setpersist(false)
        libTabs.setselectedClassTarget("link") //"link" or "linkparent"
        libTabs.init()
        var libsTabs=new ddtabcontent("librarystatustabs")
        libsTabs.setpersist(true)
        libsTabs.setselectedClassTarget("link") //"link" or "linkparent"
        libsTabs.init()
        var sourceId=[];
        var sourceData=[];
        var dialogDivTagId="";
        var dialogHtml="";
        var dialogTitle="";
        var dialogButtons="";
        var ajaxRequestData = {};
        var messageDivTagId="";
        
        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            $("#OS_Item").hide();
            $("#Add_Serviidb_Item").hide();
            var $form = $("#libraryform");
            
            $( "#libraryTableOnlineSources tbody" )
                .sortable({
                    handle: ".handle",
                    axis: 'y',
                    delay: 100,
                    stop: function(){
                        rowStyle("libraryTableOnlineSources");
                    }
                })
                .mousedown(function (evt) {
                    if (evt.pageX > 939) {
                        evt.stopImmediatePropagation();
                        return false;
                    }
                })
                .selectable({
                    filter: "tr",
                    distance: 0,
                    cancel: "img,a,select",
                    stop: function(){
                        sourceId=[];
                        sourceData=[];
                        $( ".ui-selected", this ).each(function() {
                            sourceId.push($(this).attr('id'));
                            sourceData.push($(this).children("td:nth-child(8)").text() + "  -  " + $(this).children("td:nth-child(8)").children("span").attr("title"));
                        }); 
                    }
                });
                
            $( "#libraryTableFolders tbody" )
                .mousedown(function (evt) {
                    if (evt.pageX > 939) {
                        evt.stopImmediatePropagation();
                        return false;
                    }
                })
                .selectable({
                    filter: "tr",
                    distance: 0,
                    cancel: "input,select",
                    stop: function(){
                        sourceId=[];
                        sourceData=[];
                        $( ".ui-selected", this ).each(function() {
                            sourceId.push($(this).attr('id'));
                            sourceData.push($(this).children("td:nth-child(1)").text());
                        }); 
                    }
                });
            
            rowStyle("libraryTableOnlineSources");

            /* on-off switch for Online Sources */
            $(".os_switch").each(function(i, domEle) {
                var itm = domEle.id.substring(10,14);
                var itm_def = "on";
                var itm_stat = $("#os_stat_"+itm).val();
                if (itm_stat == "true") {
                    itm_def = "on";
                } else {
                    itm_def = "off";
                }
                $(this).iphoneSwitch(itm_def, 
                    function() {
                        //alert('on');
                        $("#os_stat_"+itm).val('true');
                    },
                    function() {
                        //alert('off');
                        $("#os_stat_"+itm).val('false');
                    },
                    {
                        switch_on_container_path: 'images/iphone_switch_container_off_16.png'
                    }
                );
            });

            $("#refresh").click(function(e) {
            	$("#process").val("refresh");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": "",
                    "url": "code/library.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_rescan','Starting Refresh...')?>",
                    "genericError": "<?php echo tr('status_message_error_refresh','Error starting refresh!')?>",
                    0: "<?php echo tr('status_message_started','Started!')?>",
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
            $("#submit").click(function(e) {
                $("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/library.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>",
                    503: "<?php echo tr('error_library_503','Invalid online resource URL!')?>",
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
            $("#checkStreamURL").click(function(e) {
                if ($("#onlineFeedType option:selected").val() == "LIVE_STREAM") {
                    var mediaType=$("input[name='mediaType']:checked").val().toUpperCase();
                    var sourceURL=$("input[name='sourceURL']").val();
                    $("#process").val("checkURL");
                    messageDivTagId="#savingMsgDialog";
                    ajaxRequestData={
                        "data": "MediaType="+mediaType+"&SourceURL="+sourceURL,
                        "url": "code/library.php",
                        "dataType": "xml",
                        "initializing": "<?php echo tr('status_message_rescan','Starting Rescan...')?>",
                        "genericError": "<?php echo tr('status_message_error_stream_url','Error checking stream URL!')?>",
                        0: "<?php echo tr('status_message_valid_stream_url','Stream URL valid!')?>",
                        603: "<?php echo tr('error_stream_603','Live stream is not valid or currently available!')?>",
                        700: "<?php echo tr('error_700','Invalid parameter!')?>"
                    };
                    sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                    return false;
                }
                alert("<?php echo tr('error_no_stream_selected','No live stream as source type selected!')?>");
            });
            
            $(".refresh-link").click(function(e) {
                var os_no = "os_no=" + $(this).attr("os_no");
                $("#process").val("OSrefresh");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": os_no,
                    "url": "code/library.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_refresh','Starting Refresh...')?>",
                    "genericError": "<?php echo tr('status_message_error_refresh','Error starting refresh!')?>",
                    0: "<?php echo tr('status_message_started','Started!')?>",
                    700: "<?php echo tr('error_700','Invalid parameter!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });

			$("#exportOnlineSource").click(function(e) {
				$("#process").val("export");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": "",
                    "url": "code/library.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_exporting','Exporting...')?>",
                    "genericError": "<?php echo tr('status_message_error_export_data','Error exporting data!')?>",
                    0: "<?php echo tr('status_message_exported','Data exported!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
				return false;
			});

			$("#importOnlineSource a").click(function() {
                $(this).parent().find('input').click();
				$("#upl").change( function() {
                    //get file object using fileReader API
                    var file = document.getElementById('upl').files[0];
                    if (file) {
                        // create reader
                        var reader = new FileReader();
                        reader.readAsText(file);
                        reader.onload = function(e) {
                            // browser completed reading file
                            var backupData = e.target.result;
                            $("#process").val("import");
                            messageDivTagId="#savingMsg";
                            ajaxRequestData={
                                "data": "backup="+encodeURIComponent(backupData), //encode URI backup data for POST
                                "url": "code/library.php",
                                "dataType": "xml",
                                "initializing": "<?php echo tr('status_message_importing','Importing...')?>",
                                "genericError": "<?php echo tr('status_message_error_import_data','Error importing data!')?>",
                                0: "<?php echo tr('status_message_imported','Data imported!')?>",
                                503: "<?php echo tr('error_importexport_503','Some ServiioLink in the file is invalid (not properly formatted)!')?>",
                                505: "<?php echo tr('error_importexport_505','The file is corrupted and/or has invalid format!')?>"
                            };
                            sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                        }
                    }
				});
				return false;
			});
            
            $("#reset").click(function(e) {
				location.reload();
				return false;
			});

            $("#removeFolder, #removeOnlineSource").click(function(e) {
                if (sourceData.length == 0) {
                    alert("<?php echo tr('status_message_error_remove_source','Please select at least one source to remove')?>");
                    return false;
                }
                dialogDivTagId="dialog-remove-source";
                if ($(this).attr("id") == "removeFolder") {
                    dialogHtml="<span class='ui-icon ui-icon-alert' style='float: left; margin: 0 7px 20px 0;'></span><?php echo tr('dialog_remove_folder_message','This will remove the selected folder. Are you sure?')?><br><br><br>" + sourceData.join("<br>");
                    dialogTitle="<?php echo tr('dialog_remove_folder','Remove folder')?>";
                } else if ($(this).attr("id") == "removeOnlineSource") {
                    dialogHtml="<span class='ui-icon ui-icon-alert' style='float: left; margin: 0 7px 20px 0;'></span><?php echo tr('dialog_remove_os_message','This will remove the selected online sources. Are you sure?')?><br><br><br>" + sourceData.join("<br>");
                    dialogTitle="<?php echo tr('dialog_remove_os','Remove online source')?>";
                }
                dialogButtons={
                    "<?php echo tr('button_delete_source','Delete source(s)')?>": function() {
                        $.each(sourceId, function( index, value ) {
                            $("#"+value).remove();
                        });
                        $(this).dialog("close");
                    },
                    "<?php echo tr('button_cancel','Cancel')?>": function() {
                        $(this).dialog("close");
                        }
                };
                callDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons);
                return false;
            });

            $("#addLocalFolder").click(function(e) {
                e.preventDefault();
                dialogDivTagId="library-dialog-form";
                dialogHtml="<form accept-charset='utf-8'><fieldset><label for='selValue'><?php echo tr('dialog_selected_folder','Selected Folder')?>:&nbsp;</label><input type='text' id='selValue' name='selValue' readonly='readonly' size='70' /><div id='foldertree'></div></fieldset></form>";
                dialogTitle = "<?php echo tr('dialog_select_folder','Select Folder')?>";
                dialogButtons={
                    "<?php echo tr('button_select_folder','Select Folder')?>": function() {
                        var bValid = true;
                        var localPath = $(".sel").attr('href');
                        localPath = localPath.substr(0, localPath.length - 1);
                        var newID = parseInt($("#lastFId").val()) + 1;
                        var tableDivTagId = "libraryTableFolders";
                        var defaultRow = "default_folder_row";
                        
                        cloneDefaultTableRow(defaultRow, tableDivTagId);
                        updateAttributeNames(tableDivTagId, "id", newID);
                        updateAttributeNames(tableDivTagId, "name", newID);
                        rowStyle("libraryTableFolders");
                        
                        $("#path_"+newID).text(localPath);
                        $("input[name=folder_"+newID+"]").val("new");
                        $("input[name=name_"+newID+"]").val(localPath);
                        $("#lastFId").val(newID + 1);
                        $("#foldertree").remove();
                        $(this).dialog("close");
                    },
                    "<?php echo tr('button_cancel','Cancel')?>": function() {
                        $("#foldertree").remove();
                        $(this).dialog("close");
                    }
                }
                callSelectionDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons, "folder");
                return false;
            });

            $("#add_os").click(function(e) {
                e.preventDefault();
                // set defaults and clear fields
                $("#enabled").prop("checked", true);
                $("#onlineFeedType").val("FEED");
                $("#sourceURL").val("");
                $("#name").val("");
                $("[name=mediaType]").filter("[value=VIDEO]").prop("checked",true);
                $("#thumbnailURL").val("");
                $("#thumbnailURL").attr('disabled', 'disabled');
                
                dialogDivTagId = "OS_Item";
                dialogHtml = "";
                dialogTitle = "<?php echo tr('dialog_add_online_source','Add Online Source')?>";
                dialogButtons = {
                        "<?php echo tr('button_add','Add')?>": function() {
                            var newOSId = parseInt($("#lastOSId").val()) + 1;
                            var tableDivTagId = "libraryTableOnlineSources";
                            var defaultRow = "default_os_row";
                        
                            cloneDefaultTableRow(defaultRow, tableDivTagId);
                            updateAttributeNames(tableDivTagId, "id", newOSId);
                            updateAttributeNames(tableDivTagId, "name", newOSId);
                            updateOsSourceRow(newOSId, newOSId, "add");
                            rowStyle("libraryTableOnlineSources");
                            
                            $("#lastOSId").val(newOSId);
                            $(this).dialog("close");
                        },
                        "<?php echo tr('button_cancel','Cancel')?>": function() {
                            $(this).dialog("close");
                        }
                }
                callDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons);
                return false;                
            });
            
            /* edit the selected Online Source */
            $("#edit_os").click(function(e) {
                e.preventDefault();
                
                var sel_row = [];
                $("#libraryTableOnlineSources tr.ui-selected input[name^='onlinesource_']").each(function() {
                    sel_row.push($(this).val());
                });

                if (sel_row.length == 0) {
                    alert("<?php echo tr('status_message_no_item_selection','No Item Selected')?>");
                    return false;
                } else if (sel_row.length > 1) {
                    alert("<?php echo tr('status_message_multiple_item_selection','More than one item selected')?>");
                    return false;
                }
                // set defaults and clear fields
                if ($("#os_stat_"+sel_row).val() == "true") {
                    $("#enabled").attr('checked', 'checked');                    
                } else {
                    $("#enabled").removeAttr('checked')
                }
                $("#osID").val(sel_row);
                $("#onlineFeedType").val($("#os_type_"+sel_row).val());
                $("#sourceURL").val($("input[name=os_url_"+sel_row+"]").val());
                $("#name").val($("#os_name_"+sel_row).val());
                $("#thumbnailURL").val($("input[name=os_thumb_"+sel_row+"]").val());
                $("[name=mediaType]").filter("[value=" + $("input[name=os_media_"+sel_row+"]").val() + "]").prop("checked",true);

                if ($("#onlineFeedType").val() == "LIVE_STREAM") {
                    $("#thumbnailURL").removeAttr('disabled');
                } else {
                    $("#thumbnailURL").attr('disabled', 'disabled');
                }
                
                dialogDivTagId = "OS_Item";
                dialogHtml = "";
                dialogTitle = "<?php echo tr('dialog_edit_online_source','Edit Online Source')?>";
                dialogButtons = {
                        "<?php echo tr('button_edit','Edit')?>": function() {
                            var osID = $("#osID").val();
                            
                            updateOsSourceRow(osID, osID, "edit");
                            //var serviioLink="'serviio://"+mediaType.toLowerCase()+":"+feedType.toLowerCase()+"?url="+$('#sourceURL').val()+"&name="+$('#name').val()+"'";
                            //serviioLink="<img src='images/icon_serviiolink.gif' height='16' onClick='alert("+serviioLink+")'>";
                            //$("#os_serviiolink_"+osID).html(asd);
                            // now close the form
                            $(this).dialog("close");
                            //return false;
                        },
                        "<?php echo tr('button_cancel','Cancel')?>": function() {
                            $(this).dialog("close");
                            //return false;
                        }
                }
                callDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons);
                return false;
            });

            $("#onlineFeedType").change(function () {
                if ($(this).val() == "LIVE_STREAM") {
                    $("#thumbnailURL").removeAttr('disabled');
                    $("[name=mediaType]").filter("[value=VIDEO]").prop("checked",true);
                    $("[name=mediaType]").filter("[value=IMAGE]").attr('disabled', 'disabled');
                } else {
                    $("#thumbnailURL").attr('disabled', 'disabled');
                    $("[name=mediaType]").filter("[value=IMAGE]").removeAttr('disabled');
                }
            });
        });
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=metadata")!=-1) {
        var libTabs=new ddtabcontent("metadatatabs")
        libTabs.setpersist(false)
        libTabs.setselectedClassTarget("link") //"link" or "linkparent"
        libTabs.init()
        var ajaxRequestData = {};
        var messageDivTagId="";

        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            var $form = $("#metadataform");
            
            function toggleVideoFilter() {
                if($("#enable_video_filter").is(':checked')) {
                    $("#filter_videos").prop('disabled', false);
                }
                else {
                    $("#filter_videos").prop('disabled', true);
                }
            }
            
            toggleVideoFilter();
            $("#enable_video_filter").click(toggleVideoFilter);

            $("#rescan").click(function(e) {
                $("#process").val("rescan");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": "",
                    "url": "code/metadata.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_rescan','Starting Rescan...')?>",
                    "genericError": "<?php echo tr('status_message_error_rescan','Error starting rescan!')?>",
                    0: "<?php echo tr('status_message_started','Started!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });

            $("#submit").click(function(e) {
				$("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/metadata.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>",
                    500: "<?php echo tr('error_500','Unknown server error!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });

			$("#reset").click(function(e) {
				location.reload();
				return false;
			});
        });
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=delivery")!=-1) {
        var metTab1=new ddtabcontent("deliverytabs")
        metTab1.setpersist(false)
        metTab1.setselectedClassTarget("link") //"link" or "linkparent"
        metTab1.init()
        var metTab2=new ddtabcontent("generalsettingstab")
        metTab2.setpersist(false)
        metTab2.setselectedClassTarget("link") //"link" or "linkparent"
        metTab2.init()
		var metTab3=new ddtabcontent("videosettingstab")
        metTab3.setpersist(false)
        metTab3.setselectedClassTarget("link") //"link" or "linkparent"
        metTab3.init()
        var dialogDivTagId="";
        var dialogHtml="";
        var dialogTitle="";
        var dialogButtons="";
        var ajaxRequestData = {};
        var messageDivTagId="";


        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            var $form = $("#deliveryform");
            
            $("#submit").click(function(e) {
				$("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/delivery.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>",
                    501: "<?php echo tr('error_delivery_501','Transcoding folder doesn\'t exist or cannot be written to!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });

            $("#reset").click(function(e) {
				location.reload();
				return false;
			});

            $("#addTranscodingFolder").click(function(e) {
                e.preventDefault();
                dialogDivTagId="delivery-dialog-form";
                dialogHtml="<form accept-charset='utf-8'><fieldset><label for='selValue'><?php echo tr('dialog_selected_folder','Selected Folder')?>:&nbsp;</label><input type='text' id='selValue' name='selValue' readonly='readonly' size='70' /><div id='foldertree'></div></fieldset></form>";
                dialogTitle="<?php echo tr('dialog_select_folder','Select Folder')?>";
                dialogButtons={
                    "<?php echo tr('button_select_folder','Select Folder')?>": function() {
                        var tmp = $(".sel").attr('href');
                        $("#location").val(tmp);
                        $("#foldertree").remove();
                        $(this).dialog("close");
                    },
                    "<?php echo tr('button_cancel','Cancel')?>": function() {
                        $("#foldertree").remove();
                        $(this).dialog("close");
                    }
                }
                callSelectionDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons, "folder");
                return false;
            });
        });
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=presentation")!=-1) {
        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            var $form = $("#presentationform");
            var ajaxRequestData = {};
            var messageDivTagId="";

            $("#submit").click(function(e) {
				$("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/presentation.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>",
                    500: "<?php echo tr('error_500','Unknown server error!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });

			$("#reset").click(function(e) {
				location.reload();
				return false;
			});
        });
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=remote")!=-1) {
        var rmtTabs=new ddtabcontent("rmtSecurityTab")
        rmtTabs.setpersist(false)
        rmtTabs.setselectedClassTarget("link") //"link" or "linkparent"
        rmtTabs.init()
        var rmtTab2=new ddtabcontent("rmtDeliveryQualityTab")
        rmtTab2.setpersist(false)
        rmtTab2.setselectedClassTarget("link") //"link" or "linkparent"
        rmtTab2.init()
        var rmtTab3=new ddtabcontent("rmtInternetAccessTab")
        rmtTab3.setpersist(false)
        rmtTab3.setselectedClassTarget("link") //"link" or "linkparent"
        rmtTab3.init()
        var ajaxRequestData = {};
        var messageDivTagId="";

        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            var $form = $("#remoteform");
            
            $("#submit").click(function(e) {
				$("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/remote.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>",
                    503: "<?php echo tr('error_remote_503','Provided externalAddress is not a valid domain name or IP address!')?>",
                    504: "<?php echo tr('error_remote_504','Provided remoteUserPassword is empty!')?>",
                    554: "<?php echo tr('error_remote_554','Invalid edition of Serviio, functionality not available for this edition!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
            $("#checkPortMapping").click(function(e) {
            	$("#process").val("checkPortMapping");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/remote.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_check_port_mapping','Checking accessibility...!')?>",
                    "genericError": "<?php echo tr('status_message_error_check_portmapping','Error checking portmapping!')?>",
                    0: "<?php echo tr('status_message_check_port_mapping_ok','Port mapping ok!')?>",
                    604: "<?php echo tr('error_remote_604','CDS port is not accessible externally!')?>",
                    605: "<?php echo tr('error_remote_605','CDS port mapping status could not be determined!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
			$("#reset").click(function(e) {
				location.reload();
				return false;
			});
        });
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=settings")!=-1) {
        var conTabs=new ddtabcontent("consolesettingstab")
        conTabs.setpersist(false)
        conTabs.setselectedClassTarget("link") //"link" or "linkparent"
        conTabs.init()

        var ajaxRequestData = {};
        var messageDivTagId="";

        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            var $form = $("#settingsform");
            
            $("#submit").click(function(e) {
				$("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/settings.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>",
                    500: "<?php echo tr('error_500','Unknown server error!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
			$("#reset").click(function(e) {
				location.reload();
				return false;
			});
        });
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=logs")!=-1) {
        var conTabs=new ddtabcontent("logsFileTab")
        conTabs.setpersist(false)
        conTabs.setselectedClassTarget("link") //"link" or "linkparent"
        conTabs.init()
        var conTabs=new ddtabcontent("logsContentTab")
        conTabs.setpersist(false)
        conTabs.setselectedClassTarget("link") //"link" or "linkparent"
        conTabs.init()
        
        var dialogDivTagId="";
        var dialogHtml="";
        var dialogTitle="";
        var dialogButtons="";
        var ajaxRequestData = {};
        var messageDivTagId="";

        $(document).ready(function(){
            $("#debugInfo").text("");
            $("#debugInfoDate").text("");
            $("#debugInfo2").text("");
            $("#debugInfo2Date").text("");
            var $form = $("#logform");
            
            $("#submit").click(function(e) {
                $("#process").val("save");
                messageDivTagId="#savingMsg";
                ajaxRequestData={
                    "data": $form.serialize(),
                    "url": "code/logs.php",
                    "dataType": "xml",
                    "initializing": "<?php echo tr('status_message_saving','Saving...')?>",
                    "genericError": "<?php echo tr('status_message_error_save_data','Error saving data!')?>",
                    0: "<?php echo tr('status_message_saved','Saved!')?>"
                };
                sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                return false;
            });
            
            $("#reset").click(function(e) {
                location.reload();
                return false;
            });

            $("#addLogFile").click(function(e) {
                e.preventDefault();
                dialogDivTagId="logs-dialog-form";
                dialogHtml="<form accept-charset='utf-8'><fieldset><label for='selValue'><?php echo tr('dialog_select_file','Selected file')?>:&nbsp;</label><input type='text' id='selValue' name='selValue' readonly='readonly' size='70' /><div id='filetree'></div></fieldset></form>";
                dialogTitle="<?php echo tr('dialog_select_log_file','Select Serviio log file')?>";
                dialogButtons={
                    "<?php echo tr('button_select_file','Select File')?>": function(fileName) {
                        $("#logfile").val($("#selValue").val());
                        $("#filetree").remove();
                        $(this).dialog("close");
                    },
                    "<?php echo tr('button_cancel','Cancel')?>": function() {
                        $("#filetree").remove();
                        $(this).dialog("close");
                    }
                }
                callSelectionDialog(dialogDivTagId, dialogHtml, dialogTitle, dialogButtons, "file");
                return false;
            });
        });	
    }
    //-------------------------------------------------------------------------
    if (pageurl.indexOf("content.php?tab=about")!=-1) {
    
        var ajaxRequestData = {};
        var messageDivTagId="";

        $(document).ready(function(){
            $("#uploadLicense a").click(function() {
                $(this).parent().find('input').click();
                $("#upl").change( function() {
                    //get file object using fileReader API
                    var file = document.getElementById('upl').files[0];
                    if (file) {
                        // create reader
                        var reader = new FileReader();
                        reader.readAsText(file);
                        reader.onload = function(e) {
                            // browser completed reading file
                            var license = e.target.result;
                            $("#process").val("upload");
                            messageDivTagId="#savingMsg";
                            ajaxRequestData={
                                "data": "filename=" + file.name + "&licenseData=" + encodeURIComponent(license), //encode URI backup data for POST
                                "url": "code/about.php",
                                "dataType": "xml",
                                "initializing": "<?php echo tr('status_message_importing','Uploading...')?>",
                                "genericError": "<?php echo tr('status_message_error_upload_license','Error uploading license!')?>",
                                0: "<?php echo tr('status_message_import_license','License imported successfully!')?>",
                                555: "<?php echo tr('error_license_555','License is invalid!')?>",
                                560: "<?php echo tr('error_license_560','Uploaded file does not appear to be a license file!')?>"
                            };
                            sendAjaxRequest(e, messageDivTagId, ajaxRequestData);
                        }
                    }
                });
            return false;
            });
        });
    }

    //-------------------------------------------------------------------------
}
</script>

<hr>

<?php
if ($debugLoc == "screen") {
?>
<div>
    <fieldset>
        <legend>Debug Info</legend>
        <fieldset>
            <legend>POST data</legend>
            <div id="debugInfoDate" class="debugInfoDate"> </div>
            <pre>
                <div id="debugInfo" class="debugInfo"> </div>
            </pre>
        </fieldset>
        <fieldset>
            <legend>Response data</legend>
            <div id="debugInfo2Date" class="debugInfo2Date"> </div>
            <pre>
                <div id="debugInfo2" class="debugInfo2"> </div>
            </pre>
        </fieldset>
    </fieldset>
</div>
<?php } ?>

<div align="center"><font size="1">Web UI for Serviio by <a href="https://github.com/SwoopX/Web-UI-for-Serviio">Sascha Eilers</a>, based on the great work of <a href="https://github.com/mpemberton5/Web-UI-for-Serviio">Mark Pemberton</a><br>
RESTfull class &copy; <a href="http://www.gen-x-design.com/">Ian Selby</a> // 
AJAX File Browser &copy; <a href="http://gscripts.net/free-php-scripts/Listing_Script/AJAX_File_Browser/details.html">Free PHP Scripts</a> //
Table Sorting/Filtering &copy; <a href="http://www.javascripttoolbox.com/lib/table/source.php">Matt Kruse</a> //
jQuery File Tree &copy; <a href="http://www.abeautifulsite.net/">A Beautiful Site, LLC</a> //
Math.uuid.js &copy; <a href="http://www.broofa.com">Robert Kieffer</a> licensed under the MIT and GPL licenses</font></div>

</body>
</html>
