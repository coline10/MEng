<?php

class booking extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		
		$this->load->Model('classes');

	}
	
	
	    /*
	* Get users from associated with a class booking
	*/
	
	
	   /*
	    * Retrieves search results according to search parameters, also sorts date and time into the correct format
	    * for the database queries.
	    */
	   function success($page = 'bookingsuccess'){
	
	     $this->load->model('bookings');
	
	     $user_id = $this->tank_auth->get_user_id();
	//	$username = $this->tank_auth->get_username();
	 //   $class_type = $this->input->post('classname1');
	     $classid = $this->input->post('classid');
	
	
	     $start = $this->input->post('start');
	     $end = $this->input->post('end');
	     $bookingtype = $this->input->post('bookingtype');
	
	     if($bookingtype == "btn btn-warning"){
	      $bookingtype = "You have been added to the Waiting List for";
	    }else{
	      $bookingtype = "You will be Attending";
	
	    }
	
	 //   echo $classid;
	  //  if (isset($_POST['user_id']) && isset($_POST['class_id'])){
	   //     $m = strtolower($_POST['user_id']);
	    //    $b = strtolower($_POST['class_id']);
	
	
	   //     if(!$this->isClassBookedOut($classid) && !$this->isClassInPast($classid)){
	
	    $this->addMember($classid, $user_id, $start, $end);
	    $data['user_id'] = $this->tank_auth->get_user_id();
	    $data['class_id'] = $classid;
	    $data['start'] = $start;
	    $data['end'] = $end;
	    $data['bookingtype'] = $bookingtype;
	
	    $data['classinfo'] = $this->classes->getClassInformation($classid);
	
	    parse_temp($page, $this->load->view('pages/'.$page, $data, true));
	  }
	      //} 
	
	
	
	  function addMember($classid, $user_id, $start, $end){
	    $this->load->model('bookings');
	
	
	    $m = strtolower($user_id);
	    $b = strtolower($classid);
	
	    if(!$this->isClassBookedOut($b) && !$this->isClassInPast($b)){
	      $this->bookings->addMember($b, $m);
	      $this->_emailMemberAddedToClass($m, $b, $start, $end);
	
	
	    }elseif ($this->isClassBookedOut($b) && !$this->isClassInPast($b)) {
	      $this->bookings->addMemberWaitingList($b, $m);
	      $this->_emailMemberAddedToWaitingList($m, $b, $start, $end);
	
	    }
	
	
	  }
	  
	  /**
	   * Book user into a sport
	   */
	  function bookSport() {
		  $this->load->model('bookings');
	  	/*! need to check that you're allowed to make booking !*/
	  	
	  	if(isset($_POST['class_type_id']) && isset($_POST['class_start_date']) && isset($_POST['room_id'])){
	  		$end = new DateTime($_POST['class_start_date']);
	  		$end->modify("+60 minutes");
	  	
	  		$data = array(
	  			'class_type_id'		=> $_POST['class_type_id'],
	  			'class_start_date'	=> $_POST['class_start_date'],
	  			'class_end_date'	=> $end->format('Y-m-d H:i:00'),
	  			'room_id'			=> $_POST['room_id'],
	  		);
	
		  	$id = $this->classes->insertClass($data);
	
		  	$this->bookings->addMember($id, $this->tank_auth->get_user_id());
		  	
		  	if($this->db->_error_number() == 0){
		  		echo("You have been booked in");
		  	}
		  	
		}
	  }
	

	
	  function isClassInPast($class_booking_id){
	    $end = $this->classes->getClassEndDate($class_booking_id);
	
	    return (time() >  strtotime($end));
	  }
	
	  function _emailMemberAddedToClass($member_id, $class_id, $start, $end) {
	   $this->load->model('members');
	   $this->load->helper('email');
	   
	   $email = $this->members->getMemberEmail($member_id);
	   $classDetails = $this->classes->getClassInformation($class_id);
	   $headers  = 'MIME-Version: 1.0' . "\r\n";
	   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	   $msg ='<!DOCTYPE html>
	   <html>
	   <head>'; 
	   $msg .= 'You have booked into the following class: ' . $classDetails['class_type'] . '. <p> Starting: '. $start . ' <p>End: '. $end;
	
	   $msg .= '</html> </head>';
	   mail($email, 'Booked into a Class', $msg, $headers);
	 }
	 
	 
	 function _emailMemberAddedToWaitingList($member_id, $class_id, $start, $end) {
	   $this->load->model('members');
	   
	   $this->load->helper('email');
	   
	   $email = $this->members->getMemberEmail($member_id);
	   $classDetails = $this->classes->getClassInformation($class_id);
	   $headers  = 'MIME-Version: 1.0' . "\r\n";
	   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	   $msg ='<!DOCTYPE html>
	   <html>
	   <head>'; 
	   $msg .= 'You have been added to the waiting list for the following class: ' . $classDetails['class_type'] . '. <p> Start Time: '. $start . ' <p> End Time: '. $end;
	   $msg .= ' <p> We will notify you through your chosen method of communication if a space becomes available. </p>';
	   $msg .= '</html> </head>';
	   mail($email, 'Booked into a Class', $msg, $headers);
	 }
	 
	 
	 function index() {
	 	parse_temp('user_booking', $this->load->view('pages/user_booking', setupClassSearchForm(), true));
	 }
	

/**
* Retrieves search results according to search parameters, also sorts date and time into the correct format
* for the database queries.
*/
function search(){

	$user_id = $this->tank_auth->get_user_id();
	 $start_date=''; $end_time='';
				
	if(!isset($_POST['class_type_id'])){
		echo("Missing class to search for");
		return;
	}
		
	if($_POST['date'] == ''){
		$start_date = new DateTime();
		$end_date = new DateTime();
		$end_date->modify("+1 weeks");
		
	}else{
			$start_date = new DateTime($_POST['date']);
			$end_date = new DateTime($_POST['date']);
	}
	
	$end_date = $end_date->format("Y-m-d");
	$start_date = $start_date->format("Y-m-d");

	if($_POST['starttime']!=''){
		$start_time = new DateTime($_POST['starttime']);
		$start_time = $start_time->format('H:i:00');
	}else{
		$start_time = '00:00:00';
	}
	
	if($_POST['endtime']!=''){
		$end_time = new DateTime($_POST['endtime']);
		$end_time = $end_time->format('H:i:00');
	}else{
		$end_time = '23:59:59';
	}
	
	if(isset($_POST['is_sport'])){
	$end_date = new DateTime($_POST['date']);
	$end_date = $end_date->format("Y-m-d");
		$data['classes'] = $this->_fetchSportsClasses($_POST['class_type_id'], $start_date, $end_date, $start_time, $end_time);
		
	}else{
		$data['classes'] = $this->classes->getClassesWithTypeAndStartTime($_POST['class_type_id'], $start_date, $end_date, $start_time, $end_time);
		foreach ($data['classes'] as $key => $row) {
			$data['classes'][$key]['fully_booked'] = $this->isClassBookedOut($row['class_id']);
		}
	}
			



	//$data['classes'] = $classes;
//	echo json_encode($classes);
//	include_once('pages/search_results');
	//echo ($this->load->view('pages/search_results', $data, true));

}

/**
* Retrieve possible sports classes that could be booked out
*/
function _fetchSportsClasses($class_type_id, $start_date, $end_date, $start_time, $end_time){
	
		$this->load->model('courts');
		$this->load->model('rooms');
		$this->load->model('classtype');
		$this->load->model('restrictions');
		
		$info = $this->classtype->getClasstypeInfo($class_type_id);
		print_r($info);
		$duration = $info['duration']; 
		
		$start_object = new DateTime($start_date . $start_time);
		$end_object = new DateTime($end_date ." ". $end_time);
		$start_time = new DateTime($start_time);
		
		$results =  array();
		$rooms = $this->courts->findRoomsWithSports($class_type_id);
		
		

		foreach ($rooms as $key => $room) {
			$room_id = $room['room_id'];
			$sportInstances = $this->courts->countSportInstances($room_id, $class_type_id);
			$roomSize = $this->rooms->getRoomSize($room_id);
			$targetSportTokenSize = $this->_fetchTokenSize($room_id, $class_type_id);
			$blockedSportIds = $this->restrictions->getSportsThatBlock($room_id, $class_type_id);
			
			print_r($blockedSportIds);
			
			while($start_object <= $end_object){
			
				$sportInstancesForTime = $sportInstances;
				$roomSizeForTime = $roomSize;

				$alreadyBooked = $this->classes->getSportsBookedOverTime($room_id, $start_date, $end_date, $start_time->format('H:i:s'), $end_time);		
				
				//if intersect then this sport is blocked and can't be booked
//				if(count(array_intersect($array1, $array2)) > 0){
//					$start_time->modify("+60 minutes");
//					continue;
//				}
				//print_r($alreadyBooked);
				foreach ($alreadyBooked as $key => $booked) {
					if($booked['class_type_id'] == $class_type_id){
						$sportInstancesForTime--;
						$roomSizeForTime = $roomSizeForTime - $targetSportTokenSize;
					}else{
						$roomSizeForTime = $roomSizeForTime  - $this->_fetchTokenSize($room_id, $booked['class_type_id']);
					}
				}

				if($sportInstancesForTime > 0 && $roomSizeForTime >= $targetSportTokenSize){
					$result['class_start_date'] = $start_object->format('H:i:s');
					$result['class_end_date'] = $start_object->modify("+$duration minutes");
					$result['class_type'] = $info['class_type'];
					$result['room'] = $room['room'];
					$result['room_id'] = $room['room_id'];
					$result['date'] = $start_date;
					$result['available'] = $sportInstancesForTime;

					array_push($results, $result);
				}else{
					 $start_object->modify("+$duration minutes");
				}

			}
		}
		
		return $results;

}


/**
 * Get token size for class in room
 * @param int
 * @param int
 * @return int
 */
function _fetchTokenSize($room_id, $class_type_id){
//	echo($class_type_id);
	$sportInstances = $this->courts->countSportInstances($room_id, $class_type_id);
	$sportCourts = $this->courts->countSportCourts($room_id, $class_type_id);
	
//	echo("courts ($sportCourts) instances ($sportInstances)" );
	
	if($sportCourts == 0 || $sportInstances==0){
		return 0;
	}
	return $sportCourts / $sportInstances;
}



function isClassBookedOut($class_booking_id){
	$this->load->model('Bookings');

	$capacity = $this->classes->getClassCapacity($class_booking_id);
	$attending = $this->Bookings->countBookingAttendants($class_booking_id);
	
	return ($attending >= $capacity);
}

}
?>
