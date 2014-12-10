<?php 
	// Twig stahnout z githubu - klidne staci zip a dat do slozky twig-master
		// kontrolu provedete dle umisteni souboru Autoloader.php, ktery prikladam pro kontrolu
	
  // musim zapnout session
	session_start();
  	
  require 'config/config.inc.php';
  require 'config/functions.inc.php';
	require 'core/app.class.php';  
	require 'core/db.class.php';
	require 'core/tym.class.php';	
	require 'core/hrac.class.php';	
	require 'core/komentar.class.php';	
	require 'core/uzivatel.class.php';
  

    
  $page = @$_REQUEST["page"];
	if ($page == "") $page = "tym";
	
	// povolene stranky
	$povolene_stranky = array("tym", "hrac", "uzivatele", "registrace", "profil", "new_hrac", "edit_uzivatel", "edit_tym", "edit_hrac");
	
	if (!in_array($page, $povolene_stranky))
	{
		exit;
	}
	
	// nacist obsah
	$nazev_souboru = "content/$page.inc.php";
	
  $menu = phpWrapperFromFile("content/sign.inc.php");
	$vystup = phpWrapperFromFile($nazev_souboru);
  
  echo $menu;
	echo $vystup;
	
?>