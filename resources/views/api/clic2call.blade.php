<?php


$curl = curl_init();

$receiverNumber =  $_REQUEST['receiverNumber'];
$callernumber =  $_REQUEST['callerNumber'];
// echo "http://103.113.27.27/Anilmadan/click2call.php?callerNumber=$callernumber&receiverNumber=$receiverNumber&user=anil&key=jbti89692vc60b2o9nu%25%5E7";
// die();


curl_setopt_array($curl, [
  CURLOPT_URL => "http://103.113.27.27/Anilmadan/click2call.php?callerNumber=$callernumber&receiverNumber=$receiverNumber&user=anil&key=jbti89692vc60b2o9nu%25%5E7",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYPEER => false, // Disable certificate verification
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
?>
