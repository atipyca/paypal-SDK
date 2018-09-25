<?php  

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require "app/start.php";
if(!isset($_GET["success"] , $_GET["paymentId"] , $_GET["PayerID"])){
	die();
}

if((bool) $_GET["success"] === false){
	die();
}

$paymentId = $_GET["paymentId"];
$payerId = $_GET["PayerID"];






$payment = Payment::get($paymentId,$apiContext );

//print count($payment);
//$a="3";


//$a=json_decode($payment);

//print "Sku: " . $payment->intent."<br>";



//.$payments.'}' ;

//print count($a);




$execute = new PaymentExecution();
$execute->setPayerId($payerId);

try{
	$result = $payment->execute($execute , $apiContext );

	//print $result->transactions[0]->description;
	//var_dump($result); 
	//print_r($result);




	//echo json_decode($result);
	print "Gracias por su pago";
}
catch(Exception $e){
	$data = json_decode($e->getData());
	var_dump($data->message);
	die();
}

$s=$result->transactions[0]->description;
//echo $s;
$data=file_get_contents('https://script.google.com/macros/s/AKfycbzEL_r4QpM3j6FEUwdXZG3o_da6sljF40v1gPjhS2J9k7c-CRm_/exec?apiKey=123456&operation=GetTickers&sheetid=1lruTAbQ7IBwo-caLtWZSsOJY3XzNaUjUXrRANfikU0U&nominaid='.$s.'&locid=');

//echo $data;
$html2="";

$data_array = json_decode($data);



//echo "Sku: " . $data_array->values2[0]->link."<br>";
//echo $data_array->email."<br";

$arr_length=count($data_array->links)-1;
//echo $arr_length;

for ($i=0;$i<=$arr_length;$i++){


	$html2.="<br>".$data_array->links[$i]->item."<br>".$data_array->links[$i]->link."<br>";

}

//echo $html2;



//$url = 'https://maker.ifttt.com/trigger/ifttt_php_reply/with/key/omYWNivwkEnSmzw36hrrn';
$url = 'https://maker.ifttt.com/trigger/LVSSendEmail/with/key/omYWNivwkEnSmzw36hrrn';

// get the key from the Maker channel on IFTTT or this link https://maker.ifttt.com to replace all the jjjjjjjjjjj's
// Don't bash your head against the computer for an hour with the wrong key like I did.







$data = array("value1" => $result->transactions[0]->description.'<br>' ,"value2"=>$html2,"value3"=>$data_array->email);


// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);


$result = file_get_contents($url, false, $context);
