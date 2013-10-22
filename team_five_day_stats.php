<?php

    error_reporting(E_ALL);
    $ckfile = tempnam ("/tmp", "CURLCOOKIE");

	/***** EDIT THIS *****/

	define('TITLE', 'TITLE'); 									// set the chart title
	define('JIRA_URL', 'https://yourcompany.atlassian.net'); 	// your jira host
 	define('USERNAME', 'username'); 							// username
	define('PASSWORD', 'password'); 							// password
	define('REFRESH_RATE','7200'); 								// refresh the chart every X seconds
	define('MAX_RESULTS','200'); 								// query maximum results
	
	/*********************/
    
    $fields = array('username'=>USERNAME, 'password'=>PASSWORD);
    $jsonString = json_encode($fields);
	$searchUrl = JIRA_URL."/rest/api/2/search?jql=";

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
        
    $ch = curl_init ($searchUrl."(created%3E=%22-5d%22%20AND%20created%3C=%22-4d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);
	
    $ch_ = curl_init ($searchUrl."(resolutiondate%3E=%22-5d%22%20AND%20resolutiondate%3C=%22-4d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch_, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch_, CURLOPT_RETURNTRANSFER, true);
    $output_ = curl_exec ($ch_);
	
	
	
    $ch1 = curl_init ($searchUrl."(created%3E=%22-4d%22%20AND%20created%3C=%22-3d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch1, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, true);
    $output1 = curl_exec ($ch1);
	
    $ch1_ = curl_init ($searchUrl."(resolutiondate%3E=%22-4d%22%20AND%20resolutiondate%3C=%22-3d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch1_, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch1_, CURLOPT_RETURNTRANSFER, true);
    $output1_ = curl_exec ($ch1_);
	
	
	
    $ch2 = curl_init ($searchUrl."(created%3E=%22-3d%22%20AND%20created%3C=%22-2d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch2, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, true);
    $output2 = curl_exec ($ch2);
	
    $ch2_ = curl_init ($searchUrl."(resolutiondate%3E=%22-3d%22%20AND%20resolutiondate%3C=%22-2d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch2_, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch2_, CURLOPT_RETURNTRANSFER, true);
    $output2_ = curl_exec ($ch2_);
	
	
	
    $ch3 = curl_init ($searchUrl."(created%3E=%22-2d%22%20AND%20created%3C=%22-1d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch3, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch3, CURLOPT_RETURNTRANSFER, true);
    $output3 = curl_exec ($ch3);
	
    $ch3_ = curl_init ($searchUrl."(resolutiondate%3E=%22-2d%22%20AND%20resolutiondate%3C=%22-1d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch3_, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch3_, CURLOPT_RETURNTRANSFER, true);
    $output3_ = curl_exec ($ch3_);
	
	
	
    $ch4 = curl_init ($searchUrl."(created%3E=%22-1d%22%20AND%20created%3C=%22-0d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch4, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch4, CURLOPT_RETURNTRANSFER, true);
    $output4 = curl_exec ($ch4);
	
    $ch4_ = curl_init ($searchUrl."(resolutiondate%3E=%22-1d%22%20AND%20resolutiondate%3C=%22-0d%22)&maxResults=".MAX_RESULTS);
    curl_setopt ($ch4_, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt ($ch4_, CURLOPT_RETURNTRANSFER, true);
    $output4_ = curl_exec ($ch4_);


    $obj = json_decode($output,true);
	$issues = $obj['issues'];
    $obj1 = json_decode($output1,true);
	$issues1 = $obj1['issues'];
    $obj2 = json_decode($output2,true);
	$issues2 = $obj2['issues'];
    $obj3 = json_decode($output3,true);
	$issues3 = $obj3['issues'];
    $obj4 = json_decode($output4,true);
	$issues4 = $obj4['issues'];
	
	
    $obj_ = json_decode($output_,true);
	$issues_ = $obj_['issues'];
    $obj1_ = json_decode($output1_,true);
	$issues1_ = $obj1_['issues'];
    $obj2_ = json_decode($output2_,true);
	$issues2_ = $obj2_['issues'];
    $obj3_ = json_decode($output3_,true);
	$issues3_ = $obj3_['issues'];
    $obj4_ = json_decode($output4_,true);
	$issues4_ = $obj4_['issues'];
	
	echo "{
    \"graph\": {
        \"title\": \"LAST 5 DAYS\",
		\"total\": true,
		\"type\": \"line\",
        \"datasequences\": [
            {
                \"title\": \"Created\",
				\"refreshEveryNSeconds\" : ".REFRESH_RATE.",
				\"color\": \"red\",
                \"datapoints\": [
                    {
                        \"title\": \"4 days\",
                        \"value\": ".count($issues)."
					},
					{
                        \"title\": \"3 days\",
                        \"value\": ".count($issues1)."
                    },
                    {
                        \"title\": \"2 days\",
                        \"value\": ".count($issues2)."
                    },
                    {
                        \"title\": \"1 days\",
                        \"value\": ".count($issues3)."
                    },
                    {
                        \"title\": \"today\",
                        \"value\": ".count($issues4)."
                    }
                ]
            },
            {
                \"title\": \"Fixed\",
				\"refreshEveryNSeconds\" : ".REFRESH_RATE.",
				\"color\": \"blue\",
                \"datapoints\": [
                    {
                        \"title\": \"4 days\",
                        \"value\": ".count($issues_)."
                    },
                    {
                        \"title\": \"3 days\",
                        \"value\": ".count($issues1_)."
                    },
                    {
                        \"title\": \"2 days\",
                        \"value\": ".count($issues2_)."
                    },
                    {
                        \"title\": \"1 days\",
                        \"value\": ".count($issues3_)."
                    },
                    {
                        \"title\": \"today\",
                        \"value\": ".count($issues4_)."
                    }
                ]
            }
        ]
    }
}";