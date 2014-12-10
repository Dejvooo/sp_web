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

  if(!isset($_SESSION[SESSION_NAME]["login"])) {
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
	$uzivatel = new uzivatel($db_connection);
  
  if(isset($_SESSION[SESSION_NAME]["login"])) $uzivatel_selected = $uzivatel->GetUzivatelByNick($_SESSION[SESSION_NAME]["login"]);
  
  // editace emailu  
  if(isset($_POST['email_edit_go'])) {
    if($_POST['email'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste všechna potřebná pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $editUzivatel = $uzivatel->UpdateEmail(htmlspecialchars($_POST['email'], ENT_QUOTES), htmlspecialchars($uzivatel_selected['id_uzivatel'], ENT_QUOTES));
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Editace Vašeho profilu proběhla úspěšně.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: ?page=profil");
    }
  }
  
  // editace hesla
  if(isset($_POST['heslo_edit_go'])) {
    if($_POST['heslo_stare'] == '' || $_POST['heslo_nove'] == '' || $_POST['heslo_nove2'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste všechna potřebná pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else if($_POST['heslo_stare'] != $uzivatel_selected['heslo']) {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nezadal jste správné heslo.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else if($_POST['heslo_nove'] != $_POST['heslo_nove2']) {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nová hesla se neshodují.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $editUzivatel = $uzivatel->UpdateHeslo(htmlspecialchars($_POST['heslo_nove'], ENT_QUOTES), htmlspecialchars($uzivatel_selected['id_uzivatel'], ENT_QUOTES));
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Editace Vašeho profilu proběhla úspěšně.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: ?page=profil");
    }
  }
  
  if(isset($_SESSION[SESSION_NAME]["msg"]) && (time() - $_SESSION[SESSION_NAME]["time"] < 1)) {
    $template_params["msg"] = $_SESSION[SESSION_NAME]["msg"];
  }
  
  $template_params["header"] = "<h1>Editace profilu</h1>";
  
  // TABLE HEADER 
  $template_params["table"] = "<form action='?page=profil' method='post'>
  <table class='table table-hover'>
    <caption>Změna emailu:</caption>";
  
  // TABLE DATA                           
  $template_params["table"] .= "
      <tr><td class='col-md-4'>Uživatelské jméno:</td> <td><input disabled type='text' name='nick' value='".@htmlspecialchars($uzivatel_selected['nick'], ENT_QUOTES)."'></td></tr>
      <tr><td>Email:</td> <td><input type='text' name='email' value='".@htmlspecialchars($uzivatel_selected['email'], ENT_QUOTES)."'></td></tr>
      <tr><td><button type='submit' name='email_edit_go' class='btn btn-primary'>Změnit email</button></td><td></td></tr>";
  
  // TABLE FOOTER
  $template_params["table"] .= "</table>
  </form>";
  
  // TABLE HEADER 
  $template_params["table"] .= "<form action='?page=profil' method='post'>
  <table class='table table-hover'>
    <caption>Změna hesla:</caption>";
  
  // TABLE DATA                           
  $template_params["table"] .= "
      <tr><td class='col-md-4'>Staré heslo:</td> <td><input type='password' name='heslo_stare' value=''></td></tr>
      <tr><td>Nové heslo:</td> <td><input type='password' name='heslo_nove' value=''></td></tr>
      <tr><td>Potvrzení nového hesla:</td> <td><input type='password' name='heslo_nove2' value=''></td></tr>
      <tr><td><button type='submit' name='heslo_edit_go' class='btn btn-primary'>Změnit heslo</button></td><td></td></tr>";
  
  // TABLE FOOTER
  $template_params["table"] .= "</table>
  </form>";
     
  echo $template->render($template_params);

?>