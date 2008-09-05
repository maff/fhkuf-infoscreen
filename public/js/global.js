$(document).ready(function() {
	
        $("#infoTable").tablesorter({
			sortList: [[4,0],[0,0]],
			widgets: ['zebra']
		});
		
		$('#sel_date').DatePicker({
			format:'d.m.Y',
			date: $('#sel_date').val(),
			current: $('#sel_date').val(),
			starts: 1,
			position: 'r',
			onBeforeShow: function(){
				$('#sel_date').DatePickerSetDate($('#sel_date').val(), true);
			},
			onChange: function(formated, dates){
				$('#sel_date').val(formated);
			},
            locale: {
                days: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
                daysShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                daysMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                months: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
                monthsShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
                weekMin: 'KW'
            }            
		});		
});