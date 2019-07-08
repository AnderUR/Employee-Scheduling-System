<?php
/**
 *  Sign in view by user id, which is passed through uri 
 */ 
?>
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
        <?php
        echo form_open('timesheet/loginCheck');
        echo form_hidden('barcode', $barcode);
        $user = new Webuser();
        $user->setUserByBarcode($barcode);
        ?>
        <!--<h3 class="showTime"></h3>-->
        <br>
        <input id="shiftCaBarcode" readonly type="number" placeholder="<?= $user->getUsername(); ?>" />

        <button id="btnSignIn" name="btnSignIn" type="submit" class="button warning small-6 columns" value="Sign In">signin</button>

        <button id="btnSignOut" name="btnSignOut" type="submit" class="button warning small-6 columns" value="Sign Out">signout</button>
        <br>
        </form>
    </div>

</div><br>
<?php
$this->load->view('timesheet/currentAnnouncements');