<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class grievance_chat_details_model extends Model
{
    protected $db;
    protected $table = 'tbl_grievance_chat_details';
    protected $allowedFields = [];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    
	public function grievance_replay($data)
    {
		$this->db->table($this->table)
				->where('token_no', $data['token_no'])
				->where('query', $data['query'])
				->set(['reply' => $data['reply']])
				->update();
		echo $this->db->getLastQuery();
	}
	
}