<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class class_type extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('classes');
	}
	
	/**
	* Add new class type
	*/
	function addClassType(){
		if($this->tank_auth->is_admin()){
			if (isset($_POST['class_type']) && isset($_POST['class_description'])){
				$this->classes->addNewClassType($_POST['class_type'], $_POST['class_description']);
			}else{
				echo "No post values";
			}
		
		}
	}
	
	/**
	 * Get all the class types as json
	 */
	function getClassTypes(){
		if($this->tank_auth->is_admin()){
			$types = $this->classes->getClassTypes();
			
			echo json_encode($types);
		
		}
	}
	
	/**
	 * Modify class type
	 */
	function updateClassType(){
		if($this->tank_auth->is_admin()){
			if (isset($_POST['class_type']) && isset($_POST['class_description']) && isset($_POST['class_type_id'])){			
				
				echo($_POST['class_type_id'] . $_POST['class_type'] . $_POST['class_description']);
				$this->classes->updateClassType($_POST['class_type_id'], $_POST['class_type'], $_POST['class_description']);
			}	
	}
	
}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/class_types.php */