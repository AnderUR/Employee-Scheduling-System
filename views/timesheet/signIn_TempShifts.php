<style>
    body {
        height: 100%;
        background-image: url("/LibServices/assets/ess_assets/img/SignInOutBackground.png");
        background-repeat: no-repeat;
        background-size: cover;
    }

    #tempFormBackground {
        background: white;
    }

    .valign-middle {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    button#btnSignInTemp {
        background: #ffb253;
        border: solid 1px #606060;
        color: white;
    }

    button#btnSignInCancel {
        background-color: #bf5000;
        border: solid 1px #606060;
        color: white;
    }

    input#shiftCaBarcode {
        height: 40px;
    }

    #title {
        color: #606060;
    }

    #tempShiftContainer {
        padding: 20px;
    }

    #endTime {
        background: white;
    }

    .top-bar,
    #headline {
        display: none;
    }
</style>

<div>
    <img src="/LibServices/assets/ess_assets/img/clockLogo.png" />
</div>

<div id="tempFormBackground" class="valign-middle">
    <div id="tempShiftContainer">
        <fieldset class=text-center>
            <h3 id="title">Temporary Shift</h3>
            <b>You don't have a pre-scheduled shift. Is this a temporary shift?</b><br>
            This Shift will be added to your timesheet upon approval by your supervisor.<br><br>
        </fieldset>

        <?= form_open('timesheet/punchInTempShift'); ?>
        <?= form_hidden('barcode', $barcode); ?>
        <div class="row">
            <div class="small-12 medium-6 medium-centered columns">
                <label for="scheduledLocation_id">Location:</label>
                <select name="scheduledLocation_id">
                    <?php foreach (shift::getLocations() as $location) : ?>
                        <option value="<?= $location['id']; ?>"><?= $location['locationText']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="endTime">What time does this shift <b>END</b>:</label>
                <input readonly="true" id="endTime" name="endTime" class="endTime" type="text" placeholder="Time format ex: 13:00" />
            </div>
        </div>
        <div class="text-center">
            <button id="btnSignInTemp" name="btnSignInTemp" type="submit" class="button large" value="Sign In">Sign In</button>
            <button id="btnSignInCancel" name="btnSignInCancel" type="submit" class="button large" value="Cancel">Cancel</button>
        </div>

        </form>
    </div>
</div>