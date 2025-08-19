<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterRazorPayRequest extends Model
{

    protected $table = 'tbl_razor_pay_request';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


   
    public function getData($id)
    {
    	$result=$this->db->table($this->table)
    				->select('*')
    				->where('status',1)
                    ->where('md5(id::text)',$id)
    				->get()
    				->getFirstRow("array");
                    //echo $this->getLastQuery();
    	return $result;
		
    }
}
