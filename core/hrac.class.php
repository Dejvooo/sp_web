<?php 

// diky extends mohu pouzivat metody db - jako DBSelect ...
class hrac extends db
{
	// konstruktor
	public function hrac($connection)
	{
		// timto si nastavim pripojeni k DB, ktere jsem dostal od app()
		$this->connection = $connection;	
	}
	
	public function AddHrac($hrac_pole)
	{
	  $table_name = TABLE_HRAC;
    $item[] = array("column" => "jmeno", "value" => $hrac_pole["jmeno"]);
    $item[] = array("column" => "prijmeni", "value" => $hrac_pole["prijmeni"]);
    $item[] = array("column" => "cislo", "value" => $hrac_pole["cislo"]);
    $item[] = array("column" => "pozice", "value" => $hrac_pole["pozice"]);
    $item[] = array("column" => "datum_narozeni", "value" => $hrac_pole["datum_narozeni"]);
    $item[] = array("column" => "vyska", "value" => $hrac_pole["vyska"]);
    $item[] = array("column" => "vaha", "value" => $hrac_pole["vaha"]);
    $item[] = array("column" => "draft_tym", "value" => $hrac_pole["draft_tym"]);
    $item[] = array("column" => "draft_rok", "value" => $hrac_pole["draft_rok"]);
    $item[] = array("column" => "draft_kolo", "value" => $hrac_pole["draft_kolo"]);
    $item[] = array("column" => "draft_pozice", "value" => $hrac_pole["draft_pozice"]);
    $item[] = array("column" => "univerzita", "value" => $hrac_pole["univerzita"]);
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hrac = $this->DBInsertExpanded($table_name, $item);
		//printr($hrac);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hrac;
	}	
	
  public function AddHracToPrestup($hrac_pole)
	{
	  $table_name = TABLE_PRESTUP;
    $item[] = array("column" => "hrac", "value" => $hrac_pole["hrac"]);
    $item[] = array("column" => "tym", "value" => $hrac_pole["tym"]);
    $item[] = array("column" => "sezona", "value" => $hrac_pole["sezona"]);
    $item[] = array("column" => "aktualni", "value" => 1);
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hrac = $this->DBInsertExpanded($table_name, $item);
		//printr($hrac);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hrac;
	}	
  
  public function GetHracByDraft($draft_rok, $draft_kolo, $draft_pozice)
	{
		$table_name = TABLE_HRAC;
		$select_columns_string = "id_hrac"; 
		$where_array[] = array("column" => "draft_rok", "value" => $draft_rok, "symbol" => "=");
    $where_array[] = array("column" => "draft_kolo", "value" => $draft_kolo, "symbol" => "=");
    $where_array[] = array("column" => "draft_pozice", "value" => $draft_pozice, "symbol" => "=");
		$limit_string = "";
    $inner_tab = "";
    $inner_col = "";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hrac = $this->DBSelectOne($table_name, $select_columns_string, $where_array, $limit_string, $inner_tab, $inner_col);
		//printr($hrac);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hrac;
	}   
  
	public function GetHracByID($hrac_id)
	{
    $table_name = TABLE_HRAC;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "id_hrac", "value" => $hrac_id, "symbol" => "=");
		$limit_string = "";
    $inner_tab = "tym";
    $inner_col = "draft_tym";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hrac = $this->DBSelectOne($table_name, $select_columns_string, $where_array, $limit_string, $inner_tab, $inner_col);
		//printr($predmety);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hrac;		
	}

	
	public function LoadAllKomentarFromHrac($id_hrac)
	{
		$table_name = TABLE_KOMENTAR;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "hrac", "value" => $id_hrac, "symbol" => "=");
		$limit_string = "";
		$order_by_array = array();
    $inner1 = "";
    $inner2 = "";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hraci = $this->DBSelectAll($table_name, $select_columns_string, $where_array, $limit_string, $order_by_array, $inner1, $inner2);
		//printr($hraci);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hraci;
	}
  
  public function LoadAllPrestup($id_hrac)
	{
		$table_name = TABLE_PRESTUP;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "hrac", "value" => $id_hrac, "symbol" => "=");
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

  public function UpdateHrac($hrac_pole, $id_hrac)
	{
	  $table_name = TABLE_HRAC;
    $item[] = array("column" => "jmeno", "value" => $hrac_pole["jmeno"]);
    $item[] = array("column" => "prijmeni", "value" => $hrac_pole["prijmeni"]);
    $item[] = array("column" => "cislo", "value" => $hrac_pole["cislo"]);
    $item[] = array("column" => "pozice", "value" => $hrac_pole["pozice"]);
    $item[] = array("column" => "datum_narozeni", "value" => $hrac_pole["datum_narozeni"]);
    $item[] = array("column" => "vyska", "value" => $hrac_pole["vyska"]);
    $item[] = array("column" => "vaha", "value" => $hrac_pole["vaha"]);
    $item[] = array("column" => "draft_rok", "value" => $hrac_pole["draft_rok"]);
    $item[] = array("column" => "draft_kolo", "value" => $hrac_pole["draft_kolo"]);
    $item[] = array("column" => "draft_pozice", "value" => $hrac_pole["draft_pozice"]);
    $item[] = array("column" => "univerzita", "value" => $hrac_pole["univerzita"]);
    $where_array[] = array("column" => "id_hrac", "value" => $id_hrac, "symbol" => "=");
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hrac = $this->DBUpdate($table_name, $item, $where_array);
		//printr($hrac);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hrac;
	}
  
  public function DisablePrestup($id_hrac)
	{
    $one = 1;
	  $table_name = TABLE_PRESTUP;
    $item[] = array("column" => "aktualni", "value" => 0);
    $where_array[] = array("column" => "hrac", "value" => $id_hrac, "symbol" => "=");
	  $where_array[] = array("column" => "aktualni", "value" => $one, "symbol" => "=");
    
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$hrac = $this->DBUpdate($table_name, $item, $where_array);
		//printr($hrac);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $hrac;
	}	  
  
}

?>