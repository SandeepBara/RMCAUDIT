<?php 
namespace App\Models\Water;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Integer;

class TblConsumerRequestDtl extends Model
{
    protected $db;
    protected $table = 'tbl_consumer_request_dtls';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function store(array $data)
    {
        $this->db->table($this->table)
                ->insert($data);
        $insert_id=$this->db->insertID();
        return $insert_id;
    }

    public function updateData(int $id,array $data){
        return $this->db->table($this->table)->where("id",$id)->update($data);
    }

    public function getAllData(){
        return self::select("*")->where("status",1)->get()->getResultArray();
    }
}