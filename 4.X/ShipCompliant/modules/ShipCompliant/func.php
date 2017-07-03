<?php
/**
 * Ship Compliant Client and Configurations
 * Redeveloped for Coopers Hawk Windery 
**/


if(!extension_loaded("soap")){dl("php_soap.dll");}
ini_set("soap.wsdl_cache_enabled","0");

// connect to the ship compliant WSDL
$client = new SoapClient("https://wings.coopershawkwinery.com/WSDL/ship_compliant_wsdl.wsdl");

// determine if member
$isMember = ($_POST['u_membership'] == "Wine Club Member" ? true : false);

// build request array
$request = array(
	'source' => 'SHOP_CHECK_',
	'orderID' => mt_rand(1000, 9999),
	'firstName' => $_POST['firstname'],
	'lastName' => $_POST['lastname'],
	'dateOfBirth' => $_POST['u_birthday'],
	'phone' => '',
	'persistOption' => 'OverrideExisting',
	'strictSearchOption' => false,
	'isMember' => $isMember,
	'addressBill' => array(
		'city' => $_POST['s_city'],
		'state' => $_POST['s_state'],
		'street1' => $_POST['s_address'],
		'street2' => $_POST['s_address_2'],
		'zip' => $_POST['s_zipcode']
		),
	'products' => array()
);

// add products to array
$products = array();
$total_products = count($_POST['p_code']);

for ($i = 0; $i < $total_products; $i++) {
	$temp_array = array(
		'ProductKey'=> $_POST['p_code'][$i],
		'ProductQuantity'=> $_POST['p_amount'][$i],
		'ProductUnitPrice'=> $_POST['p_price'][$i]
		);
	array_push($products, $temp_array);
}
			
$request['products'] = $products;

$result = $client->checkOrderCompliance($request);

echo json_encode($result);

?>
