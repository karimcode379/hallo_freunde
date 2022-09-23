$(document).ready(function () {

    //on click signup, hide login and show registration form
    $("#signup").click(function () {
        $("#first-form").slideUp("slow", function () {
            $("#second-form").slideDown("slow");
        });
    });

    //on click login, hide registration and show login form
    $("#signin").click(function () {
        $("#second-form").slideUp("slow", function () {
            $("#first-form").slideDown("slow");
        });
    });
});