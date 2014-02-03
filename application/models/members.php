<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Members extends CI_Model
{
	private $table_name			    = 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this -> table_name			    = $ci -> config -> item('db_table_prefix', 'tank_auth').$this -> table_name;
		$this -> profile_table_name	= $ci -> config -> item('db_table_prefix', 'tank_auth').$this -> profile_table_name;
	}

	/**
	 * Get list of members
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function getAllUsers() //was get_all_user()
	{
		$this->db->select($this->table_name.'.id,first_name,second_name,email,activated,banned,type,membership_type');  // CHANGE
		$this -> db -> from($this -> table_name);
		$this->db->join('member_type_tbl', $this->table_name.'.member_type_id = member_type_tbl.id');
		$this->db->join('membership_type_tbl', $this->table_name.'.membership_type_id = membership_type_tbl.id');
		
		$query = $this->db->get();
		return $query -> result();
	}
	
	/*
   * Fetch users details
   */
  function getUserByID($id) //was fetchUser($id)
  {
  	$this -> db -> select($this -> table_name.'.id, first_name, second_name, email, home_number, mobile_number, twitter, comms_preference, activated, banned, ban_reason, membership_type');
  	$this -> db -> from($this -> table_name);
  	$this -> db -> where($this -> table_name.'.id', $id);
   $this->db->join('membership_type_tbl', 'membership_type_tbl.id = '.$this -> table_name.'.membership_type_id');

   $query = $this -> db -> get();   
   return $query->result();
 }

  /**
  * Fetch users that partially match a first or second name
  * @param string
  * @return  object
  */
  function getUserLike($q){
   $this -> db -> select("id, email, CONCAT_WS(' ', first_name, second_name) AS name", FALSE);

   $term = strtolower($q);
   $this->db->where("(LOWER(first_name) LIKE '%{$q}%' OR LOWER(second_name) LIKE '%{$q}%')");
   $query = $this -> db -> get($this -> table_name);

   return $query;
 }

 /**
 * Get member type ids
 * @return array
 */
 function getMembershipTypes() {
   $this -> db -> select('id');
   $query = $this -> db -> get('membership_type_tbl');

   return $query->result_array();
 }

 /**
 * Get member email
 * @return string
 */
 function getMemberEmail($member_id) {
   $this -> db -> select('email');
   $this -> db -> where('id', $member_id);
   $this -> db -> from($this -> table_name);

   return $this->db->get()->row()->email;

 }


   /**
   * Update User Details
   * @param number
   * @param Array
   * @param string
   */
   function updateUser($id, $changes)
   {
     $this->db->where('id', $id);
     $this->db->update('users', $changes); 
     return "4:Success";
   }
   
  /**
  * Get Specfic Value 
  * @param number 
  * @param row for value
  * @return object
  */

  function getUserColumn($id, $row) // $row = 'first_name'
  {
   $this->db->select($row);
   $this->db->from($this->table_name);
   $this->db->where('id', $id);

   $query = $this -> db -> get();   
   return $query->result();
 }

  function attendance($pid, $cid, $at)
  {
   $data= array('attended' => $at);
   $this->db->where('member_id', $pid);
   $this->db->where('class_id', $cid);
   $this->db->update('class_booking_tbl', $data); 
  }
}
