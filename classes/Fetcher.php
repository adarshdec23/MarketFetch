<?php
/*
  Project name : market
  Start Date : 22 Feb, 2016 9:50:00 PM
  Author: Adarsh
  Purpose :
 */
class Fetcher {
	
	private $baseUrl = 'http://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/getHistoricalData.jsp?';
	private $fromDate = '01-01-2018';
	private $toDate = '07-06-2016';

	function __construct() {
		$options = array(
                'http' => array(
                'header'  =>	"User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0"."\r\n".
								"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"."\r\n",
                'method'  => 'GET'
            )
        );
        
        $this->context  = stream_context_create($options);

	}
	
	/**
	 * 
	 * @param type $symbol stock symbol
	 * @param type $from results from date in DD-MM-YYYY
	 * @param type $to results to date in DD-MM-YYYY
	 * @return string Complete url to fetch
	 */
	function buildUrl($symbol, $from, $to) {
		$symbolPart = 'symbol='.strtoupper(urlencode($symbol))."&";
		$fromPart = 'fromDate='.$from."&";
		$toPart = 'toDate='.$to;
		$trailingPart = "series=EQ&".$symbolPart.$fromPart.$toPart;
		$targetUrl = $this->baseUrl.$trailingPart;
		return $targetUrl;
	}
	
	/**
	 * 
	 * @param type $symbol stock symbol
	 * @param type $from results from data in DD-MM-YYYY
	 * @param type $to results to data in DD-MM-YYYY
	 */
	function fetch($symbol, $from = "", $to = "") {
		if(!$symbol)
			return false;
		$from = $from?$from:$this->fromDate;
		$to = $to?$to:$this->toDate;
		$targetUrl = $this->buildUrl($symbol, $from, $to);
		return file_get_contents($targetUrl, false, $this->context);

	}
	
	/*
	 * Setter Methods
	 */
	
	function setToDate($toDate) {
		$this->$toDate = $toDate;
	}
	
	function setFromDate($fromDate) {
		$this->fromDate = $fromDate;
	}
	
	/*
	 * Getter Methods
	 */
	function getToDate() {
		return $this->toDate;
	}
	
	function getFromDate() {
		return $this->fromDate;
	}

}
?>
