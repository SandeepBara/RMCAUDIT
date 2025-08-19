<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_arr_building_mstr extends Model 
{
    protected $table = 'tbl_arr_building_mstr';
    protected $allowedFields = ['id', 'road_type_mstr_id','const_type_mstr_id', 'given_rate', 'date_of_effect','cal_rate','status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData(array $data){
        $builder = $this->db->table($this->table)
                            ->insert($data);
        return $insert_id = $this->db->insertID();
    }
    public function checkdata($road_type_mstr_id,$const_type_mstr_id)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, road_type_mstr_id,const_type_mstr_id')
                        ->where('status', 1)
                        ->where('road_type_mstr_id', $road_type_mstr_id)
                        ->where('const_type_mstr_id', $const_type_mstr_id)
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function areaBuildingList()
    {
        try
        {
            $builder = $this->db->table('view_arr_building_const_type_road_type_mstr')
                        ->select('*')
                        ->where('status', 1)
                        ->orderBy('date_of_effect ASC')
                        ->orderBy('const_type_mstr_id ASC')
                        ->get();
            //echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }
        catch(Exception $e)
        {
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
    public function checkupdatedata($id,$road_type_mstr_id,$const_type_mstr_id)
    {
        try{
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('road_type_mstr_id', $road_type_mstr_id);
        $builder->where('const_type_mstr_id', $const_type_mstr_id);
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
        /*print_r($input);
        die();*/
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'road_type_mstr_id'=>$input['road_type_mstr_id'],
                                    'const_type_mstr_id'=>$input['const_type_mstr_id'],
                                    'given_rate' =>$input['rate'],
                                    'cal_rate' =>$input['cal_rate']
                                    ]);
    }
     public function deleteAreaBuilding($id){
        return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    'status'=>0
                                    ]);

    }
    public function getMRRCalRate($input){
        try{
           return  $this->db->table($this->table)
                        ->select('id, cal_rate')
                        ->where('road_type_mstr_id', $input['road_type_mstr_id'])
                        ->where('const_type_mstr_id', $input['const_type_mstr_id'])
                        ->where('date_of_effect <=', $input['date_of_effect'])
                        ->where('status', 1)
                        ->orderBy('date_of_effect', 'ASC')
                        ->get()
                        ->getResultArray()[0];
           //echo  $this->db->getLastQuery();die;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getJoinRateByRoadConsType($input){
        try{
            return $this->db->table("tbl_arr_building_mstr")
                        ->select('tbl_arr_building_mstr.cal_rate AS cal_rate,
                                tbl_const_type_mstr.construction_type AS construction_type,
                                tbl_road_type_mstr.road_type AS road_type'        
                        )
                        ->join('tbl_const_type_mstr', 'tbl_const_type_mstr.id=tbl_arr_building_mstr.const_type_mstr_id')
                        ->join('tbl_road_type_mstr', 'tbl_road_type_mstr.id=tbl_arr_building_mstr.road_type_mstr_id')
                        ->where('tbl_arr_building_mstr.const_type_mstr_id', $input['const_type_mstr_id'])
                        ->where('tbl_arr_building_mstr.status', 1)
                        ->where('tbl_const_type_mstr.status', 1)
                        ->where('tbl_road_type_mstr.status', 1)
                        ->where('tbl_arr_building_mstr.date_of_effect', '2016-04-01')
                        ->orderBy('tbl_road_type_mstr.id', 'ASC')
                        ->get()
                        ->getResultArray();
         //  echo  $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function insertFixedArrData($road_type_mstr_id,$const_type_mstr_id,$cal_rate,$date_of_effect,$amount)
    {
        $builder = $this->db->table($this->table)
                            ->insert([
                                "road_type_mstr_id" => $road_type_mstr_id,
                                "const_type_mstr_id" =>$const_type_mstr_id,
                                "given_rate" => $amount,
                                "cal_rate" => $cal_rate,
                                "date_of_effect"=> $date_of_effect,
                                "status"=> 1
                            ]);
        //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
}
