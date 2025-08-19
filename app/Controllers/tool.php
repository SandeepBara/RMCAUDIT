<?php 
namespace App\Controllers;
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
// use App\Models\model_capital_value_rate_multiulb;
use App\Models\model_apartment_details;
use App\Models\model_govt_saf_floor_dtl;
use App\Models\model_advance_mstr;

class tool extends Controller {

    protected $db;
    protected $db_system;
    protected $model_saf_dtl;
    protected $model_datatable;

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
    // protected $model_capital_value_rate_multiulb;
    protected $model_apartment_details;
    protected $model_govt_saf_floor_dtl;
    protected $model_advance_mstr;

    public function __construct()
    {
		// ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
		
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
        // $this->model_capital_value_rate_multiulb = new model_capital_value_rate_multiulb($this->db);
        $this->model_apartment_details = new model_apartment_details($this->db);
        $this->model_govt_saf_floor_dtl = new model_govt_saf_floor_dtl($this->db);
        $this->model_advance_mstr = new model_advance_mstr($this->db);
    }

    public function index()
    {

    }

    public function onlinePaymentForProperty()
    {
        
        $data = array();
        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            
            $pprop_dtl_id = $inputs['prop_id'];
            $pfy = $inputs['upto_fy'];
            $pqtr = $inputs['upto_qtr'];
            $ptotal_payable_amount = $inputs['total_payable_amount'];
            $ppayment_date = $inputs['payment_date'];
            
            if (isset($inputs["payment_submit"]) && !empty($pprop_dtl_id) && !empty($pfy) && !empty($pqtr) && !empty($ptotal_payable_amount) && !empty($ppayment_date)) {
                $sql ="SELECT * FROM prop_pay_now_online('".$pprop_dtl_id."', '".$pfy."', '".$pqtr."', '0', 'ONLINE', '', '".$ptotal_payable_amount."', '".$ppayment_date."')";
                $this->db->query($sql)->getResultArray();
            }

            flashToast("message", "Payment done!!!");
            return $this->response->redirect(base_url('tool/onlinePaymentForProperty'));
        }
        return view('tools/prop_online_payment', $data);
    }

    // public function AdvanceCreate()
    // {
        
    //     $data = array();
        
    //     if($this->request->getMethod()=='post')
    //     {
    //         $inputs = arrFilterSanitizeString($this->request->getVar());
            
    //         $pprop_dtl_id = $inputs['prop_id'];
    //         $transaction_no = $inputs['transaction_no'];
    //         $advance_amount = $inputs['advance_amount'];
    //         $remarks = $inputs['remarks'];
    //         $module = $inputs['module'];
            
    //         if (isset($inputs["payment_submit"]) && !empty($pprop_dtl_id) && !empty($transaction_no) && !empty($advance_amount) && !empty($remarks) && !empty($module)) {
                
    //             $result = $this->db->table('tbl_advance_mstr')->
    //             insert([
    //                 "prop_dtl_id"=>$pprop_dtl_id,
    //                 "amount"=>$advance_amount,
    //                 "transaction_id"=>$input["transaction_id"],
    //                 "created_on"=>$input["created_on"],
    //                 "reason"=>$input["reason"],
    //                 "module"=>"Property",
    //                 "adjust_by_emp_details_id"=>$input['emp_details_id']
    //             ]);
    //         }

    //         flashToast("message", "Payment done!!!");
    //         return $this->response->redirect(base_url('tool/onlinePaymentForProperty'));
    //     }
    //     return view('tools/advance_created', $data);
    // }


    // public function edit($prop_dtl_id_MD5)
    // {
    //     $data=(array)null;
    //     $Session = Session();
    //     if($this->request->getMethod()=='post')
    //     {

    //         $inputs = arrFilterSanitizeString($this->request->getVar());
            
    //         if(isset($inputs["save"]) && $inputs["save"]=="Save")
    //         {
    //             unset($inputs["save"]);
    //             $updated=$this->model_prop_dtl->updateProp($prop_dtl_id_MD5, $inputs);
    //             if($updated){
    //                 flashToast("message", "Property detail updated successfully");
    //             }
    //         }
    //         else if(isset($inputs["submit"]) && $inputs["submit"]=="Update")
    //         {
    //             unset($inputs["submit"]);
    //             $updated=$this->model_prop_owner_detail->UpdateOwner($inputs, $inputs["id"]);
    //             if($updated){
    //                 flashToast("message", "Owner detail updated successfully");
    //             }
    //         }
            
    //     }
    //     $prop = $this->model_prop_dtl->get_prop_full_details($prop_dtl_id_MD5);
    //     $prop = $prop['get_prop_full_details'];
    //     $data=json_decode($prop, true);
        
        
    //     $data["ulb"]=$Session->get("ulb_dtl");
    //     $data["emp_details"]=$Session->get("emp_details");
    //     $data["ward_list"]=$this->model_ward_mstr->getWardList(["ulb_mstr_id"=> $data["ulb"]["ulb_mstr_id"]]);
    //     // print_var($data);
    //     // return;
        
    //     return view('property/edit_prop', $data);
        
    // }

}