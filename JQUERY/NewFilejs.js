$(start);
function start() {
    $("#calc").on("click", btnclicked);
}
function btnclicked() {
    let number1 = parseInt($("#A").val());
    let number2 = parseInt($("#B").val());
    let sum = number1 + number2;
    $("#Result").html("The sum is: " + sum);
}

