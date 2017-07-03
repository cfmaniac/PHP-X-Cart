<?php
$city = mysql_escape_string($_POST['city']);
$state = mysql_escape_string($_POST['state']);
$zipcode = mysql_escape_string($_POST['zip']);

//You can manaully enter this info and watch for what is returned via the ship compliant portal
//$city = "Medina";
//$state = "Ohio";
//$zipcode = "44256";

if($city == "" OR $state == "" OR $zipcode == ""){
	echo "fail";
	die;
}

function convert_state($name, $to='name') {
	$states = array(
	array('name'=>'Alabama', 'abbrev'=>'AL'),
	array('name'=>'Alaska', 'abbrev'=>'AK'),
	array('name'=>'Arizona', 'abbrev'=>'AZ'),
	array('name'=>'Arkansas', 'abbrev'=>'AR'),
	array('name'=>'California', 'abbrev'=>'CA'),
	array('name'=>'Colorado', 'abbrev'=>'CO'),
	array('name'=>'Connecticut', 'abbrev'=>'CT'),
	array('name'=>'Delaware', 'abbrev'=>'DE'),
	array('name'=>'Florida', 'abbrev'=>'FL'),
	array('name'=>'Georgia', 'abbrev'=>'GA'),
	array('name'=>'Hawaii', 'abbrev'=>'HI'),
	array('name'=>'Idaho', 'abbrev'=>'ID'),
	array('name'=>'Illinois', 'abbrev'=>'IL'),
	array('name'=>'Indiana', 'abbrev'=>'IN'),
	array('name'=>'Iowa', 'abbrev'=>'IA'),
	array('name'=>'Kansas', 'abbrev'=>'KS'),
	array('name'=>'Kentucky', 'abbrev'=>'KY'),
	array('name'=>'Louisiana', 'abbrev'=>'LA'),
	array('name'=>'Maine', 'abbrev'=>'ME'),
	array('name'=>'Maryland', 'abbrev'=>'MD'),
	array('name'=>'Massachusetts', 'abbrev'=>'MA'),
	array('name'=>'Michigan', 'abbrev'=>'MI'),
	array('name'=>'Minnesota', 'abbrev'=>'MN'),
	array('name'=>'Mississippi', 'abbrev'=>'MS'),
	array('name'=>'Missouri', 'abbrev'=>'MO'),
	array('name'=>'Montana', 'abbrev'=>'MT'),
	array('name'=>'Nebraska', 'abbrev'=>'NE'),
	array('name'=>'Nevada', 'abbrev'=>'NV'),
	array('name'=>'New Hampshire', 'abbrev'=>'NH'),
	array('name'=>'New Jersey', 'abbrev'=>'NJ'),
	array('name'=>'New Mexico', 'abbrev'=>'NM'),
	array('name'=>'New York', 'abbrev'=>'NY'),
	array('name'=>'North Carolina', 'abbrev'=>'NC'),
	array('name'=>'North Dakota', 'abbrev'=>'ND'),
	array('name'=>'Ohio', 'abbrev'=>'OH'),
	array('name'=>'Oklahoma', 'abbrev'=>'OK'),
	array('name'=>'Oregon', 'abbrev'=>'OR'),
	array('name'=>'Pennsylvania', 'abbrev'=>'PA'),
	array('name'=>'Rhode Island', 'abbrev'=>'RI'),
	array('name'=>'South Carolina', 'abbrev'=>'SC'),
	array('name'=>'South Dakota', 'abbrev'=>'SD'),
	array('name'=>'Tennessee', 'abbrev'=>'TN'),
	array('name'=>'Texas', 'abbrev'=>'TX'),
	array('name'=>'Utah', 'abbrev'=>'UT'),
	array('name'=>'Vermont', 'abbrev'=>'VT'),
	array('name'=>'Virginia', 'abbrev'=>'VA'),
	array('name'=>'Washington', 'abbrev'=>'WA'),
	array('name'=>'West Virginia', 'abbrev'=>'WV'),
	array('name'=>'Wisconsin', 'abbrev'=>'WI'),
	array('name'=>'Wyoming', 'abbrev'=>'WY')
	);

	$return = false;
	
	foreach ($states as $state) {
		if ($to == 'name') {
			if (strtolower($state['abbrev']) == strtolower($name)){
				$return = $state['name'];
				break;
			}
		} else if ($to == 'abbrev') {
			if (strtolower($state['name']) == strtolower($name)){
				$return = strtoupper($state['abbrev']);
				break;
			}
		}
	}
	return $return;
}

$state = convert_state($state,$to='abbrev');

if(!extension_loaded("soap")){dl("php_soap.dll");}
ini_set("soap.wsdl_cache_enabled","0");

// connect to the ship compliant WSDL
$client = new SoapClient("https://wings.coopershawkwinery.com/WSDL/ship_compliant_wsdl.wsdl");

$request = array(
	'source' => 'SHOP_CHECK_',
	'orderID' => mt_rand(1000, 9999),
	'firstName' => 'John',
	'lastName' => 'Doe',
	'dateOfBirth' => '03/07/1987',
	'phone' => '',
	'persistOption' => 'OverrideExisting',
	'strictSearchOption' => false,
	'isMember' => false,
	/*'persistOption' => 'Null',*/
	'addressBill' => array(
		'city' => $city,
		'state' => $state,
		'street1' => '123 Test Street',
		'street2' => '',
		'zip' => $zipcode
		),
	'products' => array()
);
// add products to array
$products =	array(
				array(
				'ProductKey'=>'34013',
				'ProductQuantity'=>'4',
				)
			);
			
$request['products'] = $products;

$result = $client->checkOrderCompliance($request);

//start as unable to ship until validated
$response_return = 'cantship';
if ($result['isCompliant'])
{
 $response_return = 'canship';
 if($result["rulesBroken"][0] == "PreviousVisitRequired" || $result["rulesBroken"][0] == "AgeVerificationRequired")
  $response_return = 'member';
}
//error_log(var_export($result, true));
echo $response_return;
?>
