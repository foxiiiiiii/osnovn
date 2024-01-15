jQuery(document).ready(function ($) {

    console.log('User subscriber info');
    moment.locale('ru');
    let now = moment();
    let end = moment().add(182, 'days');

    let currentRole = $('#role').val();
    let subInfoBlock = $('#subscriber_info');
    if(currentRole !== 'ab_subscriber')
    {
        subInfoBlock.hide();
    }

    $('#role').on('change', function () {
        if(this.value !== 'ab_subscriber')
        {
            subInfoBlock.hide();
        }
        else
        {
            subInfoBlock.show();
        }
    });

    $('#datetimepicker_start').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY'
    });
    //$('#datetimepicker_start').data("DateTimePicker").date(now.format('DD.MM.YYYY'));
    $('#datetimepicker_end').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY'
    });
    //$('#datetimepicker_end').data("DateTimePicker").date(end.format('DD.MM.YYYY'));
});