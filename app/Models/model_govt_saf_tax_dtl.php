<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_tax_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_tax_dtl';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    
	public function tax_list($govt_saf_dtl_id)
    { 
		$sql = "SELECT tb1.*, tb2.fy 
		FROM tbl_govt_saf_tax_dtl tb1
		left join view_fy_mstr tb2 on tb2.id= tb1.fy_mstr_id
		where govt_saf_dtl_id=?
		AND tb1.status=1 
		ORDER BY tb2.fy,tb1.qtr";
        $ql= $this->query($sql, [$govt_saf_dtl_id]);
		//echo $this->db->getLastQuery();
		if($ql){
			return $ql->getResultArray();
		}else{
			return false;
		}
    }
    
}
?>
