<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_demand extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_demand_dtl';
    protected $allowedFields = ['id','govt_saf_dtl_id', 'colony_mstr_id', 'ward_mstr_id', 'govt_saf_tax_dtl_id', 'fy_mstr_id', 'fyear', 'qtr', 'amount', 'balance', 'adjust_amount', 'adjust_type', 'demand_amount', 'additional_holding_tax', 'due_date', 'fine_tax', 'remarks', 'paid_status', 'created_on', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input){
        try{
            $this->db->table($this->table)
                                ->insert($input);
                //echo $this->db->getLastQuery();
            return $this->db->insertID();
        } catch(Exception $e) {
           /*  echo $e->getMessage();
			echo $e->getFile();
			echo $e->getLine(); */
        }
    }
}
?>
