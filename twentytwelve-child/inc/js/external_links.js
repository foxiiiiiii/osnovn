let j = jQuery.noConflict();
j(document).ready(function ($) {
    j('a').each(function () {
        const a = new RegExp('/' + window.location.host + '/');
        if (!a.test(this.href)) {
            j(this).click(function (event) {
                event.preventDefault();
                event.stopPropagation();
                window.open(this.href, '_blank');
            });
        }
    });
});