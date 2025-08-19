<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TradeSiteInspectionMessageModel extends Model 
{
    protected $db;
    protected $table = 'tbl_site_inspection_message';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','apply_licence_id','emp_details_id','created_on','forward_date','forward_time','status','cancel_date','cancel_by'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertMessageData($data){
        try
        {
            $builder = $this->db->table($this->table)
                ->insert([
                    "apply_licence_id" => $data['apply_licence_id'],
                    "forward_date" => $data['forward_date'],
                    "forward_time" => $data['forward_time'],
                    "emp_details_id" => $data['emp_details_id'],
                    "created_on" => $data['created_on']
                ]);
                /*echo $this->db->getLastQuery();*/
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
       
    }
    
    public function get_date_time($login_emp_details_id,$apply_licence_id)
    {
         try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('emp_details_id',$login_emp_details_id)
                      ->where('apply_licence_id',$apply_licence_id)
                      ->where('id = (select max(id) from tbl_site_inspection_message where apply_licence_id = '.$apply_licence_id.' and emp_details_id = '.$login_emp_details_id.')')
                      ->where('status',1)
                      ->get();
                     // echo $this->db->getLastQuery();
                      return $builder=$builder->getFirstRow('array');
                    }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getDetails($apply_licence_id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('id,forward_date,forward_time')
                     ->where('md5(apply_licence_id::text)',$apply_licence_id)
                     ->where('status',1)
                     ->get();
            return $builder=$builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function GetSiteInspectionId($apply_licence_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id as site_Inspection_Id')
                      ->where('apply_licence_id', $apply_licence_id)
                      ->where('status', 1)
                      ->get();
                      /*echo $this->db->getLastQuery();*/
            $builder = $builder->getFirstRow('array');
            return $builder['site_Inspection_Id'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function cancelSiteInspectionMessage($data){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('md5(apply_licence_id::text)',$data['apply_licence_id']) 
                            ->update([
                                        'cancel_date' =>$data['cancel_date'],
                                        'cancel_by' =>$data['cancel_by'],
                                        'status' => 0
                                    ]);
                            /*echo $this->db->getLastQuery();*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getDoublePostingData($apply_licence_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id')
                      ->where('apply_licence_id',$apply_licence_id)
                      ->where('status',1)
                      ->get();
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getCancelMessageData($apply_licence_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('id')
                      ->where('md5(apply_licence_id::text)',$apply_licence_id)
                      ->where('status', 1)
                      ->orderBy('id', 'DESC')
                      ->limit(1)
                      ->get();
                      /*echo $this->db->getLastQuery();*/
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
}