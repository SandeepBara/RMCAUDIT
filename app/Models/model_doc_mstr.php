<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_doc_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_doc_mstr';
    protected $allowedFields = ['id','doc_name','doc_type','doc_id','status'];

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function getDocList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('status', 1)
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
     public function insertData($input){
       $builder = $this->db->table($this->table)
                            ->insert($input);
        return $insert_id = $this->db->insertID();
        //echo $this->db->getLastQuery()
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('doc_name', $input['doc_name']);
        $builder->where('doc_type', $input['doc_type']);
        $builder->where('doc_id', $input['doc_id']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];
    }
    public function checkdata_other($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('doc_type', $input['doc_type']);
        $builder->where('doc_name', $input['doc_name']);
        $builder->where('status', 1);
        $builder = $builder->get();
        // $builder = $builder->getResultArray();
        $builder = $builder->getFirstRow('array');
        return $builder;
    }
    public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,doc_name,doc_type,doc_id,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder[0];

    }
    public function checkupdatedata_other($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('doc_name', $input['doc_name']);
        $builder->where('doc_type', $input['doc_type']);
		$builder->where('id!=', $input['id']);
        $builder->where('status', 1);
        $builder = $builder->get();
		//echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];
    }
    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('doc_name', $input['doc_name']);
        $builder->where('doc_type', $input['doc_type']);
        $builder->where('doc_id', $input['doc_id']);
        $builder->where('id!=', $input['id']);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];
    }
	public function updatedataById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'doc_name'=>$input['doc_name'],
                                    'doc_type'=>$input['doc_type'],
                                    'doc_id'=>$input['doc_id']
                                    ]);
    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }
    public function getdatabydoc_type($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,doc_name,doc_type,doc_id,status');
        $builder->where('doc_type', $input['doc_type']);
        $builder->where('status', 1);
		$builder->orderBy('id', 'asc');
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder;

    }

    public function getdatabytrmode($doc_id){
        $builder = $this->db->table($this->table);
        $builder->select('id,doc_name,doc_type,doc_id,status');
        $builder->where('doc_id', $doc_id);
        $builder->where('doc_type', 'transfer_mode');
        $builder->where('status', 1);
        $builder = $builder->get();

		$builder = $builder->getResultArray();
        return $builder;

    }
    public function getdatabyprmode($doc_id){
        $builder = $this->db->table($this->table);
        $builder->select('id,doc_name,doc_type,doc_id,status');
        $builder->where('doc_id', $doc_id);
        $builder->where('doc_type', 'property_type');
        $builder->where('status', 1);
        $builder = $builder->get();
		$builder = $builder->getResultArray();
        return $builder;

    }
    public function getdatabyother(){
        $builder = $this->db->table($this->table);
        $builder->select('id, doc_name, doc_type, doc_id, status');
        $builder->where('doc_type', 'other');
        $builder->where('status', 1);
        $builder = $builder->get();

		$builder = $builder->getResultArray();
        return $builder;
    }

    public function getFlatDocListData(){
        return $this->db->table($this->table)
                ->select('*')
                ->where('doc_type', 'flat_doc')
                ->orWhere('id', 7)
                ->where('status', 1)
                ->get()
                ->getResultArray();
    }
    
    public function getDataByDocType($doc_type){
        return $this->db->table($this->table)
                ->select('*')
                ->where('doc_type', $doc_type)
                ->where('status', 1)
                ->get()
                ->getResultArray();
				//echo $this->db->getLastQuery();
    }

    public function getDataByDocTypeTrust($doc_type){
        return $this->db->table($this->table)
                ->select('*')
                ->whereIn('doc_type', $doc_type)
                ->where('status', 1)
                ->get()
                ->getResultArray();
				//echo $this->db->getLastQuery();
    }
    public function get_docname_data_bydoc_id($id,$doc_type)
    { 
       // $this->db1 = $this->load->database('db', TRUE);

        $sql="SELECT d.doc_name,d.id,t.transfer_mode FROM tbl_saf_doc_collected_dtl s join tbl_doc_mstr d on(s.doc_mstr_id=d.id) join tbl_transfer_mode_mstr t on(d.doc_id=t.id) where d.doc_type='$doc_type'  and s.saf_distributed_dtl_id='$id'";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2;
    }

    public function HaveToUploadDoc($data)
	{
      
		$return=(array)null;
		if($data["prop_type_mstr_id"]==1 && $data['no_electric_connection'] != 't')	// super structure
		{
			$return[]=$this->getDataByDocType('super_structure_doc');
		}
		if ($data['prop_type_mstr_id']==3) //FLATS / UNIT IN MULTI STORIED BUILDING
		{
			$return[]=$this->getDataByDocType('flat_doc');
		}

		if($data['no_electric_connection']=='t') // if electric conn N/A
		{
			$return[]=$this->getDataByDocType('no_elect_connection');
		}

		if($data["assessment_type"]=="Mutation")
		{
			$return[]=$this->getDataByDocType('transfer_mode');
		}
		// if($data["is_specially_abled"]=="true")
		// {
		// 	$return[]=$this->getDataByDocType('is_specially_abled');
		// }
		// if($data["is_armed_force"]=="true")
		// {
		// 	$return[]=$this->getDataByDocType('is_armed_force');
		// }

		if($data["emp_details_id"]!=0) // Online
		{
			$return[][]=array("id"=> 0, "doc_name"=> "SAF Form", "status"=> 1, "doc_type"=> "saf_form", "doc_id"=> 0);
		}
		// commnent if not necessary
		// $return[]=$this->getDataByDocType('property_type');
        if ($data["prop_type_mstr_id"] != 1) {
            $return[] = $this->getDataByDocType('property_type');
        }
        if(isset($data["is_trust"]) && $data["is_trust"]==true)
		{
			$return[]=$this->getDataByDocType('trust_document');
            $return[]=$this->getDataByDocType('income_tax');
		}

		//print_var($return);
		return $return;
	}
}
