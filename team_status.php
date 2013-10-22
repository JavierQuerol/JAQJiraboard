<?php
    error_reporting(E_ALL);
    $ckfile = tempnam ("/tmp", "CURLCOOKIE");
	
	/***** EDIT THIS *****/

	define('TITLE', 'TITLE'); 									// set the chart title
	define('JIRA_URL', 'https://yourcompany.atlassian.net'); 	// your jira host
 	define('USERNAME', 'username'); 							// username
	define('PASSWORD', 'password'); 							// password
	define('REFRESH_RATE','600'); 								// refresh the chart every X seconds
	define('MAX_RESULTS','200'); 								// query maximum results
	$people = array("user1","user2","user3","user4");			// usernames to monitorize
	
	/*********************/
    
    $fields = array('username'=>USERNAME, 'password'=>PASSWORD);
    $jsonString = json_encode($fields);

    $ch = curl_init ();

    curl_setopt ($ch, CURLOPT_URL, JIRA_URL."/rest/auth/1/session"); 
    curl_setopt ($ch, CURLOPT_POST, 1); 
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 1); 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $jsonString); 
    curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json")); 
    curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);
	
	$url = JIRA_URL."/rest/api/2/search?jql=((";
        
	foreach($people as $person) $url .= "assignee=".$person."%20OR%20";
	$url = str_delete($url,strlen($url)-8,8);
	$url .=	")%20AND%20status=open)&startAt=0&maxResults=".MAX_RESULTS;
	$ch = curl_init ($url);
	
    curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);

    $obj = json_decode($output,true);
	$issues = $obj['issues'];

	$result = array();
	
	foreach($people as $person) $result[$person] = 0;
	
	for ($i=0;$i<count($issues);$i++) {
		$iss = $issues[$i];
		$fields = $iss['fields'];
		$assignee = $fields['assignee'];
		$result[$assignee['name']]++;
	}
	
	$output = "{\"graph\": {\"title\": \"".TITLE."\",\"total\": true,\"datasequences\": [";
	foreach($people as $person) {
		$output .= "{\"title\": \"".$person."\",
					\"refreshEveryNSeconds\" : ".REFRESH_RATE.",
					\"color\": ";
		if ($person>20) $output .= "\"red\",";
		else if ($person>8)	$output .= "\"blue\",";
		else $output .= "\"green\",";
        $output .= "\"datapoints\": [{
                    \"title\": \"".$person."\",
                    \"value\": ".$result[$person]."
                	}]},";
	}
	$output = str_delete($output,strlen($output)-1,1);
	$output .= "]}}";
	echo $output;

	function str_delete($aString, $BeginPos, $Length) {
	  $r = '';
	  $l = strlen($aString);
	  $EndPos = $BeginPos + $Length;
	  for ($i = 0; $i < $l; $i++)
	    if (($i < $BeginPos) || ($i >= ($EndPos))) $r .= $aString[$i];
	  return $r;
	}
?>