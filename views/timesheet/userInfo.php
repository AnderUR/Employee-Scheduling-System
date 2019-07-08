<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$thisUser = new webuser();

$emplProfile = "";
if($thisUser->is_supervisor()) {
    $emplProfile = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="empl_profile" />';
}

?>

<?=form_open('timesheet/viewEmployee');?>
    <?= form_hidden('caID', $caID);
    $ca = new webuser($caID);
    $emplName = ucwords(strtolower($ca->getProperties()['first_name']." ". $ca->getProperties()['last_name']));

    ?>

    <div class="tableMenuWrapper boxShadow large-9 row">
        <h3 class="shifts_userName"><?= $emplName . "  " . $emplProfile ?></h3>
    </div>

    <fieldset class="formContainer boxShadow large-9 medium-9 row">
        <div class="formInnerContainer small-12 columns">

            <div class="large-5 columns">

                <label for="phoneNumber">Phone</label>
                <input type="text" name="phone" id="phoneNumber" class="emplCardInput text-center"
                       value="<?= $ca->getProperties()['phone']; ?>" />
                <label for="barcode">Barcode</label>
                <input type="text" name="barcode" id="barcode" class="emplCardInput"
                       value="<?=$ca->getProperties()['barcode'];  ?>" disabled />
            </div>

            <div class="large-6 columns">

                <!--<label for="address">Address</label>
                <input type="text" name="address" id="address" class="emplCardInput"
                       value="<?php //$ca->getProperties()['address'];  ?>"></input>-->
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="emplCardInput"
                       value="<?= $ca->getProperties()['email']; ?>" disabled />

                <label for="emergencyContact">Emergency Contact</label>
                <input type="text" name="emergencyContact" id="emergencyContact" class="emplCardInput"
                       value="<?=$ca->getProperties()['emergencyContact'];  ?>" />
            </div>
        </div><!-- emplCardInnerContainer -->
        <div class="buttonFormContainer columns">

            <div class="large-2 medium-4 small-4 columns">
              <a href=" <?= site_url('timesheet/manageEmployee/'.$caID); ?> "> <button type="button" class="default button cancelShift">Cancel</button></a>
            </div>

            <div class="large-10 medium-8 small-8 columns">
                <button type="submit" name="editEmployee" class="default button addShift">Save</button>
            </div>
        </div>
    </fieldset><!-- emplShiftsOuterContainer -->
</form>
