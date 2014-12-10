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
  
  $uzivatele_all = $uzivatele->LoadAllUzivatel();

  if(isset($_SESSION[SESSION_NAME]["msg"]) && (time() - $_SESSION[SESSION_NAME]["time"] < 1)) {
    $template_params["msg"] = $_SESSION[SESSION_NAME]["msg"];
  }
  
  $template_params["header"] = "<h1>Administrace uživatelů</h1>";

  $pocet_uzivatelu = count($uzivatele_all);
  
  // TABLE HEADER 
  $template_params["table"] = "<table class='table table-hover table-striped'>
  <caption>Seznam uživatelů:</caption>
    <thead>
      <tr>
        <th>Nick</th>
        <th>Email</th>
        <th>Datum registrace</th>";
          if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
          $template_params["table"] .= "<th></th>";  
        }
      $template_params["table"] .= "</tr>
    </thead>
    <tbody>";
  
  // TABLE DATA
  for($i = 0; $i < $pocet_uzivatelu; $i++) {             
     $datum = strtotime($uzivatele_all[$i]["datum_registrace"]);
     $datum = date('d.m.Y', $datum);
                                  
     $template_params["table"] .= "
      <tr>
        <td>".$uzivatele_all[$i]['nick']."</td>  
        <td>".$uzivatele_all[$i]['email']."</td>
        <td>".$datum."</td>";
      if (isset($_SESSION[SESSION_NAME]["login"])){
          $template_params["table"] .= "<td><a href='?page=edit_uzivatel&amp;id=".$uzivatele_all[$i]['id_uzivatel']."'>Editovat</a></td>";  
        }
      $template_params["table"] .= "</tr>";
  }
  
  // TABLE FOOTER
  $template_params["table"] .= "
    </tbody>
  </table>";
     
  echo $template->render($template_params);

?>