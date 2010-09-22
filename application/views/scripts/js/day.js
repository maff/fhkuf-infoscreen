$(document).ready(function(){
    var dateval = "";
    var classval = "";
    var lectorval = "";
    var roomval = "";

    tableSort();
    initTableFilterLinks();
    initDayPagerLinks();

    $('#input_submit').click(function(){
        callResults();
        return false;
    });

    $('#input_reset').click(function(){
        if(!$.browser.msie) {
            resetBoxes();
            callResults();
            return false;
        }
    });

    function tableSort() {
        $("#infoTable").tablesorter({
            sortList: [[5,0],[0,0]],
            widgets: ['zebra']
        });
    }

    function initTableFilterLinks() {
        if(!$.browser.msie) {
            $('#infoTable tbody td a.filterlink').click(function() {
                resetBoxes();
                $('#sel_' + $(this).attr('rel')).val($(this).text());
                callResults();
                return false;
            });
        }
    }

    function initDayPagerLinks() {
        if(!$.browser.msie) {
            $('#dayPager a').click(function() {
                $('#sel_date').val($(this).attr('rel'));
                callResults();
                return false;
            });
        }
    }

    function getUrl() {
        url = "";
        if(dateval) url = url + 'date/' + dateval + '/';
        if(classval) url = url + 'class/' + classval + '/';
        if(lectorval) url = url + 'lector/' + lectorval + '/';
        if(roomval) url = url + 'room/' + roomval + '/';

        if(url) url = base_url + 'day/' + url;
        else url = base_url;

        return url;
    }

    function callResults() {
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

    function setupPermalink() {
        url = getUrl();
        if(url != base_url) {
            $('#pagetitle').wrapInner(' (<a title="Permanentlink auf diese Filterkriterien" href="' + url + '"></a>)');
        }
    }


    function resetBoxes() {
        $('#sel_class').val("");
        $('#sel_room').val("");
        $('#sel_lector').val("");
    }

    function fetchBoxes() {
        dateval = $('#sel_date').val();
        classval = $('#sel_class').val().toLowerCase();
        lectorval = $('#sel_lector').val().toLowerCase();
        roomval = $('#sel_room').val();
    }

    function setupMenu() {
        filters = '';
        if(dateval) filters = filters + 'date/' + dateval + '/';
        if(classval) filters = filters + 'class/' + classval + '/';

        if(filters == '') {
            $('#menuWeek a').attr('href', '/week');
        } else {
            $('#menuWeek a').attr('href', '/week/show/' + filters);
        }
    }
});