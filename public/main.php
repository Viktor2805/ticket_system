<?php

require 'vendor/autoload.php';
require_once './includes/autoloader.inc.php';
use Zendesk\API\HttpClient as ZendeskAPI;

$subdomain = "myseconddomainviktor";
$username  = "harevicviktor@gmail.com"; // replace this with your registered email
$token = "78d5yy3KX7FIHNBCYoGSqSLF23VJdjnrmfNXVo05"; // replace this with your token
$listOfmainProperties = [
    "id","description","status","priority","group_id","organization_id","recipient",
    "submitter_id","brand_id","created_at","updated_at","type"
];

$client = new ZendeskAPI($subdomain);
$client->setAuth('basic', ['username' => $username, 'token' => $token]);

$ticket = new Ticket($client);
$tickets = $client->tickets()->findAll();

$tikcetProps = $ticket->ShowTicketProperties($listOfmainProperties,$tickets);

// send ticket props to user
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    print_r(json_encode($tikcetProps));
}


if (filter_has_var( INPUT_POST, "checkedTicketsProps")) {
    $checkedTicketsProps = $_POST["checkedTicketsProps"];
    $ticket->ticketPropsToJson($tickets,$checkedTicketsProps);

    $json_filename = 'headers.json';
    $csv_filename = 'headers.csv';
    $ticket->convertJsonToCsv($json_filename,$csv_filename);
}

?>