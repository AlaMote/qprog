$("#calc_1_next").on("click", function () {
    $("#calc_1").hide();
    $("#calc_2").show();
});
$("#calc_2_back").on("click", function () {
    $("#calc_1").show();
    $("#calc_2").hide();
});
$("#calc_2_next").on("click", function () {
    $("#calc_2").hide();
    $("#calc_3").show();
});
$("#calc_3_back").on("click", function () {
    $("#calc_2").show();
    $("#calc_3").hide();
});
$("#calc_3_next").on("click", function () {
    $("#calc_3").hide();
    $("#calc_4").show();
});
$("#calc_4_back").on("click", function () {
    $("#calc_3").show();
    $("#calc_4").hide();
});
$("#calc_4_next").on("click", function () {
    $("#calc_4").hide();
    $("#calc_5").show();
});
$("#calc_5_back").on("click", function () {
    $("#calc_4").show();
    $("#calc_5").hide();
});
$("#calc_5_next").on("click", function () {
    $("#calc_5").hide();
    $("#calc_6").show();
});

$(document).ready(function () {
    $("#calc_1").hide();
    $("#calc_2").hide();
    $("#calc_3").hide();
    $("#calc_4").hide();
    $("#calc_5").hide();

    setTimeout(function () {
        $("#loader").hide();
        $("#calc_1").show();
    }, 1000);//Math.floor(Math.random() * 2000) + 2000);
});
$("#calc_back").click(function () {
    history.back();
});
$("#input_back").click(function () {
    history.back();
});
$("#calc").click(function () {
    location.href = "input.php?var_count=2&lim_count="+$("#lim_count").val()+"&func="+$("#func").val();
});