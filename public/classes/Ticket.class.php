<?php 
class Ticket {

    // insertCheckPropsInFile
    public function convertJsonToCsv($json_filename, $csv_filename) {
       if (($json = file_get_contents($json_filename)) == false)
        die('Error reading json file...');
        $data = json_decode($json, true);
        $fp = fopen($csv_filename, 'w');
        $header = false;
        foreach ($data as $row)
        {
            if (empty($header))
            {
                $header = array_keys($row);
                fputcsv($fp, $header);
                $header = array_flip($header);
            }
            fputcsv($fp, array_merge($header, $row));
        }
        fclose($fp);
    }

    public function ticketPropsToJson($tickets, $checkedTicketsProps) {
        foreach ($tickets->tickets as $numOfTicket=> $ticket) {
            $obj = new stdClass();
            foreach ($checkedTicketsProps as $key => $value) {
                $obj->$value = $ticket->$value; 
                $arr[$numOfTicket] = $obj;
            }
        }
        file_put_contents('headers.json', json_encode( $arr));  
        return json_encode($arr);
    }


    public function ShowTicketProperties($listOfmainProperties,$tickets) {
        foreach ($tickets->tickets as  $ticket) {
            foreach ($ticket as $key => $value) {
                if (in_array($key, $listOfmainProperties)) {
                    $arr[] = $key;
                }
            }
            break;
        }
        return $arr;
    }


}
?>