<style>
    body {
        height: 100%;
        background-image: url("/LibServices/assets/ess_assets/img/SignInOutBackground.png");
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
    }

    .valign-middle {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    #qr-center {
        margin-top: 2.2em;
    }

    label[for=shiftCaBarcode] {
        font-size: 16px;
        color: white;
    }

    #shiftCaBarcode {
        height: 2.3rem;
    }

    button#btnSignIn,
    button#btnSignOut {
        font-size: 1.29rem;
        border: solid 1.5px #606060;

    }

    button#btnSignIn {
        background: #ffb253;
    }

    button#btnSignOut {
        background-color: #bf5000;
    }

    #buttonsCntnr {
        margin-bottom: 1em;
    }

    .top-bar,
    #headline {
        display: none;
    }

    .showTime {
        color: white;
    }

    .doubleBorder {
        margin-top: 25px;
        border-style: double;
        border-width: 5px;
        border-color: white;
    }

    /*input#shiftCaBarcode {
    height: 3em;
    width: 120%;
}*/
</style>

<div>
    <img src="/LibServices/assets/ess_assets/img/clockLogo.png" />
</div>

<div class="row medium-5 small-12 columns doubleBorder">

    <div class="small-12 columns">
        <?= form_open('timesheet/loginCheck'); ?>
        <!--<h3 class="showTime"></h3>-->
        <br>
        <label for="shiftCaBarcode">Enter your barcode</label>
        <input id="shiftCaBarcode" name="barcode" type="number" value="" autofocus />
        <div id="buttonsCntnr" class="small-12 columns">
            <button id="btnSignIn" name="btnSignIn" type="submit" class="button warning small-6 columns" value="Sign In">signin</button>
            <button id="btnSignOut" name="btnSignOut" type="submit" class="button warning small-6 columns" value="Sign Out">signout</button>
        </div>
        <br>
        </form>
    </div>

</div><br>
<?php
$this->load->view('timesheet/currentAnnouncements');