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
	
	/*********************/
    
    $credentials = array('username'=>USERNAME, 'password'=>PASSWORD);
    $jsonString = json_encode($credentials);
	$searchUrl = '/rest/api/2/search?jql=';

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
        
    $ch = curl_init (JIRA_URL.$searchUrl."assignee=".USERNAME."&startAt=0&maxResults=".MAX_RESULTS);
    curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);

    $obj = json_decode($output,true);
	$issues = $obj['issues'];

	$open = 0;
	$fixed = 0;
	$resolved = 0;
	$closed = 0;
	
	for ($i=0;$i<count($issues);$i++) {
		$iss = $issues[$i];
		$fields = $iss['fields'];
		$status = $fields['status'];
		if ($status['name']=='Closed') $closed++;
		if ($status['name']=='Resolved') $resolved++;
		if ($status['name']=='Fixed') $fixed++;
		if ($status['name']=='Open') $open++;
	}
	
	echo "{
    \"graph\": {
        \"title\": \"".TITLE."\",
		\"total\": true,
        \"datasequences\": [
            {
                \"title\": \"Open\",
				\"refreshEveryNSeconds\" : ".REFRESH_RATE.",
                \"datapoints\": [
                    {
                        \"title\": \"Open\",
                        \"value\": ".$open."
                    }
                ]
            },
            {
                \"title\": \"Resolved\",
				\"refreshEveryNSeconds\" : ".REFRESH_RATE.",
                \"datapoints\": [
                    {
                        \"title\": \"Resolved\",
                        \"value\": ".$resolved."
                    }
                ]
            },
            {
                \"title\": \"Fixed\",
				\"refreshEveryNSeconds\" : ".REFRESH_RATE.",
                \"datapoints\": [
                    {
                        \"title\": \"Fixed\",
                        \"value\": ".$fixed."
                    }
                ]
            },
            {
                \"title\": \"Closed\",
				\"refreshEveryNSeconds\" : ".REFRESH_RATE.",
                \"datapoints\": [
                    {
                        \"title\": \"Closed\",
                        \"value\": ".$closed."
                    }
                ]
            }
        ]
    }
}";