$(document).ready(function() {
    $(window).resize(function() {
        $("#start").css({
            position:"absolute",
            left: ($(window).width() - $("#start").outerWidth()) / 2,
            top: ($(window).height() - $("#start").outerHeight()) / 2
        });
    });

    //initial centering
    $(window).resize();

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/resume",
        success: function(response) {
            var content;
            if (response != null) {
                if (response.success == 1) {
                    var color = 255;
                    $.each(response.data, function(key, element) {
                        $("#content").append(
                            $("<span>").attr("class", "section").attr("id", key).append(
                                $("<div>").attr("class", "tab").attr("style", "background-color:rgb(" + color + ", 0, 0)").append(
                                    $("<span>").append(response.data[key])
                                ).append(
                                    $("<div>").attr("class", "response").attr("id", key + "-response")
                                )
                            )
                        );
                        
                        color = color - 20;
                    });
                } else if (response.success == 0) {
                    $("#message").html(response.message);
                }
            } else {
                $("#message").html("There was a problem with service. Please try reloading the page.");
            }
        }
    });

    $("#start").click(function() {
        $(this).hide();
        $("#content").slideDown(2000);
    });
     
    $(".section").live("click", function() {
        var section  = $(this).attr("id");
        var responseId = "#" + section + "-response";
        
        if ($(responseId).html() == "") {
            $.ajax({
                 type: "GET",
                 dataType: "json",
                 url: "/resume/section/id/" + section,
                 success: function(response) {
                    var content;
                    if (response != null) {
                        if (response.success == 1) {
                            append(section, response.data);
                            //when element is 'display:hidden' it is not in the DOM, therefore it doesn't have height
                            //which needs to be recalculated after injecting content
                            $(".response").not(responseId).slideUp(500);
                            $(responseId).show().resize().hide().slideDown(500);
                        } else if (response.success == 0) {
                            $("#message").html(response.message);
                        }
                    } else {
                        $("#message").html("There was a problem with service. Please try reloading the page.");
                    }
                }
            });
        } else {        
            $(".response").not(responseId).slideUp(500);
            $(responseId).slideToggle(500);
        }
    });
});

function append(section, element) {
    if (typeof element == "object") {
        $.each(element, function(k, e) {
            append(section, e);
        });
    } else {
        $("#" + section + "-response").append(element).append("<br />");
    }
}