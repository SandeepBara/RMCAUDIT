<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_floor_details;
use App\Models\model_view_saf_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_floor_mstr;
use App\Models\model_level_pending_dtl;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_ward_permission;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use App\Models\ObjectionModel;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_transfer_mode_mstr;
use App\Models\PropertyTypeModel;
use App\Models\model_view_ward_mapping_mstr;
use App\Models\model_saf_doc_dtl;

class Auto_forward extends HomeController
{
    protected $db;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_ward_permission;
    protected $model_view_ward_permission;
    protected $model_transfer_mode_mstr;
    protected $model_prop_type_mstr;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_saf_owner_detail;
    protected $model_saf_dtl;
    protected $model_saf_tax;
    protected $model_saf_floor_details;
    protected $model_road_type_mstr;
    protected $model_usage_type_mstr;
    protected $model_const_type_mstr;
    protected $model_occupancy_type_mstr;
    protected $model_floor_mstr;
    protected $model_view_saf_floor_details;
    protected $model_level_pending_dtl;
    protected $model_saf_geotag_upload_dtl;
    protected $model_ulb_mstr;
	protected $model_datatable;
    protected $ObjectionModel;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $PropertyTypeModel;
    protected $model_view_ward_mapping_mstr;
    protected $model_saf_doc_dtl;

    public function __construct()
    {
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        //parent::__construct();

        helper(['db_helper', 'geotagging_helper', 'utility_helper']);

        $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system');
		

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model_datatable = new model_datatable($this->db);
        $this->ObjectionModel = new ObjectionModel($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->PropertyTypeModel = new PropertyTypeModel($this->db);
        $this->model_view_ward_mapping_mstr = new model_view_ward_mapping_mstr($this->dbSystem);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
		
		
    }

    // function __destruct() {
	// 	$this->db->close();
	// 	$this->dbSystem->close();
	// }

    public function getEmp($userType, $ward_mstr_id)
    {
        $sqlemp = "SELECT a.id FROM view_emp_details a
                        JOIN view_ward_permission b on b.emp_details_id=a.id
                        WHERE user_type_id=".$userType." and ward_mstr_id=".$ward_mstr_id." and user_mstr_lock_status=0 and b.status=1 order by id desc limit 1";
                
        $empdata = $this->db->query($sqlemp)->getFirstRow();
        return $empdata->id??1;
    }

    public function getDAEmpId($userTypeMstrId, $wardNo)
    {
        $sqlemp = "SELECT a.id FROM view_emp_details a
                    JOIN view_ward_permission b on b.emp_details_id=a.id
                    join view_ward_mstr on b.ward_mstr_id=view_ward_mstr.id
                    WHERE user_type_id=".$userTypeMstrId." and user_mstr_lock_status=0 and 
                    (SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT=SUBSTRING('".$wardNo."' FROM '([0-9]+)')::BIGINT)
                    and b.status=1
                    order by b.id desc limit 1";
                
        $empdata = $this->db->query($sqlemp)->getFirstRow();
        return $empdata->id??1;
        
    }

    function dealingToUlbTc()
    {

        $user_type_mstr_id = 6; // Dealing
        $recv_user_type_id = 7; // ULB TC
		//$this->db->reconnect();
        //$sender_emp_details_id = 1392;

        $sql = "SELECT
                    tbl_level_pending_dtl.id as level_id,
                    tbl_level_pending_dtl.saf_dtl_id,
                    tbl_prop_type_mstr.property_type,
                    view_ward_mstr.ward_no,
                    tbl_saf_dtl.saf_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.created_on,
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE
                    receiver_user_type_id=".$user_type_mstr_id."
                    AND tbl_level_pending_dtl.verification_status='0'
                    AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1'
                    AND tbl_level_pending_dtl.created_on::date <= NOW()::DATE-'6 DAYS'::INTERVAL 
                ORDER BY tbl_level_pending_dtl.id DESC LIMIT 100";


        $result = $this->db->query($sql)->getResultArray();
		
        foreach ($result as $res) 
        {
            $saf_dtl_id = $res['saf_dtl_id'];
            $sender_emp_details_id = $this->getDAEmpId($user_type_mstr_id, $res['ward_no']);
            //echo $res['ward_no'].'-'.$sender_emp_details_id.'<br/>';

            // Document verify
            $doc = $this->db->table('tbl_saf_doc_dtl')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('status', 1)
                            ->where('doc_mstr_id >', 0)
                            ->where('verify_status', 0)
                            ->Update([
                                "verify_status"=> 1,
                                "remarks"=> "Verified",
                                "verified_by_emp_id"=> $sender_emp_details_id,
                                "verified_on"=> "NOW()",
                            ]);
            
            $input = [
                "id" => $res["level_id"],
                "saf_dtl_id" => $saf_dtl_id,
                'receiver_emp_details_id' => $sender_emp_details_id
            ];
            if ($this->model_level_pending_dtl->updateLastRecord($input)) {

                $input = [
                    "saf_dtl_id" => $saf_dtl_id,
                    "sender_user_type_id" => $user_type_mstr_id, // Property Dealing Asst.
                    "receiver_user_type_id" => $recv_user_type_id, // ULB tax collector
                    "forward_date" => date('Y-m-d'),
                    "forward_time" => date('H:i:s'),
                    "remarks" => "Auto Forward",
                    "created_on" => date('Y-m-d H:i:s'),
                    "doc_verify_status" => 1,
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                $this->model_level_pending_dtl->bugfix_level_pending($input); 
                if ($this->model_level_pending_dtl->insrtlevelpendingdtl($input)) {
                    $input = [
                        "saf_dtl_id" => $saf_dtl_id,
                        "doc_verify_status" => 1,
                        "doc_verify_emp_details_id" => $sender_emp_details_id,

                    ];
                    $this->model_saf_dtl->updateDocVerifyStatus($input);

                    echo "Application auto forwarded to ULB TC \n";
                }
            }


        }
    }

    function ulbTcToSI()
    {

        $user_type_mstr_id = 7; //ULB TC
        $recv_user_type_id = 9; //Section Incharge
        
        
        $sql = "SELECT
                tbl_level_pending_dtl.id as level_pending_id,
                tbl_level_pending_dtl.saf_dtl_id,        
                tbl_saf_dtl.saf_no,
                tbl_saf_dtl.assessment_type,
                tbl_saf_dtl.prop_address,
                tbl_saf_dtl.apply_date,        
                tbl_level_pending_dtl.forward_date,
                tbl_level_pending_dtl.forward_time,
                tbl_level_pending_dtl.remarks,
                tbl_saf_dtl.prop_type_mstr_id,
                tbl_saf_dtl.ward_mstr_id
            from tbl_level_pending_dtl 
            INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1
            WHERE
                receiver_user_type_id=".$user_type_mstr_id."
                AND tbl_level_pending_dtl.verification_status='0'
                AND doc_upload_status='1'
                AND tbl_level_pending_dtl.status='1' 
                AND tbl_level_pending_dtl.created_on::date <= NOW()::DATE-'7 DAYS'::INTERVAL
				LIMIT 100
				";
        
        $result = $this->db->query($sql)->getResultArray();

        
        foreach ($result as $res) 
        {
            $saf_dtl_id = $res['saf_dtl_id'];
            $verification = $this->model_field_verification_dtl->getFieldDtlBYSAFId($saf_dtl_id)[0];
            
            $verificationp = array();
            
            if($verification['verified_by']=='AGENCY TC')
            {
                $verificationp = $this->model_field_verification_dtl->getdatabysafid($saf_dtl_id);
                $old_verification_id = $verificationp['id'];
            }else{
                $verificationp = $this->model_saf_dtl->Saf_details(['saf_dtl_id' => $saf_dtl_id]);
                $old_verification_id = 0;
            }
           
            $current_employee_id = $this->getEmp($user_type_mstr_id, $verificationp['ward_mstr_id']);
            
            $this->db->transBegin();
            $input = [
                'saf_dtl_id' => $saf_dtl_id,
                'prop_type_mstr_id'  => $verificationp['prop_type_mstr_id'],
                'road_type_mstr_id'  => $verificationp['road_type_mstr_id'],
                'area_of_plot'  => $verificationp['area_of_plot'],
                'verified_by_emp_details_id'  => $current_employee_id,
                'created_on'  => date('Y-m-d H:i:s'),
                'status'  => $verificationp['status'],
                'ward_mstr_id'  => $verificationp['ward_mstr_id'],
                'is_mobile_tower'  => $verificationp['is_mobile_tower'],
                'tower_area'  => $verificationp['tower_area'],
                'tower_installation_date'  => $verificationp['tower_installation_date'],
                'is_hoarding_board'  => $verificationp['is_hoarding_board'],
                'hoarding_area'  => $verificationp['hoarding_area'],
                'hoarding_installation_date'  => $verificationp['hoarding_installation_date'],
                'is_petrol_pump'  => $verificationp['is_petrol_pump'],
                'under_ground_area'  => $verificationp['under_ground_area'],
                'petrol_pump_completion_date'  => $verificationp['petrol_pump_completion_date'],
                'is_water_harvesting'  => $verificationp['is_water_harvesting'],
                'verified_by'  => "ULB TC",
                'zone_mstr_id'  => $verificationp['zone_mstr_id'],
                'percentage_of_property_transfer'  => $verificationp['percentage_of_property_transfer'],
                'new_ward_mstr_id'  => $verificationp['new_ward_mstr_id']
            ];
            
            $verification_id = $this->model_field_verification_dtl->insertData($input);
            if($verification_id)
            {
                if($old_verification_id > 0)
                {
                    $floor_verifications = $this->model_field_verification_floor_details->getFloorDataBymstrId($old_verification_id);
                }else{
                    $floor_verifications = $this->model_saf_floor_details->getDataBySafDtlIdd(['saf_dtl_id'=>$saf_dtl_id]);
                }
                
                if($floor_verifications)
                {
                    foreach($floor_verifications as $floor)
                    {
                        $floorinput = [
                            'field_verification_dtl_id' => $verification_id,
                            'saf_dtl_id' => $saf_dtl_id,
                            'saf_floor_dtl_id'  => ($floor['saf_floor_dtl_id'])?$floor['saf_floor_dtl_id']:$floor['id'],
                            'floor_mstr_id'  => $floor['floor_mstr_id'],
                            'usage_type_mstr_id'  => $floor['usage_type_mstr_id'],
                            'const_type_mstr_id'  => $floor['const_type_mstr_id'],
                            'occupancy_type_mstr_id'  => $floor['occupancy_type_mstr_id'],
                            'builtup_area'  => $floor['builtup_area'],
                            'date_from'  => $floor['date_from'],
                            'date_upto'  => $floor['date_upto'],
                            'emp_details_id'  => $current_employee_id,
                            'status'  => 1,
                            'carpet_area'  => ($floor['carpet_area'])?$floor['carpet_area']:0,
                            'verified_by'  => "ULB TC",
                            'created_on'  => date('Y-m-d H:i:s'),
                        ];
                        $this->model_field_verification_floor_details->insertData($floorinput);
                    }
                }
                $level_data = [
                    "level_pending_dtl_id"=> md5($res["level_pending_id"]),
                    'remarks' => "Auto Forward",
                    'saf_dtl_id' => $saf_dtl_id,
                    'emp_details_id' => $current_employee_id,
                    'created_on' => "NOW()",
                    'forward_date' => "NOW()",
                    'forward_time' => "NOW()",
                    'sender_user_type_id' => $user_type_mstr_id, // ULB TC
                    'receiver_user_type_id'=> $recv_user_type_id, // Section Incharge
                    'verification_status'=> 1,
                    'status'=> 0,
                    'sender_emp_details_id' => $current_employee_id,
                    'receiver_emp_details_id' => $current_employee_id
                ];
                if($updatelevelpending = $this->model_level_pending_dtl->updatelevelpendingById($level_data))
                {
                    $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data);
                    $this->model_level_pending_dtl->bugfix_level_pending($level_data);

                    
                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                    } else {
                        $this->db->transCommit();
                        echo "Application auto forwarded to Section Incharge \n";
                    }
                }
 
            }
            
        }
       
    }

    function SiToEo()
    {
        $user_type_mstr_id = 9; //Section Incharge
        $recv_user_type_id = 10; //E.O
        
        $sql = "SELECT
                    tbl_level_pending_dtl.id as level_pending_id,
                    tbl_level_pending_dtl.saf_dtl_id,
                    tbl_saf_dtl.saf_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_saf_dtl.ward_mstr_id
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 
                WHERE
                    receiver_user_type_id=".$user_type_mstr_id."
                    AND tbl_level_pending_dtl.verification_status='0'
                    AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1'
                    AND tbl_saf_dtl.assessment_type !='Reassessment'
                    AND tbl_level_pending_dtl.created_on::date <= NOW()::DATE-'6 DAYS'::INTERVAL 
                ORDER BY tbl_level_pending_dtl.id DESC LIMIT 100";
        $result = $this->db->query($sql)->getResultArray();
        
        foreach ($result as $res) 
        {
            $saf_dtl_id = $res['saf_dtl_id'];
            $current_employee_id = 1252;
   
            $level_data=[
                "level_pending_dtl_id"=> md5($res["level_pending_id"]),
                "status"=> 0,
                "verification_status"=> 1,
                'sender_emp_details_id' => $current_employee_id,
                'receiver_emp_details_id' => $current_employee_id
            ];
            $this->db->transBegin();
            if($this->model_level_pending_dtl->updatelevelpendingById($level_data))
            {
                $level_data = [
                    'remarks' => 'Auto Forward',
                    'saf_dtl_id' => $saf_dtl_id,
                    'emp_details_id' => $current_employee_id,
                    'created_on' => "NOW()",
                    'forward_date' => "NOW()",
                    'forward_time' => "NOW()",
                    'sender_user_type_id' => $user_type_mstr_id,//Property Section Incharge
                    'receiver_user_type_id'=> $recv_user_type_id,//Executive Officer
                    'verification_status'=> 0,
                    'status'=> 1,
                    'sender_emp_details_id' => $current_employee_id
                ];
                $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data);
                $this->model_level_pending_dtl->bugfix_level_pending($level_data);

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                } else {
                    $this->db->transCommit();
                    echo "Application auto forwarded to Excutive Officer \n";
                }
            } 
        }
       
    }
}