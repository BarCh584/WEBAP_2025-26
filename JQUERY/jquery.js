$(start);

function start(){
    $("#clickme").on("click", dothis);
    $("#createlist").on("click", createselectlist);
}
function dothis(){
    let newdiv = $("<div>"); // create a new div element
    newdiv.text("New Div Created!"); // set text of the new div
    $("body").prepend(newdiv); // append the new div to the body element
}

function createselectlist(){
    let carmanufacturers = ["BMW", "Audi", "Mercedes", "Volkswagen", "Porsche"];
    let selectlist = $("<select>"); // create a new select element
    for(let i=0; i<carmanufacturers.length; i++){
        let option = $("<option>"); // create a new option element
        option.attr("value", i); // set value attribute
        option.text(carmanufacturers[i]); // set text of the option
        selectlist.append(option); // append option to select list
    }
    $("body").append(selectlist); // append the select list to the body element
}

/*
1. usage for document on load
$(functionname); -> call functionname on document load
2. usage for document on load
$("CSS_selector") -> call function on elements matching CSS_selector
3. usage for event listener
$("<div>") -> create a new div element from jquery
*/