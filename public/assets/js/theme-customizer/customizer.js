if (localStorage.getItem("color"))
    $("#color").attr("href", "../assets/css/" + localStorage.getItem("color") + ".css");
if (localStorage.getItem("dark"))
    $("body").attr("class", "dark-only");

//live customizer js
$(document).ready(function () {

    $(".theme-setting").click(function () {
        $(".customizer-contain").toggleClass("open");
        $(".customizer-links").toggleClass("open");
    });

});
