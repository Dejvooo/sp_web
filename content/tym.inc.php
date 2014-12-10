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
	$tym = new tym($db_connection);
  $hrac = new hrac($db_connection);

	// render vrati data pro vypis nebo display je vypise
	// v poli jsou data pro vlozeni do sablony
  if(isset($_GET["id"]) && is_numeric($_GET["id"])) $id_tym = $_GET["id"];
  else $id_tym = 1;
  
  // prepinani tymu
  if($id_tym == 30) { $predchozi_tym = 29; $nasledujici_tym = 1; }
  else if($id_tym == 1) { $predchozi_tym = 30; $nasledujici_tym = 2; }
  else { $predchozi_tym = $id_tym-1; $nasledujici_tym = $id_tym+1; }
  
  $tym_selected = $tym->GetTymByID($id_tym);
  
  if(isset($_SESSION[SESSION_NAME]["msg"]) && (time() - $_SESSION[SESSION_NAME]["time"] < 1)) {
    $template_params["msg"] = $_SESSION[SESSION_NAME]["msg"];
  }
  
  $template_params["header"] = ""; 
  if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
    $template_params["header"] .= "<p class='right'><a href='?page=new_hrac&amp;id=$id_tym'>[Draftovat hráče do týmu]</a> <a href='?page=edit_tym&amp;id=$id_tym'>[Editovat tým]</a></p>";
  }

  // HEADER
  $template_params["header"] .= "<h1>".$tym_selected['mesto']." ".$tym_selected['nazev']."</h1>
    <p>Rok založení: ".$tym_selected['rok_zalozeni']."</p> 
    <p>Stadion: ".$tym_selected['stadion']." (kapacita ".$tym_selected['kapacita']." sedadel)</p> 
    <p>Hlavní trenér: ".$tym_selected['trener']."</p> 
    <p>Web: <a href='".$tym_selected['web']."' target='_blank'>".$tym_selected['web']."</a></p> 
    <p><a href='?page=tym&amp;id=".$predchozi_tym."' class='btn btn-primary btn-lg' role='button'>&laquo; Předchozí tým</a>
    <a href='?page=tym&amp;id=".$nasledujici_tym."' class='btn btn-primary btn-lg' role='button'>Následující tým &raquo;</a></p>
    "; 

  $all_hrac = $tym->LoadAllHracFromTym($id_tym);
  $pocet_hrac = count($all_hrac);
  
  // TABLE HEADER 
  $template_params["table"] = "<table class='table table-hover table-striped'>
  <caption>Aktuální základní sestava týmu ".$tym_selected["mesto"]." ".$tym_selected["nazev"].":</caption>
    <thead>
      <tr>
        <th>Číslo</th>
        <th>Jméno</th>
        <th>Pozice</th>
        <th>V týmu od sezony</th>";
          if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
          $template_params["table"] .= "<th></th>";  
        }
      $template_params["table"] .= "</tr>
    </thead>
    <tbody>";
  
  // TABLE DATA
  for($i = 0; $i < $pocet_hrac; $i++) {             
                                  
     $template_params["table"] .= "
      <tr>
        <td>#".$all_hrac[$i]['cislo']."</td>
        <td><a href='?page=hrac&amp;id=".$all_hrac[$i]['id_hrac']."'>".$all_hrac[$i]['jmeno']." ".$all_hrac[$i]['prijmeni']."</a></td>  
        <td>".$all_hrac[$i]['pozice']."</td>
        <td>".$all_hrac[$i]['sezona']."</td>";
        if(isset($_SESSION[SESSION_NAME]["login"]) && $_SESSION[SESSION_NAME]["prava"] == 1) {
          $template_params["table"] .= "<td><a href='?page=edit_hrac&amp;id=".$all_hrac[$i]['id_hrac']."'>Editovat</a></td>";  
        }
      $template_params["table"] .= "</tr>";
  }
  
  // TABLE FOOTER
  $template_params["table"] .= "
    </tbody>
  </table>";

	echo $template->render($template_params);
  
?>