<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class ModelHDFCOnlineResponse extends Model
{
    protected $table = 'tbl_hdfc_response';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function insertData(array $data)
    {
        $this->db->table($this->table)
                ->insert($data);
        // echo $this->db->getLastQuery();die;
        $insert_id=$this->db->insertID();
        return $insert_id;
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
}