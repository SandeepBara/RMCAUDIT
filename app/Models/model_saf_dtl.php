<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_saf_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_saf_dtl';

    protected $allowedFields = ['id', 'has_previous_holding_no', 'previous_holding_id', 'previous_ward_mstr_id', 'is_owner_changed', 'previous_holding_owner_name', 'previous_holding_owner_address', 'transfer_mode_mstr_id', 'saf_no', 'holding_no', 'ward_mstr_id', 'ownership_type_mstr_id', 'prop_type_mstr_id', 'appartment_name', 'no_electric_connection', 'elect_consumer_no', 'elect_acc_no', 'elect_bind_book_no', 'elect_cons_category', 'building_plan_approval_no', 'building_plan_approval_date', 'water_conn_no', 'water_conn_date', 'khata_no', 'plot_no', 'village_mauja_name', 'road_type_mstr_id', 'area_of_plot', 'prop_address', 'prop_city', 'prop_dist', 'prop_pin_code', 'corr_address', 'corr_city', 'corr_dist', 'corr_pin_code', 'is_mobile_tower', 'tower_area', 'tower_installation_date', 'is_hoarding_board', 'hoarding_area', 'hoarding_installation_date', 'is_petrol_pump', 'under_ground_area', 'petrol_pump_completion_date', 'is_water_harvesting', 'occupation_date', 'payment_status', 'doc_verify_status', 'doc_verify_date', 'doc_verify_emp_details_id', 'doc_verify_cancel_remarks', 'field_verify_status', 'field_verify_date', 'field_verify_emp_details_id', 'emp_details_id', 'created_on', 'status','aaply_date','saf_pending_status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    
    public function updateSaf($saf_dtl_id_md5, $inputs)
    {
        $builder = $this->db->table($this->table)
            ->where('md5(id::text)', $saf_dtl_id_md5)
            ->update($inputs);
        //echo $this->db->getLastQuery();
        return $builder;
    }

    public function getSafIdByPropId($data) {
    	try {        
            $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('prop_dtl_id', $data["prop_dtl_id"])
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function joinSafDtlBySafNo($input) {
        try{
			
			$WHERE = "";
            if ($input['ward_mstr_id']!="")
            {
                $WHERE .= " (tbl_saf_dtl.ward_mstr_id::text) ILIKE '%".$input['ward_mstr_id']."%' AND tbl_saf_dtl.saf_no ILIKE '%".$input['saf_no']."%'";
            }
            else if ($input['ward_mstr_id']=="") {
                $WHERE .= "tbl_saf_dtl.saf_no ILIKE '%".$input['saf_no']."%'";
            }
			
            $sql = "SELECT 
                    tbl_saf_dtl.id AS id,
                    tbl_saf_dtl.saf_no AS saf_no, 
                    tbl_saf_owner_detail.owner_name AS owner_name, 
                    tbl_saf_owner_detail.guardian_name AS guardian_name,
                    tbl_saf_owner_detail.mobile_no AS mobile_no,
                    tbl_saf_dtl.prop_address AS prop_address
                    FROM tbl_saf_dtl
                    INNER JOIN (SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(guardian_name, ',') AS guardian_name, STRING_AGG(mobile_no::TEXT, ',') AS mobile_no FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) AS tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id = tbl_saf_dtl.id
                    WHERE ".$WHERE;
            $queryResult = $this->db->query($sql);
            //echo $this->db->getLastQuery();
            return $queryResult->getResultArray();
            
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }

    public function getSafDtlById($input) {
        try{
             $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('id', $input['saf_dtl_id'])
                        ->where('status', 1)
                        ->get();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function getSafDtlById2($input) {
        try{
             $builder = $this->db->table($this->table)
                        ->select('*,view_ward_mstr.ward_no')
                        ->join('view_ward_mstr', 'view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id')
                        ->where('tbl_saf_dtl.id', $input['saf_dtl_id'])
                        ->where('tbl_saf_dtl.status', 1)
                        ->get();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function getSafDtlBySafno($input) {
        try{
             $builder = $this->db->table($this->table)
                        ->select('tbl_saf_dtl.id,saf_no,ward_mstr_id,prop_address,prop_city,prop_dist,prop_pin_code,payment_status,prop_dtl_id,ward_no')
                        ->join('view_ward_mstr', 'view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id')
                        ->where('tbl_saf_dtl.saf_no', strtoupper($input['saf_no']))
                        ->where('tbl_saf_dtl.status', 1)
                        ->get();
                        //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }

    public function bo_ward_saf_list($from_date,$to_date,$ward_mstr_id)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function bo_saf_list($from_date, $upto_date, $ward_permission, $whereClause)
    {
    	try
        {
            $sql="SELECT tbl_saf_dtl.id, saf_no, apply_date, assessment_type, view_saf_owner_detail.*, ward_no, emp_name 
                FROM tbl_saf_dtl
                join view_saf_owner_detail on view_saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                join view_emp_details on view_emp_details.id=tbl_saf_dtl.emp_details_id
                WHERE tbl_saf_dtl.status = 1 AND date(created_on) >= '$from_date' AND date(created_on) <= '$upto_date' AND 
                ward_mstr_id IN ($ward_permission) $whereClause ORDER BY id DESC";
            $builder = $this->db->query($sql);
            //print_var($builder->resultID);
            //echo $builder->getRow();
            //echo $this->db->getLastQuery();
            
            return $builder->getResultArray();

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function boc_saf_list($from_date,$to_date,$ward_permission)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('saf_pending_status', 2)
                       ->orderBy('id','DESC')
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwiseboc_saf_list($from_date,$to_date,$ward_mstr_id)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('saf_pending_status', 2)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get();
						
				//echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function countSubHoldingNoByPreviousHoldingId($previous_holding_id) {
        try{
            return $this->db->table($this->table)
                            ->select('COUNT(*) AS count')
                            ->where('previous_holding_id', $previous_holding_id)
                            ->where('payment_status', 1)
                            ->where('status', 1)
                            ->get()
                            ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function Saf_details($data)
    {
    	try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('id', $data["saf_dtl_id"])
                        ->get();
           return $builder->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    // public function insertData($input){
    //     $this->db->table($this->table)
    //                 ->insert($input);
       
    //     return $this->db->insertID();
    // }
    public function insertData($input){
        // echo "<br/>inside query";

        try{        
            $this->db->table($this->table)
                            ->insert($input);
                            return $this->db->insertID();

        }catch(Exception $e){
            // echo "<br/> inside error ".$e->getMessage();
            return $e->getMessage();   
        }
       
    }

    public function updateSAFDtlByBackOffice($input) {
         return $this->db->table($this->table)
                ->where('md5(id::text)', $input['saf_dtl_id'])
                ->update([
                    'elect_consumer_no'=>$input['elect_consumer_no'],
                    'elect_acc_no'=>$input['elect_acc_no'],
                    'elect_bind_book_no'=>$input['elect_bind_book_no'],
                    'elect_cons_category'=>$input['elect_cons_category'],
                    'building_plan_approval_no'=>$input['building_plan_approval_no'],
                    'building_plan_approval_date'=>($input['building_plan_approval_date']!="")?$input['building_plan_approval_date']:null,
                    'water_conn_no'=>$input['water_conn_no'],
                    'water_conn_date'=>($input['water_conn_date']!="")?$input['water_conn_date']:null,
                    'khata_no'=>$input['khata_no'],
                    'plot_no'=>$input['plot_no'],
                    'village_mauja_name'=>$input['village_mauja_name']
                ]); 
    }
    public function updateSAFDtlCorrAddByBackOffice($input) {
        return $this->db->table($this->table)
                ->where('md5(id::text)', $input['saf_dtl_id'])
                ->update([
                    'is_corr_add_differ'=>isset($input['is_corr_add_differ'])?true:false,
                    'corr_address'=>$input['corr_address'],
                    'corr_city'=>$input['corr_city'],
                    'corr_dist'=>$input['corr_dist'],
                    'corr_state'=>$input['corr_state'],
                    'corr_pin_code'=>$input['corr_pin_code']
                ]);
    }

    public function updateSafNoById($input){
        return $this->db->table($this->table)
                            ->where('id', $input['saf_dtl_id'])
                            ->update(
                                ['saf_no'=>$input['saf_no']
                            ]);
    }
    public function getSafDtlByMd5ID($saf_dtl_id) {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('md5(id::text)', $saf_dtl_id)
                        ->get();
           return $builder->getFirstRow('array');
           //echo $this->db->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function Saf_details_md5($saf_dtl_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(id::text)', $saf_dtl_id)
                        ->get();
           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function SafDetailsById($saf_dtl_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('id', $saf_dtl_id)
                        ->get();
           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function update_saf_pending_status($input)
    {
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['saf_dtl_id'])
                            ->update([
                                'saf_pending_status'=> $input['doc_verify_status']
                            ]);
    }
    public function update_doc_verify_status($input)
    {
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['saf_dtl_id'])
                            ->update([
                                'doc_verify_status'=> $input['doc_verify_status']
                            ]);
    }
    public function update_doc_upload_status($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['saf_dtl_id'])
                            ->update([
                                'doc_upload_status'=> 1
                            ]);
    }

    public function update_da_verify_status($input, $saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->where('id', $saf_dtl_id)
                            ->update($input);
        //echo $this->db->getLastQuery();
        return $builder;
    }

	public function consumer_details_old($where)
    { 
		$builder = "select * from view_saf_dtl_ward_ownership_type where status=1 and ".$where;

        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
        
    }

    public function consumer_details($where)
    {
        $builder = "select * from view_saf_dtl_ward_ownership_type
		where ".$where;

        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
        
    }

	public function basic_details($data)
    {
		try
        {
            $builder = $this->db->table("view_saf_dtl_ward_ownership_type")
                        ->select('*')
                        ->where('md5(saf_dtl_id::text)', $data['id'])
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function basic_details_by_id($data)
    {
        try
        {
            $builder = $this->db->table("view_saf_dtl_ward_ownership_type")
                        ->select('*')
                        ->where('saf_dtl_id', $data['id'])
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	public function basic_dtl($data)
    {
		try{        
            $builder = $this->db->table("view_saf_dtl_ward_ownership_type")
                        ->select('*')
                        ->where('md5(saf_dtl_id::text)', $data)
                        ->get();

           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
	}
    //lll
	public function basic_receipt_dtl($data)
    {
		try{        
            $builder = $this->db->table("view_receipt_saf_owner_dtl")
                        ->select('*')
                        ->where('md5(saf_dtl_id::text)', $data)
                        ->get();

           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
	}

	public function getholdWard($data)
    {
		try{        
            $builder = $this->db->table("view_saf_dtl_ward_ownership_type")
                        ->select('saf_no, ward_no')
                        ->where('md5(saf_dtl_id::text)', $data)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }

        /*$sql = "SELECT holding_no,ward_no
		FROM view_prop_dtl_owner_ward_prop_type_ownership_type
		where prop_dtl_id=?";
        $ql= $this->query($sql, [$data['id']]);
        $result =$ql->getResultArray();
        return $result[0];*/
    }


    public function get_saf_dtl($saf_dtl_id)
    {
        try {
            $builder = $this->db->table("view_prop_dtl_owner_ward_prop_type_ownership_type")
                ->select('*')
                ->where('saf_dtl_id', $saf_dtl_id)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
	
	
	public function land_occupancy_date($data)
    {
		try{        
            $builder = $this->db->table($this->table)
                        ->select('prop_type_mstr_id,land_occupation_date,is_mobile_tower,is_hoarding_board')
                        ->where('id', $data)
                        ->get();

           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function checkSafFormNoExistOrNot($input)
    {
    	try
        {        
            return $this->db->table('tbl_saf_distributed_dtl')
                        ->select('saf_no')
                        ->where('form_no', $input["form_no"])
                        ->where('saf_no', NULL)
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow('array');

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	
	public function docstatus($data)
    {
		try{        
            $builder = $this->db->table($this->table)
                        ->select('doc_upload_status')
                        ->where('id', $data)
                        ->get();

           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getSafdetails($saf_dtl_id)
    {
        try{        
             $builder = $this->db->table($this->table)
                        ->select('saf_no')
                        ->where('status', 1)
                        ->where('id',$saf_dtl_id)
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder["saf_no"];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function getWardMstrId($saf_dtl_id)
    {
        try{        
             $builder = $this->db->table($this->table)
                        ->select('ward_mstr_id')
                        ->where('status', 1)
                        ->where('id',$saf_dtl_id)
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder["ward_mstr_id"];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function getSafWardMstrId($from_date,$to_date)
    {
        try
        {
            $builder = $this->table($this->table)
                     ->select('id,saf_no,ward_mstr_id,apply_date')
                     ->where('status',1)
                     ->where('saf_pending_status',0)
                     ->where('apply_date >=', $from_date)
                     ->where('apply_date <=', $to_date)
                     /*->paginate(2)*/
                     ->get();
                    // echo $this->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getDataByWardMstrId($ward_mstr_id,$from_date,$to_date)
    {
        try
        {
            $builder = $this->table($this->table)
                     ->select('*')
                     ->where('ward_mstr_id',$ward_mstr_id)
                     ->where('apply_date >=', $from_date)
                     ->where('apply_date <=', $to_date)
                     ->where('status',1)
                     ->get();
                    //echo $this->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getBackToCitizenByWardMstrId($ward_mstr_id,$from_date,$to_date)
    {
        try
        {
            $builder = $this->table($this->table)
                     ->select('id,saf_no,ward_mstr_id')
                     ->where('status',1)
                     ->where('ward_mstr_id',$ward_mstr_id)
                     ->where('saf_pending_status',2)
                     ->where('apply_date >=',$from_date)
                     ->where('apply_date <=',$to_date)
                     ->get();
                     //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getBackToCitizen($from_date,$to_date)
    {
        try
        {
            $builder = $this->table($this->table)
                     ->select('id,saf_no,ward_mstr_id')
                     ->where('status',1)
                     ->where('saf_pending_status',2)
                     ->where('apply_date >=',$from_date)
                     ->where('apply_date <=',$to_date)
                     ->get();
                     //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getsafnoBySafDistDtlId($geotag_dtl_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('saf_no')
                        ->where('id',$geotag_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function safDetails($saf_no)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_no', $saf_no)
                        ->where('status', 1)
                        ->get();
           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function updateSafDtlStatus($saf_dtl_id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$saf_dtl_id)
                            ->update([
                                   "status"=>0 
                            ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	
	public function assessment_type($data)
    {
		try{        
            $builder = $this->db->table($this->table)
                        ->select('assessment_type')
                        ->where('previous_holding_id', $data)
                        ->get();
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
	}
	public function getDeactivateSafNo($saf_dtl_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('saf_no,ward_mstr_id')
                        ->where('id',$saf_dtl_id)
                        ->where('status',0)
                        ->get();
                       // echo $this->getLastQuery();
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	public function propertyDetailsSaf($saf_no){
        try{
            $builder =$this->db->table($this->table)
                     ->select('*')
                     ->where('md5(saf_no::text)',$saf_no)
                     ->where('status',1)
                     ->get();
                     //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function ConsumerDetails(){
        try{
            $builder = $this->db->table('view_saf_dtl_ward_ownership_type')
                    ->select('*')
                    ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	public function paidsts($input){
		if($input["payment_mode"]=='2' || $input["payment_mode"]=='3'){
			$psy_stats = 2;
		}else{ $psy_stats = 1; }
		$paidsts = "UPDATE tbl_saf_dtl 
				SET payment_status =?
				WHERE id =?";
				$ql= $this->query($paidsts, [$psy_stats, $input["custm_id"]]);
				return $paidsts;
	}
	public function getAllData($from_date,$to_date){
        try{
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('apply_date >=',$from_date)
                    ->where('apply_date <=',$to_date)
                    ->where('status',1)
                    ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function safPayment_Document_Status($saf_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('payment_status,doc_upload_status')
                    ->where('id',$saf_dtl_id)
                    ->where('status',1)
                    ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updatePaymentStatus($saf_dtl_id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$saf_dtl_id)
                            ->update([
                                "payment_status" =>0
                            ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	
	public function saf_quantity($day,$month_from,$month_to,$fy_from,$fy_to){
		
		$sql = "SELECT count(saf_no) as total_daysaf, (SELECT count(saf_no) as total_monthsaf FROM tbl_saf_dtl
			    WHERE status=1 AND apply_date BETWEEN '$month_from' AND '$month_to'), 
				(SELECT count(saf_no) as total_yearsaf FROM tbl_saf_dtl
			    WHERE status=1 AND apply_date BETWEEN '$fy_from' AND '$fy_to'), (SELECT count(saf_no) as New_Assessment_no
			    FROM tbl_saf_dtl WHERE status=1 AND assessment_type='New Assessment' AND apply_date BETWEEN '$fy_from' AND '$fy_to'), (SELECT count(saf_no) as reassessment_no
			    FROM tbl_saf_dtl WHERE status=1 AND assessment_type='Reassessment' AND apply_date BETWEEN '$fy_from' AND '$fy_to'), (SELECT count(saf_no) as muttation_no
			    FROM tbl_saf_dtl WHERE status=1 AND assessment_type='Muttation' AND apply_date BETWEEN '$fy_from' AND '$fy_to')
			    FROM tbl_saf_dtl
			    WHERE status=1 AND apply_date='$day'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getFirstRow('array');
				return $result;
       
    }
	
	
	
	public function msg($data)
    {
    	try
        {
            $builder = $this->db->table($this->table)
                        ->select('payment_status,doc_upload_status,saf_pending_status')
                        ->where('status', 1)
                        ->where('md5(id::text)', $data["id"])
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	public function citizen_details($where) { 
        $sql = "SELECT *
		FROM view_saf_dtl_ward_ownership_type
		where ".$where;
        $ql= $this->db->query($sql);
		//echo $this->db->getLastQuery();
        $result =$ql->getResultArray();
        return $result;
	}
	public function getSafDetailsBySafId($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('id',$id)
                     ->where('status',1)
                     ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function validate_saf($ward_id,$saf_no)
    {
        $sql="select id from tbl_saf_dtl where ward_mstr_id=".$ward_id." and upper(saf_no)='".$saf_no."' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['id'];

    }
	
    public function GetSAFOwnerDetails($saf_dtl_id)
    {
        $sql="select * from tbl_saf_owner_detail where saf_dtl_id=$saf_dtl_id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }

	public function updateSafstatusDocUpload($input)
    {
		//print_r($input);
        return $builder = $this->db->table($this->table)
						->where('id', $input['saf_id'])
						->update([
								'doc_upload_status'=> 1,
                                'saf_pending_status'=> 0,
							]);
    }
	
    public function safSearchUsingWardSafNoOwnerMobile($input)
    {
        $where=NULL; 
        if($input['ward_mstr_id']!='') {
            $where = " where tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id'];
        }

        if($input['saf_no']!='') {
            $where = " where tbl_saf_dtl.saf_no='".$input['saf_no']."'";
        }

        if($input['ward_mstr_id']!='' && $input['saf_no']!='') {
            $where = " where tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id']." and tbl_saf_dtl.saf_no='".$input['saf_no']."'";
        }

        if($input['ward_mstr_id']!='' && $input['owner_name']!='') {
            $where = " where tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id']." and saf_owner_detail.owner_name ILIKE '%".$input['owner_name']."%'";
        }

        if($input['ward_mstr_id']!='' && $input['mobile_no']!='') {
            $where = " where tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id']." and saf_owner_detail.mobile_no='".$input['mobile_no']."'";
        }

        

        $sql ="SELECT
                    tbl_saf_dtl.id,
                    view_ward_mstr.ward_no AS ward_no,
                    tbl_saf_dtl.saf_no AS saf_no,
                    saf_owner_detail.owner_name AS owner_name,
                    saf_owner_detail.mobile_no AS mobile_no
                FROM tbl_saf_dtl
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                INNER JOIN (SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(mobile_no::text, ',') AS mobile_no FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) AS saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                $where";
        return  $this->db->query($sql)->getResultArray();
    }

	public function safSearchUsingWardSafNoOwnerMobile_old($input){
        $owner_name_query = "";
        if($input['owner_name']!='') {
            $owner_name_query = " OR saf_owner_detail.owner_name ILIKE '%".$input['owner_name']."%'";
        }
        $mobie_no_query = "";
        if($input['mobile_no']!='') {
            $mobie_no_query = " OR saf_owner_detail.mobile_no='".$input['mobile_no']."'";
        }
        $sql ="SELECT
                    tbl_saf_dtl.id,
                    view_ward_mstr.ward_no AS ward_no,
                    tbl_saf_dtl.saf_no AS saf_no,
                    saf_owner_detail.owner_name AS owner_name,
                    saf_owner_detail.mobile_no AS mobile_no
                FROM tbl_saf_dtl
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                INNER JOIN (SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(mobile_no::text, ',') AS mobile_no FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) AS saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                WHERE tbl_saf_dtl.ward_mstr_id='".$input['ward_mstr_id']."' AND (tbl_saf_dtl.saf_no='".$input['saf_no']."' ".$owner_name_query.$mobie_no_query.")
                ";
        return  $this->db->query($sql)->getResultArray();
    }
	
	/*public function getNoOfSAF($ward_id)
    {
        $sql="select count(id) as no_of_saf from tbl_saf_dtl where ward_mstr_id=".$ward_id." and  status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;

    }
	
	public function getSafNo()
    {
        $sql="select count(id) as no_of_saf from tbl_saf_dtl where status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;

    }*/

	
	
	public function safUpdate_details($data)
    { 
        $builder = "select tbl_saf_dtl.payment_status,tbl_saf_dtl.status,tbl_saf_dtl.khata_no,tbl_saf_dtl.plot_no,
					tbl_saf_dtl.saf_no,tbl_saf_dtl.prop_address,tbl_saf_dtl.prop_type_mstr_id,owner.* ,view_ward_mstr.ward_no
					from tbl_saf_dtl 
					JOIN view_ward_mstr ON tbl_saf_dtl.ward_mstr_id = view_ward_mstr.id
					JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
					string_agg(tbl_saf_owner_detail.owner_name::text, ','::text) AS owner_name,
					string_agg(tbl_saf_owner_detail.mobile_no::text, ','::text) AS mobile_no
					FROM tbl_saf_owner_detail
					GROUP BY tbl_saf_owner_detail.saf_dtl_id) owner ON owner.saf_dtl_id = tbl_saf_dtl.id
					where tbl_saf_dtl.saf_no='".$data."'";
					
        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }
	
	
	public function safdtl_deactive($data) {
         return $this->db->table($this->table)
                ->where('id', $data)
                ->update([
                    'status'=>0
                ]); 
    }
    public function clearPaymentStatus($saf_id)
    {
        $sql="update tbl_saf_dtl set payment_status=1 where id=$saf_id";
        $this->db->query($sql);
    }
	
	public function CountTotalSAFInWard($ward_mstr_id, $assessment_type)
    {
        
        $builder=$this->db->table($this->table)
                            ->select('count(id) as count')
                            ->where('ward_mstr_id', $ward_mstr_id)
                            ->where('assessment_type', $assessment_type)
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow('array')['count'];
    }
	
	
	
	public function levelformdetail($data)
    { 
        $builder = "SELECT
                    tbl_saf_dtl.id,
                    view_ward_mstr.ward_no AS ward_no,
                    tbl_saf_dtl.saf_no AS saf_no,
					tbl_saf_dtl.prop_address AS address,
                    saf_owner_detail.owner_name AS owner_name,
                    saf_owner_detail.mobile_no AS mobile_no,
					tbl_prop_type_mstr.property_type
                FROM tbl_saf_dtl
				LEFT JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
				LEFT JOIN tbl_prop_type_mstr on tbl_saf_dtl.prop_type_mstr_id=tbl_prop_type_mstr.id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                INNER JOIN (SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(mobile_no::text, ',') AS mobile_no FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) AS saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                where tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.verification_status=0 and md5(tbl_level_pending_dtl.receiver_user_type_id::text)='".$data."'";
					
        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }
	
	public function updateDocVerifyStatus($input)
    {
        return $this->db->table($this->table)
                ->where("id", $input['saf_dtl_id'])
                ->update([
                    'doc_verify_status'=> $input['doc_verify_status'],
                    'doc_verify_date'=> "NOW()",
                    'doc_verify_emp_details_id'=> $input['doc_verify_emp_details_id'],
                ]);
    }
    public function updateSafPaymentStatus($saf_dtl_id)
    {
        return $this->db->table($this->table)
                ->where("id", $saf_dtl_id)
                ->update([
                    'payment_status'=> 1
                ]);
    }

    public function updateById($saf_dtl_id, $input) {
        return $this->db->table($this->table)
                ->where("id", $saf_dtl_id)
                ->update($input);
    }

    public function getTrustType($saf_dtl_id){
        try{        
            $builder = $this->db->table($this->table)
                        ->select('trust_type')
                        ->where('id',$saf_dtl_id)
                        ->get();
                       // echo $this->getLastQuery();
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
?>