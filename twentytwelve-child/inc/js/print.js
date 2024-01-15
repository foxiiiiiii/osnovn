function printDiv(divName) {
    let printContents = document.getElementById(divName).cloneNode(true);

    // Удаление ненужных элементов
    let elementsToRemove = printContents.querySelectorAll('.subscribe-me, #print_art, #fav_action, #article_id, #favorites_add_block');
    elementsToRemove.forEach(element => element.remove());

    let iframe = document.createElement('iframe');
    iframe.style.height = '0';
    iframe.style.width = '0';
    iframe.style.border = '0';
    document.body.appendChild(iframe);

    iframe.contentDocument.body.appendChild(printContents);

    iframe.contentWindow.onafterprint = function () {
        document.body.removeChild(iframe);
    };

    iframe.contentWindow.print();
}

document.getElementById('print_art').addEventListener('click', function (event) {
    printDiv('content');
    event.preventDefault();
});
