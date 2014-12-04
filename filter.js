/*******************************************************************
 *                                                                 *
 * skript pr filtrovani, dat v tabulce na zaklade vzorku v pattern *
 * table                                                           *
 *                                                                 *
 *                     author: David Buchta                        *
 *                    Last change:  3.12.2014                      *
 *                                                                 *
 *                            v 1.1.5                              *
 ******************************************************************/


 $(document).ready(function() {
     $(".pattern input").keyup(function(){ myFilter(); });
     $(".pattern select").change(function(){ myFilter(); });
     
     $.expr[':'].icontains = function(obj, index, meta, stack){
        return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
     };
     
    function myFilter(){
          var  classes="";
          var id ="";
          var exp ="";
         
         //projdeme si tabulku a odkryjeme skryte radky 
         $(".hide").each(function(){
             $(this).removeClass("hide");
         });
         
         links = $(".data").find("tr");
         
         //projdem si vsechny inputy vezmeme jejich hodnoty a porovname s danym sloupcem
         $(".pattern input").each(function(){
             exp = $(this).val();
             
			 //pokud byl zadan filtr
			 if(exp !== "")
			 {
				//zjistime si nazev fitlru
				classes=$(this).attr("class"); //.split(" ");
				if(classes !== "" && classes !== undefined){
					id=classes.substr(classes.indexOf("filter_"));             
					if (id.indexOf(" ") >= 0) id = id.substr(0,id.indexOf(" "));
             
					//filtrace
					if(id !=="" ){
                    links.find('td.'+id+':not(:icontains('+exp+'))').parent().addClass("hide");
					}
				}
			}
         });
         //projdeme si i selecty
         $(".pattern select option:selected").each(function(){
            exp= $(this).text();
            
            //zjistime si nazev fitlru
           
             classes=$(this).parent().attr("class"); //.split(" ");
             if(classes !== "" && classes !== undefined){
                id=classes.substr(classes.indexOf("filter_"));             
                if (id.indexOf(" ") >= 0) id = id.substr(0,id.indexOf(" "));
                
                //filtrace
                if(exp !== "" && id !==""){
                    links.find('td.'+id+':not(:icontains('+exp+'))').parent().addClass("hide");
                }
             }
            
         });
     };
 
 });


