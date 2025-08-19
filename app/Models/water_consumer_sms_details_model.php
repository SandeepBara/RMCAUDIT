<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class water_consumer_sms_details_model extends Model
{

    protected $table = 'tbl_consumer_sms_details';
    protected $allowedFields = ['id', 'related_id', 'type', 'sms', 'date', 'message_type', 'user_id', 'mobile_no', 'created_on'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    
    public function SI_sms_citizen($input){
		$curnt_date = date("Y-m-d");
		$result = $this->db->table($this->table)->
			insert([
				  "user_id"=>$input["user_id"],
				  "related_id"=>$input['related_id'],
				  "type"=>$input["type"],
				  "sms"=>$input["sms"],
				  "date"=>$curnt_date,
				  "message_type"=>$input["message_type"],
				  "mobile_no"=>$input["mobile_no"],
				  "created_on"=>$input["created_on"]
				  ]);

		//	echo $this->getLastQuery();
	}
    
  

}