<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class ModelThirdPartyTradeOnlineRequest extends Model
{
    protected $table = 'tbl_third_party_request';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function insertData(array $data)
    {
        try{
            
        $this->db->table($this->table)
                ->insert($data);
        // echo $this->db->getLastQuery();die;
        $insert_id=$this->db->insertID();
        return $insert_id;
        }
        catch(Exception $e)
        {
            print_var("hear");
            print_var($e->getMessage());
            die;
        }
    }

    public function updateData( $id,array $inputs)
    {
        try{
            $data = $this->db->table($this->table);
            if(!is_numeric($id))
            {
                $data = $data->where("MD5(id::text)",$id);
            }
            else
            {
                $data = $data->where("id",$id);
            }
            $data = $data->update($inputs);
            return $data;
                
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            
        }
    }

    public function getDataById($id)
    {
        $data = $this->db->table($this->table)
                ->select('*');
        if(!is_numeric($id))
        {
            $data = $data->where("MD5(id::text)",$id);
        }
        else
        {
            $data = $data->where("id",$id);
        }
        $data = $data->get()
            ->getFirstRow('array');
        return $data; 
        
    }

    public function row_sql($sql)
    {
        try
        {
            $data = $this->db->query($sql)->getResultArray();
            // echo $this->db->getLastQuery(); 
            // print_var($data);
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    } 

    public function getRequestDataByOderId($OderId)
    {
        try{
            $bilder = $this->db->table($this->table)
                    ->select("*")
                    ->where("order_id",$OderId)
                    ->where("status",2)
                    ->orderBy("id","DESC")
                    ->get()
                    ->getFirstRow("array");
            return $bilder;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }
}