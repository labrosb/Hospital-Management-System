	function init(id) 
	{														// Scheduler initialization
		scheduler.config.xml_date = "%Y-%m-%d %H:%i";	
		//scheduler.config.auto_end_date = true;	
		scheduler.config.readonly_form = true;		
		scheduler.config.details_on_dblclick = true;
		scheduler.config.details_on_create = false;
		scheduler.config.start_on_monday = true;
        //scheduler.config.time_step = 30;
        scheduler.config.multi_day = true;
		scheduler.init('scheduler_here', null, "week");		
		scheduler.load("../../server_processes/schedule_events_functions/on_duty_shift_events.php?id="+id, "json");
		scheduler.config.dblclick_create = false;
	}

	var html = function(id) { return document.getElementById(id); };
		
	scheduler.showLightbox = function(id) 
	{												// Inserts the details													
		var ev = scheduler.getEvent(id);			// in the existing events
		scheduler.startLightbox(id, html("my_form"));
		html("parent").value = ev.parent || "";
		html("more").value = ev.more || "";
		html("startDate").value =  ev.start_date;
		html("endDate").value =  ev.end_date;
		make_list('child', html("parent").value);
		html("child").value = ev.unit_id;
			
		var startDate = structStartDate(html("startDate").value);
		
		$('#start_time').val(startDate['startHourMinutes']);
		$('#start_day').val(startDate['startDay']);
		$('#start_month').val(startDate['startMonth']);
		$('#start_year').val(startDate['startYear']);		
		
		var endDate = structEndDate(html("startDate").value );	
		$('#end_time').val(endDate['endHourMinutes']);
		$('#end_day').val(endDate['endDay']);
		$('#end_month').val(endDate['endMonth']);
		$('#end_year').val(endDate['endYear']);	
	};
	
	struct_lists("basic_lists");		// Creates the list choices (general_calendar_actions.js)

	$('#child').change(function(){ 
		struct_lists("child2");
	});
	
	$('#parent').attr('disabled', true);		//Make choices unchangeable
	$('#child').attr('disabled', true);	
	$('#child2').attr('disabled', true);	
	$('#more').attr('disabled', true);	
	
	$('#start_time').attr('disabled', true);	
	$('#start_day').attr('disabled', true);	
	$('#start_month').attr('disabled', true);	
	$('#start_year').attr('disabled', true);
	
	$('#end_time').attr('disabled', true);	
	$('#end_day').attr('disabled', true);	
	$('#end_month').attr('disabled', true);	
	$('#end_year').attr('disabled', true);	
	
	$('#patient').attr('disabled', true);	