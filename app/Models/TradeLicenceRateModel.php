<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class tradelicenceratemodel extends Model 
{
    protected $db;
    protected $table = 'tbl_licence_rate';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'application_type_id', 'range_from', 'range_to', 'rate', 'effective_date', 'emp_details_id','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }  

   public function getrate($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,rate');
        $builder->where('application_type_id', $input['application_type_id']);
        $builder->where('range_from<=', $input['area_in_sqft']);
        $builder->where('range_to>=', $input['area_in_sqft']);       
        $builder->where('effective_date<', $input['curdate']);        
        $builder->where('status', 1);
        $builder->where('tobacco_status', $input['tobacco_status']);
        $builder->orderBy('effective_date','Desc');
        $builder = $builder->get();
        
        $builder = $builder->getResultArray();  


         
        //echo $this->db->getLastQuery();	
        //print_var($builder);	die;
        return $builder[0];
    }
    

    public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('apllication_type_id', $input['apllication_type_id']);
        $builder->where('range_from', $input['range_from']);
        $builder->where('range_to', $input['range_to']);
        $builder->where('rate', $input['rate']);
        $builder->where('effective_date', $input['effective_date']);        
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                                'application_type_id' => $input["application_type_id"],
                                'range_from' => $input["range_from"],
                                'range_to' => $input["range_to"],
                                'rate' => $input["rate"],
                                'effective_date' => $input["effective_date"],
                                'emp_details_id' => $input["emp_details_id"]
                                 ]);
                            echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

     public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('application_type_id', $input['application_type_id']);
        $builder->where('range_from', $input['range_from']);
        $builder->where('range_to', $input['range_to']);
        $builder->where('rate', $input['rate']);
        $builder->where('effective_date', $input['effective_date']); 
        $builder->where('id!=', $input['id']);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

    public function updatedataById($input){
         $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([                                        
                                        'application_type_id' => $input["application_type_id"],
                                        'range_from' => $input["range_from"],
                                        'range_to' => $input["range_to"],
                                        'rate' => $input["rate"],
                                        'effective_date' => $input["effective_date"],
                                        'emp_details_id' => $input["emp_details_id"]
                                    ]);
                             //echo $this->db->getLastQuery();
                             return $builder;

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }
}