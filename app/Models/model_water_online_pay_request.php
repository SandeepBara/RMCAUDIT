<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_water_online_pay_request extends Model
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
        $column = "consumer.consumer_no as application_no";
        $join = "INNER JOIN tbl_consumer consumer ON consumer.id=tbl_online_pay_request.related_id 
                    AND tbl_online_pay_request.payment_from='Demand Collection' 
                    AND tbl_online_pay_request.status = 1 ";
        if($input['payment_from']=="Connection")
        {
            $column = "consumer.application_no as application_no";
            $join = "INNER JOIN tbl_apply_water_connection consumer ON consumer.id=tbl_online_pay_request.related_id 
                    AND tbl_online_pay_request.payment_from='Connection' 
                    AND tbl_online_pay_request.status = 1 ";
        }		
		$sql = "SELECT
					tbl_online_pay_request.*,
                    $column
				FROM tbl_online_pay_request
				$join
				WHERE
					tbl_online_pay_request.biller_txn_reference_id='".$input["billertxnReferenceId"]."'
					AND tbl_online_pay_request.payable_amt='".$input["amount"]."'";
        
		return $this->db->query($sql)->getFirstRow();
	}

	public function updateRecord(array $data, $pg_mas_id)
	{
		return $this->db->table($this->table)
					->where("id", $pg_mas_id)
					->Update($data);
			
		//echo $this->getLastQuery();
		//return $this->db->insertID();
	}
    public function searchAppDtl(string $appNo,string $taxMode="Demand Collection") 
	{
       if($taxMode =="Connection")
       {
           $sql = "SELECT
                        tbl_apply_water_connection.id,
                        tbl_apply_water_connection.application_no,
                        owner_dtl.owner_name,
                        owner_dtl.guardian_name,
                        owner_dtl.mobile_no
                    FROM tbl_apply_water_connection
                    LEFT JOIN (
                        SELECT 
                            apply_connection_id,
                            STRING_AGG(applicant_name, ', ') AS owner_name,
                            STRING_AGG(father_name, ', ') AS guardian_name,
                            STRING_AGG(mobile_no::text, ', ') AS mobile_no
                        FROM tbl_applicant_details
                        join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_applicant_details.apply_connection_id
                        WHERE tbl_applicant_details.status=1 AND tbl_apply_water_connection.application_no ILIKE '".$appNo."'
                        GROUP BY apply_connection_id
                    ) owner_dtl ON owner_dtl.apply_connection_id=tbl_apply_water_connection.id                
                    WHERE tbl_apply_water_connection.application_no ILIKE '".$appNo."'";
       }
       else
       {
        $sql = "SELECT
                    tbl_consumer.id,
                    tbl_consumer.consumer_no as application_no ,
                    owner_dtl.owner_name,
                    owner_dtl.guardian_name,
                    owner_dtl.mobile_no
                FROM tbl_consumer
                LEFT JOIN (
                    SELECT 
                        consumer_id,
                        STRING_AGG(applicant_name, ', ') AS owner_name,
                        STRING_AGG(father_name, ', ') AS guardian_name,
                        STRING_AGG(mobile_no::text, ', ') AS mobile_no
                    FROM tbl_consumer_details
                    join tbl_consumer on tbl_consumer.id = tbl_consumer_details.consumer_id
                    WHERE tbl_consumer_details.status=1 AND tbl_consumer.consumer_no ILIKE '".$appNo."'
                    GROUP BY consumer_id
                ) owner_dtl ON owner_dtl.consumer_id=tbl_consumer.id                
                WHERE tbl_consumer.consumer_no ILIKE '".$appNo."'";
       }
       
       $data = $this->db->query($sql)->getFirstRow();
    //    echo $this->db->getLastQuery();die;
       return $data;
    }
}
?>