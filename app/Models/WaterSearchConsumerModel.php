<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterSearchConsumerModel extends Model
{
    protected $db;
    protected $table = 'view_water_application_details';
   
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }
    
    public function getWardList($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('id, ward_no')
                        ->where('ulb_mstr_id', $input['ulb_mstr_id'])
                        ->where('apply_from !=', 'Existing')
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();
           return $builder->getResultArray();

          
           

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

   public function fetch_consumer_details($where)
   { 
        $sql="select id,ward_no,application_no,pipeline_type,property_type,connection_type,connection_through,category,apply_date,applicant_name,mobile_no 
        from view_water_application_details where apply_from!='Existing' and ".$where;

        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->db->getLastQuery();
        //print_var($this->db);
        return $result;

   }

   public function dashbord_sql($select='*',$table,$where=array(),$join=null,$ordr_by=null)
   {
        $result=$this->db->table($table)
                ->select($select)
                ->where($where)
                ->get()
                ->getResultArray();
        return $result;

   }
}
?>