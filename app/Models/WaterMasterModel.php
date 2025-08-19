<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterMasterModel extends Model
{

    protected $table = 'tbl_document_mstr';
    protected $allowedFields = ['document_name'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function document_list()
    {

    	$result=$this->db->table($this->table)
    				->select('*')
    				->where('status',1)
    				->get()
    				->getResultArray();

    	return $result;
    }

    public function checkdata($document_name,$document_for)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('status',1)
                    ->where('document_for',$document_for)
                    ->where('document_name',$document_name)
    				->get()
    				->getFirstRow("array");

    				//echo ($result['id']);
    		//	echo $this->getLastQuery();
    	return $result['id'];
    }
    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
               //  echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function deleteData($id)
    {
    	$sql="delete from tbl_document_mstr where id=".$id;
    	$this->query($sql);

    }
    public function getData($id)
    {
    	$result=$this->db->table($this->table)
    				->select('*')
    				->where('status',1)
                    ->where('md5(id::text)',$id)
    				->get()
    				->getFirstRow("array");

                    //echo $this->getLastQuery();
    	return $result;

    }
    public function updateData(array $data)
    {

        $sql="update tbl_document_mstr set doc_for='".$data['doc_for']."', document_name='".$data['document_name']."' where md5(id::text)='".$data['id']."'";

        $this->query($sql);
        //echo $this->getLastQuery();
    }
}