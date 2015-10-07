function check_empty() {
	if (document.getElementById('name').value == "" || document.getElementById('email').value == "" || document.getElementById('msg').value == "") {
		alert("Fill All Fields !");
	}
	else {
		document.getElementById('form').submit();
		alert("Form Submitted Successfully...");
	}
}

//Function To Display Popup
function P_add_form_show() {
	document.getElementById('frm-jmeno').value = "";
	document.getElementById('frm-nazev').value = "";
	document.getElementById('frm-delka').value = "";
	document.getElementById('frm-send').value = "Přidat"
	document.getElementById('P_add_form').style.display = "block";
}

//Function to Hide Popup
function P_add_form_hide(){
	document.getElementById('P_add_form').style.display = "none";
}


//Function To Display Alert
function P_alter_form_show(params) {
	res=params.split("~");
	
	for (var i = 0; i < res.length; i++) {
		alert(res[i])
	};
	/*
	document.getElementById('P_add_form').style.display = "block";
	document.getElementById('frm-jmeno').value = res[1];
	document.getElementById('frm-nazev').value = res[2];
	document.getElementById('frm-delka').value = res[3];
	document.getElementById('frm-send').value = "Ulozit zmeny"
	document.getElementById('P_add_form').style.display = "block";		
	*/
}

//Function to Hide Alert
function P_add_form_hide(){
	document.getElementById('P_add_form').style.display = "none";
}