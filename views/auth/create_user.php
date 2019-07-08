<h3>Register</h3>
<p style="color=red;">
    <div id="infoMessage"><b><?php echo $message; ?></b></div>
</p>
<?php echo form_open("auth/create_user"); ?>
<fieldset>

    <p>
        <?php echo lang('create_user_fname_label', 'first_name'); ?> <br />
        <?php echo form_input($first_name); ?>
    </p>

    <p>
        <?php echo lang('create_user_lname_label', 'last_name'); ?> <br />
        <?php echo form_input($last_name); ?>
    </p>

    <p>
        <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
        <?php echo form_input($phone1); ?>
    </p>

    <p>
        <?php echo lang('create_user_email_label', 'email'); ?> <br />
        <?php echo form_input($email); ?>
    </p>

    <p>
        <label>Enter Barcode:</label> <br />
        <?php echo form_input($barcode); ?>
    </p>

    <p>
        <?php echo lang('create_user_password_label', 'password'); ?> <br />
        <?php echo form_input($password); ?>
    </p>

    <p>
        <?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?> <br />
        <?php echo form_input($password_confirm); ?>
    </p>

    <p>
        <input type="submit" class="button small round" value="REGISTER">
    </p>
</fieldset>
<?php echo form_close(); ?>

<!--
              <p>
        <?php echo lang('create_user_company_label', 'company'); ?> <br />
        <?php echo form_input($company); ?>
              </p>
    <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
        <?php echo form_input($phone1); ?>-
            <?php echo form_input($phone2); ?>-
                <?php echo form_input($phone3); ?></p>-->