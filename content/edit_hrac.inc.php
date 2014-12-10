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
  
  if(!isset($_SESSION[SESSION_NAME]["login"]) || $_SESSION[SESSION_NAME]["prava"] != 1) {
    $_SESSION[SESSION_NAME]["msg"] = "
      <div class='alert alert-danger' role='alert'>
        <strong>Chyba!</strong> Pro vstup na tuto stránku nemáte dostatečné oprávnění. Byl jste přesměrován na úvodní stránku.
      </div>";
    $_SESSION[SESSION_NAME]["time"] = time();
    header("Location: index.php"); 
  }
	// start the application
	$app = new app();
	
	// pripojit k db
	$app->Connect(); 
	
	// pripojeni k db
	$db_connection = $app->GetConnection();
	
	// vytvorit objekt, ktery mi poskytne pristup k DB a vlozit mu connector k DB
	$hrac = new hrac($db_connection);

  
  if(isset($_GET["id"]) && is_numeric($_GET["id"])) $id_hrac = $_GET["id"];
  $hrac_selected = $hrac->GetHracByID($id_hrac);
  
  // editace hrace
  if(isset($_POST['edit_go'])) {
    if($_POST['jmeno'] == '' || $_POST['prijmeni'] == '' || $_POST['cislo'] == '' || $_POST['pozice'] == '' || $_POST['datum_narozeni'] == '' || $_POST['vyska'] == '' || $_POST['vaha'] == '' || $_POST['draft_rok'] == '' || $_POST['draft_kolo'] == '' || $_POST['draft_pozice'] == '' || $_POST['univerzita'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste všechna potřebná pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $item = array("jmeno" => htmlspecialchars($_POST['jmeno'], ENT_QUOTES), "prijmeni" => htmlspecialchars($_POST['prijmeni'], ENT_QUOTES), "cislo" => htmlspecialchars($_POST['cislo'], ENT_QUOTES), "pozice" => htmlspecialchars($_POST['pozice'], ENT_QUOTES), "datum_narozeni" => htmlspecialchars($_POST['datum_narozeni'], ENT_QUOTES), "vyska" => htmlspecialchars($_POST['vyska'], ENT_QUOTES), "vaha" => htmlspecialchars($_POST['vaha'], ENT_QUOTES), "draft_rok" => htmlspecialchars($_POST['draft_rok'], ENT_QUOTES), "draft_kolo" => htmlspecialchars($_POST['draft_kolo'], ENT_QUOTES), "draft_pozice" => htmlspecialchars($_POST['draft_pozice'], ENT_QUOTES), "univerzita" => htmlspecialchars($_POST['univerzita'], ENT_QUOTES));
      $editHrac = $hrac->UpdateHrac($item, $id_hrac);
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Editace hrace ".$hrac_selected["jmeno"]." ".$hrac_selected["prijmeni"]." proběhla úspěšně.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: ?page=hrac&id=".$id_hrac);
    }
  }
  
  
  $template_params["header"] = "<h1>Editace hráče ".$hrac_selected["jmeno"]." ".$hrac_selected["prijmeni"];
  
  // TABLE HEADER 
  $template_params["table"] = "<table class='table table-hover'>";
  
  // TABLE DATA
                                  
  $template_params["table"] .= "
    <form action='' method='post'>
      <tr><td>Jméno:</td> <td><input type='text' name='jmeno' value='".@htmlspecialchars($hrac_selected['jmeno'], ENT_QUOTES)."'></td></tr>
      <tr><td>Příjmení:</td> <td><input type='text' name='prijmeni' value='".@htmlspecialchars($hrac_selected['prijmeni'], ENT_QUOTES)."'></td></tr>
      <tr><td>Číslo:</td> <td><input type='text' name='cislo' value='".@htmlspecialchars($hrac_selected['cislo'], ENT_QUOTES)."'></td></tr>
      <tr><td>Pozice:</td> <td><input type='text' name='pozice' value='".@htmlspecialchars($hrac_selected['pozice'], ENT_QUOTES)."'></td></tr>
      <tr><td>Datum narození:</td> <td><input type='text' name='datum_narozeni'value='".@htmlspecialchars($hrac_selected['datum_narozeni'], ENT_QUOTES)."'></td></tr>
      <tr><td>Výška:</td> <td><input type='text' name='vyska' value='".@htmlspecialchars($hrac_selected['vyska'], ENT_QUOTES)."'></td></tr>
      <tr><td>Váha:</td> <td><input type='text' name='vaha' value='".@htmlspecialchars($hrac_selected['vaha'], ENT_QUOTES)."'></td></tr>
      <tr><td>Draft rok:</td> <td><input type='text' name='draft_rok' value='".@htmlspecialchars($hrac_selected['draft_rok'], ENT_QUOTES)."'></td></tr>
      <tr><td>Draft kolo:</td> <td><input type='text' name='draft_kolo' value='".@htmlspecialchars($hrac_selected['draft_kolo'], ENT_QUOTES)."'></td></tr>
      <tr><td>Draft pozice:</td> <td><input type='text' name='draft_pozice' value='".@htmlspecialchars($hrac_selected['draft_pozice'], ENT_QUOTES)."'></td></tr>
      <tr><td>Univerzita:</td> <td><input type='text' name='univerzita' value='".@htmlspecialchars($hrac_selected['univerzita'], ENT_QUOTES)."'></td></tr>
      <tr><td><button type='submit' name='edit_go' class='btn btn-primary'>Uložit</button></td><td></td></tr>
    </form>";
  
  // TABLE FOOTER
  $template_params["table"] .= "</table>";
     
  echo $template->render($template_params);

?>