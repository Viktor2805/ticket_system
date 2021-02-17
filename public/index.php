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

    public function getTicketsByDate($date) {
        if(!empty($id)) {

            $company = $this->get('companies/' . $date );
            $name = $company->name;
        } else {
            $name = NULL;
        }

        return [
            'name' => $name,
        ];
    }

    public function getCustomFields() {
            $ticketField = $this->get('tickets/');

            foreach ($ticketField as $key => $field) {
                foreach ($field->custom_fields as $key => $prop) {
                    $fieldsKeys[] = $key;
                    $fieldsValues[] = $prop;
                }
            }

        return [$fieldsKeys,$fieldsValues];
    }

    public function getData($id) {
        $ticketsData = $this->
        get("tickets?".$id);

        $data[] = [
            "ticket_id",
            "ticket_subject",
            "ticket_status",
            "ticket_priority",
            "ticket_updated_at",
            "ticket_created_at",


            "contact_id",
            "contact_name",
            "contact_email",

            "agent_id",
            "agent_name",
            "agent_email",

            "company_id",
            "company_name",

            'group_id',
            "group_name",
        ];

        foreach ($ticketsData as $value ) {
            $group_name =  $this->getGroupByID($value->group_id)["name"];
            $agent =  $this->getAgentByID($value->responder_id);
            $company_name =  $this->getCompanyByID($value->company_id)["name"];
            $contact_name = $this->getContactByID($value->requester_id)["name"];

            $contact = $this->getContactByID($value->requester_id);

            $data[] = [
                $value->id,
                $value->subject,
                $value->status,
                $value->priority,
                $value->updated_at,
                $value->created_at,

                $value->requester_id,
                $contact["name"],
                $contact["email"],

                $value->responder_id,
                $agent["name"],
                $agent["email"],

                $value->company_id,
                $company_name,

                $value->group_id,
                $group_name
            ];
        }

        return $data;
    }

    public function pushToCsv($data) {
        $fp = fopen('headers.csv', 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
}

$freshdesk = new Freshdesk();

try {
    if (isset($_POST["company_id"]) && isset($_POST["date"])) {
        $api = "company_id=".$_POST["company_id"]."&"."updated_since=".$_POST["date"];
        $tickets = $freshdesk->getData($api);
        $freshdesk->pushToCsv($tickets);
    }
    $api = "company_id=".$_POST["company_id"];
    $tickets = $freshdesk->getData($api);
} catch (Exception $e) {
    $api = "updated_since=".$_POST["date"];
    if (isset($_POST["date"])) {
        $tickets = $freshdesk->getData($api);
    }
    if (isset($tickets )) {
        $freshdesk->pushToCsv($tickets);
    }
}





