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
function P_add_form_show(role, tabulka) {
	if (role == "hudebnik") {
		document.getElementById('P_add_form').style.display = "block";
		return;
	};
	if (role=="admin") {
		document.getElementById('frm-login').value = "";
		document.getElementById('frm-heslo').value = "";
		document.getElementById('frm-role').value = "";
		document.getElementById('frm-info').value = "";
	};
	if (role=="aranzer") {
		if (tabulka=='Seznam skladeb') {
			document.getElementById('frm-edit_skladba').value = "add";
			document.getElementById('frm-send_skladba').value = "Přidat";
			document.getElementById('frm-nazev').value = "";
			document.getElementById('frm-delka').value = "";
			document.getElementById('frm-jmeno').value = "";
			document.getElementById('P_add_skladba_form').style.display = "block";
			return;
		};
		if (tabulka == 'Seznam autoru') {
			document.getElementById('frm-edit_autor').value = "add";
			document.getElementById('frm-send_autor').value = "Přidat";
			document.getElementById('frm-jmeno_autora').value = "";
			document.getElementById('frm-zacatek_tvorby').value = 0;
			document.getElementById('frm-konec_tvorby').value = 0;
			document.getElementById('frm-styl').value = "";
			document.getElementById('P_add_autor_form').style.display = "block";
			return;
		};
	};
	if (role=="manazer") {
		document.getElementById('frm-mesto').value = "";
		document.getElementById('frm-adresa').value = "";
		document.getElementById('frm-datum_a_cas').value = "";
	};
	if (role=="nastrojar") {
		if (tabulka == "nastroj") {
			document.getElementById('frm-ttype').value = "";
			document.getElementById('frm-vyrobce').value = "";
			document.getElementById('frm-vyrobni_cislo').value = "";
			document.getElementById('frm-datum_vyroby').value = "";
			document.getElementById('frm-dat_posl_revize').value = "";
			document.getElementById('frm-dat_posl_vymeny').value = "";
			document.getElementById('frm-vymeneno').value = "";
			document.getElementById("frm-edit").value = "add";
			document.getElementById('P_add_nastroj_form').style.display = "block";
			return;
		};
		if (tabulka == "typ") {
			document.getElementById('frm-typ').value = "";
			document.getElementById('P_add_typ_form').style.display = "block";
			return;	
		};
	};
	if (role=="personalista") {
		document.getElementById("frm-rodne_cislo").value="";
		document.getElementById("frm-jmeno").value="";
		document.getElementById("frm-prijmeni").value="";
		document.getElementById('frm-PK_old').value="";
	};
	if (role == "skladba") {
		
		document.getElementById('P_add_form').style.display = "block";
		return;	
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
function P_alter_form_show(params, role, tabulka) {
	res=params.split("~~");
	//spolecne hodnoty
	

	if (role=="aranzer") {
		if (tabulka=='Seznam skladeb') {
			document.getElementById('frm-edit_skladba').value = "edit";
			document.getElementById('frm-send_skladba').value = "Ulozit zmeny";
			document.getElementById("frm-id").value = res[0];
			document.getElementById('frm-nazev').value = res[1];
			document.getElementById('frm-delka').value = res[2];
			document.getElementById('frm-jmeno').value = res[3];
			document.getElementById('P_add_skladba_form').style.display = "block";
			return;
		};
		if (tabulka == 'Seznam autoru') {
			document.getElementById('frm-edit_autor').value = "edit";
			document.getElementById('frm-send_autor').value = "Ulozit zmeny";
			document.getElementById('frm-ID_autora').value = res[0];
			document.getElementById('frm-jmeno_autora').value = res[1];
			document.getElementById('frm-zacatek_tvorby').value = res[2];
			document.getElementById('frm-konec_tvorby').value = res[3];
			document.getElementById('frm-styl').value = res[4];
			document.getElementById('P_add_autor_form').style.display = "block";
			return;
		};
		
	};
	if (role=="manazer") {
		document.getElementById('frm-id').value = res[0];
		document.getElementById('frm-nazev_koncertu').value = res[1];
		document.getElementById('frm-mesto').value = res[3];
		document.getElementById('frm-adresa').value = res[4];
		document.getElementById('frm-datum_a_cas').value = res[2];
		//alert(document.getElementById('frm-datum_a_cas').value);
		//alert(res[1]);
	};
	if (role=="nastrojar") {
		if (tabulka == "Seznam nástrojů") {
			document.getElementById('frm-datum_vyroby').value = res[1];
			document.getElementById('frm-vyrobce').value = res[2];
			document.getElementById('frm-dat_posl_revize').value = res[4];
			document.getElementById('frm-dat_posl_vymeny').value = res[6];
			document.getElementById('frm-vymeneno').value = res[7];
			document.getElementById('frm-vyrobni_cislo').value = res[8];
			document.getElementById('frm-ttype').value = res[9];
			document.getElementById("frm-PK_old").value = res[8];		//vyrobni cislo
			document.getElementById('frm-edit').value = "edit";
			document.getElementById('frm-send').value = "Ulozit zmeny";
			document.getElementById('P_add_nastroj_form').style.display = "block";
			return;
		};
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
function P_add_form_hide(tabulka){
	switch (tabulka) {
		case 'autor':
			document.getElementById('P_add_autor_form').style.display = "none";
			break;
		case 'skladba':
			document.getElementById('P_add_skladba_form').style.display = "none";
			break;
		case 'nastroj':
			document.getElementById('P_add_nastroj_form').style.display = "none";
			break;
		case 'typ':
			document.getElementById('P_add_typ_form').style.display = "none";
			break;
		default:
			document.getElementById('P_add_form').style.display = "none";
			break;
		}
}

//Prepinani tabulek

function switch_table(argument) {
    if (argument=='skladba') {
    	$('.autor').hide();
    	$('.skladba').show();
    };
    	
    if (argument=='autor') {
    	$('.skladba').hide();
    	$('.autor').show();
    };

    if (argument=='nastroj') {
    	$('.typ').hide();
    	$('.nastroj').show();
    };
    	
    if (argument=='typ') {
    	$('.nastroj').hide();
    	$('.typ').show();
    };	

}
