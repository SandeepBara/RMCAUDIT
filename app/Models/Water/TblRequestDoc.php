<?php 
namespace App\Models\Water;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TblRequestDoc  extends Model
{
    protected $db;
    protected $table = 'tbl_request_doc';
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

    public function getAllActiveDocuments($requiest_id)
    {
        // $builder = $this->db->table($this->table)
        //                     ->select("$this->table.*, coalesce($this->table.doc_for, tbl_document_mstr.doc_for) as doc_name, tbl_document_mstr.document_name")
        //                     ->join("tbl_document_mstr", "tbl_document_mstr.id = $this->table.document_id", "left")
        //                     ->where('request_id', $requiest_id)
        //                     ->where("$this->table.status", 1)
        //                     ->get();
        $builder= $this->db->query("
                        SELECT $this->table.*, coalesce($this->table.doc_for, tbl_document_mstr.doc_for) as doc_name,
                            CASE WHEN tbl_request_doc.document_id is not null  then tbl_document_mstr.document_name else tbl_request_doc.doc_for end as document_name
                        FROM $this->table
                        LEFT JOIN tbl_document_mstr ON tbl_document_mstr.id = $this->table.document_id
                        WHERE $this->table.request_id =$requiest_id
                            AND $this->table.status =1
                        ");
        return $builder->getResultArray();
    }

    public function check_doc_exist($apply_connection_id, $doc_for,$mywoner=array())
    {
        try
        {   if(count($mywoner)==0)
            {
             $builder = $this->db->table($this->table)
                        ->select('*, document_path as doc_path')
                        ->where('request_id', $apply_connection_id)
                        ->where('doc_for', $doc_for)
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow('array');
            }
            else
            {
                $builder = $this->db->table($this->table)
                        ->select('*, document_path as doc_path')
                        ->where('request_id', $apply_connection_id)
                        ->where('doc_for', $doc_for)
                        ->where('status', 1)
                        ->where($mywoner)
                        ->get()
                        ->getFirstRow('array');
            }
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
}