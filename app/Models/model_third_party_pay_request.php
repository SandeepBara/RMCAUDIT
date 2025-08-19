<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_third_party_pay_request extends Model
{
	protected $db;
    protected $table = 'tbl_third_party_request';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db)
	{
        $this->db = $db;
    }

	
	public function pay_request(array $data)
	{
		$this->db->table($this->table)->
			Insert($data);
		// return $this->getLastQuery();
		return $this->db->insertID();
	}

    public function getRecord(array $input)
	{
		$builder=$this->db->table($this->table)
					->where($input)
					->get();
		//echo $this->getLastQuery();
		return $builder->getFirstRow('array');
	}

	public function updateRecord($data, $pg_mas_id)
	{
		return $this->db->table($this->table)
					->where("id", $pg_mas_id)
					->Update($data);
			
		//echo $this->getLastQuery();
		//return $this->db->insertID();
	}


}
?>