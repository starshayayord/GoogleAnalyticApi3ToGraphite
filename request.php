<?php
// api dependenciesi
//define ('PATH_TO_API','/opt/ver3/google-api-php-client/src/');
require_once('/opt/ver3/google-api-php-client/src/Google/Client.php');
require_once('/opt/ver3/google-api-php-client/src/Google/Service/Analytics.php');
require __DIR__ . '/config.php';
require __DIR__ . '/GoogleAnalyticsToGraphite.php';
// create client object and set app name
$client = new Google_Client();
$client->setApplicationName('TestProjectOauth'); // name of your app

// set assertion credentials
$client->setAssertionCredentials(
  new Google_Auth_AssertionCredentials(

    $config['ga_email'], // email you added to GA

    array('https://www.googleapis.com/auth/analytics.readonly'),

    file_get_contents($config['key_path'])  // keyfile you downloaded

));

// other settings
$client->setClientId($config['ga_client_id']);// from API console
$client->setAccessType('offline_access');  // this may be unnecessary?
//print_r($client);
// create service and get data
$service = new Google_Service_Analytics($client);
//$metric_name = $config['metric_name'];
//print_r($service);
//$service->data_ga->get($ids, $startDate, $endDate, $metrics, $optParams);
$optParams = array(
    'dimensions' => 'rt:medium');
foreach ($config['ga_profiles'] as $ga_profile)
{
  foreach($config['metric_names'] as $metric_name)
  {
	try {
	echo "\r\n ===== Try to get metric $metric_name for $ga_profile ===== \r\n"; 
	 $results = $service->data_realtime->get(
	      $ga_profile,
	      $metric_name,
	      $optParams);
	  // Success. 
	} catch (apiServiceException $e) {
	  // Handle API service exceptions.
	  $error = $e->getMessage();
	}
	$realm = $results->profileInfo['profileName'];
	$realm = str_replace('.','_',$realm);
	$result = $results->totalsForAllResults[$metric_name];
	//GRAPHITE HERE
	$piper = new GoogleAnalyticsToGraphite(
	$config['graphite_host'], $config['graphite_port']
	);
	$piper->pipeForGraphite($metric_name, $result, $realm);
  }
}
//print_r($piper);
//$file='/opt/data.xml';
//$content = serialize($result);
//file_put_contents($file,$content);
?>
