$(function(){

	var formId = document.forms[0].id;
	var inputFields = $('#'+formId+' input[type=text],#'+formId+' input[type=password]');
	
	inputFields.each(function(){	
	
		$(this).focus(function() {
			if($(this).val() == langPack[this.id]) {
				$(this).val("");
			}
		});
		
		$(this).focusout(function() {
			if($(this).val() == "") {
				$(this).val(langPack[this.id]);
			}
		});	
	
	});
	
    $('form').submit(function(){ return false; });
	$('#loginBtn').click(function(){

        //remove classes
        $('#myform input').removeClass('error').removeClass('valid');

        //check if inputs aren't empty
        var fields = $('#myform input[type=text], #myform input[type=password]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
			//The username must be longer than 3 characters
            if ( $(this).attr('id') == 'username' && value.length < 3) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError1']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError1' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;	
			//Insert username
			} else if ( $(this).attr('id') == 'username' && value == langPack['username'] ){
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);				
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError2']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError2' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;	
			//The password must be longer than 5 characters
            } else if ( $(this).attr('id') == 'password' && value.length < 5) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError3']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError3' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;	
			//Insert password		
			} else if ( $(this).attr('id') == 'password' && value == langPack['password'] ){
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);				
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError4']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError4' );				
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;				
            } else {
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);

            }		
        });    
		   
        if(!error) {
				$("#login_loading").ajaxStart(function () {
					$('#login_msg').fadeOut(500);
					$('#login_error').fadeOut(500);
					$(this).fadeIn(500);
				});

				$("#login_loading").ajaxStop(function () {
					$(this).fadeOut(500);
				});		
				$.ajax({
					type: "POST",
					url: "server_processes/login_validation.php",
					async: false,
					data:{
						  username: $('#username').val(),
						  password: $('#password').val()
						  },
					success: function(response){
						if ( response == "asth"){
							$('.content').load('content/patient/content.php');
							$('#banner_container').fadeOut('slow', function(){$('#banner_container').html("");});
							$('#banner_container_patient').load('content/patient/banner.php').css('display', 'inline-block').hide();
							$('#banner_container_patient').fadeIn('slow');
							$('#header').fadeOut('slow', function(){$('#header').html("");});
							$('#header_patient').load('content/patient/menu.php').css('display', 'inline-block').hide();
							$('#header_patient').fadeIn('slow', function(){$.getScript("client_processes/general.js");});
						}else if (response == "staff"){
							$('.content').load('content/staff/content.php');
							$('#banner_container').fadeOut('slow', function(){$('#banner_container').html("");});
							$('#banner_container_staff').load('content/staff/banner.php').css('display', 'inline-block').hide();
							$('#banner_container_staff').fadeIn('slow');							
							$('#header').fadeOut('slow', function(){$('#header').html("");});
							$('#header_staff').load('content/staff/menu.php').css('display', 'inline-block').hide();
							$('#header_staff').fadeIn('slow', function(){$.getScript("client_processes/general.js");});
						}else if (response == "manager"){
							$('.content').load('content/manager/content.php');
							$('#banner_container').fadeOut('slow', function(){$('#banner_container').html("");});
							$('#banner_container_manager').load('content/manager/banner.php').css('display', 'inline-block').hide();
							$('#banner_container_manager').fadeIn('slow');							
							$('#header').fadeOut('slow', function(){$('#header').html("");});
							$('#header_manager').load('content/manager/menu.php').css('display', 'inline-block').hide();
							$('#header_manager').fadeIn('slow', function(){$.getScript("client_processes/general.js");});						
						}else if (response == "false"){
							$('#login_error').fadeIn(500);
						}
					}
				});	
				
        }                  

    });
	
 });