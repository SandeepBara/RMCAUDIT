<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class FeedbackMessageModel extends Model
{

    protected $table = 'tbl_feedback_message';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function getData()
    {
        $result=$this->db->table($this->table)
                        ->select('id,message')
                        ->where('status',1)
                        ->get()
                        ->getResultArray();

        return $result;
                        
    }

    
    
}