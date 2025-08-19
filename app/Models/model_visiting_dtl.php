<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_visiting_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_visiting_dtl';
    protected $allowedFields = [];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    
    public function insertdetail($data)
    {	
        $builder = $this->db->table($this->table)
			->insert($data);
			// echo $this->db->getLastQuery();
			return $this->db->insertId();
    }
	
	public function visiting_list($from_date=null,$to_date=null,$emp_id=null,$where=null)
	{
        $sql = "select tbl1.*, tbl_visiting_remarks.remarks,tbl2.emp_name,tbl2.middle_name,tbl2.last_name ,
				REGEXP_REPLACE(concat(tbl2.emp_name,' ',tbl2.middle_name,' ', tbl2.last_name),'\s+', ' ', 'g') as  full_name
			from tbl_visiting_dtl tbl1
			join tbl_visiting_remarks on tbl_visiting_remarks.id = tbl1.remarks_id
			join tbl_emp_details tbl2 on tbl2.id = tbl1.emp_id
			where 1=1 
				".($emp_id ? "AND tbl1.emp_id=".$emp_id:"");
			if($from_date && $to_date)
			{
				$sql .=" AND date(tbl1.created_on) between '$from_date' and '$to_date'";
			}
			elseif($from_date)
			{
				$sql .=" AND date(tbl1.created_on) >= '$from_date' ";
			}
			elseif($to_date)
			{
				$sql .=" AND date(tbl1.created_on) <= '$to_date' ";
			}
			if($where)
			{
				$sql .= $where;
			}
			$sql .=" order by id desc";
			// print_var($sql);die;
			$ql= $this->db->query($sql);
			// echo $this->db->getLastQuery();die;
			$result =$ql->getResultArray();
			return $result;
    }
	
	public function visiting_listwithoutid($from_date,$to_date)
	{
        $sql = "select tbl1.*, tbl2.emp_name,tbl2.middle_name,tbl2.last_name from tbl_visiting_dtl tbl1
			join tbl_emp_details tbl2 on tbl2.id = tbl1.emp_id
			where date(tbl1.created_on) between '$from_date' and '$to_date'
			order by id desc";
			$ql= $this->db->query($sql);
			// echo $this->db->getLastQuery();die;
			$result =$ql->getResultArray();
			return $result;
    }
}