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
  $uzivatel = new uzivatel($db_connection);
  
  // pridani uzivatele
  if(isset($_POST['pridat_go'])) {
    if($_POST['nick'] == '' || $_POST['email'] == '' || $_POST['heslo1'] == '' || $_POST['heslo2'] == '') {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Nevyplnil jste všechna potřebná pole.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else if($_POST['heslo1'] != $_POST['heslo2']) {
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-danger' role='alert'>
          <strong>Chyba!</strong> Hesla se neshodují.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
    }
    else {
      $addUzivatel = htmlspecialchars($uzivatel->NewUzivatel(htmlspecialchars($_POST['nick'], ENT_QUOTES), htmlspecialchars($_POST['heslo1'], ENT_QUOTES), htmlspecialchars($_POST['email'], ENT_QUOTES));
      $_SESSION[SESSION_NAME]["msg"] = "
        <div class='alert alert-success' role='alert'>
          Vaše registrace proběhla úspěšně. Nyní se můžete přihlásit.
        </div>";
      $_SESSION[SESSION_NAME]["time"] = time(); 
      header("Location: index.php");
    }
  }
  
  if(isset($_SESSION[SESSION_NAME]["msg"]) && (time() - $_SESSION[SESSION_NAME]["time"] < 1)) {
    $template_params["msg"] = $_SESSION[SESSION_NAME]["msg"];
  }
  
  $template_params["header"] = "<h1>Registrace nového uživatele";
  
  // TABLE HEADER 
  $template_params["table"] = "<table class='table table-hover'>";
  
  // TABLE DATA
                                  
  $template_params["table"] .= "
    <form action='' method='post'>
      <tr><td>Uživatelské jméno:</td> <td><input type='text' name='nick' value='".@htmlspecialchars($_POST['nick'], ENT_QUOTES)."'></td></tr>
      <tr><td>Email:</td> <td><input type='text' name='email' value='".@$_POST['email']."'></td></tr>
      <tr><td>Heslo:</td> <td><input type='password' name='heslo1'></td></tr>
      <tr><td>Heslo znovu:</td> <td><input type='password' name='heslo2'></td></tr>
      <tr><td><button type='submit' name='pridat_go' class='btn btn-success'>Registrovat</button> <button type='reset' class='btn btn-danger'>Reset</button></td> <td></td></tr>
    </form>";
  
  // TABLE FOOTER
  $template_params["table"] .= "</table>";
     
  echo $template->render($template_params);

?>