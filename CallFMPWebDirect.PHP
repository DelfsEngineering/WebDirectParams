<?PHP
/********************************************************
This file calls a FMP WebDirect Session and passes the URL Request params
This is a variation of https://github.com/SoliantMike/FM-WebDirect_Params/tree/master/php

C. Delfs - Delfs Engineering

PARAMS:
	mode=	[launch | recall]			
	format=	[let | xml]					- 
	fmurl=	{filemaker path to server}	- fms server url
	fmfile= {filemaker db filename}		- filemaker filename

MODES:
	launch 
		- Create session Var with passed REQUEST Params
		- Launch a FMP webdirect session

	recall
		- Return echos the current Sesson VARS

Filemaker must check on launch:
- Is this a Webdirect Session
- Call this file in 'recall' mode
- This file will return (in Let or XML Noatation) the passed params
- If recall is called without a session launch preceeding, returns 'no session'

sample test URL
http://167.100.30.208/MiscTest/CallFMPWebDirect.php?mode=launch&param1=shit&param2=happens
http://167.100.30.208/MiscTest/CallFMPWebDirect.php?mode=recall&format=xml

********************************************************/
// unconnect for dev, comment for production debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

//********************************************************
//     CONFIGURATION
// default parameters, if not passed
$fmurl = '167.100.30.208'; // enter the address of the server hosting webdirect
$fmfile = 'FMServer_Sample'; // enter the default file to open	

//********************************************************

session_start();

if (isset($_REQUEST['mode']) && ($_REQUEST['mode']) == 'launch') {
	//This launches Filemaker WebDirect and also saves the Request to the session
	echo "Mode = launch";

	//Save the Request into the session 
	$_SESSION['requests'] = $_REQUEST ;
	//print_r($_SESSION);
	
	//Launch the Filemaker Webdirect Page
	if (isset($_REQUEST['fmurl'])) {
		$fmurl = $_REQUEST['fmurl'];
	}
	if (isset($_REQUEST['fmfile'])) {
		$fmfile = $_REQUEST['fmfile'];
	}

	// redirect to WebDirect Session
	header('Location: http://' . $fmurl . '/fmi/webd#' . $fmfile );	

}
Elseif  (isset($_REQUEST['mode']) && ($_REQUEST['mode']) == 'recall'){
	//print_r($_SESSION);
	//Check if there isnt a $_SESSION['requests'] in the event this is checked without a launch before it
	if (!isset($_SESSION['requests'])){
		die('no session');
	}
	
	//Echo out the params
	foreach ($_SESSION['requests'] as $key => $value) {
		if ($key == 'fmurl' || $key == 'fmfile' || $key == 'fmfile'|| $key == 'mode') {
			// do not echo these  parameters
		} else {
			// store as variables
			if (isset($_REQUEST['format']) && ($_REQUEST['format']) == 'xml') {
				// XML Notation
				echo '<' . urlencode($key) . '>' . urlencode($value) . '</'. urlencode($key).'>'."<br>\r";
			} else{
				//Let Notation
				echo '$' . urlencode($key) . ' = "' . urlencode($value) . '"; '."<br>\r";
			}	
		}

	}

	//comment out next line if you want to destroy the session after and dont need to read it again
	session_destroy();
	
}




