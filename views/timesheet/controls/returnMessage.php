<!-- View for temporary shifts -->
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
	  color: white; }
	  
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
	#returnMessageContainer {
		padding: 20px;	
	}
        
        .top-bar, #headline{
        display:none;
    }
</style>

<div>
	<img src="/LibServices/assets/ess_assets/img/clockLogo.png"></img>
</div> 

<div id="tempFormBackground" class="valign-middle">
	<div id="returnMessageContainer">
		<fieldset class=text-center>
			<h3 id="title"><?=$message;?></h3>
		</fieldset>	

	</div>
</div>
<?php
