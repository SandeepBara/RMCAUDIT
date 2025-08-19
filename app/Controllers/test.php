<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_datatable;

use App\Controllers\SAF\SAFHelper;
use App\Controllers\SAF\NEW_SAFHelper;
use App\Models\model_level_pending_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_saf_floor_details;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_fy_mstr;
use App\Models\model_capital_value_rate;
//use App\Models\model_capital_value_rate_multiulb;
use App\Models\model_apartment_details;
use App\Models\model_govt_saf_floor_dtl;

class test extends Controller {

    protected $db;
    protected $db_system;
    protected $model_saf_dtl;
    protected $model_datatable;
    protected $model_saf_owner_detail;
    protected $model_saf_floor_details;
    protected $model_saf_floor_arv_dtl;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_saf_demand;
    protected $model_prop_dtl;
    protected $model_prop_demand;
    protected $model_level_pending_dtl;
    protected $model_saf_memo_dtl;
    protected $model_prop_floor_details;
    protected $model_prop_owner_detail;
    protected $model_fy_mstr;
    protected $model_capital_value_rate;
    //protected $model_capital_value_rate_multiulb;
    protected $model_apartment_details;
    protected $model_govt_saf_floor_dtl;

    public function __construct()
    {
		ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
		
        helper(['db_helper', 'utility_helper']);
        $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system');
        $this->model_datatable = new model_datatable($this->db);

        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_capital_value_rate = new model_capital_value_rate($this->db);
        //$this->model_capital_value_rate_multiulb = new model_capital_value_rate_multiulb($this->db);
        $this->model_apartment_details = new model_apartment_details($this->db);
        $this->model_govt_saf_floor_dtl = new model_govt_saf_floor_dtl($this->db);
    }

    public function index()
    {
        exit();
        $sql = "SELECT * FROM tbl_saf_dtl WHERE id<552 ORDER BY id DESC";
        $result = $this->model_datatable->getDatatable($sql);
        $data = [
            'users' => $result['result'],
            'pager' => $result['count']
        ];

        echo view('property/pagination', $data);
    }

    public function vali() {
        $path = WRITEPATH."uploads/emp_image\aa.jpg";
        $new_path = WRITEPATH."uploads/emp_image\bb.jpg";
        $image = \Config\Services::image()
                ->withFile($path)
                ->save($new_path, 10);

        exit;
        $_POST['email'] = "sss";
        $_POST['pass'] = "sss";
        $rules = [
            'email' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'bad email'
                ]
            ],
            'pass' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'bad pass'
                ]
            ]
        ];

        if ($this->validate($rules)) {

        } else {
            print_r($this->validator->getErrors());
        }
        $session = session();
        $session->setFlashdata("message", "This message is for end users #1");
        $session->getFlashdata("message");
    }

    public function getIp() {
       $session = session();

       //print_r($session->session_id);
       //echo "<pre>";
       //print_r($session);

       echo $filename = WRITEPATH."session\ci_session".$session->session_id;
       $fp = fopen($filename, "r");//open file in read mode

        $contents = fread($fp, filesize($filename));//read file

        echo "<pre>$contents</pre>";//printing data of file
        fclose($fp);//close file

    }

    public function getaa() {
        $disk_free_memory = memory_get_usage();

        // Specify memory unit
        $memory_unit = array('Bytes','KB','MB','GB','TB','PB');

        // Display memory size into kb, mb etc.
        echo 'All Used Memory : '.round($disk_free_memory/pow(1024,($x=floor(log($disk_free_memory,1024)))),2).' '.$memory_unit[$x]."\n";
    }

    public function index2() {
        // try {
        // $ddd = "";
        // //echo $dd;
        // } catch (Exception $e) {
        //     echo "Exception";
        //     print_r($e);
        // }
    }
	
	
	public function Sam_to_fam_generate_old()
    {
        exit();
		
        $sql = "SELECT distinct tbl_saf_dtl.id as saf_id,saf_no,holding_no,wname,wmobile,prop_address,holding_type,assessment_type,ward_mstr_id from tbl_saf_dtl
                join (select saf_dtl_id from tbl_saf_memo_dtl where memo_type= 'SAM') as meno on meno.saf_dtl_id=tbl_saf_dtl.id
                join (select saf_dtl_id from tbl_level_pending_dtl where sender_user_type_id=5) level on level.saf_dtl_id=tbl_saf_dtl.id
                join (
                    select saf_dtl_id,array_to_string(array_agg(owner_name), ',') as wname ,array_to_string(array_agg(mobile_no), ',') as wmobile from tbl_saf_owner_detail where status=1 group by saf_dtl_id
                    ) w on w.saf_dtl_id = tbl_saf_dtl.id
                join(
                    select a.saf_dtl_id,b.id from tbl_saf_floor_details a
                    join tbl_field_verification_floor_details b 
                    on b.saf_floor_dtl_id=a.id and b.floor_mstr_id=a.floor_mstr_id 
                    and b.usage_type_mstr_id=a.usage_type_mstr_id
                    and b.const_type_mstr_id=a.const_type_mstr_id 
                    and b.occupancy_type_mstr_id=a.occupancy_type_mstr_id 
                    and b.builtup_area=a.builtup_area 
                    and b.date_from=a.date_from 
                    and b.date_upto=a.date_upto
                    and b.carpet_area=a.carpet_area
                    where b.verified_by='AGENCY TC' group by a.saf_dtl_id,b.id order by b.id desc
                ) veri on veri.saf_dtl_id=tbl_saf_dtl.id
                where saf_pending_status!=1 and holding_no!=''";

        $result = $this->db->query($sql)->getResultArray();
		
        //field verification agency tc to ulb tc
        foreach ($result as $res) {
            $field_verification = $this->db->table('tbl_field_verification_dtl');
            $verification = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])->where('status', 1); //data get
            $ulbTcCount = $verification->where('verified_by', 'ULB TC')->countAllResults();
            
            $verificationp = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])
                                                                ->where('status', 1)
                                                                ->where('verified_by', 'AGENCY TC')
                                                                ->orderBy('id', 'desc')
                                                                ->get()
                                                                ->getFirstRow();
            if($ulbTcCount == 0)
            {
                

                $ulbTc_id = 7;
                
                $data = [
                    'saf_dtl_id' => $res['saf_id'],
                    'prop_type_mstr_id'  => $verificationp->prop_type_mstr_id,
                    'road_type_mstr_id'  => $verificationp->road_type_mstr_id,
                    'area_of_plot'  => $verificationp->area_of_plot,
                    'verified_by_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                    'created_on'  => date('Y-m-d H:i:s'),
                    'status'  => $verificationp->status,
                    'ward_mstr_id'  => $verificationp->ward_mstr_id,
                    'is_mobile_tower'  => $verificationp->is_mobile_tower,
                    'tower_area'  => $verificationp->tower_area,
                    'tower_installation_date'  => $verificationp->tower_installation_date,
                    'is_hoarding_board'  => $verificationp->is_hoarding_board,
                    'hoarding_area'  => $verificationp->hoarding_area,
                    'hoarding_installation_date'  => $verificationp->hoarding_installation_date,
                    'is_petrol_pump'  => $verificationp->is_petrol_pump,
                    'under_ground_area'  => $verificationp->under_ground_area,
                    'petrol_pump_completion_date'  => $verificationp->petrol_pump_completion_date,
                    'is_water_harvesting'  => $verificationp->is_water_harvesting,
                    'verified_by'  => "ULB TC",
                    'zone_mstr_id'  => $verificationp->zone_mstr_id,
                    'percentage_of_property_transfer'  => $verificationp->percentage_of_property_transfer,
                    'new_ward_mstr_id'  => $verificationp->new_ward_mstr_id
                ];

                $field_verification->insert($data);
                $verification_id = $this->db->insertID();

                $field_floor_verification = $this->db->table('tbl_field_verification_floor_details');
                $floor_verifications = $field_floor_verification->select('*')->where('field_verification_dtl_id', $verificationp->id)->get();
                $floor_verifications = $floor_verifications->getResult();
                foreach($floor_verifications as $floor)
                {
                    $field_floor = $this->db->table('tbl_field_verification_floor_details');
                    $data1 = [
                        'field_verification_dtl_id' => $verification_id,
                        'saf_dtl_id' => $res['saf_id'],
                        'saf_floor_dtl_id'  => $floor->saf_floor_dtl_id,
                        'floor_mstr_id'  => $floor->floor_mstr_id,
                        'usage_type_mstr_id'  => $floor->usage_type_mstr_id,
                        'const_type_mstr_id'  => $floor->const_type_mstr_id,
                        'occupancy_type_mstr_id'  => $floor->occupancy_type_mstr_id,
                        'builtup_area'  => $floor->builtup_area,
                        'date_from'  => $floor->date_from,
                        'date_upto'  => $floor->date_upto,
                        'emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        'status'  => $floor->status,
                        'carpet_area'  => $floor->carpet_area,
                        'verified_by'  => "ULB TC",
                        'created_on'  => date('Y-m-d H:i:s'),
                    ];
                    $field_floor->insert($data1);
                
                }
            }


            // LEVEL ENTRY
            $level_pending = $this->db->table('tbl_level_pending_dtl');
            $pending_at_level = $level_pending->where('saf_dtl_id', $res['saf_id'])
                                                ->where('status', 1)
                                                ->where('verification_status', 0)
                                                ->orderBy('id', 'DESC')
                                                ->get();
            $pending_at_level = $pending_at_level->getFirstRow();
            
            $aTc_id = 5;
            $ulbTc_id = 7;
            $section_incharge = 9;
            $eo = 10;
            $status = 0;
            $verification_status = 1;
            $assessment_type = $res['assessment_type'];
            $empDtlId = 0;

            if ($assessment_type == 'New Assessment' || $assessment_type == 'Mutation') 
            {
                $empDtlId = $this->getEmp($eo, $verificationp->ward_mstr_id);
                if($pending_at_level->sender_user_type_id == '5' && $pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->sender_user_type_id == '7' && $pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }

                
                
            } else {
                $empDtlId = $this->getEmp($section_incharge, $verificationp->ward_mstr_id);
                if($pending_at_level->sender_user_type_id == '5' && $pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->sender_user_type_id == '7' && $pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
                    ];
                }

            }
            
            $level_pending->insertBatch($data1);
                
            $updata = [
                'status' => 0,
                'verification_status'  => 1,
                'receiver_emp_details_id'  => $this->getEmp($pending_at_level->receiver_user_type_id, $verificationp->ward_mstr_id),
            ];
            
            $level_pending->where('id', $pending_at_level->id);
            $level_pending->update($updata);

            //memo_entry
            $memo_type = $this->db->table('tbl_saf_memo_dtl');
            $memo = $memo_type->select('tbl_saf_memo_dtl.*, ward_no')->Join('view_ward_mstr', 'tbl_saf_memo_dtl.ward_mstr_id = view_ward_mstr.id', 'left')->where('saf_dtl_id', $res['saf_id'])->get();
            
            $memo = $memo->getFirstRow();

            $status = 1;
            $memoDate = [
                'saf_dtl_id' => $memo->saf_dtl_id,
                'fy_mstr_id'  => $memo->fy_mstr_id,
                'effect_quarter'  => $memo->effect_quarter,
                'arv'  => $memo->arv,
                'quarterly_tax'  => $memo->quarterly_tax,
                'emp_details_id'  => $empDtlId,
                'memo_type'  => "FAM",
                'holding_no'  => $memo->holding_no,
                'fy'  => $memo->fy,
                'status'  => $status,
                'prop_dtl_id'  => $memo->prop_dtl_id,
                'ward_mstr_id'  => $memo->ward_mstr_id,
                'created_on'  => date('Y-m-d H:i:s'),
            ];

            $memo_type->insert($memoDate);
            $memo_id=$this->db->insertID();
            
            if($memo_id > 0)
            {
                $new_memo_no = "FAM". "/". $memo->ward_no. "/". $memo_id. "/". $memo->fy;
                $memo_type->set('memo_no', $new_memo_no);
                $memo_type->where('id', $memo_id);
                $memo_type->update();
            }

            $saf_dtl = $this->db->table('tbl_saf_dtl');
            $saf_dtl->set('saf_pending_status', 1);
            $saf_dtl->where('id', $res['saf_id']);
            $saf_dtl->update();

            echo "FAM generated successfully";
            
        }
	}
	
/*     public function Sam_to_fam_generate_new()
    {
        $sql = "SELECT distinct saf.id as saf_id,saf.saf_no,saf.prop_type_mstr_id,saf.road_type_mstr_id,saf.area_of_plot,saf.ward_mstr_id,
        saf.is_mobile_tower,saf.tower_area,saf.tower_installation_date,
        saf.is_hoarding_board,saf.hoarding_area,saf.hoarding_installation_date,
        saf.is_petrol_pump,saf.under_ground_area,saf.petrol_pump_completion_date,
        saf.is_water_harvesting,saf.zone_mstr_id,saf.percentage_of_property_transfer,saf.new_ward_mstr_id,saf.assessment_type from tbl_saf_dtl saf
        join (select * from tbl_saf_memo_dtl where memo_type='SAM') memo ON memo.saf_dtl_id = saf.id
        left join (select * from tbl_saf_memo_dtl where memo_type='FAM') memo1 ON memo1.saf_dtl_id = saf.id
		join (select saf_dtl_id from tbl_level_pending_dtl where sender_user_type_id=5 and verification_status=1) level on level.saf_dtl_id=saf.id
        where (saf.apply_date BETWEEN '2016-04-01' and '2022-04-30') and saf_pending_status!=1 and (saf.assessment_type='New Assessment' or saf.assessment_type='Reassessment') and saf.holding_no!='' and memo1.id is null and saf.id not in(83119,42989,90865,111197,132974,154509,174124,199276,120118,132994,224853) order by saf.id asc";

        $result = $this->db->query($sql)->getResultArray();

        //field verification agency tc to ulb tc
        foreach ($result as $res) {
            $field_verification = $this->db->table('tbl_field_verification_dtl');
            $verification = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])->where('status', 1); //data get
            $ulbTcCount = $verification->where('verified_by', 'ULB TC')->countAllResults();
            
            $verificationp = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])
                                                                ->where('status', 1)
                                                                ->where('verified_by', 'AGENCY TC')
                                                                ->orderBy('id', 'desc')
                                                                ->get()
                                                                ->getFirstRow();
            if($ulbTcCount == 0)
            {
                
                $ulbTc_id = 7;
                if($verificationp->verified_by=='AGENCY TC')
                {
                    $data = [
                        'saf_dtl_id' => $res['saf_id'],
                        'prop_type_mstr_id'  => $verificationp->prop_type_mstr_id,
                        'road_type_mstr_id'  => $verificationp->road_type_mstr_id,
                        'area_of_plot'  => $verificationp->area_of_plot,
                        'verified_by_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        'created_on'  => date('Y-m-d H:i:s'),
                        'status'  => $verificationp->status,
                        'ward_mstr_id'  => $verificationp->ward_mstr_id,
                        'is_mobile_tower'  => $verificationp->is_mobile_tower,
                        'tower_area'  => $verificationp->tower_area,
                        'tower_installation_date'  => $verificationp->tower_installation_date,
                        'is_hoarding_board'  => $verificationp->is_hoarding_board,
                        'hoarding_area'  => $verificationp->hoarding_area,
                        'hoarding_installation_date'  => $verificationp->hoarding_installation_date,
                        'is_petrol_pump'  => $verificationp->is_petrol_pump,
                        'under_ground_area'  => $verificationp->under_ground_area,
                        'petrol_pump_completion_date'  => $verificationp->petrol_pump_completion_date,
                        'is_water_harvesting'  => $verificationp->is_water_harvesting,
                        'verified_by'  => "ULB TC",
                        'zone_mstr_id'  => $verificationp->zone_mstr_id,
                        'percentage_of_property_transfer'  => $verificationp->percentage_of_property_transfer,
                        'new_ward_mstr_id'  => $verificationp->new_ward_mstr_id
                    ];

                    $field_verification->insert($data);
                    $verification_id = $this->db->insertID();

                    $field_floor_verification = $this->db->table('tbl_field_verification_floor_details');
                    $floor_verifications = $field_floor_verification->select('*')->where('field_verification_dtl_id', $verificationp->id)->get();
                    $floor_verifications = $floor_verifications->getResult();
                    foreach($floor_verifications as $floor)
                    {
                        $field_floor = $this->db->table('tbl_field_verification_floor_details');
                        $data1 = [
                            'field_verification_dtl_id' => $verification_id,
                            'saf_dtl_id' => $res['saf_id'],
                            'saf_floor_dtl_id'  => $floor->saf_floor_dtl_id,
                            'floor_mstr_id'  => $floor->floor_mstr_id,
                            'usage_type_mstr_id'  => $floor->usage_type_mstr_id,
                            'const_type_mstr_id'  => $floor->const_type_mstr_id,
                            'occupancy_type_mstr_id'  => $floor->occupancy_type_mstr_id,
                            'builtup_area'  => $floor->builtup_area,
                            'date_from'  => $floor->date_from,
                            'date_upto'  => $floor->date_upto,
                            'emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'status'  => $floor->status,
                            'carpet_area'  => $floor->carpet_area,
                            'verified_by'  => "ULB TC",
                            'created_on'  => date('Y-m-d H:i:s'),
                        ];
                        $field_floor->insert($data1);
                    
                    }
                }
            }
            


            // LEVEL ENTRY
            $level_pending = $this->db->table('tbl_level_pending_dtl');
            $level_bugfix_pending = $this->db->table('tbl_bugfix_level_pending_dtl');
            $pending_at_level = $level_pending->where('saf_dtl_id', $res['saf_id'])
                                                ->where('status', 1)
                                                ->orderBy('id', 'DESC')
                                                ->get();
            $pending_at_level = $pending_at_level->getFirstRow();
            
            $dealing = 6;
            $aTc_id = 5;
            $ulbTc_id = 7;
            $section_incharge = 9;
            $eo = 10;
            $status = 0;
            $verification_status = 1;
            $assessment_type = $res['assessment_type'];
            $empDtlId = 0;
            $ward = $this->db->table('view_ward_mstr')->where('id', $res['ward_mstr_id'])->get();
            $ward = $ward->getFirstRow();
            $wardNo = $ward->ward_no;
            $data1 = array();
            

            if ($assessment_type == 'New Assessment' || $assessment_type == 'Mutation') 
            {
                $empDtlId = $this->getEmp($eo, $verificationp->ward_mstr_id);
                if($pending_at_level->receiver_user_type_id == '6')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $dealing,
                            'receiver_user_type_id'  => $aTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($dealing, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }
                else if($pending_at_level->receiver_user_type_id == '5')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }else if($pending_at_level->receiver_user_type_id == '10'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }

                
                
            } else {
                $empDtlId = $this->getEmp($section_incharge, $verificationp->ward_mstr_id);
                if($pending_at_level->receiver_user_type_id == '6')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $dealing,
                            'receiver_user_type_id'  => $aTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($dealing, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }
                else if($pending_at_level->receiver_user_type_id == '5')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
                    ];
                }

            }
            
            if($data1)
                $level_pending->insertBatch($data1);
                $level_bugfix_pending->insertBatch($data1);
                
            $updata = [
                'status' => 0,
                'verification_status'  => 1,
                'receiver_emp_details_id'  => $this->getEmp($pending_at_level->receiver_user_type_id, $verificationp->ward_mstr_id),
            ];
            
            $level_pending->where('id', $pending_at_level->id);
            $level_pending->update($updata);

            
            $login_emp_details_id = $empDtlId;
            $memo=$this->model_saf_memo_dtl->generate_assessment_final_memo($res['saf_id'], $login_emp_details_id);
            $genmemo_last_id=$memo["generate_assessment_final_memo"];

            $safHelper = new SAFHelper($this->db);
            $input = ['saf_dtl_id' => $res['saf_id']];
            $propDtlId = $this->model_prop_dtl->getPropDtlIdBySafDtlId($input);
            $prop_dtl_id = $propDtlId['id'];

            
            $inputs["prop_type_mstr_id"] = $res["prop_type_mstr_id"];
            $inputs["road_type_mstr_id"] = $res["road_type_mstr_id"];
            $inputs["area_of_plot"] = $res["area_of_plot"];
            $inputs["tower_area"] = $res["tower_area"];
            $inputs["tower_installation_date"] = $res["tower_installation_date"];
            $inputs["hoarding_area"] = $res["hoarding_area"];
            $inputs['hoarding_installation_date'] = $res['hoarding_installation_date'];
            $inputs["under_ground_area"] = $res["under_ground_area"];
            $inputs["petrol_pump_completion_date"] = $res["petrol_pump_completion_date"];
            $inputs["percentage_of_property_transfer"] = $res["percentage_of_property_transfer"];
            $inputs["zone_mstr_id"] = $res["zone_mstr_id"];
            $inputs["ward_mstr_id"] = $res["ward_mstr_id"];
            $inputs["new_ward_mstr_id"] = $res["new_ward_mstr_id"];
            if ($res["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
            if ($res["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
            if ($res["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
            if ($res["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
            if ($res['prop_type_mstr_id'] == 4) {
                $inputs["land_occupation_date"] = $propDtlId["land_occupation_date"];

                $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);                    
                list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($res['saf_id']));
                if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                    $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                    $this->calDiffSafDemand($newSafTaxDtl, $res['saf_id'], $prop_dtl_id, $res['ward_mstr_id']);
                }
            } else {
                if ($FV_Saf_floor_Dtl = $this->model_field_verification_floor_details->getUlbDataBySafDtlId($input)) {
                    $floorKey = 0;
                    foreach ($FV_Saf_floor_Dtl as $key => $value) {
                        $inputs["floor_mstr_id"][$floorKey] = $value["floor_mstr_id"];
                        $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                        $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                        $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                        $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                        $inputs["date_from"][$floorKey] = date("Y-m", strtotime($value["date_from"]));
                        $inputs["date_upto"][$floorKey] = "";
                        if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                            $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                        }
                        $floorKey++;
                    }
                }
                $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                $data['floorDtlArr'] = $floorDtlArr;
                $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                
                list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                
                $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($res['saf_id']));
                
                if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                    
                    $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                    $this->calDiffSafDemand($newSafTaxDtl, $res['saf_id'], $prop_dtl_id, $res['ward_mstr_id']);
                }
            }

            //memo_entry
            $prop_tax = $this->db->table('tbl_prop_tax')->where('prop_dtl_id', $prop_dtl_id)->where('status', 1)->orderBy('id', 'DESC')->get();
            $prop_tax = $prop_tax->getFirstRow();
            if(!$prop_tax)
            {
                $prop_tax = $this->db->table('tbl_saf_tax')->where('saf_dtl_id', $res['saf_id'])->where('status', 1)->orderBy('id', 'DESC')->get();
                $prop_tax = $prop_tax->getFirstRow();
            }
            
            $memo_type = $this->db->table('tbl_saf_memo_dtl');
            $memo = $memo_type->where('saf_dtl_id', $res['saf_id'])->where('memo_type', 'FAM')->get();
            
            $memo = $memo->getFirstRow();
            
            if($memo->id > 0)
            {
                $fy_year = $this->db->table('view_fy_mstr')->where('fy', $prop_tax->fyear)->where('status', 1)->orderBy('id', 'DESC')->get();
                $fy_year = $fy_year->getFirstRow();
                $new_memo_no = "FAM". "/". str_pad($wardNo, 3, "0", STR_PAD_LEFT). "/". $memo->id. "/". $prop_tax->fyear;
                $memo_type->set('fy_mstr_id', $fy_year->id);
                $memo_type->set('arv', $prop_tax->arv);
                $memo_type->set('quarterly_tax', ($prop_tax->holding_tax+$prop_tax->water_tax+$prop_tax->education_cess+$prop_tax->latrine_tax+$prop_tax->additional_tax));
                $memo_type->set('prop_dtl_id', $prop_dtl_id);
                $memo_type->set('fy', $prop_tax->fyear);
                $memo_type->set('memo_no', $new_memo_no);
                $memo_type->set('ward_mstr_id', $res['ward_mstr_id']);
                $memo_type->where('id', $memo->id);
                $memo_type->update();
            }

            $saf_dtl = $this->db->table('tbl_saf_dtl');
            $saf_dtl->set('saf_pending_status', 1);
            $saf_dtl->where('id', $res['saf_id']);
            $saf_dtl->update();

             echo "FAM generated successfully";
            
        }

        
    } */

    public function getEmp($userType, $ward_mstr_id)
    {
        $sqlemp = "SELECT a.id FROM view_emp_details a
                        JOIN view_ward_permission b on b.emp_details_id=a.id
                        WHERE user_type_id=".$userType." and ward_mstr_id=".$ward_mstr_id." and user_mstr_lock_status=0 order by id desc limit 1";
                
        $empdata = $this->db->query($sqlemp)->getFirstRow();
        return $empdata->id??1;
    }


    /* public function calDiffSafDemand($safTaxDtl, $saf_dtl_id, $prop_dtl_id, $ward_mstr_id)
	{
		$currentFY = getFY();
		$currentFY = "2022-2023";

		$demandDtl = [];
        
		foreach ($safTaxDtl as $key => $taxDtl) {
			$pymt_frm_qtr = (int)$taxDtl['qtr'];
			$pymt_frm_year = (string)$taxDtl['fyear'];

			$pymt_upto_qtr = (int)4;
			$pymt_upto_year = (string)$currentFY;
			if ($key < sizeof($safTaxDtl) - 1) {
				$pymt_upto_qtr = (int)$safTaxDtl[$key + 1]['qtr'] - 1;
				$pymt_upto_year = (string)$safTaxDtl[$key + 1]['fyear'];
			}
			list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
			list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);

			if ($taxDtl['arv'] >= 0) {
				// tax insert code
				$arv = $holding_tax = $water_tax = $education_cess = $health_cess = $latrine_tax = $additional_tax = $quarterly_tax = 0;
				if ($taxDtl["rule_type"] == "OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$water_tax = $taxDtl["water_tax"];
					$education_cess = $taxDtl["education_cess"];
					$health_cess = $taxDtl["health_cess"];
					$latrine_tax = $taxDtl["latrine_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				} else if ($taxDtl["rule_type"] != "OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				}
                
                $floorDateFromFyID = $this->getFyID($taxDtl['fyear']);
                
				$input = [
					'prop_dtl_id' => $prop_dtl_id, 'fyear' => $taxDtl['fyear'], 'qtr' => $taxDtl['qtr'], 'arv' => $arv, 'holding_tax' => $holding_tax, 'water_tax' => $water_tax, 'education_cess' => $education_cess, 'health_cess' => $health_cess, 'latrine_tax' => $latrine_tax, 'created_on' => date("Y-m-d H:i:s"), 'status' => 1
				];
				//echo "<br />Tax Query<br /><br />";
				//$prop_tax_id = $this->model_prop_tax->insertData($input);

				$sql = "INSERT INTO tbl_prop_tax (prop_dtl_id, fy_mstr_id, quarterly_tax, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status)
				VALUES ('$prop_dtl_id', '".$floorDateFromFyID."', '".$quarterly_tax."', '" . $taxDtl['fyear'] . "', '" . $taxDtl['qtr'] . "', '$arv', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '" . date("Y-m-d H:i:s") . "', 1) returning id";
				$query = $this->db->query($sql);
				$return = $query->getFirstRow("array");
				$prop_tax_id = $return["id"];

				// end tax insert code 
				while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) {
					$newFY = $from_y1_new . "-" . $from_y2_new;
					$till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
					for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
						$newFY = $from_y1_new . "-" . $from_y2_new;
						$demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
						$due_date = $this->makeDueDateByFyearQtr($newFY, $q);
						$demandDtlTemp = [
							"fyear" => $newFY,
							"qtr" => $q,
							'due_date' => $due_date,
							"demand" => $demandAmt,
							"additional_tax" => $taxDtl['additional_tax'],
							"total_tax" => $taxDtl['quarterly_tax']
						];
						$demandDtl[] = $demandDtlTemp;

						if ($prop_tax_id != "") {
							$input = [
								'prop_dtl_id' => $prop_dtl_id,
								'fyear' => $newFY,
								'qtr' => $q,
								'due_date' => $due_date,
								'amount' => $demandAmt,
								'balance' => $demandAmt,
								'fine_tax' => 0
							];
							//echo "<br />CAL DEMAND<br />";
							$sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status IN (0,1) AND due_date='" . $due_date . "'
									UNION
									SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_saf_demand WHERE saf_dtl_id=" . $saf_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "') AS tbl_demand
									GROUP BY due_date
									ORDER BY due_date";
							$total_result = $this->db->query($sql);
							if ($total_prev_demand = $total_result->getFirstRow("array")) {
								$demandAmt = $demandAmt - $total_prev_demand["total_amount"];
							}
						}
						 
						if ($demandAmt > 0) {
							$input = [
								'prop_dtl_id' => $prop_dtl_id,
								'prop_tax_id' => $prop_tax_id,
                                'fy_mstr_id' => $this->getFyID($newFY),
                                'ward_mstr_id' => $ward_mstr_id,
								'fyear' => $newFY,
								'qtr' => $q,
								'due_date' => $due_date,
								'amount' => $demandAmt,
								'balance' => $demandAmt,
								'fine_tax' => 0,
								'created_on' => date("Y-m-d H:i:s"),
								'status' => 1,
                                'paid_status' => 0,
                                'demand_amount' => $demandAmt,
                                'additional_amount' => 0.00,
                                'adjust_amt' => 0.00,
							];
							$this->model_prop_demand->insertData($input);
							//echo "<br />Demand Query<br />";
							//echo $this->db->getLastQuery();
						}
					}
					$pymt_frm_qtr = 1;
					$from_y1_new++;
					$from_y2_new++;
				}
			}
		}
		return $demandDtl;
	} */
	
	
	public function Sam_to_fam_generate_new_old()
    {
        exit();

        $sql = "SELECT distinct saf.id as saf_id,saf.saf_no,saf.prop_type_mstr_id,saf.road_type_mstr_id,saf.area_of_plot,saf.ward_mstr_id,
        saf.is_mobile_tower,saf.tower_area,saf.tower_installation_date,
        saf.is_hoarding_board,saf.hoarding_area,saf.hoarding_installation_date,
        saf.is_petrol_pump,saf.under_ground_area,saf.petrol_pump_completion_date,
        saf.is_water_harvesting,saf.zone_mstr_id,saf.percentage_of_property_transfer,saf.new_ward_mstr_id,saf.assessment_type from tbl_saf_dtl saf
        join (select * from tbl_saf_memo_dtl where memo_type='SAM') memo ON memo.saf_dtl_id = saf.id
        left join (select * from tbl_saf_memo_dtl where memo_type='FAM') memo1 ON memo1.saf_dtl_id = saf.id
		join (select saf_dtl_id from tbl_level_pending_dtl where receiver_user_type_id=5 and verification_status=1) level on level.saf_dtl_id=saf.id
        join(
			select v.saf_dtl_id from tbl_field_verification_dtl v
			join tbl_saf_dtl s on v.saf_dtl_id=s.id 
			and v.prop_type_mstr_id=s.prop_type_mstr_id 
			and v.road_type_mstr_id=s.road_type_mstr_id 
			and v.area_of_plot=s.area_of_plot
			and v.is_mobile_tower=s.is_mobile_tower
			and v.is_hoarding_board=s.is_hoarding_board
			and v.is_petrol_pump=s.is_petrol_pump
			and v.is_water_harvesting=s.is_water_harvesting and v.verified_by='AGENCY TC' group by v.saf_dtl_id
		) veri on veri.saf_dtl_id=saf.id
		
		where (saf.apply_date BETWEEN '2016-04-01' and '2022-04-30') and saf_pending_status!=1 and (saf.assessment_type='Reassessment') and saf.holding_no!='' and memo1.id is null order by saf.id asc";

        $result = $this->db->query($sql)->getResultArray();

        //field verification agency tc to ulb tc
        foreach ($result as $res) {
            $field_verification = $this->db->table('tbl_field_verification_dtl');
            $verification = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])->where('status', 1); //data get
            $ulbTcCount = $verification->where('verified_by', 'ULB TC')->countAllResults();
            
            $verificationp = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])
                                                                ->where('status', 1)
                                                                ->where('verified_by', 'AGENCY TC')
                                                                ->orderBy('id', 'desc')
                                                                ->get()
                                                                ->getFirstRow();
            if($ulbTcCount == 0)
            {
                
                $ulbTc_id = 7;
                if($verificationp->verified_by=='AGENCY TC')
                {
                    $data = [
                        'saf_dtl_id' => $res['saf_id'],
                        'prop_type_mstr_id'  => $verificationp->prop_type_mstr_id,
                        'road_type_mstr_id'  => $verificationp->road_type_mstr_id,
                        'area_of_plot'  => $verificationp->area_of_plot,
                        'verified_by_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        'created_on'  => date('Y-m-d H:i:s'),
                        'status'  => $verificationp->status,
                        'ward_mstr_id'  => $verificationp->ward_mstr_id,
                        'is_mobile_tower'  => $verificationp->is_mobile_tower,
                        'tower_area'  => $verificationp->tower_area,
                        'tower_installation_date'  => $verificationp->tower_installation_date,
                        'is_hoarding_board'  => $verificationp->is_hoarding_board,
                        'hoarding_area'  => $verificationp->hoarding_area,
                        'hoarding_installation_date'  => $verificationp->hoarding_installation_date,
                        'is_petrol_pump'  => $verificationp->is_petrol_pump,
                        'under_ground_area'  => $verificationp->under_ground_area,
                        'petrol_pump_completion_date'  => $verificationp->petrol_pump_completion_date,
                        'is_water_harvesting'  => $verificationp->is_water_harvesting,
                        'verified_by'  => "ULB TC",
                        'zone_mstr_id'  => $verificationp->zone_mstr_id,
                        'percentage_of_property_transfer'  => $verificationp->percentage_of_property_transfer,
                        'new_ward_mstr_id'  => $verificationp->new_ward_mstr_id
                    ];

                    $field_verification->insert($data);
                    $verification_id = $this->db->insertID();

                    $field_floor_verification = $this->db->table('tbl_field_verification_floor_details');
                    $floor_verifications = $field_floor_verification->select('*')->where('field_verification_dtl_id', $verificationp->id)->get();
                    $floor_verifications = $floor_verifications->getResult();
                    foreach($floor_verifications as $floor)
                    {
                        $field_floor = $this->db->table('tbl_field_verification_floor_details');
                        $data1 = [
                            'field_verification_dtl_id' => $verification_id,
                            'saf_dtl_id' => $res['saf_id'],
                            'saf_floor_dtl_id'  => $floor->saf_floor_dtl_id,
                            'floor_mstr_id'  => $floor->floor_mstr_id,
                            'usage_type_mstr_id'  => $floor->usage_type_mstr_id,
                            'const_type_mstr_id'  => $floor->const_type_mstr_id,
                            'occupancy_type_mstr_id'  => $floor->occupancy_type_mstr_id,
                            'builtup_area'  => $floor->builtup_area,
                            'date_from'  => $floor->date_from,
                            'date_upto'  => $floor->date_upto,
                            'emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'status'  => $floor->status,
                            'carpet_area'  => $floor->carpet_area,
                            'verified_by'  => "ULB TC",
                            'created_on'  => date('Y-m-d H:i:s'),
                        ];
                        $field_floor->insert($data1);
                    
                    }
                }
            }
            


            // LEVEL ENTRY
            $level_pending = $this->db->table('tbl_level_pending_dtl');
            $level_bugfix_pending = $this->db->table('tbl_bugfix_level_pending_dtl');
            $pending_at_level = $level_pending->where('saf_dtl_id', $res['saf_id'])
                                                ->orderBy('id', 'DESC')
                                                ->get();
            $pending_at_level = $pending_at_level->getFirstRow();

            $pending_at_level_bug = $level_bugfix_pending->where('saf_dtl_id', $res['saf_id'])
                                                ->orderBy('id', 'DESC')
                                                ->get();
            $pending_at_level_bug = $pending_at_level_bug->getFirstRow();
            
            $dealing = 6;
            $aTc_id = 5;
            $ulbTc_id = 7;
            $section_incharge = 9;
            $eo = 10;
            $status = 0;
            $verification_status = 1;
            $assessment_type = $res['assessment_type'];
            $empDtlId = 0;
            $ward = $this->db->table('view_ward_mstr')->where('id', $res['ward_mstr_id'])->get();
            $ward = $ward->getFirstRow();
            $wardNo = $ward->ward_no;
            $data1 = array();
            

            if ($assessment_type == 'New Assessment' || $assessment_type == 'Mutation') 
            {
                $empDtlId = $this->getEmp($eo, $verificationp->ward_mstr_id);
                if($pending_at_level->receiver_user_type_id == '6')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $dealing,
                            'receiver_user_type_id'  => $aTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($dealing, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }
                else if($pending_at_level->receiver_user_type_id == '5')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }else if($pending_at_level->receiver_user_type_id == '10'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }

                
                
            } else {
                $empDtlId = $this->getEmp($section_incharge, $verificationp->ward_mstr_id);
                if($pending_at_level->receiver_user_type_id == '6')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $dealing,
                            'receiver_user_type_id'  => $aTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($dealing, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }
                else if($pending_at_level->receiver_user_type_id == '5')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
                    ];
                }

            }
            
            
            if($data1)
                $level_pending->insertBatch($data1);
                $level_bugfix_pending->insertBatch($data1);
                
            $updata = [
                'status' => 0,
                'verification_status'  => 1,
                'receiver_emp_details_id'  => $this->getEmp($pending_at_level->receiver_user_type_id, $verificationp->ward_mstr_id),
            ];
            
            $level_pending->where('id', $pending_at_level->id);
            $level_pending->update($updata);

            $level_bugfix_pending->where('id', $pending_at_level_bug->id);
            $level_bugfix_pending->update($updata);
            
            $input = ['saf_dtl_id' => $res['saf_id']];
            $propDtlId = $this->model_prop_dtl->getPropDtlIdBySafDtlId($input);
            $prop_dtl_id = $propDtlId['id'];

            //memo_entry
            $prop_tax = $this->db->table('tbl_prop_tax')->where('prop_dtl_id', $prop_dtl_id)->where('status', 1)->orderBy('id', 'DESC')->get();
            $prop_tax = $prop_tax->getFirstRow();
            if(!$prop_tax)
            {
                $prop_tax = $this->db->table('tbl_saf_tax')->where('saf_dtl_id', $res['saf_id'])->where('status', 1)->orderBy('id', 'DESC')->get();
                $prop_tax = $prop_tax->getFirstRow();
            }

            $memo_type = $this->db->table('tbl_saf_memo_dtl');
            $fy_year = $this->db->table('view_fy_mstr')->where('fy', $prop_tax->fyear)->where('status', 1)->orderBy('id', 'DESC')->get()->getFirstRow('array');
            $memoDate = [
                'saf_dtl_id' => $res['saf_id'],
                'fy_mstr_id'  => $fy_year['id'],
                'effect_quarter'  => $prop_tax->qtr,
                'arv'  => $prop_tax->arv,
                'quarterly_tax'  => ($prop_tax->holding_tax+$prop_tax->water_tax+$prop_tax->education_cess+$prop_tax->latrine_tax+$prop_tax->additional_tax),
                'emp_details_id'  => $empDtlId,
                'memo_type'  => "FAM",
                'holding_no'  => $propDtlId['holding_no'],
                'fy'  => $prop_tax->fyear,
                'status'  => 1,
                'prop_dtl_id'  => $prop_dtl_id,
                'ward_mstr_id'  => $res['ward_mstr_id'],
                'created_on'  => date('Y-m-d H:i:s'),
            ];

            $memo_type->insert($memoDate);
            $memo_id=$this->db->insertID();
            
            if($memo_id > 0)
            {
                $new_memo_no = "FAM". "/". str_pad($wardNo, 3, "0", STR_PAD_LEFT). "/". $memo_id. "/". $prop_tax->fyear;
                $memo_type->set('memo_no', $new_memo_no);
                $memo_type->where('id', $memo_id);
                $memo_type->update();
            }
            

            $saf_dtl = $this->db->table('tbl_saf_dtl');
            $saf_dtl->set('saf_pending_status', 1);
            $saf_dtl->where('id', $res['saf_id']);
            $saf_dtl->update();

             echo "FAM generated successfully";
            
        }

        
    }

    public function getFyID($FY)
	{
		return $this->model_fy_mstr->getFyByFy(['fy' => $FY])['id'];
	}

    public function makeDueDateByFyearQtr($fyear, $qtr)
	{
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr == 1) {
			return $fyear1 . "-06-30";
		} else if ($qtr == 2) {
			return $fyear1 . "-09-30";
		} else if ($qtr == 3) {
			return $fyear1 . "-12-31";
		} else if ($qtr == 4) {
			return $fyear2 . "-03-31";
		}
	}

    public function onePercentPenalty($prop_dtl_id, $demand_amount, $newFY, $q, $paymentDate)
    {
        $penalty_sql ="select * from prop_get1percentpenalty_month(".$prop_dtl_id.", '".$demand_amount."', '".$newFY."', '".$q."','".$paymentDate."')";
        $penalty = $this->db->query($penalty_sql)->getFirstRow();
        return $penalty->fine_amt;
    }

    public function GenDemandWithCreateAdvance()
    {
        exit();

        $currentFY = "2023-2024";
        $safHelper = new SAFHelper($this->db);
        $newsafHelper = new NEW_SAFHelper($this->db);
        $sql = "SELECT tbl_prop_dtl.*,tbl_saf_dtl.saf_pending_status,tbl_prop_tax.prop_dtl_id FROM tbl_prop_dtl
                JOIN (select max(id) as proptaxid,prop_dtl_id from tbl_prop_tax where fYear='2022-2023' or tbl_prop_tax.fy_mstr_id=53 and created_on::date<now()::date and status=1 group by prop_dtl_id) tbl_prop_tax on tbl_prop_tax.prop_dtl_id=tbl_prop_dtl.id
                LEFT JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                LEFT JOIN log_tbl_prop_avg_cal on log_tbl_prop_avg_cal.prop_dtl_id=tbl_prop_dtl.id
                LEFT JOIN (select prop_dtl_id from tbl_prop_demand where status=1 and fy_mstr_id=54 group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                WHERE tbl_prop_dtl.status=1 and tbl_prop_dtl.govt_saf_dtl_id is null and tbl_prop_dtl.prop_type_mstr_id!=4 
                and log_tbl_prop_avg_cal.prop_dtl_id is null and tbl_prop_demand.prop_dtl_id is null and tbl_prop_dtl.new_holding_no in(
'0370001002700A5'
)";

        $resultArrs = $this->db->query($sql)->getResultArray();
        
        if($resultArrs)
        {
            foreach($resultArrs as $resultArr)
            {   
                
                $prop_dtl_id = $resultArr['prop_dtl_id'];
                $saf_dtl_id = $resultArr['saf_dtl_id'];
                $ward_mstr_id = $resultArr['ward_mstr_id'];

                $getLogData = $this->db->table('log_tbl_prop_avg_cal')->where('prop_dtl_id', $prop_dtl_id)->get()->getResultArray();
                if(!isset($getLogData) || sizeof($getLogData)==0)
                {

                
                    $this->db->transBegin();
                    if($resultArr['saf_pending_status'] == 1)
                    {
                        $record = $this->model_field_verification_dtl->getdatabysafid($saf_dtl_id);
                        $record["occupation_date"] = $resultArr["occupation_date"];
                        $record['verification_id'] = $record['id'];
                    }else{
                        $record = $resultArr;
                        $record["percentage_of_property_transfer"] = null;
                    }
                    if(isset($resultArr['apartment_details_id']))
                    {
                        $apt = $this->model_apartment_details->getApartmentDtlById($resultArr['apartment_details_id']);
                        $record["is_water_harvesting"] = ($apt['water_harvesting_status'] == 1)?'t':'f';
                    }

                    $old_rwh = "select * from tbl_water_hrvesting_declaration_dtl_olddata where approval_status=1 and prop_dtl_id=".$prop_dtl_id;
                    $rwh_records = $this->db->query($old_rwh)->getFirstRow();
                    if($rwh_records)
                    {
                        $record["is_water_harvesting"] = 't';
                    }

                    $inputs = array();
                    $inputs['ward_mstr_id'] = $record['ward_mstr_id'];
                    $inputs['zone_mstr_id'] = ($resultArr['zone_mstr_id'] > 0)?$resultArr['zone_mstr_id']:2;
                    $inputs["prop_type_mstr_id"] = $record['prop_type_mstr_id'];
                    $inputs['road_type_mstr_id'] = $record['road_type_mstr_id'];
                    $inputs["area_of_plot"] = $record['area_of_plot'];
                    $inputs["tower_installation_date"] = $record['tower_installation_date'];
                    $inputs["tower_area"] = $record['tower_area'];
                    $inputs["hoarding_installation_date"] = $record['hoarding_installation_date'];
                    $inputs["hoarding_area"] = $record['hoarding_area'];
                    $inputs["petrol_pump_completion_date"] = $record['petrol_pump_completion_date'];
                    $inputs["under_ground_area"] = $record['under_ground_area'];
                    $inputs["percentage_of_property_transfer"] = $record['percentage_of_property_transfer'];
                    if ($record["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                    if ($record["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                    if ($record["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                    if ($record["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
                    $floorDtlArr = array();
                    if ($record['prop_type_mstr_id'] == 4) {
                        $inputs["land_occupation_date"] = $record["occupation_date"];
                        $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                    }else{
                        
                        if($resultArr['saf_pending_status'] == 1){
                            $sqlveri = "select tbl_field_verification_dtl.id from tbl_field_verification_dtl 
                                        left join (select field_verification_dtl_id from tbl_field_verification_floor_details where date_upto is not null group by field_verification_dtl_id) tbl_field_verification_floor_details on tbl_field_verification_floor_details.field_verification_dtl_id=tbl_field_verification_dtl.id
                                        where tbl_field_verification_floor_details.field_verification_dtl_id is null and id=".$record['verification_id']."
                                        ";
                            $checkfield_verification =  $this->db->query($sqlveri)->getRowArray();
                            if($checkfield_verification && $checkfield_verification['id'])
                            { 
                                $field_verifcation_floor_dtl = $this->model_field_verification_floor_details->getFloorDataBymstrId($record['verification_id']);
                                if(count($field_verifcation_floor_dtl)==0)
                                {
                                    $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                                }
                            }else{
                                
                                $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                            }
                        }else{
                            $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                        }
                        

                        $floorKey = 0;
                        foreach ($field_verifcation_floor_dtl as $key => $value) {
                            
                            $date_fromarra = explode('-', $value["date_from"]);

                            if($date_fromarra[0] <= 1970){
                                $date_from = '1970-04-01';
                            }else{
                                $date_from = $value["date_from"];
                            }
                            $inputs["floor_mstr_id"][$floorKey] = !empty($value["floor_mstr_id"])?$value["floor_mstr_id"]:3;
                            $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                            $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                            $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                            $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                            $inputs["date_from"][$floorKey] = date("Y-m", strtotime($date_from));
                            $inputs["date_upto"][$floorKey] = "";
                            if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                                $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                            }
                        
                            $floorKey++;

                        }
                        $inputs['prop_dtl_id']=$prop_dtl_id;
                        //print_var($inputs);
                        
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                        
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $newsafHelper->calBuildingTaxDtl_2023($floorDtlArr, (int)$record['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                        

                    }
                    //print_var($newSafTaxDtl);
                    
                    $tot_paid_demand = 0;
                    $penalty_amt_old = 0;
                    $penalty_amt_new = 0;
                    $total_rebate_amt_old = 0;
                    $total_rebate_amt_new = 0;
                    $tot_to_be_payable = 0;
                    $uwanted_rebate = 0;
                    $total_demand_amt_old = 0;
                    $total_demand_amt_new = 0;
                    $demand_type = '';
                    $entry_for = '';
                    $trans_id = 0;
                    $prop_tax = array();
                    $total_demand_amt_new1 = 0;
                    //print_var($newSafTaxDtl);
                
                    $this->db->table('tbl_prop_tax')->set('status', 0)->where('prop_dtl_id', $prop_dtl_id)
                            ->where('fy_mstr_id', 53)
                            ->where('status', 1)
                            ->update();
                    foreach($newSafTaxDtl as $key => $taxDtl)
                    {
                        
                        $pymt_frm_qtr = (int)$taxDtl['qtr'];
                        $pymt_frm_year = (string)$taxDtl['fyear'];

                        $pymt_upto_qtr = (int)4;
                        $pymt_upto_year = (string)$currentFY;
                        if ($key < sizeof($newSafTaxDtl) - 1) {
                            $pymt_upto_qtr = (int)$newSafTaxDtl[$key + 1]['qtr'] - 1;
                            $pymt_upto_year = (string)$newSafTaxDtl[$key + 1]['fyear'];
                        }
                        list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
                        list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);


                        $fy_mstr_id = $this->getFyID($taxDtl['fyear']);
                        $holding_tax = isset($taxDtl['holding_tax'])?$taxDtl['holding_tax']:0;
                        $water_tax = isset($taxDtl['water_tax'])?$taxDtl['water_tax']:0;
                        $education_cess = isset($taxDtl['education_cess'])?$taxDtl['education_cess']:0;
                        $health_cess = isset($taxDtl['health_cess'])?$taxDtl['health_cess']:0;
                        $latrine_tax = isset($taxDtl['latrine_tax'])?$taxDtl['latrine_tax']:0;
                        $additional_tax = isset($taxDtl['additional_tax'])?$taxDtl['additional_tax']:0;
                        $quarterly_tax = $holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax+$additional_tax;
                        
                        if($taxDtl['arv'] > 0)
                        {
                            

                            $sql = "INSERT INTO tbl_prop_tax (prop_dtl_id, fy_mstr_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status, quarterly_tax)
                            VALUES ('$prop_dtl_id', '".$fy_mstr_id."' ,'" . $taxDtl['fyear'] . "', '" . $taxDtl['qtr'] . "', '".$taxDtl['arv']."', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '" . date("Y-m-d H:i:s") . "', 1, '".$quarterly_tax."') returning id";
                            $query = $this->db->query($sql);
                            $return = $query->getFirstRow("array");
                            $prop_tax_id = $return["id"];
                            
                            while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) 
                            {
                                
                                $newFY = $from_y1_new . "-" . $from_y2_new;
                                $till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
                                for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
                                    
                                    $newFY = $from_y1_new . "-" . $from_y2_new;
                                    $adjust_amt = 0;
                                    $demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
                                    $amount = $taxDtl['quarterly_tax'];
                                    $additional_tax = $taxDtl['additional_tax'];
                                    $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
                                    $total_demand_amt_new = $total_demand_amt_new + $amount;

                                    $additional = 0;
                                    if($newFY != '2016-2017')
                                    {
                                        $additional = $additional_tax;
                                    }
                                    
                                    if ($quarterly_tax > 0 && round($demandAmt+$additional) > 0) 
                                    {
                                        $old_demand_sql = "(select tbl_prop_demand.id,tbl_collection.amount,tbl_collection.transaction_id,tbl_collection.created_on::date as payment_date,tbl_prop_demand.paid_status from tbl_prop_demand 
                                                        join tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id and tbl_collection.collection_type='Property' and tbl_collection.status=1
                                                        where tbl_prop_demand.prop_dtl_id=".$prop_dtl_id." 
                                                        and tbl_prop_demand.status=1 
                                                        and tbl_prop_demand.due_date='".$due_date."'
                                                        and tbl_prop_demand.fyear='".$newFY."' 
                                                        order by tbl_prop_demand.id asc)
                                                        UNION 
                                                        (select tbl_saf_demand.id,tbl_saf_collection.amount,tbl_saf_collection.transaction_id,tbl_saf_collection.created_on::date as payment_date,tbl_saf_demand.paid_status from tbl_saf_demand 
                                                        join tbl_saf_collection on tbl_saf_collection.saf_demand_id=tbl_saf_demand.id and tbl_saf_collection.collection_type='SAF' and tbl_saf_collection.status=1
                                                        where tbl_saf_demand.saf_dtl_id=".$saf_dtl_id." 
                                                        and tbl_saf_demand.status=1 
                                                        and tbl_saf_demand.due_date='".$due_date."'
                                                        and tbl_saf_demand.fyear='".$newFY."' 
                                                        order by tbl_saf_demand.id asc)";
                                        
                                        $old_demand = $this->db->query($old_demand_sql)->getRowArray();
                                        if(isset($old_demand) && $old_demand['id'] > 0 && $old_demand['paid_status'] == 1 && $old_demand['amount'] >0)
                                        {
                                            if($newFY == '2022-2023')
                                            {
                                                $total_demand_amt_old = $total_demand_amt_old + $old_demand['amount'];
                                                $total_demand_amt_new1 = $total_demand_amt_new1 + $amount;
                                                $trans_id = $old_demand['transaction_id'];
                                                $rebate_penalty = $this->db->table('tbl_transaction_fine_rebet_details')
                                                                    ->where('transaction_id', $trans_id)
                                                                    ->where('status', 1)
                                                                    ->orderBy('id', 'DESC')->get()->getResultArray();
                                                
                                                foreach($rebate_penalty as $rebP)
                                                {
                                                    if($rebP['head_name'] == '1% Monthly Penalty' && $rebP['value_add_minus'] == 'Add')
                                                    {
                                                        $paymentDate = $old_demand['payment_date'];
                                                        // $dueDate = date_create($due_date);
                                                        // $interval = date_diff($paymentDate, $dueDate);
                                                        // $diffmonth = $interval->format('%m');
                                                        // if($diffmonth > 0){$diffmonth = $diffmonth + 1;}
                                                        // $penalty_amt_old = $penalty_amt_old + (($old_demand['amount']*$diffmonth)/100);
                                                        // $penalty_amt_new = $penalty_amt_new + (($amount*$diffmonth)/100);
                                                        $penalty_amt_old = $penalty_amt_old + $this->onePercentPenalty($prop_dtl_id, $old_demand['amount'], $newFY, $q, $paymentDate);
                                                        $penalty_amt_new = $penalty_amt_new + $this->onePercentPenalty($prop_dtl_id, $amount, $newFY, $q, $paymentDate);
                                                    }
                                                    if($rebP['head_name'] == 'First Qtr Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                    }
                                                    if($rebP['head_name'] == 'Special Rebate'  && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                    }
                                                    if($rebP['head_name'] == 'Online Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                    }
                                                    if($rebP['head_name'] == 'JSK (2.5%) Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*2.5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*2.5/100);
                                                    }
                                                    if($rebP['head_name'] == 'Rebate Amount' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $uwanted_rebate = $rebP['amount'];
                                                    }
                                                    
                                                }
                                            }

                                            if(($amount-$old_demand['amount']) >= 1)
                                            {
                                                $demand_type = 'Part';
                                                $entry_for = 'Demand';
                                                $index1 = [
                                                    'prop_dtl_id' => $prop_dtl_id,
                                                    'prop_tax_id' => $prop_tax_id,
                                                    'fy_mstr_id' => $this->getFyID($newFY),
                                                    'ward_mstr_id' => $ward_mstr_id,
                                                    'fyear' => $newFY,
                                                    'qtr' => $q,
                                                    'due_date' => $due_date,
                                                    'amount' => round($amount, 2),
                                                    'balance' => round($demandAmt-$old_demand['amount']+$additional, 2),
                                                    'fine_tax' => 0,
                                                    'created_on' => date("Y-m-d H:i:s"),
                                                    'status' => 1,
                                                    'paid_status' => 0,
                                                    'demand_amount' => round($amount-$additional_tax, 2),
                                                    'additional_amount' => $additional,
                                                    'adjust_amt' => $old_demand['amount']
                                                ];
                                                $prop_tax[] = $index1;
                                                $data = $this->db->table('tbl_prop_demand')->insert($index1);
                                            }
                                            
                                        }else{
                                            $demand_type = 'Full';
                                            $entry_for = 'Demand';
                                            $this->db->table('tbl_prop_demand')->where('prop_dtl_id',$prop_dtl_id)
                                                    ->where('paid_status',0)->where('status',1)->where('due_date', $due_date)->update(['status'=>0, 'balance'=>'0.00']);
                                            
                                            $index = [
                                                'prop_dtl_id' => $prop_dtl_id,
                                                'prop_tax_id' => $prop_tax_id,
                                                'fy_mstr_id' => $this->getFyID($newFY),
                                                'ward_mstr_id' => $ward_mstr_id,
                                                'fyear' => $newFY,
                                                'qtr' => $q,
                                                'due_date' => $due_date,
                                                'amount' => round($amount, 2),
                                                'balance' => round($demandAmt+$additional, 2),
                                                'fine_tax' => 0,
                                                'created_on' => date("Y-m-d H:i:s"),
                                                'status' => 1,
                                                'paid_status' => 0,
                                                'demand_amount' => round($amount-$additional_tax, 2),
                                                'additional_amount' => $additional,
                                                'adjust_amt' => $adjust_amt
                                            ];
                                            // //print_var($index);
                                            $prop_tax[] = $index;
                                            $data = $this->db->table('tbl_prop_demand')->insert($index);
                                        }
                                    }
                                }
                                $pymt_frm_qtr = 1;
                                $from_y1_new++;
                                $from_y2_new++;       
                            
                                
                            }
                            if($pymt_frm_year == '2022-2023')
                            {
                                $total_demand_amt_new = ($total_demand_amt_new1>0)?$total_demand_amt_new1:$total_demand_amt_new;
                                $tot_paid_demand = $tot_paid_demand + ($total_demand_amt_old+$penalty_amt_old-$total_rebate_amt_old);
                                $tot_to_be_payable = $tot_to_be_payable + ($total_demand_amt_new+$penalty_amt_new-$total_rebate_amt_new);

                            }
                        }

                        
                        
                    }
                    //echo $prop_dtl_id;
                    // print_var($newSafTaxDtl);
                    
                    // print_var($prop_tax);
                    // echo $tot_paid_demand."<br/>";
                    // echo $tot_to_be_payable."<br/>";
                    // echo $tot_paid_demand-$uwanted_rebate."<br/>";
                    $advance_amount = round(($tot_paid_demand-$uwanted_rebate) - $tot_to_be_payable ,2);
                    if($advance_amount > 0)
                    {
                        $entry_for = 'Advance';
                        $advance_index = [
                            'prop_dtl_id' => $prop_dtl_id,
                            'amount' => $advance_amount,
                            'reason' => 'Advance Payment',
                            'remarks' => 'Advance Payment due to avg. calculation of 2022-2023',
                            'module' => 'Property',
                            'user_id' => 1,
                            'transaction_id' => $trans_id
                        ];
                        //print_var($advance_index);
                        $this->db->table('tbl_advance_mstr')->insert($advance_index);
                    }

                    $log_index = [
                        'prop_dtl_id' => $prop_dtl_id,
                        'saf_dtl_id' => $saf_dtl_id,
                        'entry_for' => $entry_for,
                        'demand_type' => $demand_type,
                        'created_on' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('log_tbl_prop_avg_cal')->insert($log_index);

                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        echo 'Something Wrong!';
                    } else {
                        $this->db->transCommit();
                        //$this->db->transRollback();
                        echo 'Advance or demand generated sucessfully';
                    }
                }
                
            }
        }
        
    }
	
	public function GenDemandWithCreateAdvance_new()
    {
        exit();
        $currentFY = "2023-2024";
        $safHelper = new SAFHelper($this->db);
        $newsafHelper = new NEW_SAFHelper($this->db);
        $sql = "SELECT tbl_prop_dtl.*,tbl_saf_dtl.saf_pending_status,tbl_prop_tax.prop_dtl_id FROM tbl_prop_dtl
				JOIN (select max(id) as proptaxid,prop_dtl_id from tbl_prop_tax where fYear='2022-2023' or tbl_prop_tax.fy_mstr_id=53 and created_on::date<now()::date and status=1 group by prop_dtl_id) tbl_prop_tax on tbl_prop_tax.prop_dtl_id=tbl_prop_dtl.id
				LEFT JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
				LEFT JOIN log_tbl_prop_avg_cal on log_tbl_prop_avg_cal.prop_dtl_id=tbl_prop_dtl.id
				LEFT JOIN (select prop_dtl_id from tbl_prop_demand where status=1 and fyear='2023-2024' group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
				WHERE tbl_prop_dtl.status=1 and tbl_prop_dtl.govt_saf_dtl_id is null and tbl_prop_dtl.prop_type_mstr_id!=4 
				and log_tbl_prop_avg_cal.prop_dtl_id is null and tbl_prop_demand.prop_dtl_id is null and char_length(tbl_prop_dtl.new_holding_no)>0
				and tbl_prop_dtl.id not in(14758, 101106, 117204, 150552, 192426, 192420, 186397, 196551, 196968, 238197, 209099, 217742,223997,225183, 228625)";

        $resultArrs = $this->db->query($sql)->getResultArray();
        
        if($resultArrs)
        {
            foreach($resultArrs as $resultArr)
            {   
                
                $prop_dtl_id = $resultArr['prop_dtl_id'];
                $saf_dtl_id = $resultArr['saf_dtl_id'];
                $ward_mstr_id = $resultArr['ward_mstr_id'];

                $getLogData = $this->db->table('log_tbl_prop_avg_cal')->where('prop_dtl_id', $prop_dtl_id)->get()->getResultArray();
                if(!isset($getLogData) || sizeof($getLogData)==0)
                {

                
                    $this->db->transBegin();
                    if($resultArr['saf_pending_status'] == 1)
                    {
                        $record = $this->model_field_verification_dtl->getdatabysafid($saf_dtl_id);
                        $record["occupation_date"] = $resultArr["occupation_date"];
                        $record['verification_id'] = $record['id'];
                    }else{
                        $record = $resultArr;
                        $record["percentage_of_property_transfer"] = null;
                    }
                    if(isset($resultArr['apartment_details_id']))
                    {
                        $apt = $this->model_apartment_details->getApartmentDtlById($resultArr['apartment_details_id']);
                        $record["is_water_harvesting"] = ($apt['water_harvesting_status'] == 1)?'t':'f';
                    }

                    $old_rwh = "select * from tbl_water_hrvesting_declaration_dtl_olddata where approval_status=1 and prop_dtl_id=".$prop_dtl_id;
                    $rwh_records = $this->db->query($old_rwh)->getFirstRow();
                    if($rwh_records)
                    {
                        $record["is_water_harvesting"] = 't';
                    }

                    $inputs = array();
                    $inputs['ward_mstr_id'] = $record['ward_mstr_id'];
                    $inputs['zone_mstr_id'] = ($resultArr['zone_mstr_id'] > 0)?$resultArr['zone_mstr_id']:2;
                    $inputs["prop_type_mstr_id"] = $record['prop_type_mstr_id'];
                    $inputs['road_type_mstr_id'] = $record['road_type_mstr_id'];
                    $inputs["area_of_plot"] = $record['area_of_plot'];
                    $inputs["tower_installation_date"] = $record['tower_installation_date'];
                    $inputs["tower_area"] = $record['tower_area'];
                    $inputs["hoarding_installation_date"] = $record['hoarding_installation_date'];
                    $inputs["hoarding_area"] = $record['hoarding_area'];
                    $inputs["petrol_pump_completion_date"] = $record['petrol_pump_completion_date'];
                    $inputs["under_ground_area"] = $record['under_ground_area'];
                    $inputs["percentage_of_property_transfer"] = $record['percentage_of_property_transfer'];
                    if ($record["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                    if ($record["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                    if ($record["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                    if ($record["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
                    $floorDtlArr = array();
                    if ($record['prop_type_mstr_id'] == 4) {
                        $inputs["land_occupation_date"] = $record["occupation_date"];
                        $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                    }else{
                        
                        if($resultArr['saf_pending_status'] == 1){
                            $sqlveri = "select tbl_field_verification_dtl.id from tbl_field_verification_dtl 
                                        left join (select field_verification_dtl_id from tbl_field_verification_floor_details where date_upto is not null group by field_verification_dtl_id) tbl_field_verification_floor_details on tbl_field_verification_floor_details.field_verification_dtl_id=tbl_field_verification_dtl.id
                                        where tbl_field_verification_floor_details.field_verification_dtl_id is null and id=".$record['verification_id']."
                                        ";
                            $checkfield_verification =  $this->db->query($sqlveri)->getRowArray();
                            if($checkfield_verification && $checkfield_verification['id'])
                            { 
                                $field_verifcation_floor_dtl = $this->model_field_verification_floor_details->getFloorDataBymstrId($record['verification_id']);
                                if(count($field_verifcation_floor_dtl)==0)
                                {
                                    $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                                }
                            }else{
                                
                                $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                            }
                        }else{
                            $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                        }
                        

                        $floorKey = 0;
                        foreach ($field_verifcation_floor_dtl as $key => $value) {
                            
                            $date_fromarra = explode('-', $value["date_from"]);

                            if($date_fromarra[0] <= 1970){
                                $date_from = '1970-04-01';
                            }else{
                                $date_from = $value["date_from"];
                            }
                            $inputs["floor_mstr_id"][$floorKey] = !empty($value["floor_mstr_id"])?$value["floor_mstr_id"]:3;
                            $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                            $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                            $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                            $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                            $inputs["date_from"][$floorKey] = date("Y-m", strtotime($date_from));
                            $inputs["date_upto"][$floorKey] = "";
                            if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                                $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                            }
                        
                            $floorKey++;

                        }
                        $inputs['prop_dtl_id']=$prop_dtl_id;
                        //print_var($inputs);
                        
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                        
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $newsafHelper->calBuildingTaxDtl_2023($floorDtlArr, (int)$record['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                        

                    }
                    //print_var($newSafTaxDtl);
                    
                    $tot_paid_demand = 0;
                    $penalty_amt_old = 0;
                    $penalty_amt_new = 0;
                    $total_rebate_amt_old = 0;
                    $total_rebate_amt_new = 0;
                    $tot_to_be_payable = 0;
                    $uwanted_rebate = 0;
                    $total_demand_amt_old = 0;
                    $total_demand_amt_new = 0;
                    $demand_type = '';
                    $entry_for = '';
                    $trans_id = 0;
                    $prop_tax = array();
                    $total_demand_amt_new1 = 0;
                    //print_var($newSafTaxDtl);
                
                    $this->db->table('tbl_prop_tax')->set('status', 0)->where('prop_dtl_id', $prop_dtl_id)
                            ->where('fy_mstr_id', 53)
                            ->where('status', 1)
                            ->update();
                    foreach($newSafTaxDtl as $key => $taxDtl)
                    {
                        
                        $pymt_frm_qtr = (int)$taxDtl['qtr'];
                        $pymt_frm_year = (string)$taxDtl['fyear'];

                        $pymt_upto_qtr = (int)4;
                        $pymt_upto_year = (string)$currentFY;
                        if ($key < sizeof($newSafTaxDtl) - 1) {
                            $pymt_upto_qtr = (int)$newSafTaxDtl[$key + 1]['qtr'] - 1;
                            $pymt_upto_year = (string)$newSafTaxDtl[$key + 1]['fyear'];
                        }
                        list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
                        list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);


                        $fy_mstr_id = $this->getFyID($taxDtl['fyear']);
                        $holding_tax = isset($taxDtl['holding_tax'])?$taxDtl['holding_tax']:0;
                        $water_tax = isset($taxDtl['water_tax'])?$taxDtl['water_tax']:0;
                        $education_cess = isset($taxDtl['education_cess'])?$taxDtl['education_cess']:0;
                        $health_cess = isset($taxDtl['health_cess'])?$taxDtl['health_cess']:0;
                        $latrine_tax = isset($taxDtl['latrine_tax'])?$taxDtl['latrine_tax']:0;
                        $additional_tax = isset($taxDtl['additional_tax'])?$taxDtl['additional_tax']:0;
                        $quarterly_tax = $holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax+$additional_tax;
                        
                        if($taxDtl['arv'] > 0)
                        {
                            

                            $sql = "INSERT INTO tbl_prop_tax (prop_dtl_id, fy_mstr_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status, quarterly_tax)
                            VALUES ('$prop_dtl_id', '".$fy_mstr_id."' ,'" . $taxDtl['fyear'] . "', '" . $taxDtl['qtr'] . "', '".$taxDtl['arv']."', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '" . date("Y-m-d H:i:s") . "', 1, '".$quarterly_tax."') returning id";
                            $query = $this->db->query($sql);
                            $return = $query->getFirstRow("array");
                            $prop_tax_id = $return["id"];
                            
                            while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) 
                            {
                                
                                $newFY = $from_y1_new . "-" . $from_y2_new;
                                $till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
                                for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
                                    
                                    $newFY = $from_y1_new . "-" . $from_y2_new;
                                    $adjust_amt = 0;
                                    $demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
                                    $amount = $taxDtl['quarterly_tax'];
                                    $additional_tax = $taxDtl['additional_tax'];
                                    $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
                                    $total_demand_amt_new = $total_demand_amt_new + $amount;

                                    $additional = 0;
                                    if($newFY != '2016-2017')
                                    {
                                        $additional = $additional_tax;
                                    }
                                    
                                    if ($quarterly_tax > 0 && round($demandAmt+$additional) > 0) 
                                    {
                                        $old_demand_sql = "(select tbl_prop_demand.id,tbl_collection.amount,tbl_collection.transaction_id,tbl_collection.created_on::date as payment_date,tbl_prop_demand.paid_status from tbl_prop_demand 
                                                        join tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id and tbl_collection.collection_type='Property' and tbl_collection.status=1
                                                        where tbl_prop_demand.prop_dtl_id=".$prop_dtl_id." 
                                                        and tbl_prop_demand.status=1 
                                                        and tbl_prop_demand.due_date='".$due_date."'
                                                        and tbl_prop_demand.fyear='".$newFY."' 
                                                        order by tbl_prop_demand.id asc)
                                                        UNION 
                                                        (select tbl_saf_demand.id,tbl_saf_collection.amount,tbl_saf_collection.transaction_id,tbl_saf_collection.created_on::date as payment_date,tbl_saf_demand.paid_status from tbl_saf_demand 
                                                        join tbl_saf_collection on tbl_saf_collection.saf_demand_id=tbl_saf_demand.id and tbl_saf_collection.collection_type='SAF' and tbl_saf_collection.status=1
                                                        where tbl_saf_demand.saf_dtl_id=".$saf_dtl_id." 
                                                        and tbl_saf_demand.status=1 
                                                        and tbl_saf_demand.due_date='".$due_date."'
                                                        and tbl_saf_demand.fyear='".$newFY."' 
                                                        order by tbl_saf_demand.id asc)";
                                        
                                        $old_demand = $this->db->query($old_demand_sql)->getRowArray();
                                        if(isset($old_demand) && $old_demand['id'] > 0 && $old_demand['paid_status'] == 1 && $old_demand['amount'] >0)
                                        {
                                            if($newFY == '2022-2023')
                                            {
                                                $total_demand_amt_old = $total_demand_amt_old + $old_demand['amount'];
                                                $total_demand_amt_new1 = $total_demand_amt_new1 + $amount;
                                                $trans_id = $old_demand['transaction_id'];
                                                $rebate_penalty = $this->db->table('tbl_transaction_fine_rebet_details')
                                                                    ->where('transaction_id', $trans_id)
                                                                    ->where('status', 1)
                                                                    ->orderBy('id', 'DESC')->get()->getResultArray();
                                                
                                                foreach($rebate_penalty as $rebP)
                                                {
                                                    if($rebP['head_name'] == '1% Monthly Penalty' && $rebP['value_add_minus'] == 'Add')
                                                    {
                                                        $paymentDate = $old_demand['payment_date'];
                                                        // $dueDate = date_create($due_date);
                                                        // $interval = date_diff($paymentDate, $dueDate);
                                                        // $diffmonth = $interval->format('%m');
                                                        // if($diffmonth > 0){$diffmonth = $diffmonth + 1;}
                                                        // $penalty_amt_old = $penalty_amt_old + (($old_demand['amount']*$diffmonth)/100);
                                                        // $penalty_amt_new = $penalty_amt_new + (($amount*$diffmonth)/100);
                                                        $penalty_amt_old = $penalty_amt_old + $this->onePercentPenalty($prop_dtl_id, $old_demand['amount'], $newFY, $q, $paymentDate);
                                                        $penalty_amt_new = $penalty_amt_new + $this->onePercentPenalty($prop_dtl_id, $amount, $newFY, $q, $paymentDate);
                                                    }
                                                    if($rebP['head_name'] == 'First Qtr Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                    }
                                                    if($rebP['head_name'] == 'Special Rebate'  && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                    }
                                                    if($rebP['head_name'] == 'Online Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                    }
                                                    if($rebP['head_name'] == 'JSK (2.5%) Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*2.5/100);
                                                        $total_rebate_amt_new = $total_rebate_amt_new + ($amount*2.5/100);
                                                    }
                                                    if($rebP['head_name'] == 'Rebate Amount' && $rebP['value_add_minus'] == 'Minus')
                                                    {
                                                        $uwanted_rebate = $rebP['amount'];
                                                    }
                                                    
                                                }
                                            }

                                            if(($amount-$old_demand['amount']) >= 1)
                                            {
                                                $demand_type = 'Part';
                                                $entry_for = 'Demand';
                                                $index1 = [
                                                    'prop_dtl_id' => $prop_dtl_id,
                                                    'prop_tax_id' => $prop_tax_id,
                                                    'fy_mstr_id' => $this->getFyID($newFY),
                                                    'ward_mstr_id' => $ward_mstr_id,
                                                    'fyear' => $newFY,
                                                    'qtr' => $q,
                                                    'due_date' => $due_date,
                                                    'amount' => round($amount, 2),
                                                    'balance' => round($demandAmt-$old_demand['amount']+$additional, 2),
                                                    'fine_tax' => 0,
                                                    'created_on' => date("Y-m-d H:i:s"),
                                                    'status' => 1,
                                                    'paid_status' => 0,
                                                    'demand_amount' => round($amount-$additional_tax, 2),
                                                    'additional_amount' => $additional,
                                                    'adjust_amt' => $old_demand['amount']
                                                ];
                                                $prop_tax[] = $index1;
                                                $data = $this->db->table('tbl_prop_demand')->insert($index1);
                                            }
                                            
                                        }else{
                                            $demand_type = 'Full';
                                            $entry_for = 'Demand';
                                            $this->db->table('tbl_prop_demand')->where('prop_dtl_id',$prop_dtl_id)
                                                    ->where('paid_status',0)->where('status',1)->where('due_date', $due_date)->update(['status'=>0, 'balance'=>'0.00']);
                                            
                                            $index = [
                                                'prop_dtl_id' => $prop_dtl_id,
                                                'prop_tax_id' => $prop_tax_id,
                                                'fy_mstr_id' => $this->getFyID($newFY),
                                                'ward_mstr_id' => $ward_mstr_id,
                                                'fyear' => $newFY,
                                                'qtr' => $q,
                                                'due_date' => $due_date,
                                                'amount' => round($amount, 2),
                                                'balance' => round($demandAmt+$additional, 2),
                                                'fine_tax' => 0,
                                                'created_on' => date("Y-m-d H:i:s"),
                                                'status' => 1,
                                                'paid_status' => 0,
                                                'demand_amount' => round($amount-$additional_tax, 2),
                                                'additional_amount' => $additional,
                                                'adjust_amt' => $adjust_amt
                                            ];
                                            // //print_var($index);
                                            $prop_tax[] = $index;
                                            $data = $this->db->table('tbl_prop_demand')->insert($index);
                                        }
                                    }
                                }
                                $pymt_frm_qtr = 1;
                                $from_y1_new++;
                                $from_y2_new++;       
                            
                                
                            }
                            if($pymt_frm_year == '2022-2023')
                            {
                                $total_demand_amt_new = ($total_demand_amt_new1>0)?$total_demand_amt_new1:$total_demand_amt_new;
                                $tot_paid_demand = $tot_paid_demand + ($total_demand_amt_old+$penalty_amt_old-$total_rebate_amt_old);
                                $tot_to_be_payable = $tot_to_be_payable + ($total_demand_amt_new+$penalty_amt_new-$total_rebate_amt_new);

                            }
                        }

                        
                        
                    }
                    //echo $prop_dtl_id;
                    // print_var($newSafTaxDtl);
                    
                    // print_var($prop_tax);
                    // echo $tot_paid_demand."<br/>";
                    // echo $tot_to_be_payable."<br/>";
                    // echo $tot_paid_demand-$uwanted_rebate."<br/>";
                    $advance_amount = round(($tot_paid_demand-$uwanted_rebate) - $tot_to_be_payable ,2);
                    if($advance_amount > 0)
                    {
                        $entry_for = 'Advance';
                        $advance_index = [
                            'prop_dtl_id' => $prop_dtl_id,
                            'amount' => $advance_amount,
                            'reason' => 'Advance Payment',
                            'remarks' => 'Advance Payment due to avg. calculation of 2022-2023',
                            'module' => 'Property',
                            'user_id' => 1,
                            'transaction_id' => $trans_id
                        ];
                        //print_var($advance_index);
                        $this->db->table('tbl_advance_mstr')->insert($advance_index);
                    }

                    $log_index = [
                        'prop_dtl_id' => $prop_dtl_id,
                        'saf_dtl_id' => $saf_dtl_id,
                        'entry_for' => $entry_for,
                        'demand_type' => $demand_type,
                        'created_on' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('log_tbl_prop_avg_cal')->insert($log_index);

                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        echo 'Something Wrong!';
                    } else {
                        $this->db->transCommit();
                        //$this->db->transRollback();
                        echo 'Advance or demand generated sucessfully';
                    }
                }
                
            }
        }
        
    }

    public function GenDemandWithCreateAdvance_gbsaf()
    {
        exit();
        $currentFY = "2023-2024";
        $safHelper = new SAFHelper($this->db);
        $newsafHelper = new NEW_SAFHelper($this->db);

        $sql = "SELECT tbl_govt_saf_dtl.*,tbl_govt_saf_dtl.id as govt_saf_dtl_id FROM tbl_govt_saf_dtl  
                JOIN (select max(id) as tax_id,govt_saf_dtl_id from tbl_govt_saf_tax_dtl where fy_mstr_id=53 and created_on::date<now()::date and status=1 group by govt_saf_dtl_id) tbl_govt_saf_tax_dtl on tbl_govt_saf_tax_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                LEFT JOIN log_tbl_prop_avg_cal on log_tbl_prop_avg_cal.saf_dtl_id=tbl_govt_saf_dtl.id and prop_type='GBSAF'
                WHERE tbl_govt_saf_dtl.status=1 and log_tbl_prop_avg_cal.saf_dtl_id is null";
        $resultArrs = $this->db->query($sql)->getResultArray();
        print_var($resultArrs);
        exit();
        if($resultArrs)
        {
            foreach($resultArrs as $resultArr)
            {   
                
                $prop_dtl_id = $resultArr['prop_dtl_id'];
                $govt_saf_dtl_id = $resultArr['govt_saf_dtl_id'];
                $colony_mstr_id = !empty($resultArr['colony_mstr_id'])?$resultArr['colony_mstr_id']:null;
                $ward_mstr_id = $resultArr['ward_mstr_id'];

                $getLogData = $this->db->table('log_tbl_prop_avg_cal')->where('saf_dtl_id', $govt_saf_dtl_id)->where('prop_type', 'GBSAF')->get()->getResultArray();
                
                if(!isset($getLogData) || sizeof($getLogData)==0)
                {
                
                    $this->db->transBegin();
                    $resultArr["occupation_date"] = null;

                    $inputs = array();
                    $inputs['ward_mstr_id'] = $resultArr['ward_mstr_id'];
                    $inputs['zone_mstr_id'] = ($resultArr['zone_mstr_id'] > 0)?$resultArr['zone_mstr_id']:2;
                    $inputs["prop_type_mstr_id"] = $resultArr['prop_type_mstr_id'];
                    $inputs['road_type_mstr_id'] = $resultArr['road_type_mstr_id'];
                    $inputs["area_of_plot"] = 10;
                    $inputs["is_mobile_tower"] = $resultArr["is_mobile_tower"];
                    $inputs["tower_installation_date"] = $resultArr['tower_installation_date'];
                    $inputs["tower_area"] = $resultArr['tower_area'];
                    $inputs["is_hoarding_board"] = $resultArr["is_hoarding_board"];
                    $inputs["hoarding_installation_date"] = $resultArr['hoarding_installation_date'];
                    $inputs["hoarding_area"] = $resultArr['hoarding_area'];
                    $inputs["is_petrol_pump"] = $resultArr["is_petrol_pump"];
                    $inputs["petrol_pump_completion_date"] = $resultArr['petrol_pump_completion_date'];
                    $inputs["under_ground_area"] = $resultArr['under_ground_area'];
                    $inputs["is_water_harvesting"] = $resultArr["is_water_harvesting"];
                    
                    if ($resultArr["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                    if ($resultArr["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                    if ($resultArr["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                    if ($resultArr["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
                    $floorDtlArr = array();
                    if ($resultArr['prop_type_mstr_id'] == 4) {
                        $inputs["land_occupation_date"] = $resultArr["occupation_date"];
                        $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                    }else{
                        if($resultArr['colony_mstr_id'] >0)
                        {
                            $sql = "SELECT
                                    tbl_govt_saf_floor_dtl.*
                                FROM tbl_govt_saf_dtl
                                INNER JOIN tbl_govt_saf_floor_dtl ON tbl_govt_saf_floor_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                                WHERE
                                    tbl_govt_saf_floor_dtl.status=1
                                    AND tbl_govt_saf_dtl.colony_mstr_id=".$resultArr['colony_mstr_id'];
                            $getFloorDtls = $this->db->query($sql)->getResult("array");
                        }else{
                            $getFloorDtls = $this->model_govt_saf_floor_dtl->getDataByGBSafDtlId($govt_saf_dtl_id);
                        }
                        

                        
                        
                        $floorKey = 0;
                        foreach ($getFloorDtls as $key => $value) {
                            
                            $date_fromarra = explode('-', $value["date_from"]);

                            if($date_fromarra[0] <= 1970){
                                $date_from = '1970-04-01';
                            }else{
                                $date_from = $value["date_from"];
                            }
                            $inputs["floor_mstr_id"][$floorKey] = !empty($value["floor_mstr_id"])?$value["floor_mstr_id"]:3;
                            $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                            $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                            $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                            $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                            $inputs["date_from"][$floorKey] = date("Y-m", strtotime($date_from));
                            $inputs["date_upto"][$floorKey] = "";
                            if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                                $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                            }
                        
                            $floorKey++;

                        }
                        //$inputs['prop_dtl_id']=$prop_dtl_id;
                        //print_var($inputs);
                        
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                        
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $newsafHelper->calBuildingTaxDtl_2023($floorDtlArr, (int)$resultArr['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                        

                    }
                    
                    $tot_paid_demand = 0;
                    $penalty_amt_old = 0;
                    $penalty_amt_new = 0;
                    $total_rebate_amt_old = 0;
                    $total_rebate_amt_new = 0;
                    $tot_to_be_payable = 0;
                    $uwanted_rebate = 0;
                    $total_demand_amt_old = 0;
                    $total_demand_amt_new = 0;
                    $demand_type = '';
                    $entry_for = '';
                    $trans_id = 0;
                    $prop_tax = array();
                    $total_demand_amt_new1 = 0;
                    //print_var($newSafTaxDtl);
                
                    $this->db->table('tbl_govt_saf_tax_dtl')->set('status', 0)->where('govt_saf_dtl_id', $govt_saf_dtl_id)
                            ->where('fy_mstr_id', 53)
                            ->where('status', 1)
                            ->update();
                    foreach($newSafTaxDtl as $key => $taxDtl)
                    {
                        
                        $pymt_frm_qtr = (int)$taxDtl['qtr'];
                        $pymt_frm_year = (string)$taxDtl['fyear'];

                        $pymt_upto_qtr = (int)4;
                        $pymt_upto_year = (string)$currentFY;
                        if ($key < sizeof($newSafTaxDtl) - 1) {
                            $pymt_upto_qtr = (int)$newSafTaxDtl[$key + 1]['qtr'] - 1;
                            $pymt_upto_year = (string)$newSafTaxDtl[$key + 1]['fyear'];
                        }
                        list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
                        list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);


                        $fy_mstr_id = $this->getFyID($taxDtl['fyear']);
                        $holding_tax = isset($taxDtl['holding_tax'])?$taxDtl['holding_tax']:0;
                        $water_tax = isset($taxDtl['water_tax'])?$taxDtl['water_tax']:0;
                        $education_cess = isset($taxDtl['education_cess'])?$taxDtl['education_cess']:0;
                        $health_cess = isset($taxDtl['health_cess'])?$taxDtl['health_cess']:0;
                        $latrine_tax = isset($taxDtl['latrine_tax'])?$taxDtl['latrine_tax']:0;
                        $additional_tax = isset($taxDtl['additional_tax'])?$taxDtl['additional_tax']:0;
                        $quarterly_tax = $holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax+$additional_tax;
                        
                        if($taxDtl['arv'] > 0)
                        {
                            
                            $sql = "INSERT INTO tbl_govt_saf_tax_dtl (govt_saf_dtl_id, ".($colony_mstr_id ?"colony_mstr_id , ":"")." fy_mstr_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status, quarterly_tax)
                            VALUES ('$govt_saf_dtl_id', ".($colony_mstr_id ?"'".$colony_mstr_id."', '":"'").$fy_mstr_id."' ,'" . $taxDtl['fyear'] . "', '" . $taxDtl['qtr'] . "', '".$taxDtl['arv']."', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '" . date("Y-m-d H:i:s") . "', 1, '".$quarterly_tax."') returning id";
                            $query = $this->db->query($sql);
                            $return = $query->getFirstRow("array");
                            $govt_saf_tax_dtl_id = $return["id"];
                            
                            while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) 
                            {
                                
                                $newFY = $from_y1_new . "-" . $from_y2_new;
                                $till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
                                for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
                                    
                                    $newFY = $from_y1_new . "-" . $from_y2_new;
                                    $adjust_amt = 0;
                                    $demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
                                    $amount = $taxDtl['quarterly_tax'];
                                    $additional_tax = $taxDtl['additional_tax'];
                                    $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
                                    $total_demand_amt_new = $total_demand_amt_new + $amount;

                                    $additional = 0;
                                    if($newFY != '2016-2017')
                                    {
                                        $additional = $additional_tax;
                                    }
                                    
                                    if ($quarterly_tax > 0 && round($demandAmt+$additional) > 0) 
                                    {

                                        $old_demand_sql = " select tbl_govt_saf_demand_dtl.id,tbl_govt_saf_collection_dtl.amount, tbl_govt_saf_collection_dtl.govt_saf_transaction_id as transaction_id, tbl_govt_saf_transaction.tran_date as payment_date,tbl_govt_saf_demand_dtl.paid_status from tbl_govt_saf_demand_dtl
                                            join tbl_govt_saf_collection_dtl on tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id=tbl_govt_saf_demand_dtl.id
                                            join tbl_govt_saf_transaction on tbl_govt_saf_collection_dtl.govt_saf_transaction_id=tbl_govt_saf_transaction.id
                                            where tbl_govt_saf_demand_dtl.govt_saf_dtl_id=".$govt_saf_dtl_id." and tbl_govt_saf_demand_dtl.status=1
                                            and tbl_govt_saf_demand_dtl.due_date='".$due_date."' and tbl_govt_saf_demand_dtl.fyear='".$newFY."'
                                            order by tbl_govt_saf_demand_dtl.id asc";
                                        
                                        $old_demand = $this->db->query($old_demand_sql)->getRowArray();
                                        if(isset($old_demand) && $old_demand['id'] > 0 && $old_demand['paid_status'] == 1 && $old_demand['amount'] >0)
                                        {
                                            $total_demand_amt_old = $total_demand_amt_old + $old_demand['amount'];
                                            $total_demand_amt_new1 = $total_demand_amt_new1 + $amount;
                                            $trans_id = $old_demand['transaction_id'];
                                            $rebate_penalty = $this->db->table('tbl_govt_saf_transaction_fine_rebet_details')
                                                                ->where('govt_saf_transaction', $trans_id)
                                                                ->where('status', 1)
                                                                ->orderBy('id', 'DESC')->get()->getResultArray();
                                            
                                            foreach($rebate_penalty as $rebP)
                                            {
                                                if(($rebP['head_name'] == '1% Monthly Penalty' || $rebP['head_name'] == '1% Penalty On Demand Amount') && $rebP['value_add_minus'] == 'Add')
                                                {
                                                    $paymentDate = date_create($old_demand['payment_date']);
                                                    $dueDate = date_create($due_date);
                                                    $interval = date_diff($paymentDate, $dueDate);
                                                    $diffmonth = $interval->format('%m');
                                                    if($diffmonth > 0){$diffmonth = $diffmonth + 1;}
                                                    $penalty_amt_old = $penalty_amt_old + (($old_demand['amount']*$diffmonth)/100);
                                                    $penalty_amt_new = $penalty_amt_new + (($amount*$diffmonth)/100);
                                                }
                                                if($rebP['head_name'] == 'First Qtr Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                {
                                                    $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                    $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                }
                                                if($rebP['head_name'] == 'Special Rebate'  && $rebP['value_add_minus'] == 'Minus')
                                                {
                                                    $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                    $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                }
                                                if($rebP['head_name'] == 'Online Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                {
                                                    $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
                                                    $total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
                                                }
                                                if($rebP['head_name'] == 'JSK (2.5%) Rebate' && $rebP['value_add_minus'] == 'Minus')
                                                {
                                                    $total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*2.5/100);
                                                    $total_rebate_amt_new = $total_rebate_amt_new + ($amount*2.5/100);
                                                }
                                                if($rebP['head_name'] == 'Rebate Amount' && $rebP['value_add_minus'] == 'Minus')
                                                {
                                                    $uwanted_rebate = $rebP['amount'];
                                                }
                                                
                                            }

                                            if(($amount-$old_demand['amount']) >= 1)
                                            {
                                                $demand_type = 'Part';
                                                $entry_for = 'Demand';
                                                $index1 = [
                                                    'govt_saf_dtl_id' => $govt_saf_dtl_id,
                                                    'govt_saf_tax_dtl_id' => $govt_saf_tax_dtl_id,
                                                    'colony_mstr_id' => $colony_mstr_id,
                                                    'fy_mstr_id' => $this->getFyID($newFY),
                                                    'ward_mstr_id' => $ward_mstr_id,
                                                    'fyear' => $newFY,
                                                    'qtr' => $q,
                                                    'due_date' => $due_date,
                                                    'amount' => round($amount, 2),
                                                    'balance' => round($demandAmt+$additional, 2),
                                                    'fine_tax' => 0,
                                                    'created_on' => date("Y-m-d H:i:s"),
                                                    'status' => 1,
                                                    'paid_status' => 0,
                                                    'demand_amount' => round($amount-$additional_tax, 2),
                                                    'additional_holding_tax' => $additional,
                                                    'adjust_amount' => '0.00'
                                                ];
                                                $prop_tax[] = $index1;
                                                $data = $this->db->table('tbl_govt_saf_demand_dtl')->insert($index1);
                                            }
                                            
                                        }else{
                                            $demand_type = 'Full';
                                            $entry_for = 'Demand';
                                            $this->db->table('tbl_govt_saf_demand_dtl')->where('govt_saf_dtl_id',$govt_saf_dtl_id)
                                                    ->where('paid_status',0)->where('status',1)->where('due_date', $due_date)->update(['status'=>0, 'balance'=>'0.00']);
                                            
                                            $index = [
                                                'govt_saf_dtl_id' => $govt_saf_dtl_id,
                                                'govt_saf_tax_dtl_id' => $govt_saf_tax_dtl_id,
                                                'colony_mstr_id' => $colony_mstr_id,
                                                'fy_mstr_id' => $this->getFyID($newFY),
                                                'ward_mstr_id' => $ward_mstr_id,
                                                'fyear' => $newFY,
                                                'qtr' => $q,
                                                'due_date' => $due_date,
                                                'amount' => round($amount, 2),
                                                'balance' => round($demandAmt+$additional, 2),
                                                'fine_tax' => 0,
                                                'created_on' => date("Y-m-d H:i:s"),
                                                'status' => 1,
                                                'paid_status' => 0,
                                                'demand_amount' => round($amount-$additional_tax, 2),
                                                'additional_holding_tax' => $additional,
                                                'adjust_amount' => '0.00'
                                            ];
                                            // //print_var($index);
                                            $prop_tax[] = $index;
                                            $data = $this->db->table('tbl_govt_saf_demand_dtl')->insert($index);
                                        }
                                    }
                                }
                                $pymt_frm_qtr = 1;
                                $from_y1_new++;
                                $from_y2_new++;       
                            
                                
                            }
                            $total_demand_amt_new = ($total_demand_amt_new1>0)?$total_demand_amt_new1:$total_demand_amt_new;
                            $tot_paid_demand = $tot_paid_demand + ($total_demand_amt_old+$penalty_amt_old-$total_rebate_amt_old);
                            $tot_to_be_payable = $tot_to_be_payable + ($total_demand_amt_new+$penalty_amt_new-$total_rebate_amt_new);
                        }

                        
                        
                    }
                    //print_var($prop_tax);
                    //echo $tot_paid_demand."<br/>";
                    //echo $tot_to_be_payable."<br/>";
                    // $tot_paid_demand-$uwanted_rebate."<br/>";
                    $advance_amount = round(($tot_paid_demand-$uwanted_rebate) - $tot_to_be_payable ,2);
                    //exit();
                    if($advance_amount > 0)
                    {
                        $entry_for = 'Advance';
                        $advance_index = [
                            'prop_dtl_id' => $govt_saf_dtl_id,
                            'amount' => $advance_amount,
                            'reason' => 'Advance Payment',
                            'remarks' => 'Advance Payment due to avg. calculation of 2022-2023',
                            'module' => 'GBSAF',
                            'user_id' => 1,
                            'transaction_id' => $trans_id
                        ];
                        //print_var($advance_index);
                        $this->db->table('tbl_advance_mstr')->insert($advance_index);
                    }
                    //echo $this->db->getLastQuery();
                    $log_index = [
                        'prop_dtl_id' => $prop_dtl_id,
                        'saf_dtl_id' => $govt_saf_dtl_id,
                        'entry_for' => $entry_for,
                        'demand_type' => $demand_type,
                        'created_on' => date('Y-m-d H:i:s'),
                        'prop_type' => 'GBSAF'
                    ];
                    $this->db->table('log_tbl_prop_avg_cal')->insert($log_index);
                    
                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        echo 'Something Wrong!';
                    } else {
                        $this->db->transCommit();
                        echo 'Advance or demand generated sucessfully';
                    }
                }
                
            }
        }
        
    }
    public function btcforward($id)
    {
        exit();
        if($id==1)
        {
            $data=['SAF/02/003/4704','SAF/02/037/12043','SAF/02/014/01529','SAF/02/007/01735','SAF/02/029/03310','SAF/02/029/03242','SAF/02/029/03217','SAF/02/029/03366','SAF/02/043/00949','SAF/02/011/01039','SAF/02/037/02035','SAF/02/037/02030','SAF/02/037/02132','SAF/02/015/1504','SAF/02/018/1051','SAF/02/007/01462','SAF/02/004/12233','SAF/02/016/00298','SAF/02/029/03335','SAF/02/028/00771','SAF/02/008/1121','SAF/02/034/1177','SAF/02/011/3819','SAF/02/003/4721','SAF/02/003/4324','SAF/02/037/01910','SAF/02/020/4451','SAF/02/011/3372','SAF/02/011/3801','SAF/02/011/4488','SAF/02/038/01614','SAF/02/005/00743','SAF/02/004/03703','SAF/02/021/5094','SAF/02/006/01615','SAF/02/049/3388','SAF/02/035/01441','SAF/02/037/01914','SAF/02/029/03342','SAF/02/038/9581','SAF/02/029/03337','SAF/02/029/6414','SAF/02/048/01740','SAF/02/048/01731','SAF/02/036/01178','SAF/02/048/01727','SAF/02/036/01143','SAF/02/003/2209','SAF/02/002/6025','SAF/02/002/0198','SAF/02/45/4/0132','SAF/02/002/5536','SAF/02/002/6736','SAF/02/004/11997','SAF/02/048/01722','SAF/02/003/1734','SAF/02/021/02095','SAF/02/029/03324','SAF/02/005/00774','SAF/02/005/00768','SAF/02/005/00769','SAF/02/037/01923','SAF/02/037/02096','SAF/02/004/10392','SAF/02/001/3859','SAF/02/004/10523','SAF/02/04A/0057','SAF/02/037/02094','SAF/02/032/01724','SAF/02/052/00232','SAF/02/038/01480','SAF/02/027/1992','SAF/02/027/2471','SAF/02/050/00075','SAF/02/032/01711','SAF/02/029/03263','SAF/02/022/02089','SAF/02/048/01688','SAF/02/008/03288','SAF/02/002/03372','SAF/02/022/02082','SAF/02/001/01738','SAF/02/043/00917','SAF/02/044/00069','SAF/02/029/03302','SAF/02/039/00029','SAF/02/029/03291','SAF/02/030/01626','SAF/02/048/01674','SAF/02/036/01124','SAF/02/034/01948','SAF/02/037/02072','SAF/02/004/03504','SAF/02/034/01944','SAF/02/005/00750','SAF/02/037/02068','SAF/02/008/5084','SAF/02/008/6523','SAF/02/037/7170','SAF/02/029/3740','SAF/02/029/3155','SAF/02/010/2565','SAF/02/029/3975','SAF/02/029/2817','SAF/02/011/2681','SAF/02/029/1833','SAF/02/029/2786','SAF/02/010/3001','SAF/02/029/1273','SAF/02/007/4100','SAF/02/029/2753','SAF/02/029/4252','SAF/02/029/2581','SAF/02/029/3038','SAF/02/029/4813','SAF/02/029/3210','SAF/02/010/2737','SAF/02/006/2651','SAF/02/029/2518','SAF/02/029/2505','SAF/02/029/2794','SAF/02/029/0630','SAF/02/009/1474','SAF/02/033/3141','SAF/02/026/00803','SAF/02/044/00047','SAF/02/029/2776','SAF/02/029/2276','SAF/02/029/1111','SAF/02/029/0436','SAF/02/001/01726','SAF/02/047/00200','SAF/02/048/01654','SAF/02/029/03190','SAF/02/037/02051','SAF/02/002/11563','SAF/02/008/03297','SAF/02/002/03367','SAF/02/004/03372','SAF/02/033/01881','SAF/02/028/00661','SAF/02/008/03283','SAF/02/001/01721','SAF/02/010/01548','SAF/02/023/01631','SAF/02/003/01782','SAF/02/005/00722','SAF/02/037/02045','SAF/02/053/00161','SAF/02/033/01891','SAF/02/029/03252','SAF/02/029/03271','SAF/02/039/00032','SAF/02/002/03382','SAF/02/023/01641','SAF/02/040/00023','SAF/02/002/03322','SAF/02/002/03376','SAF/02/002/03377','SAF/02/002/03378','SAF/02/028/00654','SAF/02/048/01623','SAF/02/007/01457','SAF/02/005/00732','SAF/02/049/00796','SAF/02/049/00795','SAF/02/029/03264','SAF/02/004/03543','SAF/02/001/01675','SAF/02/001/01707','SAF/02/002/03365','SAF/02/011/01030','SAF/02/002/11778','SAF/02/026/0003','SAF/02/037/01975','SAF/02/012/00798','SAF/02/003/01805','SAF/02/007/01491','SAF/02/002/03345','SAF/02/002/3235','SAF/02/002/03350','SAF/02/027/0416','SAF/02/027/1553','SAF/02/003/01804','SAF/02/011/01029','SAF/02/043/00903','SAF/02/014/4027','SAF/02/003/01795','SAF/02/014/4125','SAF/02/038/01461','SAF/02/001/4002','SAF/02/014/4141','SAF/02/011/4479','SAF/02/043/00901','SAF/02/014/4307','SAF/02/014/4321','SAF/02/014/4140','SAF/02/036/01118','SAF/02/007/01357','SAF/02/003/01801','SAF/02/008/03278','SAF/02/028/00666','SAF/02/029/6725','SAF/02/012/00791','SAF/02/004/03382','SAF/02/007/01331','SAF/02/036/01109','SAF/02/001/01685','SAF/02/014/4068','SAF/02/011/4244','SAF/02/011/4265','SAF/02/004/03405','SAF/02/011/4268','SAF/02/011/4277','SAF/02/011/4281','SAF/02/011/4283','SAF/02/001/01666','SAF/02/012/00774','SAF/02/010/01566','SAF/02/004/03376','SAF/02/005/00696','SAF/02/004/03403','SAF/02/004/03432','SAF/02/004/03425','SAF/02/028/00658','SAF/02/038/01388','SAF/02/005/6228','SAF/02/049/00808','SAF/02/038/01389','SAF/02/035/01368','SAF/02/003/01755','SAF/02/001/01642','SAF/02/001/01652','SAF/02/003/01758','SAF/02/002/03292','SAF/02/017/00870','SAF/02/017/00873','SAF/02/012/00764','SAF/02/007/01333','SAF/02/004/03366','SAF/02/022/4066','SAF/02/029/6388','SAF/02/029/4586','SAF/02/023/2875','SAF/02/017/0820','SAF/02/014/2352','SAF/02/007/6756','SAF/02/032/2275','SAF/02/008/5789','SAF/02/029/5844','SAF/02/002/4185','SAF/02/010/3468','SAF/02/011/3701','SAF/02/029/4603','SAF/02/038/6426','SAF/02/037/9161','SAF/02/037/10054','SAF/02/037/3322','SAF/02/037/6079','SAF/02/031/2822','SAF/02/018/1242','SAF/02/004/9814','SAF/02/005/4592','SAF/02/005/4590','SAF/02/012/2300','SAF/02/012/2341','SAF/02/029/5503','SAF/02/037/7116','SAF/02/006/3063','SAF/02/006/3062','SAF/02/011/3462','SAF/02/029/4270','SAF/02/019/4537','SAF/02/019/4536','SAF/02/012/2271','SAF/02/019/3307','SAF/02/010/2780','SAF/02/029/3619','SAF/02/010/3101','SAF/02/037/5394','SAF/02/010/3185','SAF/02/029/3365','SAF/02/029/4722','SAF/02/029/2413','SAF/02/037/6407','SAF/02/029/3954','SAF/02/007/5439','SAF/02/037/4794','SAF/02/029/5388','SAF/02/011/2585','SAF/02/029/2209','SAF/02/029/2206','SAF/02/008/5121','SAF/02/037/6240','SAF/02/029/5266','SAF/02/029/5391','SAF/02/037/4577','SAF/02/037/6247','SAF/02/037/6128','SAF/02/029/5321','SAF/02/029/2700','SAF/02/029/4115','SAF/02/037/9311','SAF/02/029/2337','SAF/02/029/2166','SAF/02/034/0521','SAF/02/029/1001','SAF/02/029/5218','SAF/02/029/3024','SAF/02/029/1930','SAF/02/029/1126','SAF/02/031/3479','SAF/02/011/2855','SAF/02/029/3702','SAF/02/029/2421','SAF/02/029/1684','SAF/02/029/2846','SAF/02/029/4385','SAF/02/029/2908','SAF/02/029/1329','SAF/02/029/4721','SAF/02/029/4752','SAF/02/029/2372','SAF/02/029/1311','SAF/02/029/1487','SAF/02/029/2512','SAF/02/029/5231','SAF/02/029/2543','SAF/02/011/2783','SAF/02/029/2343','SAF/02/029/2177','SAF/02/029/0736','SAF/02/029/2107','SAF/02/029/1569','SAF/02/029/5354','SAF/02/029/5349','SAF/02/029/1978','SAF/02/029/3576','SAF/02/029/2342','SAF/02/029/3801','SAF/02/011/2250','SAF/02/011/2244','SAF/02/011/2858','SAF/02/037/6938','SAF/02/037/7031','SAF/02/029/1774','SAF/02/037/5042','SAF/02/029/3732','SAF/02/029/2940','SAF/02/011/2397','SAF/02/029/1467','SAF/02/029/3650','SAF/02/029/3971','SAF/02/029/0229','SAF/02/029/2198','SAF/02/029/2130','SAF/02/029/2514','SAF/02/029/2031','SAF/02/029/2361','SAF/02/029/2719','SAF/02/029/4777','SAF/02/029/0979','SAF/02/029/1398','SAF/02/029/1886','SAF/02/029/3495','SAF/02/029/0675','SAF/02/029/2916','SAF/02/029/4827','SAF/02/011/2543','SAF/02/029/3845','SAF/02/029/5103','SAF/02/037/5417','SAF/02/029/1701','SAF/02/029/2159','SAF/02/034/3240','SAF/02/029/2831','SAF/02/029/3169','SAF/02/029/4189','SAF/02/008/5987','SAF/02/029/3098','SAF/02/037/5031','SAF/02/029/1069','SAF/02/029/0975','SAF/02/029/0732','SAF/02/037/4785','SAF/02/037/5845','SAF/02/008/4421','SAF/02/029/5242','SAF/02/008/7393','SAF/02/037/6216','SAF/02/037/5122','SAF/02/031/2780','SAF/02/037/5515','SAF/02/037/5507','SAF/02/037/3998','SAF/02/011/1658','SAF/02/037/6774','SAF/02/037/4379','SAF/02/037/3961','SAF/02/037/9154','SAF/02/037/9095','SAF/02/037/7120','SAF/02/037/5280','SAF/02/037/7126','SAF/02/008/4210','SAF/02/037/6713','SAF/02/037/3254','SAF/02/037/7242','SAF/02/029/3008','SAF/02/037/8938','SAF/02/037/5349','SAF/02/037/3071','SAF/02/008/3616','SAF/02/037/5393','SAF/02/037/7095','SAF/02/029/0461','SAF/02/037/3472','SAF/02/037/4168','SAF/02/037/8937','SAF/02/029/3996','SAF/02/003/3487','SAF/02/029/1447','SAF/02/011/2476','SAF/02/034/0494','SAF/02/008/5413','SAF/02/007/4164','SAF/02/037/4981','SAF/02/037/9173','SAF/02/037/6433','SAF/02/033/2889','SAF/02/007/4800','SAF/02/029/0844','SAF/02/029/4053','SAF/02/037/7123','SAF/02/029/3042','SAF/02/029/1665','SAF/02/029/2944','SAF/02/002/2964','SAF/02/037/6989','SAF/02/009/1025','SAF/02/037/4702','SAF/02/007/4589','SAF/02/029/2790','SAF/02/010/1882','SAF/02/009/1837','SAF/02/007/3785','SAF/02/014/3195','SAF/02/011/2114','SAF/02/029/5095','SAF/02/029/4694','SAF/02/017/0717','SAF/02/029/2432','SAF/02/011/3333','SAF/02/011/2988','SAF/02/011/2995','SAF/02/029/4564','SAF/02/011/3304','SAF/02/011/2737','SAF/02/011/2843','SAF/02/003/3393','SAF/02/011/2258','SAF/02/012/0973','SAF/02/004/9060','SAF/02/003/2794','SAF/02/004/7360','SAF/02/004/9001','SAF/02/002/8155','SAF/02/001/2581','SAF/02/001/2552','SAF/02/002/5850','SAF/02/002/8077','SAF/02/001/1029','SAF/02/002/8268','SAF/02/003/3450','SAF/02/005/4027','SAF/02/007/2918','SAF/02/008/6168','SAF/02/037/3702','SAF/02/009/1693','SAF/02/037/6068','SAF/02/032/2748','SAF/02/029/2465','SAF/02/002/0431','SAF/02/002/6512','SAF/02/002/6588','SAF/02/010/2589','SAF/02/037/5479','SAF/02/029/2979','SAF/02/002/3431','SAF/02/029/0824','SAF/02/037/3298','SAF/02/037/4426','SAF/02/002/6391','SAF/02/001/2386','SAF/02/002/7708','SAF/02/002/3073','SAF/02/002/6367','SAF/02/037/4101','SAF/02/029/1807','SAF/02/002/7745','SAF/02/001/1006','SAF/02/001/1753','SAF/02/037/6329','SAF/02/037/3839','SAF/02/008/4305','SAF/02/029/3766','SAF/02/029/1845','SAF/02/032/2093','SAF/02/037/5852','SAF/02/029/2037','SAF/02/008/5315','SAF/02/037/5725','SAF/02/008/5083','SAF/02/008/5375','SAF/02/008/5403','SAF/02/029/3883','SAF/02/029/3994','SAF/02/007/3083','SAF/02/037/2880','SAF/02/029/2931','SAF/02/029/2279','SAF/02/007/3431','SAF/02/007/3137','SAF/02/029/3356','SAF/02/029/2773','SAF/02/002/6312','SAF/02/014/1952','SAF/02/012/1329','SAF/02/008/3087','SAF/02/008/5081','SAF/02/003/2692','SAF/02/002/5567','SAF/02/029/4156','SAF/02/014/2535','SAF/02/012/1919','SAF/02/004/6799','SAF/02/008/1509','SAF/02/004/4766','SAF/02/014/1452','SAF/02/014/1924','SAF/02/014/1935','SAF/02/008/4654','SAF/02/014/1725','SAF/02/038/3936','SAF/02/008/4253','SAF/02/011/2163','SAF/02/008/3764','SAF/02/037/2988','SAF/02/008/4172','SAF/02/001/1166','SAF/02/008/2119','SAF/02/011/2895','SAF/02/029/1630','SAF/02/008/3131','SAF/02/008/4094','SAF/02/008/6027','SAF/02/033/2460','SAF/02/001/1701','SAF/02/029/1811','SAF/02/011/1577','SAF/02/029/3029','SAF/02/014/2376','SAF/02/029/1677','SAF/02/032/1634','SAF/02/008/3845','SAF/02/049/1848','SAF/02/008/3544','SAF/02/011/1902','SAF/02/008/3282','SAF/02/037/2652','SAF/02/049/0341','SAF/02/029/0642','SAF/02/029/4454','SAF/02/014/1927','SAF/02/037/2997','SAF/02/038/4383','SAF/02/008/2958','SAF/02/037/2710','SAF/02/029/4531','SAF/02/011/2554','SAF/02/029/4525','SAF/02/029/4462','SAF/02/029/0947','SAF/02/029/4483','SAF/02/014/2576','SAF/02/014/2464','SAF/02/034/2316','SAF/02/008/2022','SAF/02/014/1925','SAF/02/008/2590','SAF/02/002/4763','SAF/02/029/1501','SAF/02/029/1495','SAF/02/004/6738','SAF/02/037/6313','SAF/02/037/6310','SAF/02/015/0001','SAF/02/034/2852','SAF/02/008/2401','SAF/02/029/3949','SAF/02/002/5427','SAF/02/008/2326','SAF/02/029/3964','SAF/02/029/1177','SAF/02/029/4382','SAF/02/010/1182','SAF/02/003/2771','SAF/02/037/6059','SAF/02/002/5651','SAF/02/008/3415','SAF/02/037/5774','SAF/02/049/1557','SAF/02/029/2213','SAF/02/049/1556','SAF/02/014/1926','SAF/02/029/3643','SAF/02/029/4458','SAF/02/029/1645','SAF/02/001/2102','SAF/02/029/3536','SAF/02/033/2761','SAF/02/007/2495','SAF/02/034/2656','SAF/02/034/2423','SAF/02/029/4213','SAF/02/029/4243','SAF/02/029/0473','SAF/02/037/5455','SAF/02/001/1535','SAF/02/029/3783','SAF/02/008/2404','SAF/02/029/0627','SAF/02/029/3470','SAF/02/007/4644','SAF/02/007/3395','SAF/02/007/3111','SAF/02/007/2953','SAF/02/011/1862','SAF/02/007/3197','SAF/02/008/2597','SAF/02/007/3013','SAF/02/029/3888','SAF/02/002/3810','SAF/02/012/1687','SAF/02/008/4689','SAF/02/001/1858','SAF/02/037/5839','SAF/02/004/5170','SAF/02/011/2852','SAF/02/003/2311','SAF/02/001/1104','SAF/02/014/1727','SAF/02/011/1767','SAF/02/015/1777','SAF/02/015/0325','SAF/02/007/2771','SAF/02/017/0981','SAF/02/002/4173','SAF/02/015/1737','SAF/02/011/1821','SAF/02/015/1329','SAF/02/014/1998','SAF/02/002/3778','SAF/02/001/1704','SAF/02/004/3676','SAF/02/002/3380','SAF/02/014/1960','SAF/02/007/2897','SAF/02/023/1194','SAF/02/002/3277','SAF/02/007/2554','SAF/02/007/2546','SAF/02/037/4677','SAF/02/037/6183','SAF/02/037/3536','SAF/02/014/1684','SAF/02/037/5317','SAF/02/017/0903','SAF/02/017/0848','SAF/02/037/5783','SAF/02/029/0205','SAF/02/016/0931','SAF/02/014/1747','SAF/02/017/1009','SAF/02/029/0302','SAF/02/029/3666','SAF/02/008/4409','SAF/02/015/0966','SAF/02/011/2288','SAF/02/029/3190','SAF/02/029/3290','SAF/02/011/1885','SAF/02/037/3709','SAF/02/011/1787','SAF/02/011/2202','SAF/02/002/2044','SAF/02/011/2233','SAF/02/037/5037','SAF/02/037/5493','SAF/02/037/5584','SAF/02/008/4419','SAF/02/029/3279','SAF/02/012/1296','SAF/02/015/1325','SAF/02/029/1056','SAF/02/014/2020','SAF/02/029/1598','SAF/02/016/0948','SAF/02/029/2997','SAF/02/016/0877','SAF/02/003/0964','SAF/02/015/0776','SAF/02/017/1148','SAF/02/018/0295','SAF/02/006/2437','SAF/02/029/1380','SAF/02/028/0766','SAF/02/028/0647','SAF/02/029/1744','SAF/02/029/1548','SAF/02/028/0767','SAF/02/015/1348','SAF/02/015/0633','SAF/02/028/1001','SAF/02/017/0618','SAF/02/015/0099','SAF/02/029/2128','SAF/02/029/1835','SAF/02/015/0882','SAF/02/029/2057','SAF/02/017/0706','SAF/02/029/2066','SAF/02/029/2179','SAF/02/017/0832','SAF/02/017/0694','SAF/02/016/0770','SAF/02/017/0934','SAF/02/029/1385','SAF/02/037/4305','SAF/02/015/0114','SAF/02/018/0627','SAF/02/016/0720','SAF/02/029/1730','SAF/02/002/1346','SAF/02/032/0675','SAF/02/017/0902','SAF/02/029/1463','SAF/02/016/0257','SAF/02/002/1165','SAF/02/037/4153','SAF/02/029/1342','SAF/02/017/0379','SAF/02/037/4212','SAF/02/037/4251','SAF/02/037/3914','SAF/02/029/1718','SAF/02/029/1660','SAF/02/029/1536','SAF/02/016/0343','SAF/02/017/0319','SAF/02/037/3801','SAF/02/017/0276','SAF/02/016/0021','SAF/02/017/0187','SAF/02/016/0541','SAF/02/029/1523','SAF/02/016/0480','SAF/02/017/0566','SAF/02/017/0567','SAF/02/016/0517','SAF/02/037/3939','SAF/02/037/4263','SAF/02/037/3922','SAF/02/029/1678','SAF/02/029/1166','SAF/02/017/0533','SAF/02/017/0378','SAF/02/017/0342','SAF/02/028/0027','SAF/02/010/0160','SAF/02/017/0043'];
        }
        if($id==2)
        {
            $data=['SAF/03/006/4210','SAF/03/53/2/0690','SAF/03/46/1/0112','SAF/03/021/2676','SAF/03/048/5359','SAF/03/029/01263','SAF/03/029/01233','SAF/03/032/01217','SAF/03/037/00883','SAF/03/037/00897','SAF/03/037/00898','SAF/03/007/7707','SAF/03/021/0471','SAF/03/021/5504','SAF/03/021/2190','SAF/03/021/5526','SAF/03/029/6416','SAF/03/021/1784','SAF/03/013/00876','SAF/03/021/2572','SAF/03/019/5570','SAF/03/027/01124','SAF/03/010/4332','SAF/03/013/4122','SAF/03/018/00652','SAF/03/018/00653','SAF/03/018/00654','SAF/03/012/2995','SAF/03/020/2509','SAF/03/003/4472','SAF/03/037/00712','SAF/03/033/4800','SAF/03/021/1978','SAF/03/021/1749','SAF/03/021/1750','SAF/03/032/01146','SAF/03/020/00899','SAF/03/029/01222','SAF/03/029/01221','SAF/03/021/01281','SAF/03/011/4389','SAF/03/007/00410','SAF/03/037/00843','SAF/03/032/01181','SAF/03/021/2219','SAF/03/021/2181','SAF/03/021/1484','SAF/03/021/0749','SAF/03/021/2407','SAF/03/021/2064','SAF/03/021/2036','SAF/03/021/2511','SAF/03/021/2360','SAF/03/021/2339','SAF/03/021/2666','SAF/03/021/5026','SAF/03/021/2298','SAF/03/048/5443','SAF/03/012/447','SAF/03/055/00397','SAF/03/019/01405','SAF/03/025/1411','SAF/03/025/1758','SAF/03/025/1439','SAF/03/029/6988','SAF/03/025/1924','SAF/03/025/1820','SAF/03/008/9932','SAF/03/021/1712','SAF/03/021/5162','SAF/03/021/1546','SAF/03/021/2371','SAF/03/021/2370','SAF/03/030/4654','SAF/03/014/2599','SAF/03/014/2157','SAF/03/029/4655','SAF/03/029/6675','SAF/03/021/4868','SAF/03/021/4855','SAF/03/021/4861','SAF/03/021/4611','SAF/03/022/4088','SAF/03/022/4248','SAF/03/002/11438','SAF/03/015/1097','SAF/03/015/2932','SAF/03/015/3204','SAF/03/025/1464','SAF/03/025/2008','SAF/03/025/3043','SAF/03/005/5501','SAF/03/005/5404','SAF/03/030/1098','SAF/03/014/4153','SAF/03/022/4249','SAF/03/030/4930','SAF/03/022/4301','SAF/03/030/4926','SAF/03/024/3373','SAF/03/024/2691','SAF/03/015/3121','SAF/03/036/5085','SAF/03/037/12376','SAF/03/037/00710','SAF/03/021/2584','SAF/03/033/1349','SAF/03/025/2993','SAF/03/036/4875','SAF/03/014/4053','SAF/03/014/4166','SAF/03/036/00409','SAF/03/002/01050','SAF/03/008/10118','SAF/03/008/10144','SAF/03/036/00405','SAF/03/014/3028','SAF/03/014/4134','SAF/03/018/1330','SAF/03/007/7299','SAF/03/007/7200','SAF/03/014/4250','SAF/03/021/4858','SAF/03/012/1431','SAF/03/021/4982','SAF/03/037/00735','SAF/03/003/4767','SAF/03/016/1778','SAF/03/003/4773','SAF/03/016/1806','SAF/03/016/1612','SAF/03/007/00420','SAF/03/007/7257','SAF/03/020/4398','SAF/03/024/3412','SAF/03/007/7624','SAF/03/021/4402','SAF/03/026/2309','SAF/03/030/4239','SAF/03/021/5015','SAF/03/003/4806','SAF/03/008/10146','SAF/03/036/00361','SAF/03/033/4546','SAF/03/024/3379','SAF/03/026/2209','SAF/03/026/2327','SAF/03/024/3443','SAF/03/024/3446','SAF/03/024/3499','SAF/03/022/3977','SAF/03/022/4428','SAF/03/021/4991','SAF/03/021/4993','SAF/03/021/5090','SAF/03/021/5194','SAF/03/033/4607','SAF/03/033/4660','SAF/03/020/4324','SAF/03/022/4416','SAF/03/026/2339','SAF/03/026/2333','SAF/03/026/2314','SAF/03/010/00910','SAF/03/014/2712','SAF/03/045/00183','SAF/03/054/00707','SAF/03/031/00904','SAF/03/032/01198','SAF/03/036/5197','SAF/03/036/5518','SAF/03/035/00352','SAF/03/035/4711','SAF/03/029/4516','SAF/03/029/01181','SAF/03/005/00385','SAF/03/021/5361','SAF/03/005/00384','SAF/03/037/00851','SAF/03/035/2622','SAF/03/033/1703','SAF/03/001/00503','SAF/03/013/00774','SAF/03/018/1611','SAF/03/012/1458','SAF/03/034/00668','SAF/03/049/3176','SAF/03/010/4367','SAF/03/008/9738','SAF/03/009/2931','SAF/03/024/01061','SAF/03/003/00508','SAF/03/002/10371','SAF/03/015/00998','SAF/03/029/01188','SAF/03/023/2988','SAF/03/054/00657','SAF/03/030/01152','SAF/03/054/00656','SAF/03/017/1666','SAF/03/017/0410','SAF/03/017/0412','SAF/03/017/0414','SAF/03/014/1978','SAF/03/017/0974','SAF/03/015/0900','SAF/03/017/0314','SAF/03/015/0571','SAF/03/008/2741','SAF/03/018/0430','SAF/03/018/0428','SAF/03/018/0429','SAF/03/018/0967','SAF/03/006/2854','SAF/03/017/0779','SAF/03/016/0725','SAF/03/016/0668','SAF/03/017/1444','SAF/03/015/1239','SAF/03/015/1592','SAF/03/015/2237','SAF/03/015/1155','SAF/03/036/2786','SAF/03/032/1944','SAF/03/017/0884','SAF/03/017/0877','SAF/03/018/0189','SAF/03/018/0180','SAF/03/015/1354','SAF/03/028/0736','SAF/03/035/4453','SAF/03/030/4725','SAF/03/021/3982','SAF/03/025/00463','SAF/03/045/00215','SAF/03/045/00214','SAF/03/004/2216','SAF/03/035/4763','SAF/03/033/4728','SAF/03/029/6794','SAF/03/036/00399','SAF/03/024/1044','SAF/03/027/3592','SAF/03/027/1905','SAF/03/027/1905','SAF/03/027/3532','SAF/03/027/1493','SAF/03/027/3736','SAF/03/027/1662','SAF/03/027/3510','SAF/03/027/3524','SAF/03/027/2017','SAF/03/027/3562','SAF/03/027/3519','SAF/03/027/2573','SAF/03/007/5958','SAF/03/024/2435','SAF/03/024/2436','SAF/03/024/0491','SAF/03/007/5411','SAF/03/024/1114','SAF/03/024/2415','SAF/03/024/2855','SAF/03/024/2785','SAF/03/024/2677','SAF/03/007/6231','SAF/03/011/3412','SAF/03/024/1282','SAF/03/007/6891','SAF/03/024/2798','SAF/03/024/2910','SAF/03/024/2910','SAF/03/024/2759','SAF/03/024/2908','SAF/03/024/2856','SAF/03/024/2795','SAF/03/024/2861','SAF/03/011/3106','SAF/03/024/0567','SAF/03/024/1111','SAF/03/024/2657','SAF/03/024/2659','SAF/03/024/2683','SAF/03/011/3505','SAF/03/024/2682','SAF/03/024/2796','SAF/03/011/3722','SAF/03/007/1202','SAF/03/024/1879','SAF/03/038/00675','SAF/03/028/2666','SAF/03/034/00663','SAF/03/033/00820','SAF/03/053/00205','SAF/03/004/01503','SAF/03/035/3827','SAF/03/023/3118','SAF/03/034/00654','SAF/03/048/00589','SAF/03/012/00459','SAF/03/029/1672','SAF/03/037/00823','SAF/03/029/6656','SAF/03/036/00395','SAF/03/022/3700','SAF/03/024/2692','SAF/03/011/3300','SAF/03/007/7106','SAF/03/011/3049','SAF/03/011/3813','SAF/03/011/2168','SAF/03/027/2259','SAF/03/027/2195','SAF/03/027/3329','SAF/03/027/3446','SAF/03/024/3213','SAF/03/020/2536','SAF/03/038/00842','SAF/03/012/1707','SAF/03/011/3495','SAF/03/011/0195','SAF/03/011/2825','SAF/03/011/1214','SAF/03/011/3739','SAF/03/011/3846','SAF/03/011/3797','SAF/03/007/7131','SAF/03/007/7254','SAF/03/007/2612','SAF/03/007/7144','SAF/03/011/2804','SAF/03/011/2804','SAF/03/011/1985','SAF/03/011/3948','SAF/03/007/7204','SAF/03/007/7168','SAF/03/007/7148','SAF/03/007/7143','SAF/03/020/0687','SAF/03/021/2502','SAF/03/045/00210','SAF/03/013/1367','SAF/03/018/1184','SAF/03/018/0363','SAF/03/015/2292','SAF/03/016/1247','SAF/03/006/2871','SAF/03/015/1219','SAF/03/017/1703','SAF/03/018/1032','SAF/03/014/2261','SAF/03/018/1041','SAF/03/006/2917','SAF/03/015/1496','SAF/03/009/2106','SAF/03/002/4464','SAF/03/008/9913','SAF/03/010/4267','SAF/03/010/4130','SAF/03/010/4263','SAF/03/029/6742','SAF/03/024/3127','SAF/03/054/4248','SAF/03/024/2042','SAF/03/024/2715','SAF/03/024/2701','SAF/03/024/2862','SAF/03/024/2832','SAF/03/024/3030','SAF/03/024/2865','SAF/03/024/3024','SAF/03/024/2969','SAF/03/024/2950','SAF/03/024/2716','SAF/03/024/2943','SAF/03/011/3844','SAF/03/024/2930','SAF/03/011/3807','SAF/03/024/2875','SAF/03/024/2782','SAF/03/023/2896','SAF/03/038/6354','SAF/03/011/3494','SAF/03/007/6303','SAF/03/024/2713','SAF/03/038/8229','SAF/03/023/3202','SAF/03/023/3201','SAF/03/023/3359','SAF/03/021/2164','SAF/03/017/0967','SAF/03/017/1485','SAF/03/015/2039','SAF/03/018/0471','SAF/03/017/0161','SAF/03/017/0556','SAF/03/016/0845','SAF/03/009/1637','SAF/03/008/5019','SAF/03/008/5037','SAF/03/029/1381','SAF/03/009/0375','SAF/03/015/1700','SAF/03/018/0179','SAF/03/018/0726','SAF/03/015/1622','SAF/03/017/0134','SAF/03/014/2279','SAF/03/014/2657','SAF/03/029/2564','SAF/03/026/1198','SAF/03/016/1303','SAF/03/018/1189','SAF/03/017/0084','SAF/03/018/0389','SAF/03/018/0529','SAF/03/018/1082','SAF/03/018/1076','SAF/03/018/0532','SAF/03/018/0577','SAF/03/017/0780','SAF/03/017/1691','SAF/03/012/1369','SAF/03/015/1710','SAF/03/017/0617','SAF/03/017/1126','SAF/03/016/1249','SAF/03/016/1251','SAF/03/017/0727','SAF/03/016/1250','SAF/03/017/0732','SAF/03/012/1388','SAF/03/014/2258','SAF/03/026/1224','SAF/03/017/1580','SAF/03/013/1536','SAF/03/014/1743','SAF/03/015/2313','SAF/03/030/2024','SAF/03/030/2986','SAF/03/017/0633','SAF/03/026/0940','SAF/03/017/1787','SAF/03/015/2325','SAF/03/018/1188','SAF/03/018/0106','SAF/03/015/1606','SAF/03/015/0449','SAF/03/014/1817','SAF/03/017/0852','SAF/03/017/0336','SAF/03/026/1292','SAF/03/026/0783','SAF/03/018/0386','SAF/03/017/0716','SAF/03/014/0952','SAF/03/015/1444','SAF/03/015/2341','SAF/03/018/0641','SAF/03/018/1037','SAF/03/018/0737','SAF/03/012/0795','SAF/03/015/1647','SAF/03/014/2521','SAF/03/017/0565','SAF/03/017/1682','SAF/03/026/0679','SAF/03/026/1633','SAF/03/026/1634','SAF/03/029/2054','SAF/03/029/2388','SAF/03/029/2856','SAF/03/018/1185','SAF/03/017/0976','SAF/03/018/1179','SAF/03/018/0162','SAF/03/012/1091','SAF/03/018/1172','SAF/03/017/0733','SAF/03/026/1328','SAF/03/014/2174','SAF/03/026/0662','SAF/03/018/1183','SAF/03/026/0904','SAF/03/018/1186','SAF/03/018/0233','SAF/03/030/2666','SAF/03/017/0971','SAF/03/017/0790','SAF/03/017/0951','SAF/03/012/1884','SAF/03/015/1118','SAF/03/018/0259','SAF/03/015/1129','SAF/03/023/1967','SAF/03/015/1612','SAF/03/014/1813','SAF/03/018/0493','SAF/03/008/2204','SAF/03/015/0901','SAF/03/015/0788','SAF/03/015/0772','SAF/03/015/0774','SAF/03/015/0651','SAF/03/015/0647','SAF/03/029/3109','SAF/03/015/0799','SAF/03/026/1510','SAF/03/035/2065','SAF/03/036/2902','SAF/03/015/0634','SAF/03/018/0541','SAF/03/014/1699','SAF/03/018/0642','SAF/03/018/0346','SAF/03/014/1704','SAF/03/018/0367','SAF/03/018/0365','SAF/03/018/0788','SAF/03/014/1695','SAF/03/018/0281','SAF/03/018/0293','SAF/03/018/0289','SAF/03/017/1478','SAF/03/018/0307','SAF/03/018/0305','SAF/03/015/0477','SAF/03/011/1258','SAF/03/038/00652','SAF/03/026/0740','SAF/03/038/00653','SAF/03/014/0991','SAF/03/026/0418','SAF/03/026/0802','SAF/03/026/1262','SAF/03/028/2306','SAF/03/030/4869','SAF/03/004/12832','SAF/03/011/2712','SAF/03/018/0334','SAF/03/015/0781','SAF/03/015/0648','SAF/03/055/4078','SAF/03/024/1740','SAF/03/024/1693','SAF/03/024/1803','SAF/03/024/1891','SAF/03/024/1998','SAF/03/024/2027','SAF/03/024/2028','SAF/03/024/2026','SAF/03/024/1552','SAF/03/024/2990','SAF/03/029/0634','SAF/03/024/1611','SAF/03/024/1263','SAF/03/024/1389','SAF/03/024/1424','SAF/03/024/3133','SAF/03/024/2989','SAF/03/024/2201','SAF/03/024/2853','SAF/03/024/2867','SAF/03/024/2670','SAF/03/024/2517','SAF/03/024/2284','SAF/03/024/2135','SAF/03/024/2134','SAF/03/024/2992','SAF/03/024/2991','SAF/03/024/2453','SAF/03/024/2993','SAF/03/024/2156','SAF/03/024/2454','SAF/03/004/12164','SAF/03/055/3749','SAF/03/009/1053','SAF/03/030/4867','SAF/03/047/324','SAF/03/024/01052','SAF/03/029/01073','SAF/03/037/00785','SAF/03/023/1318','SAF/03/021/4063','SAF/03/023/0961','SAF/03/021/1510','SAF/03/029/5946','SAF/03/029/5998','SAF/03/035/00353','SAF/03/035/00354','SAF/03/033/4327','SAF/03/034/4265','SAF/03/034/4746','SAF/03/034/4502','SAF/03/030/01098','SAF/03/030/01099','SAF/03/030/01092','SAF/03/023/1154','SAF/03/021/5170','SAF/03/034/4969','SAF/03/032/3146','SAF/03/019/3943','SAF/03/020/4366','SAF/03/011/3556','SAF/03/011/3475','SAF/03/011/3418','SAF/03/011/3419','SAF/03/011/3411','SAF/03/011/3410','SAF/03/025/2819','SAF/03/007/3565','SAF/03/024/2183','SAF/03/024/0528','SAF/03/024/0159','SAF/03/024/1613','SAF/03/024/2165','SAF/03/024/1221','SAF/03/024/1306','SAF/03/037/00797','SAF/03/024/1747','SAF/03/024/2141','SAF/03/024/2142','SAF/03/024/1527','SAF/03/024/1540','SAF/03/024/1688','SAF/03/024/2140','SAF/03/024/2010','SAF/03/002/5083','SAF/03/024/2107','SAF/03/025/0974','SAF/03/021/01159','SAF/03/048/00609','SAF/03/002/01273','SAF/03/023/3344','SAF/03/004/12587','SAF/03/023/3062','SAF/03/021/1179','SAF/03/034/00638','SAF/03/002/4844','SAF/03/027/01085','SAF/03/027/01088','SAF/03/050/1342','SAF/03/008/9417','SAF/03/009/00287','SAF/03/021/1717','SAF/03/021/2056','SAF/03/021/2125','SAF/03/021/2169','SAF/03/021/2068','SAF/03/021/2175','SAF/03/021/2028','SAF/03/021/1840','SAF/03/024/0661','SAF/03/024/3531','SAF/03/024/3492','SAF/03/024/3479','SAF/03/024/01054','SAF/03/024/01066','SAF/03/024/3395','SAF/03/024/01057','SAF/03/038/00730','SAF/03/006/4399','SAF/03/030/01134','SAF/03/038/00659','SAF/03/029/01078','SAF/03/036/00363','SAF/03/002/01111','SAF/03/026/1308','SAF/03/036/00337','SAF/03/022/4036','SAF/03/026/2157','SAF/03/024/3513','SAF/03/004/11510','SAF/03/037/00739','SAF/03/037/00736','SAF/03/037/00749','SAF/03/004/01346','SAF/03/036/4898','SAF/03/002/01105','SAF/03/036/4897','SAF/03/004/11718','SAF/03/028/00473','SAF/03/028/1118','SAF/03/026/2212','SAF/03/026/2045','SAF/03/026/2143','SAF/03/026/2172','SAF/03/026/2332','SAF/03/020/4499','SAF/03/021/2222','SAF/03/026/2337','SAF/03/026/783','SAF/03/017/1931','SAF/03/020/3917','SAF/03/020/3697','SAF/03/021/2633','SAF/03/049/00257','SAF/03/031/5907','SAF/03/030/2943','SAF/03/055/00303','SAF/03/021/5608','SAF/03/037/00713','SAF/03/037/12892','SAF/03/037/12990','SAF/03/037/12899','SAF/03/021/2931','SAF/03/021/1987','SAF/03/020/1051','SAF/03/020/1030','SAF/03/012/2405','SAF/03/023/1747','SAF/03/043/6119','SAF/03/028/2517','SAF/03/027/01122','SAF/03/031/5740','SAF/03/030/4868','SAF/03/002/11100','SAF/03/002/10696','SAF/03/002/10609','SAF/03/026/00791','SAF/03/002/10869','SAF/03/014/4040','SAF/03/002/10963','SAF/03/002/9854','SAF/03/054/3515','SAF/03/048/5481','SAF/03/054/3525','SAF/03/038/00765','SAF/03/020/4518','SAF/03/020/4519','SAF/03/020/4515','SAF/03/007/6971','SAF/03/022/4031','SAF/03/010/00874','SAF/03/010/00876','SAF/03/010/00872','SAF/03/010/00878','SAF/03/010/00873','SAF/03/010/00879','SAF/03/023/01008','SAF/03/022/3906','SAF/03/022/3984','SAF/03/014/1294','SAF/03/011/1553','SAF/03/014/1271','SAF/03/014/0807','SAF/03/014/0890','SAF/03/011/1552','SAF/03/012/2873','SAF/03/027/3292','SAF/03/012/0863','SAF/03/012/2200','SAF/03/007/8014','SAF/03/012/2645','SAF/03/012/2658','SAF/03/011/4187','SAF/03/011/4339','SAF/03/011/1034','SAF/03/014/01022','SAF/03/012/2955','SAF/03/011/4475','SAF/03/013/4427','SAF/03/014/0755','SAF/03/013/00773','SAF/03/038/8565','SAF/03/014/1145','SAF/03/011/00424','SAF/03/012/2490','SAF/03/012/2491','SAF/03/007/6704','SAF/03/013/3570','SAF/03/007/6776','SAF/03/007/6775','SAF/03/007/6777','SAF/03/007/6693','SAF/03/007/6696','SAF/03/011/3527','SAF/03/011/3420','SAF/03/025/1266','SAF/03/043/5414','SAF/03/013/4428','SAF/03/013/4120','SAF/03/013/4179','SAF/03/014/3995','SAF/03/011/3825','SAF/03/013/3969','SAF/03/014/3736','SAF/03/013/3884','SAF/03/024/2086','SAF/03/014/0892','SAF/03/025/1267','SAF/03/025/1269','SAF/03/026/0076','SAF/03/011/1934','SAF/03/014/0833','SAF/03/014/0245','SAF/03/014/1206','SAF/03/014/2554','SAF/03/014/1148','SAF/03/012/2201','SAF/03/014/1354','SAF/03/013/1931','SAF/03/037/10884','SAF/03/001/4261','SAF/03/014/3544','SAF/03/014/1578','SAF/03/013/0694','SAF/03/013/0978','SAF/03/025/3514','SAF/03/014/0437','SAF/03/011/0333','SAF/03/014/1266','SAF/03/014/0493','SAF/03/011/1400','SAF/03/012/0869','SAF/03/013/3088','SAF/03/014/0617','SAF/03/014/0144','SAF/03/014/0349','SAF/03/014/0784','SAF/03/013/0980','SAF/03/014/0971','SAF/03/014/0839','SAF/03/013/0912','SAF/03/014/0317','SAF/03/014/1264','SAF/03/014/0834','SAF/03/014/2564','SAF/03/014/0703','SAF/03/013/0693','SAF/03/011/0653','SAF/03/014/0145','SAF/03/020/4098','SAF/03/024/01116','SAF/03/002/01187','SAF/03/023/0785','SAF/03/023/0795','SAF/03/014/0659','SAF/03/014/0641','SAF/03/023/0745','SAF/03/023/0176','SAF/03/014/0594','SAF/03/014/0578','SAF/03/014/0559','SAF/03/023/0079','SAF/03/023/0681','SAF/03/022/0828','SAF/03/023/0094','SAF/03/020/4517','SAF/03/026/1857','SAF/03/026/1856','SAF/03/026/1860','SAF/03/020/2691','SAF/03/001/2013','SAF/03/019/2683','SAF/03/020/2641','SAF/03/022/2115D','SAF/03/019/2903','SAF/03/019/3326','SAF/03/004/6100','SAF/03/019/3339','SAF/03/021/1981','SAF/03/001/1940','SAF/03/021/2674','SAF/03/022/1985','SAF/03/022/1723','SAF/03/001/1853','SAF/03/021/2679','SAF/03/003/2489','SAF/03/021/2683','SAF/03/001/1750','SAF/03/004/5351','SAF/03/004/5586','SAF/03/019/0987','SAF/03/004/5391','SAF/03/019/1738','SAF/03/021/2139','SAF/03/004/5924','SAF/03/019/2912','SAF/03/022/1403','SAF/03/021/2413','SAF/03/022/1399','SAF/03/019/2680','SAF/03/004/5442','SAF/03/021/2421','SAF/03/020/2378','SAF/03/021/2512','SAF/03/048/4812','SAF/03/010/0319','SAF/03/020/2272','SAF/03/003/2211','SAF/03/004/5138','SAF/03/019/2785','SAF/03/022/0832','SAF/03/027/0515','SAF/03/027/0114','SAF/03/027/3943','SAF/03/027/3944','SAF/03/027/3930','SAF/03/027/3909','SAF/03/027/3670','SAF/03/027/3545','SAF/03/027/3965','SAF/03/027/3537','SAF/03/027/1807','SAF/03/027/3912','SAF/03/027/0162','SAF/03/027/0141','SAF/03/027/3560','SAF/03/027/2781','SAF/03/027/2779','SAF/03/027/2782','SAF/03/048/5310','SAF/03/048/5461','SAF/03/048/5475','SAF/03/025/3518','SAF/03/048/5507','SAF/03/048/5497','SAF/03/048/5513','SAF/03/048/5531','SAF/03/048/5578','SAF/03/027/0510','SAF/03/027/3845','SAF/03/027/3844','SAF/03/027/3738','SAF/03/027/3600','SAF/03/027/3589','SAF/03/027/2729','SAF/03/027/2730','SAF/03/027/2732','SAF/03/027/2109','SAF/03/027/2218','SAF/03/027/2194','SAF/03/027/2395','SAF/03/027/2439','SAF/03/027/2285','SAF/03/027/2196','SAF/03/027/1452','SAF/03/027/3697','SAF/03/027/3704','SAF/03/027/1915','SAF/03/019/2466','SAF/03/027/3702','SAF/03/027/3554','SAF/03/027/3722','SAF/03/027/3546','SAF/03/027/3710','SAF/03/027/3561','SAF/03/027/3711','SAF/03/027/3785','SAF/03/027/3647','SAF/03/027/3658','SAF/03/027/3508','SAF/03/027/3419','SAF/03/027/3326','SAF/03/027/3566','SAF/03/027/3262','SAF/03/027/3611','SAF/03/027/3250','SAF/03/027/3633','SAF/03/027/3628','SAF/03/027/3646','SAF/03/027/3696','SAF/03/027/3227','SAF/03/027/3427','SAF/03/027/3214','SAF/03/027/3406','SAF/03/027/3349','SAF/03/027/3355','SAF/03/027/3426','SAF/03/027/0587','SAF/03/027/2243','SAF/03/027/3084','SAF/03/027/3115','SAF/03/027/3151','SAF/03/027/3158','SAF/03/027/3169','SAF/03/027/3213','SAF/03/027/2415','SAF/03/027/2308','SAF/03/027/2865','SAF/03/027/1290','SAF/03/027/1398','SAF/03/027/0245','SAF/03/027/0120','SAF/03/027/0091','SAF/03/027/2153','SAF/03/027/2823','SAF/03/027/1668','SAF/03/027/1350','SAF/03/027/1205','SAF/03/027/2337','SAF/03/027/1393','SAF/03/027/2599','SAF/03/019/1605','SAF/03/004/5150','SAF/03/019/2711','SAF/03/019/2722','SAF/03/022/1099','SAF/03/019/2718','SAF/03/022/1365','SAF/03/022/1366','SAF/03/022/1359','SAF/03/027/2065','SAF/03/004/4811','SAF/03/027/1767','SAF/03/022/1272','SAF/03/004/3529','SAF/03/013/1857','SAF/03/013/1858','SAF/03/027/1766','SAF/03/027/1670','SAF/03/027/1672','SAF/03/024/1002','SAF/03/024/0996','SAF/03/001/1108','SAF/03/024/1004','SAF/03/024/1006','SAF/03/024/1000','SAF/03/024/0992','SAF/03/019/4438','SAF/03/014/4207','SAF/03/021/2556','SAF/03/019/5525','SAF/03/019/5182','SAF/03/023/0827','SAF/03/024/2325','SAF/03/011/3526','SAF/03/011/3804','SAF/03/011/3802','SAF/03/024/2315','SAF/03/031/5848','SAF/03/031/5812','SAF/03/031/5756','SAF/03/021/1847','SAF/03/016/0919','SAF/03/011/3550','SAF/03/024/2662','SAF/03/011/3089','SAF/03/024/2745','SAF/03/024/2840','SAF/03/011/3769','SAF/03/024/2246','SAF/03/024/2414','SAF/03/007/5898','SAF/03/024/2523','SAF/03/007/6154','SAF/03/024/2620','SAF/03/007/6187','SAF/03/007/3468','SAF/03/024/1872','SAF/03/007/3591','SAF/03/007/2295','SAF/03/007/4820','SAF/03/011/3814','SAF/03/024/2669','SAF/03/022/1995','SAF/03/024/2712','SAF/03/011/3759','SAF/03/022/1757','SAF/03/024/2676','SAF/03/024/2766','SAF/03/024/2800','SAF/03/037/13272','SAF/03/037/13265','SAF/03/037/13203','SAF/03/037/13186','SAF/03/037/13185','SAF/03/037/13164','SAF/03/037/13146','SAF/03/037/13137','SAF/03/037/13117','SAF/03/037/13116','SAF/03/037/13114','SAF/03/037/13091','SAF/03/037/13074','SAF/03/035/4370','SAF/03/021/2402','SAF/03/007/6460','SAF/03/024/2909','SAF/03/024/2671','SAF/03/011/3501','SAF/03/024/1633','SAF/03/011/2287','SAF/03/007/6288','SAF/03/007/6289','SAF/03/024/2711','SAF/03/024/2651','SAF/03/007/6200','SAF/03/011/3414','SAF/03/011/3441','SAF/03/011/3476','SAF/03/007/6120','SAF/03/022/1563','SAF/03/024/2272','SAF/03/007/5532','SAF/03/022/1485','SAF/03/022/1544','SAF/03/022/3002','SAF/03/004/1220','SAF/03/021/2755','SAF/03/022/1835','SAF/03/022/1566','SAF/03/022/1676','SAF/03/022/2065','SAF/03/022/1373','SAF/03/035/4372','SAF/03/037/13318','SAF/03/037/13305','SAF/03/037/13301','SAF/03/037/13286','SAF/03/037/13277','SAF/03/038/9651','SAF/03/033/4445','SAF/03/038/9567','SAF/03/038/9547','SAF/03/031/5761','SAF/03/038/9681','SAF/03/038/9682','SAF/03/038/9806','SAF/03/038/9830','SAF/03/033/4517','SAF/03/054/00589','SAF/03/021/3870','SAF/03/022/1474','SAF/03/021/2712','SAF/03/021/2341','SAF/03/021/2714','SAF/03/021/2713','SAF/03/055/4771','SAF/03/013/4385','SAF/03/021/5080','SAF/03/021/2349','SAF/03/021/2756','SAF/03/021/2758','SAF/03/021/2351','SAF/03/021/2594','SAF/03/021/2540','SAF/03/034/00611','SAF/03/037/00785','SAF/03/038/9729','SAF/03/038/9464','SAF/03/055/00252','SAF/03/038/8710','SAF/03/055/00251','SAF/03/016/1664','SAF/03/037/00720','SAF/03/037/00721','SAF/03/037/00715','SAF/03/015/1663','SAF/03/037/00718','SAF/03/037/13304','SAF/03/015/00991','SAF/03/015/00990','SAF/03/037/00734','SAF/03/015/00992','SAF/03/035/4564','SAF/03/037/00737','SAF/03/033/4786','SAF/03/027/01119','SAF/03/027/01120','SAF/03/027/4523','SAF/03/027/01123','SAF/03/038/9024','SAF/03/038/9005','SAF/03/038/8626','SAF/03/048/5562','SAF/03/015/3424','SAF/03/015/3423','SAF/03/015/3422','SAF/03/015/3410','SAF/03/048/00517','SAF/03/015/3409','SAF/03/015/3411','SAF/03/048/507','SAF/03/048/5189','SAF/03/038/00664','SAF/03/026/00785','SAF/03/038/8480','SAF/03/013/4430','SAF/03/026/2278','SAF/03/026/2299','SAF/03/029/3401','SAF/03/027/4083','SAF/03/027/01117','SAF/03/048/00513','SAF/03/018/1564','SAF/03/018/1565','SAF/03/018/1583','SAF/03/018/1704','SAF/03/018/0322','SAF/03/046/0776','SAF/03/046/0890','SAF/03/018/1754','SAF/03/018/1642','SAF/03/018/1520','SAF/03/018/1521','SAF/03/018/1522','SAF/03/019/5218','SAF/03/019/5217','SAF/03/019/5214','SAF/03/002/01148','SAF/03/026/2197','SAF/03/026/2093','SAF/03/026/2330','SAF/03/026/2331','SAF/03/024/0852','SAF/03/004/12314','SAF/03/022/3028','SAF/03/021/1986','SAF/03/022/2498','SAF/03/021/3067','SAF/03/034/4807','SAF/03/034/4815','SAF/03/025/00478','SAF/03/021/2065','SAF/03/012/2696','SAF/03/020/4545','SAF/03/038/7384','SAF/03/034/4633','SAF/03/034/4779','SAF/03/025/2140','SAF/03/013/4111','SAF/03/013/4070','SAF/03/013/4125','SAF/03/021/4333','SAF/03/013/4262','SAF/03/033/4678','SAF/03/038/00761','SAF/03/021/5623','SAF/03/007/6083','SAF/03/011/4237','SAF/03/029/6280','SAF/03/021/5202','SAF/03/021/5167','SAF/03/021/5187','SAF/03/021/5262','SAF/03/021/5231','SAF/03/021/5244','SAF/03/019/5253','SAF/03/014/4085','SAF/03/004/11269','SAF/03/014/0759','SAF/03/014/3762','SAF/03/013/4446','SAF/03/013/4147','SAF/03/013/4508','SAF/03/013/763','SAF/03/024/01107','SAF/03/020/4317','SAF/03/020/1584','SAF/03/021/5617','SAF/03/008/9152','SAF/03/021/1964','SAF/03/021/2238','SAF/03/021/2073','SAF/03/021/1041','SAF/03/021/2100','SAF/03/021/5630','SAF/03/037/12879','SAF/03/037/12273','SAF/03/037/12274','SAF/03/037/12275','SAF/03/037/13206','SAF/03/021/5158','SAF/03/037/12258','SAF/03/022/4434','SAF/03/021/4990','SAF/03/021/5533','SAF/03/024/3198','SAF/03/027/01093','SAF/03/018/00609','SAF/03/022/1962','SAF/03/014/2410','SAF/03/004/01455','SAF/03/020/4302','SAF/03/022/1154','SAF/03/002/11353','SAF/03/002/11480','SAF/03/018/1555','SAF/03/018/1561','SAF/03/018/0387','SAF/03/018/0697','SAF/03/014/0684','SAF/03/014/2471','SAF/03/020/4349','SAF/03/014/1017','SAF/03/014/1629','SAF/03/021/2162','SAF/03/014/1632','SAF/03/014/0969','SAF/03/014/2556','SAF/03/002/01176','SAF/03/014/2413','SAF/03/014/2565','SAF/03/002/11779','SAF/03/029/0613','SAF/03/014/2209','SAF/03/029/1968','SAF/03/020/0528','SAF/03/001/3820','SAF/03/020/0530','SAF/03/020/2650','SAF/03/026/2178','SAF/03/027/3905','SAF/03/029/6809','SAF/03/005/00282','SAF/03/005/00328','SAF/03/021/5578','SAF/03/014/4229','SAF/03/014/4232','SAF/03/014/4231','SAF/03/020/1166','SAF/03/046/0891','SAF/03/019/2926','SAF/03/001/3943','SAF/03/001/4403','SAF/03/001/4124','SAF/03/001/4111','SAF/03/001/3999','SAF/03/002/11767','SAF/03/014/1532','SAF/03/014/2438','SAF/03/014/4247','SAF/03/022/0928','SAF/03/022/0929','SAF/03/022/0877','SAF/03/022/0927','SAF/03/032/2593','SAF/03/032/2571','SAF/03/021/2554','SAF/03/021/2675','SAF/03/014/1307','SAF/03/014/1570','SAF/03/019/4738','SAF/03/014/1251','SAF/03/012/1218','SAF/03/011/3100','SAF/03/011/3101','SAF/03/012/2588','SAF/03/011/3103','SAF/03/014/4286','SAF/03/002/11768','SAF/03/002/11500','SAF/03/019/4566','SAF/03/011/3104','SAF/03/014/2405','SAF/03/019/5240','SAF/03/001/3840','SAF/03/013/4064','SAF/03/013/3975','SAF/03/043/5560','SAF/03/043/5673','SAF/03/022/4421','SAF/03/014/2409','SAF/03/014/2310','SAF/03/014/2395','SAF/03/043/5750','SAF/03/014/2281','SAF/03/020/4300','SAF/03/013/3976','SAF/03/014/1840','SAF/03/002/11752','SAF/03/009/2667','SAF/03/004/01398','SAF/03/022/1294','SAF/03/011/4041','SAF/03/021/2322','SAF/03/021/2466','SAF/03/021/5448','SAF/03/004/01338','SAF/03/032/3643','SAF/03/032/3642','SAF/03/021/5588','SAF/03/021/2102','SAF/03/021/2634','SAF/03/004/12542','SAF/03/009/2899','SAF/03/009/259','SAF/03/009/2916','SAF/03/008/9222','SAF/03/006/4182','SAF/03/006/4355','SAF/03/006/4356','SAF/03/006/4357','SAF/03/006/4358','SAF/03/006/4360','SAF/03/008/9189','SAF/03/006/4482','SAF/03/006/4484','SAF/03/006/4596','SAF/03/009/2918','SAF/03/004/12454','SAF/03/004/12024','SAF/03/005/5477','SAF/03/004/9756','SAF/03/006/3760','SAF/03/005/6282','SAF/03/032/3641','SAF/03/032/3640','SAF/03/022/4259','SAF/03/026/00786','SAF/03/022/4137','SAF/03/022/4195','SAF/03/022/4194','SAF/03/034/00586','SAF/03/009/3038','SAF/03/038/9419','SAF/03/009/3034','SAF/03/002/01127','SAF/03/002/01128','SAF/03/024/3475','SAF/03/011/3824','SAF/03/001/4087','SAF/03/014/2356','SAF/03/017/2394','SAF/03/017/2030','SAF/03/021/5317','SAF/03/021/5316','SAF/03/021/5334','SAF/03/017/1990','SAF/03/021/5401','SAF/03/017/0428','SAF/03/002/10525','SAF/03/004/13262','SAF/03/004/13223','SAF/03/021/5338','SAF/03/021/5374','SAF/03/004/13193','SAF/03/004/13192','SAF/03/004/13112','SAF/03/004/13111','SAF/03/004/13034','SAF/03/021/5313','SAF/03/002/10611','SAF/03/002/10589','SAF/03/002/11721','SAF/03/003/4799','SAF/03/003/4864','SAF/03/003/4840','SAF/03/003/4775','SAF/03/003/4880','SAF/03/004/12917','SAF/03/004/12911','SAF/03/005/5978','SAF/03/005/5811','SAF/03/005/5673','SAF/03/007/8143','SAF/03/002/9915','SAF/03/007/7496','SAF/03/002/10587','SAF/03/002/11039','SAF/03/021/5382','SAF/03/021/5427','SAF/03/004/12415','SAF/03/004/12321','SAF/03/004/11655','SAF/03/004/13205','SAF/03/004/12366','SAF/03/002/10510','SAF/03/014/2402','SAF/03/002/10509','SAF/03/002/10497','SAF/03/001/3873','SAF/03/002/9633','SAF/03/001/3967','SAF/03/021/5366','SAF/03/002/11006','SAF/03/002/11284','SAF/03/007/7501','SAF/03/013/4448','SAF/03/002/01103','SAF/03/002/01099','SAF/03/002/10649','SAF/03/003/00498','SAF/03/003/4591','SAF/03/003/4263','SAF/03/014/1685','SAF/03/014/1880','SAF/03/014/1654','SAF/03/014/1900','SAF/03/021/5464','SAF/03/014/1431','SAF/03/003/4665','SAF/03/014/1617','SAF/03/014/1141','SAF/03/002/8913','SAF/03/003/4696','SAF/03/003/4442','SAF/03/002/01080','SAF/03/003/4762','SAF/03/004/01325','SAF/03/003/4464','SAF/03/055/4904','SAF/03/014/2651','SAF/03/002/01091','SAF/03/002/01167','SAF/03/002/11288','SAF/03/022/3999','SAF/03/022/4032','SAF/03/003/4637','SAF/03/003/1793','SAF/03/003/4890','SAF/03/004/11690','SAF/03/004/11677','SAF/03/014/1678','SAF/03/014/01051','SAF/03/002/01158','SAF/03/037/00757','SAF/03/019/4369','SAF/03/023/01040','SAF/03/021/4762','SAF/03/014/4343','SAF/03/014/4342','SAF/03/013/00775','SAF/03/021/5137','SAF/03/029/6975','SAF/03/029/6985','SAF/03/002/10668','SAF/03/002/10648','SAF/03/021/5028','SAF/03/009/3072','SAF/03/010/4384','SAF/03/009/2936','SAF/03/014/01061','SAF/03/021/5058','SAF/03/014/4270','SAF/03/014/4317','SAF/03/004/12016','SAF/03/026/2343','SAF/03/014/4114','SAF/03/014/3984','SAF/03/028/2624','SAF/03/002/11603','SAF/03/004/12020','SAF/03/024/01058','SAF/03/024/3219','SAF/03/017/2374','SAF/03/015/00996','SAF/03/014/3946','SAF/03/021/5173','SAF/03/017/00751','SAF/03/015/3758','SAF/03/002/11115','SAF/03/015/1266','SAF/03/014/01036','SAF/03/014/3967','SAF/03/021/4932','SAF/03/008/9234','SAF/03/008/9214','SAF/03/018/1582','SAF/03/012/00453','SAF/03/005/6284','SAF/03/018/1681','SAF/03/014/2155','SAF/03/014/1576','SAF/03/031/00848','SAF/03/032/01116','SAF/03/031/00847','SAF/03/031/00846','SAF/03/015/00997','SAF/03/016/00355','SAF/03/014/01034','SAF/03/014/01033','SAF/03/009/2944','SAF/03/048/00519','SAF/03/024/01046','SAF/03/024/01052','SAF/03/024/01045','SAF/03/005/00281','SAF/03/005/00273','SAF/03/006/4054','SAF/03/024/01059','SAF/03/014/01044','SAF/03/011/00427','SAF/03/016/1792','SAF/03/017/00761','SAF/03/012/446','SAF/03/010/1474','SAF/03/017/00762','SAF/03/024/01065','SAF/03/024/01064','SAF/03/014/01026','SAF/03/011/00422','SAF/03/030/4857','SAF/03/004/01323','SAF/03/030/1468','SAF/03/004/13337','SAF/03/018/00600','SAF/03/029/1118','SAF/03/002/01098','SAF/03/014/01041','SAF/03/025/452','SAF/03/012/00448','SAF/03/038/00656','SAF/03/004/01348','SAF/03/017/00757','SAF/03/017/00756','SAF/03/017/00758','SAF/03/014/01035','SAF/03/001/3841','SAF/03/001/3842','SAF/03/015/1671','SAF/03/014/01021','SAF/03/014/01043','SAF/03/015/1352','SAF/03/015/0790','SAF/03/016/1248','SAF/03/025/00457','SAF/03/014/01029','SAF/03/014/01028','SAF/03/014/01027','SAF/03/038/8178','SAF/03/017/00754','SAF/03/024/01062','SAF/03/012/447','SAF/03/017/00760','SAF/03/048/5240','SAF/03/017/00759','SAF/03/012/2982','SAF/03/014/3530','SAF/03/015/1267','SAF/03/011/3261','SAF/03/001/00519','SAF/03/029/3377','SAF/03/027/01126','SAF/03/012/2907','SAF/03/034/4621','SAF/03/024/3465','SAF/03/021/5636','SAF/03/021/1009','SAF/03/035/0088','SAF/03/035/0087','SAF/03/021/5565','SAF/03/021/5575','SAF/03/021/5563','SAF/03/021/5587','SAF/03/021/1759','SAF/03/031/5906','SAF/03/035/4732','SAF/03/030/4907','SAF/03/030/2760','SAF/03/028/2578','SAF/03/029/6621','SAF/03/024/3489','SAF/03/015/3364','SAF/03/026/2324','SAF/03/021/5445','SAF/03/026/2317','SAF/03/022/4343','SAF/03/022/4381','SAF/03/022/4380','SAF/03/021/4448','SAF/03/014/2479','SAF/03/026/2115','SAF/03/022/4378','SAF/03/022/4383','SAF/03/031/5789','SAF/03/026/2313','SAF/03/022/4349','SAF/03/026/2306','SAF/03/022/4398','SAF/03/019/5314','SAF/03/027/3076','SAF/03/029/6823','SAF/03/021/5539','SAF/03/021/5540','SAF/03/015/3358','SAF/03/031/3683','SAF/03/026/2297','SAF/03/022/4375','SAF/03/027/4108','SAF/03/032/3585','SAF/03/031/5887','SAF/03/025/3401','SAF/03/026/1621','SAF/03/015/3277','SAF/03/015/3276','SAF/03/021/5390','SAF/03/021/5389','SAF/03/021/5429','SAF/03/015/2142','SAF/03/032/3601','SAF/03/015/3242','SAF/03/008/10061','SAF/03/008/10090','SAF/03/015/3230','SAF/03/017/2373','SAF/03/005/6096','SAF/03/005/6100','SAF/03/005/6101','SAF/03/021/5446','SAF/03/021/5417','SAF/03/021/5499','SAF/03/037/12373','SAF/03/011/4395','SAF/03/017/2377','SAF/03/017/2390','SAF/03/038/9430','SAF/03/024/3188','SAF/03/024/3391','SAF/03/004/13011','SAF/03/004/13012','SAF/03/014/4160','SAF/03/007/8110','SAF/03/014/4178','SAF/03/024/3371','SAF/03/005/6059','SAF/03/031/5830','SAF/03/004/12997','SAF/03/014/4235','SAF/03/025/3480','SAF/03/025/3481','SAF/03/025/3482','SAF/03/015/2342','SAF/03/034/4820','SAF/03/015/3251','SAF/03/015/3312','SAF/03/015/3311','SAF/03/015/3305','SAF/03/008/9359','SAF/03/018/1709','SAF/03/018/1711','SAF/03/018/1710','SAF/03/032/3571','SAF/03/024/3385','SAF/03/035/4625','SAF/03/032/3622','SAF/03/026/2191','SAF/03/030/4539','SAF/03/008/9907','SAF/03/014/4090','SAF/03/021/5502','SAF/03/021/5501','SAF/03/022/4267','SAF/03/027/3570','SAF/03/027/3569','SAF/03/027/3572','SAF/03/027/3571','SAF/03/027/3568','SAF/03/027/3567','SAF/03/027/3558','SAF/03/027/3523','SAF/03/027/3513','SAF/03/027/3634','SAF/03/027/3593','SAF/03/027/3583','SAF/03/027/3576','SAF/03/027/3575','SAF/03/027/3574','SAF/03/027/3573','SAF/03/022/4300','SAF/03/022/4338','SAF/03/027/3489','SAF/03/027/3457','SAF/03/027/3446','SAF/03/027/3444','SAF/03/027/3425','SAF/03/027/3418','SAF/03/027/3416','SAF/03/027/3394','SAF/03/027/3354','SAF/03/027/3360','SAF/03/027/3361','SAF/03/027/3322','SAF/03/027/3321','SAF/03/027/1909','SAF/03/013/2147','SAF/03/026/2289','SAF/03/026/2290','SAF/03/008/9583','SAF/03/026/2293','SAF/03/026/2287','SAF/03/005/6043','SAF/03/005/6062','SAF/03/014/4216','SAF/03/015/3231','SAF/03/011/4393','SAF/03/005/6060','SAF/03/026/2294','SAF/03/034/4622','SAF/03/008/9975','SAF/03/033/4656','SAF/03/033/4657','SAF/03/033/4658','SAF/03/022/4334','SAF/03/022/4331','SAF/03/024/3390','SAF/03/024/3388','SAF/03/031/4364','SAF/03/023/3261','SAF/03/014/4151','SAF/03/014/4152','SAF/03/027/4306','SAF/03/027/3274','SAF/03/027/3279','SAF/03/027/3316','SAF/03/027/3301','SAF/03/027/3166','SAF/03/027/3285','SAF/03/027/3284','SAF/03/027/3282','SAF/03/027/3235','SAF/03/027/3234','SAF/03/027/3226','SAF/03/027/3192','SAF/03/027/3159','SAF/03/027/3181','SAF/03/027/3156','SAF/03/027/3149','SAF/03/027/3121','SAF/03/027/3094','SAF/03/027/2475','SAF/03/027/3078','SAF/03/027/3079','SAF/03/027/2890','SAF/03/027/2872','SAF/03/027/2878','SAF/03/027/2197','SAF/03/027/2476','SAF/03/027/2466','SAF/03/027/2555','SAF/03/027/2369','SAF/03/027/2836','SAF/03/027/2828','SAF/03/027/1063','SAF/03/027/2414','SAF/03/027/2736','SAF/03/027/2773','SAF/03/027/1708','SAF/03/027/2444','SAF/03/027/2280','SAF/03/027/2795','SAF/03/027/2775','SAF/03/027/2443','SAF/03/027/2260','SAF/03/027/2362','SAF/03/027/1140','SAF/03/027/2349','SAF/03/027/2693','SAF/03/027/2680','SAF/03/027/2634','SAF/03/015/3224','SAF/03/015/1038','SAF/03/015/0506','SAF/03/015/1419','SAF/03/008/9969','SAF/03/017/2368','SAF/03/005/5963','SAF/03/012/2932','SAF/03/029/6632','SAF/03/002/11122','SAF/03/029/6602','SAF/03/034/4711','SAF/03/034/4645','SAF/03/015/3219','SAF/03/012/2854','SAF/03/036/5486','SAF/03/037/12775','SAF/03/018/1661','SAF/03/014/4170','SAF/03/014/4171','SAF/03/004/12922','SAF/03/016/1754','SAF/03/013/4367','SAF/03/027/1656','SAF/03/027/2756','SAF/03/027/2762','SAF/03/027/2760','SAF/03/027/2721','SAF/03/027/2745','SAF/03/022/4198','SAF/03/022/4330','SAF/03/021/5468','SAF/03/012/2899','SAF/03/038/8457','SAF/03/008/9941','SAF/03/030/4614','SAF/03/030/4556','SAF/03/021/5153','SAF/03/055/4658','SAF/03/007/7964','SAF/03/030/4604','SAF/03/030/4591','SAF/03/024/3386','SAF/03/004/12804','SAF/03/005/5985','SAF/03/014/4186','SAF/03/014/4200','SAF/03/014/4206','SAF/03/030/4605','SAF/03/030/4629','SAF/03/030/4678','SAF/03/034/4667','SAF/03/036/5428','SAF/03/036/5481','SAF/03/037/12760','SAF/03/053/2336','SAF/03/038/8436','SAF/03/008/9908','SAF/03/017/2358','SAF/03/024/3396','SAF/03/022/4266','SAF/03/022/4235','SAF/03/038/9352','SAF/03/033/4642','SAF/03/026/2272','SAF/03/037/12248','SAF/03/024/3354','SAF/03/022/4302','SAF/03/022/4262','SAF/03/023/3243','SAF/03/034/4607','SAF/03/022/4242','SAF/03/022/4260','SAF/03/008/9909','SAF/03/008/9911','SAF/03/008/9912','SAF/03/038/8532','SAF/03/038/8522','SAF/03/038/9271','SAF/03/004/12858','SAF/03/008/9967','SAF/03/037/12595','SAF/03/008/9968','SAF/03/037/12594','SAF/03/010/4259','SAF/03/037/12552','SAF/03/004/12817','SAF/03/010/4318','SAF/03/004/12856','SAF/03/030/0714','SAF/03/022/4284','SAF/03/022/4320','SAF/03/017/1250','SAF/03/017/1247','SAF/03/017/1238','SAF/03/008/9910','SAF/03/030/4780','SAF/03/030/4805','SAF/03/033/4571','SAF/03/030/4756','SAF/03/022/3855','SAF/03/017/2342','SAF/03/034/4850','SAF/03/017/2346','SAF/03/034/4861','SAF/03/008/9949','SAF/03/008/9948','SAF/03/037/12615','SAF/03/037/12724','SAF/03/037/12265','SAF/03/037/12264','SAF/03/037/12239','SAF/03/037/12246','SAF/03/017/2352','SAF/03/038/9094','SAF/03/038/9159','SAF/03/037/12672','SAF/03/035/4582','SAF/03/015/0785','SAF/03/005/5997','SAF/03/004/12819','SAF/03/004/12818','SAF/03/015/3317','SAF/03/036/5447','SAF/03/011/2627','SAF/03/017/2365','SAF/03/017/2363','SAF/03/035/4600','SAF/03/008/9920','SAF/03/026/2258','SAF/03/002/11126','SAF/03/002/11124','SAF/03/027/4030','SAF/03/022/4012','SAF/03/017/2372','SAF/03/017/2355','SAF/03/011/4305','SAF/03/014/4177','SAF/03/012/2889','SAF/03/017/0762','SAF/03/017/2267','SAF/03/017/2315','SAF/03/012/2728','SAF/03/012/2897','SAF/03/017/2319','SAF/03/017/2304','SAF/03/017/2297','SAF/03/017/2298','SAF/03/017/2295','SAF/03/010/4266','SAF/03/025/2643','SAF/03/028/2594','SAF/03/038/9233','SAF/03/028/2597','SAF/03/031/4304','SAF/03/008/9813','SAF/03/011/1906','SAF/03/011/3050','SAF/03/008/1866','SAF/03/028/2542','SAF/03/031/2743','SAF/03/003/4700','SAF/03/024/3359','SAF/03/021/2227','SAF/03/011/4261','SAF/03/031/5774','SAF/03/022/4250','SAF/03/014/4095','SAF/03/021/2277','SAF/03/008/9723','SAF/03/014/3869','SAF/03/005/5871','SAF/03/005/5892','SAF/03/008/9776','SAF/03/023/3214','SAF/03/002/11023','SAF/03/008/9757','SAF/03/035/4513','SAF/03/032/1969','SAF/03/032/2266','SAF/03/032/3451','SAF/03/032/0335','SAF/03/004/12471','SAF/03/037/12143','SAF/03/037/12154','SAF/03/037/12155','SAF/03/013/4268','SAF/03/035/4502','SAF/03/035/4514','SAF/03/032/1347','SAF/03/032/1346','SAF/03/004/12494','SAF/03/032/3548','SAF/03/032/3549','SAF/03/031/3048','SAF/03/037/12074','SAF/03/031/5707','SAF/03/030/4653','SAF/03/037/11702','SAF/03/021/2662','SAF/03/015/1071','SAF/03/029/6370','SAF/03/014/3940','SAF/03/014/3722','SAF/03/014/2626','SAF/03/014/2707','SAF/03/012/2731','SAF/03/014/3690','SAF/03/032/2436','SAF/03/022/4215','SAF/03/033/4481','SAF/03/033/4524','SAF/03/008/9750','SAF/03/009/2997','SAF/03/008/9739','SAF/03/004/12558','SAF/03/037/12197','SAF/03/049/3251','SAF/03/015/1748','SAF/03/001/4027','SAF/03/008/9737','SAF/03/008/9734','SAF/03/015/3024','SAF/03/015/3023','SAF/03/015/3027','SAF/03/015/3025','SAF/03/005/5790','SAF/03/024/3326','SAF/03/032/3524','SAF/03/011/4074','SAF/03/032/3500','SAF/03/030/4747','SAF/03/032/3489','SAF/03/032/3486','SAF/03/032/3487','SAF/03/032/3488','SAF/03/032/3515','SAF/03/022/4114','SAF/03/015/2780','SAF/03/015/3120','SAF/03/015/1157','SAF/03/017/2261','SAF/03/015/2130','SAF/03/015/2996','SAF/03/005/5726','SAF/03/013/4173','SAF/03/013/3995','SAF/03/013/3996','SAF/03/013/3992','SAF/03/013/4214','SAF/03/034/4641','SAF/03/008/9736','SAF/03/008/9735','SAF/03/005/5796','SAF/03/005/5797','SAF/03/015/1394','SAF/03/023/3200','SAF/03/017/2310','SAF/03/031/5632','SAF/03/004/12313','SAF/03/015/0920','SAF/03/015/1868','SAF/03/015/3028','SAF/03/031/5691','SAF/03/031/5682','SAF/03/031/5683','SAF/03/031/2893','SAF/03/002/10464','SAF/03/030/4733','SAF/03/030/4734','SAF/03/030/4735','SAF/03/030/2670','SAF/03/029/6512','SAF/03/029/6520','SAF/03/037/11809','SAF/03/037/11657','SAF/03/037/11646','SAF/03/037/11879','SAF/03/022/4108','SAF/03/054/4127','SAF/03/005/5743','SAF/03/005/5719','SAF/03/002/10646','SAF/03/002/10474','SAF/03/030/4689','SAF/03/002/10686','SAF/03/037/11766','SAF/03/055/4529','SAF/03/030/1509','SAF/03/021/2187','SAF/03/037/11544','SAF/03/037/11439','SAF/03/037/11497','SAF/03/037/11778','SAF/03/037/11777','SAF/03/031/5681','SAF/03/030/1923','SAF/03/030/4708','SAF/03/037/11562','SAF/03/037/11660','SAF/03/037/11579','SAF/03/037/11895','SAF/03/037/11965','SAF/03/004/12346','SAF/03/030/4715','SAF/03/030/4693','SAF/03/030/4730','SAF/03/030/4731','SAF/03/037/11433','SAF/03/037/11633','SAF/03/037/11531','SAF/03/037/11487','SAF/03/037/11599','SAF/03/037/11434','SAF/03/003/4596','SAF/03/021/5214','SAF/03/021/5216','SAF/03/008/9670','SAF/03/008/9666','SAF/03/003/4595','SAF/03/003/4608','SAF/03/022/4193','SAF/03/003/4597','SAF/03/003/4617','SAF/03/003/4618','SAF/03/030/1707','SAF/03/004/12509','SAF/03/008/6270','SAF/03/004/11711','SAF/03/014/1936','SAF/03/030/4726','SAF/03/014/2183','SAF/03/012/2807','SAF/03/012/2806','SAF/03/012/2810','SAF/03/005/5787','SAF/03/030/4716','SAF/03/037/12006','SAF/03/037/12022','SAF/03/030/4707','SAF/03/004/12411','SAF/03/037/12026','SAF/03/037/12021','SAF/03/037/12103','SAF/03/028/2553','SAF/03/005/5707','SAF/03/021/2214','SAF/03/021/5040','SAF/03/017/2356','SAF/03/022/4073','SAF/03/022/4041','SAF/03/022/4042','SAF/03/022/4038','SAF/03/022/4040','SAF/03/022/4039','SAF/03/005/5469','SAF/03/005/5468','SAF/03/005/5467','SAF/03/005/5470','SAF/03/024/1096','SAF/03/024/3285','SAF/03/024/3185','SAF/03/021/5289','SAF/03/021/5280','SAF/03/021/0984','SAF/03/021/5283','SAF/03/012/2798','SAF/03/004/12180','SAF/03/017/2305','SAF/03/017/2348','SAF/03/018/1632','SAF/03/017/2335','SAF/03/022/4149','SAF/03/022/4055','SAF/03/022/4095','SAF/03/022/4154','SAF/03/030/4692','SAF/03/024/3056','SAF/03/028/2509','SAF/03/029/1784','SAF/03/021/2660','SAF/03/027/1338','SAF/03/021/2225','SAF/03/022/2425','SAF/03/017/2303','SAF/03/010/4037','SAF/03/010/4040','SAF/03/023/2972','SAF/03/024/1798','SAF/03/024/1104','SAF/03/026/1052','SAF/03/007/3861','SAF/03/024/0991','SAF/03/007/3372','SAF/03/007/4524','SAF/03/015/1109','SAF/03/006/4105','SAF/03/002/9597','SAF/03/001/0258','SAF/03/015/0765','SAF/03/024/0415','SAF/03/026/0357','SAF/03/036/3644','SAF/03/036/3645','SAF/03/035/3512','SAF/03/009/0418','SAF/03/008/9294','SAF/03/025/3367','SAF/03/049/1394','SAF/03/030/3709','SAF/03/033/4419','SAF/03/023/2888','SAF/03/009/2904','SAF/03/034/4579','SAF/03/001/3745','SAF/03/008/9270','SAF/03/024/3148','SAF/03/014/3371','SAF/03/008/9251','SAF/03/013/4014','SAF/03/038/8227','SAF/03/034/4546','SAF/03/008/9243','SAF/03/014/3616','SAF/03/032/3453','SAF/03/028/2049','SAF/03/022/3865','SAF/03/030/4542','SAF/03/008/9191','SAF/03/008/9190','SAF/03/021/4994','SAF/03/008/9193','SAF/03/032/3446','SAF/03/032/3445','SAF/03/029/6314','SAF/03/029/6313','SAF/03/023/2931','SAF/03/013/3974','SAF/03/022/3945','SAF/03/022/3946','SAF/03/022/3947','SAF/03/021/4954','SAF/03/011/3396','SAF/03/017/2250','SAF/03/014/1906','SAF/03/011/3793','SAF/03/014/2642','SAF/03/014/2299','SAF/03/015/1830','SAF/03/012/1281','SAF/03/014/2357','SAF/03/001/1907','SAF/03/028/0711','SAF/03/028/0430','SAF/03/011/2764','SAF/03/011/2767','SAF/03/044/2655','SAF/03/008/9166','SAF/03/007/7205','SAF/03/033/2711','SAF/03/008/8850','SAF/03/008/9099','SAF/03/030/2306','SAF/03/036/4317','SAF/03/016/0906','SAF/03/036/1699','SAF/03/029/2261','SAF/03/029/6282','SAF/03/029/3737','SAF/03/029/1097','SAF/03/029/4130','SAF/03/030/4243','SAF/03/029/3287','SAF/03/032/3021','SAF/03/033/2026','SAF/03/037/11081','SAF/03/033/2420','SAF/03/023/1896','SAF/03/023/1825','SAF/03/038/3335','SAF/03/030/2047','SAF/03/030/0930','SAF/03/032/1421','SAF/03/029/6251','SAF/03/029/6252','SAF/03/021/4980','SAF/03/022/2517','SAF/03/022/3757','SAF/03/030/1846','SAF/03/037/10830','SAF/03/031/3882','SAF/03/022/2522','SAF/03/037/10808','SAF/03/033/1887','SAF/03/035/4046','SAF/03/016/1019','SAF/03/030/2442','SAF/03/038/7947','SAF/03/034/2328','SAF/03/038/7946','SAF/03/038/7945','SAF/03/010/2288','SAF/03/038/7944','SAF/03/038/7943','SAF/03/002/8263','SAF/03/038/7942','SAF/03/023/2442','SAF/03/008/9060','SAF/03/023/2718','SAF/03/010/3746','SAF/03/037/9403','SAF/03/037/9979','SAF/03/023/1730','SAF/03/008/5534','SAF/03/030/1034','SAF/03/022/3441','SAF/03/037/5415','SAF/03/016/0898','SAF/03/026/2026','SAF/03/014/1891','SAF/03/026/2024','SAF/03/026/2025','SAF/03/016/1254','SAF/03/035/3772','SAF/03/016/1156','SAF/03/016/1521','SAF/03/026/2042','SAF/03/006/3775','SAF/03/006/3754','SAF/03/026/2064','SAF/03/030/1218','SAF/03/006/3877','SAF/03/028/1015','SAF/03/028/2301','SAF/03/014/2165','SAF/03/011/2812','SAF/03/014/2362','SAF/03/014/2437','SAF/03/030/4287','SAF/03/029/4421','SAF/03/014/2493','SAF/03/012/2579','SAF/03/014/2110','SAF/03/021/4981','SAF/03/008/8815','SAF/03/008/1175','SAF/03/008/9165','SAF/03/014/2727','SAF/03/030/1730','SAF/03/010/3884','SAF/03/023/2902','SAF/03/023/1381','SAF/03/006/3747','SAF/03/035/2784','SAF/03/04A/0083','SAF/03/10A/0032','SAF/03/028/1148','SAF/03/016/1484','SAF/03/011/1567','SAF/03/025/1576','SAF/03/017/2255','SAF/03/004/10623','SAF/03/030/4306','SAF/03/014/3644','SAF/03/016/1580','SAF/03/016/0318','SAF/03/002/10278','SAF/03/008/7880','SAF/03/016/0823','SAF/03/016/0859','SAF/03/037/11392','SAF/03/034/4488','SAF/03/034/2736','SAF/03/028/1294','SAF/03/013/3789','SAF/03/030/4519','SAF/03/013/2761','SAF/03/028/2379','SAF/03/008/9081','SAF/03/022/3750','SAF/03/022/3719','SAF/03/023/2877','SAF/03/023/2876','SAF/03/038/7972','SAF/03/008/9109','SAF/03/022/2526','SAF/03/023/2784','SAF/03/030/4269','SAF/03/022/3864','SAF/03/016/1532','SAF/03/016/1530','SAF/03/014/0566','SAF/03/030/2638','SAF/03/008/5308','SAF/03/016/0868','SAF/03/028/2360','SAF/03/008/9104','SAF/03/030/2728','SAF/03/016/1039','SAF/03/030/4511','SAF/03/016/1136','SAF/03/038/7974','SAF/03/030/3000','SAF/03/016/0879','SAF/03/011/3915','SAF/03/029/4097','SAF/03/028/2270','SAF/03/035/4047','SAF/03/022/2651','SAF/03/016/1549','SAF/03/007/7219','SAF/03/006/3749','SAF/03/014/3935','SAF/03/023/1621','SAF/03/014/3529','SAF/03/023/2607','SAF/03/023/1684','SAF/03/008/9103','SAF/03/016/1592','SAF/03/016/1518','SAF/03/025/3286','SAF/03/025/3284','SAF/03/025/3282','SAF/03/025/3287','SAF/03/017/1365','SAF/03/028/2351','SAF/03/028/2203','SAF/03/016/1650','SAF/03/022/3775','SAF/03/022/3776','SAF/03/014/3444','SAF/03/014/3695','SAF/03/008/8929','SAF/03/008/8886','SAF/03/031/3087','SAF/03/032/2347','SAF/03/022/1834','SAF/03/033/2774','SAF/03/023/2882','SAF/03/023/1581','SAF/03/033/2854','SAF/03/006/3748','SAF/03/022/2718','SAF/03/031/3272','SAF/03/033/1941','SAF/03/002/9607','SAF/03/022/3787','SAF/03/023/2823','SAF/03/010/1793','SAF/03/031/3802','SAF/03/006/3756','SAF/03/002/9810','SAF/03/021/4882','SAF/03/010/1224','SAF/03/004/10537','SAF/03/030/4491','SAF/03/002/9971','SAF/03/032/1886','SAF/03/032/2349','SAF/03/032/2302','SAF/03/028/1337','SAF/03/028/1377','SAF/03/030/2997','SAF/03/026/0964','SAF/03/030/2996','SAF/03/010/3889','SAF/03/013/2133','SAF/03/008/8832','SAF/03/028/2290','SAF/03/032/1914','SAF/03/032/2199','SAF/03/010/3983','SAF/03/029/6158','SAF/03/008/8771','SAF/03/033/4326','SAF/03/026/1202','SAF/03/002/9872','SAF/03/026/0839','SAF/03/026/1264','SAF/03/032/2044','SAF/03/026/2164','SAF/03/008/8639','SAF/03/034/4413','SAF/03/031/5214','SAF/03/026/1240','SAF/03/032/2400','SAF/03/006/3968','SAF/03/006/3975','SAF/03/032/2380','SAF/03/032/2377','SAF/03/032/2442','SAF/03/004/10614','SAF/03/013/3747','SAF/03/032/2681','SAF/03/016/1473','SAF/03/021/4870','SAF/03/013/3796','SAF/03/017/0969','SAF/03/032/3163','SAF/03/016/1038','SAF/03/023/2894','SAF/03/008/8648','SAF/03/023/2929','SAF/03/002/8366','SAF/03/023/1642','SAF/03/013/3690','SAF/03/031/3999','SAF/03/026/0935','SAF/03/035/3859','SAF/03/026/2088','SAF/03/032/1942','SAF/03/013/2368','SAF/03/023/0703','SAF/03/033/4049','SAF/03/008/8646','SAF/03/029/6016','SAF/03/016/0749','SAF/03/016/0754','SAF/03/016/0752','SAF/03/038/7951','SAF/03/022/3938','SAF/03/017/0806','SAF/03/011/3917','SAF/03/011/2263','SAF/03/032/3413','SAF/03/032/2583','SAF/03/033/2515','SAF/03/037/11370','SAF/03/033/1834','SAF/03/022/1743','SAF/03/033/2539','SAF/03/032/2153','SAF/03/006/3746','SAF/03/033/1866','SAF/03/033/1867','SAF/03/016/1628','SAF/03/033/2722','SAF/03/032/2147','SAF/03/008/8769','SAF/03/016/1080','SAF/03/026/1072','SAF/03/016/1605','SAF/03/004/11246','SAF/03/015/3104','SAF/03/013/3943','SAF/03/008/8845','SAF/03/007/6850','SAF/03/008/8856','SAF/03/002/10094','SAF/03/017/2153','SAF/03/032/2106','SAF/03/032/1890','SAF/03/017/2227','SAF/03/017/2223','SAF/03/001/3626','SAF/03/026/1295','SAF/03/001/3608','SAF/03/017/1404','SAF/03/010/3846','SAF/03/026/2116','SAF/03/013/2395','SAF/03/036/2843','SAF/03/004/10760','SAF/03/026/0891','SAF/03/038/4638','SAF/03/036/4539','SAF/03/013/2064','SAF/03/031/5321','SAF/03/004/10885','SAF/03/013/3717','SAF/03/008/9122','SAF/03/028/2017','SAF/03/013/2036','SAF/03/008/9111','SAF/03/028/2266','SAF/03/030/4403','SAF/03/008/9148','SAF/03/004/10886','SAF/03/031/5600','SAF/03/031/3351','SAF/03/010/1939','SAF/03/013/3716','SAF/03/010/3873','SAF/03/006/3799','SAF/03/038/6633','SAF/03/038/7719','SAF/03/022/3893','SAF/03/026/2154','SAF/03/026/2100','SAF/03/011/3982','SAF/03/026/2104','SAF/03/033/3954','SAF/03/033/3735','SAF/03/008/8647','SAF/03/022/3926','SAF/03/004/10888','SAF/03/016/1647','SAF/03/004/11156','SAF/03/022/3933','SAF/03/036/4616','SAF/03/017/2163','SAF/03/010/3950','SAF/03/026/2113','SAF/03/004/10962','SAF/03/026/2128','SAF/03/004/11136','SAF/03/010/3964','SAF/03/010/3927','SAF/03/013/2411','SAF/03/032/2426','SAF/03/022/3951','SAF/03/008/9158','SAF/03/008/9159','SAF/03/017/2123','SAF/03/013/3633','SAF/03/013/3634','SAF/03/037/11379','SAF/03/006/2606','SAF/03/035/3977','SAF/03/011/3713','SAF/03/004/10979','SAF/03/037/11046','SAF/03/037/11047','SAF/03/037/11050','SAF/03/037/11048','SAF/03/037/11052','SAF/03/034/1416','SAF/03/037/6045','SAF/03/017/2216','SAF/03/017/1864','SAF/03/034/0473','SAF/03/002/6098','SAF/03/037/11165','SAF/03/017/0843','SAF/03/037/11163','SAF/03/011/4036','SAF/03/017/0842','SAF/03/035/3833','SAF/03/017/1545','SAF/03/002/10189','SAF/03/017/2242','SAF/03/002/10203','SAF/03/011/4017','SAF/03/017/1047','SAF/03/032/3410','SAF/03/032/2041','SAF/03/037/11233','SAF/03/017/1536','SAF/03/017/2232','SAF/03/009/2869','SAF/03/008/9118','SAF/03/007/7183','SAF/03/037/11257','SAF/03/037/11250','SAF/03/037/11321','SAF/03/017/2239','SAF/03/002/10211','SAF/03/002/10181','SAF/03/008/9125','SAF/03/032/3415','SAF/03/030/4482','SAF/03/004/10335','SAF/03/008/9090','SAF/03/035/4114','SAF/03/023/2927','SAF/03/019/5113','SAF/03/033/2243','SAF/03/014/3621','SAF/03/032/3428','SAF/03/032/1542','SAF/03/029/6289','SAF/03/029/6288','SAF/03/029/6287','SAF/03/037/11357','SAF/03/037/11292','SAF/03/037/11346','SAF/03/036/4819','SAF/03/023/1870','SAF/03/008/9124','SAF/03/009/2881','SAF/03/015/3097','SAF/03/017/2065','SAF/03/013/3917','SAF/03/017/2247','SAF/03/023/2908','SAF/03/049/3072','SAF/03/032/3414','SAF/03/031/5559','SAF/03/031/5557','SAF/03/037/11236','SAF/03/003/4339','SAF/03/036/4352','SAF/03/022/3868','SAF/03/023/2924','SAF/03/014/3930','SAF/03/014/3929','SAF/03/014/3924','SAF/03/014/3921','SAF/03/008/9132','SAF/03/034/4463','SAF/03/037/11351','SAF/03/037/11350','SAF/03/025/3308','SAF/03/022/3901','SAF/03/031/5480','SAF/03/023/2915','SAF/03/007/7171','SAF/03/033/4261','SAF/03/033/4260','SAF/03/006/4018','SAF/03/014/3914','SAF/03/017/2238','SAF/03/032/3429','SAF/03/034/4426','SAF/03/036/4826','SAF/03/013/3925','SAF/03/013/3923','SAF/03/030/3978','SAF/03/016/1641','SAF/03/016/1643','SAF/03/016/1642','SAF/03/021/4952','SAF/03/049/2616','SAF/03/011/4026','SAF/03/030/4489','SAF/03/010/3900','SAF/03/037/11280','SAF/03/015/3088','SAF/03/008/9066','SAF/03/028/2370','SAF/03/006/3996','SAF/03/014/3872','SAF/03/014/3871','SAF/03/008/9064','SAF/03/004/10489','SAF/03/012/2643','SAF/03/008/9054','SAF/03/008/9055','SAF/03/014/3875','SAF/03/004/11163','SAF/03/006/3946','SAF/03/008/8880','SAF/03/021/4907','SAF/03/021/4938','SAF/03/008/9083','SAF/03/014/3884','SAF/03/012/2641','SAF/03/008/9027','SAF/03/009/2868','SAF/03/006/3958','SAF/03/003/4283','SAF/03/008/9013','SAF/03/022/3867','SAF/03/031/5456','SAF/03/034/4406','SAF/03/034/4397','SAF/03/014/3832','SAF/03/008/9001','SAF/03/034/4395','SAF/03/023/2883','SAF/03/029/6194','SAF/03/034/4392','SAF/03/023/2878','SAF/03/030/4354','SAF/03/025/3103','SAF/03/015/3093','SAF/03/015/3092','SAF/03/038/7799','SAF/03/036/4364','SAF/03/008/0112','SAF/03/029/1245','SAF/03/049/2036','SAF/03/030/4355','SAF/03/030/4353','SAF/03/038/7973','SAF/03/030/1690','SAF/03/031/5268','SAF/03/031/5269','SAF/03/037/11076','SAF/03/023/2911','SAF/03/006/3891','SAF/03/030/2218','SAF/03/002/10038','SAF/03/010/3897','SAF/03/003/4238','SAF/03/009/2850','SAF/03/023/2832','SAF/03/003/4313','SAF/03/004/11199','SAF/03/004/11195','SAF/03/037/10098','SAF/03/029/6240','SAF/03/029/6149','SAF/03/008/8975','SAF/03/022/3824','SAF/03/008/8958','SAF/03/022/3825','SAF/03/008/8983','SAF/03/021/4425','SAF/03/036/4498','SAF/03/004/11056','SAF/03/008/8918','SAF/03/035/4000','SAF/03/023/2828','SAF/03/030/4429','SAF/03/014/3633','SAF/03/003/1241','SAF/03/022/3819','SAF/03/030/4298','SAF/03/013/3802','SAF/03/004/10983','SAF/03/010/3872','SAF/03/003/3879','SAF/03/020/3912','SAF/03/008/8852','SAF/03/035/4049','SAF/03/017/2208','SAF/03/002/9802','SAF/03/010/3852','SAF/03/007/6917','SAF/03/003/4231','SAF/03/010/3890','SAF/03/018/1544','SAF/03/009/2833','SAF/03/004/10991','SAF/03/008/8894','SAF/03/010/3888','SAF/03/008/9047','SAF/03/008/9046','SAF/03/008/8887','SAF/03/015/3050','SAF/03/008/8862','SAF/03/009/2831','SAF/03/018/1532','SAF/03/008/8859','SAF/03/008/8854','SAF/03/030/4043','SAF/03/008/8853','SAF/03/011/3909','SAF/03/008/8814','SAF/03/002/9848','SAF/03/008/8801','SAF/03/015/3041','SAF/03/04A/0086','SAF/03/002/9984','SAF/03/023/2692','SAF/03/043/5190','SAF/03/030/4124','SAF/03/017/2215','SAF/03/022/3656','SAF/03/021/4874','SAF/03/021/4881','SAF/03/021/4880','SAF/03/037/9844','SAF/03/016/0946','SAF/03/028/2319','SAF/03/001/3564','SAF/03/018/1530','SAF/03/018/1529','SAF/03/029/6135','SAF/03/023/2680','SAF/03/029/6136','SAF/03/017/2077','SAF/03/011/3863','SAF/03/010/3802','SAF/03/010/3830','SAF/03/017/2180','SAF/03/022/3616','SAF/03/003/4186','SAF/03/002/9880','SAF/03/002/9844','SAF/03/008/8833','SAF/03/010/3793','SAF/03/004/10729','SAF/03/006/3820','SAF/03/017/2080','SAF/03/004/10764','SAF/03/009/2769','SAF/03/013/3502','SAF/03/022/3573','SAF/03/023/0174','SAF/03/002/10002','SAF/03/036/4661','SAF/03/033/3604','SAF/03/032/2898','SAF/03/001/1901','SAF/03/008/8842','SAF/03/010/3805','SAF/03/037/11124','SAF/03/018/1523','SAF/03/008/8685','SAF/03/008/8681','SAF/03/001/3461','SAF/03/015/2873','SAF/03/028/2317','SAF/03/035/4048','SAF/03/006/3815','SAF/03/021/4502','SAF/03/023/2825','SAF/03/009/2814','SAF/03/009/2815','SAF/03/015/2742','SAF/03/015/3047','SAF/03/032/3287','SAF/03/035/3823','SAF/03/021/4429','SAF/03/029/5700','SAF/03/013/3683','SAF/03/036/4240','SAF/03/017/2196','SAF/03/014/3612','SAF/03/014/3613','SAF/03/037/11015','SAF/03/030/1931','SAF/03/030/4261','SAF/03/021/4354','SAF/03/022/3768','SAF/03/011/3874','SAF/03/002/9668','SAF/03/015/3015','SAF/03/029/6126','SAF/03/022/3818','SAF/03/001/3359','SAF/03/006/3769','SAF/03/032/3221','SAF/03/032/3235','SAF/03/006/3774','SAF/03/031/5308','SAF/03/031/5280','SAF/03/016/1604','SAF/03/032/3216','SAF/03/031/5252','SAF/03/030/4278','SAF/03/023/2839','SAF/03/008/8676','SAF/03/008/8679','SAF/03/008/8645','SAF/03/030/4037','SAF/03/003/4132','SAF/03/037/10989','SAF/03/030/4299','SAF/03/021/4353','SAF/03/008/8695','SAF/03/008/8688','SAF/03/023/2859','SAF/03/004/10720','SAF/03/008/8684','SAF/03/010/3797','SAF/03/009/2755','SAF/03/008/8683','SAF/03/004/10593','SAF/03/004/10589','SAF/03/004/10587','SAF/03/002/9671','SAF/03/021/4413','SAF/03/006/3773','SAF/03/008/8637','SAF/03/004/10566','SAF/03/034/4299','SAF/03/032/3191','SAF/03/031/5229','SAF/03/033/4050','SAF/03/006/3757','SAF/03/004/10562','SAF/03/008/8655','SAF/03/002/9635','SAF/03/008/8419','SAF/03/017/2043','SAF/03/016/1415','SAF/03/043/5030','SAF/03/002/9479','SAF/03/014/3626','SAF/03/030/4242','SAF/03/014/3759','SAF/03/023/2603','SAF/03/021/4442','SAF/03/031/3457','SAF/03/036/3208','SAF/03/008/8634','SAF/03/022/3634','SAF/03/029/5798','SAF/03/033/3794','SAF/03/032/2971','SAF/03/006/3517','SAF/03/036/3910','SAF/03/038/7267','SAF/03/022/3636','SAF/03/008/8632','SAF/03/022/3674','SAF/03/029/5772','SAF/03/008/8601','SAF/03/030/4219','SAF/03/023/2684','SAF/03/008/8238','SAF/03/018/1392','SAF/03/017/1917','SAF/03/010/3699','SAF/03/008/8187','SAF/03/033/4047','SAF/03/044/2598','SAF/03/038/6202','SAF/03/014/3652','SAF/03/018/0513','SAF/03/006/3711','SAF/03/018/1469','SAF/03/022/3767','SAF/03/023/2807','SAF/03/021/0849','SAF/03/021/3922','SAF/03/028/2278','SAF/03/030/3963','SAF/03/006/3736','SAF/03/006/3734','SAF/03/043/4910','SAF/03/018/0314','SAF/03/018/0447','SAF/03/014/1701','SAF/03/018/0453','SAF/03/028/2268','SAF/03/014/2399','SAF/03/033/1389','SAF/03/021/4547','SAF/03/006/3735','SAF/03/018/0195','SAF/03/006/3737','SAF/03/021/3946','SAF/03/021/1741','SAF/03/017/0388','SAF/03/028/1434','SAF/03/018/0792','SAF/03/008/8441','SAF/03/018/0567','SAF/03/021/4381','SAF/03/021/3844','SAF/03/030/3170','SAF/03/021/2973','SAF/03/030/4087','SAF/03/023/2794','SAF/03/028/0418','SAF/03/002/5195','SAF/03/028/0036','SAF/03/008/3974','SAF/03/008/2945','SAF/03/023/2785','SAF/03/031/2916','SAF/03/018/0571','SAF/03/030/1259','SAF/03/033/3974','SAF/03/035/3773','SAF/03/017/2101','SAF/03/018/1278','SAF/03/014/2275','SAF/03/008/4799','SAF/03/031/4223','SAF/03/017/2144','SAF/03/014/1751','SAF/03/035/3656','SAF/03/032/2360','SAF/03/031/3730','SAF/03/017/0929','SAF/03/014/2243','SAF/03/018/1433','SAF/03/017/2145','SAF/03/014/2522','SAF/03/032/3033','SAF/03/033/2790','SAF/03/014/1807','SAF/03/002/9572','SAF/03/017/1194','SAF/03/014/2296','SAF/03/018/0559','SAF/03/014/2295','SAF/03/021/2550','SAF/03/018/0656','SAF/03/034/3666','SAF/03/031/2574','SAF/03/030/2417','SAF/03/014/2748','SAF/03/008/8511','SAF/03/014/2382','SAF/03/017/2075','SAF/03/032/2532','SAF/03/023/1806','SAF/03/008/4510','SAF/03/006/1197','SAF/03/014/1390','SAF/03/014/2455','SAF/03/032/3177','SAF/03/021/2470','SAF/03/023/2079','SAF/03/017/1645','SAF/03/014/2388','SAF/03/014/2229','SAF/03/033/1388','SAF/03/014/3727','SAF/03/018/1503','SAF/03/028/1163','SAF/03/014/1834','SAF/03/028/0653','SAF/03/033/3848','SAF/03/033/3994','SAF/03/014/2500','SAF/03/033/3995','SAF/03/033/2143','SAF/03/017/1611','SAF/03/011/2650','SAF/03/028/1610','SAF/03/014/2177','SAF/03/014/2176','SAF/03/014/1832','SAF/03/028/0830','SAF/03/030/1937','SAF/03/014/2046','SAF/03/014/2305','SAF/03/032/2427','SAF/03/032/2381','SAF/03/035/3824','SAF/03/014/3525','SAF/03/014/2659','SAF/03/018/1507','SAF/03/021/4297','SAF/03/019/4846','SAF/03/014/2349','SAF/03/019/4847','SAF/03/036/1942','SAF/03/002/9589','SAF/03/014/1749','SAF/03/014/1802','SAF/03/014/2648','SAF/03/018/0970','SAF/03/030/2097','SAF/03/030/4132','SAF/03/017/1558','SAF/03/037/10763','SAF/03/021/2570','SAF/03/023/2754','SAF/03/023/2445','SAF/03/003/3995','SAF/03/030/4211','SAF/03/030/4205','SAF/03/008/4107','SAF/03/008/8497','SAF/03/004/10343','SAF/03/008/3250','SAF/03/008/2557','SAF/03/031/5222','SAF/03/023/2810','SAF/03/014/2105','SAF/03/014/2112','SAF/03/014/2074','SAF/03/014/2073','SAF/03/023/2822','SAF/03/013/3270','SAF/03/021/3998','SAF/03/014/2594','SAF/03/014/2587','SAF/03/028/0728','SAF/03/008/5677','SAF/03/008/8361','SAF/03/021/1787','SAF/03/013/3568','SAF/03/008/8513','SAF/03/022/3584','SAF/03/022/1489','SAF/03/030/2521','SAF/03/030/2890','SAF/03/010/1223','SAF/03/030/2291','SAF/03/010/1799','SAF/03/010/1373','SAF/03/030/2484','SAF/03/033/4037','SAF/03/017/2148','SAF/03/008/8612','SAF/03/029/5620','SAF/03/001/3335','SAF/03/021/4494','SAF/03/013/3618','SAF/03/031/5216','SAF/03/013/3592','SAF/03/009/2727','SAF/03/030/4109','SAF/03/001/3339','SAF/03/001/3340','SAF/03/008/8496','SAF/03/030/4194','SAF/03/015/2988','SAF/03/008/5088','SAF/03/003/4017','SAF/03/010/3747','SAF/03/008/8471','SAF/03/008/8591','SAF/03/012/1603','SAF/03/008/8306','SAF/03/022/3219','SAF/03/022/3552','SAF/03/031/4682','SAF/03/028/1124','SAF/03/006/3692','SAF/03/030/4047','SAF/03/007/6797','SAF/03/037/10749','SAF/03/030/2778','SAF/03/028/1977','SAF/03/032/2264','SAF/03/017/2084','SAF/03/012/1779','SAF/03/021/4549','SAF/03/012/1787','SAF/03/018/0592','SAF/03/018/1363','SAF/03/018/0457','SAF/03/030/2634','SAF/03/002/2607','SAF/03/010/2220','SAF/03/031/3615','SAF/03/012/1416','SAF/03/002/4818','SAF/03/031/3637','SAF/03/012/1442','SAF/03/010/3663','SAF/03/017/2078','SAF/03/032/3151','SAF/03/032/1603','SAF/03/012/1861','SAF/03/012/1059','SAF/03/012/1893','SAF/03/008/5555','SAF/03/008/5554','SAF/03/008/2316','SAF/03/008/4723','SAF/03/002/9445','SAF/03/008/4840','SAF/03/017/0504','SAF/03/017/1110','SAF/03/010/1378','SAF/03/010/1294','SAF/03/028/0613','SAF/03/017/1987','SAF/03/018/0479','SAF/03/007/6792','SAF/03/018/0507','SAF/03/012/1897','SAF/03/012/2534','SAF/03/018/0771','SAF/03/018/0966','SAF/03/002/1250','SAF/03/010/3722','SAF/03/031/2662','SAF/03/002/5869','SAF/03/002/9544','SAF/03/008/5725','SAF/03/012/2441','SAF/03/012/2440','SAF/03/018/0297','SAF/03/012/2442','SAF/03/043/4882','SAF/03/012/2439','SAF/03/013/2445','SAF/03/013/2103','SAF/03/017/0596','SAF/03/017/0928','SAF/03/037/10704','SAF/03/008/8384','SAF/03/003/1230','SAF/03/008/8333','SAF/03/028/1338','SAF/03/028/1374','SAF/03/030/4178','SAF/03/013/3161','SAF/03/013/2541','SAF/03/013/2586','SAF/03/013/2373','SAF/03/012/1377','SAF/03/012/1690','SAF/03/022/0876','SAF/03/012/1846','SAF/03/022/2170','SAF/03/012/1703','SAF/03/022/3726','SAF/03/004/10339','SAF/03/012/2535','SAF/03/030/2871','SAF/03/008/8588','SAF/03/030/2722','SAF/03/031/3772','SAF/03/030/3924','SAF/03/012/2461','SAF/03/012/1253','SAF/03/012/0208','SAF/03/030/3859','SAF/03/029/5935','SAF/03/012/1882','SAF/03/022/3668','SAF/03/013/3382','SAF/03/013/2612','SAF/03/013/2521','SAF/03/013/2454','SAF/03/013/2484','SAF/03/038/4568','SAF/03/022/3704','SAF/03/022/3691','SAF/03/043/4810','SAF/03/031/3000','SAF/03/013/2299','SAF/03/012/1633','SAF/03/013/2550','SAF/03/013/2548','SAF/03/013/2549','SAF/03/031/3836','SAF/03/013/2301','SAF/03/008/7963','SAF/03/007/2616','SAF/03/013/2776','SAF/03/038/5278','SAF/03/008/8100','SAF/03/022/1909','SAF/03/038/4359','SAF/03/038/7159','SAF/03/038/3882','SAF/03/033/2448','SAF/03/031/3516','SAF/03/033/3424','SAF/03/032/2928','SAF/03/032/3055','SAF/03/032/2100','SAF/03/002/9517','SAF/03/002/8817','SAF/03/002/9138','SAF/03/002/9450','SAF/03/022/2400','SAF/03/030/3757','SAF/03/030/2775','SAF/03/022/2488','SAF/03/032/2946','SAF/03/032/3090','SAF/03/032/3158','SAF/03/032/2561','SAF/03/007/3003','SAF/03/033/3744','SAF/03/031/3191','SAF/03/008/8444','SAF/03/008/8322','SAF/03/031/3757','SAF/03/031/2600','SAF/03/031/3256','SAF/03/031/4330','SAF/03/030/1870','SAF/03/022/3733','SAF/03/030/4001','SAF/03/026/0957','SAF/03/030/2420','SAF/03/030/3893','SAF/03/030/2037','SAF/03/030/2080','SAF/03/008/2955','SAF/03/008/5709','SAF/03/030/2352','SAF/03/037/10625','SAF/03/030/2056','SAF/03/034/2587','SAF/03/030/1445','SAF/03/003/3919','SAF/03/003/1399','SAF/03/031/3491','SAF/03/026/2037','SAF/03/026/1398','SAF/03/026/1449','SAF/03/029/2395','SAF/03/031/3867','SAF/03/029/4221','SAF/03/015/2917','SAF/03/008/8334','SAF/03/013/2469','SAF/03/026/0805','SAF/03/008/2351','SAF/03/031/3824','SAF/03/008/8365','SAF/03/014/2627','SAF/03/031/3872','SAF/03/008/8583','SAF/03/029/5261','SAF/03/017/2021','SAF/03/037/6974','SAF/03/029/5970','SAF/03/008/8167','SAF/03/008/5147','SAF/03/014/1943','SAF/03/002/9516','SAF/03/014/2226','SAF/03/014/1837','SAF/03/030/1139','SAF/03/014/2526','SAF/03/014/2590','SAF/03/013/2472','SAF/03/013/2473','SAF/03/035/0405','SAF/03/013/3240','SAF/03/013/3560','SAF/03/014/2595','SAF/03/013/2132','SAF/03/031/3044','SAF/03/010/2536','SAF/03/008/8569','SAF/03/026/1998','SAF/03/007/6784','SAF/03/003/1973','SAF/03/031/2930','SAF/03/031/4752','SAF/03/029/3121','SAF/03/031/5186','SAF/03/031/5210','SAF/03/008/8473','SAF/03/026/1151','SAF/03/032/1931','SAF/03/032/1743','SAF/03/032/2118','SAF/03/015/2779','SAF/03/032/3139','SAF/03/014/2527','SAF/03/014/2592','SAF/03/014/2274','SAF/03/014/3685','SAF/03/014/2025','SAF/03/026/1879','SAF/03/026/1397','SAF/03/037/10743','SAF/03/037/10744','SAF/03/029/5993','SAF/03/013/2466','SAF/03/013/2456','SAF/03/013/3595','SAF/03/014/2093','SAF/03/014/2324','SAF/03/030/3949','SAF/03/003/3877','SAF/03/003/3837','SAF/03/022/3665','SAF/03/008/8364','SAF/03/008/8366','SAF/03/031/4833','SAF/03/031/4166','SAF/03/026/0468','SAF/03/026/1331','SAF/03/003/0747','SAF/03/008/8367','SAF/03/026/1797','SAF/03/026/1380','SAF/03/026/1379','SAF/03/031/4918','SAF/03/036/1631','SAF/03/026/0830','SAF/03/001/3227','SAF/03/029/5887','SAF/03/030/1626','SAF/03/035/3551','SAF/03/035/3549','SAF/03/026/1237','SAF/03/014/1327','SAF/03/014/2620','SAF/03/014/2211','SAF/03/014/2250','SAF/03/013/2535','SAF/03/013/2332','SAF/03/008/4041','SAF/03/030/0768','SAF/03/030/2202','SAF/03/010/3087','SAF/03/034/2371','SAF/03/006/3699','SAF/03/003/1110','SAF/03/026/0903','SAF/03/023/2793','SAF/03/030/3115','SAF/03/036/4133','SAF/03/014/2101','SAF/03/014/2064','SAF/03/017/2057','SAF/03/013/3370','SAF/03/030/1427','SAF/03/017/2102','SAF/03/002/8931','SAF/03/031/3215','SAF/03/030/1181','SAF/03/014/2403','SAF/03/037/10613','SAF/03/017/0651','SAF/03/002/5765','SAF/03/030/3697','SAF/03/013/3574','SAF/03/015/2953','SAF/03/008/8534','SAF/03/003/3956','SAF/03/031/5098','SAF/03/003/3959','SAF/03/030/4184','SAF/03/017/0729','SAF/03/017/0728','SAF/03/002/9150','SAF/03/009/2677','SAF/03/009/2725','SAF/03/026/2007','SAF/03/023/2681','SAF/03/026/1259','SAF/03/032/2379','SAF/03/010/2252','SAF/03/023/2804','SAF/03/031/3738','SAF/03/026/0328','SAF/03/025/3252','SAF/03/031/4139','SAF/03/037/5102','SAF/03/014/2540','SAF/03/031/3445','SAF/03/014/2543','SAF/03/014/2541','SAF/03/010/3705','SAF/03/010/3704','SAF/03/031/3443','SAF/03/010/3706','SAF/03/031/2631','SAF/03/031/3535','SAF/03/026/1508','SAF/03/031/2115','SAF/03/026/1307','SAF/03/017/1644','SAF/03/017/1557','SAF/03/014/1449','SAF/03/023/2792','SAF/03/026/0946','SAF/03/014/2577','SAF/03/023/2796','SAF/03/017/1312','SAF/03/013/3586','SAF/03/023/1123','SAF/03/026/1473','SAF/03/031/3774','SAF/03/013/2428','SAF/03/017/0774','SAF/03/017/1423','SAF/03/014/2239','SAF/03/017/0835','SAF/03/037/10739','SAF/03/017/0594','SAF/03/017/0621','SAF/03/010/3395','SAF/03/010/1755','SAF/03/032/2137','SAF/03/003/3970','SAF/03/031/3337','SAF/03/031/2606','SAF/03/017/0375','SAF/03/013/2669','SAF/03/037/10104','SAF/03/014/2186','SAF/03/031/1914','SAF/03/014/2185','SAF/03/031/3214','SAF/03/013/2030','SAF/03/017/1381','SAF/03/026/2009','SAF/03/014/1999','SAF/03/031/2857','SAF/03/026/2006','SAF/03/014/2002','SAF/03/014/2000','SAF/03/014/1284','SAF/03/013/3397','SAF/03/031/2909','SAF/03/017/1515','SAF/03/026/1481','SAF/03/017/0959','SAF/03/026/2008','SAF/03/026/1356','SAF/03/026/1982','SAF/03/026/1450','SAF/03/003/3955','SAF/03/022/3731','SAF/03/026/1471','SAF/03/031/5094','SAF/03/026/1799','SAF/03/033/3957','SAF/03/022/3713','SAF/03/033/3956','SAF/03/026/1793','SAF/03/026/1809','SAF/03/023/2767','SAF/03/013/1733','SAF/03/022/3730','SAF/03/023/2768','SAF/03/026/2002','SAF/03/026/1775','SAF/03/014/2141','SAF/03/014/3703','SAF/03/034/3293','SAF/03/022/3594','SAF/03/031/4862','SAF/03/026/1535','SAF/03/026/1534','SAF/03/031/4225','SAF/03/008/5415','SAF/03/031/4035','SAF/03/022/3495','SAF/03/022/3493','SAF/03/022/3498','SAF/03/031/4991','SAF/03/031/5009','SAF/03/014/2804','SAF/03/029/0362','SAF/03/026/0889','SAF/03/026/0893','SAF/03/026/2010','SAF/03/036/3841','SAF/03/029/5434','SAF/03/013/2524','SAF/03/013/2398','SAF/03/014/3708','SAF/03/032/2282','SAF/03/032/1264','SAF/03/013/3539','SAF/03/013/3540','SAF/03/013/3538','SAF/03/017/1821','SAF/03/030/2592','SAF/03/030/2719','SAF/03/002/9521','SAF/03/017/1535','SAF/03/031/4114','SAF/03/013/2659','SAF/03/029/3057','SAF/03/012/2549','SAF/03/023/1539','SAF/03/023/1550','SAF/03/037/10146','SAF/03/032/1776','SAF/03/032/1775','SAF/03/034/4248','SAF/03/002/8573','SAF/03/017/0661','SAF/03/026/0735','SAF/03/017/0873','SAF/03/031/3679','SAF/03/017/1172','SAF/03/031/4583','SAF/03/026/1388','SAF/03/017/1598','SAF/03/017/1468','SAF/03/017/1469','SAF/03/017/1470','SAF/03/014/1198','SAF/03/017/2090','SAF/03/038/7205','SAF/03/031/3740','SAF/03/014/3709','SAF/03/034/4241','SAF/03/014/3710','SAF/03/034/4184','SAF/03/014/3723','SAF/03/030/2321','SAF/03/012/2566','SAF/03/037/10524','SAF/03/037/10620','SAF/03/037/10690','SAF/03/037/10688','SAF/03/013/3271','SAF/03/022/3681','SAF/03/009/2433','SAF/03/009/1943','SAF/03/013/2680','SAF/03/003/3985','SAF/03/013/2682','SAF/03/013/2634','SAF/03/010/3136','SAF/03/025/3250','SAF/03/008/8508','SAF/03/006/3595','SAF/03/038/7215','SAF/03/017/2017','SAF/03/015/2970','SAF/03/038/7202','SAF/03/015/2950','SAF/03/031/5158','SAF/03/031/5169','SAF/03/015/2931','SAF/03/033/4014','SAF/03/015/2901','SAF/03/037/10669','SAF/03/016/1566','SAF/03/031/5165','SAF/03/017/2014','SAF/03/033/3992','SAF/03/033/3991','SAF/03/037/10451','SAF/03/017/2087','SAF/03/023/2795','SAF/03/015/2871','SAF/03/017/2071','SAF/03/014/3637','SAF/03/017/2020','SAF/03/015/2918','SAF/03/031/5154','SAF/03/015/2912','SAF/03/031/5152','SAF/03/016/1557','SAF/03/015/2881','SAF/03/015/2893','SAF/03/015/2896','SAF/03/017/1928','SAF/03/015/2890','SAF/03/017/2085','SAF/03/018/1449','SAF/03/017/2079','SAF/03/017/2081','SAF/03/017/2070','SAF/03/017/2069','SAF/03/031/5129','SAF/03/031/5111','SAF/03/017/2064','SAF/03/017/2063','SAF/03/018/1446','SAF/03/031/5103','SAF/03/031/5101','SAF/03/018/1438','SAF/03/018/1440','SAF/03/015/2860','SAF/03/018/1439','SAF/03/015/2851','SAF/03/016/1531','SAF/03/017/2054','SAF/03/015/2804','SAF/03/015/2790','SAF/03/018/1409','SAF/03/015/2850','SAF/03/015/2821','SAF/03/017/2040','SAF/03/017/2042','SAF/03/015/2778','SAF/03/018/1394','SAF/03/032/3142','SAF/03/032/3122','SAF/03/017/2086','SAF/03/013/3475','SAF/03/023/2789','SAF/03/022/3685','SAF/03/036/4238','SAF/03/032/3143','SAF/03/022/3624','SAF/03/003/3976','SAF/03/033/3793','SAF/03/033/3941','SAF/03/032/2987','SAF/03/033/3887','SAF/03/022/3630','SAF/03/022/3578','SAF/03/032/3012','SAF/03/022/3515','SAF/03/033/3795','SAF/03/032/2993','SAF/03/032/3085','SAF/03/030/4174','SAF/03/030/4175','SAF/03/032/3125','SAF/03/037/10244','SAF/03/033/3912','SAF/03/033/3911','SAF/03/032/3019','SAF/03/022/3506','SAF/03/021/4424','SAF/03/021/4400','SAF/03/021/4401','SAF/03/032/3011','SAF/03/036/3703','SAF/03/008/8506','SAF/03/032/3049','SAF/03/028/2102','SAF/03/028/2104','SAF/03/028/2103','SAF/03/034/3872','SAF/03/032/3020','SAF/03/032/3098','SAF/03/032/3121','SAF/03/032/3127','SAF/03/032/3124','SAF/03/032/3126','SAF/03/030/4146','SAF/03/011/3772','SAF/03/010/3660','SAF/03/028/2071','SAF/03/011/3774','SAF/03/030/4010','SAF/03/030/4141','SAF/03/010/3571','SAF/03/023/2777','SAF/03/030/4118','SAF/03/014/3669','SAF/03/030/4073','SAF/03/030/4066','SAF/03/026/1967','SAF/03/030/4067','SAF/03/030/4065','SAF/03/030/4044','SAF/03/030/4042','SAF/03/030/4040','SAF/03/030/4038','SAF/03/031/5088','SAF/03/015/2805','SAF/03/016/1493','SAF/03/030/4039','SAF/03/017/2032','SAF/03/018/1407','SAF/03/015/2767','SAF/03/014/3680','SAF/03/002/9511','SAF/03/002/9510','SAF/03/003/3973','SAF/03/003/3974','SAF/03/003/3975','SAF/03/007/4627','SAF/03/015/2702','SAF/03/010/3479','SAF/03/026/1952','SAF/03/030/4033','SAF/03/033/3686','SAF/03/010/3617','SAF/03/022/3516','SAF/03/029/5939','SAF/03/023/2729','SAF/03/032/3042','SAF/03/030/4032','SAF/03/030/4028','SAF/03/009/2623','SAF/03/028/2048','SAF/03/030/4022','SAF/03/001/2983','SAF/03/012/2525','SAF/03/029/5925','SAF/03/023/2719','SAF/03/035/3733','SAF/03/037/10395','SAF/03/029/5462','SAF/03/003/3896','SAF/03/002/9373','SAF/03/023/2632','SAF/03/017/1994','SAF/03/018/1405','SAF/03/017/2037','SAF/03/017/2026','SAF/03/015/2777','SAF/03/028/2043','SAF/03/025/3173','SAF/03/029/5856','SAF/03/015/2770','SAF/03/029/5855','SAF/03/003/3684','SAF/03/015/2763','SAF/03/013/3519','SAF/03/015/2762','SAF/03/017/2009','SAF/03/002/9074','SAF/03/015/2730','SAF/03/018/1410','SAF/03/002/8939','SAF/03/003/3682','SAF/03/003/3680','SAF/03/003/3683','SAF/03/003/3681','SAF/03/003/3636','SAF/03/030/3997','SAF/03/002/9121','SAF/03/017/1989','SAF/03/029/5769','SAF/03/017/1979','SAF/03/017/1981','SAF/03/003/3864','SAF/03/031/4869','SAF/03/029/5651','SAF/03/029/5979','SAF/03/006/3665','SAF/03/001/3205','SAF/03/031/4867','SAF/03/006/3413','SAF/03/031/4875','SAF/03/031/5142','SAF/03/031/5143','SAF/03/029/5745','SAF/03/029/5725','SAF/03/014/3707','SAF/03/023/2637','SAF/03/023/2631','SAF/03/002/8650','SAF/03/031/4840','SAF/03/008/8049','SAF/03/015/2775','SAF/03/038/7024','SAF/03/038/7032','SAF/03/038/7035','SAF/03/038/7025','SAF/03/008/7966','SAF/03/031/5053','SAF/03/031/4802','SAF/03/016/1404','SAF/03/031/4818','SAF/03/017/1902','SAF/03/017/1892','SAF/03/031/4794','SAF/03/017/1891','SAF/03/008/7937','SAF/03/033/3968','SAF/03/014/3683','SAF/03/008/8091','SAF/03/017/0979','SAF/03/030/3927','SAF/03/030/3925','SAF/03/030/3928','SAF/03/030/3931','SAF/03/010/3400','SAF/03/010/3399','SAF/03/030/3902','SAF/03/008/7810','SAF/03/030/3892','SAF/03/010/3260','SAF/03/003/3886','SAF/03/003/3875','SAF/03/014/3676','SAF/03/021/4450','SAF/03/014/3634','SAF/03/031/4745','SAF/03/014/3571','SAF/03/021/4378','SAF/03/013/3462','SAF/03/021/4372','SAF/03/012/2452','SAF/03/021/4356','SAF/03/012/2446','SAF/03/012/2435','SAF/03/021/4306','SAF/03/013/3437','SAF/03/021/4355','SAF/03/021/4349','SAF/03/014/3518','SAF/03/021/4338','SAF/03/014/3512','SAF/03/014/3514','SAF/03/014/3511','SAF/03/012/2427','SAF/03/012/2418','SAF/03/014/3506','SAF/03/014/3507','SAF/03/014/3486','SAF/03/013/3413','SAF/03/014/3467','SAF/03/014/3468','SAF/03/014/3458','SAF/03/014/3456','SAF/03/014/3428','SAF/03/014/3381','SAF/03/012/2374','SAF/03/014/3377','SAF/03/014/3368','SAF/03/021/4068','SAF/03/021/4066','SAF/03/014/3235','SAF/03/013/3459','SAF/03/044/2581','SAF/03/017/0201','SAF/03/017/0202','SAF/03/017/0205','SAF/03/017/0199','SAF/03/017/0200','SAF/03/030/3845','SAF/03/008/0122','SAF/03/012/1198','SAF/03/015/1415','SAF/03/029/0507','SAF/03/015/2577','SAF/03/015/1894','SAF/03/032/1582','SAF/03/033/1126','SAF/03/031/3967','SAF/03/014/1572','SAF/03/031/2359','SAF/03/031/2850','SAF/03/032/2554','SAF/03/015/1657','SAF/03/033/2180','SAF/03/009/2579','SAF/03/033/2561','SAF/03/010/1472','SAF/03/002/6196','SAF/03/033/2442','SAF/03/034/1351','SAF/03/014/3032','SAF/03/010/3484','SAF/03/017/0192','SAF/03/032/3027','SAF/03/028/0847','SAF/03/033/2137','SAF/03/012/1286','SAF/03/036/3575','SAF/03/018/0863','SAF/03/033/1886','SAF/03/029/3644','SAF/03/012/1361','SAF/03/013/3175','SAF/03/026/1966','SAF/03/030/1425','SAF/03/026/1927','SAF/03/035/2914','SAF/03/032/1842','SAF/03/030/1423','SAF/03/012/2456','SAF/03/012/0694','SAF/03/012/1035','SAF/03/012/1041','SAF/03/006/3246','SAF/03/031/4989','SAF/03/032/1564','SAF/03/032/1563','SAF/03/013/3375','SAF/03/013/3372','SAF/03/003/3786','SAF/03/014/3471','SAF/03/021/4344','SAF/03/021/4343','SAF/03/015/2509','SAF/03/023/2566','SAF/03/038/3726','SAF/03/002/3364','SAF/03/017/2008','SAF/03/015/2286','SAF/03/002/5852','SAF/03/002/4417','SAF/03/014/2454','SAF/03/034/1662','SAF/03/015/1364','SAF/03/015/2462','SAF/03/010/3505','SAF/03/015/2050','SAF/03/010/3496','SAF/03/015/2693','SAF/03/002/8002','SAF/03/015/2758','SAF/03/037/10190','SAF/03/036/1509','SAF/03/033/2124','SAF/03/032/2781','SAF/03/038/6791','SAF/03/037/4742','SAF/03/038/4526','SAF/03/015/1921','SAF/03/036/3303','SAF/03/030/3594','SAF/03/030/3596','SAF/03/030/3595','SAF/03/030/1943','SAF/03/021/4312','SAF/03/002/4551','SAF/03/015/1117','SAF/03/032/1441','SAF/03/014/2023','SAF/03/034/2926','SAF/03/031/3497','SAF/03/014/1423','SAF/03/003/3774','SAF/03/031/4180','SAF/03/037/10108','SAF/03/036/2299','SAF/03/036/1318','SAF/03/018/0064','SAF/03/037/10179','SAF/03/010/2065','SAF/03/017/0859','SAF/03/006/0690','SAF/03/015/2684','SAF/03/035/2305','SAF/03/033/2097','SAF/03/033/2963','SAF/03/032/2984','SAF/03/035/2307','SAF/03/002/5484','SAF/03/002/8775','SAF/03/038/4588','SAF/03/008/1913','SAF/03/013/3332','SAF/03/013/3360','SAF/03/034/1829','SAF/03/033/3569','SAF/03/031/3879','SAF/03/008/7237','SAF/03/026/1845','SAF/03/002/5419','SAF/03/038/1152','SAF/03/035/3401','SAF/03/015/2703','SAF/03/014/1780','SAF/03/010/2509','SAF/03/014/2579','SAF/03/010/2538','SAF/03/008/6585','SAF/03/015/1600','SAF/03/015/2689','SAF/03/015/0963','SAF/03/016/1258','SAF/03/035/2663','SAF/03/015/1932','SAF/03/016/0654','SAF/03/035/3550','SAF/03/015/1475','SAF/03/015/1736','SAF/03/014/3482','SAF/03/015/1874','SAF/03/016/0779','SAF/03/018/1362','SAF/03/018/0727','SAF/03/018/0175','SAF/03/018/0169','SAF/03/008/5102','SAF/03/033/1749','SAF/03/031/3691','SAF/03/008/7957','SAF/03/036/3549','SAF/03/038/4912','SAF/03/035/2548','SAF/03/036/3551','SAF/03/008/3357','SAF/03/008/0580','SAF/03/037/6751','SAF/03/013/2593','SAF/03/032/2972','SAF/03/008/0883','SAF/03/013/2315','SAF/03/038/6692','SAF/03/010/1445','SAF/03/026/0872','SAF/03/001/3006','SAF/03/014/3122','SAF/03/008/3610','SAF/03/031/3454','SAF/03/032/2970','SAF/03/015/1953','SAF/03/038/1756','SAF/03/010/3038','SAF/03/038/6405','SAF/03/038/6404','SAF/03/038/6420','SAF/03/037/9996','SAF/03/008/8015','SAF/03/006/3485','SAF/03/037/10080','SAF/03/006/2670','SAF/03/037/5051','SAF/03/037/10100','SAF/03/035/3552','SAF/03/035/3491','SAF/03/031/3140','SAF/03/028/2015','SAF/03/006/2029','SAF/03/002/5295','SAF/03/008/6186','SAF/03/008/6182','SAF/03/014/2221','SAF/03/014/3350','SAF/03/008/8133','SAF/03/008/8134','SAF/03/006/3331','SAF/03/001/2850','SAF/03/006/3330','SAF/03/006/3329','SAF/03/008/7867','SAF/03/026/0815','SAF/03/008/6079','SAF/03/018/1350','SAF/03/038/6403','SAF/03/038/6442','SAF/03/018/1249','SAF/03/032/2374','SAF/03/015/0641','SAF/03/015/1636','SAF/03/033/3729','SAF/03/029/4233','SAF/03/018/1336','SAF/03/018/1358','SAF/03/015/1004','SAF/03/001/1230','SAF/03/017/1986','SAF/03/033/2799','SAF/03/018/1223','SAF/03/029/3044','SAF/03/015/1125','SAF/03/038/6406','SAF/03/016/1211','SAF/03/038/6444','SAF/03/032/2362','SAF/03/028/0886','SAF/03/012/1689','SAF/03/012/1692','SAF/03/014/1957','SAF/03/029/4612','SAF/03/012/2393','SAF/03/012/2394','SAF/03/029/4569','SAF/03/029/3440','SAF/03/023/2650','SAF/03/029/2785','SAF/03/023/1568','SAF/03/023/2322','SAF/03/022/2499','SAF/03/022/3450','SAF/03/030/1697','SAF/03/029/4057','SAF/03/029/3724','SAF/03/012/0894','SAF/03/033/3737','SAF/03/029/4653','SAF/03/017/1975','SAF/03/029/4606','SAF/03/029/4739','SAF/03/028/1336','SAF/03/028/1339','SAF/03/012/1754','SAF/03/023/1869','SAF/03/030/2411','SAF/03/030/3895','SAF/03/015/1376','SAF/03/015/2041','SAF/03/022/3105','SAF/03/028/0364','SAF/03/023/1937','SAF/03/023/2415','SAF/03/023/0627','SAF/03/002/8557','SAF/03/023/0711','SAF/03/028/0687','SAF/03/028/1468','SAF/03/010/2216','SAF/03/028/1057','SAF/03/028/0549','SAF/03/023/2556','SAF/03/028/0930','SAF/03/028/2022','SAF/03/006/3494','SAF/03/025/2327','SAF/03/001/2912','SAF/03/001/1833','SAF/03/001/0406','SAF/03/035/0969','SAF/03/014/2401','SAF/03/014/1850','SAF/03/10A/0043','SAF/03/002/8828','SAF/03/008/8085','SAF/03/012/1039','SAF/03/012/0994','SAF/03/008/7550','SAF/03/030/0825','SAF/03/008/7918','SAF/03/029/5616','SAF/03/030/2156','SAF/03/028/1989','SAF/03/029/2907','SAF/03/007/5235','SAF/03/029/2738','SAF/03/026/1887','SAF/03/034/1525','SAF/03/001/1727','SAF/03/023/1743','SAF/03/023/2583','SAF/03/023/2582','SAF/03/023/1929','SAF/03/023/1171','SAF/03/014/3174','SAF/03/014/2618','SAF/03/008/7881','SAF/03/026/1269','SAF/03/008/2114','SAF/03/026/1278','SAF/03/029/2515','SAF/03/029/3660','SAF/03/036/3771','SAF/03/029/3089','SAF/03/038/6482','SAF/03/014/0968','SAF/03/010/0949','SAF/03/014/0916','SAF/03/021/4152','SAF/03/029/1888','SAF/03/014/3033','SAF/03/028/1100','SAF/03/029/5690','SAF/03/014/2175','SAF/03/029/2535','SAF/03/008/7405','SAF/03/026/1924','SAF/03/029/3091','SAF/03/018/1345','SAF/03/037/9749','SAF/03/026/0781','SAF/03/018/1192','SAF/03/018/0635','SAF/03/026/0312','SAF/03/026/1085','SAF/03/029/3220','SAF/03/030/3719','SAF/03/030/1199','SAF/03/026/1549','SAF/03/026/1926','SAF/03/023/1741','SAF/03/023/2420','SAF/03/017/1050','SAF/03/029/4610','SAF/03/018/1339','SAF/03/026/1874','SAF/03/009/2563','SAF/03/026/1861','SAF/03/026/1093','SAF/03/011/3405','SAF/03/026/0995','SAF/03/026/1750','SAF/03/026/1904','SAF/03/029/5636','SAF/03/026/1884','SAF/03/008/8051','SAF/03/023/2604','SAF/03/029/5550','SAF/03/014/1603','SAF/03/028/0537','SAF/03/003/3692','SAF/03/029/4300','SAF/03/028/0750','SAF/03/029/2640','SAF/03/026/1902','SAF/03/003/3597','SAF/03/026/1373','SAF/03/030/2971','SAF/03/038/6473','SAF/03/030/1694','SAF/03/035/3585','SAF/03/035/3433','SAF/03/003/0861','SAF/03/009/1877','SAF/03/011/1263','SAF/03/031/3383','SAF/03/014/1946','SAF/03/018/1351','SAF/03/014/1940','SAF/03/018/0821','SAF/03/018/0871','SAF/03/030/2849','SAF/03/016/1439','SAF/03/018/0160','SAF/03/003/3637','SAF/03/016/1372','SAF/03/018/0397','SAF/03/018/1323','SAF/03/003/3596','SAF/03/003/3653','SAF/03/010/2983','SAF/03/023/1562','SAF/03/010/3086','SAF/03/017/1969','SAF/03/017/1984','SAF/03/014/0994','SAF/03/014/0989','SAF/03/017/1939','SAF/03/008/6184','SAF/03/008/4237','SAF/03/021/4268','SAF/03/015/2728','SAF/03/037/9928','SAF/03/014/3294','SAF/03/017/1550','SAF/03/033/3694','SAF/03/015/1465','SAF/03/015/1666','SAF/03/018/1357','SAF/03/018/0980','SAF/03/018/0979','SAF/03/016/1086','SAF/03/029/2517','SAF/03/033/2841','SAF/03/016/1198','SAF/03/026/1909','SAF/03/026/1051','SAF/03/006/3510','SAF/03/029/2744','SAF/03/029/2638','SAF/03/026/1053','SAF/03/029/2676','SAF/03/029/5609','SAF/03/010/3386','SAF/03/003/3667','SAF/03/003/3675','SAF/03/029/4192','SAF/03/003/2215','SAF/03/007/4825','SAF/03/028/1191','SAF/03/015/1508','SAF/03/015/2065','SAF/03/021/4233','SAF/03/001/1220','SAF/03/032/2925','SAF/03/015/1135','SAF/03/015/0937','SAF/03/006/3438','SAF/03/021/4146','SAF/03/021/4145','SAF/03/010/3378','SAF/03/014/1674','SAF/03/029/5597','SAF/03/006/1628','SAF/03/017/0203','SAF/03/009/2515','SAF/03/009/0823','SAF/03/032/2375','SAF/03/025/3129','SAF/03/014/1447','SAF/03/036/3683','SAF/03/038/6471','SAF/03/002/8898','SAF/03/032/2424','SAF/03/032/2425','SAF/03/002/5737','SAF/03/037/10107','SAF/03/023/2594','SAF/03/037/10152','SAF/03/002/0064','SAF/03/017/1543','SAF/03/008/8068','SAF/03/030/3119','SAF/03/002/8868','SAF/03/029/5623','SAF/03/037/6780','SAF/03/023/1589','SAF/03/009/1453','SAF/03/009/2411','SAF/03/017/1601','SAF/03/017/0645','SAF/03/031/2277','SAF/03/017/0127','SAF/03/002/5522','SAF/03/031/3241','SAF/03/031/3856','SAF/03/030/2981','SAF/03/011/3305','SAF/03/031/3298','SAF/03/002/8695','SAF/03/031/4646','SAF/03/002/8748','SAF/03/002/8630','SAF/03/033/3652','SAF/03/037/9877','SAF/03/030/3214','SAF/03/030/3215','SAF/03/037/9833','SAF/03/038/6558','SAF/03/037/9824','SAF/03/001/2802','SAF/03/037/9628','SAF/03/032/2895','SAF/03/022/3381','SAF/03/017/1961','SAF/03/017/1950','SAF/03/017/1964','SAF/03/017/1963','SAF/03/017/1951','SAF/03/021/4192','SAF/03/008/8007','SAF/03/030/3958','SAF/03/030/3959','SAF/03/031/3927','SAF/03/029/5482','SAF/03/029/5481','SAF/03/035/3554','SAF/03/026/1885','SAF/03/037/10085','SAF/03/029/5540','SAF/03/029/5619','SAF/03/021/4087','SAF/03/021/4086','SAF/03/021/4088','SAF/03/030/1385','SAF/03/023/2562','SAF/03/021/4130','SAF/03/028/1947','SAF/03/017/0898','SAF/03/010/1401','SAF/03/010/3307','SAF/03/010/3292','SAF/03/035/1562','SAF/03/017/1377','SAF/03/029/4015','SAF/03/029/3916','SAF/03/023/2557','SAF/03/023/2558','SAF/03/035/3482','SAF/03/018/1303','SAF/03/017/0638','SAF/03/010/3271','SAF/03/001/2874','SAF/03/030/1444','SAF/03/030/0113','SAF/03/030/2745','SAF/03/030/1333','SAF/03/035/3454','SAF/03/030/1580','SAF/03/017/1144','SAF/03/017/0973','SAF/03/017/1875','SAF/03/017/1883','SAF/03/031/4203','SAF/03/030/2074','SAF/03/031/3294','SAF/03/015/2608','SAF/03/031/4093','SAF/03/031/4545','SAF/03/037/9969','SAF/03/017/1945','SAF/03/017/0773','SAF/03/017/0532','SAF/03/025/2799','SAF/03/031/4147','SAF/03/030/1915','SAF/03/031/3419','SAF/03/031/2852','SAF/03/030/1651','SAF/03/036/3464','SAF/03/014/3367','SAF/03/013/3328','SAF/03/030/2354','SAF/03/032/2886','SAF/03/017/0291','SAF/03/017/0807','SAF/03/017/1941','SAF/03/026/1872','SAF/03/026/0825','SAF/03/017/1429','SAF/03/017/1920','SAF/03/014/1896','SAF/03/016/0765','SAF/03/017/1907','SAF/03/017/0970','SAF/03/015/0835','SAF/03/013/1779','SAF/03/017/0429','SAF/03/030/2905','SAF/03/030/2904','SAF/03/026/1875','SAF/03/021/4126','SAF/03/029/5607','SAF/03/017/0789','SAF/03/033/1733','SAF/03/021/3064','SAF/03/017/0296','SAF/03/017/1422','SAF/03/023/1591','SAF/03/030/2914','SAF/03/017/0643','SAF/03/017/0662','SAF/03/033/3636','SAF/03/033/3641','SAF/03/033/1320','SAF/03/017/1141','SAF/03/017/1142','SAF/03/038/6344','SAF/03/026/1026','SAF/03/026/1866','SAF/03/026/1025','SAF/03/017/0197','SAF/03/017/0259','SAF/03/010/1444','SAF/03/010/1348','SAF/03/023/2560','SAF/03/013/3324','SAF/03/002/8722','SAF/03/010/1505','SAF/03/029/5381','SAF/03/014/2744','SAF/03/021/4091','SAF/03/023/0965','SAF/03/031/2976','SAF/03/010/2310','SAF/03/035/0749','SAF/03/034/3360','SAF/03/031/3719','SAF/03/023/1720','SAF/03/023/1721','SAF/03/016/0549','SAF/03/023/2547','SAF/03/037/9822','SAF/03/008/7878','SAF/03/029/2620','SAF/03/029/5581','SAF/03/022/3325','SAF/03/032/2341','SAF/03/011/2638','SAF/03/010/1971','SAF/03/010/1973','SAF/03/033/3490','SAF/03/010/1553','SAF/03/014/2536','SAF/03/014/2547','SAF/03/016/1401','SAF/03/038/6314','SAF/03/008/2258','SAF/03/013/3319','SAF/03/033/3654','SAF/03/033/1729','SAF/03/033/1725','SAF/03/033/1726','SAF/03/010/1276','SAF/03/022/3331','SAF/03/029/0578','SAF/03/038/5969','SAF/03/010/3105','SAF/03/010/3106','SAF/03/026/1865','SAF/03/026/1245','SAF/03/035/1564','SAF/03/007/6143','SAF/03/031/3812','SAF/03/016/1377','SAF/03/017/0212','SAF/03/013/1519','SAF/03/017/0360','SAF/03/016/1386','SAF/03/029/4352','SAF/03/017/1272','SAF/03/022/3215','SAF/03/022/3375','SAF/03/010/3293','SAF/03/031/2131','SAF/03/026/1392','SAF/03/021/4113','SAF/03/032/0195','SAF/03/017/0991','SAF/03/008/7807','SAF/03/008/7808','SAF/03/017/0246','SAF/03/009/2430','SAF/03/007/6142','SAF/03/009/1425','SAF/03/033/3047','SAF/03/031/3218','SAF/03/008/7782','SAF/03/018/1314','SAF/03/026/1419','SAF/03/026/1420','SAF/03/025/2748','SAF/03/025/2749','SAF/03/018/1312','SAF/03/018/1313','SAF/03/028/0363','SAF/03/026/1771','SAF/03/026/1774','SAF/03/031/2874','SAF/03/038/6155','SAF/03/008/6524','SAF/03/008/5050','SAF/03/006/3408','SAF/03/034/2992','SAF/03/031/2794','SAF/03/018/1226','SAF/03/034/1837','SAF/03/016/1131','SAF/03/001/1470','SAF/03/003/2532','SAF/03/006/3236','SAF/03/015/2593','SAF/03/010/2494','SAF/03/010/2222','SAF/03/034/0978','SAF/03/002/4164','SAF/03/037/9636','SAF/03/034/2427','SAF/03/006/3177','SAF/03/033/1798','SAF/03/033/1801','SAF/03/033/2190','SAF/03/038/4694','SAF/03/011/2789','SAF/03/032/1562','SAF/03/033/1477','SAF/03/033/3503','SAF/03/018/0476','SAF/03/014/1633','SAF/03/031/3814','SAF/03/010/1001','SAF/03/010/1858','SAF/03/032/1436','SAF/03/034/2856','SAF/03/032/1589','SAF/03/006/1766','SAF/03/010/3137','SAF/03/032/1585','SAF/03/010/1446','SAF/03/032/1587','SAF/03/014/3183','SAF/03/032/2486','SAF/03/010/1110','SAF/03/032/2038','SAF/03/010/0191','SAF/03/002/8377','SAF/03/014/2404','SAF/03/015/2435','SAF/03/028/1922','SAF/03/031/1968','SAF/03/038/3656','SAF/03/034/1390','SAF/03/032/1754','SAF/03/015/1831','SAF/03/010/1639','SAF/03/010/1940','SAF/03/010/1447','SAF/03/010/1679','SAF/03/032/1813','SAF/03/033/3458','SAF/03/010/2604','SAF/03/010/2352','SAF/03/012/2081','SAF/03/033/2364','SAF/03/032/1584','SAF/03/033/2374','SAF/03/012/1982','SAF/03/031/2311','SAF/03/002/8605','SAF/03/029/2356','SAF/03/025/2703','SAF/03/014/3257','SAF/03/014/2172','SAF/03/008/7689','SAF/03/015/0733','SAF/03/011/1764','SAF/03/008/7363','SAF/03/008/7725','SAF/03/023/2524','SAF/03/033/2880','SAF/03/032/1194','SAF/03/032/2767','SAF/03/036/3336','SAF/03/033/3422','SAF/03/030/2643','SAF/03/018/1295','SAF/03/033/1818','SAF/03/014/1892','SAF/03/032/2190','SAF/03/014/2799','SAF/03/032/2454','SAF/03/007/3091','SAF/03/002/8572','SAF/03/010/3257','SAF/03/033/2759','SAF/03/010/1754','SAF/03/010/1909','SAF/03/033/3115','SAF/03/033/3590','SAF/03/007/6043','SAF/03/006/3397','SAF/03/022/3330','SAF/03/010/0336','SAF/03/031/3537','SAF/03/030/2443','SAF/03/030/3254','SAF/03/038/6175','SAF/03/010/1506','SAF/03/015/1729','SAF/03/010/1568','SAF/03/010/3145','SAF/03/010/3133','SAF/03/010/0970','SAF/03/010/1507','SAF/03/010/1347','SAF/03/010/3138','SAF/03/013/3269','SAF/03/014/1732','SAF/03/010/1406','SAF/03/010/2722','SAF/03/10A/0036','SAF/03/010/0667','SAF/03/010/1008','SAF/03/010/2346','SAF/03/010/2224','SAF/03/010/3082','SAF/03/010/2546','SAF/03/010/1099','SAF/03/026/0525','SAF/03/010/2479','SAF/03/010/2223','SAF/03/031/3139','SAF/03/010/3220','SAF/03/010/1937','SAF/03/015/2512','SAF/03/010/3142','SAF/03/010/2607','SAF/03/017/1133','SAF/03/017/1132','SAF/03/010/2521','SAF/03/017/1131','SAF/03/029/3564','SAF/03/017/1130','SAF/03/010/2501','SAF/03/010/0921','SAF/03/029/3866','SAF/03/025/2404','SAF/03/031/3932','SAF/03/010/2478','SAF/03/010/0232','SAF/03/029/4759','SAF/03/10A/0007','SAF/03/010/3170','SAF/03/010/0803','SAF/03/010/1380','SAF/03/010/1151','SAF/03/010/1316','SAF/03/010/1314','SAF/03/010/1063','SAF/03/017/1134','SAF/03/006/3090','SAF/03/010/1551','SAF/03/006/2044','SAF/03/010/0154','SAF/03/030/0834','SAF/03/037/5496','SAF/03/002/8520','SAF/03/032/1144','SAF/03/038/4492','SAF/03/010/1783','SAF/03/038/4095','SAF/03/025/1778','SAF/03/025/2797','SAF/03/023/2455','SAF/03/023/2384','SAF/03/023/2350','SAF/03/023/1559','SAF/03/010/3152','SAF/03/023/1939','SAF/03/022/2557','SAF/03/029/3652','SAF/03/025/1864','SAF/03/030/2514','SAF/03/034/3659','SAF/03/002/3138','SAF/03/021/3800','SAF/03/037/9010','SAF/03/029/3681','SAF/03/028/1617','SAF/03/028/1614','SAF/03/029/3802','SAF/03/010/2982','SAF/03/037/4282','SAF/03/006/2277','SAF/03/010/1555','SAF/03/029/4084','SAF/03/023/2108','SAF/03/028/0684','SAF/03/023/2107','SAF/03/028/1909','SAF/03/031/2776','SAF/03/022/2514','SAF/03/031/2039','SAF/03/009/1262','SAF/03/008/5366','SAF/03/030/2983','SAF/03/030/2946','SAF/03/010/1203','SAF/03/026/0980','SAF/03/021/3667','SAF/03/025/2949','SAF/03/021/3666','SAF/03/006/1077','SAF/03/031/3813','SAF/03/023/2439','SAF/03/023/2473','SAF/03/031/3860','SAF/03/038/6021','SAF/03/023/1376','SAF/03/010/1436','SAF/03/030/2915','SAF/03/037/4934','SAF/03/023/1606','SAF/03/023/1657','SAF/03/006/0985','SAF/03/010/2215','SAF/03/028/0702','SAF/03/023/2532','SAF/03/023/1250','SAF/03/025/2605','SAF/03/029/5329','SAF/03/032/0944','SAF/03/029/5291','SAF/03/025/2727','SAF/03/006/2941','SAF/03/022/0718','SAF/03/025/2478','SAF/03/008/6057','SAF/03/028/1584','SAF/03/026/1835','SAF/03/032/2633','SAF/03/025/2773','SAF/03/007/3847','SAF/03/028/1577','SAF/03/025/1779','SAF/03/028/1574','SAF/03/029/3998','SAF/03/022/3120','SAF/03/029/0753','SAF/03/022/3273','SAF/03/029/4109','SAF/03/008/1411','SAF/03/022/3203','SAF/03/022/3202','SAF/03/022/3051','SAF/03/003/3405','SAF/03/038/6099','SAF/03/003/1233','SAF/03/031/3915','SAF/03/037/9714','SAF/03/008/5828','SAF/03/031/3917','SAF/03/030/0735','SAF/03/025/2926','SAF/03/031/4474','SAF/03/022/1824','SAF/03/030/1740','SAF/03/031/2696','SAF/03/031/2697','SAF/03/025/2580','SAF/03/008/5082','SAF/03/030/1426','SAF/03/022/3311','SAF/03/029/5362','SAF/03/022/3264','SAF/03/030/2412','SAF/03/023/2310','SAF/03/023/2118','SAF/03/030/0953','SAF/03/023/0967','SAF/03/023/0964','SAF/03/032/1967','SAF/03/023/1861','SAF/03/010/2394','SAF/03/032/1745','SAF/03/028/1575','SAF/03/023/2498','SAF/03/023/2483','SAF/03/022/2501','SAF/03/023/2466','SAF/03/031/3168','SAF/03/023/2364','SAF/03/030/2440','SAF/03/029/4082','SAF/03/026/1369','SAF/03/023/2456','SAF/03/032/2368','SAF/03/026/1523','SAF/03/023/1843','SAF/03/023/2492','SAF/03/015/2139','SAF/03/014/1518','SAF/03/030/1680','SAF/03/011/3439','SAF/03/022/2760','SAF/03/030/1429','SAF/03/031/3349','SAF/03/002/4287','SAF/03/030/2126','SAF/03/009/1538','SAF/03/002/6017','SAF/03/022/3147','SAF/03/025/2479','SAF/03/002/6016','SAF/03/009/1227','SAF/03/008/7175','SAF/03/010/3171','SAF/03/031/2015','SAF/03/002/6279','SAF/03/002/3900','SAF/03/014/2067','SAF/03/032/2026','SAF/03/028/1301','SAF/03/009/1917','SAF/03/032/1449','SAF/03/008/4914','SAF/03/032/2455','SAF/03/009/1037','SAF/03/030/1532','SAF/03/022/1958','SAF/03/030/2492','SAF/03/028/1415','SAF/03/037/5400','SAF/03/033/2521','SAF/03/010/1301','SAF/03/008/4554','SAF/03/028/1165','SAF/03/025/1497','SAF/03/030/1779','SAF/03/015/1277','SAF/03/026/1061','SAF/03/025/2218','SAF/03/026/1686','SAF/03/026/0580','SAF/03/034/3448','SAF/03/031/3222','SAF/03/031/3448','SAF/03/032/2142','SAF/03/009/1906','SAF/03/025/2388','SAF/03/031/4643','SAF/03/036/3328','SAF/03/031/1558','SAF/03/008/5619','SAF/03/032/2463','SAF/03/037/9465','SAF/03/030/1368','SAF/03/032/2357','SAF/03/002/4153','SAF/03/026/0873','SAF/03/028/0368','SAF/03/010/1988','SAF/03/026/1041','SAF/03/028/1250','SAF/03/010/2052','SAF/03/010/1228','SAF/03/010/2355','SAF/03/037/5403','SAF/03/022/1451','SAF/03/030/1755','SAF/03/025/2462','SAF/03/033/1442','SAF/03/026/1442','SAF/03/015/2478','SAF/03/038/5849','SAF/03/038/6065','SAF/03/033/1309','SAF/03/026/1167','SAF/03/028/1078','SAF/03/038/6096','SAF/03/026/1433','SAF/03/026/1778','SAF/03/017/1101','SAF/03/031/2312','SAF/03/022/3297','SAF/03/008/5567','SAF/03/013/3253','SAF/03/030/3021','SAF/03/013/2737','SAF/03/013/2736','SAF/03/021/3047','SAF/03/028/1108','SAF/03/013/2092','SAF/03/022/2476','SAF/03/028/1889','SAF/03/028/1285','SAF/03/010/3107','SAF/03/033/1611','SAF/03/022/2418','SAF/03/026/0963','SAF/03/013/3242','SAF/03/028/1012','SAF/03/030/1387','SAF/03/030/1405','SAF/03/010/3102','SAF/03/028/1292','SAF/03/010/2640','SAF/03/033/1805','SAF/03/025/2575','SAF/03/025/1788','SAF/03/008/6623','SAF/03/008/4560','SAF/03/030/1382','SAF/03/031/4636','SAF/03/018/1218','SAF/03/028/1572','SAF/03/026/0888','SAF/03/026/0664','SAF/03/034/3353','SAF/03/025/2341','SAF/03/026/1787','SAF/03/011/2491','SAF/03/026/1786','SAF/03/030/1913','SAF/03/022/3299','SAF/03/023/1905','SAF/03/011/3341','SAF/03/011/3463','SAF/03/014/2801','SAF/03/014/2802','SAF/03/023/2503','SAF/03/023/2479','SAF/03/017/0398','SAF/03/028/1201','SAF/03/028/1317','SAF/03/007/3659','SAF/03/025/1848','SAF/03/031/2047','SAF/03/029/2920','SAF/03/028/1646','SAF/03/028/0288','SAF/03/007/3582','SAF/03/008/6175','SAF/03/008/7575','SAF/03/028/1613','SAF/03/008/7684','SAF/03/028/0978','SAF/03/028/0136','SAF/03/014/2460','SAF/03/028/1381','SAF/03/014/1009','SAF/03/028/0699','SAF/03/028/0841','SAF/03/017/0949','SAF/03/008/7398','SAF/03/014/0820','SAF/03/010/1845','SAF/03/008/1651','SAF/03/010/2204','SAF/03/008/5622','SAF/03/008/5624','SAF/03/010/2623','SAF/03/010/0848','SAF/03/008/6015','SAF/03/008/5258','SAF/03/008/5293','SAF/03/008/6223','SAF/03/008/7470','SAF/03/010/0244','SAF/03/014/1463','SAF/03/038/4555','SAF/03/008/4054','SAF/03/038/3347','SAF/03/015/2356','SAF/03/015/0672','SAF/03/008/7609','SAF/03/015/2557','SAF/03/008/7442','SAF/03/033/3573','SAF/03/025/1295','SAF/03/025/2229','SAF/03/025/1981','SAF/03/008/6253','SAF/03/025/0961','SAF/03/015/2513','SAF/03/025/2720','SAF/03/014/3262','SAF/03/029/5298','SAF/03/008/7576','SAF/03/006/1750','SAF/03/015/0919','SAF/03/036/3416','SAF/03/008/2887','SAF/03/008/4328','SAF/03/025/2692','SAF/03/038/6054','SAF/03/006/3346','SAF/03/025/2763','SAF/03/033/3531','SAF/03/037/5606','SAF/03/011/3360','SAF/03/021/3974','SAF/03/025/2700','SAF/03/025/1761','SAF/03/008/7353','SAF/03/025/3016','SAF/03/025/2463','SAF/03/025/2251','SAF/03/025/2691','SAF/03/025/1679','SAF/03/025/2204','SAF/03/038/3304','SAF/03/009/2487','SAF/03/025/1216','SAF/03/009/1919','SAF/03/009/1244','SAF/03/026/1747','SAF/03/025/2061','SAF/03/029/2634','SAF/03/038/2092','SAF/03/08A/0029','SAF/03/025/2438','SAF/03/009/1879','SAF/03/008/3928','SAF/03/025/2495','SAF/03/030/3610','SAF/03/006/2359','SAF/03/026/0994','SAF/03/008/5944','SAF/03/026/1299','SAF/03/026/1772','SAF/03/029/2628','SAF/03/008/7488','SAF/03/028/1117','SAF/03/029/3572','SAF/03/037/7145','SAF/03/008/7518','SAF/03/030/3740','SAF/03/030/3806','SAF/03/028/0256','SAF/03/008/7445','SAF/03/028/1438','SAF/03/007/2584','SAF/03/031/2639','SAF/03/026/1626','SAF/03/030/3739','SAF/03/026/1065','SAF/03/031/2790','SAF/03/034/2486','SAF/03/028/0470','SAF/03/028/0471','SAF/03/028/0472','SAF/03/034/3700','SAF/03/028/1611','SAF/03/029/4716','SAF/03/008/7567','SAF/03/028/1110','SAF/03/028/1054','SAF/03/002/2617','SAF/03/034/3588','SAF/03/006/1515','SAF/03/015/0702','SAF/03/008/6094','SAF/03/014/1913','SAF/03/008/6096','SAF/03/008/5935','SAF/03/025/1689','SAF/03/025/2956','SAF/03/033/1760','SAF/03/034/1553','SAF/03/015/1528','SAF/03/026/1522','SAF/03/015/2551','SAF/03/025/1675','SAF/03/025/2127','SAF/03/014/2094','SAF/03/029/5245','SAF/03/025/1871','SAF/03/029/3246','SAF/03/008/6505','SAF/03/026/0992','SAF/03/011/2966','SAF/03/002/5278','SAF/03/014/2572','SAF/03/008/5896','SAF/03/022/2438','SAF/03/022/2115','SAF/03/022/1720','SAF/03/022/2749','SAF/03/022/2750','SAF/03/037/9334','SAF/03/015/1492','SAF/03/015/1160','SAF/03/008/0992','SAF/03/008/3935','SAF/03/030/2923','SAF/03/025/1631','SAF/03/022/1183','SAF/03/006/1514','SAF/03/025/2124','SAF/03/028/1882','SAF/03/028/1881','SAF/03/029/5323','SAF/03/017/0726','SAF/03/031/3854','SAF/03/030/2371','SAF/03/031/3711','SAF/03/008/7278','SAF/03/029/3953','SAF/03/029/2861','SAF/03/015/2191','SAF/03/007/2605','SAF/03/011/2043','SAF/03/008/2867','SAF/03/011/3048','SAF/03/007/3500','SAF/03/014/2180','SAF/03/037/9525','SAF/03/032/2725','SAF/03/032/2621','SAF/03/032/2413','SAF/03/029/3768','SAF/03/008/2580','SAF/03/009/0276','SAF/03/028/0846','SAF/03/014/2311','SAF/03/025/2112','SAF/03/032/2760','SAF/03/017/0881','SAF/03/031/2213','SAF/03/032/2798','SAF/03/014/2010','SAF/03/032/1810','SAF/03/007/2835','SAF/03/014/2703','SAF/03/031/2560','SAF/03/025/2836','SAF/03/025/2505','SAF/03/028/0709','SAF/03/031/3138','SAF/03/017/1872','SAF/03/038/3749','SAF/03/011/2951','SAF/03/017/1867','SAF/03/011/2906','SAF/03/011/2549','SAF/03/028/1615','SAF/03/015/2228','SAF/03/009/1280','SAF/03/009/1093','SAF/03/033/0923','SAF/03/033/2593','SAF/03/038/6036','SAF/03/030/2244','SAF/03/033/3318','SAF/03/031/2532','SAF/03/037/6274','SAF/03/029/1737','SAF/03/011/3332','SAF/03/033/3447','SAF/03/015/1159','SAF/03/007/2913','SAF/03/008/2623','SAF/03/008/3142','SAF/03/022/2063','SAF/03/014/1490','SAF/03/009/1330','SAF/03/026/1571','SAF/03/009/1092','SAF/03/009/1094','SAF/03/037/6631','SAF/03/008/6046','SAF/03/030/3177','SAF/03/022/2222','SAF/03/009/1876','SAF/03/025/2245','SAF/03/028/1153','SAF/03/022/1873','SAF/03/022/3158','SAF/03/029/2968','SAF/03/037/7129','SAF/03/007/4525','SAF/03/037/5670','SAF/03/029/3402','SAF/03/025/1573','SAF/03/006/3311','SAF/03/014/1644','SAF/03/022/2970','SAF/03/037/5688','SAF/03/007/3383','SAF/03/031/4553','SAF/03/008/6988','SAF/03/014/2047','SAF/03/008/5975','SAF/03/014/3061','SAF/03/011/1955','SAF/03/011/1952','SAF/03/006/3318','SAF/03/022/3026','SAF/03/015/2006','SAF/03/015/1847','SAF/03/007/2855','SAF/03/030/0889','SAF/03/008/2625','SAF/03/014/1585','SAF/03/006/3178','SAF/03/037/4716','SAF/03/037/4935','SAF/03/006/3121','SAF/03/033/3379','SAF/03/022/2681','SAF/03/034/3662','SAF/03/034/3660','SAF/03/035/2069','SAF/03/033/2394','SAF/03/037/7011','SAF/03/043/3988','SAF/03/033/3425','SAF/03/029/4719','SAF/03/022/2709','SAF/03/029/3519','SAF/03/036/1972','SAF/03/006/2141','SAF/03/006/1729','SAF/03/033/1770','SAF/03/007/5242','SAF/03/014/1340','SAF/03/034/2527','SAF/03/014/3188','SAF/03/022/3199','SAF/03/022/3198','SAF/03/015/1890','SAF/03/033/3191','SAF/03/037/6112','SAF/03/034/3445','SAF/03/010/1396','SAF/03/014/1714','SAF/03/034/1503','SAF/03/010/2480','SAF/03/037/6416','SAF/03/007/3498','SAF/03/012/1665','SAF/03/029/4137','SAF/03/011/2455','SAF/03/037/6951','SAF/03/010/1366','SAF/03/037/2996','SAF/03/037/5071','SAF/03/037/6181','SAF/03/031/3192','SAF/03/018/1065','SAF/03/018/1246','SAF/03/033/2395','SAF/03/031/3612','SAF/03/018/0712','SAF/03/015/2198','SAF/03/029/3869','SAF/03/012/1168','SAF/03/010/1589','SAF/03/013/3201','SAF/03/014/2323','SAF/03/037/6349','SAF/03/033/3138','SAF/03/010/0155','SAF/03/014/1655','SAF/03/014/1652','SAF/03/031/3125','SAF/03/011/2907','SAF/03/008/6130','SAF/03/008/5521','SAF/03/033/3159','SAF/03/014/1795','SAF/03/015/2114','SAF/03/011/3307','SAF/03/010/1403','SAF/03/031/2259','SAF/03/038/3654','SAF/03/025/2802','SAF/03/038/3653','SAF/03/025/1617','SAF/03/025/1695','SAF/03/014/1801','SAF/03/025/2642','SAF/03/037/6114','SAF/03/010/0882','SAF/03/014/2515','SAF/03/008/1762','SAF/03/012/2169','SAF/03/015/2433','SAF/03/013/2453','SAF/03/025/1787','SAF/03/037/6275','SAF/03/036/1515','SAF/03/038/4321','SAF/03/011/2084','SAF/03/008/3354','SAF/03/003/2144','SAF/03/008/4962','SAF/03/008/5424','SAF/03/008/7329','SAF/03/033/2798','SAF/03/006/2951','SAF/03/029/2819','SAF/03/031/1739','SAF/03/008/4988','SAF/03/010/2001','SAF/03/030/2391','SAF/03/020/3298','SAF/03/029/2437','SAF/03/037/6542','SAF/03/029/2243','SAF/03/029/3904','SAF/03/030/2208','SAF/03/037/4791','SAF/03/029/1047','SAF/03/008/6265','SAF/03/002/6757','SAF/03/036/3118','SAF/03/031/3744','SAF/03/029/3933','SAF/03/006/1674','SAF/03/033/3429','SAF/03/037/7034','SAF/03/029/1580','SAF/03/008/2995','SAF/03/037/6470','SAF/03/031/3342','SAF/03/008/2247','SAF/03/008/3227','SAF/03/010/1644','SAF/03/029/3546','SAF/03/008/2791','SAF/03/011/2120','SAF/03/015/2522','SAF/03/010/2101','SAF/03/010/1402','SAF/03/010/1192','SAF/03/010/2350','SAF/03/031/4089','SAF/03/031/4088','SAF/03/008/6567','SAF/03/030/2123','SAF/03/008/5509','SAF/03/008/3656','SAF/03/030/3208','SAF/03/022/2579','SAF/03/008/7202','SAF/03/008/5499','SAF/03/010/2648','SAF/03/010/3004','SAF/03/037/4691','SAF/03/008/5710','SAF/03/008/4285','SAF/03/008/4896','SAF/03/015/2111','SAF/03/015/1473','SAF/03/008/4596','SAF/03/008/2998','SAF/03/033/1204','SAF/03/015/1254','SAF/03/009/2168','SAF/03/015/1249','SAF/03/002/5258','SAF/03/010/2083','SAF/03/031/2214','SAF/03/002/8371','SAF/03/008/6520','SAF/03/031/3825','SAF/03/029/2730','SAF/03/018/0974','SAF/03/018/1239','SAF/03/037/6199','SAF/03/008/3740','SAF/03/015/1036','SAF/03/037/5456','SAF/03/010/2348','SAF/03/008/2315','SAF/03/010/1775','SAF/03/008/6377','SAF/03/038/5964','SAF/03/029/2740','SAF/03/029/1676','SAF/03/029/3526','SAF/03/029/4184','SAF/03/029/3353','SAF/03/011/2824','SAF/03/006/2324','SAF/03/016/1384','SAF/03/029/3223','SAF/03/002/8418','SAF/03/006/1787','SAF/03/034/3614','SAF/03/037/4590','SAF/03/033/1217','SAF/03/030/1269','SAF/03/014/3178','SAF/03/010/2651','SAF/03/010/2086','SAF/03/008/3716','SAF/03/010/2751','SAF/03/014/2806','SAF/03/010/0868','SAF/03/037/6922','SAF/03/015/2013','SAF/03/015/2017','SAF/03/035/3419','SAF/03/008/5901','SAF/03/022/3099','SAF/03/022/1054','SAF/03/015/2458','SAF/03/037/7185','SAF/03/036/3109','SAF/03/015/1822','SAF/03/015/1599','SAF/03/031/3281','SAF/03/011/1528','SAF/03/013/3058','SAF/03/037/6150','SAF/03/014/2474','SAF/03/029/0558','SAF/03/034/3672','SAF/03/008/4153','SAF/03/006/2415','SAF/03/008/3897','SAF/03/015/1821','SAF/03/030/1495','SAF/03/015/2467','SAF/03/026/1741','SAF/03/031/2118','SAF/03/007/5727','SAF/03/013/1479','SAF/03/002/8398','SAF/03/026/1351','SAF/03/026/1743','SAF/03/030/1674','SAF/03/008/5685','SAF/03/010/2677','SAF/03/036/3272','SAF/03/010/2664','SAF/03/034/1790','SAF/03/010/1803','SAF/03/003/3513','SAF/03/010/2161','SAF/03/008/6498','SAF/03/010/1638','SAF/03/007/4526','SAF/03/036/2761','SAF/03/007/3251','SAF/03/006/1941','SAF/03/010/2128','SAF/03/025/2514','SAF/03/006/1416','SAF/03/037/6783','SAF/03/008/4547','SAF/03/025/1600','SAF/03/007/4924','SAF/03/026/1779','SAF/03/026/0917','SAF/03/008/1011','SAF/03/008/6393','SAF/03/008/3999','SAF/03/030/1398','SAF/03/018/0234','SAF/03/003/1532','SAF/03/026/0822','SAF/03/026/1306','SAF/03/026/1816','SAF/03/030/1887','SAF/03/037/4726','SAF/03/037/9360','SAF/03/031/4298','SAF/03/015/2586','SAF/03/015/1278','SAF/03/010/2275','SAF/03/008/3155','SAF/03/035/3278','SAF/03/030/2503','SAF/03/035/3265','SAF/03/009/0839','SAF/03/008/5288','SAF/03/007/4570','SAF/03/037/5402','SAF/03/010/2987','SAF/03/035/1656','SAF/03/010/0883','SAF/03/006/0964','SAF/03/010/1353','SAF/03/010/1191','SAF/03/037/4662','SAF/03/029/2531','SAF/03/037/4164','SAF/03/010/1880','SAF/03/037/4124','SAF/03/008/1646','SAF/03/030/3840','SAF/03/034/2989','SAF/03/008/2792','SAF/03/010/1791','SAF/03/037/4960','SAF/03/010/1297','SAF/03/008/4602','SAF/03/037/4788','SAF/03/012/1944','SAF/03/008/5959','SAF/03/037/6766','SAF/03/017/0960','SAF/03/008/4066','SAF/03/037/6194','SAF/03/037/6197','SAF/03/008/6020','SAF/03/037/9003','SAF/03/015/2594','SAF/03/026/0887','SAF/03/008/2561','SAF/03/008/5171','SAF/03/008/2378','SAF/03/008/4352','SAF/03/026/0702','SAF/03/026/0666','SAF/03/037/3057','SAF/03/008/4372','SAF/03/026/1425','SAF/03/026/1121','SAF/03/010/1901','SAF/03/010/0752','SAF/03/025/1703','SAF/03/008/3519','SAF/03/033/1652','SAF/03/037/6735','SAF/03/010/2491','SAF/03/033/1857','SAF/03/008/4771','SAF/03/010/3081','SAF/03/037/5826','SAF/03/037/3710','SAF/03/010/0840','SAF/03/010/0839','SAF/03/008/1497','SAF/03/010/0924','SAF/03/035/2815','SAF/03/010/1964','SAF/03/010/1962','SAF/03/007/4874','SAF/03/037/6429','SAF/03/037/5777','SAF/03/010/1462','SAF/03/013/1194','SAF/03/013/1195','SAF/03/007/3632','SAF/03/023/2468','SAF/03/018/1247','SAF/03/006/3143','SAF/03/008/4324','SAF/03/008/1117','SAF/03/037/6657','SAF/03/014/1297','SAF/03/008/1490','SAF/03/014/2087','SAF/03/006/0708','SAF/03/009/1261','SAF/03/026/1246','SAF/03/017/0559','SAF/03/008/2432','SAF/03/029/3749','SAF/03/017/1861','SAF/03/010/2469','SAF/03/030/1585','SAF/03/029/2772','SAF/03/010/2603','SAF/03/008/4479','SAF/03/002/3332','SAF/03/017/0999','SAF/03/033/2054','SAF/03/037/6491','SAF/03/025/2781','SAF/03/031/2490','SAF/03/011/2542','SAF/03/026/1592','SAF/03/008/2645','SAF/03/030/1531','SAF/03/029/2527','SAF/03/029/3087','SAF/03/037/5297','SAF/03/029/2095','SAF/03/010/1060','SAF/03/029/3285','SAF/03/003/2396','SAF/03/031/2750','SAF/03/008/1757','SAF/03/037/9466','SAF/03/008/4952','SAF/03/010/1333','SAF/03/010/1137','SAF/03/025/2726','SAF/03/029/5317','SAF/03/008/1755','SAF/03/037/6304','SAF/03/030/2004','SAF/03/037/5224','SAF/03/029/3400','SAF/03/030/1999','SAF/03/012/1467','SAF/03/009/2392','SAF/03/008/5228','SAF/03/025/1580','SAF/03/025/1581','SAF/03/025/1454','SAF/03/025/1455','SAF/03/009/1729','SAF/03/025/1370','SAF/03/025/1975','SAF/03/008/6522','SAF/03/007/2968','SAF/03/025/1693','SAF/03/008/1382','SAF/03/034/0405','SAF/03/037/5952','SAF/03/003/2146','SAF/03/002/2166','SAF/03/002/3371','SAF/03/026/0921','SAF/03/012/0683','SAF/03/002/5782','SAF/03/037/6720','SAF/03/008/7270','SAF/03/025/2410','SAF/03/025/2040','SAF/03/009/1084','SAF/03/008/3485','SAF/03/025/1757','SAF/03/025/2723','SAF/03/025/2721','SAF/03/035/2061','SAF/03/002/5758','SAF/03/08A/0020','SAF/03/012/0929','SAF/03/026/0857','SAF/03/037/6353','SAF/03/011/1948','SAF/03/008/1388','SAF/03/008/5005','SAF/03/030/3774','SAF/03/008/6432','SAF/03/008/3339','SAF/03/008/7387','SAF/03/037/3335','SAF/03/008/7057','SAF/03/030/1491','SAF/03/032/1863','SAF/03/037/5278','SAF/03/037/6992','SAF/03/037/5790','SAF/03/037/6988','SAF/03/002/4992','SAF/03/025/1367','SAF/03/032/2099','SAF/03/029/0937','SAF/03/032/2186','SAF/03/029/2069','SAF/03/030/3818','SAF/03/008/5040','SAF/03/011/2637','SAF/03/002/5741','SAF/03/017/0230','SAF/03/008/4304','SAF/03/037/4046','SAF/03/037/6701','SAF/03/009/1140','SAF/03/008/5246','SAF/03/008/3039','SAF/03/002/1421','SAF/03/009/1702','SAF/03/037/5397','SAF/03/002/4561','SAF/03/009/1514','SAF/03/037/6144','SAF/03/015/1573','SAF/03/008/1562','SAF/03/008/6316','SAF/03/037/6649','SAF/03/037/4621','SAF/03/002/7987','SAF/03/002/7986','SAF/03/002/7988','SAF/03/002/8321','SAF/03/003/2752','SAF/03/029/3512','SAF/03/008/6237','SAF/03/008/6214','SAF/03/006/1587','SAF/03/026/1536','SAF/03/026/1754','SAF/03/026/1466','SAF/03/028/0641','SAF/03/028/0640','SAF/03/028/0398','SAF/03/015/1451','SAF/03/028/0400','SAF/03/008/3372','SAF/03/028/0399','SAF/03/026/0902','SAF/03/026/0550','SAF/03/028/1241','SAF/03/028/0976','SAF/03/028/0932','SAF/03/028/0806','SAF/03/026/1755','SAF/03/033/2266','SAF/03/007/4223','SAF/03/035/3291','SAF/03/007/4487','SAF/03/033/1982','SAF/03/032/2367','SAF/03/032/2365','SAF/03/008/5234','SAF/03/032/2366','SAF/03/008/4595','SAF/03/032/2620','SAF/03/006/1665','SAF/03/025/2668','SAF/03/002/6248','SAF/03/015/0384','SAF/03/031/3613','SAF/03/017/0516','SAF/03/025/2411','SAF/03/017/0514','SAF/03/013/2027','SAF/03/011/2014','SAF/03/008/7391','SAF/03/014/1008','SAF/03/013/2126','SAF/03/031/3104','SAF/03/031/3102','SAF/03/031/4315','SAF/03/029/5148','SAF/03/011/2741','SAF/03/008/6242','SAF/03/033/1990','SAF/03/008/2313','SAF/03/035/2874','SAF/03/033/1989','SAF/03/008/4836','SAF/03/025/2139','SAF/03/031/1736','SAF/03/008/6318','SAF/03/023/2486','SAF/03/008/7025','SAF/03/031/2627','SAF/03/008/6314','SAF/03/031/2628','SAF/03/008/5284','SAF/03/031/2345','SAF/03/008/5231','SAF/03/011/2063','SAF/03/031/2211','SAF/03/031/2215','SAF/03/012/1771','SAF/03/008/3049','SAF/03/033/3448','SAF/03/012/1288','SAF/03/009/1385','SAF/03/011/2634','SAF/03/026/0810','SAF/03/026/1217','SAF/03/026/0558','SAF/03/026/1318','SAF/03/010/2641','SAF/03/010/2023','SAF/03/015/2453','SAF/03/002/3209','SAF/03/032/1638','SAF/03/037/3906','SAF/03/032/2443','SAF/03/032/2478','SAF/03/032/2477','SAF/03/009/1544','SAF/03/014/3034','SAF/03/009/1668','SAF/03/006/1483','SAF/03/014/1287','SAF/03/011/1775','SAF/03/006/1223','SAF/03/033/2556','SAF/03/015/1774','SAF/03/031/2713','SAF/03/037/6443','SAF/03/015/2461','SAF/03/023/1644','SAF/03/018/1244','SAF/03/008/4134','SAF/03/033/1485','SAF/03/035/2509','SAF/03/018/0555','SAF/03/014/3155','SAF/03/018/1241','SAF/03/018/1240','SAF/03/026/1734','SAF/03/010/1492','SAF/03/013/3083','SAF/03/033/3385','SAF/03/034/0012','SAF/03/002/7825','SAF/03/010/0996','SAF/03/037/6053','SAF/03/010/2105','SAF/03/010/2025','SAF/03/010/2103','SAF/03/010/2542','SAF/03/010/3058','SAF/03/010/2493','SAF/03/033/2619','SAF/03/014/1680','SAF/03/008/6081','SAF/03/033/3198','SAF/03/010/2598','SAF/03/008/6006','SAF/03/036/2757','SAF/03/013/3192','SAF/03/013/3198','SAF/03/009/1581','SAF/03/008/2881','SAF/03/002/4204','SAF/03/026/1550','SAF/03/015/2479','SAF/03/026/0993','SAF/03/026/0985','SAF/03/015/2477','SAF/03/015/2476','SAF/03/026/1305','SAF/03/034/3654','SAF/03/008/7300','SAF/03/037/6069','SAF/03/010/1457','SAF/03/008/3695','SAF/03/010/1930','SAF/03/037/8982','SAF/03/034/3313','SAF/03/037/2778','SAF/03/037/4085','SAF/03/011/2973','SAF/03/009/1873','SAF/03/008/3383','SAF/03/035/3280','SAF/03/035/3295','SAF/03/009/1932','SAF/03/014/3159','SAF/03/009/1608','SAF/03/013/2807','SAF/03/013/1197','SAF/03/030/2345','SAF/03/030/2349','SAF/03/026/0924','SAF/03/06A/0078','SAF/03/026/1243','SAF/03/026/0742','SAF/03/026/0720','SAF/03/002/4265','SAF/03/030/1630','SAF/03/002/4262','SAF/03/002/2908','SAF/03/030/1160','SAF/03/006/2922','SAF/03/031/4503','SAF/03/002/8179','SAF/03/030/2248','SAF/03/002/5956','SAF/03/031/2270','SAF/03/006/0967','SAF/03/10A/0031','SAF/03/015/1649','SAF/03/010/2000','SAF/03/006/2755','SAF/03/006/2726','SAF/03/008/4056','SAF/03/009/1786','SAF/03/006/2743','SAF/03/015/1280','SAF/03/031/0481','SAF/03/033/1781','SAF/03/036/2166','SAF/03/015/1229','SAF/03/031/4102','SAF/03/008/4325','SAF/03/034/1622','SAF/03/013/2787','SAF/03/014/2397','SAF/03/009/1889','SAF/03/009/1887','SAF/03/009/1891','SAF/03/008/5391','SAF/03/014/2224','SAF/03/036/2869','SAF/03/011/2301','SAF/03/011/2533','SAF/03/032/1328','SAF/03/009/1018','SAF/03/015/2472','SAF/03/034/2215','SAF/03/034/2211','SAF/03/008/4514','SAF/03/015/1314','SAF/03/008/2215','SAF/03/034/3587','SAF/03/008/2766','SAF/03/014/0946','SAF/03/014/0992','SAF/03/007/4729','SAF/03/014/3111','SAF/03/014/1295','SAF/03/015/1939','SAF/03/014/1781','SAF/03/014/2503','SAF/03/003/3359','SAF/03/008/1414','SAF/03/030/2731','SAF/03/003/1555','SAF/03/008/4362','SAF/03/015/2565','SAF/03/009/1982','SAF/03/015/1720','SAF/03/006/1270','SAF/03/035/3292','SAF/03/035/3231','SAF/03/008/5157','SAF/03/035/3149','SAF/03/037/6139','SAF/03/030/2092','SAF/03/014/0699','SAF/03/034/3756','SAF/03/029/2407','SAF/03/034/1910','SAF/03/029/2450','SAF/03/017/0270','SAF/03/030/1877','SAF/03/002/5902','SAF/03/002/5763','SAF/03/016/0928','SAF/03/002/3546','SAF/03/002/1544','SAF/03/003/2645','SAF/03/026/0724','SAF/03/013/2342','SAF/03/037/5786','SAF/03/007/2729','SAF/03/037/5784','SAF/03/013/2462','SAF/03/003/2610','SAF/03/030/2166','SAF/03/030/2170','SAF/03/030/2171','SAF/03/030/2172','SAF/03/026/1407','SAF/03/030/0732','SAF/03/026/1553','SAF/03/010/2612','SAF/03/026/1706','SAF/03/030/2380','SAF/03/002/4459','SAF/03/029/4556','SAF/03/029/3409','SAF/03/033/2833','SAF/03/029/2149','SAF/03/029/3468','SAF/03/014/2313','SAF/03/014/2314','SAF/03/014/2312','SAF/03/002/4097','SAF/03/002/7698','SAF/03/002/3970','SAF/03/026/1247','SAF/03/026/0890','SAF/03/003/3327','SAF/03/026/1781','SAF/03/026/1538','SAF/03/003/0298','SAF/03/026/1430','SAF/03/026/0834','SAF/03/036/2861','SAF/03/026/0665','SAF/03/026/0842','SAF/03/026/0667','SAF/03/010/1763','SAF/03/010/1265','SAF/03/033/1200','SAF/03/034/3421','SAF/03/006/2762','SAF/03/035/2810','SAF/03/015/1227','SAF/03/037/5781','SAF/03/033/1199','SAF/03/035/2809','SAF/03/035/2811','SAF/03/035/3326','SAF/03/008/2884','SAF/03/034/3431','SAF/03/023/2054','SAF/03/035/3334','SAF/03/035/2064','SAF/03/015/1829','SAF/03/014/1114','SAF/03/015/2480','SAF/03/015/1366','SAF/03/026/1696','SAF/03/026/1697','SAF/03/026/0628','SAF/03/033/2911','SAF/03/006/2484','SAF/03/013/1803','SAF/03/013/1816','SAF/03/026/1454','SAF/03/026/0757','SAF/03/006/1163','SAF/03/026/0734','SAF/03/036/2799','SAF/03/030/1421','SAF/03/030/1419','SAF/03/030/2261','SAF/03/030/2262','SAF/03/030/2264','SAF/03/030/2916','SAF/03/010/0946','SAF/03/033/2141','SAF/03/010/0952','SAF/03/008/2438','SAF/03/023/1827','SAF/03/023/1934','SAF/03/023/2342','SAF/03/002/6064','SAF/03/015/1431','SAF/03/015/0391','SAF/03/026/0504','SAF/03/002/7800','SAF/03/026/0654','SAF/03/026/0663','SAF/03/026/1325','SAF/03/026/1514','SAF/03/013/2806','SAF/03/002/3509','SAF/03/028/0601','SAF/03/028/0931','SAF/03/023/1889','SAF/03/023/1464','SAF/03/028/1429','SAF/03/023/0708','SAF/03/023/1398','SAF/03/010/1449','SAF/03/029/2414','SAF/03/006/1320','SAF/03/006/2563','SAF/03/006/1718','SAF/03/010/1828','SAF/03/035/3249','SAF/03/029/2238','SAF/03/034/2379','SAF/03/034/2307','SAF/03/010/1854','SAF/03/008/1354','SAF/03/010/1450','SAF/03/008/6447','SAF/03/026/1611','SAF/03/010/1034','SAF/03/026/0322','SAF/03/026/1617','SAF/03/026/0897','SAF/03/026/0896','SAF/03/026/0633','SAF/03/026/1079','SAF/03/026/1115','SAF/03/002/3078','SAF/03/007/5844','SAF/03/030/3683','SAF/03/030/3685','SAF/03/030/3684','SAF/03/010/2736','SAF/03/038/4869','SAF/03/009/1175','SAF/03/030/2597','SAF/03/030/3623','SAF/03/002/3854','SAF/03/028/0902','SAF/03/023/1138','SAF/03/028/0331','SAF/03/023/1696','SAF/03/028/0927','SAF/03/023/2429','SAF/03/028/1456','SAF/03/023/1740','SAF/03/030/1343','SAF/03/038/3088','SAF/03/028/1642','SAF/03/028/0692','SAF/03/035/0748','SAF/03/028/0158','SAF/03/038/5283','SAF/03/010/1364','SAF/03/010/1252','SAF/03/012/1265','SAF/03/038/3462','SAF/03/002/4982','SAF/03/030/1535','SAF/03/030/3108','SAF/03/026/1765','SAF/03/013/1340','SAF/03/013/1387','SAF/03/026/1625','SAF/03/015/2457','SAF/03/037/6647','SAF/03/030/3672','SAF/03/037/6015','SAF/03/030/1790','SAF/03/033/2550','SAF/03/010/1075','SAF/03/010/1076','SAF/03/010/1084','SAF/03/030/2284','SAF/03/006/1225','SAF/03/033/3144','SAF/03/037/9376','SAF/03/023/1656','SAF/03/002/7935','SAF/03/037/5963','SAF/03/037/5959','SAF/03/035/2748','SAF/03/010/1231','SAF/03/023/0718','SAF/03/034/2732','SAF/03/034/2731','SAF/03/034/2733','SAF/03/010/1198','SAF/03/013/1716','SAF/03/010/1355','SAF/03/010/1356','SAF/03/010/1656','SAF/03/003/3273','SAF/03/030/1497','SAF/03/026/1310','SAF/03/026/1703','SAF/03/003/2562','SAF/03/028/0404','SAF/03/026/1469','SAF/03/028/0299','SAF/03/006/3081','SAF/03/006/1871','SAF/03/033/1318','SAF/03/030/3651','SAF/03/023/2113','SAF/03/023/1599','SAF/03/023/0543','SAF/03/002/8289','SAF/03/028/1372','SAF/03/038/3217','SAF/03/002/2984','SAF/03/028/1371','SAF/03/038/4837','SAF/03/026/1699','SAF/03/026/1114','SAF/03/015/2496','SAF/03/026/1199','SAF/03/030/2112','SAF/03/026/1385','SAF/03/026/1372','SAF/03/026/0253','SAF/03/015/0923','SAF/03/013/1713','SAF/03/031/1636','SAF/03/034/3701','SAF/03/031/3677','SAF/03/023/0557','SAF/03/034/3711','SAF/03/023/1733','SAF/03/023/1736','SAF/03/023/1729','SAF/03/018/1220','SAF/03/018/1266','SAF/03/023/2480','SAF/03/018/0492','SAF/03/037/4618','SAF/03/033/1821','SAF/03/026/1744','SAF/03/026/1709','SAF/03/031/4475','SAF/03/008/4085','SAF/03/026/1504','SAF/03/026/1499','SAF/03/014/0938','SAF/03/030/1785','SAF/03/030/3637','SAF/03/008/2281','SAF/03/013/1723','SAF/03/023/1678','SAF/03/034/1760','SAF/03/023/1831','SAF/03/023/1829','SAF/03/034/1849','SAF/03/017/1549','SAF/03/038/2254','SAF/03/030/2151','SAF/03/030/2598','SAF/03/030/1505','SAF/03/030/2148','SAF/03/002/8147','SAF/03/002/8148','SAF/03/030/1435','SAF/03/030/3726','SAF/03/030/2168','SAF/03/002/4976','SAF/03/008/3182','SAF/03/008/4593','SAF/03/013/1737','SAF/03/026/1701','SAF/03/030/1379','SAF/03/026/1436','SAF/03/026/1281','SAF/03/026/0423','SAF/03/026/1293','SAF/03/026/1018','SAF/03/026/1717','SAF/03/026/1720','SAF/03/026/1718','SAF/03/031/4189','SAF/03/034/2428','SAF/03/034/2424','SAF/03/031/3355','SAF/03/031/3352','SAF/03/031/3354','SAF/03/010/1770','SAF/03/034/0175','SAF/03/031/3702','SAF/03/031/3703','SAF/03/033/0921','SAF/03/010/2507','SAF/03/034/2969','SAF/03/013/1734','SAF/03/033/2779','SAF/03/023/2459','SAF/03/023/2460','SAF/03/023/1878','SAF/03/023/2391','SAF/03/023/1761','SAF/03/023/1762','SAF/03/008/4647','SAF/03/036/0834','SAF/03/015/0824','SAF/03/015/2540','SAF/03/033/1677','SAF/03/001/1913','SAF/03/038/4666','SAF/03/026/1715','SAF/03/026/1714','SAF/03/023/1840','SAF/03/002/1489','SAF/03/026/1719','SAF/03/026/1716','SAF/03/026/1322','SAF/03/026/1350','SAF/03/026/1377','SAF/03/010/1361','SAF/03/008/4578','SAF/03/049/1195','SAF/03/031/2929','SAF/03/002/6686','SAF/03/001/1011','SAF/03/026/0564','SAF/03/026/1602','SAF/03/016/1025','SAF/03/030/3743','SAF/03/030/3744','SAF/03/034/1377','SAF/03/034/1376','SAF/03/008/7421','SAF/03/023/0935','SAF/03/023/1779','SAF/03/033/1333','SAF/03/010/2116','SAF/03/010/1605','SAF/03/003/2387','SAF/03/026/1458','SAF/03/030/3195','SAF/03/030/3662','SAF/03/003/1085','SAF/03/030/1911','SAF/03/013/2386','SAF/03/031/3764','SAF/03/033/2439','SAF/03/030/1618','SAF/03/026/1482','SAF/03/026/1495','SAF/03/013/1549','SAF/03/030/1620','SAF/03/030/1060','SAF/03/014/2604','SAF/03/014/2606','SAF/03/033/1332','SAF/03/033/1297','SAF/03/014/2269','SAF/03/014/2266','SAF/03/023/0725','SAF/03/033/0965','SAF/03/014/2273','SAF/03/014/2276','SAF/03/036/0611','SAF/03/031/3775','SAF/03/010/2029','SAF/03/030/1356','SAF/03/030/2927','SAF/03/030/3178','SAF/03/032/2746','SAF/03/032/2717','SAF/03/032/2397','SAF/03/032/2338','SAF/03/026/1319','SAF/03/026/1465','SAF/03/008/3966','SAF/03/026/1283','SAF/03/030/0910','SAF/03/026/1273','SAF/03/026/1568','SAF/03/031/3545','SAF/03/035/2674','SAF/03/038/2844','SAF/03/037/5841','SAF/03/001/2003','SAF/03/001/1528','SAF/03/001/1526','SAF/03/014/2076','SAF/03/003/2283','SAF/03/001/2022','SAF/03/037/5600','SAF/03/011/3217','SAF/03/030/2793','SAF/03/008/7447','SAF/03/031/3621','SAF/03/030/2790','SAF/03/003/1708','SAF/03/008/1865','SAF/03/031/2054','SAF/03/031/1654','SAF/03/008/6345','SAF/03/013/1392','SAF/03/031/2234','SAF/03/013/1391','SAF/03/013/1389','SAF/03/013/2067','SAF/03/013/2068','SAF/03/013/2061','SAF/03/030/2785','SAF/03/030/2818','SAF/03/002/4300','SAF/03/002/5305','SAF/03/014/2845','SAF/03/014/1669','SAF/03/014/1650','SAF/03/014/2835','SAF/03/023/0404','SAF/03/030/3763','SAF/03/010/1057','SAF/03/017/1750','SAF/03/017/1254','SAF/03/011/2512','SAF/03/017/1841','SAF/03/010/3030','SAF/03/017/0549','SAF/03/001/2380','SAF/03/016/1347','SAF/03/001/1642','SAF/03/023/1600','SAF/03/023/1586','SAF/03/011/1554','SAF/03/026/1470','SAF/03/001/1019','SAF/03/006/2518','SAF/03/006/2519','SAF/03/011/1811','SAF/03/017/1684','SAF/03/017/1685','SAF/03/017/1683','SAF/03/038/1841','SAF/03/017/0890','SAF/03/006/1887','SAF/03/003/0472','SAF/03/003/1685','SAF/03/030/2717','SAF/03/003/1420','SAF/03/011/1720','SAF/03/031/3181','SAF/03/032/2774','SAF/03/032/2797','SAF/03/030/2895','SAF/03/032/2791','SAF/03/026/1589','SAF/03/032/1752','SAF/03/030/2792','SAF/03/032/2482','SAF/03/008/1813','SAF/03/008/1863','SAF/03/033/3380','SAF/03/032/2152','SAF/03/010/1895','SAF/03/006/0886','SAF/03/008/4571','SAF/03/017/1806','SAF/03/017/1698','SAF/03/017/1789','SAF/03/011/1936','SAF/03/013/3125','SAF/03/013/2400','SAF/03/013/1437','SAF/03/033/2425','SAF/03/038/5319','SAF/03/002/3409','SAF/03/023/1712','SAF/03/023/1627','SAF/03/002/3801','SAF/03/011/1524','SAF/03/011/2318','SAF/03/003/1194','SAF/03/006/2861','SAF/03/031/3811','SAF/03/06A/0060','SAF/03/013/2162','SAF/03/013/1869','SAF/03/006/2356','SAF/03/043/4188','SAF/03/030/2889','SAF/03/002/3911','SAF/03/030/2888','SAF/03/030/2893','SAF/03/030/2891','SAF/03/010/2541','SAF/03/032/1627','SAF/03/030/2892','SAF/03/032/0369','SAF/03/010/2980','SAF/03/008/0816','SAF/03/012/2082','SAF/03/008/7155','SAF/03/033/2729','SAF/03/012/2143','SAF/03/017/1840','SAF/03/017/1225','SAF/03/017/0668','SAF/03/017/1844','SAF/03/017/1792','SAF/03/006/3199','SAF/03/017/0734','SAF/03/017/1788','SAF/03/017/1849','SAF/03/030/1901','SAF/03/032/2660','SAF/03/017/0841','SAF/03/032/1694','SAF/03/032/2728','SAF/03/003/2502','SAF/03/010/1584','SAF/03/001/2017','SAF/03/010/2253','SAF/03/007/4759','SAF/03/001/2178','SAF/03/013/1559','SAF/03/012/1739','SAF/03/001/1986','SAF/03/010/1954','SAF/03/013/1745','SAF/03/017/1663','SAF/03/017/1664','SAF/03/012/1819','SAF/03/016/1266','SAF/03/014/1231','SAF/03/038/3834','SAF/03/001/2691','SAF/03/017/1667','SAF/03/017/1665','SAF/03/017/1668','SAF/03/038/1845','SAF/03/008/0474','SAF/03/017/1839','SAF/03/012/1542','SAF/03/012/1620','SAF/03/032/2786','SAF/03/032/2301','SAF/03/032/2115','SAF/03/010/2681','SAF/03/010/2680','SAF/03/023/0839','SAF/03/023/0838','SAF/03/017/0682','SAF/03/017/0450','SAF/03/017/0573','SAF/03/017/1572','SAF/03/014/1797','SAF/03/016/1064','SAF/03/010/1147','SAF/03/007/5663','SAF/03/014/2624','SAF/03/002/7710','SAF/03/007/5393','SAF/03/032/2306','SAF/03/023/2304','SAF/03/023/2343','SAF/03/008/6985','SAF/03/018/0830','SAF/03/011/1037','SAF/03/014/2506','SAF/03/016/0795','SAF/03/014/2885','SAF/03/023/2077','SAF/03/015/2178','SAF/03/032/1646','SAF/03/009/1388','SAF/03/009/1834','SAF/03/026/1639','SAF/03/009/1493','SAF/03/038/2985','SAF/03/038/2984','SAF/03/010/1112','SAF/03/012/1111','SAF/03/010/1046','SAF/03/010/1045','SAF/03/010/1044','SAF/03/018/1269','SAF/03/008/3338','SAF/03/018/1199','SAF/03/007/4227','SAF/03/006/2573','SAF/03/009/0704','SAF/03/008/7106','SAF/03/030/3645','SAF/03/009/0705','SAF/03/009/1361','SAF/03/014/3179','SAF/03/016/0631','SAF/03/016/1378','SAF/03/012/1036','SAF/03/016/1361','SAF/03/030/3231','SAF/03/012/2092','SAF/03/012/1691','SAF/03/030/3654','SAF/03/030/3659','SAF/03/030/3658','SAF/03/023/2357','SAF/03/018/0075','SAF/03/014/1156','SAF/03/026/0754','SAF/03/015/0769','SAF/03/029/4677','SAF/03/028/1210','SAF/03/028/0899','SAF/03/002/3236','SAF/03/028/0593','SAF/03/037/6697','SAF/03/009/2170','SAF/03/009/1885','SAF/03/009/1888','SAF/03/009/0376','SAF/03/032/2643','SAF/03/032/2016','SAF/03/032/2018','SAF/03/032/1397','SAF/03/032/2395','SAF/03/032/2396','SAF/03/002/4247','SAF/03/001/1225','SAF/03/014/1410','SAF/03/002/6738','SAF/03/002/1690','SAF/03/001/1090','SAF/03/002/1685','SAF/03/017/0895','SAF/03/017/0893','SAF/03/002/4111','SAF/03/001/0850','SAF/03/002/6674','SAF/03/002/4216','SAF/03/001/2115','SAF/03/017/0272','SAF/03/002/3031','SAF/03/003/0376','SAF/03/017/0640','SAF/03/017/0677','SAF/03/032/1447','SAF/03/001/1322','SAF/03/006/1372','SAF/03/006/3103','SAF/03/014/2267','SAF/03/002/5394','SAF/03/002/4787','SAF/03/001/2067','SAF/03/002/4979','SAF/03/002/5123','SAF/03/002/6277','SAF/03/003/2931','SAF/03/002/6490','SAF/03/002/6012','SAF/03/002/4229','SAF/03/002/4225','SAF/03/030/2216','SAF/03/026/0821','SAF/03/002/6080','SAF/03/003/2864','SAF/03/032/2697','SAF/03/001/1274','SAF/03/003/2912','SAF/03/017/0978','SAF/03/003/2986','SAF/03/003/1494','SAF/03/003/2959','SAF/03/018/0260','SAF/03/032/2682','SAF/03/001/1051','SAF/03/003/2660','SAF/03/001/0908','SAF/03/032/1559','SAF/03/030/2127','SAF/03/032/1739','SAF/03/002/5211','SAF/03/033/1210','SAF/03/016/1234','SAF/03/020/2555','SAF/03/002/2169','SAF/03/017/0226','SAF/03/031/2391','SAF/03/016/0899','SAF/03/026/1326','SAF/03/018/0312','SAF/03/033/2633','SAF/03/026/0566','SAF/03/015/1860','SAF/03/001/2092','SAF/03/002/5364','SAF/03/017/1332','SAF/03/002/5081','SAF/03/032/1859','SAF/03/017/0830','SAF/03/002/3685','SAF/03/002/3687','SAF/03/032/1860','SAF/03/017/0632','SAF/03/017/1660','SAF/03/036/2763','SAF/03/018/0190','SAF/03/003/1635','SAF/03/036/2600','SAF/03/036/0818','SAF/03/003/0962','SAF/03/003/2369','SAF/03/018/0333','SAF/03/036/2620','SAF/03/036/1609','SAF/03/015/2132','SAF/03/018/0840','SAF/03/003/0330','SAF/03/002/2966','SAF/03/033/1585','SAF/03/032/1826','SAF/03/032/2370','SAF/03/015/0768','SAF/03/015/0750','SAF/03/002/5071','SAF/03/013/1955','SAF/03/013/1969','SAF/03/015/0615','SAF/03/015/0612','SAF/03/014/1776','SAF/03/002/0589','SAF/03/015/1595','SAF/03/015/1442','SAF/03/015/0925','SAF/03/035/1348','SAF/03/015/0409','SAF/03/038/1846','SAF/03/029/4625','SAF/03/002/4779','SAF/03/001/2185','SAF/03/032/2328','SAF/03/017/0346','SAF/03/015/1392','SAF/03/015/0902','SAF/03/002/4747','SAF/03/015/1760','SAF/03/015/1765','SAF/03/034/3081','SAF/03/009/1931','SAF/03/010/1999','SAF/03/012/1868','SAF/03/017/0793','SAF/03/002/3941','SAF/03/002/1683','SAF/03/017/0813','SAF/03/017/0517','SAF/03/033/1121','SAF/03/017/0329','SAF/03/015/0826','SAF/03/018/0098','SAF/03/018/0539','SAF/03/018/0660','SAF/03/018/0776','SAF/03/017/1509','SAF/03/017/0506','SAF/03/017/0768','SAF/03/018/0302','SAF/03/029/3688','SAF/03/035/0494','SAF/03/017/1575','SAF/03/007/2395','SAF/03/013/1542','SAF/03/017/0673','SAF/03/017/0672','SAF/03/003/0423','SAF/03/002/5121','SAF/03/010/1148','SAF/03/010/1810','SAF/03/010/1812','SAF/03/015/0697','SAF/03/003/2427','SAF/03/009/0984','SAF/03/003/2364','SAF/03/003/0762','SAF/03/002/2657','SAF/03/015/2285','SAF/03/015/0113','SAF/03/015/1202','SAF/03/003/2034','SAF/03/003/2356','SAF/03/018/0810','SAF/03/003/2440','SAF/03/009/2138','SAF/03/018/0721','SAF/03/002/4942','SAF/03/002/0728','SAF/03/003/0095','SAF/03/002/4560','SAF/03/002/4786','SAF/03/002/4322','SAF/03/002/5691','SAF/03/002/4264','SAF/03/002/4263','SAF/03/002/5614','SAF/03/018/0968','SAF/03/002/1678','SAF/03/014/1602','SAF/03/015/0978','SAF/03/015/0425','SAF/03/018/0547','SAF/03/018/0824','SAF/03/034/2217','SAF/03/015/0655','SAF/03/015/1459','SAF/03/015/1460','SAF/03/017/1304','SAF/03/002/3880','SAF/03/015/1110','SAF/03/018/0888','SAF/03/018/0150','SAF/03/018/0583','SAF/03/018/0165','SAF/03/018/0167','SAF/03/018/0166','SAF/03/003/1044','SAF/03/002/4474','SAF/03/029/2240','SAF/03/002/4836','SAF/03/002/4713','SAF/03/002/4349','SAF/03/017/0525','SAF/03/018/0530','SAF/03/015/0943','SAF/03/017/0494','SAF/03/002/4565','SAF/03/002/4780','SAF/03/017/0473','SAF/03/034/1983','SAF/03/015/1043','SAF/03/015/1042','SAF/03/017/1578','SAF/03/015/0946','SAF/03/016/1183','SAF/03/003/2011','SAF/03/015/0528','SAF/03/016/1141','SAF/03/017/1249','SAF/03/002/4553','SAF/03/017/1248','SAF/03/002/0118','SAF/03/001/1426','SAF/03/002/5594','SAF/03/003/1313','SAF/03/017/0778','SAF/03/002/4748','SAF/03/003/2333','SAF/03/016/0903','SAF/03/016/0409','SAF/03/016/0562','SAF/03/016/0892','SAF/03/016/0891','SAF/03/017/0966','SAF/03/017/0564','SAF/03/017/1579','SAF/03/001/1806','SAF/03/035/2852','SAF/03/018/0624','SAF/03/015/2274','SAF/03/013/1350','SAF/03/035/2322','SAF/03/035/2511','SAF/03/001/1668','SAF/03/034/1699','SAF/03/017/1652','SAF/03/015/0195','SAF/03/035/2744','SAF/03/015/1474','SAF/03/017/0611','SAF/03/018/0331','SAF/03/017/0678','SAF/03/018/0556','SAF/03/018/0521','SAF/03/002/3712','SAF/03/017/1006','SAF/03/017/1474','SAF/03/002/3767','SAF/03/036/1723','SAF/03/002/6128','SAF/03/008/2006','SAF/03/008/2004','SAF/03/002/0077','SAF/03/035/1533','SAF/03/002/3342','SAF/03/015/1019','SAF/03/036/0936','SAF/03/015/2175','SAF/03/017/0875','SAF/03/002/3488','SAF/03/008/4196','SAF/03/002/3330','SAF/03/002/4201','SAF/03/002/3581','SAF/03/002/3564','SAF/03/002/3394','SAF/03/043/1392','SAF/03/033/2271','SAF/03/002/3410','SAF/03/017/1436','SAF/03/002/2758','SAF/03/002/3791','SAF/03/009/1061','SAF/03/002/4076','SAF/03/033/2716','SAF/03/017/1034','SAF/03/015/0755','SAF/03/002/4127','SAF/03/002/3915','SAF/03/002/4021','SAF/03/002/3922','SAF/03/016/0625','SAF/03/016/0545','SAF/03/016/0960','SAF/03/008/0310','SAF/03/015/0855','SAF/03/017/1376','SAF/03/017/1375','SAF/03/017/0152','SAF/03/001/1681','SAF/03/015/1771','SAF/03/017/0330','SAF/03/017/1261','SAF/03/016/0688','SAF/03/016/0874','SAF/03/016/0788','SAF/03/016/0524','SAF/03/016/0449','SAF/03/016/0444','SAF/03/015/0694','SAF/03/015/0693','SAF/03/016/0860','SAF/03/016/0806','SAF/03/017/0946','SAF/03/008/2486','SAF/03/034/1065','SAF/03/036/1995','SAF/03/035/2451','SAF/03/018/0862','SAF/03/018/0911','SAF/03/017/0257','SAF/03/018/0523','SAF/03/017/1348','SAF/03/003/1810','SAF/03/031/1285','SAF/03/001/1543','SAF/03/018/0364','SAF/03/018/0191','SAF/03/017/0348','SAF/03/016/1094','SAF/03/016/0364','SAF/03/016/0578','SAF/03/016/0577','SAF/03/016/0455','SAF/03/016/0460','SAF/03/016/0459','SAF/03/016/0457','SAF/03/016/0456','SAF/03/016/0454','SAF/03/016/0452','SAF/03/018/0439','SAF/03/014/1533','SAF/03/003/1537','SAF/03/003/0163','SAF/03/003/2247','SAF/03/018/0224','SAF/03/001/0738','SAF/03/018/0394','SAF/03/017/0256','SAF/03/009/2144','SAF/03/018/0395','SAF/03/32A/0003','SAF/03/032/2169','SAF/03/032/2162','SAF/03/038/0609','SAF/03/036/2443','SAF/03/001/1435','SAF/03/034/1556','SAF/03/003/0574','SAF/03/003/0439','SAF/03/038/2086','SAF/03/008/1905','SAF/03/038/3298','SAF/03/032/2419','SAF/03/034/1824','SAF/03/023/0999','SAF/03/030/2596','SAF/03/010/0976','SAF/03/001/0954','SAF/03/028/0820','SAF/03/002/2636','SAF/03/001/1018','SAF/03/001/1204','SAF/03/023/1316','SAF/03/023/1630','SAF/03/014/1737','SAF/03/001/1079','SAF/03/023/1397','SAF/03/023/1598','SAF/03/031/1747','SAF/03/030/1176','SAF/03/008/1982','SAF/03/002/2472','SAF/03/002/2723','SAF/03/028/0424','SAF/03/023/1311','SAF/03/023/0920','SAF/03/008/3790','SAF/03/003/1318','SAF/03/017/0652','SAF/03/030/1290','SAF/03/015/1089','SAF/03/003/0627','SAF/03/036/2545','SAF/03/008/1793','SAF/03/014/2459','SAF/03/013/1561','SAF/03/015/0040','SAF/03/015/0433','SAF/03/010/0186','SAF/03/010/0076','SAF/03/002/1484','SAF/03/023/1479','SAF/03/030/1174','SAF/03/030/1186','SAF/03/010/0925','SAF/03/034/3191','SAF/03/002/3100','SAF/03/023/1714','SAF/03/023/1150','SAF/03/023/1152','SAF/03/015/2019','SAF/03/003/2820','SAF/03/015/1148','SAF/03/034/3016','SAF/03/036/2837','SAF/03/008/3382','SAF/03/036/2517','SAF/03/030/0876','SAF/03/030/0877','SAF/03/010/0808','SAF/03/009/0212','SAF/03/030/1499','SAF/03/023/1777','SAF/03/018/0875','SAF/03/018/0897','SAF/03/015/0638','SAF/03/018/0073','SAF/03/035/1883','SAF/03/036/2737','SAF/03/036/2575','SAF/03/033/2786','SAF/03/015/1269','SAF/03/035/2602','SAF/03/035/1169','SAF/03/035/1170','SAF/03/030/3057','SAF/03/035/2519','SAF/03/002/4385','SAF/03/023/1617','SAF/03/023/1661','SAF/03/035/0488','SAF/03/035/2311','SAF/03/035/2513','SAF/03/036/2462','SAF/03/033/1985','SAF/03/033/2185','SAF/03/033/1420','SAF/03/017/0937','SAF/03/036/1804','SAF/03/036/2448','SAF/03/015/0696','SAF/03/010/1332','SAF/03/036/1635','SAF/03/036/2451','SAF/03/036/2133','SAF/03/036/1400','SAF/03/015/1763','SAF/03/015/1762','SAF/03/015/2086','SAF/03/018/0842','SAF/03/030/2404','SAF/03/018/0188','SAF/03/018/0337','SAF/03/018/0570','SAF/03/008/1599','SAF/03/017/1072','SAF/03/030/2602','SAF/03/033/1487','SAF/03/023/1339','SAF/03/006/0933','SAF/03/001/2041','SAF/03/017/0791','SAF/03/029/3391','SAF/03/017/0788','SAF/03/017/1246','SAF/03/015/1537','SAF/03/015/1424','SAF/03/001/2109','SAF/03/023/0516','SAF/03/018/0822','SAF/03/015/2236','SAF/03/002/6293','SAF/03/018/1028','SAF/03/018/0717','SAF/03/003/2838','SAF/03/003/2837','SAF/03/023/1640','SAF/03/017/0908','SAF/03/016/0871','SAF/03/015/1183','SAF/03/002/5359','SAF/03/023/1641','SAF/03/023/1636','SAF/03/017/0324','SAF/03/017/0122','SAF/03/023/0684','SAF/03/034/2043','SAF/03/015/0890','SAF/03/030/1352','SAF/03/030/1167','SAF/03/035/1731','SAF/03/036/2475','SAF/03/002/2535','SAF/03/031/2597','SAF/03/033/3081','SAF/03/030/2121','SAF/03/023/1580','SAF/03/017/1589','SAF/03/015/0981','SAF/03/015/2085','SAF/03/015/0905','SAF/03/035/2741','SAF/03/023/1895','SAF/03/015/2037','SAF/03/035/2632','SAF/03/035/2414','SAF/03/033/2575','SAF/03/035/2618','SAF/03/033/2383','SAF/03/033/1960','SAF/03/015/1893','SAF/03/035/2656','SAF/03/034/2137','SAF/03/035/2564','SAF/03/017/1620','SAF/03/017/0720','SAF/03/017/1301','SAF/03/023/1164','SAF/03/015/2264','SAF/03/008/4263','SAF/03/034/2831','SAF/03/015/2260','SAF/03/015/1230','SAF/03/035/2714','SAF/03/033/2468','SAF/03/034/2789','SAF/03/008/1753','SAF/03/008/1705','SAF/03/017/0208','SAF/03/035/2543','SAF/03/017/1615','SAF/03/033/2988','SAF/03/015/1873','SAF/03/033/2987','SAF/03/030/0630','SAF/03/018/0672','SAF/03/017/0418','SAF/03/035/2420','SAF/03/035/2637','SAF/03/035/2417','SAF/03/018/0987','SAF/03/003/2377','SAF/03/035/2556','SAF/03/035/2217','SAF/03/031/2539','SAF/03/036/2659','SAF/03/033/3044','SAF/03/034/3305','SAF/03/017/1394','SAF/03/032/2579','SAF/03/035/2210','SAF/03/035/2211','SAF/03/015/0620','SAF/03/029/2266','SAF/03/003/2171','SAF/03/006/0427','SAF/03/036/2388','SAF/03/028/0710','SAF/03/018/1008','SAF/03/018/0213','SAF/03/018/0757','SAF/03/018/0977','SAF/03/035/2566','SAF/03/036/1874','SAF/03/023/1584','SAF/03/017/1342','SAF/03/033/1983','SAF/03/016/1049','SAF/03/016/1203','SAF/03/016/1215','SAF/03/016/0185','SAF/03/034/2943','SAF/03/018/0985','SAF/03/029/4406','SAF/03/023/1555','SAF/03/036/1844','SAF/03/008/2712','SAF/03/008/6185','SAF/03/008/2576','SAF/03/030/1481','SAF/03/017/1305','SAF/03/017/0198','SAF/03/003/2826','SAF/03/023/1439','SAF/03/023/1548','SAF/03/030/1461','SAF/03/030/2518','SAF/03/030/1428','SAF/03/014/1569','SAF/03/014/1373','SAF/03/017/1176','SAF/03/002/0979','SAF/03/030/1632','SAF/03/023/1306','SAF/03/035/2066','SAF/03/033/1827','SAF/03/035/2207','SAF/03/015/1712','SAF/03/023/1497','SAF/03/015/2118','SAF/03/028/0177','SAF/03/036/0340','SAF/03/035/1848','SAF/03/015/1564','SAF/03/015/1562','SAF/03/033/1771','SAF/03/002/1844','SAF/03/008/1367','SAF/03/035/2063','SAF/03/035/1862','SAF/03/015/1010','SAF/03/030/1925','SAF/03/030/1525','SAF/03/030/1411','SAF/03/030/1327','SAF/03/015/2097','SAF/03/015/2171','SAF/03/036/1061','SAF/03/015/1866','SAF/03/034/2622','SAF/03/008/0050','SAF/03/002/2170','SAF/03/023/0379','SAF/03/008/4497','SAF/03/023/0357','SAF/03/023/1180','SAF/03/030/2009','SAF/03/036/1872','SAF/03/036/1870','SAF/03/033/2351','SAF/03/033/1575','SAF/03/033/2202','SAF/03/030/1917','SAF/03/030/1157','SAF/03/031/2077','SAF/03/017/1477','SAF/03/031/2504','SAF/03/010/0701','SAF/03/018/0199','SAF/03/018/0201','SAF/03/018/0814','SAF/03/033/2465','SAF/03/029/4062','SAF/03/030/0489','SAF/03/006/1058','SAF/03/017/1135','SAF/03/023/1292','SAF/03/015/1580','SAF/03/031/2559','SAF/03/033/1860','SAF/03/033/2699','SAF/03/017/1577','SAF/03/017/1576','SAF/03/033/3051','SAF/03/017/1100','SAF/03/030/1058','SAF/03/036/1623','SAF/03/017/1503','SAF/03/003/0114','SAF/03/008/1784','SAF/03/049/1511','SAF/03/033/2747','SAF/03/030/1015','SAF/03/030/1054','SAF/03/017/0882','SAF/03/030/1758','SAF/03/017/1435','SAF/03/033/1484','SAF/03/015/0474','SAF/03/028/1221','SAF/03/032/2109','SAF/03/031/1889','SAF/03/036/1975','SAF/03/029/3983','SAF/03/018/0732','SAF/03/017/1393','SAF/03/001/0983','SAF/03/015/1778','SAF/03/043/0285','SAF/03/033/1324','SAF/03/034/0613','SAF/03/023/1347','SAF/03/035/2096','SAF/03/023/1258','SAF/03/023/1112','SAF/03/017/1613','SAF/03/003/2672','SAF/03/017/1276','SAF/03/017/1398','SAF/03/017/1505','SAF/03/015/1968','SAF/03/018/0894','SAF/03/034/2976','SAF/03/018/0848','SAF/03/030/1489','SAF/03/030/0984','SAF/03/030/0047','SAF/03/030/0057','SAF/03/038/2227','SAF/03/015/1469','SAF/03/017/1472','SAF/03/015/1033','SAF/03/015/1624','SAF/03/015/1930','SAF/03/030/2003','SAF/03/023/1541','SAF/03/023/1540','SAF/03/018/0724','SAF/03/023/1201','SAF/03/015/1806','SAF/03/016/1099','SAF/03/033/2452','SAF/03/033/1145','SAF/03/007/1135','SAF/03/023/1478','SAF/03/016/1132','SAF/03/016/1133','SAF/03/015/1918','SAF/03/031/1806','SAF/03/034/1872','SAF/03/032/2163','SAF/03/032/2170','SAF/03/032/2333','SAF/03/032/2238','SAF/03/018/0903','SAF/03/031/3226','SAF/03/031/3220','SAF/03/031/2246','SAF/03/018/0909','SAF/03/031/3221','SAF/03/023/1495','SAF/03/018/0883','SAF/03/023/1427','SAF/03/018/0884','SAF/03/023/1429','SAF/03/031/2310','SAF/03/031/2745','SAF/03/017/1374','SAF/03/015/2105','SAF/03/015/2059','SAF/03/017/1546','SAF/03/031/2257','SAF/03/031/2001','SAF/03/016/1079','SAF/03/018/0713','SAF/03/031/1628','SAF/03/015/1976','SAF/03/017/1464','SAF/03/015/1937','SAF/03/033/2348','SAF/03/016/1115','SAF/03/031/2005','SAF/03/016/1072','SAF/03/018/0710','SAF/03/015/1993','SAF/03/017/1330','SAF/03/033/2293','SAF/03/017/1492','SAF/03/015/1638','SAF/03/015/1927','SAF/03/015/1931','SAF/03/015/2066','SAF/03/016/1058','SAF/03/016/1155','SAF/03/016/1154','SAF/03/016/1153','SAF/03/016/1152','SAF/03/038/1741','SAF/03/030/0989','SAF/03/030/1369','SAF/03/015/1864','SAF/03/016/1143','SAF/03/015/1672','SAF/03/018/0811','SAF/03/015/2018','SAF/03/015/2014','SAF/03/023/0575','SAF/03/015/1973','SAF/03/015/1634','SAF/03/034/1300','SAF/03/035/1794','SAF/03/017/1230','SAF/03/034/1529','SAF/03/015/0815','SAF/03/034/2149','SAF/03/002/2468','SAF/03/023/0633','SAF/03/008/0713','SAF/03/023/0636','SAF/03/014/1227','SAF/03/023/0555','SAF/03/023/0556','SAF/03/023/1728','SAF/03/015/1751','SAF/03/015/1753','SAF/03/034/2036','SAF/03/031/1856','SAF/03/023/1078','SAF/03/023/1113','SAF/03/008/1082','SAF/03/014/1365','SAF/03/023/0870','SAF/03/034/2450','SAF/03/035/1176','SAF/03/023/0769','SAF/03/023/0618','SAF/03/023/1165','SAF/03/036/1478','SAF/03/023/0456','SAF/03/033/1913','SAF/03/015/0867','SAF/03/015/1261','SAF/03/023/0459','SAF/03/017/0386','SAF/03/036/2633','SAF/03/017/1120','SAF/03/015/1776','SAF/03/015/1699','SAF/03/036/2113','SAF/03/036/1619','SAF/03/015/1051','SAF/03/023/0528','SAF/03/008/0960','SAF/03/023/0526','SAF/03/023/0542','SAF/03/023/1441','SAF/03/017/1231','SAF/03/015/1707','SAF/03/017/0684','SAF/03/015/1503','SAF/03/015/0621','SAF/03/015/1222','SAF/03/015/1677','SAF/03/003/1856','SAF/03/003/1021','SAF/03/036/1471','SAF/03/032/1721','SAF/03/036/1427','SAF/03/023/1155','SAF/03/015/1726','SAF/03/015/1678','SAF/03/015/1085','SAF/03/015/1470','SAF/03/034/2444','SAF/03/015/1300','SAF/03/015/1556','SAF/03/035/1568','SAF/03/008/1274','SAF/03/015/1727','SAF/03/033/1535','SAF/03/015/0577','SAF/03/015/1393','SAF/03/033/0074','SAF/03/023/1130','SAF/03/030/1133','SAF/03/033/0815','SAF/03/015/0336','SAF/03/015/0598','SAF/03/015/1029','SAF/03/028/0484','SAF/03/023/1056','SAF/03/015/1446','SAF/03/015/1447','SAF/03/033/1679','SAF/03/028/0483','SAF/03/016/0995','SAF/03/016/1018','SAF/03/023/0433','SAF/03/023/0948','SAF/03/032/1858','SAF/03/034/1549','SAF/03/008/1119','SAF/03/023/1082','SAF/03/033/1435','SAF/03/023/0730','SAF/03/10A/0006','SAF/03/008/1131','SAF/03/017/0555','SAF/03/014/1353','SAF/03/038/2032','SAF/03/033/0920','SAF/03/016/0077','SAF/03/036/2632','SAF/03/036/0811','SAF/03/023/0407','SAF/03/032/1667','SAF/03/017/1232','SAF/03/023/0735','SAF/03/015/1420','SAF/03/017/1214','SAF/03/017/1266','SAF/03/010/0593','SAF/03/017/1186','SAF/03/016/0962','SAF/03/016/0957','SAF/03/016/0426','SAF/03/016/0893','SAF/03/010/0526','SAF/03/016/1031','SAF/03/016/0805','SAF/03/016/0865','SAF/03/010/2187','SAF/03/015/0291','SAF/03/015/0293','SAF/03/032/2042','SAF/03/036/1712','SAF/03/033/1228','SAF/03/017/1152','SAF/03/017/1151','SAF/03/015/1048','SAF/03/023/0686','SAF/03/016/0608','SAF/03/015/1417','SAF/03/015/1463','SAF/03/017/1280','SAF/03/036/1376','SAF/03/023/1011','SAF/03/017/1114','SAF/03/016/1030','SAF/03/016/1032','SAF/03/016/0833','SAF/03/016/1020','SAF/03/036/1364','SAF/03/023/1163','SAF/03/023/1007','SAF/03/028/0747','SAF/03/028/0580','SAF/03/017/0730','SAF/03/010/0637','SAF/03/010/0636','SAF/03/017/1127','SAF/03/015/0809','SAF/03/015/0812','SAF/03/017/1229','SAF/03/015/0673','SAF/03/017/0244','SAF/03/028/0365','SAF/03/028/0367','SAF/03/015/1628','SAF/03/015/1259','SAF/03/028/0513','SAF/03/015/1625','SAF/03/017/1118','SAF/03/015/0604','SAF/03/028/0499','SAF/03/015/0894','SAF/03/028/0506','SAF/03/028/0507','SAF/03/023/1001','SAF/03/010/1592','SAF/03/028/0356','SAF/03/008/1060','SAF/03/008/0974','SAF/03/018/0723','SAF/03/018/0636','SAF/03/016/1043','SAF/03/034/1924','SAF/03/034/1212','SAF/03/015/1761','SAF/03/017/0941','SAF/03/017/1234','SAF/03/036/1444','SAF/03/015/0797','SAF/03/015/0502','SAF/03/015/1074','SAF/03/008/0925','SAF/03/018/0435','SAF/03/017/1085','SAF/03/008/1111','SAF/03/016/0846','SAF/03/036/1714','SAF/03/028/0688','SAF/03/036/1022','SAF/03/015/0735','SAF/03/036/1475','SAF/03/032/1258','SAF/03/032/1259','SAF/03/015/1091','SAF/03/015/1093','SAF/03/015/1099','SAF/03/006/0644','SAF/03/018/0543','SAF/03/009/0556','SAF/03/016/0996','SAF/03/016/0696','SAF/03/018/0540','SAF/03/006/0531','SAF/03/028/0905','SAF/03/010/0087','SAF/03/033/0752','SAF/03/028/0962','SAF/03/015/0501','SAF/03/015/0500','SAF/03/015/1260','SAF/03/015/1440','SAF/03/018/0469','SAF/03/023/0682','SAF/03/023/0680','SAF/03/030/0612','SAF/03/036/0951','SAF/03/028/0592','SAF/03/036/0594','SAF/03/028/0997','SAF/03/028/0998','SAF/03/030/1033','SAF/03/030/0782','SAF/03/015/0478','SAF/03/015/0482','SAF/03/018/0544','SAF/03/017/0561','SAF/03/016/0482','SAF/03/016/0870','SAF/03/036/0695','SAF/03/016/0717','SAF/03/016/0648','SAF/03/034/1173','SAF/03/018/0505','SAF/03/018/0516','SAF/03/018/0515','SAF/03/017/0748','SAF/03/018/0503','SAF/03/017/0945','SAF/03/012/1052','SAF/03/010/0284','SAF/03/028/0304','SAF/03/017/0709','SAF/03/006/0536','SAF/03/036/0889','SAF/03/017/0656','SAF/03/015/0535','SAF/03/015/0479','SAF/03/015/0483','SAF/03/028/0505','SAF/03/033/0963','SAF/03/023/0515','SAF/03/023/0314','SAF/03/016/0672','SAF/03/036/0829','SAF/03/010/0337','SAF/03/010/0349','SAF/03/008/0938','SAF/03/017/0738','SAF/03/016/0697','SAF/03/017/0776','SAF/03/017/0775','SAF/03/017/0771','SAF/03/016/0445','SAF/03/036/1241','SAF/03/003/0628','SAF/03/036/1226','SAF/03/036/0470','SAF/03/028/0666','SAF/03/028/0664','SAF/03/015/1141','SAF/03/033/0996','SAF/03/028/0643','SAF/03/017/0518','SAF/03/017/0598','SAF/03/032/1432','SAF/03/036/1156','SAF/03/015/0227','SAF/03/036/0927','SAF/03/015/0237','SAF/03/015/0216','SAF/03/015/0269','SAF/03/017/0328','SAF/03/018/0261','SAF/03/016/0152','SAF/03/017/0177','SAF/03/017/0583','SAF/03/032/0943','SAF/03/038/1380','SAF/03/038/1215','SAF/03/038/1538','SAF/03/018/0377','SAF/03/018/0393','SAF/03/017/0135','SAF/03/023/1254','SAF/03/038/0643','SAF/03/016/0204','SAF/03/015/0364','SAF/03/018/0414','SAF/03/008/0752','SAF/03/016/0500','SAF/03/017/0480','SAF/03/015/0488','SAF/03/017/0320','SAF/03/015/0512','SAF/03/038/0703','SAF/03/016/0461','SAF/03/017/0455','SAF/03/017/0457','SAF/03/017/0454','SAF/03/018/0253','SAF/03/010/0082','SAF/03/015/0665','SAF/03/015/0276','SAF/03/015/0341','SAF/03/018/0174','SAF/03/035/0589','SAF/03/018/0095','SAF/03/006/0326','SAF/03/018/0303','SAF/03/015/0197','SAF/03/018/0177','SAF/03/018/0205','SAF/03/017/0083','SAF/03/017/0154','SAF/03/017/0148','SAF/03/017/0145','SAF/03/034/0259','SAF/03/015/0174','SAF/03/018/0085','SAF/03/015/0217','SAF/03/015/0054','SAF/03/015/0111','SAF/03/017/0044'];
        }
        $data="'".implode("','",$data)."'";
       // $data=str_replace('[','',$data);
       // $data=str_replace(']','',$data);
       // print_r($data);
        $sql = "SELECT tbl_level_pending_dtl.id, tbl_level_pending_dtl.saf_dtl_id, tbl_prop_type_mstr.property_type, view_ward_mstr.ward_no, tbl_saf_dtl.saf_no, tbl_saf_dtl.apply_date, owner_dtl.owner_name,
         owner_dtl.mobile_no, tbl_saf_dtl.assessment_type, tbl_level_pending_dtl.forward_date, tbl_level_pending_dtl.forward_time, tbl_level_pending_dtl.remarks FROM tbl_level_pending_dtl
          INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.ward_mstr_id 
          IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159) INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id, string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name, string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no FROM tbl_saf_owner_detail GROUP BY tbl_saf_owner_detail.saf_dtl_id ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id WHERE tbl_saf_dtl.status=1 AND tbl_level_pending_dtl.verification_status='2' AND tbl_level_pending_dtl.receiver_user_type_id=11
              AND tbl_level_pending_dtl.status='1' AND saf_no IN ($data) AND saf_pending_status=2 ORDER BY tbl_level_pending_dtl.id ASC limit 2";
       // print_r($sql);
       $results = $this->db->query($sql)->getResultArray();
     //  print_r(count($results));
       //exit();
        foreach ($results as $result) {
            $saf = $result['saf_dtl_id'];
            $id = md5($result['id']);

            $safdoc = new SafDoc();
            $safdoc->re_send_rmc2($saf, $id);
        }
        print_r($result);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function Sam_to_fam_generate2024()
    {
       //exit();
        date_default_timezone_set('ASIA/KOLKATA');
//        $sql = "SELECT distinct saf.id as saf_id,saf.saf_no,saf.prop_type_mstr_id,saf.road_type_mstr_id,saf.area_of_plot,saf.ward_mstr_id,
//        saf.is_mobile_tower,saf.tower_area,saf.tower_installation_date,
//        saf.is_hoarding_board,saf.hoarding_area,saf.hoarding_installation_date,
//        saf.is_petrol_pump,saf.under_ground_area,saf.petrol_pump_completion_date,
//        saf.is_water_harvesting,saf.zone_mstr_id,saf.percentage_of_property_transfer,saf.new_ward_mstr_id,saf.assessment_type from tbl_saf_dtl saf
//        join (select * from tbl_saf_memo_dtl where memo_type='SAM') memo ON memo.saf_dtl_id = saf.id
//        left join (select * from tbl_saf_memo_dtl where memo_type='FAM') memo1 ON memo1.saf_dtl_id = saf.id
//		join (select saf_dtl_id from tbl_level_pending_dtl where receiver_user_type_id=5 and verification_status=1) level on level.saf_dtl_id=saf.id
//        join(
//			select v.saf_dtl_id from tbl_field_verification_dtl v
//			join tbl_saf_dtl s on v.saf_dtl_id=s.id
//			and v.prop_type_mstr_id=s.prop_type_mstr_id
//			and v.road_type_mstr_id=s.road_type_mstr_id
//			and v.area_of_plot=s.area_of_plot
//			and v.is_mobile_tower=s.is_mobile_tower
//			and v.is_hoarding_board=s.is_hoarding_board
//			and v.is_petrol_pump=s.is_petrol_pump
//			and v.is_water_harvesting=s.is_water_harvesting and v.verified_by='AGENCY TC' group by v.saf_dtl_id
//		) veri on veri.saf_dtl_id=saf.id
//
//		where (saf.apply_date BETWEEN '2016-04-01' and '2022-04-30') and saf_pending_status!=1 and (saf.assessment_type='Reassessment') and saf.holding_no!='' and memo1.id is null order by saf.id asc";

        $sql = "SELECT distinct saf.id as saf_id,saf.saf_no,saf.prop_type_mstr_id,saf.road_type_mstr_id,saf.area_of_plot,saf.ward_mstr_id,saf.previous_holding_id,
        saf.is_mobile_tower,saf.tower_area,saf.tower_installation_date,
        saf.is_hoarding_board,saf.hoarding_area,saf.hoarding_installation_date,
        saf.is_petrol_pump,saf.under_ground_area,saf.petrol_pump_completion_date,
        saf.is_water_harvesting,saf.zone_mstr_id,saf.percentage_of_property_transfer,saf.new_ward_mstr_id,saf.assessment_type from tbl_saf_dtl saf 
       -- left join (select * from tbl_saf_memo_dtl where memo_type='SAM') memo ON memo.saf_dtl_id = saf.id
       -- left join (select * from tbl_saf_memo_dtl where memo_type='FAM') memo1 ON memo1.saf_dtl_id = saf.id
		--left join (select saf_dtl_id from tbl_level_pending_dtl where receiver_user_type_id=5 and verification_status=1) level on level.saf_dtl_id=saf.id
        -- left join(
		-- 	select v.saf_dtl_id from tbl_field_verification_dtl v
		-- 	join tbl_saf_dtl s on v.saf_dtl_id=s.id
		-- 	and v.prop_type_mstr_id=s.prop_type_mstr_id
		-- 	and v.road_type_mstr_id=s.road_type_mstr_id
		-- 	and v.area_of_plot=s.area_of_plot
		-- 	and v.is_mobile_tower=s.is_mobile_tower
		-- 	and v.is_hoarding_board=s.is_hoarding_board
		-- 	and v.is_petrol_pump=s.is_petrol_pump
		-- 	and v.is_water_harvesting=s.is_water_harvesting and v.verified_by='AGENCY TC' group by v.saf_dtl_id
		-- ) veri on veri.saf_dtl_id=saf.id
		where saf_no IN ('SAF/03/038/00694')
		and saf_pending_status!=1 AND saf.status=1 order by saf.id asc";
        $result = $this->db->query($sql)->getResultArray();

       /*
       		where saf_no IN ('SAF/02/034/02012','SAF/02/037/02186','SAF/02/034/02009','SAF/02/049/00861','SAF/02/049/00860','SAF/02/049/00862','SAF/02/038/01706','SAF/02/037/02181','SAF/02/037/02183','SAF/02/037/02185','SAF/02/031/02086','SAF/02/038/01693','SAF/02/037/02171','SAF/02/038/01680','SAF/02/031/02085','SAF/02/034/01994','SAF/02/033/01939','SAF/02/033/01938','SAF/02/048/01795','SAF/02/035/01470','SAF/02/034/01985','SAF/02/038/01654','SAF/02/004/03592','SAF/02/036/01191','SAF/02/038/01625','SAF/02/038/01609','SAF/02/036/01190','SAF/02/037/02135','SAF/02/037/02158','SAF/02/038/01658','SAF/02/048/01791','SAF/02/038/01653','SAF/02/036/01193','SAF/02/033/01935','SAF/02/049/00840','SAF/02/048/01787','SAF/02/038/01597','SAF/02/036/01195','SAF/02/037/02112','SAF/02/038/01605','SAF/02/035/01463','SAF/02/037/02145','SAF/02/048/01779','SAF/02/048/01777','SAF/02/049/00821','SAF/02/035/01424','SAF/02/034/01931','SAF/02/038/01535','SAF/02/047/00236','SAF/02/050/00117','SAF/02/038/01505','SAF/02/053/00197','SAF/02/052/00258','SAF/02/054/00536','SAF/02/049/00825','SAF/02/049/00827','SAF/02/038/01574','SAF/02/037/02101','SAF/02/049/00828','SAF/02/049/00829','SAF/02/055/00269','SAF/02/055/00270','SAF/02/055/00271','SAF/02/054/00600','SAF/02/055/00272','SAF/02/049/00830','SAF/02/037/01951','SAF/02/054/00353','SAF/02/054/00591','SAF/02/052/00336','SAF/02/038/01488','SAF/02/048/01771','SAF/02/038/01640','SAF/02/054/00596','SAF/02/052/00213','SAF/02/054/00569','SAF/02/049/00832','SAF/02/035/1350','SAF/02/054/00593','SAF/02/048/01774','SAF/02/050/00119','SAF/02/031/02071','SAF/02/038/01624','SAF/02/037/02124','SAF/02/052/00327','SAF/02/038/01612','SAF/02/037/02121','SAF/02/053/00242','SAF/02/037/01914','SAF/02/029/03342','SAF/02/038/9581','SAF/02/054/00548','SAF/02/028/00780','SAF/02/029/03331','SAF/02/028/00763','SAF/02/041/00126','SAF/02/054/00563','SAF/02/041/00124','SAF/02/039/00044','SAF/02/029/03337','SAF/02/054/00553','SAF/02/029/6414','SAF/02/041/00142','SAF/02/042/00139','SAF/02/052/00324','SAF/02/037/02111','SAF/02/028/00785','SAF/02/028/00786','SAF/02/036/01180','SAF/02/054/00559','SAF/02/048/01740','SAF/02/048/01731','SAF/02/036/01178','SAF/02/048/01727','SAF/02/036/01143','SAF/02/026/1688','SAF/02/035/01442','SAF/02/001/01770','SAF/02/029/03340','SAF/02/027/3964','SAF/02/043/00952','SAF/02/037/02109','SAF/02/035/01440','SAF/02/003/2097','SAF/02/011/2273','SAF/02/045/00292','SAF/02/053/00201','SAF/02/029/03339','SAF/02/004/03700','SAF/02/003/2209','SAF/02/002/6025','SAF/02/002/0198','SAF/02/026/1945','SAF/02/45/4/0132','SAF/02/052/00315','SAF/02/002/5536','SAF/02/002/6736','SAF/02/048/01738','SAF/02/048/01742','SAF/02/048/01736','SAF/02/004/11997','SAF/02/001/1837','SAF/02/048/01722','SAF/02/004/03692','SAF/02/014/01481','SAF/02/048/01729','SAF/02/047/00221','SAF/02/004/03694','SAF/02/050/00104','SAF/02/029/03338','SAF/02/038/01589','SAF/02/021/02011','SAF/02/003/2099','SAF/02/003/1734','SAF/02/021/02095','SAF/02/003/01752','SAF/02/029/03324','SAF/02/033/01914','SAF/02/037/02106','SAF/02/035/01435','SAF/02/055/00267','SAF/02/055/00266','SAF/02/054/00546','SAF/02/054/00547','SAF/02/013/1609','SAF/02/055/00265','SAF/02/054/4879','SAF/02/045/1874','SAF/02/046/0865','SAF/02/028/00777','SAF/02/002/03433','SAF/02/037/02104','SAF/02/005/00774','SAF/02/005/00768','SAF/02/005/00769','SAF/02/012/00855','SAF/02/036/01181','SAF/02/038/01578','SAF/02/028/00751','SAF/02/028/00743','SAF/02/013/01721','SAF/02/048/01698','SAF/02/045/00330','SAF/02/045/00331','SAF/02/031/02068','SAF/02/022/02173','SAF/02/008/03455','SAF/02/054/00535','SAF/02/028/00773','SAF/02/029/03334','SAF/02/030/01641','SAF/02/034/01972','SAF/02/028/00764','SAF/02/028/00766','SAF/02/043/00935','SAF/02/037/01923','SAF/02/037/02096','SAF/02/034/01970','SAF/02/004/10392','SAF/02/001/3859','SAF/02/004/10523','SAF/02/002/03256','SAF/02/002/03241','SAF/02/028/00758','SAF/02/031/02062','SAF/02/054/00463','SAF/02/04A/0057','SAF/02/004/9511','SAF/02/037/02094','SAF/02/032/01724','SAF/02/052/00232','SAF/02/038/01480','SAF/02/031/02057','SAF/02/008/03450','SAF/02/050/00075','SAF/02/029/03319','SAF/02/010/01628','SAF/02/002/03420','SAF/02/029/03322','SAF/02/051/00057','SAF/02/038/01568','SAF/02/029/03318','SAF/02/038/01563','SAF/02/032/01711','SAF/02/035/1443','SAF/02/029/03263','SAF/02/022/02089','SAF/02/048/01688','SAF/02/052/00307','SAF/02/007/01583','SAF/02/052/00285','SAF/02/028/00727','SAF/02/046/00057','SAF/02/045/00282','SAF/02/037/01962','SAF/02/008/03288','SAF/02/054/00420','SAF/02/038/01558','SAF/02/038/01556','SAF/02/002/03372','SAF/02/028/00749','SAF/02/038/01448','SAF/02/028/00750','SAF/02/022/02082','SAF/02/036/01168','SAF/02/038/01554','SAF/02/038/01551','SAF/02/050/00095','SAF/02/005/00761','SAF/02/038/01549','SAF/02/005/00759','SAF/02/001/01738','SAF/02/052/00302','SAF/02/043/00934','SAF/02/043/00917','SAF/02/044/00069','SAF/02/038/01501','SAF/02/055/00246','SAF/02/029/03302','SAF/02/038/01496','SAF/02/052/00294','SAF/02/039/00029','SAF/02/004/03636','SAF/02/043/00907','SAF/02/029/03291','SAF/02/030/01626','SAF/02/034/01958','SAF/02/036/01160','SAF/02/048/01674','SAF/02/045/00303','SAF/02/036/01124','SAF/02/034/01948','SAF/02/037/02072','SAF/02/004/03504','SAF/02/001/01743','SAF/02/048/01673','SAF/02/031/02048','SAF/02/036/01157','SAF/02/052/00280','SAF/02/034/01944','SAF/02/052/00288','SAF/02/052/00286','SAF/02/004/03620','SAF/02/005/00750','SAF/02/037/02068','SAF/02/046/00056','SAF/02/008/5084','SAF/02/008/6523','SAF/02/037/7170','SAF/02/029/3740','SAF/02/029/3155','SAF/02/010/2565','SAF/02/029/3975','SAF/02/029/2817','SAF/02/011/2681','SAF/02/029/1833','SAF/02/029/2786','SAF/02/010/3001','SAF/02/029/1273','SAF/02/007/4100','SAF/02/029/2753','SAF/02/029/4252','SAF/02/029/2581','SAF/02/029/3038','SAF/02/029/4813','SAF/02/029/3210','SAF/02/010/2737','SAF/02/006/2651','SAF/02/029/2518','SAF/02/029/2505','SAF/02/029/2794','SAF/02/029/0630','SAF/02/009/1474','SAF/02/033/3141','SAF/02/004/03609','SAF/02/004/03544','SAF/02/052/00227','SAF/02/026/00803','SAF/02/044/00047','SAF/02/052/00278','SAF/02/029/2776','SAF/02/029/2276','SAF/02/029/1111','SAF/02/029/0436','SAF/02/001/01726','SAF/02/047/00200','SAF/02/048/01654','SAF/02/045/00268','SAF/02/013/01716','SAF/02/029/03190','SAF/02/014/01424','SAF/02/037/02051','SAF/02/039/00036','SAF/02/002/11563','SAF/02/008/03297','SAF/02/002/03367','SAF/02/004/03372','SAF/02/033/01881','SAF/02/028/00661','SAF/02/008/03283','SAF/02/001/01721','SAF/02/041/00091','SAF/02/010/01548','SAF/02/023/01631','SAF/02/003/01782','SAF/02/005/00722','SAF/02/037/02045','SAF/02/053/00161','SAF/02/033/01891','SAF/02/029/03252','SAF/02/029/03271','SAF/02/002/03382','SAF/02/023/01641','SAF/02/040/00023','SAF/02/002/03322','SAF/02/002/03376','SAF/02/002/03377','SAF/02/002/03378','SAF/02/037/02052','SAF/02/028/00654','SAF/02/048/01623','SAF/02/037/02047','SAF/02/007/01457','SAF/02/005/00732','SAF/02/049/00796','SAF/02/049/00795','SAF/02/029/03264','SAF/02/004/03543','SAF/02/001/01675','SAF/02/001/01707','SAF/02/002/03365','SAF/02/050/00085','SAF/02/004/03571','SAF/02/004/03572','SAF/02/011/01030','SAF/02/004/03564','SAF/02/004/03565','SAF/02/004/03566','SAF/02/002/11778','SAF/02/002/03368','SAF/02/037/01975','SAF/02/002/03363','SAF/02/012/00798','SAF/02/003/01805','SAF/02/007/01491','SAF/02/002/03345','SAF/02/038/01482','SAF/02/002/3235','SAF/02/002/03350','SAF/02/003/01804','SAF/02/011/01029','SAF/02/043/00903','SAF/02/010/01581','SAF/02/014/4027','SAF/02/003/01795','SAF/02/014/4125','SAF/02/002/03357','SAF/02/008/03384','SAF/02/038/01461','SAF/02/001/4002','SAF/02/014/4141','SAF/02/037/02025','SAF/02/037/02024','SAF/02/011/4479','SAF/02/002/03355','SAF/02/043/00901','SAF/02/014/4307','SAF/02/014/4321','SAF/02/014/4140','SAF/02/034/01921','SAF/02/002/03354','SAF/02/036/01118','SAF/02/007/01357','SAF/02/003/01801','SAF/02/054/00425','SAF/02/008/03278','SAF/02/002/03353','SAF/02/028/00666','SAF/02/029/6725','SAF/02/012/00791','SAF/02/004/03382','SAF/02/037/02020','SAF/02/007/01331','SAF/02/036/01109','SAF/02/052/00218','SAF/02/001/01685','SAF/02/014/4068','SAF/02/052/00216','SAF/02/031/02020','SAF/02/052/00215','SAF/02/010/01572','SAF/02/011/4244','SAF/02/011/4265','SAF/02/004/03405','SAF/02/054/00418','SAF/02/011/4268','SAF/02/011/4277','SAF/02/011/4281','SAF/02/011/4283','SAF/02/052/00209','SAF/02/001/01666','SAF/02/034/01908','SAF/02/002/03330','SAF/02/052/00203','SAF/02/052/00186','SAF/02/012/00774','SAF/02/010/01566','SAF/02/038/01431','SAF/02/050/00079','SAF/02/004/03376','SAF/02/005/00696','SAF/02/004/03403','SAF/02/004/03432','SAF/02/052/00192','SAF/02/052/00195','SAF/02/052/00194','SAF/02/052/00193','SAF/02/032/01692','SAF/02/051/00054','SAF/02/038/01424','SAF/02/050/00082','SAF/02/004/03425','SAF/02/035/01372','SAF/02/028/00658','SAF/02/037/01964','SAF/02/038/01388','SAF/02/038/01414','SAF/02/005/6228','SAF/02/049/00808','SAF/02/006/01510','SAF/02/054/00395','SAF/02/037/01981','SAF/02/052/00185','SAF/02/046/00049','SAF/02/038/01389','SAF/02/035/01368','SAF/02/049/00807','SAF/02/037/01969','SAF/02/003/01755','SAF/02/017/00880','SAF/02/036/01103','SAF/02/001/01642','SAF/02/038/01392','SAF/02/001/01652','SAF/02/003/01758','SAF/02/002/03292','SAF/02/017/00870','SAF/02/017/00873','SAF/02/012/00764','SAF/02/038/01391','SAF/02/052/00154','SAF/02/046/00047','SAF/02/031/01988','SAF/02/046/00045','SAF/02/020/02185','SAF/02/007/01333','SAF/02/031/01977','SAF/02/017/00872','SAF/02/004/03366','SAF/02/004/03377','SAF/02/046/00039','SAF/02/038/1337','SAF/02/034/5029','SAF/02/029/7009','SAF/02/049/3382','SAF/02/034/4979','SAF/02/031/5958','SAF/02/038/9820','SAF/02/051/0500','SAF/02/043/6119','SAF/02/051/0512','SAF/02/051/0511','SAF/02/038/9858','SAF/02/051/0510','SAF/02/037/13201','SAF/02/035/4761','SAF/02/049/3396','SAF/02/052/3479','SAF/02/037/13205','SAF/02/052/3474','SAF/02/029/6388','SAF/02/029/4586','SAF/02/023/2875','SAF/02/007/6756','SAF/02/008/5789','SAF/02/029/5844','SAF/02/002/4185','SAF/02/010/3468','SAF/02/011/3701','SAF/02/029/4603','SAF/02/038/6426',
        'SAF/02/037/9161','SAF/02/037/10054','SAF/02/037/6079','SAF/02/004/9814','SAF/02/005/4592','SAF/02/005/4590','SAF/02/012/2300','SAF/02/012/2341','SAF/02/029/5503','SAF/02/037/7116','SAF/02/006/3063','SAF/02/006/3062','SAF/02/011/3462','SAF/02/029/4270','SAF/02/019/4537','SAF/02/019/4536','SAF/02/012/2271','SAF/02/019/3307','SAF/02/010/2780','SAF/02/029/3619','SAF/02/010/3101','SAF/02/037/5394','SAF/02/010/3185','SAF/02/029/3365','SAF/02/029/4722','SAF/02/029/2413','SAF/02/037/6407','SAF/02/029/3954','SAF/02/007/5439','SAF/02/037/4794','SAF/02/029/5388','SAF/02/011/2585','SAF/02/029/2209','SAF/02/029/2206','SAF/02/008/5121','SAF/02/037/6240','SAF/02/029/5266','SAF/02/029/5391','SAF/02/037/4577','SAF/02/037/6247','SAF/02/037/6128','SAF/02/029/5321','SAF/02/029/2700','SAF/02/029/4115','SAF/02/037/9311','SAF/02/029/2337','SAF/02/029/2166','SAF/02/029/1001','SAF/02/029/5218','SAF/02/029/3024','SAF/02/029/1930','SAF/02/029/1126','SAF/02/011/2855','SAF/02/029/3702','SAF/02/029/2421','SAF/02/029/1684','SAF/02/029/2846','SAF/02/029/4385','SAF/02/029/2908','SAF/02/029/1329','SAF/02/029/4721','SAF/02/029/4752','SAF/02/012/1820','SAF/02/029/2372','SAF/02/029/1311','SAF/02/029/1487','SAF/02/029/2512','SAF/02/029/5231','SAF/02/029/2543','SAF/02/011/2783','SAF/02/029/2343','SAF/02/029/2177','SAF/02/029/0736','SAF/02/029/2107','SAF/02/029/1569','SAF/02/029/5354','SAF/02/029/5349','SAF/02/029/1978','SAF/02/029/3576','SAF/02/029/2342','SAF/02/029/3801','SAF/02/011/2250','SAF/02/011/2244','SAF/02/011/2858','SAF/02/037/6938','SAF/02/037/7031','SAF/02/029/1774','SAF/02/037/5042','SAF/02/029/3732','SAF/02/029/2940','SAF/02/011/2397','SAF/02/029/1467','SAF/02/029/3650','SAF/02/029/3971','SAF/02/029/0229','SAF/02/029/2198','SAF/02/029/2130','SAF/02/029/2514','SAF/02/029/2031','SAF/02/029/2361','SAF/02/029/2719','SAF/02/029/4777','SAF/02/029/0979','SAF/02/029/1398','SAF/02/029/1886','SAF/02/029/3495','SAF/02/029/0675','SAF/02/029/2916','SAF/02/029/4827','SAF/02/011/2543','SAF/02/029/3845','SAF/02/029/5103','SAF/02/037/5417','SAF/02/029/1701','SAF/02/029/2159','SAF/02/029/2831','SAF/02/029/3169','SAF/02/029/4189','SAF/02/008/5987','SAF/02/029/3098','SAF/02/037/5031','SAF/02/029/1069','SAF/02/029/0975','SAF/02/029/0732','SAF/02/037/4785','SAF/02/037/5845','SAF/02/008/4421','SAF/02/029/5242','SAF/02/008/7393','SAF/02/037/6216','SAF/02/037/5122','SAF/02/037/5515','SAF/02/037/5507','SAF/02/011/1658','SAF/02/037/6774','SAF/02/037/3961','SAF/02/037/9154','SAF/02/037/9095','SAF/02/037/7120','SAF/02/037/5280','SAF/02/037/7126','SAF/02/037/6713','SAF/02/037/3254','SAF/02/037/7242','SAF/02/029/3008','SAF/02/037/8938','SAF/02/037/5349','SAF/02/008/3616','SAF/02/037/5393','SAF/02/037/7095','SAF/02/029/0461','SAF/02/037/4168','SAF/02/037/8937','SAF/02/029/3996','SAF/02/003/3487','SAF/02/029/1447','SAF/02/011/2476','SAF/02/008/5413','SAF/02/007/4164','SAF/02/037/4981','SAF/02/037/9173','SAF/02/037/6433','SAF/02/033/2889','SAF/02/007/4800','SAF/02/029/4053','SAF/02/037/7123','SAF/02/029/3042','SAF/02/029/1665','SAF/02/029/2944','SAF/02/037/6989','SAF/02/009/1025','SAF/02/037/4702','SAF/02/007/4589','SAF/02/029/2790','SAF/02/010/1882','SAF/02/009/1837','SAF/02/007/3785','SAF/02/014/3195','SAF/02/011/2114','SAF/02/029/5095','SAF/02/029/4694','SAF/02/029/2432','SAF/02/011/3333','SAF/02/011/2988','SAF/02/011/2995','SAF/02/029/4564','SAF/02/011/3304','SAF/02/011/2737','SAF/02/011/2843','SAF/02/003/3393','SAF/02/011/2258','SAF/02/012/0973','SAF/02/004/9060','SAF/02/003/2794','SAF/02/004/7360','SAF/02/004/9001','SAF/02/002/8155','SAF/02/001/2581','SAF/02/001/2552','SAF/02/002/5850','SAF/02/002/8077','SAF/02/001/1029','SAF/02/002/8268','SAF/02/003/3450','SAF/02/005/4027','SAF/02/007/2918','SAF/02/008/6168','SAF/02/037/3702','SAF/02/009/1693','SAF/02/037/6068','SAF/02/032/2748','SAF/02/029/2465','SAF/02/002/6512','SAF/02/002/6588','SAF/02/010/2589','SAF/02/037/5479','SAF/02/029/2979','SAF/02/002/3431','SAF/02/037/3298','SAF/02/037/4426','SAF/02/002/6391','SAF/02/001/2386','SAF/02/002/7708','SAF/02/002/6367','SAF/02/037/4101','SAF/02/002/7745','SAF/02/001/1006','SAF/02/001/1753','SAF/02/037/6329','SAF/02/037/3839','SAF/02/008/4305','SAF/02/029/3766','SAF/02/029/1845','SAF/02/032/2093','SAF/02/037/5852','SAF/02/029/2037','SAF/02/008/5315','SAF/02/037/5725','SAF/02/008/5083','SAF/02/008/5375','SAF/02/008/5403','SAF/02/029/3883','SAF/02/029/3994','SAF/02/007/3083','SAF/02/037/2880','SAF/02/029/2931','SAF/02/029/2279','SAF/02/007/3431','SAF/02/002/6312','SAF/02/004/6799','SAF/02/004/4766','SAF/02/001/1166','SAF/02/001/1701'
        ,'SAF/02/029/0844','SAF/02/037/3071','SAF/02/037/3322','SAF/02/037/3998','SAF/02/031/2268','SAF/02/029/1807','SAF/02/037/4379','SAF/02/47/3/0565','SAF/02/47/1/1944','SAF/02/036/5091'
       ,'SAF/02/030/4671','SAF/02/035/4487','SAF/02/023/3187','SAF/02/031/5782','SAF/02/054/5078','SAF/02/036/5424','SAF/02/035/4617','SAF/02/037/12768','SAF/02/054/5201','SAF/02/033/4673'
       ,'SAF/02/024/3436','SAF/02/023/3331')
*/
       print_r(implode(' ',array_column($result,'saf_no')));
        // print_r($result);
     //   exit();

            //new asmt.
        //,'SAF/01/036/4973','SAF/01/036/4983','SAF/01/036/5665',
//        'SAF/01/052/03594'
//        ,'SAF/01/046/00937','SAF/01/054/05055','SAF/01/049/02467'
        //field verification agency tc to ulb tc
        foreach ($result as $res) {
            print_r($res['saf_no']);
            // exit();
            log_message('info','--'.$res['saf_no']);
           $field_verification = $this->db->table('tbl_field_verification_dtl');
            $verification = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])->where('status', 1); //data get
            $ulbTcCount = $verification->where('verified_by', 'ULB TC')->countAllResults();
            $verificationp = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])
                                                                ->where('status', 1)
                                                                ->where('verified_by', 'AGENCY TC')
                                                                ->orderBy('id', 'desc')
                                                                ->get()
                                                                ->getFirstRow();
            if($ulbTcCount == 0)
            {
                if(!isset($verificationp->verified_by))
                {
                    continue;
                }
                $ulbTc_id = 7;
                if($verificationp->verified_by=='AGENCY TC')
                {
                    $data = [
                        'saf_dtl_id' => $res['saf_id'],
                        'prop_type_mstr_id'  => $verificationp->prop_type_mstr_id,
                        'road_type_mstr_id'  => $verificationp->road_type_mstr_id,
                        'area_of_plot'  => $verificationp->area_of_plot,
                        'verified_by_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        'created_on'  => date('Y-m-d H:i:s'),
                        'status'  => $verificationp->status,
                        'ward_mstr_id'  => $verificationp->ward_mstr_id,
                        'is_mobile_tower'  => $verificationp->is_mobile_tower,
                        'tower_area'  => $verificationp->tower_area,
                        'tower_installation_date'  => $verificationp->tower_installation_date,
                        'is_hoarding_board'  => $verificationp->is_hoarding_board,
                        'hoarding_area'  => $verificationp->hoarding_area,
                        'hoarding_installation_date'  => $verificationp->hoarding_installation_date,
                        'is_petrol_pump'  => $verificationp->is_petrol_pump,
                        'under_ground_area'  => $verificationp->under_ground_area,
                        'petrol_pump_completion_date'  => $verificationp->petrol_pump_completion_date,
                        'is_water_harvesting'  => $verificationp->is_water_harvesting,
                        'verified_by'  => "ULB TC",
                        'zone_mstr_id'  => $verificationp->zone_mstr_id,
                        'percentage_of_property_transfer'  => $verificationp->percentage_of_property_transfer,
                        'new_ward_mstr_id'  => $verificationp->new_ward_mstr_id
                    ];

                    $field_verification->insert($data);
                    $verification_id = $this->db->insertID();

                    $field_floor_verification = $this->db->table('tbl_field_verification_floor_details');
                    $floor_verifications = $field_floor_verification->select('*')->where('field_verification_dtl_id', $verificationp->id)->get();
                    $floor_verifications = $floor_verifications->getResult();
                    foreach($floor_verifications as $floor)
                    {
                        $field_floor = $this->db->table('tbl_field_verification_floor_details');
                        $data1 = [
                            'field_verification_dtl_id' => $verification_id,
                            'saf_dtl_id' => $res['saf_id'],
                            'saf_floor_dtl_id'  => $floor->saf_floor_dtl_id,
                            'floor_mstr_id'  => $floor->floor_mstr_id,
                            'usage_type_mstr_id'  => $floor->usage_type_mstr_id,
                            'const_type_mstr_id'  => $floor->const_type_mstr_id,
                            'occupancy_type_mstr_id'  => $floor->occupancy_type_mstr_id,
                            'builtup_area'  => $floor->builtup_area,
                            'date_from'  => $floor->date_from,
                            'date_upto'  => $floor->date_upto,
                            'emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'status'  => $floor->status,
                            'carpet_area'  => $floor->carpet_area,
                            'verified_by'  => "ULB TC",
                            'created_on'  => date('Y-m-d H:i:s'),
                        ];
                        $field_floor->insert($data1);
                    
                    }
                }
            }



            // LEVEL ENTRY
            $level_pending = $this->db->table('tbl_level_pending_dtl');
            $level_bugfix_pending = $this->db->table('tbl_bugfix_level_pending_dtl');
            $pending_at_level = $level_pending->where('saf_dtl_id', $res['saf_id'])
                                                ->orderBy('id', 'DESC')
                                                ->get();
            $pending_at_level = $pending_at_level->getFirstRow();

            $pending_at_level_bug = $level_bugfix_pending->where('saf_dtl_id', $res['saf_id'])
                                                ->orderBy('id', 'DESC')
                                                ->get();
            $pending_at_level_bug = $pending_at_level_bug->getFirstRow();
            
            $dealing = 6;
            $aTc_id = 5;
            $ulbTc_id = 7;
            $section_incharge = 9;
            $eo = 10;
            $status = 0;
            $verification_status = 1;
            $assessment_type = $res['assessment_type'];
            $empDtlId = 0;
            $ward = $this->db->table('view_ward_mstr')->where('id', $res['ward_mstr_id'])->get();
            $ward = $ward->getFirstRow();
            $wardNo = $ward->ward_no;
            $data1 = array();

//            dd($ulbTcCount,$res['saf_no'],$assessment_type,$pending_at_level,$pending_at_level_bug);


            if($pending_at_level->receiver_user_type_id == '11')
            {
                $saf = $res['saf_id'];
                $id = md5($pending_at_level->id);
                // print_r($pending_at_level);
                // exit();
                $safdoc = new SafDoc();
                $safdoc->re_send_rmc2($saf, $id);
                $level_bugfix_pending_ = $this->db->table('tbl_level_pending_dtl')->where('saf_dtl_id', $res['saf_id'])
                ->orderBy('id', 'DESC')
                ->get()->getFirstRow();
                $pending_at_level=$level_bugfix_pending_;
             //   print_r($pending_at_level);
            }else{
              $pending_at_level=$pending_at_level_bug;
            }
            // print_r($level_bugfix_pending);

            // exit();
            if ($assessment_type == 'New Assessment' || $assessment_type == 'Mutation')
            {
                if(empty($verificationp->ward_mstr_id))
                {
                    $verificationp = $field_verification->select('*')->where('saf_dtl_id', $res['saf_id'])
                                                                ->where('status', 1)
                                                                ->where('verified_by', 'ULB TC')
                                                                ->orderBy('id', 'desc')
                                                                ->get()
                                                                ->getFirstRow();
                }
                $empDtlId = $this->getEmp($eo, $verificationp->ward_mstr_id);
                if($pending_at_level->receiver_user_type_id == '6')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $dealing,
                            'receiver_user_type_id'  => $aTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($dealing, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }
                else if($pending_at_level->receiver_user_type_id == '5')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }else if($pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }else if($pending_at_level->receiver_user_type_id == '10'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $eo,
                            'receiver_user_type_id'  => $eo,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto Approved",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($eo, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                }
                
            } else {
                $empDtlId = $this->getEmp($section_incharge, $verificationp->ward_mstr_id);
                if($pending_at_level->receiver_user_type_id == '6')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $dealing,
                            'receiver_user_type_id'  => $aTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($dealing, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }
                else if($pending_at_level->receiver_user_type_id == '5')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $aTc_id,
                            'receiver_user_type_id'  => $ulbTc_id,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($aTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '7')
                {
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $ulbTc_id,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($ulbTc_id, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                        ],
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
    
                    ];
                    
                }else if($pending_at_level->receiver_user_type_id == '9'){
                    $data1 = [
                        [
                            'saf_dtl_id' => $res['saf_id'],
                            'sender_user_type_id'  => $section_incharge,
                            'receiver_user_type_id'  => $section_incharge,
                            'forward_date'  => date('Y-m-d'),
                            'forward_time'  => date('H:i:s'),
                            'created_on'  => date('Y-m-d H:i:s'),
                            'status'  => $status,
                            'remarks'  => "Auto forward",
                            'verification_status'  => $verification_status,
                            'sender_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id),
                            'receiver_emp_details_id'  => $this->getEmp($section_incharge, $verificationp->ward_mstr_id)
                        ]
                    ];
                }
            }
//            dd($data1);
            log_message('info',$res['saf_no']);
            if($data1)
                $level_pending->insertBatch($data1);
                $level_bugfix_pending->insertBatch($data1);
                
            $updata = [
                'status' => 0,
                'verification_status'  => 1,
                'receiver_emp_details_id'  => $this->getEmp($pending_at_level->receiver_user_type_id, $verificationp->ward_mstr_id),
            ];
            
            $level_pending->where('id', $pending_at_level->id);
            $level_pending->update($updata);

            $level_bugfix_pending->where('id', $pending_at_level_bug->id);
            $level_bugfix_pending->update($updata);
            
            $input = ['saf_dtl_id' => $res['saf_id']];
            $propDtlId = $this->model_prop_dtl->getPropDtlIdBySafDtlId($input);
            if(isset($propDtlId['id'])){
                $prop_dtl_id = $propDtlId['id'];
                $holding_no=$propDtlId['holding_no'];
            }else{
                $propDtlId = $this->model_prop_dtl->getPropDetails($res['previous_holding_id']);
                $prop_dtl_id = $res['previous_holding_id'];
                $holding_no=$propDtlId;
            }
            //memo_entry
            $prop_tax = $this->db->table('tbl_prop_tax')->where('prop_dtl_id', $prop_dtl_id)->where('status', 1)->orderBy('id', 'DESC')->get();
            $prop_tax = $prop_tax->getFirstRow();
            if(!$prop_tax)
            {
                $prop_tax = $this->db->table('tbl_saf_tax')->where('saf_dtl_id', $res['saf_id'])->where('status', 1)->orderBy('id', 'DESC')->get();
                $prop_tax = $prop_tax->getFirstRow();
            }

            $memo_type = $this->db->table('tbl_saf_memo_dtl');
            $fy_year = $this->db->table('view_fy_mstr')->where('fy', $prop_tax->fyear)->where('status', 1)->orderBy('id', 'DESC')->get()->getFirstRow('array');
            $memoDate = [
                'saf_dtl_id' => $res['saf_id'],
                'fy_mstr_id'  => $fy_year['id'],
                'effect_quarter'  => $prop_tax->qtr,
                'arv'  => $prop_tax->arv,
                'quarterly_tax'  => ($prop_tax->holding_tax+$prop_tax->water_tax+$prop_tax->education_cess+$prop_tax->latrine_tax+$prop_tax->additional_tax),
                'emp_details_id'  => $empDtlId,
                'memo_type'  => "FAM",
                'holding_no'  => $holding_no,
                'fy'  => $prop_tax->fyear,
                'status'  => 1,
                'prop_dtl_id'  => $prop_dtl_id,
                'ward_mstr_id'  => $res['ward_mstr_id'],
                'created_on'  => date('Y-m-d H:i:s'),
            ];

            $memo_type->insert($memoDate);
            $memo_id=$this->db->insertID();
            
            if($memo_id > 0)
            {
                $new_memo_no = "FAM". "/". str_pad($wardNo, 3, "0", STR_PAD_LEFT). "/". $memo_id. "/". $prop_tax->fyear;
                $memo_type->set('memo_no', $new_memo_no);
                $memo_type->where('id', $memo_id);
                $memo_type->update();
            }

            $saf_dtl = $this->db->table('tbl_saf_dtl');
            $saf_dtl->set('saf_pending_status', 1);
            $saf_dtl->where('id', $res['saf_id']);
            $saf_dtl->update();

             echo "FAM generated successfully";
            
        }

        
    }

    public function genrateassesment(){
        $data=$_GET['safd'];
        $safdtls=explode(',',$data);
        print_r($safdtls);
        foreach($safdtls as $safdtl)
        {
            $famsql="select * from tbl_saf_memo_dtl where memo_type='FAM' AND prop_dtl_id IS NULL AND saf_dtl_id=".$safdtl;
            $result = $this->db->query($famsql)->getResultArray();

            // print_r($result);
            // exit();
            $samsql="select * from tbl_saf_memo_dtl where memo_type='SAM' AND saf_dtl_id=".$safdtl;
            $samcheck = $this->db->query($samsql)->getResultArray();
            $safcheck="select * from tbl_saf_dtl where assessment_type='New Assessment' AND id=".$safdtl; //tbl_saf_dtl.prop_dtl_id=0 AND
            $safcheck = $this->db->query($safcheck)->getResultArray();

            if(count($samcheck)==0 && $safcheck[0]['prop_dtl_id']==0)
            {
                $empDtlId = $this->getEmp(6, $safcheck[0]['ward_mstr_id']);
               
                $memo=$this->model_saf_memo_dtl->generate_assessment_memo($safdtl, $empDtlId);
                $genmemo_last_id=$memo["generate_assessment_memo"];
                $samdata="select * from tbl_saf_memo_dtl where memo_type='SAM' AND id=".$genmemo_last_id;
                $samd = $this->db->query($samdata)->getResultArray();
  
                $updata = [
                    'prop_dtl_id' => $samd[0]['prop_dtl_id'],
                    'holding_no' => $samd[0]['holding_no']
                ];
//
                $famupdate = $this->db->table('tbl_saf_memo_dtl');
                $famupdate->where('id', $result[0]['id']);
               // print_r($famupdate->get()->id);
                //exit();
                $famupdate->update($updata);

                print_r($updata);
            }
        }
    }
}
?>
