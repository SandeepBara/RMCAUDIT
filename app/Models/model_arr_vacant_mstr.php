<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_arr_vacant_mstr extends Model 
{
    protected $table = 'tbl_arr_vacant_mstr';
    protected $allowedFields = ['id','road_type_mstr_id','rate','date_of_effect'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData(array $data){
        $builder = $this->db->table($this->table)
                            ->insert($data);
        return $insert_id = $this->db->insertID();
    }
    public function checkdata($road_type_mstr_id)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, road_type_mstr_id')
                        ->where('status', 1)
                        ->where('road_type_mstr_id', $road_type_mstr_id)
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function arrVacantList()
    {
        try{
        
            $builder = $this->db->table('arrvacantlist')
                        ->select('*')
                        ->where('status',1)
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getdatabyid($id)
    {
        try{
        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->where('id',$id)
                        ->get();
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function checkupdatedata($id,$road_type_mstr_id)
    {
        try{
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('road_type_mstr_id', $road_type_mstr_id);
        $builder->where('id!=', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updatedataById($input)
    {
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'road_type_mstr_id'=>$input['road_type_mstr_id'],
                                    'rate' =>$input['rate']
                                    ]);
    }
     public function deleteAreaVacant($id){
        return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    'status'=>0
                                    ]);

    }
    public function getMRRCalRate($input){
        try{
            return $this->db->table($this->table)
                        ->select('rate')
                        ->where('road_type_mstr_id', $input['road_type_mstr_id'])
                        ->where('date_of_effect <=', $input['date_of_effect'])
                        ->where('status', 1)
                        ->orderBy('date_of_effect', 'ASC')
                        ->get()
                        ->getFirstRow("array");
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getJoinVacantLandRateByRoadType(){
        try{
            return $this->db->table("tbl_arr_vacant_mstr")
                        ->select('tbl_road_type_mstr.road_type, 
                                    tbl_arr_vacant_mstr.rate AS rate'  
                        )
                        ->join('tbl_road_type_mstr', 'tbl_road_type_mstr.id=tbl_arr_vacant_mstr.road_type_mstr_id')
                        ->where('tbl_arr_vacant_mstr.status', 1)
                        ->orderBy('tbl_road_type_mstr.id', 'ASC')
                        ->get()
                        ->getResultArray();
           //echo  $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
