<?php
include(dirname(__FILE__). '/../../config/config.inc.php');
include(dirname(__FILE__). '/../../init.php');
include(dirname(__FILE__). '/simplepay.php');

$simplepay = new SimplePay();
$cart = Context::getContext()->cart;
$customer = new Customer($cart->id_customer);

/* Verify SimplePay transaction */
$private_key = Configuration::get('SIMPLEPAY_LIVE_PRIVATE_KEY');
if ((int)Configuration::get('SIMPLEPAY_TEST_MODE')) {
	$private_key = Configuration::get('SIMPLEPAY_TEST_PRIVATE_KEY');
}

$data = array (
	'token' => $_POST['token']
);
$data_string = json_encode($data); 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://checkout.simplepay.ng/v1/payments/verify/');
curl_setopt($ch, CURLOPT_USERPWD, $private_key . ':');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string)                                                                       
));

$curl_response = curl_exec($ch);
$curl_response = preg_split("/\r\n\r\n/",$curl_response);
$response_content = $curl_response[1];
$json_response = json_decode(chop($response_content), TRUE);

$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($response_code == '200') 
{
	$id_order_state = Configuration::get('PS_OS_PAYMENT');

	$simplepay->validateOrder((int)$cart->id, $id_order_state, $_POST['ps_amount'], $simplepay->displayName, $response_code, 
	array('transaction_id' => $json_response['customer_reference']), false, false, $cart->secure_key);
} 
else 
{
	$id_order_state = Configuration::get('_PS_OS_ERROR_');
}

Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.
			'&id_module='.(int)$simplepay->id.'&id_order='.$simplepay->currentOrder.
			'&key='.$customer->secure_key);
?>