<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_doc_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_doc_dtl';

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input)
    {
        try
        {
            $this->db->table($this->table)
                                ->insert($input);
            echo $this->db->getLastQuery();
            return $this->db->insertID();
        } catch(Exception $e) {

        }
    }

    public function VerifyDocument($doc_dtl_id, $input)
    {
        $this->db->table($this->table)
                ->where('id', $doc_dtl_id)
                ->update([
                            'verify_status'=> $input['verify_status'],
                            'remarks' => $input['remarks'],
                            'verified_by_emp_details_id' => $input['verified_by_emp_details_id'],
                            'verification_datetime' => 'Now()',
                            'verified_ip_address' => NULL,
                        ]);
    }

    public function updateFileName($id,$file_name)
    {
        $sql="update tbl_govt_saf_doc_dtl set file_name='$file_name' where id=$id";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
    }
	
    public function checkExists($govt_saf_dtl_id, $document_name='Application Form')
    {
        $sql="select id from tbl_govt_saf_doc_dtl where govt_saf_dtl_id='$govt_saf_dtl_id' and document_name='$document_name' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        //echo $this->getLastQuery();
        return $result['id'];
    }

    public function checkAlreadyUploaded($govt_saf_dtl_id, $document_name)
    {
        $sql="select id from tbl_govt_saf_doc_dtl where govt_saf_dtl_id='$govt_saf_dtl_id' and document_name='$document_name' and status=1 and verify_status=0;";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        //echo $this->getLastQuery();
        return $result['id'];
    }

    public function deactivateRejectedDocument($gov_saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                        ->where('govt_saf_dtl_id', $gov_saf_dtl_id)
                        ->where('verify_status', 2)
                        ->update([
                                    'status'=> 0,
                                ]);
        return $builder;
    }

    public function getDocumentDetails($govt_saf_dtl_id)
    {
        $sql="select * from tbl_govt_saf_doc_dtl where md5(govt_saf_dtl_id::text)='$govt_saf_dtl_id' and document_name like '%Application Form%' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        //echo $this->getLastQuery();
        return $result;
    }


    public function getAllDocuments($govt_saf_dtl_id)
    {
        $sql="select * from tbl_govt_saf_doc_dtl where md5(govt_saf_dtl_id::text)='$govt_saf_dtl_id' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

	
}
?>
