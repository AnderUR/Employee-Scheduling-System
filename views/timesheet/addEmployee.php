<div class="large-9 medium-9 row">
    <h3 class="text-center">Add Employee</h3>
    <p>&nbsp The employee will be emailed a temporary barcode that will serve as their barcode login and password</p>
</div>
<?php
echo form_open('timesheet/adminPanel');
?>
<fieldset class="adminformContainer formContainer boxShadow large-9 medium-9 row">
    <div class="formInnerContainer small-12 columns">

        <div class="large-5 columns">

            <label for="emplName">First Name</label>
            <input type="text" name="firstname" id="emplName" />

            <label for="email">Email</label>
            <input type="text" name="email" id="email" />

        </div>

        <div class="large-6 columns">

            <label for="emplLast">Last Name</label>
            <input type="text" name="lastname" id="email" />

            <label for="phoneNumber">Phone</label>
            <input type="text" name="phone" id="phoneNumber" />

        </div>

    </div>

    <div class="buttonFormContainer columns">

        <div class="large-2 medium-4 small-4 columns">
            <a href="<?= site_url('/timesheet/adminPanel/'); ?>"><button type="button" class="default button cancelShift">Cancel</button></a>
        </div>

        <div class="large-10 medium-8 small-8 columns">
            <button type="submit" name="newEmployeeByName" class="default button addShift">Add</button>
        </div>

    </div>

</fieldset>
</form>

<br>
<br>

<div class="large-9 medium-9 row">
    <h3 class="text-center">Manage Users and Privileges</h3>
    <hr>
</div>
<div class="adminformContainer formContainer boxShadow large-9 medium-9 row">
    <div class="formInnerContainer small-12 columns">
        <p>In the auth page, you can make detail changes, such as: </p>
        <ul>
            <li>View all users.</li>
            <li>Add/Edit user with more details.</li>
            <li>Activate/deactivate accounts.</li>
            <li>Add new privilege group (currently there are 4: admin(1), supervisor(2), staff(3), guest(4)).</li>
            <li>Edit user account privilege.</li>
        </ul>
        <p>NOTE: Changes made in the below page will not be emailed to the user, except to inform them an account was created for them.</p>
        <div class="text-center"><?= anchor('auth/index', 'Go to manage users', 'class="ess_orange"'); ?></div>
    </div>
</div>
<br>