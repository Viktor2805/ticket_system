<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;
$client = new Client();
require_once './includes/autoloader.inc.php';


abstract class GetByApi {
    private $url = 'https://shipweb.zendesk.com';
    private $auth = ['auth' => ['maks.shtieklia@gmail.com', 'Rikoriko955']];

    public function get($api) {
        $client = new Client();
        $response = $client->request('GET', $this->url.$api, $this->auth);

        $tickets = json_decode($response->getBody());
        return $tickets;
    }
}

$page = "contacts";
$url = 'https://newaccount1613401321771.freshdesk.com/api/v2/'.$page;
$response = $client->request("GET", $url, ['auth' => ["thPbFfHTZFJxgcZzYMng", "12"]]);

$listOfmainProperties = [
    "id","description","status","priority","group_id","organization_id","recipient",
    "submitter_id","brand_id","created_at","updated_at","status","company_id"
];

$ticket = new Ticket();
$tickets = json_decode($response->getBody(), true);

$ticketProps = $ticket->getTicketProperties($tickets);
$ticketsJson = $ticket->ticketPropsToJson($page, $tickets,["name","email"]);

echo" <pre>";print_r($tickets);"</pre>";


$ticket->convertJsonToCsv("headers.json",'headers.csv');








