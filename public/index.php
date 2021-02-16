<?php

require_once './includes/autoloader.inc.php';
require 'vendor/autoload.php';
use GuzzleHttp\Client;
$client = new Client();

class Freshdesk {
    private $url = 'https://newaccount1613401321771.freshdesk.com/api/v2/';
    private $auth = ['auth' => ["thPbFfHTZFJxgcZzYMng", "12"]];

    public function get($api) {
        $client = new Client();
        $response = $client->request('GET', $this->url.$api, $this->auth);

        $response = json_decode($response->getBody());
        return $response;
    }

    public function getContactsData($apiPrefix) {
        $contactsData = $this->get($apiPrefix);
        foreach ($contactsData as $value) {
             echo "<pre>";print_r($value->name);"</pre>";
            echo "<pre>";print_r($value->email);"</pre>";
        }
        return $contactsData;
    }

    public function getTicketsData($apiPrefix) {
        $ticketsData = $this->get($apiPrefix);
        foreach ($ticketsData as $value) {
            echo "<pre>";print_r($value->name);"</pre>";
            echo "<pre>";print_r($value->email);"</pre>";
        }
        return $ticketsData;
    }

    public function getAgentsData($apiPrefix) {
        $agentsData = $this->get($apiPrefix);
        foreach ($agentsData as $value) {
            echo "<pre>";print_r($value->name);"</pre>";
            echo "<pre>";print_r($value->email);"</pre>";
        }
        return $agentsData;
    }
}







class GetData {
    public function getData() {
        $data = [];
        $freshdesk = new Freshdesk();

        print_r($freshdesk->pushInCsv("tickets"));

    }

}

$getData = new GetData();

$getData->getData("tickets");

$listOfmainProperties = [
    "id","description","status","priority","group_id","organization_id","recipient",
    "submitter_id","brand_id","created_at","updated_at","status","company_id"
];


$apiPrefix = ["contacts" => "contacts", "tickets" => "tickets", "agents" => "agents"];

//$freshdesk->pushContactsInCsv($apiPrefix["contacts"]);

//echo" <pre>";print_r($freshdesk->pushContactsInCsv($apiPrefix["agents"]));"</pre>";





//$ticket = new Ticket();
//$tickets = json_decode($response->getBody(), true);

//$ticketProps = $ticket->getTicketProperties($tickets);
//$ticketsJson = $ticket->ticketPropsToJson($page, $tickets,["name","email"]);

//echo" <pre>";print_r($tickets);"</pre>";


//$ticket->convertJsonToCsv("headers.json",'headers.csv');








