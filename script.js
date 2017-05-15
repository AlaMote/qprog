$("#calc_1_next").on("click", function () {
    $("#calc_1").hide();
    $("#calc_2").show();
});
$("#calc_2_back").on("click", function () {
    $("#calc_1").show();
    $("#calc_2").hide();
});
$(document).ready(function () {
    $("#calc_1").hide();
    $("#calc_2").hide();

    setTimeout(function () {
        $("#loader").hide();
        $("#calc_1").show();
    }, Math.floor(Math.random() * 2000) + 2000);
});
$("#calc_back").click(function () {
    history.back();
});
$("#input_back").click(function () {
    history.back();
});