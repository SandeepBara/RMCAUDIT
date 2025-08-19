<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class ModelWaterSurvey
{
    protected $db;
    protected $table = 'tbl_water_survey';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                ->insert($input);
            return $insert_id = $this->db->insertId();
        }
        catch(Exception $e)
        {
            echo($e->getMessage());
        }
       
    }

    public function updateData($input,$id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                ->where("id",$id)
                ->update($input);
            return $builder;
        }
        catch(Exception $e)
        {
            echo($e->getMessage());
        }
    }

    public function getSurveyDtl($consumerId)
    {
        $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->where('consumer_id',$consumerId)
                    ->get();
					//echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
    }
}