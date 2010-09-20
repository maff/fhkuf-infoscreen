$(document).ready(function()
{	
    var dateval = "";
    var classval = "";
    var lectorval = "";
    var roomval = "";
    
    tableSort();
    initTableFilterLinks();
    initDayPagerLinks();
    
   $('#sel_date').DatePicker({
        format:'d.m.Y',
        date: $('#sel_date').val(),
        current: $('#sel_date').val(),
        starts: 1,
        //mode: 'range',
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
    
    
    $('#input_submit').click(function()
    {
        callResults();
        return false;
    });
    
    $('#input_reset').click(function()
    {
        if(!$.browser.msie)
        {
            if(window.location.href == base_url)
            {
                resetBoxes();
                callResults();
                return false;
            }
        }
    });
    
    if($.browser.msie)
    {
        $('div.footermessage').html('Um bestmögliche Funktionalität zu gewährleisten, sollten sie <a href="http://www.mozilla.com/">einen</a> <a href="http://www.opera.com/">anderen</a> Browser als Internet Explorer verwenden.');
    }
    
    function tableSort()
    {    
        $("#infoTable").tablesorter({
            sortList: [[5,0],[0,0]],
            widgets: ['zebra']
        });
    }

    function initTableFilterLinks()
    {         
        if(!$.browser.msie)
        {
            $('#infoTable tbody td a.filterlink').click(function()
            {
                resetBoxes();        
                $('#sel_' + $(this).attr('rel')).val($(this).text());
                callResults();
                return false;        
            });
        }
    }
    
    function initDayPagerLinks()
    {         
        if(!$.browser.msie)
        {
            $('#dayPager a').click(function()
            {
                $('#sel_date').val($(this).attr('rel'));
                callResults();
                return false;        
            });
        }
    }
    
    function resetBoxes()
    {
        $('#sel_class').val("");
        $('#sel_room').val("");
        $('#sel_lector').val("");
    }
    
    function fetchBoxes()
    {
        dateval = $('#sel_date').val();
        classval = $('#sel_class').val().toLowerCase();
        lectorval = $('#sel_lector').val().toLowerCase();
        roomval = $('#sel_room').val();
    }
    
    function getUrl()
    {
        url = "";
        if(dateval) url = url + 'date/' + dateval + '/';
        if(classval) url = url + 'class/' + classval + '/';
        if(lectorval) url = url + 'lector/' + lectorval + '/';
        if(roomval) url = url + 'room/' + roomval + '/';
        
        if(url) url = base_url + 'filter/' + url;
        else url = base_url;
        
        return url;
    }
   
    function callResults()
    {
        $('#loading').fadeIn('fast');
        fetchBoxes();
        $.get(getUrl(), '', function(html){
            $('#main').html(html);
            document.title = $('#pagetitle').text() + " - FH Kufstein Raumbelegungs-Webservice";
            tableSort();
            setupPermalink();
            setupMenu();
            initTableFilterLinks();
            initDayPagerLinks();
            $('#loading').fadeOut('fast');
        });
    }
    
    function setupPermalink()
    {
        url = getUrl();
        if(url != base_url)
            $('#pagetitle').wrapInner(' (<a title="Permanentlink auf diese Filterkriterien" href="' + url + '"></a>)');
    }
    
    function setupMenu()
    {
        filters = '';
        if(dateval) filters = filters + 'date/' + dateval + '/';
        if(classval) filters = filters + 'class/' + classval + '/';
        
        if(filters == '')
            $('#menuWeek a').attr('href', '/week');
        else
            $('#menuWeek a').attr('href', '/week/show/' + filters)
            
        //alert(filters);
        
    }
});