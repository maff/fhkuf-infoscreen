$(document).ready(function(){

    switchType();
    switchFormat();
    displayResult();
    $('#apiGenerator').show();

    $('#sel_type').change(function(){
        switchType();
    });

    $('#sel_format').change(function(){
        switchFormat();
    });

    $('#apiGeneratorForm select').change(function(){
        displayResult();
    });

    $('#sel_datepicker').DatePicker({
        format:'d.m.Y',
        date: $('#sel_datepicker').val(),
        current: $('#sel_datepicker').val(),
        starts: 1,
        position: 'r',
        onBeforeShow: function(){
            $('#sel_datepicker').DatePickerSetDate($('#sel_datepicker').val(), true);
        },
        onChange: function(formatted, dates){
            $('#sel_datepicker').val(formatted);
            displayResult();
        }
    });

    function displayResult() {
        apiUrl = getAPIUrl();
        $('#apiGeneratorResult').html('<pre><a href="' + apiUrl + '">' + apiUrl + '</a></pre>');
    }

    function switchType() {
        $('#formSections > div').hide();
        $('#form-' + $('#sel_type').val()).show();

        if($('#sel_type').val() == 'list') {
            $('#format_ical').hide();

            if($('#sel_format').val() == 'ical') {
                $('#sel_format').val("json");
            }
        } else {
            $('#format_ical').show();
        }
    }

    function switchFormat() {
        $('#json-categorized').hide();
        if($('#sel_format').val() == 'json') {
            $('#json-categorized').show();
        }
    }

    function getAPIUrl() {
        url = '';

        if($('#sel_type').val() == 'list') {
            if($('#sel_format').val() == 'ical') {
                return false;
            }

            url = url + '/type/list/key/' + $('#sel_key').val();
        } else {
            if($('#sel_datepicker').val() != '') {
                url = url + '/date/' + $('#sel_datepicker').val();
            }

            if($('#sel_range').val() != 'relative') {
                url = url + '/range/' + $('#sel_range').val();
            }

            if($('#sel_course').val() != '') {
                url = url + '/course/' + $('#sel_course').val();
            }

            if($('#sel_lector').val() != '') {
                url = url + '/lector/' + $('#sel_lector').val();
            }

            if($('#sel_room').val() != '') {
                url = url + '/room/' + $('#sel_room').val();
            }

            if($('#sel_format').val() == 'json' && $('#sel_categorized').val() == 'true') {
                url = url + '/categorized/true';
            }

            if($('#sel_strict').val() == 'true') {
                url = url + '/strict/true';
            }
        }

        if($('#sel_debug').val() == 'true') {
            url = url + '/debug/true';
        }

        url = base_url + 'api/' + $('#sel_format').val() + url;
        return url;
    }
});