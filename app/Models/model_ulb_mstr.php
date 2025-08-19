<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Predis\Client;
use Exception;

class model_ulb_mstr extends Model 
{
    protected $db;
    protected $table = 'tbl_ulb_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'ulb_name', 'short_ulb_name', 'db_property', 'db_water', 'logo_path', 'watermark_path', 'status'];
    protected $redis_client;
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
        $this->redis_client = new Client();

    }

    public function getDbDetailsById($input){
        return $this->getModelUlbMstr();
        /* $builder = $this->db->table($this->table);
        $builder->select('id AS ulb_mstr_id, logo_path, watermark_path, db_property AS property, db_water AS water, db_trade AS trade, db_advertisement AS advertisement,state,district,city,ulb_type_id,ulb_name, description as ulb_name_hindi');
        $builder->where('id', $input['ulb_mstr_id']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0]; */
    }
    
    public function getULBDetailsByMD5Id($input){
        return $this->getModelUlbMstr();
        /* return $this->db->table($this->table)
                        ->select('id AS ulb_mstr_id, logo_path, watermark_path, db_property AS property, db_water AS water, db_trade AS trade, db_advertisement AS advertisement,state,district,city,ulb_type_id,ulb_name, description as ulb_name_hindi')
                        ->where('md5(id::text)', $input['ulb_mstr_id'])
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow("array"); */
    }

    public function getDbDetailsByIdToSelect($input){
        return $this->getModelUlbMstr();
        /* $builder = $this->db->table($this->table);
        $builder->select('id AS ulb_mstr_id, db_property AS property, db_water AS water, db_trade AS trade, db_advertisement AS advertisement,ulb_name');
        $builder->where('id', $input['ulb_mstr_id']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0]; */
    }

    public function getUlbList(){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('status', 1);
        $builder = $builder->get();
        return $builder->getResultArray();
    }
    public function getUlbListbyulbtype($ulb_type){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('status', 1);
        $builder->where('ulb_type_id', $ulb_type);
        $builder = $builder->get();
        return $builder->getResultArray();
    }
    public function ulb_list()
    {
       try{
            $builder = $this->db->table($this->table)
                        ->select('id,ulb_name')
                        ->where('status', 1)
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getulb_list($id){
        return $this->getModelUlbMstr();
        /* $builder = $this->db->table($this->table);
        $builder->select('id AS ulb_mstr_id, ulb_name, short_ulb_name, logo_path, watermark_path, description, city');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        //echo $this->db->getLastQuery();
        return $builder[0]; */
    }

    public function getAddressById($input){
        $ranchi_ulb_address = $this->redis_client->get("ranchi_ulb_address");
        if (!$ranchi_ulb_address) {
            $ranchi_ulb_address = $this->db->table($this->table)
                                    ->select('city, district, state')
                                    ->where('id', $input['ulb_mstr_id'])
                                    ->where('status', 1)
                                    ->get()
                                    ->getFirstRow("array");
            $this->redis_client->set("ranchi_ulb_address", json_encode($ranchi_ulb_address));
        } else {
            $ranchi_ulb_address = json_decode($ranchi_ulb_address, true);
        }
        return $ranchi_ulb_address;
    }
    
    
    public function getUlbMsrtDtlByMD5ID($id){
        return $this->getModelUlbMstr();
        /* $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        //echo $this->db->getLastQuery();
        return $builder[0]; */
    }

    public function getCity($id){
        try{        
            return $this->db->table($this->table)
                        ->select('city')
                        ->where('id',$id)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getULBById_MD5($ulb_mstr_id){
        try{        
            return $this->db->table($this->table)
                        ->select('*')
                        ->where('md5(id::text)', $ulb_mstr_id)
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getModelUlbMstr() {
        return [
            "ulb_mstr_id" => 1,
            "logo_path" => "/muncipalicon/RMC_LOGO.jpg",
            "watermark_path" => "/img/logo/1.png",
            "property" => "db_rmc_property",
            "water" => "db_rmc_water",
            "trade" => "db_rmc_trade",
            "advertisement" => "db_rmc_advertisement",
            "state" => "JHARKHAND",
            "district" => "RANCHI",
            "city" => "RANCHI",
            "ulb_type_id" => "1",
            "short_ulb_name" => "RMC",
            "ulb_name" => "Ranchi Municipal Corporation",
            "ulb_name_hindi" => "रांची नगर निगम"
        ];
    }
}