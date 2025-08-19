<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_fy_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_fy_mstr';
    protected $allowedFields = ['id','fy','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
	
	public function getFyearByFyid($input){
        try{
            return $this->db->table($this->table)
                        ->select('fy')
                        ->where('id', $input)
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getFyByFy($input){
        try{
            return $this->db->table($this->table)
                        ->select('id, fy')
                        ->where('fy', $input['fy'])
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getfyList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, fy')
						->where('id>', 46)
						->where('id<', 53)
                        ->where('status', 1)
                        ->orderBy('id')
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function getFyByIdRangeAsc($input){
        try{
            $data = $this->db->table($this->table)
                        ->select('id, fy')
                        ->where('id >=', $input['fromId'])
                        ->where('id <=', $input['toId'])
                        ->where('status', 1)
                        ->orderBy('id', 'ASC')
                        ->get()
                        ->getResultArray();
            return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getFyByFyRangeAsc($input){
        try{
            return $this->db->table($this->table)
                        ->select('id, fy')
                        ->where('fy >=', $input['fromFy'])
                        ->where('fy <=', $input['toFy'])
                        ->where('status', 1)
                        ->orderBy('id', 'ASC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

	public function getfyFromList($data){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('id', $data);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];
    }

	public function getfyUptoList($data){
        $builder = $this->db->table($this->table);
        $builder->select('id AS fy_upto_id,fy AS fyUpto');
        $builder->where('id', $data);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return isset($builder[0])?$builder[0]:null;
    }
	
	
	public function fy_id($data){
        $builder = $this->db->table($this->table);
        $builder->select('id AS fyid');
        $builder->where('fy', $data);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray()[0];
        return $builder;
    }
	public function getFiyrByid($fy_mstr_id){
        try{
            return $this->db->table($this->table)
                        ->select('id, fy')
                        ->where('id', $fy_mstr_id)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getFiidByfyyr($fy){
        try{
            return $this->db->table($this->table)
                        ->select('id, fy')
                        ->where('fy', $fy)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function fyList(){
        try{
            return $this->db->table($this->table)
                        ->select('id, fy')
                        ->where('status', 1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getFYListDescBefore10Year($currentFyID){
        try{
            return $this->db->table($this->table)
                        ->select('id, fy')
                        ->where('status', 1)
                        ->where('id >=', 47)
                        ->where('id <=', $currentFyID)
                        ->OrderBy('id', 'ASC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	
}
?>