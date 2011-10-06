$(document).ready(function() {
    switchType();
    switchFormat();
    displayResult();

    $('#apiGenerator').show();

    $('#sel-type').change(function(){
        switchType();
    });

    $('#sel-format').change(function(){
        switchFormat();
    });

    $('#apiGeneratorForm select').change(function(){
        displayResult();
    });

    function displayResult() {
        apiUrl = getAPIUrl();
        $('#apiGeneratorResult').html('<pre><a href="' + apiUrl + '">' + apiUrl + '</a></pre>');
    }

    function switchType() {
        $('#formSections > div').hide();
        $('#form-' + $('#sel-type').val()).show();

        if($('#sel-type').val() == 'list') {
            $('#format_ical').hide();

            if($('#sel-format').val() == 'ical') {
                $('#sel-format').val("json");
            }
        } else {
            $('#format_ical').show();
        }
    }

    function switchFormat() {
        $('#json-categorized').hide();
        if($('#sel-format').val() == 'json') {
            $('#json-categorized').show();
        }
    }

    function getAPIUrl() {
        url = '';

        if($('#sel-type').val() == 'list') {
            if($('#sel-format').val() == 'ical') {
                return false;
            }

            url = url + '/type/list/key/' + $('#sel-key').val();
        } else {
            if($('#sel-datepicker').val() != '') {
                url = url + '/date/' + $('#sel-datepicker').val();
            }

            if($('#sel-range').val() != 'relative') {
                url = url + '/range/' + $('#sel-range').val();
            }

            if($('#sel-course').val() != '') {
                url = url + '/course/' + $('#sel-course').val();
            }

            if($('#sel-lector').val() != '') {
                url = url + '/lector/' + $('#sel-lector').val();
            }

            if($('#sel-room').val() != '') {
                url = url + '/room/' + $('#sel-room').val();
            }

            if($('#sel-format').val() == 'json' && $('#sel-categorized').val() == 'true') {
                url = url + '/categorized/true';
            }

            if($('#sel-strict').val() == 'false') {
                url = url + '/strict/false';
            }
        }

        if($('#sel-debug').val() == 'true') {
            url = url + '/debug/true';
        }

        url = base_url + 'api/' + $('#sel-format').val() + url;
        return url;
    }
});