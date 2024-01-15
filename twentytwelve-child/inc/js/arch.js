jQuery('.arch-journal-number').click(function (event) {
    jQuery(this).next('.art_list').toggle();
     return false;
});

jQuery(document).ready(function () {
    let firstIteration = true;
    jQuery('.art_list').each(function () {
        if (!firstIteration)
            jQuery(this).toggle();
        else
            firstIteration = false;
    });
});

// jQuery('a.year_lnk').on('click', function (event) {
//     event.preventDefault();
//     let year_text = jQuery(this).text();
//     jQuery('input[name="arch_year"]').val(year_text);
//     jQuery('input[name="year"]').val(year_text);
//     jQuery('form[name="arch_filter"]').submit();
// }).on('mousedown', function (e) {
//     if(e.which == 2 /* wheel */)
//     {
//         //e.preventDefault();
//         let year_text = jQuery(this).text();
//         jQuery('input[name="arch_year"]').val(year_text);
//         jQuery('input[name="year"]').val(year_text);
//         jQuery('form[name="arch_filter"]').submit();
//     }
// });