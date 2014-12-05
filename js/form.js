function check_empty() {
if (document.getElementById('name').value == "" || document.getElementById('email').value == "" || document.getElementById('msg').value == "") {
alert("Fill All Fields !");
} else {
document.getElementById('form').submit();
alert("Form Submitted Successfully...");
}
}
//Function To Display Popup
function P_add_form_show() {
document.getElementById('P_add_form').style.display = "block";
}
//Function to Hide Popup
function P_add_form_hide(){
document.getElementById('P_add_form').style.display = "none";
}