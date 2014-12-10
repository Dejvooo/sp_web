<?php
  // nacist twig - kopie z dokumentace
	require_once 'twig/lib/Twig/Autoloader.php';
	Twig_Autoloader::register();
	// cesta k adresari se sablonama - od index.php
	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader); // takhle je to bez cache
  
  // nacist danou sablonu z adresare
	$template_menu = $twig->loadTemplate('temp_menu.html');

  $template_menu_params = array();
  
	// start the application
  $app = new app();
	
	// pripojit k db
	$app->Connect(); 
	
	// pripojeni k db
	$db_connection = $app->GetConnection();
  
  $uzivatel = new uzivatel($db_connection);
  
  $tym = new tym($db_connection);
  $sortedTym = $tym->LoadAllTymForMenu();
  
  // ---MENU TABS---
  $template_menu_params["menu_polozky"] = "";
  $konference = array(1 => 'Východní', 2 => 'Západní');
  $divize = array(1 => 'Atlantická', 2 => 'Centrální', 3 => 'Jihovýchodní', 4 => 'Jihozápadní', 5 => 'Pacifická', 6 => 'Severozápadní');
  
  $konference15 = 0;
  for($l = 0; $l < 2; $l++) {
    $template_menu_params["menu_polozky"] .= "<li class='dropdown'>
      <a href='#' class='dropdown-toggle' data-toggle='dropdown'>".$konference[$l+1]." konference <span class='caret'></span></a>
      <ul class='dropdown-menu' role='menu'>";
    for($i = 1; $i <= 3; $i++) {             
      $template_menu_params["menu_polozky"] .= "<li class='dropdown-header'>".$divize[$i+($l*3)]." divize</li>";
       
      for($j = 0; $j < 5; $j++) {
      $k = ($i * 5) + $konference15 + $j - 5;                            
      $template_menu_params["menu_polozky"] .= "<li><a href='?page=tym&amp;id=".$sortedTym[$k]['id_tym']."'>".$sortedTym[$k]['mesto']." ".$sortedTym[$k]['nazev']."</a></li>";
      }
    }
    $template_menu_params["menu_polozky"] .= "</ul>
      </li>";
    $konference15 += 15;
  }
  // ---END MENU TABS---
  
  // ---LOGIN---
	// existuje tento klic v poli session?
	if (isset($_SESSION[SESSION_NAME]))
	{
		// ano, existuje
	}
	else 
	{
		// ne, neexistuje, musim ho zalozit
		$_SESSION[SESSION_NAME] = array();
	}

	// na zacatek zpracovat nejake pocatecni akce = action
	$action = @$_REQUEST["action"];
	
	// provest prihlaseni, pokud je pozadovano
	if ($action == "login_go")
	{
		// nekdo se pokousi prihlasit
		//printr($_POST);
		
    $currentUzivatel = $uzivatel->GetUzivatelByNick($_POST["login"]);
		$login = $_POST["login"];
		$heslo = $_POST["heslo"];
		
		if ($login == $currentUzivatel['nick'] && $heslo == $currentUzivatel['heslo'])
		{
			$template_menu_params["login_msg"] = "<div class='alert alert-success' role='alert'>
        <strong>Vítejte!</strong> Přihlášení proběhlo úspěšně.
      </div>";
			
      $currentUzivatel = $uzivatel->GetUzivatelByNick($login);
      $prava_uzivatel = $currentUzivatel["prava"];
			// prihlasit
			$_SESSION[SESSION_NAME]["login"] = $login;
      $_SESSION[SESSION_NAME]["prava"] = $prava_uzivatel;
			
		}
		else
		{
			// špatný login a heslo
			$template_menu_params["login_msg"] = "<div class='alert alert-danger' role='alert'>
        <strong>Chyba!</strong> Bylo zadáno nesprávné jméno nebo heslo.
      </div>";
		}
	}
	
	// provest odhlaseni
	if ($action == "logout_go")
	{
		$_SESSION[SESSION_NAME] = array();
		unset($_SESSION[SESSION_NAME]);
	}
	
	
	
	// je uzivatel prihlaseny? Existuje klic login?
	if (isset($_SESSION[SESSION_NAME]["login"]))
	{
		$uzivatel_prihlasen = true;
	}
	else
		$uzivatel_prihlasen = false;
	
	
	// neprihlasenemu uzivateli zobrazim prihlasovaci formular
	if ($uzivatel_prihlasen == false)
	{
		$template_menu_params["login"] = "
          <form class='navbar-form navbar-right' role='form' method='post'>
            <div class='form-group'>
              <input type='text' name='login' placeholder='Email' class='form-control'>
            </div>
            <div class='form-group'>
              <input type='password' name='heslo' placeholder='Heslo' class='form-control'>
            </div>
            <input type='hidden' name='action' value='login_go'>
            <button type='submit' class='btn btn-primary'>Přihlásit</button>
            <a href='?page=registrace' class='btn btn-success'>Registrovat</a>
          </form>";
	}                  
	else
	{
		$template_menu_params["login"] = "
      <a href='index.php?action=logout_go' class='navbar-form navbar-right'><span class='btn btn-primary'>Odhlásit</span></a>
      <a href='?page=profil' class='navbar-form navbar-right'><span class='btn btn-success'>Nastavení účtu</span></a>";

	}
  // ---ENDLOGIN---
 
  if (isset($_SESSION[SESSION_NAME]["login"]) AND $_SESSION[SESSION_NAME]["prava"] == 1){
    $template_menu_params["menu_polozky"] .= "<li><a href='?page=uzivatele'>Uživatelé</a></li>";
  }
     
  echo $template_menu->render($template_menu_params);

?>