$(start);

function start() {
  $.post(
    "backEnd.php",
    { db: "users" },
    function (response) {
      response.forEach((user) => {
        let option = document.createElement("option");
        option.value = user.userId;
        option.textContent = user.userName;
        $("#Users").append(option);
      });
    },
    "json"
  );
}
$("#sendMessage").on("click", function () {
  let message = $("#NewMessage").val();
  let user = $("#Users").val();
  $.post(
    "backEnd.php",
    { db: "insertmessage", user: user, message: message },
    function (response) {
      console.log(response);
    }
  );
});
$("#AddUser").on("click", function () {
  let value = $("#NewUser").val();
  $.post("backEnd.php", { db: "insertuser", username: value });
  $("#Users").empty();
  start();
});
