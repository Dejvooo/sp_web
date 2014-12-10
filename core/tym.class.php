<?php 

// diky extends mohu pouzivat metody db - jako DBSelect ...
class tym extends db
{
	// konstruktor
	public function tym($connection)
	{
		// timto si nastavim pripojeni k DB, ktere jsem dostal od app()
		$this->connection = $connection;	
	}
	
	public function LoadAllTymForMenu() {
    $table_name = TABLE_TYM;
		$select_columns_string = "id_tym, mesto, nazev"; 
		$where_array = array();
		$limit_string = "";
		$order_by_array[0] = array("column" => "konference", "sort" => "ASC");
    $order_by_array[1] = array("column" => "divize", "sort" => "ASC");
    $order_by_array[2] = array("column" => "mesto", "sort" => "ASC");
    $inner1 = "";
    $inner2 = "";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$tymy = $this->DBSelectAll($table_name, $select_columns_string, $where_array, $limit_string, $order_by_array, $inner1, $inner2);
		//printr($tymy);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $tymy;  
  }
  
	public function GetTymByID($tym_id)
	{
    $table_name = TABLE_TYM;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "id_tym", "value" => $tym_id, "symbol" => "=");
		$limit_string = "";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$tym = $this->DBSelectOne($table_name, $select_columns_string, $where_array, $limit_string);
		//printr($predmety);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $tym;		
	}

	public function GetTymByHracId($id_hrac)
	{
    $table_name = TABLE_PRESTUP;
		$select_columns_string = "tym"; 
		$where_array[] = array("column" => "hrac", "value" => $id_hrac, "symbol" => "=");
		$where_array[] = array("column" => "aktualni", "value" => 1, "symbol" => "=");
		$limit_string = "";
    $inner_tab = "";
    $inner_col = "";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$tym = $this->DBSelectOne($table_name, $select_columns_string, $where_array, $limit_string, $inner_tab, $inner_col);
		//printr($tym);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $tym;		
	}

	
	public function LoadAllHracFromTym($id_tym)
	{
		$table_name = TABLE_PRESTUP;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "tym", "value" => $id_tym, "symbol" => "=");
    $where_array[] = array("column" => "aktualni", "value" => 1, "symbol" => "=");
		$limit_string = "";
		$order_by_array = array();
    $inner1 = "tym";
    $inner2 = "hrac";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hraci = $this->DBSelectAll($table_name, $select_columns_string, $where_array, $limit_string, $order_by_array, $inner1, $inner2);
		//printr($hraci);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hraci;
	}   
  
	public function LoadAllTym()
	{
		$table_name = TABLE_TYM;
		$select_columns_string = "*"; 
		$where_array = array();
		$limit_string = "";
		$order_by_array = array();
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$tymy = $this->DBSelectAll($table_name, $select_columns_string, $where_array, $limit_string, $order_by_array);
		//printr($predmety);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $tymy;
	}   

  public function UpdateTym($tym_pole, $id_tym)
	{
	  $table_name = TABLE_TYM;
    $item[] = array("column" => "mesto", "value" => $tym_pole["mesto"]);
    $item[] = array("column" => "nazev", "value" => $tym_pole["nazev"]);
    $item[] = array("column" => "rok_zalozeni", "value" => $tym_pole["rok_zalozeni"]);
    $item[] = array("column" => "stadion", "value" => $tym_pole["stadion"]);
    $item[] = array("column" => "kapacita", "value" => $tym_pole["kapacita"]);
    $item[] = array("column" => "trener", "value" => $tym_pole["trener"]);
    $item[] = array("column" => "web", "value" => $tym_pole["web"]);
    $item[] = array("column" => "konference", "value" => $tym_pole["konference"]);
    $item[] = array("column" => "divize", "value" => $tym_pole["divize"]);
    $where_array[] = array("column" => "id_tym", "value" => $id_tym, "symbol" => "=");
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$tym = $this->DBUpdate($table_name, $item, $where_array);
		//printr($tym);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $tym;
	}
  
}	


?>