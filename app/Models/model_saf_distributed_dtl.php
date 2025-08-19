<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_saf_distributed_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_distributed_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','saf_no','ward_mstr_id','owner_name','phone_no','owner_address','survey_by_emp_details_id','created_on','doc_received_on','doc_received_by_emp_details_id','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function get_saf_disrtibuted_detail($data,$empid){
        //print_r($inputs);
        $builder = "select * from view_saf_distribution 
		where status=1 and
		survey_by_emp_details_id=".$empid." and 
		date(created_on)>='".$data["datefrom"]."' and date(created_on)<='".$data["dateto"]."'";        
         $ql= $this->query($builder);
        //echo $this->db->getLastQuery();
        return $result =$ql->getResultArray();
    }
	
	
    public function get_saf_disrtibuted_detailpost($data,$empid){
        $builder = "select * from view_saf_distribution 
		where status=1 and ward_mstr_id=".$data['ward_mstr_id']." and 
		survey_by_emp_details_id=".$empid." and 
		date(created_on)>='".$data["datefrom"]."' and date(created_on)<='".$data["dateto"]."'";        
         $ql= $this->query($builder);
        //echo $this->db->getLastQuery();
        return $result =$ql->getResultArray();
    }
	
	public function get_saf_disrtibuted_detailsafno($empid,$inputs){
        $builder = $this->db->table('view_saf_distribution');
        $builder->select('*');
        $builder->where('saf_no',$inputs["saf_no"]); 
        $builder->where('survey_by_emp_details_id',$empid);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }
	
    public function get_last_saf_detail()
    { 

        $sql="SELECT id,saf_no FROM tbl_saf_distributed_dtl order by id desc Limit 1";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2[0];
    }


    public function CheckDataExists($input)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('owner_name', $input['owner_name']);
        $builder->where('phone_no', $input['phone_no']);
        $builder->where('status', 1);
        $builder = $builder->get();
        return $builder->getFirstRow("array");
    }

    public function CheckFormNoExists($input)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('form_no', $input['form_no']);
        $builder->where('status', 1);
        $builder = $builder->get();
       return $builder->getFirstRow();
    }


    public function insertData($input)
    {
        $builder = $this->db->table($this->table)
                            ->insert($input);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function updateSafNoById($input)
    {
        return $this->db->table($this->table)
                        ->where('id', $input['saf_distributed_dtl_id'])
                        ->update([
                            'saf_no'=>$input['saf_no']
                        ]);
    }

    public function getDetailsById($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,saf_no, owner_name, phone_no, owner_address, ward_mstr_id, form_no');
        $builder->where('md5(id::text)', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];
    }

    public function getDetailsBySAFNo($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,saf_no, owner_name, phone_no, owner_address, ward_mstr_id');
        $builder->where('saf_no', strtoupper($input['saf_no']));
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        //echo $this->db->getLastQuery();
        return $builder[0];
    }
    
    public function CheckEmpidExists($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('saf_no', strtoupper($input['saf_no']));
        $builder->where('doc_received_by_emp_details_id', NULL);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];
    }
    public function CheckEmpidwardExists($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('ward_mstr_id', $input['ward_mstr_id']);
        $builder->where('phone_no', $input['phone_no']);
        $builder->where('doc_received_by_emp_details_id', NULL);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];
    }

    public function getDetailsBySAFFormNo($input)
    {
        $sql="SELECT tbl_saf_distributed_dtl.*, view_ward_mstr.ward_no 
                FROM tbl_saf_distributed_dtl
                join view_ward_mstr on view_ward_mstr.id=tbl_saf_distributed_dtl.ward_mstr_id
                WHERE form_no like '%$input[form_no]%' and tbl_saf_distributed_dtl.status = 1 order by tbl_saf_distributed_dtl.id desc";
        $builder = $this->db->query($sql);
        
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
        
    }
    public function getDetailsBywardphoneNo($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,saf_no, owner_name, phone_no, owner_address, ward_mstr_id');
        $builder->where('ward_mstr_id', strtoupper($input['ward_mstr_id']));
        $builder->where('phone_no', strtoupper($input['phone_no']));
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        //echo $this->db->getLastQuery();
        return $builder[0];
    }
    public function getDetailsBygensafno($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,saf_no, owner_name, phone_no, owner_address, ward_mstr_id');
        $builder->where('saf_no', strtoupper($input['saf_no']));
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder;
    }
    public function getDetailsBygenwardphno($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,saf_no, owner_name, phone_no, owner_address, ward_mstr_id');
        $builder->where('ward_mstr_id', $input['ward_mstr_id']);
        $builder->where('phone_no', $input['phone_no']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder;
    }

    public function updatedocreceivedetById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['saf_distributed_dtl_id'])
                            ->update([
                                    'doc_received_by_emp_details_id'=>$input['doc_received_by_emp_details_id'],
                                    'doc_received_on'=>$input['created_on'],
                                    ]);

    }

    public function getDetailsBySafDistributedDtlId($input){
        return $this->db->table($this->table)
                    ->select('id, saf_no, form_no, ward_mstr_id')
                    ->where('md5(id::text)', $input['saf_distributed_dtl_id'])
                    ->where('status', 1)
                    ->get()
                    ->getFirstRow("array");
    }

    public function getsafnoBySafDistDtlId($saf_distributed_dtl_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('saf_no')
                        ->where('id',$saf_distributed_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

}