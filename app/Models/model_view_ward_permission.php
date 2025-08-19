<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_ward_permission extends Model 
{
    protected $db;
    protected $table = 'view_ward_permission';
    
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
        $this->ulb_mstr_id = 1;
    }

    public function getPermittedWard($id)
    {
        try  {
            $client = new \Predis\Client();
            //$client->del("get_permitted_ward".$id);
            $get_permitted_ward = $client->get("get_permitted_ward".$id);
            if (!$get_permitted_ward) {

                $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('emp_details_id', $id)
                        ->where('ulb_mstr_id', $this->ulb_mstr_id)
                        ->orderBy('ward_mstr_id', 'ASC')
                        ->get();
                $get_permitted_ward = $builder->getResultArray();
                $client->set("get_permitted_ward".$id, json_encode($get_permitted_ward));
                return $get_permitted_ward;
            } else {
                return json_decode($get_permitted_ward, true);
            }
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }

    public function getPermittedWardWithSession($id)
    {
        try  {
            $client = new \Predis\Client();
            $get_permitted_ward = $client->get("get_permitted_ward".$id);
            if (!$get_permitted_ward) {

                $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('emp_details_id', $id)
                        ->where('ulb_mstr_id', $this->ulb_mstr_id)
                        ->orderBy('ward_mstr_id', 'ASC')
                        ->get();
                $get_permitted_ward = $builder->getResultArray();
                $client->set("get_permitted_ward".$id, json_encode($get_permitted_ward));
                return $get_permitted_ward;
            } else {
                return json_decode($get_permitted_ward, true);
            }
        } catch(Exception $e) {
            return $e->getMessage();   
        }        
    }

    public function getPermittedWardUlb($id, $ulb_mstr_id) {
        try  {
            $builder = $this->db->table($this->table)
                    ->select('ward_mstr_id AS id, ward_mstr_id, ward_no')
                    ->where('emp_details_id', $id)
                    ->where('ulb_mstr_id', $ulb_mstr_id)
                    ->orderBy('ward_mstr_id', 'ASC')
                    ->get();
			//echo $this->db->getLastQuery();
            //exit;
            return $builder->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();   
        }
        
    }

    public function getPermittedWardG($id)
    {
        try  {
            $builder = $this->db->table($this->table)
                    ->select('ward_mstr_id, ward_no')
                    ->where('emp_details_id', $id)
                    ->where('ulb_mstr_id', $this->ulb_mstr_id)
                    ->orderBy('ward_mstr_id', 'ASC')
                    ->get();
			//echo $this->db->getLastQuery();exit;
            return $builder->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();   
        }
        
    }

    public function getGroupPermittedWard($id) {
        try {
            $builder = $this->db->table($this->table)
                    ->select('ward_mstr_id')
                    ->where('emp_details_id', $id)
                    ->where('ulb_mstr_id', $this->ulb_mstr_id)
                    ->groupBy('emp_details_id')
                    ->orderBy('ward_mstr_id', 'ASC')
                    ->get();
			echo $this->db->getLastQuery();
            die();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
        
    }
    
    public function getPermittedWardshow($id)
    {
       
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('ward_mstr_id')
                    ->where('emp_details_id',$id)
                    ->get();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
        
    }
}