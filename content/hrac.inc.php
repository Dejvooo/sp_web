<?php
  // nacist twig - kopie z dokumentace
	require_once 'twig/lib/Twig/Autoloader.php';
	Twig_Autoloader::register();
	// cesta k adresari se sablonama - od index.php
	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader); // takhle je to bez cache
  
  // nacist danou sablonu z adresare
	$template = $twig->loadTemplate('temp_default.html');

  $template_params = array();
  
	// start the application
	$app = new app();
	
	// pripojit k db
	$app->Connect(); 
	
	// pripojeni k db
	$db_connection = $app->GetConnection();
	
	// vytvorit objekt, ktery mi poskytne pristup k DB a vlozit mu connector k DB
	$hrac = new hrac($db_connection);
  $tym = new tym($db_connection);
  $komentar = new komentar($db_connection);
  $uzivatel = new uzivatel($db_connection);
  
  if(isset($_GET["id"]) && is_numeric($_GET["id"])) $id_hrac = $_GET["id"];
  else $id_hrac = 1;
  
  // pridani komentare
  if(isset($_POST['pridat_komentar_go']) && isset($_SESSION[SESSION_NAME]["login"])) {
    if($_POST['text'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste text komentáře.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $addKomentar = $komentar->AddKomentar(htmlspecialchars($_POST['uzivatel'], ENT_QUOTES), htmlspecialchars($_POST['hrac'], ENT_QUOTES), $_POST['text']);
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Váš komentář byl úspěšně přidán.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: index.php?page=hrac&id=".$id_hrac);
    }
  }
  
  // pridani prestupu
  if(isset($_POST['pridat_prestup_go']) && isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
    if($_POST['sezona'] == '' || $_POST['tym'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste obě požadovaná pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
    $disable_prestup = $hrac->DisablePrestup($id_hrac);
    $item = array("hrac" => htmlspecialchars($id_hrac, ENT_QUOTES), "tym" => htmlspecialchars($_POST['tym'], ENT_QUOTES), "sezona" => htmlspecialchars($_POST['sezona'], ENT_QUOTES));
      $addPrestup = $hrac->AddHracToPrestup($item);
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Přestup byl úspěšně přidán.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: index.php?page=hrac&id=".$id_hrac);
    }
  }
  
  // smazani komentare
  if(isset($_GET['kom']) && is_numeric($_GET['kom'])) {
    if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
      $deleteKomentar = $komentar->DeleteKomentar($_GET['kom']);
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Komentář byl úspěšně smazán.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: index.php?page=hrac&id=".$id_hrac);
    }
    else {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Na tuto akci nemáte dostatečné oprávnění!
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time();
      header("Location: index.php?page=hrac&id=".$id_hrac);     
    }
  }
  
  $hrac_selected = $hrac->GetHracByID($id_hrac);
  $tym_hrace = $tym->GetTymByHracId($id_hrac);
  
  $datum_narozeni = strtotime($hrac_selected["datum_narozeni"]);
  $datum_narozeni = date('d.m.Y', $datum_narozeni);
  
  if(isset($_SESSION[SESSION_NAME]["msg"]) && (time() - $_SESSION[SESSION_NAME]["time"] < 1)) {
    $template_params["msg"] = $_SESSION[SESSION_NAME]["msg"];
  }
  
  $template_params["header"] = ""; 
  if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1){
    $template_params["header"] .= "<p class='right'><a href='?page=edit_hrac&amp;id=$id_hrac'>[Editovat hráče]</a></p>";
  }
  
  $template_params["header"] .= "    
    <h1>".$hrac_selected['jmeno']." ".$hrac_selected['prijmeni']." | #".$hrac_selected['cislo']." | ".$hrac_selected['pozice']."</h1>
    <p>Datum narození: ".$datum_narozeni."</p> 
    <p>Výška: ".$hrac_selected['vyska']." m</p> 
    <p>Váha: ".$hrac_selected['vaha']." kg</p> 
    <p>Draft: <a href='?page=tym&amp;id=".$hrac_selected['draft_tym']."'>".$hrac_selected['mesto']." ".$hrac_selected['nazev']."</a> (rok: ".$hrac_selected['draft_rok'].", ".$hrac_selected['draft_kolo'].". kolo, ".$hrac_selected['draft_pozice'].". pozice)</p> 
    <p>Univerzita: ".$hrac_selected['univerzita']."</p> 
    <p><a href='?page=tym&amp;id=".$tym_hrace['tym']."' class='btn btn-primary btn-lg' role='button'>&laquo; Zpět na soupisku týmu</a></p>
  ";

  
  $all_prestup = $hrac->LoadAllPrestup($id_hrac);
  $pocet_prestup = count($all_prestup);

  // TABLE HEADER 
  $template_params["table"] = "<form action='?page=hrac&amp;id=$id_hrac' method='post'>
  <table class='table table-hover table-striped'>
  <caption>Přestupová historie:</caption>
    <thead>
      <tr>
        <th class='col-md-4'>Sezona</th>
        <th>Klub</th>
      </tr>
    </thead>
    <tbody>";
  
  // TABLE DATA
  for($i = 0; $i < $pocet_prestup; $i++) {             
                                  
     $template_params["table"] .= "
      <tr>
        <td>".$all_prestup[$i]['sezona']."</td>
        <td>".$all_prestup[$i]['mesto']." ".$all_prestup[$i]['nazev']."</td>";
      $template_params["table"] .= "</tr>";
  }
  
  $all_tym = $tym->LoadAllTym();
  $pocet_tym = count($all_tym);
         
  if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
    $template_params["table"] .= "
        <tr>
          <td><input type='text' name='sezona'></td>
          <td><select name='tym'>";
                for($i = 0; $i < $pocet_tym; $i++) {
                  $template_params["table"] .= "<option value='".$all_tym[$i]['id_tym']."'>".$all_tym[$i]['mesto']." ".$all_tym[$i]['nazev']."</option>";
                }
    $template_params["table"] .= "</select>
              <input type='submit' name='pridat_prestup_go' class='btn btn-success' value='Odeslat' onclick='javascript:confirmPrestup()'>
          </td>
        </tr>";
  }
  
  // TABLE FOOTER
  $template_params["table"] .= "
    </tbody>
  </table>
  </form>";


  $all_komentar = $komentar->LoadAllKomentarFromHrac($id_hrac);
  $pocet_komentar = count($all_komentar);
  
  // TABLE HEADER 
  $template_params["table"] .= "<table class='table table-hover table-striped'>
  <caption>Komentáře k výkonům hráče ".$hrac_selected["jmeno"]." ".$hrac_selected["prijmeni"].":</caption>
    <thead>
      <tr>
        <th>Datum</th>
        <th>Uživatel</th>
        <th>Text</th>";
        if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
          $template_params["table"] .= "<th></th>";  
        }
      $template_params["table"] .= "</tr>
    </thead>
    <tbody>";
  
  // TABLE DATA
  for($i = 0; $i < $pocet_komentar; $i++) {             
     $datum = strtotime($all_komentar[$i]["datum"]);
     $datum = date('d.m.Y H:i:s', $datum);
                                  
     $template_params["table"] .= "
      <tr>
        <td class='col-md-2'>".$datum."</td>
        <td class='col-md-2'><strong>".$all_komentar[$i]['nick']."</strong></td>  
        <td>".$all_komentar[$i]['text']."</td>";
      if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1){
          $template_params["table"] .= "<td><a href='javascript:confirmDelete(\"?page=hrac&amp;id=$id_hrac&amp;kom=".$all_komentar[$i]['id_komentar']."\")' class='btn btn-danger'>Odstranit</a></td>";  
        }
      $template_params["table"] .= "</tr>";
  }
  
  // TABLE FOOTER
  $template_params["table"] .= "
    </tbody>
  </table>";
  
  if (isset($_SESSION[SESSION_NAME]["login"])){
    $currentUzivatel = $uzivatel->GetUzivatelByNick($_SESSION[SESSION_NAME]["login"]);
    $id_uzivatel = $currentUzivatel["id_uzivatel"];
    $id_hrac = $hrac_selected["id_hrac"];
    
    $template_params["table"] .= "
      <form action='?page=hrac&amp;id=$id_hrac' method='post'>
        <p><textarea name='text' class='ckeditor' > </textarea></p>
        <input type='hidden' name='uzivatel' value='$id_uzivatel'>
        <input type='hidden' name='hrac' value='$id_hrac'>
        <button type='submit' class='btn btn-primary' name='pridat_komentar_go'>Přidat příspěvek</button>
      </form>";
  }
     
  echo $template->render($template_params);

?>