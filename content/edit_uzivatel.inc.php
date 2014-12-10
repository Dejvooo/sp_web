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
	$uzivatele = new uzivatel($db_connection);
  
  if(isset($_GET["id"]) && is_numeric($_GET["id"])) $id_uzivatel = $_GET["id"];
  $uzivatel_selected = $uzivatele->GetUzivatelById($id_uzivatel);
  
  // editace uzivatele
  if(isset($_POST['edit_go'])) {
    if($_POST['email'] == '' || $_POST['prava'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste všechna potřebná pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $item = array("email" => htmlspecialchars($_POST['email'], ENT_QUOTES), "prava" => htmlspecialchars($_POST['prava'], ENT_QUOTES));
      $editUzivatel = $uzivatele->UpdateUzivatel($item, $id_uzivatel);
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Editace uživatele ".$uzivatel_selected['nick']." proběhla úspěšně.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: ?page=uzivatele");
    }
  }
  
  $template_params["header"] = "<h1>Editace uživatele ".$uzivatel_selected["nick"];
  
  // TABLE HEADER 
  $template_params["table"] = "<table class='table table-hover'>";
  
  // TABLE DATA
                                  
  $template_params["table"] .= "
    <form action='' method='post'>
      <tr><td>Uživatelské jméno:</td> <td><input disabled type='text' name='nick' value='".@htmlspecialchars($uzivatel_selected['nick'], ENT_QUOTES)."'></td></tr>
      <tr><td>Email:</td> <td><input type='text' name='email' value='".@htmlspecialchars($uzivatel_selected['email'], ENT_QUOTES)."'></td></tr>
      <tr><td>Oprávnění:</td> <td><select name='prava'>
                                    <option value='0'>Uživatel</option>
                                    <option value='1' ";
                                    if($uzivatel_selected['prava'] == 1) 
                                    $template_params["table"] .= "selected='selected'";
                                    $template_params["table"] .= ">Administrátor</option>
                                  </select></td></tr>
      <tr><td><button type='submit' name='edit_go' class='btn btn-primary'>Uložit</button></td><td></td></tr>
    </form>";
  
  // TABLE FOOTER
  $template_params["table"] .= "</table>";
     
  echo $template->render($template_params);

?>