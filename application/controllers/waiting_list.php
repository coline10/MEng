<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class waiting_list extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('waiting');

	}

	/**
	* Fetch those waiting list for a particular class
	*/
	public function getWaiting($class_id){
		if($this->tank_auth->is_admin()){
			if(isset($class_id)){
				$this->load->model('waiting');

				$waiting = $this->waiting->getWaiting($class_id);
				$num_waiting = sizeof($waiting);
				if($num_waiting > 0){
					echo "Number on waiting list: " . $num_waiting . "<br><ul class='list-group'>";
					foreach ($waiting as $key => $person) {
						echo  '<li class="list-group-item">' . $person['first_name'] . " " . $person['second_name'].'</li>'; 
					}
					echo "</uL>";
				}else{
					echo "No one in the waiting pool.";
				}
			}
		}

	}

	/**
	* Add a member to the waiting list
	*/
	public function addWaiting(){
		if($this->tank_auth->is_admin()){
			if(isset($_POST['member_id']) && isset($_POST['class_id'])){

				print_r($_POST);

				$this->load->model('classes');
				$this->load->helper('book');

				$b = $_POST['class_id'];
				$m = $this->tank_auth->get_user_id();

				$classInfo = $this->classes->getClassInformation($b);

				if($this->waiting->waitingListFull($b, $classInfo['max_attendance'])){
					echo('There are no more spaces on the waiting list.');
					return;
				}

				if(!isclassBookedOut($b)){
					echo "This class has spaces";
					return;
				}

				if($this->waiting->addMemberWaitingList($b, $m)){
					echo "Added to the list";
					emailMemberAddedToWaitingList($m, $classInfo);
				}else{
					echo('Already on the waiting list for this class.');
				}
			}		

		}

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/waiting_list.php */