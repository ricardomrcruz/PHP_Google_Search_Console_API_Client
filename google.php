<?php

// echo "Hello World!";

require './vendor/autoload.php';

// **********************************************************CLIENT ID
// ****************************************** CLIENT SECRET

//scopes needed for this client query -|> Google Service Search Console 
//scope https://www.googleapis.com/auth/webmasters.readonly
//scope https://www.googleapis.com/auth/webmasters

session_start(); // session opening

//client google
$client = new Google_Client();
$client->setApplicationName('Google Search Console API PHP');
$client->setScopes([
    'https://www.googleapis.com/auth/webmasters.readonly',
    'https://www.googleapis.com/auth/webmasters'
]);
$client->setAccessType('offline');
$client->setPrompt('consent'); // in the case of google constant demand for verification, just comment it
$client->setAuthConfig('yourgoogleusersecretjson.com.json');
$client->setRedirectUri('https://www.example.fr/path/to/your/folder/google.php');


if (file_exists('refresh_token.txt')) {
    $refreshToken = file_get_contents('refresh_token.txt');
    $refreshToken = trim($refreshToken);
    if (!empty($refreshToken)) {
        $client->refreshToken($refreshToken);
    } else {
        echo "Refresh token is empty.";
    }
} else {
    echo "Refresh token file not found.";
}

if (!empty($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);

    if ($client->isAccessTokenExpired()) {
        // it assures a token is already in place
        if (!empty($client->getRefreshToken())) {
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $_SESSION['access_token'] = $client->getAccessToken();
        } else {
            echo "No refresh token available.";
        }
    }
}


if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $_SESSION['access_token'] = $token;
        $client->setAccessToken($token);
        if (isset($token['refresh_token'])) {
            file_put_contents('refresh_token.txt', $token['refresh_token']);
        }
        header('Location: https://www.example.com/path/to/your/folder/google.php');
        exit();
    } else {
        echo "Error fetching access token.";
    }
}

// access token = successful authentification
if ($client->getAccessToken()) {


    // echo "Gogggle Search Console Keyword Analysis";

    //access gsc par client
    $service = new Google_Service_SearchConsole($client); //this class might give an error, if php and composers versions dont match with your machine ones. 
    //Ignore it in the case of SFTP connection like this one wich was made for a prestashop server. 


    $siteUrl = 'https://www.example.com/'; // the website we added to google clients credentials and wich we had granted permissino in oogle search console

    // NEW SEARCH query
    $query = new Google_Service_SearchConsole_SearchAnalyticsQueryRequest(); //ignore php composer mismatches

    $query->setStartDate('2024-01-01'); // start date
    $query->setEndDate(date("Y-m-d")); // end date
    $query->setDimensions(['page', 'query']); // dimensions query
    $query->setRowLimit(100); // number of queries

    try {
        $response = $service->searchanalytics->query($siteUrl, $query);

        // Initialize XML structure
        $xml = new SimpleXMLElement('<google_query/>');
        $xml->addChild('response_AggregationType', $response->responseAggregationType);

        $rowsXml = $xml->addChild('analysis');
        foreach ($response->getRows() as $row) {
            $rowXml = $rowsXml->addChild('query');
            $rowXml->addChild('clicks', $row->clicks);
            $rowXml->addChild('click_rate', $row->ctr);
            $rowXml->addChild('impressions', $row->impressions);
            $keywords = implode(" ", $row->keys); // Combining all keys into a comma-separated string
            $rowXml->addChild('link', strtok($keywords, " "));
            $rowXml->addChild('keyword', substr(strstr($keywords, " "), 1));
            $rowXml->addChild('ranking', round($row->position));
        }

        // Output the XML outside the loop
        Header('Content-type: text/xml');
        echo $xml->asXML();
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
} else {
    // Redirect to Google's OAuth 2.0 server to initiate the authentication and authorization process
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
    echo "Error. Confirm Google OAuth Identity.";
}
