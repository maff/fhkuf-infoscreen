$(document).ready(function(){
    var dateval = "";
    var courseval = "";
    var lectorval = "";
    var roomval = "";
    var strictval = false;

    initTableFilterLinks();
    initDayPagerLinks();

    $('#input-submit').click(function(){
        callResults();
        return false;
    });

    $('#input-reset').click(function(){
        if(!$.browser.msie) {
            resetBoxes();
            callResults();
            return false;
        }
    });

    function initTableFilterLinks() {
        if(!$.browser.msie) {
            $('#resultTable tbody td a.filterlink').click(function() {
                resetBoxes();
                $('#sel-' + $(this).attr('data-filterkey')).val($(this).text());
                callResults();
                return false;
            });
        }
    }

    function initDayPagerLinks() {
        if(!$.browser.msie) {
            $('div.pager a').click(function() {
                $('#sel-date').val($(this).attr('data-date'));
                callResults();
                return false;
            });
        }
    }

    function getUrl() {
        url = "";
        if(dateval) url = url + '/date/' + dateval;
        if(courseval) url = url + '/course/' + courseval;
        if(lectorval) url = url + '/lector/' + lectorval;
        if(roomval) url = url + '/room/' + roomval;
        if(strictval == false) url = url + '/strict/false';

        if(url) url = base_url + 'day' + url;
        else url = base_url;

        //url = url + '/ajax/true';

        return url;
    }

    function callResults() {
        $('#loading').fadeIn('fast');
        fetchBoxes();
        $.get(getUrl(), '', function(html){
            $('#results').html(html);
            document.title = $('#pagetitle').text() + " - <?php echo $this->config->frontend->title; ?>";
            tableSort();
            //setupPermalink();
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
        $('#sel-course').val('');
        $('#sel-room').val('');
        $('#sel-lector').val('');
    }

    function fetchBoxes() {
        dateval = $('#sel-date').val();
        courseval = $('#sel-course').val().toLowerCase();
        lectorval = $('#sel-lector').val().toLowerCase();
        roomval = $('#sel-room').val();
        strictval = $('#chb-strict').is(":checked");
    }

    function setupMenu() {
        filters = '';
        if(dateval) filters = filters + 'date/' + dateval + '/';
        if(courseval) filters = filters + 'course/' + courseval + '/';

        if(filters == '') {
            $('#menuWeek a').attr('href', '/week');
        } else {
            $('#menuWeek a').attr('href', '/week/' + filters);
        }
    }
});