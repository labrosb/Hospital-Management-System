	function init(id) 
	{													// Scheduler initialization
		scheduler.config.xml_date = "%Y-%m-%d %H:%i";	
		//scheduler.config.auto_end_date = true;		
		scheduler.config.details_on_dblclick = true;
		scheduler.config.details_on_create = true;
		scheduler.config.start_on_monday = true;
        scheduler.config.multi_day = true;
		scheduler.init('scheduler_here', null, "week");		
		scheduler.load("../../server_processes/schedule_events_functions/doc_events.php?id="+id, "json");
	}
									
	function refreshScheduler(arg){						// Refresh sheduler (reload events)
		scheduler.load("../../server_processes/schedule_events_functions/doc_events.php?id="+arg, "json");	
	}
	
	setSave();
	
	scheduler.attachEvent("onClick",setUpdate);
	scheduler.attachEvent("onDblClick",setUpdate);

	var html = function(id) { return document.getElementById(id); };
		
	scheduler.showLightbox = function(id) 
	{													// Inserts the details
		var ev = scheduler.getEvent(id);				// in the existing events
		scheduler.startLightbox(id, html("my_form"));
		html("parent").value = ev.parent || "";
		html("more").value = ev.more || "";
		html("startDate").value =  ev.start_date;
		html("endDate").value =  ev.end_date;
		make_list('child', html("parent").value);
	
		if(ev.parent === undefined){
			$("#parent").val($("#parent option:first").val());
		}
		
		if (ev.parent == 4){						// If examination
			html("child").value = ev.exam_type_id;
			html("patient_id").value = ev.patient_id;
			html("ward").value = ev.number+ " / " + ev.unit_name + " / " + ev.building_name || "";
		}
		
		if (ev.parent == 5 || ev.parent == 6){		// If Work shift or call duty
			html("child").value = ev.unit_id;
		}		
		
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
	
	struct_lists("basic_lists");	// Creates the list choices (general_calendar_actions.js)