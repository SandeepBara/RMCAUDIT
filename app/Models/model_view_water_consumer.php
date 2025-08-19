<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_water_consumer extends Model
{
    protected $db;
    protected $table = 'view_consumer';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function consumerListByDate($data)
    {
        try{
                $sql="select * from view_consumer where created_on::date between '".$data['from_date']."' and '".$data['to_date']."'";

               $run=$this->db->query($sql);
               $result=$run->getResultArray();
               //echo $this->getLastQuery();
               return $result; 
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    public function consumerList($data)
    {
        try{
                $sql="select * from view_consumer where created_on::date between '".$data['from_date']."' and '".$data['to_date']."'and ward_mstr_id='".$data['ward_mstr_id']."'";

               $run=$this->db->query($sql);
               $result=$run->getResultArray();
               //echo $this->getLastQuery();
               return $result;

        }catch(Exception $e){
            echo $e->getMessage();
        }
       
    }
    public function waterConsumerDetailsById($id){
        try{
             $builder=$this->db->table($this->table)
                           ->select('*')
                           ->where('md5(id::text)',$id)
                           ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array'); 
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        } 
    }

    public function waterConsumerLists($where)
    {
        try
        {
           $sql="select id,ward_no,consumer_no,pipeline_type,property_type,connection_type,connection_through,  
                    category,owner_name,mobile_no,address,holding_no,saf_no  
                from view_consumer 
                left join (
                                select consumer_id,string_agg(applicant_name,',') as owner_name, string_agg(mobile_no::text,',') as mobile_no 
                                from tbl_consumer_details 
                                where status = 1
                                group by consumer_id
                            ) as owner on owner.consumer_id=view_consumer.id 
                    where ".$where;

            $run=$this->db->query($sql);
            $result=$run->getResultArray();
            // print_var($sql);
            return $result;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        } 
    }

    public function getholding_no($consumr_id)
    {
        $data=$this->db->table('tbl_consumer')
            ->select(('holding_no'))
            ->where('id',$consumr_id)
            ->get()
            ->getFirstRow('array');
            return $data;
    }

    public function getConsumerByMd5Id($consumr_id)
    {

        $data=$this->db->table('tbl_consumer')
            ->select('*')
            ->where('md5(id::text)',$consumr_id)
            ->get()
            ->getFirstRow('array');
            return $data;
    }

    public function update_consume($consumr_id,$inputs)
    {
        try
        {
            $result = $this->db->table('tbl_consumer')
                ->where('id', $consumr_id)
                ->update($inputs);
            // echo $this->db->getLastQuery();
            return $result;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die();
            return false;
        }
    }

    public function insert_owner($inputs)
    {
        try
        {
            $this->db->table('tbl_consumer_details')
                    ->insert($inputs);
                
            return $this->db->insertID()??false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
    public function update_owner($id,$inputs)
    {
        try
        {
            $result = $this->db->table('tbl_consumer_details')
                   ->where('id',$id)
                   ->update($inputs);
            //echo $this->db->getLastQuery();
            return $result;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

    public function GetExisting_consumer($consumr_id)
    {
        try{
            $builder=$this->db->table($this->table)
                          ->select('*')
                          ->where('md5(id::text)',$consumr_id)
                          ->where('apply_from','Existing')
                          ->get();
           //echo $this->db->getLastQuery();
           return $builder->getFirstRow('array'); 
       }
       catch(Exception $e)
       {
           echo $e->getMessage();
       } 
    }

    public function get_document_type()
    {
        try{
            $builder=$this->db->table('tbl_document_mstr')
                          ->select('distinct(doc_for) as doc_for ')
                          ->where('status',1)
                          ->get();
           //echo $this->db->getLastQuery();
           return $builder->getResultArray(); 
       }
       catch(Exception $e)
       {
           echo $e->getMessage();
       } 
    }

    public function get_document_name($doc_for)
    {
        try{
            $builder=$this->db->table('tbl_document_mstr')
                          ->select('*')
                          ->where('status',1)
                          ->where('doc_for',$doc_for)
                          ->get();
           //echo $this->db->getLastQuery();
           return $builder->getResultArray(); 
       }
       catch(Exception $e)
       {
           echo $e->getMessage();
       } 
    }
    

}
?> 