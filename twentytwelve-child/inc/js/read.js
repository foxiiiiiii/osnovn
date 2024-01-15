jQuery('.section-item').click(function (event) {
    jQuery(this).next('.subsection-item').toggle();
    return false;
});

jQuery("input[type=radio][name='entitytype']").change(function (event) {
    jQuery("input[name='typechanged']").val(1);
    jQuery("form.chitalka-search").submit()
    return false;
});


jQuery(document).ready(function () {
    let firstIteration = true;
    jQuery('.subsection-item').each(function () {
        jQuery(this).toggle();
    });
});

jQuery(document).ready(function () {
    let selectCtrl = jQuery('select[name="year"]');
    selectCtrl.on('change', function () {
        let selectedYear = selectCtrl.val();
        if(selectedYear === 'Все')
            selectedYear = 'all';

        jQuery.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            data: {
                year: selectedYear,
                action: 'get_magazines'
            },
            success: function (response){
                console.log(response);
                let selectMag = jQuery('select[name="mag_num"]');
                selectMag.find('option').remove();
                selectMag.append("<option value=\"0\">Все</option>");

                jQuery.each(response, function (){
                    let title = this.Title.replace(/&#8212;/g, ' -- ');
                    selectMag.append(jQuery("<option />").val(this.Id).text(title));
                })
            },
            error: function (response){
              console.log(response);
            },
            dataType: 'json'
        });
    });

});