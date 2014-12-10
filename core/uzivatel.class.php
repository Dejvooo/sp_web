<?php 

// diky extends mohu pouzivat metody db - jako DBSelect ...
class uzivatel extends db
{
	// konstruktor
	public function uzivatel($connection)
	{
		// timto si nastavim pripojeni k DB, ktere jsem dostal od app()
		$this->connection = $connection;	
	}
	
  public function LoadAllUzivatel()
	{
    $table_name = TABLE_UZIVATEL;
		$select_columns_string = "*"; 
		$where_array = array();
		$limit_string = "";
		$order_by_array = array();
    $inner1 = "";
    $inner2 = "";
    	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$uzivatele = $this->DBSelectAll($table_name, $select_columns_string, $where_array, $limit_string, $order_by_array, $inner1, $inner2);
		//printr($predmety);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $uzivatele;		
	}

	public function GetUzivatelById($uzivatel_id)
	{
    $table_name = TABLE_UZIVATEL;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "id_uzivatel", "value" => $uzivatel_id, "symbol" => "=");
		$limit_string = "";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$uzivatel = $this->DBSelectOne($table_name, $select_columns_string, $where_array, $limit_string);
		//printr($uzivatel);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $uzivatel;		
	}
    
	public function GetUzivatelByNick($uzivatel_nick)
	{
    $table_name = TABLE_UZIVATEL;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "nick", "value" => $uzivatel_nick, "symbol" => "=");
		$limit_string = "";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$uzivatel = $this->DBSelectOne($table_name, $select_columns_string, $where_array, $limit_string);
		//printr($uzivatel);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $uzivatel;		
	}
  
  public function NewUzivatel($nick, $heslo, $email)
	{
    $table_name = TABLE_UZIVATEL;

    $item[] = array("column" => "nick", "value" => $nick);
    $item[] = array("column" => "heslo", "value" => $heslo);
    $item[] = array("column" => "email", "value" => $email);
    $item[] = array("column" => "datum_registrace", "value" => date('Y-m-d'));
    $item[] = array("column" => "prava", "value" => 0);
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$uzivatel = $this->DBInsertExpanded($table_name, $item);
		//printr($komentar);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $uzivatel;	
	}
  
  public function UpdateUzivatel($uzivatel_pole, $id_uzivatel)
	{
	  $table_name = TABLE_UZIVATEL;
    $item[] = array("column" => "email", "value" => $uzivatel_pole["email"]);
    $item[] = array("column" => "prava", "value" => $uzivatel_pole["prava"]);
    $where_array[] = array("column" => "id_uzivatel", "value" => $id_uzivatel, "symbol" => "=");
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$uzivatel = $this->DBUpdate($table_name, $item, $where_array);
		//printr($uzivatel);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $uzivatel;
	}
  
  public function UpdateEmail($email, $id_uzivatel)
	{
	  $table_name = TABLE_UZIVATEL;
    $item[] = array("column" => "email", "value" => $email);
    $where_array[] = array("column" => "id_uzivatel", "value" => $id_uzivatel, "symbol" => "=");
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$uzivatel = $this->DBUpdate($table_name, $item, $where_array);
		//printr($uzivatel);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $uzivatel;
	}	
  
  public function UpdateHeslo($heslo, $id_uzivatel)
	{
	  $table_name = TABLE_UZIVATEL;
    $item[] = array("column" => "heslo", "value" => $heslo);
    $where_array[] = array("column" => "id_uzivatel", "value" => $id_uzivatel, "symbol" => "=");
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$uzivatel = $this->DBUpdate($table_name, $item, $where_array);
		//printr($uzivatel);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $uzivatel;
	}	
	   
}

?>