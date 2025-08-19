<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_prop_tax extends Model
{
    protected $db;
    protected $table = 'tbl_prop_tax';
    protected $allowedFields = [''];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData(array $data){
        $this->db->table($this->table)
                            ->insert($data);
        //echo  $this->db->getLastQuery();                    
        return $this->db->insertId();
    }

    public function updateSafDeactiveStatus($input){
        return $this->db->table($this->table)
                    ->set('status', 0)
                    ->where('prop_dtl_id', $input['prop_dtl_id'])
                    ->where('status', 1)
                    ->update();
    }

	// public function tax_list($data)
    // {
    //     //getting last 2 record
    //     //get result array not working
    //      $sql = "SELECT *
	// 	FROM view_prop_tax
	// 	where prop_dtl_id=? 
	// 	ORDER BY fy desc,qtr limit 2";
    //     $ql= $this->query($sql, [$data]);

      
	// 	if($ql){
	// 		return $ql->getResultArray();
	// 	}else{
	// 		return false;
	// 	} 

    // }
	public function tax_list($data)
    {
        //getting last 2 record
        //get result array not working
         $sql = "SELECT *
		FROM view_prop_tax
		where prop_dtl_id=? AND status=1
		ORDER BY fy desc,qtr limit 2";
        $ql= $this->query($sql, [$data]);

      
		if($ql){
			return $ql->getResultArray();
		}else{
			return false;
		} 

    }

    public function getLastEffecredTax($prop_dtl_id) {
        $sql = "SELECT * FROM tbl_prop_tax WHERE prop_dtl_id='".$prop_dtl_id."' 
		ORDER BY id desc";
        $result = $this->query($sql);
		return $result->getFirstRow("array");
    }

    public function insertpropaxdetbysafid($propdtl,$fy_mstr_id,$qtr,$arv,$holding_tax,$water_tax,$education_cess,$health_cess,$latrine_tax,$additional_tax,$created_on, $fyear, $quarterly_tax){
        $builder = $this->db->table($this->table)
                ->insert([
                  "prop_dtl_id"=>$propdtl,
                  "fy_mstr_id"=>$fy_mstr_id,
                  "qtr"=>$qtr,
                  "arv"=>$arv,
                  "holding_tax"=>$holding_tax,
                  "water_tax"=>$water_tax,
                  "education_cess"=>$education_cess,
                  "health_cess"=>$health_cess,
                  "latrine_tax"=>$latrine_tax,
                  "additional_tax"=>$additional_tax,
                  "created_on"=>$created_on,
                  "status"=>'1',
                  "fyear"=>$fyear,
                  "quarterly_tax"=>$quarterly_tax
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function updateproptaxBypropdetId($prop_dtl_id){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$prop_dtl_id)
                            ->update([
                                    'status'=>0
                                    ]);
    }

    public function get_taxdl_bypropid($prop_dtl_id)
    { 

        $sql="SELECT * FROM tbl_prop_tax where prop_dtl_id='".$prop_dtl_id."' order by id desc Limit 1";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2[0];
        //echo $this->db->getLastQuery();
    }
    public function getmaxfyidbypropid($prop_dtl_id)
    {
        $sql = "select max(fy_mstr_id) as max_fy_id from tbl_prop_tax where prop_dtl_id='$prop_dtl_id'";
        $q =$this->db->query($sql);
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result[0];
    }
    public function getmaxfyqtridbypropid($prop_dtl_id,$max_fy_id){
        $sql = "select max(qtr) as max_qtr from tbl_prop_tax where prop_dtl_id='$prop_dtl_id' and fy_mstr_id='$max_fy_id'";
         $q =$this->db->query($sql);
         $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result[0];
    }
    public function getallmaxfyqtridbypropid($prop_dtl_id, $max_fy_id, $max_qtr){
        $sql = "select * from tbl_prop_tax where prop_dtl_id='$prop_dtl_id' and fy_mstr_id='$max_fy_id' and qtr='$max_qtr' 
        -- AND status=1 
        ORDER BY fy_mstr_id DESC, qtr";
        $q =$this->db->query($sql);
        $result = $q->getFirstRow('array');
        //echo $this->db->getLastQuery();
        return $result;
    }
    public function getdetByfyid_qtr_propdtlid($prop_dtl_id,$fy_mstr_id,$qtr)
    {
        try{
            return $this->db->table($this->table)
                        ->select('*')
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->where('fy_mstr_id', $fy_mstr_id)
                        ->where('qtr', $qtr)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getdetails_propdtlid($prop_dtl_id){
        try{
            //print_r($this->db);
            //echo "<br />";
            /* echo $sql = "SELECT * FROM ".$this->table." WHERE status=1 AND prop_dtl_id='".$prop_dtl_id."';";
            $builder = $this->db->query($sql);
            //echo $this->db->getLastQuery();
            $data = $builder->getResultArray();
            print_r($data);
            return $data; */

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->where('status', 1)
                        ->orderBy('fy_mstr_id ASC, qtr ASC')
                        ->get()
                        ->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updateadditionaltaxById($prop_tax_id){
        return $builder = $this->db->table($this->table)
                            ->where('id',$prop_tax_id)
                            ->update([
                                    'additional_tax'=>0 
                                    ]);
    }
    public function get_previous_fyid_qtr_byproptaxid($prop_dtl_id,$fy_mstr_id,$qtr)
    { 

        $sql="SELECT id,arv,holding_tax,fy_mstr_id, qtr FROM tbl_prop_tax where status=1 and prop_dtl_id='".$prop_dtl_id."' and fy_mstr_id<='".$fy_mstr_id."' and qtr<='".$qtr."' order by fy_mstr_id desc, qtr desc limit 1";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2[0];
        //echo $this->db->getLastQuery();
    }
    
    public function updateadditionaltaxByfyIdqtr($prop_dtl_id,$fy_mstr_id,$qtr){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$prop_dtl_id)
                            ->where('fy_mstr_id>=',$fy_mstr_id)
                            ->where('qtr>=',$qtr)
                            ->update([
                                    'additional_tax'=>0 
                                    ]);
    }
    public function updatetaxBypropdtlId($input_data){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$input_data['prop_dtl_id'])
                            ->update([
                                    'arv'=>$input_data['arv'],
                                    'holding_tax'=>$input_data['holding_tax'],
                                    'water_tax'=>$input_data['water_tax'],
                                    'education_cess'=>$input_data['education_cess'],
                                    'health_cess'=>$input_data['health_cess'],
                                    'latrine_tax'=>$input_data['latrine_tax']
                                    ]);
    }
    public function updatetaxByproptaxdtlId($input_data){
        return $builder = $this->db->table($this->table)
                            ->where('id',$input_data['prop_tax_id'])
                            ->update([
                                    'arv'=>$input_data['arv'],
                                    'holding_tax'=>$input_data['holding_tax'],
                                    'water_tax'=>$input_data['water_tax'],
                                    'education_cess'=>$input_data['education_cess'],
                                    'health_cess'=>$input_data['health_cess'],
                                    'latrine_tax'=>$input_data['latrine_tax']
                                    ]);
    }

    public function getPropTaxDtlByPropDtlId($input) {
        $builder = $this->db->table('tbl_prop_tax')
                    ->select('
                            tbl_prop_tax.id as saf_tax_id, 
                            tbl_prop_tax.prop_dtl_id as prop_dtl_id, 
                            tbl_prop_tax.fy_mstr_id as fy_mstr_id,
                            view_fy_mstr.fy as fy,
                            tbl_prop_tax.arv as arv, 
                            tbl_prop_tax.holding_tax as holding_tax, 
                            tbl_prop_tax.water_tax as water_tax, 
                            tbl_prop_tax.education_cess as education_cess, 
                            tbl_prop_tax.health_cess as health_cess, 
                            tbl_prop_tax.latrine_tax as latrine_tax, 
                            tbl_prop_tax.additional_tax as additional_tax, 
                            tbl_prop_tax.status as status, 
                            tbl_prop_tax.qtr as qtr')
                    ->join('view_fy_mstr', 'view_fy_mstr.id = tbl_prop_tax.fy_mstr_id')
                    ->where('tbl_prop_tax.prop_dtl_id', $input['prop_dtl_id'])
                    ->where('tbl_prop_tax.status', 1)
                    ->orderBy('tbl_prop_tax.fy_mstr_id', 'ASC')
                    ->get();
       // echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }
	
	
	public function update_add_tax_dtl($data) {
		
        $sql = "INSERT INTO tbl_prop_tax 
        (prop_dtl_id, fy_mstr_id, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, 
		additional_tax, created_on, status, saf_deactive_status, rmc_prop_dtl_id, rmc_prop_tax_dtl_id)
        SELECT
            prop_dtl_id, ".$data['due_upto_year'].", ".$data['date_upto_qtr'].", arv, holding_tax, water_tax, education_cess, health_cess,
			latrine_tax, (holding_tax/100)*1.5, '".$data['created_on']."', status, saf_deactive_status, rmc_prop_dtl_id, rmc_prop_tax_dtl_id
        FROM tbl_prop_tax WHERE prop_dtl_id=".$data['custm_id']." order by id desc limit 1";
        $this->db->query($sql);
		return $insert_id = $this->db->insertID();
		
    }
	public function update_remove_tax_dtl($data) {
		
        $sql = "INSERT INTO tbl_prop_tax 
        (prop_dtl_id, fy_mstr_id, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, 
		additional_tax, created_on, status, saf_deactive_status, rmc_prop_dtl_id, rmc_prop_tax_dtl_id)
        SELECT
            prop_dtl_id, ".$data['due_upto_year'].", ".$data['date_upto_qtr'].", arv, holding_tax, water_tax, education_cess, health_cess,
			latrine_tax, '0', '".$data['created_on']."', status, saf_deactive_status, rmc_prop_dtl_id, rmc_prop_tax_dtl_id
        FROM tbl_prop_tax WHERE prop_dtl_id=".$data['custm_id']." order by id desc limit 1";
        $this->db->query($sql);
		return $insert_id = $this->db->insertID();
		
    }

    public function taxDtlDeactivatedByPropDtlId($prop_dtl_id){
        return $this->db->table($this->table)
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->update(['status'=>0]);
    }
	

    
}
?>