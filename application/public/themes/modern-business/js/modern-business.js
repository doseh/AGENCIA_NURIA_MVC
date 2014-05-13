// Activates the Carousel
/*$('.carousel').carousel({ interval: 5000 })*/

// Activates Tooltips for Social Links
/*$('.tooltip-social').tooltip({ selector: "a[data-toggle=tooltip]" })*/

$(document).ready(function() {
    var dropdown = 0;
    $(".dropdown").click(function(){
        if ( dropdown == 1 ){    
            $(".dropdown-menu").slideUp();
            dropdown = 0;
        }
        else {
            $(".dropdown-menu").slideDown();
            dropdown = 1;
        }
    });
});