<?php
/*
  Project name : market
  Start Date : 22 Feb, 2016 11:25:14 PM
  Author: Adarsh
  Purpose : Filter the raw html data and store it
 */
class Processor {
	
	/**
	 * @var type array Indexes of all required fields in one days data
	 */
	private $neededKeys = [0, 3, 4, 5, 7, 8, 9]; 
			
	function __construct($symbol) {
		include __DIR__."/../include/config/db.php";
		$this->db = new mysqli("localhost", $db['username'], $db["password"], $db["databaseName"]);
		$this->symbol = $symbol;
	}
	
	public function getContentFromHtml($rawHtml) {
		$dom = new DOMDocument();
        @$dom->loadHTML($rawHtml);
        $xpath = new DOMXPath($dom);
        return $xpath->query("//div[@id='csvContentDiv']")->item(0)->nodeValue;
	}
	
	public function writeToDb($rows) {
		$query = "INSERT INTO table1 (d) VALUES (?)";
		$stmt = $this->db->prepare($query) or die($this->db->error);
		$this->db->query("START TRANSACTION");
		foreach ($rows as $row) {
			$stmt ->bind_param("s", $row[0]);;
			$stmt->execute();
		}
		$stmt->close();
		$this->db->query("COMMIT");
		
		if($this->db->error) echo $this->db->error;
		else echo "Done";
	}
	/**
	 * 
	 * @param type $oldDate DD-MMM-YYY
	 * @return type YYYY-MM-DD (MySql format)
	 */
	public function dateTransform($oldDate) {
		list($date, $month, $year) = explode("-", $oldDate);
		if(!$month)
			return FALSE;
		$monthNumber = date_parse($month)['month'];
		$monthString = sprintf("%'.02d", $monthNumber);
		$newDateArray = [$year, $monthString, $date];
		return implode("-", $newDateArray);
	}
	
	/**
	 * 
	 * @param type $rawHtml Raw html from extractor
	 * @return mixed false on failure, array of rows otherwise
	 */
	public function process($rawHtml) {
		$content = $this->getContentFromHtml($rawHtml);
		if(!$content)
			return FALSE;
		
		$stringRows = explode(":",$content);
		unset($stringRows[0]); // Remove the first row that contains unwanted text
		foreach($stringRows as $stringRow){
			if(!$stringRow)
				continue;
			$arrayRow = explode('","', trim($stringRow,'"'));
			$arrayRow[0] = $this->dateTransform($arrayRow[0]);
			$rows[] = array_key_intersect($arrayRow, $this->neededKeys); //Get only the required fields
		}
		//$this->writeToDb($rows);
		var_dump($rows);
	}

}
?>

