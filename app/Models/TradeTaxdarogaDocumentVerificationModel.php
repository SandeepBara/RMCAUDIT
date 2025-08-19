<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TradeTaxdarogaDocumentVerificationModel extends Model 
{
    protected $db;
    protected $table = 'tbl_taxdaroga_document_verification';
    protected $allowedFields = ['id','apply_licence_id','upload_doc_id','remarks','emp_details_id','created_on','status','verify_status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData($input){
        try
        {
            $builder = $this->db->table($this->table)
                ->insert([
                    "apply_licence_id" => $input['apply_licence_id'],
                    "upload_doc_id" => $input['document_id'],
                    "created_on" => $input['created_on'],
                    "emp_details_id" => $input['emp_details_id'], 
                    "remarks" => $input['remark']   
                ]);
                //echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
       
    } 
    public function insertDocumentData($data){
        try{
            $builder = $this->db->table($this->table)
            ->insert([
                    "apply_licence_id" => $data['apply_licence_id'],
                    "upload_doc_id" => $data['upload_doc_id'],
                    "created_on" => $data['created_on'],
                    "emp_details_id" => $data['emp_details_id'], 
                    "remarks" => $data['doc_remark'], 
                    "verify_status" => $data['verify_status']
                ]);
             /*echo $this->db->getLastQuery();*/
             return $insert_id = $this->db->insertId();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function getAllRemarks($upload_doc_id){
        try
        {
             $builder = $this->db->table($this->table)
                      ->select('remarks, verify_status')
                      ->where('upload_doc_id', $upload_doc_id)
                      ->where('status', 1)
                      ->get();
            //echo $this->db->getLastQuery();
            return  $builder = $builder->getFirstRow('array');
            
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    
}