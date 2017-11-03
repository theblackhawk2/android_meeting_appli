<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// Json Response Array

$response = array("error" => FALSE);

/**
Assuming that the entry values have the form :
 Title           =======> TEXT
 Date            =======> "yyyy/mm/dd"
 time            =======> "HH:mm"
 description     =======> TEXT
 Nb_contributors =======> TEXT convertible to INT 
**/

if (isset($_POST['title']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['description']) && isset($_POST['Nb_contributors']) && isset($_POST['place']) {
	
	$title = $_POST['title'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$description = $_POST['description'];
	$place = $_POST['place'];
	
    if ($db->isMeetingExisted($title , $date, $time)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "Meeting Already existing ";
        echo json_encode($response);
    } else {
		// Creating a new meeting 
		$meeting = db->storeMeeting($title,$description,$date,$time,$place);
		if($meeting){
			// Meeting register successful
			$response["error"] = FALSE;
            $response["uid"] = $meeting["id"];
            $response["meeting"]["title"] = $meeting["title"];
            $response["meeting"]["description"] = $meeting["description"];
            $response["meeting"]["date"] = $meeting["date"];
            $response["meeting"]["time"] = $meeting["time"];
			$response["meeting"]["place"] = $meeting["place"];
            echo json_encode($response);			
		} else {
            // Meeting failed to get registered
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);			
		}
		
		
	}
			
} else {
	$response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (title, date, time, description or place) is missing!";
    echo json_encode($response);
}



?>