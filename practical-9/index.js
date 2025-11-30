$(document).ready(function() {
    $("#myButton").click(function() {
        $("#message").text("✨ Success! The button was clicked.");
        $(this).css("background-color", "green"); 
        $("#message").fadeOut(1500); 
        console.log("Button clicked!");
    });
});
