<?php

    error_reporting(E_ALL);
    $ckfile = tempnam ("/tmp", "CURLCOOKIE");

	/***** EDIT THIS *****/

	define('JIRA_URL', 'https://yourcompany.atlassian.net'); 	// your jira host
 	define('USERNAME', 'username'); 							// username
	define('PASSWORD', 'password'); 							// password
	define('MAX_RESULTS','200'); 								// query maximum results
	
	/*********************/
    
    $fields = array('username'=>USERNAME, 'password'=>PASSWORD);
    $jsonString = json_encode($fields);
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
        
    $ch = curl_init (JIRA_URL.$searchUrl."(assignee=".USERNAME."%20AND%20status=open)&startAt=0&maxResults=".MAX_RESULTS);
    curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);

    $obj = json_decode($output,true);
	$issues = $obj['issues'];
	
	echo "100%;
	";
	
	if (count($issues)==0) {
		echo "No issues;
		";
	}
	else {
		for ($i=0;$i<count($issues);$i++) {
			$iss = $issues[$i];
			$fields = $iss['fields'];
			$status = $fields['summary'];
			echo $status.";
			";
		}
	}