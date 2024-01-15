let j = jQuery.noConflict();



j('#login_view').on('show.bs.modal', function (event) {

    let button = j(event.relatedTarget); // Button that triggered the modal

    let recipient = button.data('whatever'); // Extract info from data-* attributes

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).

    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    let modal = j(this);

    modal.find('.modal-title').text('Авторизуйтесь');

});