<?php
include 'menuf.php';


echo <<<_endpage
<!DOCTYPE html>
<html>
<head>
<title>A JSmol demo </title>
<meta charset="utf-8">
<script type="text/javascript" src="./jsmol/JSmol.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {

Info = {
        width: 500,
        height: 500,
        debug: false,
        j2sPath: "jsmol/j2s",
        color: "0xC0C0C0",
  disableJ2SLoadMonitor: true,
  disableInitialConsole: true,
        addSelectionOptions: false,
        readyFunction: null,
        src: "./Additional_Files/Oai40000.sdf"

}

$("#mydiv").html(Jmol.getAppletHtml("jmolApplet0",Info))

});
</script>
</head>
<body style="padding-left:150px;">
<span id=mydiv></span>
<p>
<a href="javascript:Jmol.script(jmolApplet0, 'spin on')">Make me dizzy!</a>
<br/>
<a href="javascript:Jmol.script(jmolApplet0, 'spin off')">I've had enough...</a>
</p>
</body>
</html>
_endpage;
?>