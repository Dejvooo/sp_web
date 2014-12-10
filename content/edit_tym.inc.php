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
	$tym = new tym($db_connection);
  
  if(isset($_GET["id"]) && is_numeric($_GET["id"])) $id_tym = $_GET["id"];
  $tym_selected = $tym->GetTymByID($id_tym);
  
  // editace tymu
  if(isset($_POST['edit_go'])) {
    if($_POST['mesto'] == '' || $_POST['nazev'] == '' || $_POST['rok_zalozeni'] == '' || $_POST['stadion'] == '' || $_POST['kapacita'] == '' || $_POST['trener'] == '' || $_POST['web'] == '' || $_POST['konference'] == '' || $_POST['divize'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste všechna potřebná pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $item = array("mesto" => htmlspecialchars($_POST['mesto'], ENT_QUOTES), "nazev" => htmlspecialchars($_POST['nazev'], ENT_QUOTES), "rok_zalozeni" => htmlspecialchars($_POST['rok_zalozeni'], ENT_QUOTES), "stadion" => htmlspecialchars($_POST['stadion'], ENT_QUOTES), "kapacita" => htmlspecialchars($_POST['kapacita'], ENT_QUOTES), "trener" => htmlspecialchars($_POST['trener'], ENT_QUOTES), "web" => htmlspecialchars($_POST['web'], ENT_QUOTES), "konference" => htmlspecialchars($_POST['konference'], ENT_QUOTES), "divize" => htmlspecialchars($_POST['divize'], ENT_QUOTES));
      $editTym = $tym->UpdateTym($item, $id_tym);
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Editace týmu ".$tym_selected["mesto"]." ".$tym_selected["nazev"]." proběhla úspěšně.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: ?page=tym&id=".$id_tym);
    }
  }
  
  $template_params["header"] = "<h1>Editace týmu ".$tym_selected["mesto"]." ".$tym_selected["nazev"];
  
  // TABLE HEADER 
  $template_params["table"] = "<table class='table table-hover'>";
  
  // TABLE DATA
                                  
  $template_params["table"] .= "
    <form action='' method='post'>
      <tr><td>Město:</td> <td><input type='text' name='mesto' value='".@htmlspecialchars($tym_selected['mesto'], ENT_QUOTES)."'></td></tr>
      <tr><td>Název:</td> <td><input type='text' name='nazev' value='".@htmlspecialchars($tym_selected['nazev'], ENT_QUOTES)."'></td></tr>
      <tr><td>Rok zalozeni:</td> <td><input type='text' name='rok_zalozeni' value='".@htmlspecialchars($tym_selected['rok_zalozeni'], ENT_QUOTES)."'></td></tr>
      <tr><td>Stadion:</td> <td><input type='text' name='stadion' value='".@htmlspecialchars($tym_selected['stadion'], ENT_QUOTES)."'></td></tr>
      <tr><td>Kapacita:</td> <td><input type='text' name='kapacita' value='".@htmlspecialchars($tym_selected['kapacita'], ENT_QUOTES)."'></td></tr><br />
      <tr><td>Trenér:</td> <td><input type='text' name='trener' value='".@htmlspecialchars($tym_selected['trener'], ENT_QUOTES)."'></td></tr>
      <tr><td>Web:</td> <td><input type='text' name='web' value='".@htmlspecialchars($tym_selected['web'], ENT_QUOTES)."'></td></tr>
      <tr><td>Konfernece:</td> <td><select name='konference'>
                                    <option value='Východní'>Východní</option>
                                    <option value='Západní' ";
                                    if($tym_selected['konference'] == "Západní") 
                                    $template_params["table"] .= "selected='selected'";
                                    $template_params["table"] .= ">Západní</option>
                                  </select></td></tr>
      <tr><td>Divize:</td> <td><input type='text' name='divize' value='".@htmlspecialchars($tym_selected['divize'], ENT_QUOTES)."'></td></tr>
      <tr><td><button type='submit' name='edit_go' class='btn btn-primary'>Uložit</button></td><td></td></tr>
    </form>";
  
  // TABLE FOOTER
  $template_params["table"] .= "</table>";
     
  echo $template->render($template_params);

?>