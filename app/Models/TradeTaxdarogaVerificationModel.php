<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TradeTaxdarogaVerificationModel extends Model 
{
    protected $db;
    protected $table = 'tbl_taxdaroga_verification';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','apply_licence_id','area_in_sqft','emp_details_id','created_on','status','remarks'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                ->insert([
                    "apply_licence_id" => $input['apply_licence_id'],
                    "emp_details_id" => $input['emp_details_id'],
                    "created_on" => $input['created_on'],
                    "area_in_sqft" => $input['area_in_sqft'],  
                    "remarks" => $input['remarks']   
                ]);
               /*echo $this->db->getLastQuery();*/
            return $insert_id = $this->db->insertId();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
        
    }

    public function siteInspectionRemarks($apply_licence_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('apply_licence_id', $apply_licence_id)
                      ->where('status', 1)
                      ->get();
                    // echo  $this->db->getLastQuery();
            return $builder = $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function getTaxDarogaVerification($apply_licence_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('apply_licence_id',$apply_licence_id)
                      ->where('status',1)
                      ->get();
                    //  echo  $this->db->getLastQuery();
            return $builder = $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
}