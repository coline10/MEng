<?php

Class Rooms extends CI_Model{

	private $table_name = 'room_tbl';
                
            function retrieve_descriptions(){  
                $this->db->select('description'); 
		$this -> db -> from($this -> table_name); 

		$query = $this -> db ->get();
		
                return $query->result_array();  
            }             

            function retrieve_titles(){  
                $this->db->select('room'); 
		$this -> db -> from($this -> table_name); 

		$query = $this -> db ->get();
		
                return $query->result_array();  
            } 

            function retrieve_ids(){  
                $this->db->select('room_id'); 
		$this -> db -> from($this -> table_name); 

		$query = $this -> db ->get();
		
                return $query->result_array();  
            }          

	/**
	 * Function for fetching all rooms 
	 * @return array
	 */
	function getRooms()	{
		$query = $this -> db -> get('room_tbl');

		return $query->result_array();
	}

	/**
	* Function for fetching all room ids
	* @return array
	*/
	function getRoomIDS(){
		$this->db->select('room_id');
		$this->db->from('room_tbl'); 
		
		return $this -> db -> get()->result_array();
	}


	//getRoomByName
	//getRoomById
	//getRoomByCapacity
	
}
?>
