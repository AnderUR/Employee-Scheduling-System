<?php

$returnString = '';

if ($signInStatus == "NO SCHEDULED SHIFT") {
    $returnString = '<meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="/LibServices/assets/ess_assets/jquery_components/jquery-ui/themes/smoothness/jquery-ui.min.css">
        <link rel="stylesheet" href="/LibServices/assets/ess_assets/jquery_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.css">
        <link rel="stylesheet" href="/LibServices/assets/ess_assets/foundation6/css/foundation.css" />
        <link rel="stylesheet" href="/LibServices/assets/ess_assets/css/app.css">
';
    $data['barcode']= $barcode;
    $returnString .= $this->load->view('timesheet/signIn_TempShifts', $data, true);
    $returnString .= $this->load->view('timesheet/footer.php','',true);
    echo $returnString;
} else {
    $returnString = "Error: " . $signInStatus;
    $returnString = $returnString . '<br><a href="/timesheet/ipadPage"> <button type="button" class="default button cancelShift">Go Back</button></a>';//<a class='ess_orange' href='/timesheet/ipadPage'><br>Go Back</a>";
    $returnMessage['message'] = $returnString;
    $this->web->getResponse_ess('timesheet/controls/returnMessage', $returnMessage);
}
