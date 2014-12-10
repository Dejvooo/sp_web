Adresářová struktura

V rootu jsou uloženy tyto soubory:
  •	index.php - Organizuje, jaký obsah se má zobrazit.
  •	sql_struktura.sql - Vyexportovaná struktura z databáze.
  •	sql_data.sql - Vyexportovaná data z databáze.
  
Adresáře:
  •	bootstrap - Obsahuje všechny potřebné soubory technologie Bootstrap.
  •	ckeditor  - Umožňuje přidávat vizuálně upravené příspěvky.
  •	config - Obsahuje potřebné funkce a definované konstanty.
  •	content - Zde jsou uložena data, která se předávají šablonovacímu systému a ten je následně skrze danou šablonu zobrazí. Jedná se o jediné soubory, které uživateli něco vypisují. Většina souborů z této složky se vypisuje pouze v případě potřeby (např. když chce uživatel zobrazit detail daného hráče, je přesměrován na soubor hrac.inc.php). Soubor sign.inc.php je ale zobrazován na každé stránce bez ohledu na požadavek uživatele. Stará se totiž o hlavičku stránky, menu a přihlašovací pole.
  •	core - Obsahuje třídy, které komunikují s databází. Zde se definují všechny dotazovací funkce na databázi. 
  •	css - Zde jsou další kaskádové styly, které nejsou součástí stylů Bootstrapu.
  •	templates - Šablony v HTLM, do kterých se pomocí Twigu doplňuje požadovaný obsah. Adresář obsahuje 2 šablony. Do šablony temp_default.htlm se vypisuje konkrétní obsah požadované stránky a šablona temp_menu.htlm zobrazuje hlavičku stránky a menu.
  •	twig - Soubory potřebné pro šablonovací systém Twig.

Github: https://github.com/Dejvooo/sp_web