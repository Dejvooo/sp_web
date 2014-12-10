<?php
/**
 * Hlavni konfiguracni soubor.
 * 
 * Tady by mely byt vsechny duletize informace typu pripojeni k db, 
 * prefixy db tabulek a nazvy tabulek. Pro nazvy sloupcu tabulek neni treba
 * zakladat vlastni konstanty.
 *  
 */
	/**
	 * Configuration for: Error reporting
	 * Useful to show every little problem during development, but only show hard errors in production
	 */
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	/**
	 * URL projektu.
	 * Lokalni stroj: "127.0.0.1" nebo "localhost" + cesta k home adresari projektu s index.php
	 */
	define('WEB_DOMAIN', 'http://localhost/nba');
	/**
	 * Pripojeni k DB.
	 */
	
	// lokalni 
	define('DB_TYPE', 'mysql');
	define('DB_HOST', '127.0.0.1');
	define('DB_DATABASE_NAME', 'nba');
	define('DB_USER_LOGIN', 'root');
	define('DB_USER_PASSWORD', '');
	// online
	/*
	define('DB_TYPE', 'mysql');
	define('DB_HOST', '127.0.0.1');
	define('DB_DATABASE_NAME', 'db1_vyuka');
	define('DB_USER_LOGIN', 'db1_vyuka');
	define('DB_USER_PASSWORD', 'db1_vyuka');
	*/
	
	/**
	 * Tady jsou ruzna databazova nastaveni.
	 */

	
	// tabulky
	define('TABLE_TYM', 'tym');
  define('TABLE_HRAC', 'hrac');
  define('TABLE_PRESTUP', 'prestup');
  define('TABLE_KOMENTAR', 'komentar');
  define('TABLE_UZIVATEL', 'uzivatel');
  define('SESSION_NAME', 'nba_user');
?>