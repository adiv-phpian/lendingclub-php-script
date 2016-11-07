<?php
  $invester_id = "Account number or invester id of lending club";
  $authkey = "Your secret key from lending club";

  /*** set false to receive only datas ***/
  define("DEBUG_LENDING_API", false);

  /*** this part for getting balance ***/
  $balance = get_balance($invester_id, $authkey);
  print_r($balance);die;

  /*** this part for getting notes ***/
  $notes = get_notes($invester_id, $authkey);
  print_r($notes);die;

  /*** this part for buying notes ***/
  $buy = buy_notes($invester_id, $authkey);
  print_r($buy);die;

  function get_balance($invester_id, $authkey){
    $balance_url = "https://api.lendingclub.com/api/investor/v1/accounts/$invester_id/availablecash";
    // to get balance call this
     return call_curl($balance_url, $authkey);
  }

  function get_notes($invester_id, $authkey){
    $notes_url = "https://api.lendingclub.com/api/investor/v1/accounts/$invester_id/notes";
    // to get notes call this
    return call_curl($notes_url, $authkey);
  }


  function buy_notes($invester_id, $authkey){
    $buy_notes_url = "https://api.lendingclub.com/api/investor/v1/accounts/$invester_id/trades/buy";
    // you can add multiple array in notes, mean multiple notes.
    $note[] = array("loanId" => "", "orderId" => "", "noteID" => "", "bidPrice" => "");
    $note[] = array("loanId" => "", "orderId" => "", "noteID" => "", "bidPrice" => "");
    $datas = array("aid" => "some_id", "notes" => $note);

    // to get balance call this
    $buy_notes = call_curl($buy_notes_url, $authkey, json_encode($datas));

    $notes = json_decode($notes['data']);
    return $notes;
  }

  function call_curl($url, $authkey, $post = "0"){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt ( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0" );
   if($post != "0"){
     curl_setopt($ch,CURLOPT_POST, 1);
     curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
   }
   curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
   curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
   $headers = array();
   $headers[] = "Authorization: $authkey";
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   $server_output = curl_exec ($ch);
   $info = curl_getinfo($ch);
   curl_close ($ch);

   if(DEBUG_LENDING_API == true){
     return array("data" => $server_output, "response" => $info);
   }else{
     return json_decode($server_output);
   }

  }

?>
