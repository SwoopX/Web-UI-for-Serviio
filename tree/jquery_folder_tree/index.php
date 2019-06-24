<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Folder Selection Form</title>
    <style type="text/css">
    body {
        color: #666666;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 12px;
        line-height: 1.5;
    }
    </style>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="jquery.foldertree.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#smallbrowser").folderTree({
                root: '/',
                script: 'jquery.foldertree.php',
                loadMessage: 'My loading message...'
            });

            $("#smallbrowser").click(function() {
                var tmp = $(".sel").attr('href');
                $("#selValue").val(tmp);
            });
            $(".button").click(function() {
                var tmp = $(".sel").attr('href');
                // return tmp
            });
            $(".cancel").click(function() {
                // cancel form
            });
        });
    </script>
</head>

<body>
    <h3>Select Folder</h3>

    <label for="selValue">Selected Folder: </label> <input type="text" id="selValue" readonly="readonly" size="75" />
    <div id="smallbrowser"></div>

    <input type="button" class="button" name="submit" value="Select">
    <input type="button" class="cancel" name="cancel" value="Cancel">
</body>
</html>
