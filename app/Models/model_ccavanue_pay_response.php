<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_ccavanue_pay_response extends Model
{
	protected $db;
    protected $table = 'tbl_hdfc_response';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	
	
	public function pay_response($data)
	{
		$this->db->table($this->table)->
			Insert($data);
		//echo $this->db->getLastQuery();
		return $this->db->InsertID();
	}
    

}
?>