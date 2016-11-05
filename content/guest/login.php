    <script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"></script>
	<script type="text/javascript" src="client_processes/login.js"></script>
	
	<div id="intro">
						
		<h1 data-inter="login"> </h1>
						
        <div class="content_page">
			<form id="myform" class="loginForm" action="#" method="post">			
				<!-- #first_step -->
					<div id="form_left">
						<div id="myform_container">
							<div class="myform">
								<input type="text" name="username" id="username" value="Username" />
								<label for="username" id='usernameLabel' class="err_msg"> </label>

								<input type="password" name="password" id="password" value="Password" />
								<label for="password" id='passwordLabel' class="err_msg"> </label>
								
								<input type="submit" class="loginBtn" name="login" id="loginBtn" value="Login" />     
							</div>    
						</div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
					</div>
				</form>
											
				<div id="form_right">
					<div id ="login_msg"> 
						<p data-inter="loginMsg"> </p> 
					</div>
					<div id ="login_error"> 
						<img id='checkmark' src='styles/images/error.gif' />
						<p data-inter="pwdError"> </p> 
					</div>
					<div id='login_loading'>
						<img id='loading_img' src='styles/images/loading_icon.gif' height="120" width="120" />
					</div>

				</div><!--right ends-->
				
			</div>
 
		</div><!--content end-->
											
	</div><!--introduction end-->

	<script>
		changeLang(defaultLang);
	</script>		

									