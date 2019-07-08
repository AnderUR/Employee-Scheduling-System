<title>Forget Password</title>
<?php //$this->load->view('template_mobile_clean') ?>
<div class="small-centered small-12 medium-6 columns">
    <br><br>
<center>
    <h1>
    <?php //echo lang('forgot_password_heading');
?>
</h1>
<h3><?php echo "Library Systems Services";?></h3>

<p><?php echo "To reset your password, please enter your email below.";?></p>

<div id="infoMessage"><?php //echo $message;?></div>

<?php echo form_open("auth/forgot_password");?>

      <p>
      	<label for="identity"><input type="text" name="identity" />
      	<?php //echo form_input($identity);?>
      </p>

      <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'));?></p>

<?php echo form_close();?>
    
</center>
</div>