<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class tradeitemsmstrmodel extends Model 
{
    protected $db;
    protected $table = 'tbl_trade_items_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'trade_code', 'trade_item','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }    
    public function gettradeitemsList(){        
         try
        {
            $client = new \Predis\Client();
            //$client->del("get_ward_list");
            $get_trade_items_list = $client->get("get_trade_items_list");
            if (!$get_trade_items_list) {
               $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('status',1)
                            ->orderBy('id', 'desc')                   
                            ->get();
                        //echo $this->db->getLastQuery();exit;
                $get_trade_items_list = $builder->getResultArray();
                $client->set("get_trade_items_list", json_encode($get_trade_items_list));
                return $get_trade_items_list;
            } else {
                return json_decode($get_trade_items_list, true);
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('trade_code', $input['trade_item']);
        $builder->where('trade_item', $input['trade_item']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
    public function gettradeitems($id){  
         
        try
       {
           $builder = $this->db->table($this->table)
                   ->select('*')
                   ->where('status',1)
                   ->where('id in ('.$id.')')
                   ->orderBy('id', 'desc')                   
                   ->get();
                  // echo $this->db->getLastQuery();
           return $result = $builder->getResultArray();
       }
       catch(Exception $e)
       {
           echo $e->getMessage();
       }
   }


    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "trade_code"=>$input["trade_code"],
                  "trade_item"=>$input["trade_item"]
                  ]);
                            //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function getIdByitem($trade_item){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('trade_item',$trade_item)
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

     public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,trade_code,trade_item,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('trade_code', $input['trade_code']);
        $builder->where('trade_item', $input['trade_item']);
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
                                    'trade_code'=>$input['trade_code'],
                                    'trade_item'=>$input['trade_item']
                                    ]);

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }
    public function getTradeItemDetails($tradeItemId){
        $sql = "SELECT *
                FROM tbl_trade_items_mstr
                WHERE id IN ($tradeItemId)";
                $ql= $this->db->query($sql);
              return $result =$ql->getResultArray();
                
    }
	
	
	public function tradedetail($tradeItemId)
    {
        $sql = "SELECT *
                FROM tbl_trade_items_mstr
                WHERE id IN ($tradeItemId)";
                $ql= $this->query($sql);
            return $result =$ql->getResultArray();
    }
	
	public function get_tobocoItem()
    {
        try{
              return $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->where('trade_code','185')
                        ->get()
                        ->getResultArray()[0];
						//echo $this->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}