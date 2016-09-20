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
  	
    //first_step
    $('form').submit(function(){ return false; });
    $('#submit_edit_first').click(function(){
        //remove classes
        $('#submit_edit_first').removeClass('error').removeClass('valid');

		var fields = $('#first_step input[type=text]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
			var phone = $('#phone').val();
			var cellPhone = $('#cellPhone').val();
            var value = $(this).val();
           if( $(this).attr('id')!='phone' && $(this).attr('id')!='cellPhone' && $(this).attr('id')!='postCode' && value==langPack[$(this).attr('id')] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError5');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
			}else if (value.length<3){
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError1']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError1');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
			}else if( $(this).attr('id')!='phone' && $(this).attr('id')!='cellPhone' && $(this).attr('id')!='postCode' && value==langPack[$(this).attr('id')] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError5');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
			}else if ( $(this).attr('id')!='phone' && $(this).attr('id')!='cellPhone' && $(this).attr('id')!='postCode' && value==langPack[$(this).attr('id')] && value.length<3){
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError8']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError8');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
            }else if(($(this).attr('id')=='phone' || $(this).attr('id')=='cellPhone') && phone==langPack['phone'] && cellPhone==langPack['cellPhone'] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError10']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError10');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);               
                error++;
            }else if((value!=langPack[$(this).attr('id')] && $(this).attr('id')=='postCode' && (isNaN( $('#postCode').val() ) ) ) || ( value!=langPack[$(this).attr('id')] && $(this).attr('id')=='phone' && (isNaN( $('#phone').val() ) ) ) || ( value!=langPack[$(this).attr('id')] && $(this).attr('id')=='cellPhone' && (isNaN( $('#cellPhone').val() ) ) ) ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError11']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError11');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);               
                error++;
            }else{
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            }		
        });                     	
		
	    if(!error) {
			$('.error_p').fadeTo('fast', 0);		
			$('#form_msg1 h1').fadeTo('fast', 0);
			$('#form_loading_p').fadeTo('slow', 1);  			
			sendData(1);
		}
    });


    $('#submit_edit_second').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
        var fields = $('#second_step input[type=text], #second_step input[type=password]');
        var error = 0;
        fields.each(function(){	
			var value = $(this).val();

            if( $(this).attr('id')=='email' && !emailPattern.test(value) ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError11']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError11');				
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);               
                error++;
			}else if($(this).attr('id')=='password' && value.length<5){
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError3']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError3' );				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);    
                error++;
			}else if(value==langPack[$(this).attr('id')] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr('data-inter', 'formError5' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;								
            }else {
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            }
        });
		
	    if(!error) {
			$('.error_p').fadeTo('fast', 0);		
			$('#form_msg2 p').fadeTo('fast', 0);
			$('#form_loading_p2').fadeTo('slow', 1);  			
			sendData(2);
		}
    });


    $('#submit_edit_third').click(function(){
        //remove classes
        $('#third_step input').removeClass('error').removeClass('valid');

        //ckeck if inputs aren't empty
        var fields = $('#third_step input[type=password]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( value==langPack[$(this).attr('id')] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
 				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);    
                error++;
			}else if (($(this).attr('id')=='oldPassword' && value.length<5) ||
					  ($(this).attr('id')=='newPassword' && value.length<5) ||
					  ($(this).attr('id')=='passwordConf' && value.length<5) )
			{
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
 				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError3']);
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);    
                error++;
			}else if ($('#newPassword').val() != $('#passwordConf').val()){
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
 				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError14']);
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);    
				error++;
            } else {
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            } 
        });        

	    if(!error) {
			$('.error_p').fadeTo('fast', 0);
			$('#form_msg3 h1, #form_msg3 p').fadeTo('fast', 0);
			$('#form_loading_p3').fadeTo('slow', 1);  			
			sendData(3);
		}		
		
    });
	
	$('.choice_buttons tr #communication_upd').click(function(){	
		$('.content').animate({
			height: 690
			}, 800, function(){
					$('#first_step').slideDown();
					$('#second_step').slideUp();
					$('#third_step').slideUp();	
				}	
		);
	});
	
	$('.choice_buttons tr #email_upd').click(function(){
		$('#first_step').slideUp();	
		$('#third_step').slideUp();		
		$('.content').animate({
			height: 450
			}, 800, function(){
					$('#first_step').slideUp();	
					$('#second_step').slideDown();
					$('#third_step').slideUp();	

				}	
		);
	});	

	$('.choice_buttons tr #password_upd').click(function(){
		$('.content').animate({
			height: 510
			}, 800, function(){
					$('#first_step').slideUp();
					$('#second_step').slideUp();
					$('#third_step').slideDown();
				}	
		);
	});	

	function sendData(step){
		$.ajax({
			type: "POST",
			url: "server_processes/edit_patient.php",
			data:{
				step: step,
				birthDate: $('#birthDate').val(),
				address: $('#address').val(),
				city: $('#city').val(),
				postCode: $('#postCode').val(),
				phone: $('#phone').val(),
				cellPhone: $('#cellPhone').val(),
				email: $('#email').val(),
				password: $('#newPassword').val(),
				oldpass: $('#oldPassword').val(),
						},
			success: function(response){

						if ( response == "DONE"){
							if(step == 1){
								$('#form_loading_p').fadeTo('slow', 0);  			
								$('h1.succ').html(langPack['editSuccess']).fadeTo('slow', 1);

							}
							else if(step == 2){
								$('#form_loading_p2').fadeTo('slow', 0);  			
								$('p.succ2').html(langPack['editSuccess']).fadeTo('slow', 1);
							}	
							else if(step == 3){
								$('#form_loading_p3').fadeTo('slow', 0);  			
								$('h1.succ3').html(langPack['editSuccess']).fadeTo('slow', 1);
							}	
						}							
						else if(response =="WRONG PASS"){
							if(step == 2){
								$('#form_loading_p2').fadeTo('slow', 0);  			
								$('#form_msg2 p.error_p').html(langPack['wrongPass']).fadeTo('slow', 1);
							}	
							else if(step == 3){
								$('#form_loading_p3').fadeTo('slow', 0);  			
								$('#form_msg3 h1.error_p').html(langPack['wrongPass']).fadeTo('slow', 1);
							}
						}
						else {
							if(step == 1){
								$('#form_loading_p').fadeTo('slow', 0);  	
								$('#form_msg1 h1.error_p').html(langPack['regFailed']).fadeTo('slow', 1);														
								$('#form_msg1 p.error_p').html(langPack['regSubFaied']).fadeTo('slow', 1);
							}							
							else if(step == 2){
								$('#form_loading_p2').fadeTo('slow', 0);  			
								$('#form_msg2 h1.error_p').html(langPack['regFailed']).fadeTo('slow', 1);
								$('#form_msg2 p.error_p').html(langPack['regSubFaied']).fadeTo('slow', 1);
							}	
							else if(step == 3){
								$('#form_loading_p3').fadeTo('slow', 0);  			
								$('#form_msg3 h1.error_p').html(langPack['regFailed']).fadeTo('slow', 1);
								$('#form_msg3 p.error_p').html(langPack['regSubFaied']).fadeTo('slow', 1);
							}						
						
						}

					}
		});	
				            
	} 		
		
});