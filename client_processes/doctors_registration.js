function isValidDate(date) {
	
	var d = new Date();
	var currentYear = d.getFullYear();
	
	var valid = true;

	var arrayDate = date.split('/');

	var day = arrayDate[0];
	var month = arrayDate[1];
	var year  = arrayDate[2];
	var other = arrayDate[3];
	
	if (typeof other != 'undefined'){ valid = false;}
	else if ((isNaN(day)) || (isNaN(month)) || (isNaN(year))) valid = false;
	else if((month < 1) || (month > 12)) valid = false;
	else if((day < 1) || (day > 31)) valid = false;
	else if((year < 1900) || (year > currentYear)) valid = false;
	else if(((month == 4) || (month == 6) || (month == 9) || (month == 11)) && (day > 30)) valid = false;
	else if((month == 2) && (((year % 400) == 0) || ((year % 4) == 0)) && ((year % 100) != 0) && (day > 29)) valid = false;
	else if((month == 2) && ((year % 100) == 0) && (day > 29)) valid = false;

	return valid;
}


function struck_list(response){
	for(var i=0; i<response.length; i++){
		$('.categories_select').append('<option value="'+response[i].Id+'">'+response[i].Specialty_name+'</option>');	
	}
}	
	
		
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
	
    //reset progress bar
    $('#progress').css('width','0');
    $('#progress_text').html(langPack['Completed_0']);
	$('#progress_text').removeAttr("data-inter");
	$('#progress_text').attr( 'data-inter', 'Completed_0' );
	$('#registration_container').height("603px");
	
	
	$.ajax({
		type: "POST",
		url: "server_processes/load_specialties.php",
		dataType: "json",	  					
		success: function(response){
			struck_list(response);
			}					
	});		
	
	
    //first_step
    $('form').submit(function(){ return false; });
    $('#submit_first').click(function(){
        //remove classes
        $('#first_step input').removeClass('error').removeClass('valid');

        var fields = $('#first_step input[type=text], #first_step select[type=text]');
        var error = 0;
        fields.each(function(){
			var date = $('#birthDate').val();
			var dateValidation = isValidDate(date);  
            var value = $(this).val();
            if( value==langPack[$(this).attr('id')] && $(this).attr('id')!="mothersName") {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError5' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
			}else if (value.length<3){
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError8']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError8' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
            }else if ($(this).attr('id') == 'birthDate' && dateValidation == false){ 			    
                $('#birthDate').removeClass('valid').addClass('error');
                $('#birthDate').effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError9']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError9' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
            } else{
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            }		
        });        
        
        if(!error) {
			$('.content').animate({
				height: 873
				}, 800, function(){
						$('#progress_text').html(langPack['Completed_33']);
						$('#progress_text').removeAttr("data-inter");
						$('#progress_text').attr( 'data-inter', 'Completed_33' );	
						$('#progress').css('width','113px');
						$('#myform').height("750px");
						//slide steps
						$('#first_step').slideUp();
						$('#second_step').slideDown();	
					}
			); 
							
        }               

    });


    $('#submit_second').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');

        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
        var fields = $('#second_step input[type=text]');
        var error = 0;
        fields.each(function(){
		//alert(langPack['phone']);
		//alert(langPack['cellPhone']);

            var value = $(this).val();
			var phone = $('#phone').val();
			var cellPhone = $('#cellPhone').val();
           if( $(this).attr('id')!='phone' && $(this).attr('id')!='cellPhone' && $(this).attr('id')!='postCode' && $(this).attr('id')!='fathersName' && value==langPack[$(this).attr('id')] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
			}else if( ($(this).attr('id')=='phone' || $(this).attr('id')=='cellPhone' || $(this).attr('id')=='postCode') && value.length<3){
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError5' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;
            }else if(($(this).attr('id')=='phone' || $(this).attr('id')=='cellPhone') && phone==langPack['phone'] && cellPhone==langPack['cellPhone'] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError10']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError10' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);               
                error++;
            }else if(( $(this).attr('id')=='email' && !emailPattern.test(value) ) || (value!=langPack[$(this).attr('id')] && $(this).attr('id')=='postCode' && (isNaN( $('#postCode').val() ) ) ) || ( value!=langPack[$(this).attr('id')] && $(this).attr('id')=='phone' && (isNaN( $('#phone').val() ) ) ) || ( value!=langPack[$(this).attr('id')] && $(this).attr('id')=='cellPhone' && (isNaN( $('#cellPhone').val() ) ) ) || ( value!=langPack[$(this).attr('id')] && $(this).attr('id')=='workPhone' && (isNaN( $('#workPhone').val() ) ) ) ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError11']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError11' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);               
                error++;
			} else {
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            }
        });

        if(!error) {   
			$('.content').animate({
				height: 780
				}, 800, function(){
						$('#progress_text').html(langPack['Completed_66']);
						$('#progress_text').removeAttr("data-inter");
						$('#progress_text').attr( 'data-inter', 'Completed_66' );
						$('#progress').css('width','226px');
						$('#progress_bar').css('margin-top','75px');
						$('#second_step#myform').height("710px");							
						//slide steps
						$('#second_step').slideUp();
						$('#third_step').slideDown();  
					}
			); 
				
        } else return false;

    });


    $('#submit_fourth').click(function(){
        //remove classes
        $('#third_step input').removeClass('error').removeClass('valid');

        //ckeck if inputs aren't empty
        var fields = $('#third_step input[type=text], #third_step input[type=password]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( value==langPack[$(this).attr('id')] ) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError5' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);    
                error++;
			}else if ( $(this).attr('id')=='password' && value.length<5 ){
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError13']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError13' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);    
                error++;
			}else if ( ( $(this).attr('id')=='password' || $(this).attr('id')=='cpassword') && $('#password').val() != $('#cpassword').val() ) {
				$(this).removeClass('valid').addClass('error');
				$(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError14']);
				$("label[for='"+$(this).attr('id')+"']").removeAttr("data-inter");
				$("label[for='"+$(this).attr('id')+"']").attr( 'data-inter', 'formError14' );
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);    
                error++; 
            } else {
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            } 
        });        
		
        
        if(!error) {
				$.ajax({
					type: "POST",
					url: "server_processes/registration_doctor.php",
					data:{
						name: $('#name').val(),
						surname: $('#surname').val(),
						fathersName: $('#fathersName').val(),
						mothersName: $('#mothersName').val(),
						sex: $('#sex').val(),
						birthDate: $('#birthDate').val(),
						address: $('#address').val(),
						city: $('#city').val(),
						postCode: $('#postCode').val(),
						phone: $('#phone').val(),
						cellPhone: $('#cellPhone').val(),
						workPhone: $('#workPhone').val(),
						email: $('#email').val(),
						specialty: $('#specialty').val(),
						biog: $('#biog').val(),
						password: $('#password').val()
						},
					success: function(response){
						$('#third_step').fadeOut();
						$('.content').animate({
							height: 250
							}, 800, function(){
										$('#myform').height("150px");
										$('#progress_bar').css('margin-top','-50px');
										$('#fourth_step').fadeIn();  
										if ( response == "success"){
											//update progress bar
											$('#progress_text').html(langPack['Completed_100']);
											$('#progress_text').removeAttr("data-inter");
											$('#progress_text').attr( 'data-inter', 'Completed_100' );
											$('#progress').css('width','339px');
											$('#fourth_step').html("<div id='message'></div>");
											$('#message').html("<img id='checkmark' src='styles/images/check.png' />")
											.append('<h2 id = "success_msg1">'+langPack["docRegCompleted"]+'</h2>')
											.hide();
											$("message").removeAttr("data-inter");
											$("message").attr( 'data-inter', 'docRegCompleted' );
											$('#message').fadeIn(1500);												
										}else{
											//update progress bar
											$('#progress_text').html(langPack['error']);
											$('#progress_text').removeAttr("data-inter");
											$('#progress_text').attr( 'data-inter', 'error' );
											$('#progress').css('width','339px');
											$('#fourth_step').html("<div id='message'></div>");
											$('#message').html("<img id='checkmark' src='styles/images/error.gif' />")
											.append('<h2 id = "success_msg1">'+langPack["regFailed"]+'</h2>')
											.hide();
											$("message").removeAttr("data-inter");
											$("message").attr( 'data-inter', 'regFailed' );												
											$('#message').fadeIn(1500, function() {
												$('<p id = "success_msg2">'+langPack["regSubFaied"]+'</p>').hide().appendTo("#message").fadeIn(1000);
												$("success_msg2").removeAttr("data-inter");
												$("success_msg2").attr( 'data-inter', 'regSubFaied' );
											});	
										}
									}
								);
					}
				});	
				            
        } 
    });
	
	$('#back_second').click(function(){
				$('.content').animate({
				height: 700
				}, 800, function(){
							//$('#registration_container').height("603px");
							//$('.content').height("653px");
							$('#second_step').slideUp();
							$('#first_step').slideDown();
							$('#progress_bar').css('margin-top','0');
							$('#myform').height("630px");							


					}
			); 

	});
	
	$('#back_third').click(function(){
				$('.content').animate({
				height: 873
				}, 800, function(){
							//$('#registration_container').height("603px");
							//$('.content').height("653px");
							$('#second_step#myform').height("710px");							
							$('#third_step').slideUp();
							$('#second_step').slideDown();

					}
			); 
	});
	
});