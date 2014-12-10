<?php 

// diky extends mohu pouzivat metody db - jako DBSelect ...
class komentar extends db
{
	// konstruktor
	public function komentar($connection)
	{
		// timto si nastavim pripojeni k DB, ktere jsem dostal od app()
		$this->connection = $connection;	
	}

	public function AddKomentar($id_uzivatel, $id_hrac, $text)
	{
		$table_name = TABLE_KOMENTAR;

    $item[] = array("column" => "uzivatel", "value" => $id_uzivatel);
    $item[] = array("column" => "hrac", "value" => $id_hrac);
    $item[] = array("column" => "text", "value" => $text);
    $item[] = array("column" => "datum", "value" => date('Y-m-d H:i:s'));
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$komentar = $this->DBInsertExpanded($table_name, $item);
		//printr($komentar);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $komentar;
	}
  
  
  public function DeleteKomentar($id_komentar)
	{
		$table_name = TABLE_KOMENTAR;
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$komentar = $this->DBDelete($table_name, $id_komentar);
		//printr($komentar);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $komentar;
	}    
  
  
	public function LoadAllKomentarFromHrac($id_hrac)
	{
		$table_name = TABLE_KOMENTAR;
		$select_columns_string = "*"; 
		$where_array[] = array("column" => "hrac", "value" => $id_hrac, "symbol" => "=");
		$limit_string = "";
		$order_by_array[0] = array("column" => "datum", "sort" => "DESC");
    $inner1 = "hrac";
    $inner2 = "uzivatel";
	
		// vrati pole zaznamu v podobe asociativniho pole: sloupec = hodnota
		$komentare = $this->DBSelectAll($table_name, $select_columns_string, $where_array, $limit_string, $order_by_array, $inner1, $inner2);
		//printr($hraci);
		
		// tady jeste neco pripadne dochroupat - docist vsechna potrebna data
		
		// vratit data
		return $komentare;
	}   
  
}

?>