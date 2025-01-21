AOS.init();


var navbar = $('#navbar');
$(window).scroll(function() {
    if ($(this).scrollTop() > 0) {
        navbar.addClass('shadow');
    } else {
        navbar.removeClass('shadow');
    }
});