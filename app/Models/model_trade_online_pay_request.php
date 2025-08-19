<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_trade_online_pay_request extends Model
{
	protected $db;
    protected $table = 'tbl_online_pay_request';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }
	
	public function pay_request($data) {
		$this->db->table($this->table)
					->insert($data);
		return $this->db->insertID();
	}

    public function getRecordBYRefNo(array $input) 
    {  	
		$sql = "SELECT
					tbl_online_pay_request.*,
                    tbl_apply_licence.application_no
				FROM tbl_online_pay_request
				INNER JOIN tbl_apply_licence ON tbl_apply_licence.id=tbl_online_pay_request.apply_licence_id 
                INNER JOIN tbl_application_type_mstr ON tbl_application_type_mstr.application_type = tbl_online_pay_request.payment_from 
                    AND tbl_application_type_mstr.status = 1                   
				WHERE
					tbl_online_pay_request.biller_txn_reference_id='".$input["billertxnReferenceId"]."'
					AND tbl_online_pay_request.payable_amt='".$input["amount"]."'
                    AND tbl_online_pay_request.status = 1 ";
        
		return $this->db->query($sql)->getFirstRow();
	}

	public function updateRecord(array $data, $pg_mas_id)
	{
		return $this->db->table($this->table)
					->where("id", $pg_mas_id)
					->Update($data);;
	}
    public function searchAppDtl(string $appNo) 
	{
        $sql = "SELECT
                    tbl_apply_licence.id,
                    tbl_apply_licence.application_no as application_no ,
                    owner_dtl.owner_name,
                    owner_dtl.guardian_name,
                    owner_dtl.mobile_no
                FROM tbl_apply_licence
                LEFT JOIN (
                    SELECT 
                    apply_licence_id,
                        STRING_AGG(owner_name, ', ') AS owner_name,
                        STRING_AGG(guardian_name, ', ') AS guardian_name,
                        STRING_AGG(mobile::text, ', ') AS mobile_no
                    FROM tbl_firm_owner_name
                    join tbl_apply_licence on tbl_apply_licence.id = tbl_firm_owner_name.apply_licence_id
                    WHERE tbl_firm_owner_name.status=1 AND tbl_apply_licence.application_no ILIKE '".$appNo."'
                    GROUP BY apply_licence_id
                ) owner_dtl ON owner_dtl.apply_licence_id=tbl_apply_licence.id                
                WHERE tbl_apply_licence.application_no ILIKE '".$appNo."'";      
       
       $data = $this->db->query($sql)->getFirstRow();
    //    echo $this->db->getLastQuery();die;
       return $data;
    }
}
?>