var base_url = "<?php echo $this->config->base_url; ?>/"

$(document).ready(function(){
    tableSort();

    $.datepicker.setDefaults($.datepicker.regional['de']);
    datePicker();

    if($.browser.msie) {
        $('div.footermessage').html('Um bestmögliche Funktionalität zu gewährleisten, sollten sie <a href="http://www.mozilla.com/">einen</a> <a href="http://www.opera.com/">anderen</a> Browser als Internet Explorer verwenden.');
    }
});

function tableSort() {
    $('.dataTable').tablesorter({
        sortList: [[5,0],[0,0]],
        widgets: ['zebra']
    });
}

function datePicker() {
    $('.datePickerWidget').datepicker({
        dateFormat: 'dd.mm.yy'
    });
}