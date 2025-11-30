$(document).ready(function() {
    $("#change-button").click(function() {
        $("#target-text").text("The text and color have been changed.");
        $("#target-text").css("color", "red");
    });
});
