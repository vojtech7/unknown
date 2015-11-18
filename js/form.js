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
function P_add_form_show(role) {
	
	if (role=="aranzer") {
		document.getElementById('frm-jmeno').value = "";
		document.getElementById('frm-nazev').value = "";
		document.getElementById('frm-delka').value = "";
	};
	if (role=="manazer") {
		document.getElementById('frm-mesto').value = "";
		document.getElementById('frm-adresa').value = "";
		document.getElementById('frm-datum_a_cas').value = "";
	};
	if (role=="nastrojar") {
		document.getElementById('frm-ttype').value = "";
		document.getElementById('frm-vyrobce').value = "";
		document.getElementById('frm-vyrobni_cislo').value = "";
		document.getElementById('frm-datum_vyroby').value = "";
		document.getElementById('frm-dat_posl_revize').value = "";
		document.getElementById('frm-dat_posl_vymeny').value = "";
		document.getElementById('frm-vymeneno').value = "";
	};
	if (role=="personalista") {
		document.getElementById("frm-rodne_cislo").value="";
		document.getElementById("frm-jmeno").value="";
		document.getElementById("frm-prijmeni").value="";
		document.getElementById('frm-PK_old').value="";
	};
	document.getElementById('frm-edit').value = "add";
	document.getElementById('frm-send').value = "Přidat";
	document.getElementById('P_add_form').style.display = "block";
}

//Function to Hide Popup
function P_add_form_hide(){
	document.getElementById('P_add_form').style.display = "none";
}


//Function To Display Alert
function P_alter_form_show(params, role) {
	res=params.split("~~");
	
	if (role=="aranzer") {
		document.getElementById("frm-id").value = res[0];
		document.getElementById('frm-nazev').value = res[1];
		document.getElementById('frm-delka').value = res[2];
		document.getElementById('frm-jmeno').value = res[3];
	};
	if (role=="manazer") {
		document.getElementById('frm-id').value = res[0];
		document.getElementById('frm-mesto').value = res[2];
		document.getElementById('frm-adresa').value = res[3];
		document.getElementById('frm-datum_a_cas').value = res[1];
		//alert(document.getElementById('frm-datum_a_cas').value);
		//alert(res[1]);
	};
	if (role=="nastrojar") {
		document.getElementById('frm-datum_vyroby').value = res[0];
		document.getElementById('frm-vyrobce').value = res[1];
		document.getElementById('frm-dat_posl_revize').value = res[2];
		document.getElementById('frm-dat_posl_vymeny').value = res[3];
		document.getElementById('frm-vymeneno').value = res[4];
		document.getElementById('frm-vyrobni_cislo').value = res[5];
		document.getElementById('frm-ttype').value = res[6];
	};
	if (role=="personalista") {
		document.getElementById("frm-rodne_cislo").value=res[0];
		document.getElementById("frm-jmeno").value=res[1];
		document.getElementById("frm-prijmeni").value=res[2];
		//ulozeni stareho PK pro pripda, ze bude zmenen
		document.getElementById("frm-PK_old").value=res[0];
	};
	document.getElementById('frm-edit').value = "edit";
	document.getElementById('frm-send').value = "Ulozit zmeny";
	document.getElementById('P_add_form').style.display = "block";		
}

//Function to Hide Alert
function P_add_form_hide(){
	document.getElementById('P_add_form').style.display = "none";
}