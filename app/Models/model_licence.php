<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_licence extends Model 
{
    protected $table = 'tbl_apply_licence';
    protected $allowedFields = [];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    } 
    public function gatedeactiveLicence($frm_date,$to_date){
        $sql = "SELECT count(status) AS deactive
		FROM tbl_apply_licence
		where status=0 and created_on::date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
	
	public function gateactiveLicence($frm_date,$to_date){
        $sql = "SELECT count(status) AS active
		FROM tbl_apply_licence
		where status=1 and created_on::date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
	
	public function gatesurrenderLicence($frm_date,$to_date){
        $sql = "SELECT count(status) AS surrender
		FROM tbl_apply_licence
		where status=2 and created_on::date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
	
	public function gatedebardLicence($frm_date,$to_date){
        $sql = "SELECT count(status) AS debard
		FROM tbl_apply_licence
		where status=3 and created_on::date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
	
	public function gatecancelLicence($frm_date,$to_date){
        $sql = "SELECT count(status) AS cancel
		FROM tbl_apply_licence
		where status=4 and created_on::date BETWEEN'".$frm_date."' and '".$to_date."'";
        $ql= $this->db->query($sql);
        $result =$ql->getResultArray()[0];
        return $result;
    }
    
}
