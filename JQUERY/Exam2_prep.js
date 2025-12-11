$(start);

let options = ["Carrots", "Potatoes", "Rice", "Milk"];
let ingredientsofrecipe = [];
function start() {
    let recipenameinput = $("<input>");
    recipenameinput.attr("id","recipename");
    recipenameinput.attr("type","text")
    recipenameinput.attr("placeholder", "Recipe name");
    let createrecipe = $("<button>");
    createrecipe.attr("id", "createrecipe");
    createrecipe.text("Create recipe");
    let ingredientsselect = $("<select>");
    ingredientsselect.attr("id", "selectingredient");
    let ingredientquantity = $("<input>");
    ingredientquantity.attr("type", "number");
    ingredientquantity.attr("id", "ingredientquantity");
    ingredientquantity.attr("placeholder", "Quantity");
    let addingredienttorecipe = $("<button>");
    addingredienttorecipe.attr("id", "addingredient");
    addingredienttorecipe.text("Add ingredient");
    $("body").append(
        recipenameinput,
        createrecipe,
        ingredientsselect,
        ingredientquantity,
        addingredienttorecipe
    );
    for(let i=0; i<options.length; i++) {
        let option = $("<option>");
        option.attr("id", i);
        option.text(options[i]);
        ingredientsselect.append(option);
    }
    $("#createrecipe").on("click", createrecipe());

    function createrecipe() {
        let lastrecipecreated = recipenameinput.val();
    }
    $("")
    $("#recipenameinput").on("click", addingredient);

    function addingredient() {
        let ingredientsel = ingredientsselect.val();
        let quantitysel = ingredientquantity.val();
        ingredientsofrecipe.push(ingredientsel, quantitysel);
        displayrecipe();
    }
    function displayrecipe() {
        let text = "Recipe name: " + lastrecipecreated + "\n";
        let ingredientstxt = "Ingredients: "; 
        for(let i=0; i < ingredientsofrecipe.length; i++) {
            ingredients.text = "";
        }
        $("body").append(
            text,
            ingredientstxt,
            ingredients
        )

    }
}
