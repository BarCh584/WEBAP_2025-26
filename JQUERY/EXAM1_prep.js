$(start);

function start() {
    let bttonrows = $("<input>");
    bttonrows.attr("type", "number");
    bttonrows.attr("id", "rows");
    bttonrows.attr("placeholder", "rows");
    let bttoncols = $("<input>");
    bttoncols.attr("type", "number");
    bttoncols.attr("id", "cols");
    bttoncols.attr("placeholder", "cols");
    let btntargetx = $("<input>");
    btntargetx.attr("type", "number");
    btntargetx.attr("id", "targetx");
    btntargetx.attr("placeholder", "target x");
    let btntargety = $("<input>");
    btntargety.attr("type", "number");
    btntargety.attr("id", "targety");
    btntargety.attr("placeholder", "target y");
    let btncreategrid = $("<button>");
    btncreategrid.attr("id", "creategrid");
    btncreategrid.text("Create Grid");
    let btnreset = $("<button>");
    btnreset.attr("id", "reset");
    btnreset.text("Reset");
    $("body").append(bttonrows, bttoncols, btntargetx, btntargety, btncreategrid, btnreset);
    $("#creategrid").on("click", creategrid);
    $(document).on('click', 'td', function () {
        const val = $(this).attr('value');
        alert(val);
    });
    $("#reset").on("click", function () {
        $("table").remove();
    });
}
function creategrid() {
    $("table").remove();
    let rows = $("#rows").val();
    let cols = $("#cols").val();
    let targetx = $("#targetx").val();
    let targety = $("#targety").val();
    let table = $("<table></table>");
    if (targetx >= 1 && targetx <= cols && targety >= 1 && targety <= rows) {
        for (let r = 0; r < rows; r++) {
            let row = $("<tr></tr>");
            for (let c = 0; c < cols; c++) {
                let cell = $("<td>click</td>");
                if (r == targety - 1 && c == targetx - 1) {
                    cell.attr("value", "yes");
                } else {
                    cell.attr("value", "no");
                }
                row.append(cell);
            }
            table.append(row);
        }
        $("body").append(table);
    } else {
        alert("Target coordinates out of bounds");
    }
}