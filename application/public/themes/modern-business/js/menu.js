$(document).ready(function() {    
    $("#reg").click(function() {
        if ( ($(".regindex").css("display")) == "none") {
            $(".regindex").fadeIn();

            if ( ($(".logindex").css("display")) != "none")
                $(".logindex").fadeOut();
        }
        else
            $(".regindex").fadeOut();
    });

    $(".regindex input[type='button']").click(function(){
        $(".regindex").fadeOut();
    });

    $("#log").click(function() {
        if ( ($(".logindex").css("display")) == "none") {
            $(".logindex").fadeIn();
            if ( ($(".regindex").css("display")) != "none")
                $(".regindex").fadeOut();
        }
        else
            $(".logindex").fadeOut();
    });

    $(".logindex input[type='button']").click(function(){
        $(".logindex").fadeOut();
    });
});