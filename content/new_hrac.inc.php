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
  $hrac = new hrac($db_connection);
  $tym = new tym($db_connection);
  
  if(isset($_GET["id"]) && is_numeric($_GET["id"])) $id_tym = $_GET["id"];
  $tym_selected = $tym->GetTymByID($id_tym);
  
  // pridani noveho hrace
  if(isset($_POST['pridat_go'])) {
    if($_POST['jmeno'] == '' || $_POST['prijmeni'] == '' || $_POST['cislo'] == '' || $_POST['pozice'] == '' || $_POST['datum_narozeni'] == '' || $_POST['vyska'] == '' || $_POST['vaha'] == '' || $_POST['draft_rok'] == '' || $_POST['draft_kolo'] == '' || $_POST['draft_pozice'] == '' || $_POST['univerzita'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste všechna pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $hrac_pole = array("jmeno" => htmlspecialchars($_POST['jmeno'], ENT_QUOTES), "prijmeni" => htmlspecialchars($_POST['prijmeni'], ENT_QUOTES), "cislo" => htmlspecialchars($_POST['cislo'], ENT_QUOTES), "pozice" => htmlspecialchars($_POST['pozice'], ENT_QUOTES), "datum_narozeni" => htmlspecialchars($_POST['datum_narozeni'], ENT_QUOTES), "vyska" => htmlspecialchars($_POST['vyska'], ENT_QUOTES), "vaha" => htmlspecialchars($_POST['vaha'], ENT_QUOTES), "draft_tym" => $tym_selected['id_tym'], "draft_rok" => htmlspecialchars($_POST['draft_rok'], ENT_QUOTES), "draft_kolo" => htmlspecialchars($_POST['draft_kolo'], ENT_QUOTES), "draft_pozice" => htmlspecialchars($_POST['draft_pozice'], ENT_QUOTES), "univerzita" => htmlspecialchars($_POST['univerzita'], ENT_QUOTES));  
      $addHrac = $hrac->AddHrac($hrac_pole);
      
      $hracAll = $hrac->GetHracByDraft(htmlspecialchars($_POST['draft_rok'], ENT_QUOTES), htmlspecialchars($_POST['draft_kolo'], ENT_QUOTES), htmlspecialchars($_POST['draft_pozice'], ENT_QUOTES));
      $idHrac = htmlspecialchars($hracAll['id_hrac'], ENT_QUOTES);
      $rok = htmlspecialchars($_POST['draft_rok'], ENT_QUOTES);
      $sezonaOd = $rok;
      $sezonaDo = $rok+1;
      $sezona = $sezonaOd."/".$sezonaDo;
      
      $prestup_pole = array("hrac" => $idHrac, "tym" => htmlspecialchars($tym_selected['id_tym'], ENT_QUOTES), "sezona" => $sezona);
      $addPrestup = $hrac->AddHracToPrestup($prestup_pole);
      
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Hráč byl úspěšně přidán.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      
      printr($prestup_pole);
      header("Location: index.php?page=hrac&id=".$idHrac);
    }
  }

  if(isset($_SESSION[SESSION_NAME]["msg"]) && (time() - $_SESSION[SESSION_NAME]["time"] < 1)) {
    $template_params["msg"] = $_SESSION[SESSION_NAME]["msg"];
  }
    
  $template_params["header"] = "<h1>Přidání nového hráče</h1>";
  
  // TABLE HEADER 
  $template_params["table"] = "<form action='?page=new_hrac&amp;id=$id_tym' method='post'>
  <table class='table table-hover'>";
  
  // TABLE DATA
                                  
  $template_params["table"] .= "
      <tr><td>Jméno:</td> <td><input type='text' name='jmeno' value='".@$_POST['jmeno']."'></td></tr>
      <tr><td>Příjmení:</td> <td><input type='text' name='prijmeni'  value='".@$_POST['prijmeni']."'></td></tr>
      <tr><td>Číslo:</td> <td><input type='text' name='cislo'  value='".@$_POST['cislo']."'></td></tr>
      <tr><td>Pozice:</td> <td><input type='text' name='pozice'  value='".@$_POST['pozice']."'></td></tr>
      <tr><td>Datum narození:</td> <td><input type='text' name='datum_narozeni' value='".@$_POST['datum_narozeni']."'></td></tr>
      <tr><td>Výška:</td> <td><input type='text' name='vyska' value='".@$_POST['vyska']."'></td></tr>
      <tr><td>Váha:</td> <td><input type='text' name='vaha' value='".@$_POST['vaha']."'></td></tr>
      <tr><td>Draft tým:</td> <td><input type='text' name='draft_tym' value='".$tym_selected['mesto']." ".$tym_selected['nazev']."' disabled></td></tr>
      <tr><td>Draft rok:</td> <td><input type='text' name='draft_rok' value='".@$_POST['draft_rok']."'></td></tr>
      <tr><td>Draft kolo:</td> <td><input type='text' name='draft_kolo' value='".@$_POST['draft_kolo']."'></td></tr>
      <tr><td>Draft pozice:</td> <td><input type='text' name='draft_pozice' value='".@$_POST['draft_pozice']."'></td></tr>
      <tr><td>Univerzita:</td> <td><input type='text' name='univerzita' value='".@$_POST['univerzita']."'></td></tr>
      <tr><td><button type='submit' name='pridat_go' class='btn btn-primary'>Přidat hráče</button> <button type='reset' class='btn btn-danger'>Reset</button></td> <td></td></tr>";
  
  // TABLE FOOTER
  $template_params["table"] .= "</table>
  </form>";
     
  echo $template->render($template_params);

?>