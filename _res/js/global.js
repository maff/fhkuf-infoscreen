$(document).ready(function()
{	
    tableSort();

    function tableSort()
    {    
        $("#infoTable").tablesorter({
            sortList: [[4,0],[0,0]],
            widgets: ['zebra']
        });
    }
    
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

    $('#input_submit').click(function() {
        dateval = $('#sel_date').val();
        classval = $('#sel_class').val();
        lectorval = $('#sel_lector').val();
        roomval = $('#sel_room').val();
        
        url = base_url + '/filter/';
        
        if(dateval) url = url + 'date/' + dateval + '/';
        if(classval) url = url + 'class/' + classval + '/';
        if(lectorval) url = url + 'lector/' + lectorval + '/';
        if(roomval) url = url + 'room/' + roomval + '/';
        
        url = url + 'json/true/';
        
        $.getJSON(url, '', function(json)
        {
            document.title = json.title;
            $('#pagetitle').text(json.title);
            $('#error').text(json.error);
            
            if(json.results == true)
            {
                $('#infoTable tbody tr').remove();
                
                html = "";
                $.each(json.data,function(i,appointment) {
                    html += "<tr>";
                    
                    $.each(["class","description","lector","room","startTime","endTime","info"],function(j,key)
                    {
                        value = "";
                        htmlclass = key;
                        
                        if(key == "startTime" || key == "endTime")
                            htmlclass += " time";
                        
                        if(appointment[key] != "" && appointment[key] != null && appointment[key] != undefined)
                            value = appointment[key];
                    
                        html += "<td class=\"" + key + "\">" + value + "</td>";
                    });

                    html += "</tr>";
                });
                
                $('#infoTable tbody').html(html);
                
                $('#error').hide();
                $('#infoTable').show();
                
                $("#infoTable").tablesorter({
                    sortList: [[4,0],[0,0]],
                    widgets: ['zebra']
                });
            }
            else
            {
                $('#error').show();
                $('#infoTable').hide();
            }
        });
        return false;
    });
    
    function callResults()
    {

    }
    
});