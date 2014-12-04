<?php
    session_start();

    require_once 'libs/User.class.php';
    require_once 'libs/DB.class.php';
    require_once 'libs/Menu.class.php';
    
    //zkontrolujeme jestli je uzivatel prihlasen do systemu
    if(isset($_SESSION['user'])) $user = unserialize($_SESSION['user']);
    else header('Location: login.php');
    if (!$user->isLogged()) header('Location: login.php'); 
    
    $menu = new Menu($user);
    $db = new Database();
    
    
     if(isset($_POST['submitted']))
    {
          unset($_POST['submitted']);
          $q = "SELECT id_dodavatel FROM Dodavatel WHERE jmeno='".$_POST['dodavatel']."'";
          $r = $db->mysqli->query($q);
          $row = $r->fetch_array(MYSQLI_ASSOC);
          $dodavatel = $row['id_dodavatel'];
          $na_predpis = empty($_POST['na_predpis'])?0:1;
          
          $q = "INSERT INTO Lek (id_dodavatel, cena_koupe, nazev, cena_prodeje, vyrobce, na_predpis, doplatek)"
                  . "VALUES (".$dodavatel.", ".$_POST['kupni_cena'].", '".$db->mysqli->real_escape_string($_POST['jmeno'])."',"
                  . "".$_POST['prodejni_cena'].", '".$_POST['vyrobce']."', ".$na_predpis.", ".$_POST['doplatek'].")";
                   
          $db->mysqli->query($q);
          
          $q = "SELECT MAX(id_lek) as maximum FROM Lek";
          
          $r = $db->mysqli->query($q);
          
          $row = $r->fetch_array(MYSQLI_ASSOC);

          $q = "INSERT INTO Skladuje (id_lek, id_lekarna, mnozstvi) VALUES(".$row['maximum'].", {$user->getLekarna()}, 0);";

          $r = $db->mysqli->query($q);

    }
            
        if(isset($_POST['mazanec']))
        {
            $q = "DELETE FROM Lek WHERE id_lek='".$_POST['mazanec']."'";
            $db->mysqli->query($q);
            $q = "DELETE FROM Skladuje WHERE id_lek='".$_POST['mazanec']."'";
            $db->mysqli->query($q);
            unset($_POST['mazanec']);
        }  
        
        if(isset($_POST['objednavka']))
        {
            $q = "SELECT mnozstvi FROM Skladuje WHERE id_lek='".$_POST['objednavka']."' and id_lekarna='{$user->getLekarna()}'";
            $r = $db->mysqli->query($q);
            $row = $r->fetch_array(MYSQLI_ASSOC);            
            $mnozstvi = $row['mnozstvi'] + $_POST['mnozstvi'];
            $q = "UPDATE Skladuje SET mnozstvi='".$mnozstvi."' WHERE id_lek='".$_POST['objednavka']."' and id_lekarna='{$user->getLekarna()}'";
            $db->mysqli->query($q);
            

            

            $xmla = "<?xml version=\"1.0\"?>";
            $qprodej = "SELECT * FROM Dodavatel WHERE id_dodavatel='".$_POST['dodavatel']."'";
            $r = $db->mysqli->query($qprodej);
            $row2 = $r->fetch_array(MYSQLI_ASSOC);
            
            
            $xmla .= "<objednavka id_lekarna=\"{$user->getLekarna()}\">";
            $xmla .= "<lek id_leku=\"".$_POST['objednavka']."\" mnozstvi=\"".$_POST['mnozstvi']."\"></lek>";
            $xmla .= "</objednavka>";
           
            $fh = fopen($row2['jmeno'].".xml", 'w');
            fwrite($fh, $xmla);
            fclose($fh);

    
        }  
    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">
    <script src="js/libs/jquery-1.9.0/jquery.min.js"></script>
    <script src="js/filter.js"></script>
    <title>IS Lékarna</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/lekarna.css" rel="stylesheet">

  
  </head>

  <body>
      
          <div class='navbar navbar-inverse navbar-fixed-top'>
              <div class='container'>
                  <?php $menu->printMenu(); ?>
              </div>
           </div>

    <div class="container">
      
         <div class="my_body">
            <div>
                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal">Přidat lék</button>
            </div>
         </div>
        
        <div class="my_body">
            
                        <h3>Vyhledávání</h3>
            <div>
          
                <table class="table-bordered pattern">
                    <tr>
                        <th>id léku</th>
                        <th>název</th>
                        <th>výrobce</th>
                        <th>na předpis</th>
                        <th>cena</th>
                        <th>doplatek</th>
                    </tr>
                    
                    <tr>
                        <td> <input type="text" class="form-control filter_id_lek" placeholder="id léku" size="8"></td>
                        <td> <input type="text" class="form-control filter_jmeno" placeholder="název" size="20"></td>
                        <td> <input type="text" class="form-control filter_vyrobce" placeholder="výrobce" size="20"></td> <!-- je treba dodelat napovedu ala google -->
                        <td style=" vertical-align: middle; text-align: center;"> 
                            <select class="filter_predpis form-control">
                                <option value="" selected="selected"></option>
                                <option value="ano" >ano</option>
                                <option value="ne" >ne</option>
                            </select>
                        </td>
                        <td> <input type="text" class="form-control filter_cena" placeholder="cena" size="8"></td>
                        <td> <input type="text" class="form-control filter_doplatek" placeholder="doplatek"  size="8"></td>
                    </tr>
                </table>
            </div>
                        
                        
            <h3 style="margin-top: 30px;">Léky</h3>
            <div>
                <table class="table-hover data td_padding1"> 
                    <tr>      
                        <th>id léku</th>
                        <th>název</th>
                        <th>výrobce</th>
                        <th>na předpis</th>
                        <th>cena</th>
                        <th>doplatek</th>
                        <th>skladem</th>                  
                        <th></th>
                        <th></th>
                    </tr>
                    <!-- vypis dat z db a tvoreni jednotlivych radku -->
                    <?php
                       $query = "select Lek.id_lek, Lek.id_dodavatel, Lek.nazev, Lek.cena_prodeje, Lek.vyrobce, Lek.na_predpis, Lek.doplatek, Skladuje.mnozstvi from Lek, Skladuje where Lek.id_lek=Skladuje.id_lek and Skladuje.id_lekarna={$user->getLekarna()}";
                        
                       $r = $db->mysqli->query($query);
                        
                        while($row = $r->fetch_array(MYSQLI_ASSOC)){
                            echo "<tr>
                                    <td class='filter_id_lek'>{$row['id_lek']}</td>
                                    <td class='filter_jmeno'>{$row['nazev']}</td>
                                    <td class='filter_vyrobce'>{$row['vyrobce']}</td>";
                                    if($row['na_predpis']) echo "<td class='filter_predpis'>ano</td>";
                                    else echo "<td class='filter_predpis'>ne</td>";
                             echo"
                                    <td class='filter_cena'>{$row['cena_prodeje']}</td>
                                    <td class='filter_doplatek'>{$row['doplatek']}</td>
                                    <td class='filter_sklad'>{$row['mnozstvi']}</td>
                                    <form method='post'><td class='btn_pridavani'><input type='number' size='4' min='1' name='mnozstvi'></td>
                                    <td class='btn_objednat'><input type='hidden' value='{$row['id_lek']}' name='objednavka'><input type='hidden' value='{$row['id_dodavatel']}' name='dodavatel'><button class='btn btn-primary' type='submit'>Objednat léky</button></td></form>
                                    <td class='btn_odebrat'><button class='btn btn-danger' value='{$row['id_lek']}' onclick='document.getElementById(\"mazanec\").value = this.value;' data-toggle='modal' data-target='#myModal2' type='button'>Odebrat léky</button></td>
                                  </tr>";                            
                        }          
                    ?>
                </table>
            </div>
            
                        <!-- Modal -->
            <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" role="form" onsubmit="return check_form()" oninput="mandatory()" method="post">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h3 class="modal-title" id="myModalLabel"> Přidání léku</h3>
                    </div>

                    <div class="modal-body">
                                <div class="form-group">
                                  <label for="dodavatel" class="col-sm-3 control-label">Dodavatel*</label>
                                  <div class="col-sm-8">
                                      <select class="form-control" id="dodavatel" name="dodavatel">
                                          <?php
                                          $query = "select jmeno from Dodavatel";
                                          $res = $db->mysqli->query($query);

                                          while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                                              echo "<option>{$row['jmeno']}</option>";       
                                          }
                                          ?>   
                                      </select>                                      
                                  </div>
                                </div>
          
                                <div class="form-group">
                                  <label for="jmeno" class="col-sm-3 control-label">Název léku*</label>
                                  <div class="col-sm-8" id="jmenodiv">
                                      <input type="text" class="form-control" id="jmeno" name="jmeno" placeholder="Název léku" autofocus>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="vyrobce" class="col-sm-3 control-label">Výrobce*</label>
                                  <div class="col-sm-8" id="vyrobcediv">
                                    <input type="text" class="form-control" id="vyrobce" name="vyrobce" placeholder="Výrobce">
                                  </div>
                                </div> 
                        <hr>
                                <div class="form-group">
                                  <label for="kupni_cena" class="col-sm-3 control-label">Kupní cena*</label>
                                  <div class="col-sm-8" id="kupni_cenadiv">
                                      <input type="number" class="form-control" id="kupni_cena" name="kupni_cena"  min='1' placeholder="Kupní cena">
                                  </div>
                                </div> 
                        
                                <div class="form-group">
                                  <label for="prodejni_cena" class="col-sm-3 control-label">Prodejní cena*</label>
                                  <div class="col-sm-8" id="prodejni_cenadiv">
                                    <input type="number" class="form-control" id="prodejni_cena" name="prodejni_cena"  min='1' placeholder="Prodejní cena">
                                  </div>
                                </div>  
                        
                                <div class="form-group">
                                  <label for="doplatek" class="col-sm-3 control-label">Doplatek*</label>
                                  <div class="col-sm-8" id="doplatekdiv">
                                      <input type="number" class="form-control" id="doplatek"  min='0' placeholder="Doplatek" name="doplatek">
                                  </div>
                                </div>   
                                <div class="form-group">
                                    <label for="na_predpis" class="col-sm-3 control-label">Na předpis</label>
                                  <div class="col-sm-8" id="na_predpisdiv">
                                      <input type="checkbox" id="na_predpis" name="na_predpis">
                                  </div>
                                </div>   
                        
                                <div class="form-group">
                                  <label class="col-sm-3 control-label"></label>
                                        <div id="missingdiv" class="col-sm-8">
                                            <label id="missing_data" class="control-label">Položky s hvězdičkou (*) jsou povinné</label> 
                                        </div>
                                  <input type="hidden" name="submitted">
                                </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-default">Vyčistit</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
                      <button class="btn btn-primary" type="submit">Přidat</button>
                    </div>
                  </form>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            
            <div class="modal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        
                            <form method="post">
                                <input type="hidden" name="mazanec" id="mazanec" value="">
                                <label class="modal-title">Opravdu si přejete odstranit tento lék?</label>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                                    <button class="btn btn-primary" type="submit">Odstranit</button>
                                </div>                                    
                            </form>
                        
                    </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            
            
            
        </div>
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->     
    <script src="js/bootstrap.min.js"></script>
    <script>
        
    function check_form()
    {   
        
        var all = true;
        var errmsg = '<span class="glyphicon glyphicon-remove"></span> Nevyplněna všechna povinná pole';

        if($('#jmeno').val() == '')
        {
            all = false;
            document.getElementById('jmenodiv').className = 'col-sm-8 has-error';
        }
        
        if($('#vyrobce').val() == '')
        {
            all = false;
            document.getElementById('vyrobcediv').className = 'col-sm-8 has-error';
        }
        
        if($('#kupni_cena').val() == '')
        {
            all = false;
            document.getElementById('kupni_cenadiv').className = 'col-sm-8 has-error';
        }
        
        if($('#prodejni_cena').val() == '')
        {
            all = false;
            document.getElementById('prodejni_cenadiv').className = 'col-sm-8 has-error';
        }
        
        if($('#doplatek').val() == '')
        {
            all = false;
            document.getElementById('doplatekdiv').className = 'col-sm-8 has-error';
        }       
        
        if(!all)
        {
            document.getElementById('missingdiv').className = 'col-sm-8 has-error';
            document.getElementById('missing_data').innerHTML = errmsg;
            document.getElementById('missing_data').className = 'control-label';
        }
        
        return all;
    }
    
    function mandatory()
    {
            document.getElementById('missingdiv').className = 'col-sm-8';
            document.getElementById('missing_data').innerHTML = 'Položky s hvězdičkou (*) jsou povinné';
            document.getElementById('jmenodiv').className = 'col-sm-8';
            document.getElementById('vyrobcediv').className = 'col-sm-8';
            document.getElementById('kupni_cenadiv').className = 'col-sm-8';
            document.getElementById('prodejni_cenadiv').className = 'col-sm-8';
            document.getElementById('doplatekdiv').className = 'col-sm-8';
    }
    </script>
             
  </body>
</html>