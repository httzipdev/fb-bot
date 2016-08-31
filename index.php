<?php

$token_a="EAAAACZAVC6ygBACZCEwZCSAzve3lZCfmeNfbLtet9iju2JRBGhnYhWmbAaBDKj5JC5FL0I3FQ5TrpaDmqSHtZB7bZAtRd7Rvpgkz3infMHquHNwwTTkbV2SEIKST58aRkB4D2tadBv0ykYqqyUOgkIfki3Hn0hpBQZD";
$access_token = "EAADsNJVTk44BABYOFv3ZCax5JvsegkKmNpMy6PPt1KzJ0ssVQ7RqK2G39XconKfCtebt5BozWbegbrGZCtGBZBNCFNBu3QF1aFnD3z4sxg47I8yEPfkd6ZCgbEZAdqQlRAOncAVbNaXdUORZBGZAZA7S1OoTBjrNyAZBiZCVZBNYY5u1QZDZD";
$verify_token = "fb_time_bot";
$hub_verify_token = null;
if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
    echo $challenge;
}
$input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$message_to_reply = '';
/**
 * Some Basic rules to validate incoming messages
 */
/* Get Group */
    

$phanloai=(strpos($message, 'https://www.facebook.com/groups') !== true);
if ($phanloai) {

$tachurl =str_replace(array('https://www.facebook.com/','profile.php?id=','https://m.facebook.com/',' ','https://www.facebook.com/groups/','/'), '', $message); 

$idlink = end((explode('/', $tachurl)));


$graph_link1="https://graph.facebook.com/".$idlink."?fields=name,id&access_token=".$token_a;

$graph_content1=file_get_contents($graph_link1);
$graph1=json_decode($graph_content1);

$name=$graph1->name;
$id=$graph1->id;
if(!empty($id))
{
$message_to_reply="$name - $id";
}
if(empty($id))
{
$message_to_reply="User Not Found !";
}
}
if (strpos($message, 'https://www.facebook.com/groups') !== false) {
$phanloai="";
$tachurlg =str_replace(array('https://www.facebook.com/groups/','https://m.facebook.com/groups',' ','/'), '', $message); 

   $graph_linkg="https://graph.facebook.com/search?q=".strtoupper($tachurlg)."&type=group&access_token=".$token_a."&limit=4";

$graph_contentg=file_get_contents($graph_linkg);
$graphg=json_decode($graph_contentg);

$idg=$graphg->data[0]->id;
$nameg=$graphg->data[0]->name;
$gtype=$graphg->data[0]->privacy;
if(!empty($idg))
{
$message_to_reply="$nameg - $idg";
}
    if(empty($idg))
{
$message_to_reply="Group Not Found !";
}
}

 
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}

