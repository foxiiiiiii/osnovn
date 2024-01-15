

jQuery(document).ready(function($){

    $('.main-slider').slick({
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrows: false,
		autoplay: true,
		autoplaySpeed: 5000,
    });


    $('.actuals').slick({
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
               {
              breakpoint: 992,
              settings: {
                slidesToShow: 2
              }
            },
            {
              breakpoint: 767,
              settings: {
                slidesToShow: 1
              }
            }
        ]
    });
	
	$('.author-slider').slick({
    slidesToShow: 2,
    slidesToScroll: 1,
    infinite: true,
	dots: true,
	arrows: true,
    prevArrow: '<button type="button" class="slick-prev">Previous</button>',
    nextArrow: '<button type="button" class="slick-next">Next</button>',
	speed: 400,
	});



});

document.addEventListener('DOMContentLoaded', function() {
    var commentForm = document.getElementById('commentform');
    var commentField = document.getElementById('comment');

    if (commentForm && commentField) {
        var errorMessage = document.createElement('div');
        errorMessage.style.color = 'red';
        errorMessage.style.marginTop = '10px';
        errorMessage.textContent = 'Пожалуйста, введите комментарий.';

        commentForm.addEventListener('submit', function(event) {
            if (commentField.value.trim() === '') {
                event.preventDefault();
                commentField.style.borderColor = 'red';
                if (!commentField.nextElementSibling) {
                    commentField.parentNode.insertBefore(errorMessage, commentField.nextSibling);
                }
            } else {
                commentField.style.borderColor = '';
                if (commentField.nextElementSibling === errorMessage) {
                    commentField.parentNode.removeChild(errorMessage);
                }
            }
        });
    }
});


document.addEventListener('DOMContentLoaded', function() {
    var badge = document.querySelector('.header_account_wrapper');
    var searchBtn = document.querySelector('.header-search__btn');
    var formBtn = document.querySelector('.header-search__form-btn');
    var menuButtons = document.querySelectorAll('.nav-menu');

    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            badge.style.display = 'none';
        });
    }

    if (formBtn) {
        formBtn.addEventListener('click', function() {
            badge.style.display = '';
        });
    }

    menuButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            badge.style.display = '';
        });
    });
});

jQuery(document).ready(function($) {
    $('#respond form textarea').on('input', function() {
        this.style.height = '154px';
        this.style.height = (this.scrollHeight) + 'px';
    });
});