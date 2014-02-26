<?php

class searchclass extends CI_Controller{

	function __construct()
	{
		parent::__construct();
	}
	
    /*
* Get users from associated with a class booking
*/


/*
* Retrieves search results according to search parameters, also sorts date and time into the correct  *     format
* for the database queries.
*/
function index($page = 'search_results'){
	
	$this->load->Model('Classes');
	$this->load->Model('Classtype');
	
	
	$dbres = $this->Classtype->getClasstype();
	
	$ddmenu = array();
	foreach ($dbres as $row) {
		$ddmenu[] = $row['class_type'];
	}
	$selected = $ddmenu[0];
	$data['options'] = $ddmenu;
	
	
	
	$user_id = $this->tank_auth->get_user_id();
	$class_type = $this->input->post('classname');
	$date = $this->input->post('date');
	$start_time = $this->input->post('starttime');
	$end_time = $this->input->post('endtime');
	
	for ($i = 0; $i <= $class_type; $i++) {
		$selected = $ddmenu[$i];
	}

	if($date == "" && $start_time == "" && $end_time == ""){
		
		$data['classes'] = $this->Classes->getClassesWithType($selected);	
		$dbresults = $this->Classes->getClassesWithType($selected);	
		
		$ddrmenu = array();
		foreach ($dbresults as $row) {
			$resulted = $row['class_id'];
			$bookedout = $this->isClassBookedOut($resulted);
			if($bookedout != ""){
				$ddrmenu[] = "btn btn-warning";
				
			}else{
				$ddrmenu[] = "btn btn-primary";
			}
			
		//			if($this->isClassBookedOut($bookedout)){
		//				echo $bookedout;
		//			}
			
		}
		$data['buttondata'] = $ddrmenu;
		
		
		
		
		
	}elseif($date != "" && $start_time == "" && $end_time == ""){

		$dateInput = explode('/',$date);
		$dbDate = $dateInput[2].'-'.$dateInput[0].'-'.$dateInput[1];
		$starttime = $dbDate." 00:00:00";
		$endtime = $dbDate." 23:59:00";
		echo "HI";
		echo $starttime;
		echo $endtime;
		$data['classes'] = $this->Classes->getClassesWithTypeAndStartTime($selected, $starttime, $endtime);

		$dbresults = $this->Classes->getClassesWithTypeAndStartTime($selected, $starttime, $endtime);
		
		$ddrmenu = array();
		foreach ($dbresults as $row) {
			$resulted = $row['class_id'];
			$bookedout = $this->isClassBookedOut($resulted);
			if($bookedout != ""){
				$ddrmenu[] = "btn btn-warning";
				
			}else{
				$ddrmenu[] = "btn btn-primary";
			}
			
		//			if($this->isClassBookedOut($bookedout)){
		//				echo $bookedout;
		//			}
			
		}
		$data['buttondata'] = $ddrmenu;
	}else{	
		

		$startAMorPM = mb_substr($start_time,-2);
		$endAMorPM = mb_substr($end_time,-2);

	//format date to suit database
	//works fine
		$dateInput = explode('/',$date);
		$dbDate = $dateInput[2].'-'.$dateInput[0].'-'.$dateInput[1];

		$start_time = mb_substr($start_time,0,-3);
		$end_time = mb_substr($end_time,0,-3);
		
		if($startAMorPM == "PM"){
			

			$temp = (int)substr($start_time, 0, 2);
			
			if($temp == 12){
				
				$start_time = $start_time . ":00";
				
			}else{
				
				$temp = $temp + 12;
				$start_time = mb_substr($start_time,2);
				
			//This is necessary to make sure single digit pm hours work
				if(strlen($start_time) == 3){
					$start_time = $temp . $start_time . ":00";
				}else{
					$start_time = $temp . ":" . $start_time . ":00";
				}
			}
			
		}

		if($startAMorPM == "AM"){
		//get the hour digits
			$temp = (int)substr($start_time, 0, 2);
			if($temp < 12){

				$start_time = $start_time . ":00";
				
				
			}else{
		 	//remove 12 to hour digits to signify AM
				$temp = $temp - 12;
				$start_time = mb_substr($start_time,2);
				$start_time = $temp . $start_time. ":00";
				
			}
			
		}
		
		if($endAMorPM == "PM"){
		//get the hour digits
			$temp = (int)substr($end_time, 0, 2);
			
			if($temp == 12){
				
				$end_time = $end_time . ":00";
				
				
			}else{
		 	//add 12 to hour digits to signify PM
				$temp = $temp + 12;
				$end_time = mb_substr($end_time,2);
				if(strlen($end_time) == 3){
					$end_time = $temp  . $end_time . ":00";
					
				}else{
					$end_time = $temp  . ":" .$end_time . ":00";
				}
				

				
			}
			
		}
		if($endAMorPM == "AM"){
		//get the hour digits
			$temp = (int)substr($end_time, 0, 2);
			if($temp < 12){
				
				$end_time = $end_time . ":00";
				
				
			}else{
		 	//add 12 to hour digits to signify PM
				$temp = $temp - 12;
				$end_time = mb_substr($end_time,2);
				$end_time = "0".$temp . $end_time . ":00";
				
			}
			
		}
		

		
		$start_date = $dbDate . " " . $start_time;
		$end_date = $dbDate . " " . $end_time;
		echo $start_date;
		echo $end_date;	
		$data['classes'] = $this->Classes->getClassesWithTypeAndStartTime($selected, $start_date, $end_date);

	}


	$data['classtype'] = $this->Classtype->getClasstype();
//    $data['classes'] = $this->Classes->getClassesWithType($selected);
	parse_temp($page, $this->load->view('pages/'.$page, $data, true));

}

function isClassBookedOut($class_booking_id){
	$this->load->model('classes');
	$this->load->model('bookings');
	$capacity = $this->classes->getClassCapacity($class_booking_id);
	$attending = $this->bookings->countBookingAttendants($class_booking_id);

	return ($attending >= $capacity);
}

}
?>

