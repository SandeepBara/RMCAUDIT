<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class tradefirmtypemstrmodel extends Model 
{
    protected $db;
    protected $table = 'tbl_firm_type_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'firm_type','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }    
    public function getFirmTypeList(){        
         try
        {
            $client = new \Predis\Client();
            //$client->del("get_ward_list");
            $get_trade_firm_type_list = $client->get("get_trade_firm_type_list");
            if (!$get_trade_firm_type_list) {
               $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('status',1)
                            ->orderBy('id', 'asc')                   
                            ->get();
                        //echo $this->db->getLastQuery();exit;
                $get_trade_firm_type_list = $builder->getResultArray();
                $client->set("get_trade_firm_type_list", json_encode($get_trade_firm_type_list));
                return $get_trade_firm_type_list;
            } else {
                return json_decode($get_trade_firm_type_list, true);
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getIdByfirmtype($firm_type){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('firm_type',$firm_type)
                    ->where('status',1)                                       
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('firm_type', $input['firm_type']);
        $builder->where('status', 1)
                ->orderBy('id','desc');
        $builder = $builder->get();
        $builder = $builder->getFirstRow('array');
        return $builder;

    }

    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "firm_type"=>$input["firm_type"]
                  ]);
                            //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

     public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,firm_type,status');
        $builder->where('id', $id);
        $builder->where('status', 1)
                ->orderBy('id','desc');
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getFirstRow('array');
        
        return $builder;

    }    

    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('firm_type', $input['firm_type']);
        $builder->where('id!=', $input['id']);
        $builder->where('status', 1)
                ->orderBy('id','desc');
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getFirstRow('array');
        return $builder;

    }

    public function updatedataById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'firm_type'=>$input['firm_type']
                                    ]);

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }
}