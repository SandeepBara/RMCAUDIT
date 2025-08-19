<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_saf_tax extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_tax';
    protected $allowedFields = ['id', 'saf_dtl_id', 'fy_mstr_id', 'qtr', 'arv', 'holding_tax', 'water_tax', 'education_cess', 'health_cess', 'latrine_tax', 'created_on', 'status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData(array $data){
        $this->db->table($this->table)
                            ->insert($data);
        //echo $this->db->getLastQuery();                            
        return $this->db->insertID();
    }

     public function getDataBySafDtlId_md5($saf_dtl_id){
        try{
            return $this->db->table($this->table)
                            ->select('*')
                            ->where('md5(saf_dtl_id::text)', $saf_dtl_id)
                            ->where('status', 1)                            
                            ->get()
                            ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getJoinBySafDtlIdMd5($saf_dtl_id){
        try{
            $sql = "SELECT
                        tbl_saf_tax.*,
                        view_fy_mstr.fy
                    FROM tbl_saf_tax
                    INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_saf_tax.fy_mstr_id
                    WHERE md5(tbl_saf_tax.saf_dtl_id::TEXT)='".$saf_dtl_id."';";
            $query = $this->db->query($sql);
            return $query->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getsaftaxbysafId($saf_dtl_id)
    {
        $sql = "SELECT * FROM tbl_saf_tax WHERE saf_dtl_id=$saf_dtl_id and status=1";
        $builder = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }

    public function getsaftaxbysafId_old($saf_dtl_id)
    {
        try
        {
            $builder= $this->db->table($this->table)
                            ->select('*')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('status', 1)                            
                            ->get();
            echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getsaftaxbygbsafId($govt_saf_dtl_id)
    {
        try
        {
            return $this->db->table("tbl_govt_saf_tax_dtl")
                            ->select('*')
                            ->where('govt_saf_dtl_id', $govt_saf_dtl_id)
                            ->where('status', 1)                            
                            ->get()
                            ->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
    /*
    public function getmaxfyidbysafid($saf_dtl_id)
    {
        $sql = "SELECT * FROM tbl_saf_tax where fy_mstr_id in(select max(fy_mstr_id) from tbl_saf_tax where qtr in(select max(qtr) from tbl_saf_tax)) and saf_dtl_id='$saf_dtl_id'";
        $q =$this->db->query($sql);
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result[0];
    }
    */
    public function getmaxfyidbysafid($saf_dtl_id)
    {
        $sql = "select max(fy_mstr_id) as max_fy_id from tbl_saf_tax where saf_dtl_id='$saf_dtl_id'";
        $builder =$this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow("array");
    }

    public function getmaxfyqtridbysafid($saf_dtl_id,$max_fy_id)
    {
        $sql = "select max(qtr) as max_qtr from tbl_saf_tax where saf_dtl_id='$saf_dtl_id' and fy_mstr_id='$max_fy_id'";
        $builder =$this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow("array");
    }
    public function getallmaxfyqtridbysafid($saf_dtl_id ,$max_fy_id, $max_qtr)
    {
        $sql = "select * from tbl_saf_tax where saf_dtl_id='$saf_dtl_id' and fy_mstr_id='$max_fy_id' and qtr='$max_qtr'";
        $builder =$this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow("array");
    }

    public function getMaxTaxDtl($saf_dtl_id)
    {
        $sql = "select * from tbl_saf_tax where saf_dtl_id='$saf_dtl_id' ORDER BY id DESC LIMIT 1";
        $builder =$this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow("array");
    }

	public function tax_list($data)
    {
		/*try{        
            $builder = $this->db->table("tbl_saf_tax")
                        ->select('*')
                        ->where('md5(saf_dtl_id::text)', $data['id'])
                        ->get();
            return $builder->getResultArray();
			}catch(Exception $e){
				return $e->getMessage();   
			}*/
        $sql = "SELECT tb1.arv,tb1.holding_tax,tb1.water_tax,tb1.education_cess,tb1.health_cess,tb1.latrine_tax,tb1.additional_tax,tb1.qtr,tb2.fy, tb1.fyear
		FROM tbl_saf_tax tb1
		left join view_fy_mstr tb2 on tb2.id= tb1.fy_mstr_id
		where saf_dtl_id=? 
		ORDER BY tb2.fy,tb1.qtr";
        $ql= $this->query($sql, [$data]);
		//echo $this->db->getLastQuery();
		if($ql){
			return $ql->getResultArray();
		}else{
			return false;
		}

    }
	
	public function al_tax_id($data)
    {
		$tax_id = "SELECT *
			FROM tbl_saf_tax
			WHERE id=?";
			$ql= $this->query($tax_id, [$data['resultid']]);
			$al_tax_id =$ql->getResultArray();
	}
    public function getalltaxfyqtridbysafid($saf_dtl_id,$fy_id,$qtr)
    {
        $sql = "select * from tbl_saf_tax where saf_dtl_id='$saf_dtl_id' and fyear='$fy_id' and qtr='$qtr'";
        $builder =$this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow("array");
    }
    
    public function getSafTaxDtlBySafDtlId($input) {
        $builder = $this->db->table('tbl_saf_tax')
                    ->select('
                            tbl_saf_tax.id as saf_tax_id, 
                            tbl_saf_tax.saf_dtl_id as saf_dtl_id, 
                            tbl_saf_tax.fy_mstr_id as fy_mstr_id,
                            view_fy_mstr.fy as fy,
                            tbl_saf_tax.arv as arv, 
                            tbl_saf_tax.holding_tax as holding_tax, 
                            tbl_saf_tax.water_tax as water_tax, 
                            tbl_saf_tax.education_cess as education_cess, 
                            tbl_saf_tax.health_cess as health_cess, 
                            tbl_saf_tax.latrine_tax as latrine_tax, 
                            tbl_saf_tax.additional_tax as additional_tax, 
                            tbl_saf_tax.status as status, 
                            tbl_saf_tax.qtr as qtr')
                    ->join('view_fy_mstr', 'view_fy_mstr.id = tbl_saf_tax.fy_mstr_id')
                    ->where('tbl_saf_tax.saf_dtl_id', $input['saf_dtl_id'])
                    ->where('tbl_saf_tax.status', 1)
                    ->orderBy('tbl_saf_tax.fy_mstr_id, tbl_saf_tax.qtr', 'ASC')
                    ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }
	
	
	
	public function saftax_deactive($data) {
         return $this->db->table($this->table)
                ->where('saf_dtl_id', $data)
                ->update([
                    'status'=>0
                ]); 
    }
	
	
}
