<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_ward_mstr extends Model
{
    protected $db;
    protected $table = 'tbl_ward_mstr';
    protected $allowedFields = ['id', 'ward_no', 'status', 'ulb_mstr_id'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
        //$Session = Session();
        //$ulb = $Session->get('ulb_dtl');
        $this->ulb_mstr_id=1;//$ulb["ulb_mstr_id"];
    }

    public function getWardListOnlyDigit($input){
        try{
            $client = new \Predis\Client();
            $get_ward_list_only_digit = $client->get("get_ward_list_only_digit");
            if (!$get_ward_list_only_digit) {
                $builder = $this->db->table($this->table)
                            ->select('id, id as ward_mstr_id, ward_no')
                            ->where('ulb_mstr_id', $input['ulb_mstr_id'])
                            ->where("ward_no ~ '^\d+$'")
                            ->where('status', 1)
                            ->orderBy("(substring(ward_no, '^[0-9]+'))::int,ward_no")
                            ->get();
                            //echo $this->db->getLastQuery();exit;
                $get_ward_list_only_digit = $builder->getResultArray();
                $client->set("get_ward_list_only_digit", json_encode($get_ward_list_only_digit));
                return $get_ward_list_only_digit;
            } else {
                return json_decode($get_ward_list_only_digit, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getWardListOnlyDigitEmpPermitted($input){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, id as ward_mstr_id, ward_no')
                        ->where('ulb_mstr_id', $input['ulb_mstr_id'])
                        ->where("ward_no ~ '^\d+$'")
                        ->where("id IN (".$input['emp_pemitted_ward_list'].")")
                        ->where('status', 1)
                        ->orderBy("(substring(ward_no, '^[0-9]+'))::int,ward_no")
                        ->get();
                        //echo $this->db->getLastQuery();exit;
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getWardList($input){
        try{
            $client = new \Predis\Client();
            //$client->del("get_ward_list");
            $get_ward_list = $client->get("get_ward_list");
            if (!$get_ward_list) {
               $builder = $this->db->table($this->table)
                        ->select('id, id as ward_mstr_id, ward_no')
                        ->where('ulb_mstr_id', $input['ulb_mstr_id'])
                        ->where('status', 1)
                        ->orderBy(" (substring(ward_no, '^[0-9]+'))::int,ward_no")
                        ->get();
                        //echo $this->db->getLastQuery();exit;
                $get_ward_list = $builder->getResultArray();
                $client->set("get_ward_list", json_encode($get_ward_list));
                return $get_ward_list;
            } else {
                return json_decode($get_ward_list, true);
            }
        }catch(Exception $e){
            return $e->getMessage();   
        }
        /*try{
            $builder = $this->db->table($this->table)
                        ->select('id, id as ward_mstr_id, ward_no')
                        ->where('ulb_mstr_id', $input['ulb_mstr_id'])
                        ->where('status', 1)
                        ->orderBy(" (substring(ward_no, '^[0-9]+'))::int,ward_no")
                        ->get();
                        //echo $this->db->getLastQuery();exit;
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }*/
    }
    
    public function getWardListWithSession($input, $session = null){
        try {
            $client = new \Predis\Client();
            $allwardlist = $client->get("allwardlist");
            if (!$allwardlist) {
                $builder = $this->db->table($this->table)
                            ->select('id, id as ward_mstr_id, ward_no')
                            ->where('ulb_mstr_id', $input['ulb_mstr_id'])
                            ->where('status', 1)
                            ->orderBy(" (substring(ward_no, '^[0-9]+'))::int,ward_no")
                            ->get();
                            //echo $this->db->getLastQuery();exit;
                $allwardlist = $builder->getResultArray();
                $client->set("allwardlist", json_encode($allwardlist));
                return $allwardlist;
            } else {
                return json_decode($allwardlist, true);
            }
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }

    public function getIdBywardno($ward_no){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('ward_no', $ward_no)
                    ->where('status', 1)                                       
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getWardNoById($input){
        try{
            $builder = $this->db->table($this->table)
                        ->select('ward_no')
                        //->where('ulb_mstr_id', $input['ulb_mstr_id'])
                        ->where('id', $input['ward_mstr_id'])
                        ->where('status', 1)
                        ->get();
           return $builder->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function getWardNoBywardId($ward_mstr_id){
        try{
            $builder = $this->db->table($this->table)
                        ->select('ward_no')
                        ->where('id', $ward_mstr_id)
                        ->where('status', 1)
                        ->get();
           return $builder->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    
    public function ward_list($ulb_mstr_id,$data)
    {      
      try{
        
            $builder = $this->db->table($this->table)
                        ->select('id, ward_no')
                        ->where('status', 1)
                        ->where('ulb_mstr_id', $ulb_mstr_id)
                        ->where('id', $data["ward_mstr_id"])
                        ->get();
            return $builder->getResultArray()[0];


        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function insertData(array $data)
    {
        $builder = $this->db->table($this->table)
                            ->insert($data);
        return $insert_id = $this->db->insertID();
    }
    public function checkdata($ward_no,$ulb_mstr_id)
    {
        try{
        
            $builder = $this->db->table($this->table)
                        ->select('id, ward_no,ulb_mstr_id')
                        ->where('status', 1)
                        ->where('ward_no', $ward_no)
                        ->where('ulb_mstr_id', $ulb_mstr_id)
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function allWardList($input=array())
    {
        try{
            
            $builder = $this->db->table('wardlist')
                        ->select('*')
                        ->where('ulb_mstr_id', $input["ulb_mstr_id"] ?? 1)
                        ->where('status',1)
                        ->orderBy('id','ASC')
                        ->get();
                       // echo $this->getLastQuery();
           return $builder->getResultArray();
            //->paginate(2);

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
                        ->where('id', $id)
                        ->get();
           //echo $this->getLastQuery(); exit;
           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function deleteward($id){
        return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    'status'=>0
                                    ]);
    }
    public function checkupdatedata($input)
    {
        try{
        
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('ward_no', $input['ward_no']);
        $builder->where('ulb_mstr_id', $input['ulb_mstr_id']);
        $builder->where('id!=', $input['id']);
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
                                    'ward_no'=>$input['ward_no'],
                                    'ulb_mstr_id'=>$input['ulb_mstr_id']
                                    ]);
    }
	public function get_ward_list()
    {
        $sql = "SELECT * FROM tbl_ward_mstr";
        $ql= $this->query($sql);
        $result =$ql->getResultArray();
        return $result;
    }
    public function ajax_wardList($ulb)
    {
         try{
        
            $builder = $this->db->table($this->table)
                        ->select('id,ward_no')
                        ->where('status',1)
                        ->where('ulb_mstr_id',$ulb)
                        ->get();
                        //echo $this->db->getLastQuery();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	public function getWardListForReport($ulb)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('id,ward_no')
                        ->where('status', 1)
                        ->where('ulb_mstr_id', $ulb)
                        ->orderBy('id','ASC')
                        ->get();
                       // echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getWardByIdWithUlbId($ward_mstr_id)
    {
        try
        {        
            $builder = $this->db->table($this->table)
                        ->select('ward_no')
                        ->where('id',$ward_mstr_id)
                        ->where('status',1)
                        ->get();
                      // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['ward_no'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    } 
    public function getPermittedWard($wardPermissioin,$ulb_mstr_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id,ward_no')
                      ->WhereIn('id',$wardPermissioin)
                      ->Where('ulb_mstr_id',$ulb_mstr_id)
                      ->where('status',1)
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getWardNoByOldWardId($old_ward_mstr_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id, ward_no')
                      ->Where('id', $old_ward_mstr_id)
                      ->get();
            //echo $this->db->getLastQuery();
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getWardNoByOldWardNo($old_ward_mstr_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id, ward_no')
                      ->where('ward_no', $old_ward_mstr_id)
                      ->where('ulb_mstr_id', $this->ulb_mstr_id)
                      ->get();
            //print_var($this->db->getLastQuery());
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
?>