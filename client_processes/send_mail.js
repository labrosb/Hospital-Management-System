$(function(){

	var formId = document.forms[0].id;
	var inputFields = $('#'+formId+' input[type=text],#'+formId+' textarea ');
	
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
	
    $('#sendMailBtn').click(function(){
        //remove classes	
        $('#myform2 input').removeClass('error').removeClass('valid');

        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
        var fields = $('#contact_form input[type=text]');
        var error = 0;
        fields.each(function(){
           var value = $(this).val();
           if(($(this).attr('id')=='name' && value == langPack['name']) || 
			  ($(this).attr('id')=='email' && value == "E-mail") ||
			  ($(this).attr('id')=='sendMailBtn' && value == langPack['message'])) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError5' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
            }else if(( $(this).attr('id')=='email' && !emailPattern.test(value) ) ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError6']);
				$(".err_msg").removeAttr("data-inter");
				$('.err_msg').attr( 'data-inter', 'formError6' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);               
                error++;
            } else {
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            }
        });

		if($('#text').val() == "Message") {
			$('#text').addClass('error');
            $('#text').effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$(".err_msg").removeAttr("data-inter");
				$('.err_msg').attr( 'data-inter', 'formError5' );
			$("label[for='"+$('#text').attr('id')+"']").fadeTo('slow', 1);               
            error++;		
		}
		
        if(!error){
			$('#send_msg').html("<img id='loading_img' src='styles/images/loading2.gif' />")
			$('#send_msg').fadeIn('fast');
				$.ajax({
					type: "POST",
					url: "server_processes/send_mail/general_mail.php",
					data:{
						name : $('#name').val(),
						email : $('#email').val(),
						text : $('#message').val()
						},
					success: function(response){
						if(response == 'OK'){
							$('#send_msg').css('color', '#7f7e7e');
							$('#send_msg').html(langPack['sendSucceed']);
							$("#send_msg").removeAttr("data-inter");
							$('#send_msg').attr( 'data-inter', 'sendSucceed' );
						}else{
							$('#send_msg').css('color', '#ff0000');
							$('#send_msg').html(langPack['ERROR']);												
							$("#send_msg").removeAttr("data-inter");
							$('#send_msg').attr( 'data-inter', 'ERROR' );
						}
					}
				});	
				
        } 
    });
	

		
});