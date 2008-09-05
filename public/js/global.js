$(document).ready(function() 
    {
		/*$("#sel_date").datepicker();*/	
	
        $("#infoTable").tablesorter({
			sortList: [[4,0],[0,0]],
			widgets: ['zebra']
		});
		
		//$('#dataTable').flexigrid();
		
    } 
);