import {FavoriteService} from "./libs/favorite_lib.js";

let $j = jQuery.noConflict();



$j("#fav_action").on('click', function (e) {

    e.preventDefault();



    let src = $j("#fav_img").attr('src').split('/').pop();

    src = (src === 'fav_empty.png') ? 'fav.png' : 'fav_empty.png';

    $j("#fav_img").attr('src', window.location.origin + '/wp-content/themes/twentytwelve-child/images/' + src);



    let fs = new FavoriteService();

    fs.add_to_favorite($j("#fav_action").data('art'));

});



// article.js
function printDiv(divName) {
    let printContents = document.getElementById(divName).innerHTML;

    // Создаем временный iframe для печати
    let iframe = document.createElement('iframe');
    iframe.style.height = '0';
    iframe.style.width = '0';
    iframe.style.border = '0';
    document.body.appendChild(iframe);

    // Загружаем содержимое iframe
    iframe.contentDocument.body.innerHTML = printContents;

    // Слушаем событие завершения печати
    iframe.contentWindow.onafterprint = function () {
        // Восстанавливаем исходное содержимое
        document.body.removeChild(iframe);
    };

    // Печать содержимого iframe
    iframe.contentWindow.print();
}

// Обработчик события для кнопки
document.getElementById('print_art').addEventListener('click', function (event) {
    printDiv('content');
    event.preventDefault();
});

