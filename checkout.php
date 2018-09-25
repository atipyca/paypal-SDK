<?php  
require "app/start.php";

$s=($_GET["ID"]);

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;



$payer = new Payer();
$payer->setPaymentMethod("paypal");

//filtro cotizacion.

$data=file_get_contents('https://script.google.com/macros/s/AKfycbxP7bqlLzT9_igFMnncW_rz6gH1fLgwyY-DY_PUjORVUrX2eAo/exec?apiKey=123456&operation=GetTickers&sheetid=1lruTAbQ7IBwo-caLtWZSsOJY3XzNaUjUXrRANfikU0U&nominaid='.$s.'&locid=
');


$data = file_get_contents('https://script.google.com/macros/s/AKfycbzoTAtQYZErNOdNvTIWErqOUdAjfVcoZMWskZa8Gu0hnLnrB50/exec?apiKey=1234&operation=GetTickers');

//echo $data;

$data_array = json_decode($data);



////echo "Sku: " . $data_array->records[0]->sku."<br>";

//$product="Batata";
//$product="Batata";
//$product=$data_array->records[1]->name;
$desc=$s;

//$product=$s;
//echo $product;
//echo $data_array;
$items =array();

//echo print_r($data_array);
$arr_length=count($data_array->records)-1;
//$arr_length=3;
$itbis=0;
$descuento=0;
$subtotal=0;
$total=0;
//echo $arr_length;
for ($i=0;$i<=$arr_length;$i++){
     

    //$item = new Item();
    $item[$i] = new Item();

    //$item->setName($product)
    $item[$i]->setName($data_array->records[$i]->name)
        ->setCurrency('USD')
        ->setQuantity(1)
        //->setSku("sku")
        ->setSku($data_array->records[$i]->sku)
        
        //->setPrice(200);
        ->setPrice($data_array->records[$i]->price);

        $subtotal=$subtotal+$data_array->records[$i]->price;
        $itbis=$itbis+$data_array->records[$i]->tax;
    

        $items[]=$item[$i];
    
}



    

	

$itemList = new ItemList();
//$itemList->setItems([$item]);
$itemList->setItems($items);

//echo $subtotal."<br>";

//echo $itbis."<br>";
//echo $total."<br>";

$details = new Details();
$details->setShipping(0)
    ->setTax($itbis)
    ->setSubtotal($subtotal);




//$details = new Details();
//$details->setShipping(6)
  //  ->setTax(1)
  //  ->setSubtotal(200);
	
$amount = new Amount();
$amount->setCurrency("USD")
   // ->setTotal(207)
   ->setTotal($subtotal+$itbis)
    ->setDetails($details);

    
	
$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription($desc)
    ->setInvoiceNumber(uniqid());
	
//$baseUrl = SITE_URL;

          $baseUrl ="http://atipyca.sytes.net:81/LVS/paypal-SDK";

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("$baseUrl/success.php?success=true")
    ->setCancelUrl("$baseUrl/success.php?success=false");
	
$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));
	
	
$request = clone $payment;
   // https://hooks.zapier.com/hooks/paypal/?cid=1581843

//http://atipyca.sytes.net:81/paypal/paypal-SDK

try {
    $payment->create($apiContext);
}catch (Exception $ex) {
	/* print "<pre>";
	print_r($ex);
	print "</pre>"; */
	exit(1);
}

$approvalUrl = $payment->getApprovalLink();

header("location:".$approvalUrl);
