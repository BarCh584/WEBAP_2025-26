$(start)

function start(){
    $("#creategunlist").on("click", creategunlist);  
}
function creategunlist(){
    let gunlist = ["AK-47", "M4A1", "Glock-18", "Desert Eagle", "AWP"];
    let gunlistselect = $("<select>");
    for(let i=0; i<gunlist.length; i++){
        let option = $("<option>")
        option.attr("value", gunlist[i]);
        option.text(gunlist[i]);
        gunlistselect.append(option);
    }
    $("body").append(gunlistselect);
}