<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 13/03/2018
 * Time: 13:26
 */

?>


<!--    para el escaneo de codigos de barra-->
<script type="text/javascript" src="../js/grid.js"></script>
<script type="text/javascript" src="../js/version.js"></script>
<script type="text/javascript" src="../js/detector.js"></script>
<script type="text/javascript" src="../js/formatinf.js"></script>
<script type="text/javascript" src="../js/errorlevel.js"></script>
<script type="text/javascript" src="../js/bitmat.js"></script>
<script type="text/javascript" src="../js/datablock.js"></script>
<script type="text/javascript" src="../js/bmparser.js"></script>
<script type="text/javascript" src="../js/datamask.js"></script>
<script type="text/javascript" src="../js/rsdecoder.js"></script>
<script type="text/javascript" src="../js/gf256poly.js"></script>
<script type="text/javascript" src="../js/gf256.js"></script>
<script type="text/javascript" src="../js/decoder.js"></script>
<script type="text/javascript" src="../js/qrcode.js"></script>
<script type="text/javascript" src="../js/findpat.js"></script>
<script type="text/javascript" src="../js/alignpat.js"></script>
<script type="text/javascript" src="../js/databr.js"></script>

<body onload="load()">
<div class="container">

    <object  id="iembedflash" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="320" height="240">
        <param name="movie" value="camcanvas.swf" />
        <param name="quality" value="high" />
        <param name="allowScriptAccess" value="always" />
        <embed  allowScriptAccess="always"  id="embedflash" src="camcanvas.swf" quality="high" width="320" height="240" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" mayscript="true"  />
    </object>

</div>
<button onclick="captureToCanvas()">Capture</button><br>
<canvas id="qr-canvas" width="640" height="480"></canvas>
</body>


