	function init(type) 
	{													// Scheduler initialization
		scheduler.config.xml_date = "%Y-%m-%d %H:%i";	
		//scheduler.config.auto_end_date = true;		
		scheduler.config.details_on_dblclick = true;
		scheduler.config.details_on_create = true;
		scheduler.config.start_on_monday = true;
        scheduler.config.multi_day = true;
		scheduler.init('scheduler_here', null, "week");		
		scheduler.load("../../server_processes/schedule_events_functions/units_events.php?id="+type, "json");
	}
	
	function refreshScheduler(type){					// Refresh sheduler (reload events)
		scheduler.load("../../server_processes/schedule_events_functions/units_events.php?id="+type, "json");	
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
		html("doc").value = ev.staff_id || "";
		
		if(ev.parent === undefined){
			$("#parent").val($("#parent option:first").val());
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