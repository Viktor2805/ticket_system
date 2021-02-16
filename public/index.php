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


    public function getAgentByID($id) {
        if(!empty($id)) {
            $agent = $this->get('agents/' . $id );
            $name = $agent->contact->name;
            $email = $agent->contact->email;
        } else {
            $name = NULL;
            $email = NULL;
        }

        return [
            'name' => $name,
            'email' => $email
        ];
    }

    public function getContactByID($id) {
        if(!empty($id)) {
            try {
                $contact = $this->get('contacts/' . $id );
                $name = $contact->name;
                $email = $contact->email;
            }  catch (Exception $e) {
                $agent = $this->get('agents/' . $id);
                $name = $agent->contact->name;
                $email = $agent->contact->email;
            };
        } else {
            $name = NULL;
            $email = NULL;
        }

        return [
            'name' => $name,
            'email' => $email
        ];
    }

    public function getGroupByID($id) {
        if(!empty($id)) {

            $group = $this->get('groups/' . $id );
            $name = $group->name;
        } else {
            $name = NULL;
        }

        return [
            'name' => $name,
        ];
    }

    public function getCompanyByID($id) {
        if(!empty($id)) {

            $company = $this->get('companies/' . $id );
            $name = $company->name;
        } else {
            $name = NULL;
        }

        return [
            'name' => $name,
        ];
    }

    public function getData($id) {
        $ticketsData = $this->get("tickets?company_id=".$id);

        $data[] = [
            "ticket_id",
            "ticket_subject",
            "ticket_status",
            "ticket_priority",

            "contact_id",
            "contact_name",
            "contact_email",

            "agent_id",
            "agent_name",
            "agent_email",

            "company_id",
            "company_name",


            'group_id',
            "group_name"
        ];

        foreach ($ticketsData as $value ) {
            $group_name =  $this->getGroupByID("$value->group_id")["name"];
            $agent_name =  $this->getAgentByID("$value->responder_id")["name"];
            $agent_email =  $this->getAgentByID("$value->responder_id")["email"];
            $company_name =  $this->getCompanyByID("$value->company_id")["name"];
            $contact_name = $this->getContactByID("$value->requester_id")["name"];
            $contact_email = $this->getContactByID("$value->requester_id")["email"];

            $data[] = [
                $value->id,
                $value->subject,
                $value->status,
                $value->priority,

                $value->requester_id,
                $contact_name,
                $contact_email,

                $value->responder_id,
                $agent_name,
                $agent_email,

                $value->company_id,
                $company_name,

                $value->group_id,
                $group_name
            ];
        }



        echo "<pre>";print_r($data);"</pre>";


        $fp = fopen('persons.csv', 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

    }


}


$getData = new Freshdesk();
$getData->getData($_POST['company_id']);
































