var wrapper = document.getElementById("signature-pad"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    savePNGButton = wrapper.querySelector("[data-action=save-png]"),
    saveSVGButton = wrapper.querySelector("[data-action=save-svg]"),
    canvas = wrapper.querySelector("canvas"),
    signaturePad;

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

signaturePad = new SignaturePad(canvas);

//signaturePad.dotSize(70);// = 100;
//signaturePad.minWidth = 2.5;
//signaturePad.maxWidth = 3.5;

clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
});

savePNGButton.addEventListener("click", function (event) {
    if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
//        var png = signaturePad.toDataURL('image/svg+xml');
        const baseUrl = document.location.origin + '/LibServices/index.php';
        var tempShiftVal = document.getElementById("tempShift").value;
        var png = signaturePad.toDataURL('image/svg+xml');
        var modeVal = document.getElementById("mode").value;
        var barcodeVal = document.getElementById("barcode").value;
        
        $.post(baseUrl + "/timesheet/signatureSubmit/" + tempShiftVal, {
            signature: png, 
            mode: modeVal,
            barcode: barcodeVal
        }, function(data){
            if (data.toLowerCase().includes("success")){
                alert("SUCCESS");
                window.location.href = (baseUrl + "/timesheet/ipadPage");  
            }else{
                alert(data);
                window.location.href = (baseUrl + "/timesheet/ipadPage");
            }
        });
    }
});
//
//saveSVGButton.addEventListener("click", function (event) {
//    if (signaturePad.isEmpty()) {
//        alert("Please provide signature first.");
//    } else {
//        //window.open(signaturePad.toDataURL('image/svg+xml'));
//        var svg = signaturePad.toDataURL('image/svg+xml');
//     
//        $.post(window.location.origin + "/timesheet/testSign",{signature:svg}, function(data){
//            //window.open(window.location.origin + "/timesheet/testSign2");
//        });
//    }
//});
