<!doctype html>
<html lang="en">    

    <head>
        <meta charset="utf-8">
        <title>Signature</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <link rel="stylesheet" href="/LibServices/assets/ess_assets/signature/css/signature-pad.css">
        <script src="/LibServices/assets/ess_assets/foundation6/js/vendor/jquery.js"></script>

    </head>

    <?php
    echo form_input(array('type' => 'hidden', 'id' => 'mode', 'value' => $mode));
    echo form_input(array('type' => 'hidden', 'id' => 'barcode', 'value' => $barcode));
    echo form_input(array('type' => 'hidden', 'id' => 'tempShift', 'value' => $tempShift));
    ?>

    <body onselectstart="return false">
    <center><b><div class="description">Please sign below (<?=$mode;?>)</div></b></center>
        <div id="signature-pad" class="m-signature-pad">
            <div class="m-signature-pad--body">
                <canvas></canvas>
            </div>
            <div class="m-signature-pad--footer">
                <div class="left">
                    <button type="button" class="button clear" data-action="clear">Redo</button>
                </div>
                <div class="right">
                    <button type="button" class="button save" data-action="save-png">Submit</button>
                    <!--        <button type="button" class="button save" data-action="save-svg">Save as SVG</button>-->
                </div>
            </div>
        </div>

        <script src="/LibServices/assets/ess_assets/signature/js/signature_pad.js"></script>
        <script src="/LibServices/assets/ess_assets/signature/js/app.js"></script>
    </body>
</html>