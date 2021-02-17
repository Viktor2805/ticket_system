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

    public function getCustomFields($id) {
        if(!empty($id)) {
            $ticketField = $this->get('tickets/' . $id );
            $custom_fields = $ticketField->custom_fields;

            foreach ($custom_fields as $key => $field) {
                $fieldsKeys[] = $key;
                $fieldsValues[] = $field;

            }

            $fp = fopen('custom_fields.csv', 'w');
            foreach ([$fieldsKeys,$fieldsValues] as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
        } else {
            $custom_fields = NULL;
        }

        return $arrFields;
    }

    public function getData($id) {
        $ticketsData = $this->get("tickets");
        $ticket_Fiedls = $this->get("tickets/15");

//?company_id=".$id;
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
            "group_name",
        ];

//        echo "<pre>";print_r($contact_email);"</pre>";
//                foreach ($ticket_Fiedls->custom_fields)
        $this->getCustomFields("1");

        foreach ($ticketsData as $value ) {
            $group_name =  $this->getGroupByID("$value->group_id")["name"];
            $agent_name =  $this->getAgentByID("$value->responder_id")["name"];
            $agent_email =  $this->getAgentByID("$value->responder_id")["email"];
            $company_name =  $this->getCompanyByID("$value->company_id")["name"];
            $contact_name = $this->getContactByID("$value->requester_id")["name"];
            $contact_email = $this->getContactByID("$value->requester_id")["email"];

//            $contact_email = $this->getContactByID("$value->requester_id")["cu"];



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

//        echo "<pre>";print_r($data);"</pre>";

        return $data;
    }

    public function pushToCsv($data) {
        $fp = fopen('persons.csv', 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
}


$getData = new Freshdesk();
$data = $getData->getData("15");

$csvData = $getData->pushToCsv($data);
