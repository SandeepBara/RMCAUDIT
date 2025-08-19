<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\WaterConsumerInitialMeterReadingModel;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterConsumerTaxModel;
use App\Models\WaterFixedMeterRateModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\model_water_consumer;
use App\Models\WaterRateChartModel;
use App\Models\WaterDemandPenaltyMaster;
use App\Models\WaterRevisedMeterRateModel;
use App\Models\WaterMeterRateCalculationModel;
use App\Models\model_water_sms_log;
use App\Models\water_consumer_details_model;
use App\Models\model_water_reading_doc;
use Exception;

class WaterGenerateDemand extends AlphaController
{
    protected $db;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $emp_id;
    protected $ulb_type_id;
    protected $initial_meter_reading;
    protected $consumer_tax_model;
    protected $consumer_demand_model;
    protected $revised_meter_rate_model;
    protected $meter_rate_calc_model;
    protected $model_water_sms_log;
    protected $consumer_details_model;
    protected $water_reading_doc;
    public function __construct()
    {

        parent::__construct();
        helper(['db_helper', 'form','form_helper','sms_helper']);
        $session = session();
        $ulb_details = $session->get('ulb_dtl')??getUlbDtl();
        $this->ulb_type_id = $ulb_details['ulb_type_id'];
        $this->ulb_details = $ulb_details;
        $emp_details_id = $session->get('emp_details');
        $this->emp_id = $emp_details_id['id'];
        
        if ($db_name = dbConfig("water")) {
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name);
        }
        /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->initial_meter_reading = new WaterConsumerInitialMeterReadingModel($this->db);
        $this->meter_status_model = new WaterMeterStatusModel($this->db);
        $this->consumer_tax_model = new WaterConsumerTaxModel($this->db);
        $this->fixed_meter_rate_model = new WaterFixedMeterRateModel($this->db);
        $this->consumer_demand_model = new WaterConsumerDemandModel($this->db);
        $this->model_water_consumer = new model_water_consumer($this->db);
        $this->rate_chart_model = new WaterRateChartModel($this->db);
        $this->demand_penalty_master = new WaterDemandPenaltyMaster($this->db);
        $this->revised_meter_rate_model = new WaterRevisedMeterRateModel($this->db);
        $this->meter_rate_calc_model = new WaterMeterRateCalculationModel($this->db);
        $this->water_sms_log = new model_water_sms_log($this->db);
        $this->consumer_details_model=new water_consumer_details_model($this->db);        
        $this->water_reading_doc = new model_water_reading_doc($this->db);
    }

    // call from waterupdateconsumerconnection controller
    public function tax_generation($consumer_id, $upto_date, $final_meter_reading,$file=null)
    {

        return $this->generate_demand($consumer_id, $upto_date, $final_meter_reading,$file);
    }

    public function generate_demand($consumer_id, $upto_date, $final_reading = 0,$file=null)
    {
        $demand_id = false;
        $penalty = 0;
        // $get_demanddetails = $this->consumer_demand_model->getLastDemand(md5($consumer_id));
        $get_demanddetails = $this->consumer_demand_model->getLastDemand2($consumer_id);
        $last_demand_upto = $get_demanddetails['demand_upto'];
        $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $area_sqmt = $consumer_details['area_sqmt'];
        $ward_mstr_id = $consumer_details['ward_mstr_id'];
        $property_type_id = $consumer_details['property_type_id'];
        $category = !empty(trim($consumer_details['category']))?trim($consumer_details['category']):"APL";
        $ulb_details = session()->get('ulb_dtl')??getUlbDtl();
        $ulb_short_name = substr($ulb_details['city'], 0, 3);
        $ward_no = $this->model_ward_mstr->getWardNoById(['ward_mstr_id' => $ward_mstr_id]);
        $ward_no = isset($ward_no['ward_no']) ? $ward_no['ward_no'] : 0;
        $demand_no = $ulb_short_name . str_pad($ward_no, 3, '0', STR_PAD_LEFT) . str_pad($this->emp_id, 5, '0', STR_PAD_LEFT) . '/';
        // if(in_array(strtoupper(trim($category)),['BPL']))
        // {
        //      return null;
        //      die;   
        // } 
        $generation_date = date('Y-m-d');
        if ((!empty($get_demanddetails) and $last_demand_upto == "") or $property_type_id <= 0 or $consumer_details['area_sqmt'] <= 0) {

            flashToast("error", "Update your area or property type!!!");

            //echo $consumer_id;
            //die("Contact to Admin");
            //return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/'.md5($consumer_id)));
            //exit();
        }

        $prev_connection_details = $this->meter_status_model->getLastConnectionDetails($consumer_details['id']);
        if(empty($prev_connection_details) || $prev_connection_details['connection_date']=='')
        {
            flashToast("error", "Connection Date Not Found!!!");
            return;
        }
        if ($last_demand_upto == "") {
            $last_demand_upto = $prev_connection_details['connection_date'];
            $demand_from = $last_demand_upto;
        } else {
            $demand_from = date('Y-m-d', strtotime($last_demand_upto . "+1 days"));
        }

        $prev_conn_date = $prev_connection_details['connection_date'];
        if ($prev_connection_details['connection_type'] == 3) // fixed demand generate
        {            
            $fixed_rate_details = $this->fixed_meter_rate_model->getLatestFixedRateCharge($property_type_id, $area_sqmt, $demand_from);
            $rate_effect_details = $this->fixed_meter_rate_model->getFixedRateEffectBetweenDemandGeneration($property_type_id, $area_sqmt, $demand_from);

            if (empty($fixed_rate_details)) {
                $demand_from = $rate_effect_details[0]['effective_date'];
            }

            $fixed_amount = $fixed_rate_details['amount'];
            if (!empty($rate_effect_details)) {

                $i = $demand_from; // initialize i with demand from
                if ($upto_date == "") {
                    $to_date = date("Y-m-t", strtotime("-1 months"));
                } else {
                    //  $to_date=$upto_date;
                    $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
                }

                $upto_date = $to_date;
                $j = 1;
                $flag = 0;
                $rate_array = [];
                $last_rate_array = "";
                foreach ($rate_effect_details as $val) {
                    $rate_array[] = ["id" => $val['id'], "effective_date" => $val['effective_date'], "amount" => $val['amount']];
                    $last_rate_array = ["id" => $val['id'], "effective_date" => $val['effective_date'], "amount" => $val['amount']];
                }

                $rate_array[] = ["id" => $last_rate_array['id'], "effective_date" => date('Y-m-t', strtotime(date('Y-m-d') . "-1 month ")), "amount" => $last_rate_array['amount']];

                //print_r($rate_array);die;
                $z = 0;
                foreach ($rate_array as $val) {
                    $z++;
                    if ($i > $val['effective_date'] and $flag == 1) {
                        $i = $val['effective_date'];
                    }

                    $consumer_tax = array();
                    $consumer_tax['consumer_id'] = $consumer_id;
                    $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_tax['charge_type'] = 'Fixed';
                    $consumer_tax['rate_id'] = $val['id'];
                    $consumer_tax['amount'] = $fixed_amount;
                    $consumer_tax['effective_from'] = $i;
                    $consumer_tax['emp_details_id'] = $this->emp_id;
                    $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                    $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);

                    $demand_no = $demand_no . str_pad($consumer_tax_id, 6, '0', STR_PAD_LEFT);
                    while ($i < $val['effective_date']) {

                        $flag = 1;
                        if ($i < $upto_date) {
                            $last_date_of_current_month = date('Y-m-t', strtotime($i));
                            if ($last_date_of_current_month > $to_date) {
                                $last_date_of_current_month = $to_date;
                                $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
                            } else {
                                $demand_upto = date('Y-m-t', strtotime($i));
                            }

                            $date_diff_upto = date('Y-m-d', strtotime($last_date_of_current_month . "+1 days"));

                            $get_date_diff = $this->consumer_demand_model->date_diff_water($i, $date_diff_upto);
                            $noof_monthday = date('t', strtotime($i));

                            if ($get_date_diff['month_diff'] == 1) {
                                $total_fixed_amount = $fixed_amount;
                            } else if ($get_date_diff['day_diff'] > 0) {
                                $days_diff = $get_date_diff['day_diff'];
                                $total_fixed_amount = round(($fixed_amount / $noof_monthday) * $days_diff);
                            }
                            $penalty_dtls = $this->demand_penalty_master->getPenaltyDetails($prev_connection_details['connection_type'], $i);

                            if (!empty($penalty_dtls)) {
                                if ($penalty_dtls['effective_date'] <= $i) {
                                    $penalty = number_format((($penalty_dtls['penalty_amount'] * $total_fixed_amount) / 100), 2);
                                } else {
                                    $penalty = 0;
                                }
                            }

                            $consumer_demand = array();
                            $consumer_demand['consumer_id'] = $consumer_id;
                            $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                            $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                            $consumer_demand['generation_date'] = date('Y-m-d');
                            $consumer_demand['amount'] = $total_fixed_amount + $penalty;
                            $consumer_demand['unit_amount'] = $fixed_amount;
                            $consumer_demand['demand_from'] = $i;
                            $consumer_demand['demand_upto'] = $demand_upto;
                            $consumer_demand['penalty'] = $penalty;
                            $consumer_demand['emp_details_id'] = $this->emp_id;
                            $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                            $consumer_demand['connection_type'] = 'Fixed';
                            $consumer_demand['demand_no'] = $demand_no;

                            $this->consumer_demand_model->insertData($consumer_demand);

                            $i = date('Y-m-d', strtotime($demand_upto . "+1 days"));
                        }
                    }
                    $fixed_amount = $val['amount'];
                    $j++;
                }
            } else {
                $i = $demand_from;
                //$i ='2020-12-31';
                if ($upto_date == "") {
                    $to_date = date("Y-m-t", strtotime("-1 months"));
                } else {
                    $month = date('m', strtotime($upto_date));
                    $year = date('Y', strtotime($upto_date));

                    $curr_month = date('m');
                    $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
                }

                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Fixed';
                $consumer_tax['rate_id'] = $fixed_rate_details['id'];
                $consumer_tax['amount'] = $fixed_amount;
                $consumer_tax['effective_from'] = $i;
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                $demand_no = $demand_no . str_pad($consumer_tax_id, 6, '0', STR_PAD_LEFT);
                while ($i < $to_date) {
                    $last_date_of_current_month = date('Y-m-t', strtotime($i));
                    if ($last_date_of_current_month > $to_date) {
                        $last_date_of_current_month = $to_date;
                        $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
                    } else {
                        $demand_upto = date('Y-m-t', strtotime($i));
                    }
                    //print_var(date('t', strtotime($i)));
                    $date_diff_upto = date('Y-m-d', strtotime($last_date_of_current_month . "+1 days"));
                    $get_date_diff = $this->consumer_demand_model->date_diff_water($i, $date_diff_upto);
                    $noof_monthday = date('t', strtotime($i));
                    if ($get_date_diff['month_diff'] == 1) {
                        $total_fixed_amount = $fixed_amount;
                    } else if ($get_date_diff['day_diff'] > 0) {
                        $days_diff = $get_date_diff['day_diff'];
                        $total_fixed_amount = round(($fixed_amount / $noof_monthday) * $days_diff);
                    }

                    $penalty_dtls = $this->demand_penalty_master->getPenaltyDetails($prev_connection_details['connection_type'], $i);
                    if (!empty($penalty_dtls)) {
                        if ($penalty_dtls['effective_date'] <= $i) {
                            $penalty = number_format((($penalty_dtls['penalty_amount'] * $total_fixed_amount) / 100), 2);
                        } else {
                            $penalty = 0;
                        }
                    } else {
                        $penalty = 0;
                    }

                    $consumer_demand = array();
                    $consumer_demand['consumer_id'] = $consumer_id;
                    $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                    $consumer_demand['generation_date'] = date('Y-m-d');
                    $consumer_demand['unit_amount'] = $fixed_amount;
                    $consumer_demand['amount'] = $total_fixed_amount + $penalty;
                    $consumer_demand['demand_from'] = $i;
                    $consumer_demand['demand_upto'] = $demand_upto;
                    $consumer_demand['penalty'] = $penalty;
                    $consumer_demand['emp_details_id'] = $this->emp_id;
                    $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                    $consumer_demand['connection_type'] = 'Fixed';
                    $consumer_demand['demand_no'] = $demand_no;
                    //print_var($consumer_demand);die;
                    $this->consumer_demand_model->insertData($consumer_demand);
                    $i = date('Y-m-d', strtotime($demand_upto . "+1 days"));
                }
            }
        } 
        #meter
        else if ($prev_connection_details['connection_type'] == 1 || $prev_connection_details['connection_type'] == 2) 
        {
            if ($upto_date == "") 
            {
                $to_date = date('Y-m-d');
            } 
            else 
            {
                $to_date = $upto_date;
            }
            if($prev_connection_details['meter_status']==0 && ($property_type_id == 3 || (!empty($get_demanddetails) && $property_type_id != 3)))
            {
                return $this->averageBulling($consumer_id,$to_date,$final_reading,$file);
            }
            $get_initial_reading = $this->initial_meter_reading->initial_meter_reading($consumer_id);

            $initial_reading = $get_initial_reading['initial_reading']??0;            
            $diff_reading = $final_reading - $initial_reading;

            if ($property_type_id == 1) 
            {
                $where = " and category='$category' and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            } 
            else 
            {
                $where = " and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            }         
            $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
            //$get_meter_rate = $this->revised_meter_rate_model->getMeterRate($property_type_id, $where);
            //it is tempropery remove it 
            $temp_pro = $property_type_id;
            if(in_array($property_type_id,[7]))
                $temp_pro = 1;
            elseif(in_array($property_type_id,[8]))
                $temp_pro = 4;
            elseif(!in_array($property_type_id,[1,2,3,4,5,6,7]))
                $temp_pro = 8;    
            $get_meter_rate_new = $this->revised_meter_rate_model->getMeterRate_new($temp_pro, $where);
            //end her
            $temp_diff = $diff_reading;
            $incriment = 0;
            $amount= 0;
            $ret_ids=''; 
            $meter_rate_id=0;
            $meter_calc_rate=0;
            foreach($get_meter_rate_new as $key=>$val)
            {       
                $meter_calc_rate = $val['amount'];
                $meter_calc_factor = $get_meter_calc['meter_rate']; 
                $meter_rate_id = $val['id']; 
                if($key==0)
                    $ret_ids .=  $val['id'];
                else
                    $ret_ids .=  ",".$val['id'];

                $reading = $incriment + $val['reading'];                 
                 if($reading<=$diff_reading && !empty($val['reading']))
                 {
                    $amount += $meter_calc_rate * $meter_calc_factor * $val['reading']; 
                    $reading = $val['reading'];                                     
                 } 
                 elseif(empty($val['reading']))
                 {
                    $reading = $temp_diff - $reading;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;  
                 }
                 else
                 {
                    $reading = $temp_diff - $incriment;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;                   
                 } 
                
                $incriment +=$val['reading'];

            }              
            $ret_ids = ltrim($ret_ids,',');
            $meter_calc_factor = $get_meter_calc['meter_rate'];  
            $meter_rate = $meter_calc_factor *  $meter_calc_rate ;       
            $meter_rate_id = $meter_rate_id;
            $total_amount = $amount;
            if ($total_amount >= 0) 
            {
                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Meter';
                $consumer_tax['rate_id'] = $meter_rate_id;
                $consumer_tax['initial_reading'] = $initial_reading;
                $consumer_tax['final_reading'] = $final_reading;
                $consumer_tax['amount'] = $total_amount;
                $consumer_tax['effective_from'] = date('Y-m-d');
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                $demand_no = $demand_no . str_pad($consumer_tax_id, 6, '0', STR_PAD_LEFT);

                $consumer_demand = array();
                $consumer_demand['consumer_id'] = $consumer_id;
                $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                $consumer_demand['generation_date'] = date('Y-m-d');
                $consumer_demand['amount'] = $total_amount;
                $consumer_demand['current_meter_reading '] = $final_reading;
                $consumer_demand['unit_amount'] = $meter_rate;
                $consumer_demand['demand_from'] = $demand_from;
                $consumer_demand['demand_upto'] = $to_date;
                $consumer_demand['emp_details_id'] = $this->emp_id;
                $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                $consumer_demand['connection_type'] = 'Meter';
                $consumer_demand['demand_no'] = $demand_no;

                $demand_id = $this->consumer_demand_model->insertData($consumer_demand);
            }
            if($file && $demand_id)
            {
                $extension = $file->getExtension();                
                $city = $this->ulb_details['city'];

                if($file->isValid() && !$file->hasMoved())
                {
                    $newName =$demand_id.".".$extension;
                    $path=$city.'/meter_reading'.'/';
                    if($file->move(WRITEPATH.'uploads/'.$path,$newName))
                    {
                        $tbl_meter_reading_doc=["demand_id"=>$demand_id,
                                        "file_name"=>$path.$newName,
                                        "meter_no"=>$prev_connection_details['meter_no']??0,
                        ];
                        $this->water_reading_doc->insert_meter_reading_doc($tbl_meter_reading_doc);
                    }
                }                
            }
        }
        //print_var($final_reading);die;
        // inserting last reading at the time of meter change
        if ($final_reading > 0) 
        {
            $consumer_last_reading_insert = array();
            $consumer_last_reading_insert['consumer_id'] = $consumer_id;
            $consumer_last_reading_insert['initial_reading'] = $final_reading;
            $consumer_last_reading_insert['emp_details_id'] = $this->emp_id;
            $consumer_last_reading_insert['created_on'] = date('Y-m-d H:i:s');
            $consumer_last_reading_insert['status'] = 1;

            $this->initial_meter_reading->insertData($consumer_last_reading_insert);
        }
        if($consumer_tax_id)
        {
            #-----------------------------------sms send code---------------------------------
            //----------------------sms data -----------------------
            $appliction = $consumer_details;
            $owner = $this->consumer_details_model->consumerDetails($appliction['id']);
            //---------------------- end sms data------------------
            $demands = $this->consumer_demand_model->getTotalAmountByCidTid($appliction['id']);
            $amount = $demands['amount']+$demands['penalty'];                    
            $sms = Water(['amount'=>$amount,'consumer_no'=>$appliction['consumer_no'],"toll_free_no1"=>'1800 8904115','ulb_name'=>$this->ulb_details['ulb_name']],'Consumer Demand');
            if($sms['status'])
            {
                $message = $sms['sms'];
                $templateid = $sms['temp_id'];
                foreach ($owner as $val )
                {
                    $mobile=$val['mobile_no'];
                    $sms_log_data = ['emp_id'=>$this->emp_id,
                                    'ref_id'=>$appliction['id'],
                                    'ref_type'=>'tbl_consumer',
                                    'mobile_no'=>$mobile,
                                    'purpose'=>"Consumer Demand",
                                    'template_id'=>$templateid,
                                    'message'=>$message
                    ];
                    $sms_id =  $this->water_sms_log->insert_sms_log($sms_log_data);
                    $s = send_sms($mobile,$message, $templateid);
                    
                    if($s)
                    {
                        $update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
                        $up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                        
                    } 

                }
            }
            #----------------------------------- end sms send code----------------------------
        }
        return $consumer_tax_id??null;
    }

    public function generate_demand_old2($consumer_id, $upto_date, $final_reading = 0)
    {
        // $get_demanddetails = $this->consumer_demand_model->getLastDemand(md5($consumer_id));
        $get_demanddetails = $this->consumer_demand_model->getLastDemand2($consumer_id);
        $last_demand_upto = $get_demanddetails['demand_upto'];
        $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $area_sqmt = $consumer_details['area_sqmt'];
        $ward_mstr_id = $consumer_details['ward_mstr_id'];
        $property_type_id = $consumer_details['property_type_id'];
        $category = $consumer_details['category'];
        $ulb_details = session()->get('ulb_dtl');
        $ulb_short_name = substr($ulb_details['city'], 0, 3);
        $ward_no = $this->model_ward_mstr->getWardNoById(['ward_mstr_id' => $ward_mstr_id]);
        $ward_no = isset($ward_no['ward_no']) ? $ward_no['ward_no'] : 0;
        $demand_no = $ulb_short_name . str_pad($ward_no, 3, '0', STR_PAD_LEFT) . str_pad($this->emp_id, 5, '0', STR_PAD_LEFT) . '/';
        if(in_array(strtoupper(trim($category)),['BPL']))
        {
             return null;
             die;   
        } 
        $generation_date = date('Y-m-d');
        if ((!empty($get_demanddetails) and $last_demand_upto == "") or $property_type_id <= 0 or $consumer_details['area_sqmt'] <= 0) {

            flashToast("error", "Update your area or property type!!!");

            //echo $consumer_id;
            //die("Contact to Admin");
            //return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/'.md5($consumer_id)));
            //exit();

        }

        $prev_connection_details = $this->meter_status_model->getLastConnectionDetails($consumer_details['id']);

        if ($last_demand_upto == "") {
            $last_demand_upto = $prev_connection_details['connection_date'];
            $demand_from = $last_demand_upto;
        } else {
            $demand_from = date('Y-m-d', strtotime($last_demand_upto . "+1 days"));
        }

        $prev_conn_date = $prev_connection_details['connection_date'];
        if ($prev_connection_details['connection_type'] == 3) // fixed demand generate
        {

            $fixed_rate_details = $this->fixed_meter_rate_model->getLatestFixedRateCharge($property_type_id, $area_sqmt, $demand_from);
            $rate_effect_details = $this->fixed_meter_rate_model->getFixedRateEffectBetweenDemandGeneration($property_type_id, $area_sqmt, $demand_from);

            if (empty($fixed_rate_details)) {
                $demand_from = $rate_effect_details[0]['effective_date'];
            }

            $fixed_amount = $fixed_rate_details['amount'];
            if (!empty($rate_effect_details)) {

                $i = $demand_from; // initialize i with demand from
                if ($upto_date == "") {
                    $to_date = date("Y-m-t", strtotime("-1 months"));
                } else {
                    //  $to_date=$upto_date;
                    $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
                }

                $upto_date = $to_date;
                $j = 1;
                $flag = 0;
                $rate_array = [];
                $last_rate_array = "";
                foreach ($rate_effect_details as $val) {
                    $rate_array[] = ["id" => $val['id'], "effective_date" => $val['effective_date'], "amount" => $val['amount']];
                    $last_rate_array = ["id" => $val['id'], "effective_date" => $val['effective_date'], "amount" => $val['amount']];
                }

                $rate_array[] = ["id" => $last_rate_array['id'], "effective_date" => date('Y-m-t', strtotime(date('Y-m-d') . "-1 month ")), "amount" => $last_rate_array['amount']];

                //print_r($rate_array);die;
                $z = 0;
                foreach ($rate_array as $val) {
                    $z++;
                    if ($i > $val['effective_date'] and $flag == 1) {
                        $i = $val['effective_date'];
                    }

                    $consumer_tax = array();
                    $consumer_tax['consumer_id'] = $consumer_id;
                    $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_tax['charge_type'] = 'Fixed';
                    $consumer_tax['rate_id'] = $val['id'];
                    $consumer_tax['amount'] = $fixed_amount;
                    $consumer_tax['effective_from'] = $i;
                    $consumer_tax['emp_details_id'] = $this->emp_id;
                    $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                    $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);

                    $demand_no = $demand_no . str_pad($consumer_tax_id, 6, '0', STR_PAD_LEFT);
                    while ($i < $val['effective_date']) {

                        $flag = 1;
                        if ($i < $upto_date) {
                            $last_date_of_current_month = date('Y-m-t', strtotime($i));
                            if ($last_date_of_current_month > $to_date) {
                                $last_date_of_current_month = $to_date;
                                $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
                            } else {
                                $demand_upto = date('Y-m-t', strtotime($i));
                            }

                            $date_diff_upto = date('Y-m-d', strtotime($last_date_of_current_month . "+1 days"));

                            $get_date_diff = $this->consumer_demand_model->date_diff_water($i, $date_diff_upto);
                            $noof_monthday = date('t', $i);

                            if ($get_date_diff['month_diff'] == 1) {
                                $total_fixed_amount = $fixed_amount;
                            } else if ($get_date_diff['day_diff'] > 0) {
                                $days_diff = $get_date_diff['day_diff'];
                                $total_fixed_amount = round(($fixed_amount / $noof_monthday) * $days_diff);
                            }
                            $penalty_dtls = $this->demand_penalty_master->getPenaltyDetails($prev_connection_details['connection_type'], $i);

                            if (!empty($penalty_dtls)) {
                                if ($penalty_dtls['effective_date'] <= $i) {
                                    $penalty = number_format((($penalty_dtls['penalty_amount'] * $total_fixed_amount) / 100), 2);
                                } else {
                                    $penalty = 0;
                                }
                            }

                            $consumer_demand = array();
                            $consumer_demand['consumer_id'] = $consumer_id;
                            $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                            $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                            $consumer_demand['generation_date'] = date('Y-m-d');
                            $consumer_demand['amount'] = $total_fixed_amount + $penalty;
                            $consumer_demand['unit_amount'] = $fixed_amount;
                            $consumer_demand['demand_from'] = $i;
                            $consumer_demand['demand_upto'] = $demand_upto;
                            $consumer_demand['penalty'] = $penalty;
                            $consumer_demand['emp_details_id'] = $this->emp_id;
                            $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                            $consumer_demand['connection_type'] = 'Fixed';
                            $consumer_demand['demand_no'] = $demand_no;

                            $this->consumer_demand_model->insertData($consumer_demand);

                            $i = date('Y-m-d', strtotime($demand_upto . "+1 days"));
                        }
                    }
                    $fixed_amount = $val['amount'];
                    $j++;
                }
            } else {
                $i = $demand_from;
                //$i ='2020-12-31';
                if ($upto_date == "") {
                    $to_date = date("Y-m-t", strtotime("-1 months"));
                } else {
                    $month = date('m', strtotime($upto_date));
                    $year = date('Y', strtotime($upto_date));

                    $curr_month = date('m');
                    $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
                }

                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Fixed';
                $consumer_tax['rate_id'] = $fixed_rate_details['id'];
                $consumer_tax['amount'] = $fixed_amount;
                $consumer_tax['effective_from'] = $i;
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                $demand_no = $demand_no . str_pad($consumer_tax_id, 6, '0', STR_PAD_LEFT);
                while ($i < $to_date) {
                    $last_date_of_current_month = date('Y-m-t', strtotime($i));
                    if ($last_date_of_current_month > $to_date) {
                        $last_date_of_current_month = $to_date;
                        $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
                    } else {
                        $demand_upto = date('Y-m-t', strtotime($i));
                    }
                    //print_var(date('t', strtotime($i)));
                    $date_diff_upto = date('Y-m-d', strtotime($last_date_of_current_month . "+1 days"));
                    $get_date_diff = $this->consumer_demand_model->date_diff_water($i, $date_diff_upto);
                    $noof_monthday = date('t', strtotime($i));
                    if ($get_date_diff['month_diff'] == 1) {
                        $total_fixed_amount = $fixed_amount;
                    } else if ($get_date_diff['day_diff'] > 0) {
                        $days_diff = $get_date_diff['day_diff'];
                        $total_fixed_amount = round(($fixed_amount / $noof_monthday) * $days_diff);
                    }

                    $penalty_dtls = $this->demand_penalty_master->getPenaltyDetails($prev_connection_details['connection_type'], $i);
                    if (!empty($penalty_dtls)) {
                        if ($penalty_dtls['effective_date'] <= $i) {
                            $penalty = number_format((($penalty_dtls['penalty_amount'] * $total_fixed_amount) / 100), 2);
                        } else {
                            $penalty = 0;
                        }
                    } else {
                        $penalty = 0;
                    }

                    $consumer_demand = array();
                    $consumer_demand['consumer_id'] = $consumer_id;
                    $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                    $consumer_demand['generation_date'] = date('Y-m-d');
                    $consumer_demand['unit_amount'] = $fixed_amount;
                    $consumer_demand['amount'] = $total_fixed_amount + $penalty;
                    $consumer_demand['demand_from'] = $i;
                    $consumer_demand['demand_upto'] = $demand_upto;
                    $consumer_demand['penalty'] = $penalty;
                    $consumer_demand['emp_details_id'] = $this->emp_id;
                    $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                    $consumer_demand['connection_type'] = 'Fixed';
                    $consumer_demand['demand_no'] = $demand_no;
                    //print_var($consumer_demand);die;
                    $this->consumer_demand_model->insertData($consumer_demand);
                    $i = date('Y-m-d', strtotime($demand_upto . "+1 days"));
                }
            }
        } else if ($prev_connection_details['connection_type'] == 1 || $prev_connection_details['connection_type'] == 2) 
        {
            if ($upto_date == "") {
                $to_date = date('Y-m-d');
            } else {
                $to_date = $upto_date;
            }

            $get_initial_reading = $this->initial_meter_reading->initial_meter_reading($consumer_id);

            $initial_reading = $get_initial_reading['initial_reading'];            
            $diff_reading = $final_reading - $initial_reading;
            if ($property_type_id == 1) {
                $where = " and category='$category' and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            } else {
                $where = " and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            }
            $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
            //$get_meter_rate = $this->revised_meter_rate_model->getMeterRate($property_type_id, $where);
            //it is tempropery remove it 
            $temp_pro = $property_type_id;
            if(in_array($property_type_id,[7]))
                $temp_pro = 1;
            $get_meter_rate = $this->revised_meter_rate_model->getMeterRate($temp_pro, $where);
            //end her
            $meter_calc_rate = $get_meter_rate['amount'];
            $meter_calc_factor = $get_meter_calc['meter_rate'];
            $meter_rate = $meter_calc_rate * $meter_calc_factor * $diff_reading;

            $meter_rate_id = $get_meter_rate['id'];
            $total_amount = $meter_rate;
            if ($total_amount >= 0) {
                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Meter';
                $consumer_tax['rate_id'] = $meter_rate_id;
                $consumer_tax['initial_reading'] = $initial_reading;
                $consumer_tax['final_reading'] = $final_reading;
                $consumer_tax['amount'] = $meter_rate;
                $consumer_tax['effective_from'] = date('Y-m-d');
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                $demand_no = $demand_no . str_pad($consumer_tax_id, 6, '0', STR_PAD_LEFT);

                $consumer_demand = array();
                $consumer_demand['consumer_id'] = $consumer_id;
                $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                $consumer_demand['generation_date'] = date('Y-m-d');
                $consumer_demand['amount'] = $total_amount;
                $consumer_demand['unit_amount'] = $meter_rate;
                $consumer_demand['demand_from'] = $demand_from;
                $consumer_demand['demand_upto'] = $to_date;
                $consumer_demand['emp_details_id'] = $this->emp_id;
                $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                $consumer_demand['connection_type'] = 'Meter';
                $consumer_demand['demand_no'] = $demand_no;

                $this->consumer_demand_model->insertData($consumer_demand);
            }
        }
        //print_var($final_reading);die;
        // inserting last reading at the time of meter change
        if ($final_reading > 0) {
            $consumer_last_reading_insert = array();
            $consumer_last_reading_insert['consumer_id'] = $consumer_id;
            $consumer_last_reading_insert['initial_reading'] = $final_reading;
            $consumer_last_reading_insert['emp_details_id'] = $this->emp_id;
            $consumer_last_reading_insert['created_on'] = date('Y-m-d H:i:s');
            $consumer_last_reading_insert['status'] = 1;

            $this->initial_meter_reading->insertData($consumer_last_reading_insert);
        }
        return $consumer_tax_id??null;
    }


    public function generate_demand_old($consumer_id, $upto_date, $final_reading = 0)
    {
        $get_demanddetails = $this->consumer_demand_model->getLastDemand(md5($consumer_id));
        $last_demand_upto = $get_demanddetails['demand_upto'];
        $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $area_sqmt = $consumer_details['area_sqmt'];
        $ward_mstr_id = $consumer_details['ward_mstr_id'];
        $property_type_id = $consumer_details['property_type_id'];
        $category = $consumer_details['category'];

        $generation_date = date('Y-m-d');
        if ((!empty($get_demanddetails) and $last_demand_upto == "") or $property_type_id <= 0 or $consumer_details['area_sqmt'] <= 0) {

            flashToast("error", "Update your area or property type!!!");

            //echo $consumer_id;
            //die("Contact to Admin");
            //return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/'.md5($consumer_id)));
            //exit();
        }

        // echo $consumer_id;
        $prev_connection_details = $this->meter_status_model->getLastConnectionDetails($consumer_details['id']);
        // print_r($prev_connection_details);

        if ($last_demand_upto == "") {
            $last_demand_upto = $prev_connection_details['connection_date'];
            $demand_from = $last_demand_upto;
        } else {
            $demand_from = date('Y-m-d', strtotime($last_demand_upto . "+1 days"));
        }

        //print_r($prev_connection_details);
        $prev_conn_date = $prev_connection_details['connection_date'];
        // echo $demand_from;



        /*while($last_demand_upto<$to_date)
                {
                    
                    
                }*/
        // echo $last_demand_upto;


        if ($prev_connection_details['connection_type'] == 3) // fixed demand generate
        {


            $fixed_rate_details = $this->fixed_meter_rate_model->getLatestFixedRateCharge($property_type_id, $area_sqmt, $demand_from);

            // print_r($fixed_rate_details);


            $rate_effect_details = $this->fixed_meter_rate_model->getFixedRateEffectBetweenDemandGeneration($property_type_id, $area_sqmt, $demand_from);

            //  print_r($rate_effect_details);


            // fetch penalty for demand in case of fixed 





            if (empty($fixed_rate_details)) {
                $demand_from = $rate_effect_details[0]['effective_date'];
            }
            // echo $demand_from;
            /* if($demand_from<$fixed_rate_details['effective_date'])
                {
                    $demand_from=$fixed_rate_details['effective_date'];
                    $fixed_rate_details=$this->fixed_meter_rate_model->getLatestFixedRateCharge($property_type_id,$area_sqmt,$demand_from);
                }*/

            //print_r($fixed_rate_details);
            $fixed_amount = $fixed_rate_details['amount'];
            //  $rate_effect_details=array_merge($rate_effect_details,array("0"=>$fixed_rate_details));
            //print_r($rate_effect_details);
            // ksort($rate_effect_details);

            //   usort($rate_effect_details, 'effective_date_water');
            //print_r($rate_effect_details);

            if (!empty($rate_effect_details)) {

                $i = $demand_from; // initialize i with demand from

                if ($upto_date == "") {
                    $to_date = date("Y-m-t", strtotime("-1 months"));
                } else {
                    //  $to_date=$upto_date;
                    $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
                }
                // echo $to_date;
                $upto_date = $to_date;

                // print_r($rate_effect_details);
                $j = 1;
                $flag = 0;
                $rate_array = [];
                $last_rate_array = "";

                foreach ($rate_effect_details as $val) {
                    $rate_array[] = ["id" => $val['id'], "effective_date" => $val['effective_date'], "amount" => $val['amount']];
                    $last_rate_array = ["id" => $val['id'], "effective_date" => $val['effective_date'], "amount" => $val['amount']];
                }

                $rate_array[] = ["id" => $last_rate_array['id'], "effective_date" => date('Y-m-t', strtotime(date('Y-m-d') . "-1 month ")), "amount" => $last_rate_array['amount']];

                // print_r($rate_array);

                $z = 0;

                foreach ($rate_array as $val) {
                    $z++;
                    //  echo "string";
                    //  print_r($val);
                    // exit();

                    /* echo "<br>";
                          echo $i;
                          echo "<br>";
                          echo  $flag;
                          echo "<br>";
                          echo $j;
                          echo "<br>";
                          echo "<br>";*/

                    //  echo $val['amount'];
                    // todate should be one month less in case of fixed

                    // echo $fixed_amount;
                    //   echo "<br>";
                    // echo $val['effective_date'].'flag '.$flag.' --i--'.$i;
                    // echo "<br>";

                    if ($i > $val['effective_date'] and $flag == 1) {
                        $i = $val['effective_date'];
                    }


                    $consumer_tax = array();
                    $consumer_tax['consumer_id'] = $consumer_id;
                    $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_tax['charge_type'] = 'Fixed';
                    $consumer_tax['rate_id'] = $val['id'];
                    $consumer_tax['amount'] = $fixed_amount;
                    $consumer_tax['effective_from'] = $i;
                    $consumer_tax['emp_details_id'] = $this->emp_id;
                    $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                    // print_r($consumer_tax);
                    $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);



                    //  echo $i;
                    /*  echo "<br>";

                        echo "______".$i;
                        echo "---".$z;
                        echo "===".$val['effective_date'];

                        echo "<br>";
                        echo $upto_date;
                     */
                    /*
                       echo $i.'-'.$fixed_amount;
                       echo "<br>";
                       echo $i.'-'.$fixed_amount;
                    */

                    while ($i < $val['effective_date']) {

                        $flag = 1;
                        //  echo "aaaa".$val['amount'];

                        //  echo " val amount ".$val['amount'];
                        //$i is demand_from that is updating in loop




                        // echo $upto_date;

                        // echo $i.'-'. $upto_date;

                        if ($i < $upto_date) {

                            //echo "from date".$i;

                            $last_date_of_current_month = date('Y-m-t', strtotime($i));
                            // echo "upto date".$to_date;

                            if ($last_date_of_current_month > $to_date) {

                                $last_date_of_current_month = $to_date;
                                $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
                            } else {
                                $demand_upto = date('Y-m-t', strtotime($i));
                            }


                            $date_diff_upto = date('Y-m-d', strtotime($last_date_of_current_month . "+1 days"));

                            $get_date_diff =  $this->consumer_demand_model->date_diff_water($i, $date_diff_upto);
                            // print_r($get_date_diff);
                            $noof_monthday = date('t', $i);

                            if ($get_date_diff['month_diff'] == 1) {
                                $total_fixed_amount = $fixed_amount;
                            } else if ($get_date_diff['day_diff'] > 0) {
                                $days_diff = $get_date_diff['day_diff'];
                                $total_fixed_amount = round(($fixed_amount / $noof_monthday) * $days_diff);
                            }
                            // echo "from date".$i;
                            // echo  "fixed amount".$total_fixed_amount;

                            $penalty_dtls = $this->demand_penalty_master->getPenaltyDetails($prev_connection_details['connection_type'], $i);


                            if (!empty($penalty_dtls)) {
                                // print_r($penalty_dtls);
                                // echo $i;

                                if ($penalty_dtls['effective_date'] <= $i) {


                                    $penalty = number_format((($penalty_dtls['penalty_amount'] * $total_fixed_amount) / 100), 2);
                                } else {
                                    $penalty = 0;
                                }
                            }


                            /*  if($j>1)
                            {
                                $fixed_amount=$val['amount'];
                            }*/

                            $consumer_demand = array();
                            $consumer_demand['consumer_id'] = $consumer_id;
                            $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                            $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                            $consumer_demand['generation_date'] = date('Y-m-d');
                            $consumer_demand['amount'] = $total_fixed_amount + $penalty;
                            $consumer_demand['unit_amount'] = $fixed_amount;
                            $consumer_demand['demand_from'] = $i;
                            $consumer_demand['demand_upto'] = $demand_upto;
                            $consumer_demand['penalty'] = $penalty;
                            $consumer_demand['emp_details_id'] = $this->emp_id;
                            $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                            $consumer_demand['connection_type'] = 'Fixed';

                            // print_r($consumer_demand);
                            //  echo "<br>";
                            $this->consumer_demand_model->insertData($consumer_demand);

                            $i = date('Y-m-d', strtotime($demand_upto . "+1 days"));
                            // echo "date   ".$i;

                            //   echo "upto date".  $i=date('Y-m-d',strtotime($demand_upto."+1 days"));
                            //   echo "<br>";

                        }
                    }


                    $fixed_amount = $val['amount'];
                    $j++;
                }
            } else {

                //print_r($fixed_rate_details);

                $i = $demand_from;


                if ($upto_date == "") {

                    $to_date = date("Y-m-t", strtotime("-1 months"));
                } else {
                    $month = date('m', strtotime($upto_date));
                    $year = date('Y', strtotime($upto_date));

                    $curr_month = date('m');
                    $curr_year = date('Y');

                    /*if($curr_year==$year and $curr_month==$month)
                        {
                            $to_date=date('Y-m-t',strtotime($upto_date,'-1 months'));
                        }*/

                    $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
                    // $to_date=$upto_date;
                }



                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Fixed';
                $consumer_tax['rate_id'] = $fixed_rate_details['id'];
                $consumer_tax['amount'] = $fixed_amount;
                $consumer_tax['effective_from'] = $i;
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);


                while ($i < $to_date) {

                    // echo $i;


                    $last_date_of_current_month = date('Y-m-t', strtotime($i));
                    // echo "upto date".$to_date;

                    if ($last_date_of_current_month > $to_date) {
                        $last_date_of_current_month = $to_date;
                        $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
                    } else {
                        $demand_upto = date('Y-m-t', strtotime($i));
                    }
                    //  echo "last date".$last_date_of_current_month;
                    //echo $demand_upto;

                    //      $days_diff=round(strtotime($last_date_of_current_month)-strtotime($i))/(60 * 60 * 24);

                    /*   $date_diff_upto=date('Y-m-d',strtotime($last_date_of_current_month."+1 days"));
                           if($days_diff<30)
                            {
                                $total_fixed_amount=($fixed_amount/30)*$days_diff;    
                            }
                            else
                            {
                                $total_fixed_amount=$fixed_amount;
                            }
                        */

                    $date_diff_upto = date('Y-m-d', strtotime($last_date_of_current_month . "+1 days"));

                    $get_date_diff =  $this->consumer_demand_model->date_diff_water($i, $date_diff_upto);
                    // print_r($get_date_diff);
                    $noof_monthday = date('t', $i);


                    if ($get_date_diff['month_diff'] == 1) {
                        $total_fixed_amount = $fixed_amount;
                    } else if ($get_date_diff['day_diff'] > 0) {
                        $days_diff = $get_date_diff['day_diff'];
                        $total_fixed_amount = round(($fixed_amount / $noof_monthday) * $days_diff);
                    }

                    $penalty_dtls = $this->demand_penalty_master->getPenaltyDetails($prev_connection_details['connection_type'], $i);


                    if (!empty($penalty_dtls)) {
                        // print_r($penalty_dtls);


                        if ($penalty_dtls['effective_date'] <= $i) {


                            $penalty = number_format((($penalty_dtls['penalty_amount'] * $total_fixed_amount) / 100), 2);
                        } else {
                            $penalty = 0;
                        }
                    }


                    $consumer_demand = array();
                    $consumer_demand['consumer_id'] = $consumer_id;
                    $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                    $consumer_demand['generation_date'] = date('Y-m-d');
                    $consumer_demand['unit_amount'] = $fixed_amount;
                    $consumer_demand['amount'] = $total_fixed_amount + $penalty;
                    $consumer_demand['demand_from'] = $i;
                    $consumer_demand['demand_upto'] = $demand_upto;
                    $consumer_demand['penalty'] = $penalty;
                    $consumer_demand['emp_details_id'] = $this->emp_id;
                    $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                    $consumer_demand['connection_type'] = 'Fixed';

                    $this->consumer_demand_model->insertData($consumer_demand);

                    // $i=date('Y-m-d',strtotime($demand_upto."+1 days"));

                    $i = date('Y-m-d', strtotime($demand_upto . "+1 days"));
                }

                // echo $i;

            }
        } else if ($prev_connection_details['connection_type'] == 1 || $prev_connection_details['connection_type'] == 2) {



            //  echo $demand_from.'---'.$to_date;
            if ($upto_date == "") {
                $to_date = date('Y-m-d');
            } else {
                $to_date = $upto_date;
            }

            $get_initial_reading = $this->initial_meter_reading->initial_meter_reading($consumer_id);
            $initial_reading = $get_initial_reading['initial_reading'];

            // echo $final_reading.'-'.$property_type_id.'-'.$area_sqmt;

            $diff_reading = $final_reading - $initial_reading;

            if ($property_type_id == 1) {
                $where = " and category='$category' and $diff_reading>=from_unit and $diff_reading<=upto_unit ";
            } else {
                $where = " and $diff_reading>=from_unit and $diff_reading<=upto_unit ";
            }
            $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
            $get_meter_rate = $this->revised_meter_rate_model->getMeterRate($property_type_id, $where);
            //print_r($get_meter_rate);



            $meter_calc_rate = $get_meter_rate['amount'];
            $meter_calc_factor = $get_meter_calc['meter_rate'];
            $meter_rate = $meter_calc_rate * $meter_calc_factor * $diff_reading;

            $meter_rate_id = $get_meter_rate['id'];
            $total_amount = $meter_rate;


            /*if($total_amount==0)
                {
                    flashToast("error", "Some Error Occured!!!");
                    return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.md5($consumer_id)));
                    
                }*/

            if ($total_amount > 0) {
                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Meter';
                $consumer_tax['rate_id'] = $meter_rate_id;
                $consumer_tax['initial_reading'] = $initial_reading;
                $consumer_tax['final_reading'] = $final_reading;
                $consumer_tax['amount'] = $meter_rate;
                $consumer_tax['effective_from'] = date('Y-m-d');
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);



                $consumer_demand = array();
                $consumer_demand['consumer_id'] = $consumer_id;
                $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                $consumer_demand['generation_date'] = date('Y-m-d');
                $consumer_demand['amount'] = $total_amount;
                $consumer_demand['unit_amount'] = $meter_rate;
                $consumer_demand['demand_from'] = $demand_from;
                $consumer_demand['demand_upto'] = $to_date;
                $consumer_demand['emp_details_id'] = $this->emp_id;
                $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                $consumer_demand['connection_type'] = 'Meter';

                $this->consumer_demand_model->insertData($consumer_demand);
            }
        }


        // inserting last reading at the time of meter change

        if ($final_reading > 0) {
            $consumer_last_reading_insert = array();
            $consumer_last_reading_insert['consumer_id'] = $consumer_id;
            $consumer_last_reading_insert['initial_reading'] = $final_reading;
            $consumer_last_reading_insert['emp_details_id'] = $this->emp_id;
            $consumer_last_reading_insert['created_on'] = date('Y-m-d H:i:s');
            $consumer_last_reading_insert['status'] = 1;

            $this->initial_meter_reading->insertData($consumer_last_reading_insert);
        }
    }

    public function averageBulling($consumer_id, $upto_date, $final_reading = 0,$file=null) // rule date 07/11/2022
    {
        $demand_id = false; 
        $consumer_tax_id = null;
        $get_demanddetails = $this->consumer_demand_model->getLastDemand2($consumer_id);
        $last_demand_upto = $get_demanddetails['demand_upto'];
        $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $area_sqmt = $consumer_details['area_sqmt'];
        $ward_mstr_id = $consumer_details['ward_mstr_id'];
        $property_type_id = $consumer_details['property_type_id'];
        $category = !empty(trim($consumer_details['category']))?trim($consumer_details['category']):"APL";
        $ulb_details = session()->get('ulb_dtl')??getUlbDtl();
        $ulb_short_name = substr($ulb_details['city'], 0, 3);
        $ward_no = $this->model_ward_mstr->getWardNoById(['ward_mstr_id' => $ward_mstr_id]);
        $ward_no = isset($ward_no['ward_no']) ? $ward_no['ward_no'] : 0;
        $demand_no = $ulb_short_name . str_pad($ward_no, 3, '0', STR_PAD_LEFT) . str_pad($this->emp_id, 5, '0', STR_PAD_LEFT) . '/';         
        $generation_date = date('Y-m-d');
        if ((!empty($get_demanddetails) && $last_demand_upto == "") || $property_type_id <= 0 || $consumer_details['area_sqmt'] <= 0) 
        {
            flashToast("error", "Update your area or property type!!!");
        }
       
        $prev_connection_details = $this->meter_status_model->getLastConnectionDetails($consumer_details['id']);
        if($prev_connection_details['connection_type'] == 3)
        {
            flashToast("error", "Can not Generate average Billig Of this Consumer!!!");
            return ;
        }
        if(in_array($prev_connection_details['connection_type'],[1]) && $property_type_id ==3 && ($prev_connection_details['rate_per_month']==0 || empty($prev_connection_details['rate_per_month'])))
        {
            flashToast("error", "Average bulling Rate Not Available Of this Consumer!!!");
            return ;
        }
        if(empty($prev_connection_details) || $prev_connection_details['connection_date']=='')
        {
            flashToast("error", "Connection Date Not Found!!!");
            return;
        }
        if($property_type_id !=3 && in_array($prev_connection_details['connection_type'],[1,2]) && empty($get_demanddetails) )
        {
            flashToast("error", "No Meter Demand Found!!!");
            return;
        }
        if ($last_demand_upto == "") 
        {
            $last_demand_upto = $prev_connection_details['connection_date'];
            $demand_from = $last_demand_upto;
        }        
        else 
        {
            $demand_from = date('Y-m-d', strtotime($last_demand_upto . "+1 days"));
        }
        $prev_conn_date = $prev_connection_details['connection_date'];
        
        if($property_type_id ==3 && $prev_connection_details['connection_type'] == 1 && $prev_connection_details['meter_status']==0) // for gov property meter Fixed
        {   
           
            $i = $demand_from;
            if ($upto_date == "") 
            {
                $to_date = date("Y-m-t", strtotime("-1 months"));
            } 
            else 
            {
                $month = date('m', strtotime($upto_date));
                $year = date('Y', strtotime($upto_date));

                $curr_month = date('m');
                $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
            }

            
            $gpriv_reading = 0;
            $gfinal_reading = $prev_connection_details['rate_per_month']??0;
            $consumtion_per_day = $gfinal_reading;


            while ($i < $to_date) 
            {                
                $last_date_of_current_month = date('Y-m-t', strtotime($i));
                
                if ($last_date_of_current_month > $to_date) 
                {
                    $last_date_of_current_month = $to_date;
                    $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
                } 
                else 
                {
                    $demand_upto = date('Y-m-t', strtotime($i));
                }
                $date1= date_create($i);
                $date2=date_create($demand_upto);
                $diff=date_diff($date2,$date1);
                $no_diff = $diff->format("%a")+1;           

                
                $gfinal_reading = $gpriv_reading +($consumtion_per_day*($no_diff)) ;

                $diff_reading = $gfinal_reading - $gpriv_reading;

                if ($property_type_id == 1) 
                {
                    $where = " and category='$category' and ceil($consumtion_per_day)>=from_unit and ceil($consumtion_per_day)<=upto_unit ";
                } 
                else 
                {
                    $where = " and ceil($consumtion_per_day)>=from_unit and ceil($consumtion_per_day)<=upto_unit ";
                }  

                $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
                $temp_pro = $property_type_id;
                if(in_array($property_type_id,[7]))
                {
                $temp_pro = 1;
                }
                elseif(in_array($property_type_id,[8]))
                {
                $temp_pro = 4;
                }
                elseif(!in_array($property_type_id,[1,2,3,4,5,6,7]))
                {
                $temp_pro = 8;  

                }
                $get_meter_rate_new = $this->revised_meter_rate_model->getMeterRate_new($temp_pro, $where);
                //end her
                $temp_diff = $diff_reading;
                
                
                $incriment = 0;
                $amount= 0;
                $ret_ids=''; 
                $meter_rate_id=0;
                $meter_calc_rate=0;

                foreach($get_meter_rate_new as $key=>$val)
                {       
                    $meter_calc_rate = $val['amount'];
                    $meter_calc_factor = $get_meter_calc['meter_rate']; 
                    $meter_rate_id = $val['id']; 
                    if($key==0)
                        $ret_ids .=  $val['id'];
                    else
                        $ret_ids .=  ",".$val['id'];

                    $reading = $incriment + $val['reading'];                 
                    if($reading<=$diff_reading && !empty($val['reading']))
                    {
                        $amount += $meter_calc_rate * $meter_calc_factor * $val['reading']; 
                        $reading = $val['reading'];                                     
                    } 
                    elseif(empty($val['reading']))
                    {
                        $reading = $temp_diff - $reading;
                        $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                        break;  
                    }
                    else
                    {
                        $reading = $temp_diff - $incriment;
                        $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                        break;                   
                    } 
                    
                    $incriment +=$val['reading'];

                } 

                $ret_ids = ltrim($ret_ids,',');
                $meter_calc_factor = $get_meter_calc['meter_rate'];  
                $meter_rate = $meter_calc_factor *  $meter_calc_rate ;       
                $meter_rate_id = $meter_rate_id;
                $total_amount = $amount;
                $total_amount = $meter_rate * $consumtion_per_day * $no_diff;

                if($total_amount>0)
                {

                    $consumer_tax = array();
                    $consumer_tax['consumer_id'] = $consumer_id;
                    $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_tax['charge_type'] = 'Average';
                    $consumer_tax['rate_id'] = $ret_ids;
                    $consumer_tax['initial_reading'] = $gpriv_reading;
                    $consumer_tax['final_reading'] = $gfinal_reading;
                    $consumer_tax['amount'] = $total_amount;
                    $consumer_tax['effective_from'] = $i;
                    $consumer_tax['emp_details_id'] = $this->emp_id;
                    $consumer_tax['created_on'] = date('Y-m-d H:i:s');
    
                    $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                    $demand_nof = $demand_no . str_pad($consumer_tax_id, 6, 'A', STR_PAD_LEFT);
                    
                    
                    $consumer_demand = array();
                    $consumer_demand['consumer_id'] = $consumer_id;
                    $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                    $consumer_demand['generation_date'] = date('Y-m-d');
                    $consumer_demand['unit_amount'] = $meter_rate??0;
                    $consumer_demand['current_meter_reading '] = $gfinal_reading;
                    $consumer_demand['amount'] = $total_amount;
                    $consumer_demand['demand_from'] = $i;
                    $consumer_demand['demand_upto'] = $demand_upto;
                    $consumer_demand['penalty'] = 0;
                    $consumer_demand['emp_details_id'] = $this->emp_id;
                    $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                    $consumer_demand['connection_type'] = 'Meter';
                    $consumer_demand['demand_no'] = $demand_nof;
    
                    $this->consumer_demand_model->insertData($consumer_demand);
                    
                    
                    
                }

                $i = date('Y-m-d', strtotime($demand_upto . "+1 days")); 

                $gpriv_reading = $gfinal_reading ;       
                // $gfinal_reading += ($prev_connection_details['rate_per_month']??0) ;
            
            }
                       
        }        
        elseif ($property_type_id !=3 && $prev_connection_details['connection_type'] == 1 || $prev_connection_details['connection_type'] == 2 && $prev_connection_details['meter_status']==0) 
        {
            if ($upto_date == "") 
            {
                $to_date = date('Y-m-d');
            } 
            else 
            {
                $to_date = $upto_date;
            }
            $args = $this->getMeterArrvg($consumer_id,$to_date,$final_reading);
            $get_initial_reading = $this->initial_meter_reading->initial_meter_reading($consumer_id);
            $initial_reading = $get_initial_reading['initial_reading']??0;            
            $diff_reading = $final_reading - ($initial_reading+($initial_reading<=5 ? 5:0));            
            if(!$args)
            {
                flashToast("error", "Demand Not Generated!!!");
                return;
            }
            elseif(round($args['current_reading']) != round($diff_reading))
            {
                flashToast("error", "Average Reading Not Currect !!!");
                return;
            }
            
            if ($property_type_id == 1) 
            {
                $where = " and category='$category' and ceil($diff_reading)>=from_unit --and ceil($diff_reading)<=upto_unit ";
            } 
            else 
            {
                $where = " and ceil($diff_reading)>=from_unit --and ceil($diff_reading)<=upto_unit ";
            }            
            $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
            //$get_meter_rate = $this->revised_meter_rate_model->getMeterRate($property_type_id, $where);
            //it is tempropery remove it 
            $temp_pro = $property_type_id;
            if(in_array($property_type_id,[7]))
            {
                $temp_pro = 1;
            }
            elseif(in_array($property_type_id,[8]))
            {
                $temp_pro = 4;
            }
            elseif(!in_array($property_type_id,[1,2,3,4,5,6,7]))
            {
                $temp_pro = 8;  

            }
            $get_meter_rate_new = $this->revised_meter_rate_model->getMeterRate_new($temp_pro, $where);
            //end her
            $temp_diff = $diff_reading;
            $incriment = 0;
            $amount= 0;
            $ret_ids=''; 
            $meter_rate_id=0;
            $meter_calc_rate=0;
            foreach($get_meter_rate_new as $key=>$val)
            {       
                $meter_calc_rate = $val['amount'];
                $meter_calc_factor = $get_meter_calc['meter_rate']; 
                $meter_rate_id = $val['id']; 
                if($key==0)
                    $ret_ids .=  $val['id'];
                else
                    $ret_ids .=  ",".$val['id'];

                $reading = $incriment + $val['reading'];                 
                 if($reading<=$diff_reading && !empty($val['reading']))
                 {
                    $amount += $meter_calc_rate * $meter_calc_factor * $val['reading']; 
                    $reading = $val['reading'];                                     
                 } 
                 elseif(empty($val['reading']))
                 {
                    $reading = $temp_diff - $reading;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;  
                 }
                 else
                 {
                    $reading = $temp_diff - $incriment;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;                   
                 } 
                
                $incriment +=$val['reading'];

            }      
            // print_var($amount - ($initial_reading<=5 ? ((5-$initial_reading)*9):0));    
            // print_var($amount);die;        
            $ret_ids = ltrim($ret_ids,',');
            $meter_calc_factor = $get_meter_calc['meter_rate'];  
            $meter_rate = $meter_calc_factor *  $meter_calc_rate ;       
            $meter_rate_id = $meter_rate_id;
            $total_amount = $amount;
            if ($total_amount >= 0) 
            {
                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Average';
                $consumer_tax['rate_id'] = $meter_rate_id;
                $consumer_tax['initial_reading'] = $initial_reading;
                $consumer_tax['final_reading'] = $final_reading;
                $consumer_tax['amount'] = $total_amount;
                $consumer_tax['effective_from'] = date('Y-m-d');
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                $demand_no = $demand_no . str_pad($consumer_tax_id, 6, 'A', STR_PAD_LEFT);

                $consumer_demand = array();
                $consumer_demand['consumer_id'] = $consumer_id;
                $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                $consumer_demand['generation_date'] = date('Y-m-d');
                $consumer_demand['amount'] = $total_amount;
                $consumer_demand['current_meter_reading '] = $final_reading;
                $consumer_demand['unit_amount'] = $args['arvg'];
                $consumer_demand['demand_from'] = $demand_from;
                $consumer_demand['demand_upto'] = $to_date;
                $consumer_demand['emp_details_id'] = $this->emp_id;
                $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                $consumer_demand['connection_type'] = 'Meter';
                $consumer_demand['demand_no'] = $demand_no;

                $demand_id = $this->consumer_demand_model->insertData($consumer_demand);
            }
            if($file && $demand_id)
            {
                $extension = $file->getExtension();                
                $city = $this->ulb_details['city'];

                if($file->isValid() && !$file->hasMoved())
                {
                    $newName =$demand_id.".".$extension;
                    $path=$city.'/meter_reading'.'/';
                    if($file->move(WRITEPATH.'uploads/'.$path,$newName))
                    {
                        $tbl_meter_reading_doc=["demand_id"=>$demand_id,
                                        "file_name"=>$path.$newName,
                                        "meter_no"=>$prev_connection_details['meter_no']??0,
                        ];
                        $this->water_reading_doc->insert_meter_reading_doc($tbl_meter_reading_doc);
                    }
                }                
            }
        }
        
        if ($final_reading > 0) 
        {
            $consumer_last_reading_insert = array();
            $consumer_last_reading_insert['consumer_id'] = $consumer_id;
            $consumer_last_reading_insert['initial_reading'] = $final_reading;
            $consumer_last_reading_insert['emp_details_id'] = $this->emp_id;
            $consumer_last_reading_insert['created_on'] = date('Y-m-d H:i:s');
            $consumer_last_reading_insert['status'] = 1;

            $this->initial_meter_reading->insertData($consumer_last_reading_insert);
        }
        if($consumer_tax_id)
        {
            #-----------------------------------sms send code---------------------------------
            //----------------------sms data -----------------------
            $appliction = $consumer_details;
            $owner = $this->consumer_details_model->consumerDetails($appliction['id']);
            //---------------------- end sms data------------------
            $demands = $this->consumer_demand_model->getTotalAmountByCidTid($appliction['id']);
            $amount = $demands['amount']+$demands['penalty'];                    
            $sms = Water(['amount'=>$amount,'consumer_no'=>$appliction['consumer_no'],"toll_free_no1"=>'1800 8904115','ulb_name'=>$this->ulb_details['ulb_name']],'Consumer Demand');
            if($sms['status'])
            {
                $message = $sms['sms'];
                $templateid = $sms['temp_id'];
                foreach ($owner as $val )
                {
                    $mobile=$val['mobile_no'];
                    $sms_log_data = ['emp_id'=>$this->emp_id,
                                    'ref_id'=>$appliction['id'],
                                    'ref_type'=>'tbl_consumer',
                                    'mobile_no'=>$mobile,
                                    'purpose'=>"Consumer Demand",
                                    'template_id'=>$templateid,
                                    'message'=>$message
                    ];
                    $sms_id =  $this->water_sms_log->insert_sms_log($sms_log_data);
                    $s = send_sms($mobile,$message, $templateid);
                    
                    if($s)
                    {
                        $update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
                        $up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                        
                    } 

                }
            }
            #----------------------------------- end sms send code----------------------------
        }      
        return $consumer_tax_id??null;
    }

    public function getMeterArrvg($consumer_id,$upto_date=null)
    {
        try{
            $get_initial_reading = $this->initial_meter_reading->initial_meter_reading($consumer_id);
            $secondLastReading = $this->initial_meter_reading->getpreviousMeterReding($consumer_id, $get_initial_reading['id']??0);
            $lastDemand = $this->consumer_demand_model->getLastDemand2($consumer_id);
            if ($upto_date == "") 
            {
                $to_date = date('Y-m-d');
            } 
            else 
            {
                $to_date = $upto_date;
            }
            $date1= date_create($lastDemand['demand_upto']);
            $date2=date_create($lastDemand['demand_from']);
            $date3 = date_create($to_date);
            $diff=date_diff($date2,$date1);
            $no_diff = $diff->format("%a");            
            $current_diff = date_diff($date3,$date1)->format("%a");
            $reading = ($get_initial_reading['initial_reading']??0) - ($secondLastReading['initial_reading']??0);
            $arvg = $no_diff!=0 ? round(($reading / $no_diff),2) : 1 ;
            $current_reading = ( $current_diff * $arvg);
    
            return [
                "priv_demand_from"=> $lastDemand['demand_from'],
                "priv_demand_upto"=> $lastDemand['demand_upto'],
                "demand_from"=> $lastDemand['demand_upto'],
                "demand_upto" => $to_date,
                "priv_day_diff"=> $no_diff,
                "current_day_diff"=> $current_diff ,
                "last_reading" => $reading,
                "current_reading"=>$current_reading,
                "arvg" =>$arvg ,
            ];

        }
        catch(Exception $e)
        { 
            return[];
        }
    }

    #2 month Demand Not Demand gerated
    public function meterAverageBilling($consumer_id,$upto_date=null,$final_meter_reading=0,$file=null)
    {
        $demand_id = false; 
        $consumer_tax_id = "";
        $get_demanddetails = $this->consumer_demand_model->getLastDemand2($consumer_id);
        $last_demand_upto = $get_demanddetails['demand_upto']??"";
        $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $prev_connection_details = $this->meter_status_model->getLastConnectionDetails($consumer_details['id']);
        $tax_detals = $this->consumer_tax_model->getData(md5($get_demanddetails['consumer_tax_id']??"0"));

        $ward_mstr_id = $consumer_details['ward_mstr_id'];
        $property_type_id = $consumer_details['property_type_id'];
        $category = !empty(trim($consumer_details['category']))?trim($consumer_details['category']):"APL";
        $ulb_details = session()->get('ulb_dtl')??getUlbDtl();
        $ulb_short_name = substr($ulb_details['city'], 0, 3);
        $ward_no = $this->model_ward_mstr->getWardNoById(['ward_mstr_id' => $ward_mstr_id]);
        $ward_no = isset($ward_no['ward_no']) ? $ward_no['ward_no'] : 0;
        $demand_no = $ulb_short_name . str_pad($ward_no, 3, '0', STR_PAD_LEFT) . str_pad($this->emp_id, 5, '0', STR_PAD_LEFT) . '/';         
        $generation_date = date('Y-m-d');
        $tax_diff_reading = ($tax_detals["final_reading"]??0) - ($tax_detals["initial_reading"]??0);
        $form_date = $get_demanddetails['demand_from']??"";

        $date1= date_create($last_demand_upto);
        $date2=date_create($form_date);
        $diff=date_diff($date2,$date1);
        $no_diff = $diff->format("%a"); 

        $arvg_reading = $no_diff!=0 ? round(($tax_diff_reading / $no_diff),2) : 1 ;
        // print_Var($tax_detals);
        
        
        if(empty($prev_connection_details) || $prev_connection_details['connection_date']=='')
        {
            flashToast("error", "Connection Date Not Found!!!");
            return;
        }
        if(empty($tax_detals))
        {
            flashToast("error", "Last Tax Not Found!!!");
            return;
        }
        if($prev_connection_details['connection_type'] == 3)
        {
            flashToast("error", "Can not Generate average Billig Of this Consumer!!!");
            return ;
        }
        if(in_array($prev_connection_details['connection_type'],[1,2]) && $property_type_id ==3 )
        {
            flashToast("error", "Average bulling Rate Not Available Of this GOV Consumer!!!");
            return ;
        }

        if ($last_demand_upto == "") 
        {
            $last_demand_upto = $prev_connection_details['connection_date'];
            $demand_from = $last_demand_upto;
        }        
        else 
        {
            $demand_from = date('Y-m-d', strtotime($last_demand_upto . "+1 days"));
        }
        $i = $demand_from;

        if ($upto_date == "") 
        {
            $to_date = date("Y-m-t", strtotime("-1 months"));
        } 
        else 
        {
            $month = date('m', strtotime($upto_date));
            $year = date('Y', strtotime($upto_date));

            $curr_month = date('m');
            $to_date = date('Y-m-t', strtotime($upto_date . '-1 months'));
        }

        ($tax_detals["final_reading"]??0) - ($tax_detals["initial_reading"]??0);
        $gpriv_reading = $tax_detals["final_reading"];
        $gfinal_reading = $arvg_reading??0;
        $consumtion_per_day = $gfinal_reading;

        while ($i < $to_date) 
        {   
            // print_Var("******************************");             
            $last_date_of_current_month = date('Y-m-t', strtotime($i));
            
            if ($last_date_of_current_month > $to_date) 
            {
                $last_date_of_current_month = $to_date;
                $demand_upto = date('Y-m-d', strtotime($to_date . "-1 days"));
            } 
            else 
            {
                $demand_upto = date('Y-m-t', strtotime($i));
            }
            $date1= date_create($i);
            $date2=date_create($demand_upto);
            $diff=date_diff($date2,$date1);
            $no_diff = $diff->format("%a")+1;           

            
            $gfinal_reading = $gpriv_reading +($consumtion_per_day*($no_diff)) ;

            $diff_reading = $gfinal_reading - $gpriv_reading;
            // print_Var("no_diff");
            // print_var($no_diff);
            // print_Var("consumtion_per_day");
            // print_Var($consumtion_per_day);

            if ($property_type_id == 1) 
            {
                $where = " and category='$category' and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            } 
            else 
            {
                $where = " and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            }  

            $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
            $temp_pro = $property_type_id;
            if(in_array($property_type_id,[7]))
            {
                $temp_pro = 1;
            }
            elseif(in_array($property_type_id,[8]))
            {
                $temp_pro = 4;
            }
            elseif(!in_array($property_type_id,[1,2,3,4,5,6,7]))
            {
                $temp_pro = 8; 
            }
            $get_meter_rate_new = $this->revised_meter_rate_model->getMeterRate_new($temp_pro, $where);
            //end her
            $temp_diff = $diff_reading;
            
            
            $incriment = 0;
            $amount= 0;
            $ret_ids=''; 
            $meter_rate_id=0;
            $meter_calc_rate=0;

            foreach($get_meter_rate_new as $key=>$val)
            {       
                $meter_calc_rate = $val['amount'];
                $meter_calc_factor = $get_meter_calc['meter_rate']; 
                $meter_rate_id = $val['id']; 
                if($key==0)
                    $ret_ids .=  $val['id'];
                else
                    $ret_ids .=  ",".$val['id'];

                $reading = $incriment + $val['reading'];                 
                if($reading<=$diff_reading && !empty($val['reading']))
                {
                    $amount += $meter_calc_rate * $meter_calc_factor * $val['reading']; 
                    $reading = $val['reading'];                                     
                } 
                elseif(empty($val['reading']))
                {
                    $reading = $temp_diff - $reading;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;  
                }
                else
                {
                    $reading = $temp_diff - $incriment;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;                   
                } 
                
                $incriment +=$val['reading'];

            } 

            $ret_ids = ltrim($ret_ids,',');
            $meter_calc_factor = $get_meter_calc['meter_rate'];  
            $meter_rate = $meter_calc_factor *  $meter_calc_rate ;       
            $meter_rate_id = $meter_rate_id;
            $total_amount = $amount;
            $total_amount = $meter_rate * $consumtion_per_day * $no_diff;
            
            
            

            if($total_amount>0)
            {

                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Bulk Meter demand';
                $consumer_tax['rate_id'] = $ret_ids;
                $consumer_tax['initial_reading'] = $gpriv_reading;
                $consumer_tax['final_reading'] = $gfinal_reading;
                $consumer_tax['amount'] = $total_amount;
                $consumer_tax['effective_from'] = $i;
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');
                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);

                $demand_nof = $demand_no . str_pad($consumer_tax_id, 6, 'A', STR_PAD_LEFT);                
                
                $consumer_demand = array();
                $consumer_demand['consumer_id'] = $consumer_id;
                $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                $consumer_demand['generation_date'] = date('Y-m-d');
                $consumer_demand['unit_amount'] = $meter_rate??0;
                $consumer_demand['current_meter_reading '] = $gfinal_reading;
                $consumer_demand['amount'] = $total_amount;
                $consumer_demand['demand_from'] = $i;
                $consumer_demand['demand_upto'] = $demand_upto;
                $consumer_demand['penalty'] = 0;
                $consumer_demand['emp_details_id'] = $this->emp_id;
                $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                $consumer_demand['connection_type'] = 'Meter';
                $consumer_demand['demand_no'] = $demand_nof;

                $this->consumer_demand_model->insertData($consumer_demand);
                
                // print_var("consumer_demand new");
                // print_var($consumer_demand);
                // print_var("consumer_tax new");
                // print_var($consumer_tax);
                
            }
            if ($gfinal_reading > 0) 
            {
                $consumer_last_reading_insert = array();
                $consumer_last_reading_insert['consumer_id'] = $consumer_id;
                $consumer_last_reading_insert['initial_reading'] = $gfinal_reading;
                $consumer_last_reading_insert['emp_details_id'] = $this->emp_id;
                $consumer_last_reading_insert['created_on'] = date('Y-m-d H:i:s');
                $consumer_last_reading_insert['status'] = 1;

                $this->initial_meter_reading->insertData($consumer_last_reading_insert);
                // print_Var("gfinal_reading");
                // print_var($gfinal_reading);
            }

            $i = date('Y-m-d', strtotime($demand_upto . "+1 days")); 

            $gpriv_reading = $gfinal_reading ;       
            // $gfinal_reading += ($prev_connection_details['rate_per_month']??0) ;
            
            
            
        
        }
        if($consumer_tax_id)
        {
            #-----------------------------------sms send code---------------------------------
            //----------------------sms data -----------------------
            $appliction = $consumer_details;
            $owner = $this->consumer_details_model->consumerDetails($appliction['id']);
            //---------------------- end sms data------------------
            $demands = $this->consumer_demand_model->getTotalAmountByCidTid($appliction['id']);
            $amount = $demands['amount']+$demands['penalty'];                    
            $sms = Water(['amount'=>$amount,'consumer_no'=>$appliction['consumer_no'],"toll_free_no1"=>'1800 8904115','ulb_name'=>$this->ulb_details['ulb_name']],'Consumer Demand');            
            if($sms['status'])
            {
                $message = $sms['sms'];
                $templateid = $sms['temp_id'];
                foreach ($owner as $val )
                {
                    $mobile=$val['mobile_no'];
                    $sms_log_data = ['emp_id'=>$this->emp_id,
                                    'ref_id'=>$appliction['id'],
                                    'ref_type'=>'tbl_consumer',
                                    'mobile_no'=>$mobile,
                                    'purpose'=>"Consumer Demand",
                                    'template_id'=>$templateid,
                                    'message'=>$message
                    ];
                    $sms_id =  $this->water_sms_log->insert_sms_log($sms_log_data);
                    $s = send_sms($mobile,$message, $templateid);
                    
                    if($s)
                    {
                        $update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
                        $up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                        
                    } 

                }
            }
            #----------------------------------- end sms send code----------------------------
        }
        return $consumer_tax_id;
    }
    #------------------------11-03-2023 by sandeep for Worng Demand genration for Meter---------------------------------
    public function generatDiffDemand($consumer_id,$demand_id,$final_reading=0,$initial_reading=0,$connection_type)
    {
        try
        {

            $consumer_tax_id = null;
            $total_amount = 0 ;
            $demand_sql = "SELECT * 
            FROM tbl_consumer_demand 
            WHERE id = $demand_id
            " ;
            $demand = $this->db->query($demand_sql)->getFirstRow("array");
            $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);            
            $ward_mstr_id = $consumer_details['ward_mstr_id'];
            if($demand)
            {   
                $upto_date = $demand["demand_upto"];
                $demand_form = $demand["demand_from"];    
                $generated_ammount = $demand["amount"]; 
    
                $diff_reading = $final_reading - $initial_reading;
    
                $property_type_id = $consumer_details['property_type_id'];
                $category = !empty(trim($consumer_details['category']))?trim($consumer_details['category']):"APL";
                $ulb_details = session()->get('ulb_dtl')??getUlbDtl();
                if ($property_type_id == 1) 
                {
                    $where = " and category='$category' and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
                } 
                else 
                {
                    $where = " and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
                } 
                        
                $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
                // $get_meter_rate = $this->revised_meter_rate_model->getMeterRate($property_type_id, $where);
                //it is tempropery remove it 
                $temp_pro = $property_type_id;
                if(in_array($property_type_id,[7]))
                {
                    $temp_pro = 1;
                }
                elseif(in_array($property_type_id,[8]))
                {
                    $temp_pro = 4;
                }
                elseif(!in_array($property_type_id,[1,2,3,4,5,6,7]))
                {
                    $temp_pro = 8;  
    
                }
                $get_meter_rate_new = $this->revised_meter_rate_model->getMeterRate_new($temp_pro, $where);
                //end her
                   
                $temp_diff = $diff_reading;
                $incriment = 0;
                $amount= 0;
                $ret_ids=''; 
                $meter_rate_id=0;
                $meter_calc_rate=0;
                foreach($get_meter_rate_new as $key=>$val)
                {       
                    $meter_calc_rate = $val['amount'];
                    $meter_calc_factor = $get_meter_calc['meter_rate']; 
                    $meter_rate_id = $val['id']; 
                    if($key==0)
                        $ret_ids .=  $val['id'];
                    else
                        $ret_ids .=  ",".$val['id'];
    
                    $reading = $incriment + $val['reading'];                 
                     if($reading<=$diff_reading && !empty($val['reading']))
                     {
                        $amount += $meter_calc_rate * $meter_calc_factor * $val['reading']; 
                        $reading = $val['reading'];                                     
                     } 
                     elseif(empty($val['reading']))
                     {
                        $reading = $temp_diff - $reading;
                        $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                        break;  
                     }
                     else
                     {
                        $reading = $temp_diff - $incriment;
                        $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                        break;                   
                     } 
                    
                    $incriment +=$val['reading'];
    
                }       
                // print_var($amount - ($initial_reading<=5 ? ((5-$initial_reading)*9):0));   
                // print_var(ltrim($ret_ids,',')); 
                // print_var($amount);die; 
    
                
                $ret_ids = ltrim($ret_ids,',');
                $meter_calc_factor = $get_meter_calc['meter_rate'];  
                $meter_rate = $meter_calc_factor *  $meter_calc_rate ;       
                $meter_rate_id = $meter_rate_id;
                $total_amount = $amount - $generated_ammount;                
                $diff_demand_genrated_sql1 = "INSERT INTO tbl_diff_demand_genrated
                                            (consumer_id,demand_id,tax_id,genrated_amount,deff_amount,demand_type,emp_details_id)
                                            VALUES
                                            ($consumer_id,$demand_id,".$demand["consumer_tax_id"].",$generated_ammount,$total_amount,'Old',".$this->emp_id.")
                ";  
                // print_var($diff_demand_genrated_sql1);                  
                $this->db->query($diff_demand_genrated_sql1)->getResultArray();
                if ($total_amount >0) 
                {
                    $consumer_tax = array();
                    $consumer_tax['consumer_id'] = $consumer_id;
                    $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                    $consumer_tax['charge_type'] = 'Deffrent Ammount';
                    $consumer_tax['rate_id'] = $meter_rate_id;
                    $consumer_tax['initial_reading'] = $initial_reading;
                    $consumer_tax['final_reading'] = $final_reading;
                    $consumer_tax['amount'] = $total_amount;
                    $consumer_tax['effective_from'] = date('Y-m-d');
                    $consumer_tax['emp_details_id'] = $this->emp_id;
                    $consumer_tax['created_on'] = date('Y-m-d H:i:s');
    
                    $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
    
                    $consumer_demand = array();
                    $consumer_demand['consumer_id'] = $consumer_id;
                    $consumer_demand['ward_mstr_id'] = $demand["ward_mstr_id"];
                    $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                    $consumer_demand['generation_date'] = date('Y-m-d');
                    $consumer_demand['amount'] = $total_amount;
                    $consumer_demand['current_meter_reading '] = $final_reading;
                    $consumer_demand['unit_amount'] = $meter_rate;
                    $consumer_demand['demand_from'] = $demand["demand_from"];
                    $consumer_demand['demand_upto'] = $demand["demand_upto"];
                    $consumer_demand['emp_details_id'] = $this->emp_id;
                    $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                    $consumer_demand['connection_type'] = $demand["connection_type"];
                    $consumer_demand['demand_no'] = $demand["demand_no"];
    
                    $demand_id = $this->consumer_demand_model->insertData($consumer_demand);
                    if($demand_id)
                    {
                        $diff_demand_genrated_sql = "INSERT INTO tbl_diff_demand_genrated
                                                    (consumer_id,demand_id,tax_id,genrated_amount,demand_type,emp_details_id)
                                                    VALUES
                                                    ($consumer_id,$demand_id,$consumer_tax_id,$total_amount,'New',".$this->emp_id.")
                        ";
                        $this->db->query($diff_demand_genrated_sql)->getResultArray();
                    }
                }
                
            }
            // print_var($final_reading);die;
            // inserting last reading at the time of meter change        
            // if($consumer_tax_id)
            // {
            //     #-----------------------------------sms send code---------------------------------
            //     //----------------------sms data -----------------------
            //     $appliction = $consumer_details;
            //     $owner = $this->consumer_details_model->consumerDetails($appliction['id']);
            //     //---------------------- end sms data------------------
            //     $demands = $this->consumer_demand_model->getTotalAmountByCidTid($appliction['id']);
            //     $amount = $demands['amount']+$demands['penalty'];                    
            //     $sms = Water(['amount'=>$amount,'consumer_no'=>$appliction['consumer_no'],"toll_free_no1"=>'1800 8904115','ulb_name'=>$this->ulb_details['ulb_name']],'Consumer Demand');
            //     if($sms['status'])
            //     {
            //         $message = $sms['sms'];
            //         $templateid = $sms['temp_id'];
            //         foreach ($owner as $val )
            //         {
            //             $mobile=$val['mobile_no'];
            //             $sms_log_data = ['emp_id'=>$this->emp_id,
            //                             'ref_id'=>$appliction['id'],
            //                             'ref_type'=>'tbl_consumer',
            //                             'mobile_no'=>$mobile,
            //                             'purpose'=>"Consumer Demand",
            //                             'template_id'=>$templateid,
            //                             'message'=>$message
            //             ];
            //             $sms_id =  $this->water_sms_log->insert_sms_log($sms_log_data);
            //             $s = send_sms($mobile,$message, $templateid);
                        
            //             if($s)
            //             {
            //                 $update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
            //                 $up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                            
            //             } 
    
            //         }
            //     }
            // }
            return ["status"=>true,"diffrent_ammount"=>$total_amount??0];
        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            print_var($consumer_id);
            print_var($e->getFile());
            print_var($e->getLine());
            die;
            return ["status"=>false,"diffrent_ammount"=>0];
        }
    }

    #------------------------11-03-2023 by sandeep for Worng Demand genration for Meter End---------------------------------
    
    /*public function tax_generation()
    {
        
        if($this->request->getMethod()=='post')
        {
             $inputs=filterSanitizeStringtoUpper($this->request->getVar());
             //print_r($inputs);
             $data=array();
    
             $data['consumer_id']=$inputs['consumer_id'];
             $data['final_meter_reading']=$inputs['final_meter_reading'];
             $data['is_meter_ok']=$inputs['is_meter_ok'];
             $data['meter_destroy_date']=$inputs['meter_destroy_date'];
             $data['ward_mstr_id']=$inputs['ward_mstr_id'];
             $data['property_type_id']=$inputs['property_type_id'];
             $data['area_sqft']=$inputs['area_sqft'];
             
    
             $get_initial_reading=$this->initial_meter_reading->initial_meter_reading($data['consumer_id']);
             //print_r($get_initial_reading);
             $initial_reading=$get_initial_reading['initial_reading'];
             $last_reading_date=$get_initial_reading['initial_date'];
             $last_demand_generated=$this->consumer_demand_model->getLastDemand(md5($data['consumer_id']));
             //print_r($last_demand_generated);
             //exit();
             
             $curr_date=date('Y-m-d');
             $date_diff = abs(strtotime($curr_date) - strtotime($last_reading_date));
             $months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
    
             $get_meter_rate_details=$this->fixed_meter_rate_model->getMeteredRateId($data);
    
             $get_fixed_rate_details=$this->fixed_meter_rate_model->getFixedRateId($data);
             $fixed_amount=$get_fixed_rate_details['amount'];
    
             $fixed_rate_amount=$fixed_amount*$months;
                
                
             $metered_rateid=$get_meter_rate_details['id'];
             $metered_amount=$get_meter_rate_details['amount'];
            
             $metered_rate_amount=($data['final_meter_reading']-$initial_reading)*$metered_amount;
    
             
    
             $get_count=$this->consumer_tax_model->getCount($data['consumer_id']);
             $count=$get_count['count'];
    
    
    
             if($inputs['is_meter_ok']=='NO')
             {
              
    
                $meter_status=array();
                $meter_status['consumer_id']=$data['consumer_id'];
                $meter_status['meter_status']=$data['is_meter_ok'];
                $meter_status['meter_destroy_date']=$data['meter_destroy_date'];
    
                $insert_meter_id= $this->meter_status_model->insertData($meter_status);
    
                if($metered_rate_amount>$fixed_rate_amount)
                {
                    $demand_amount=$metered_rate_amount;
                }
                else
                {
                    $demand_amount=$fixed_rate_amount;
                }
                
                
                $get_fixed_rate_changecount=$this->fixed_meter_rate_model->getFixedRateChangeCount($data);
                $count_ratecnt=$get_fixed_rate_changecount['count'];
                
                if($last_demand_generated['generation_date']!="")
                {
                    
                    $initial_date=date('Y-m-01',strtotime("+1 month",strtotime($last_demand_generated['generation_date'])));
    
                }
                else
                {
                   
                    $initial_date= date('Y-m-01',strtotime("+1 month",strtotime($last_reading_date)));
                }
                
              
                
                if($count_ratecnt>1)
                {
                    
                  
                    
                    for($i=$initial_date;$i<date('Y-m-01',strtotime($curr_date));$i=date('Y-m-01',$i+strtotime("+1 month",strtotime($i))))
                    {
                        
                        $get_amount=$this->fixed_meter_rate_model->getFixedRatebyEffectDate($data);
                             
    
                    }
    
                }
                else
                {
    
                    $fixed_rate_id=$get_fixed_rate_details['id'];
    
                    $consumer_tax=array();
                    $consumer_tax['consumer_id']=$data['consumer_id'];
                    $consumer_tax['ward_mstr_id']=$data['ward_mstr_id'];
                    $consumer_tax['charge_type']='Fixed';
                    $consumer_tax['rate_id']=$fixed_rate_id;
                    $consumer_tax['initial_reading']=$initial_reading;
                    $consumer_tax['final_reading']=$data['final_meter_reading'];
                    $consumer_tax['amount']=$fixed_amount;
                    $consumer_tax['effective_from']=$initial_date;
                    $consumer_tax['emp_details_id']=$this->emp_id;
                    $consumer_tax['created_on']=date('Y-m-d H:i:s');
                    
                    $consumer_tax_id=$this->consumer_tax_model->insertData($consumer_tax);
    
                    
                    for($i=$initial_date;$i<date('Y-m-01',strtotime($curr_date));$i=date('Y-m-01',$i+strtotime("+1 month",strtotime($i))))
                    {
                        
                      
    
                        $consumer_demand=array();
                        $consumer_demand['consumer_id']=$data['consumer_id'];
                        $consumer_demand['ward_mstr_id']=$data['ward_mstr_id'];
                        $consumer_demand['consumer_tax_id']=$consumer_tax_id;
                        $consumer_demand['generation_date']=$i;
                        $consumer_demand['amount']=$fixed_amount;
                        $consumer_demand['emp_details_id']=$this->emp_id;
                        $consumer_demand['created_on']=date('Y-m-d H:i:s');
                        
                        $this->consumer_demand_model->insertData($consumer_demand);
    
    
    
                    }
                    
                    return $this->response->redirect(base_url('WaterViewUserChargeMobile/view_usercharge/'.md5($data['consumer_id'])));
    
    
                    
                }
    
             }
             else
             {
    
              
                if($count==0)
                {
    
                    $consumer_tax=array();
                    $consumer_tax['consumer_id']=$data['consumer_id'];
                    $consumer_tax['ward_mstr_id']=$data['ward_mstr_id'];
                    $consumer_tax['charge_type']='Meter';
                    $consumer_tax['rate_id']=$metered_rateid;
                    $consumer_tax['initial_reading']=$initial_reading;
                    $consumer_tax['final_reading']=$data['final_meter_reading'];
                    $consumer_tax['amount']=$metered_rate_amount;
                    $consumer_tax['effective_from']=date('Y-m-d');
                    $consumer_tax['emp_details_id']=$this->emp_id;
                    $consumer_tax['created_on']=date('Y-m-d H:i:s');
                    
                    $consumer_tax_id=$this->consumer_tax_model->insertData($consumer_tax);
    
                   
                    $consumer_demand=array();
                    $consumer_demand['consumer_id']=$data['consumer_id'];
                    $consumer_demand['ward_mstr_id']=$data['ward_mstr_id'];
                    $consumer_demand['consumer_tax_id']=$consumer_tax_id;
                    $consumer_demand['generation_date']=date('Y-m-d H:i:s');
                    $consumer_demand['amount']=$metered_rate_amount;
                    $consumer_demand['emp_details_id']=$this->emp_id;
                    $consumer_demand['created_on']=date('Y-m-d H:i:s');
                    
    
    
                    
    
                    $this->consumer_demand_model->insertData($consumer_demand);
    
                    return $this->response->redirect(base_url('WaterViewUserChargeMobile/view_usercharge/'.md5($data['consumer_id'])));
    
                }
                    
                    
             }
    
    
    
    
        }
       
    
    }*/

    /*public function generate_demand($consumer_id,$final_reading)
    {
        $get_demanddetails=$this->consumer_demand_model->getLastDemand($consumer_id);
        $demand_upto=$get_demanddetails['demand_upto'];
        $consumer_details=$this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $area_sqmt=$consumer_details['area_sqmt'];
        $ward_mstr_id=$consumer_details['ward_mstr_id'];
        $property_type_id=$consumer_details['consumer_details'];
        $generation_date=date('Y-m-d');
    
        if($demand_upto=="")
        {
            $connection_dtls=$this->meter_status_model->getAllConnectionDetails($consumer_id);
    
            foreach($connection_dtls as $val)
            {   
    
                $connection_id=$val['id'];
    
                $get_next_conn_date=$this->meter_status_model->getNextConnectionDetails($consumer_id,$id);
    
                $next_conn_date=$get_next_conn_date['connection_date'];
    
                $connection_date=$val['connection_date'];
                $connection_type=$val['connection_type'];
    
                if($connection_type==1)
                {
                    $conn_type='Meter';
                    $charge_type='Meter';
                }
                else if($connection_type==2)
                {
                    $conn_type='Gallon';
                    $charge_type='Gallon';
                }
                else if($connection_type==3)
                {
                    $conn_type='Fixed';
                    $charge_type='Fixed';
                }
    
                $get_rate=$this->fixed_meter_rate_model->getRate($conn_type,$property_type_id,$area_sqmt);
                $rate_amount=$get_rate['amount'];
                $rate_id=$get_rate['id'];
    
                $consumer_tax=array();
                $consumer_tax['ward_mstr_id']=$ward_mstr_id;
                $consumer_tax['consumer_id']=$consumer_id;
                $consumer_tax['charge_type']=$charge_type;
                $consumer_tax['rate_id']=$rate_id;
                $consumer_tax['initial_reading']=0;
                $consumer_tax['final_reading']=0;
                $consumer_tax['effective_from']=$connection_date;
                $consumer_tax['amount']=$rate_amount;
                $consumer_tax['emp_details_id']=$this->emp_id;
                $consumer_tax['created_on']=date('Y-m-d H:i:s');
                
                
    
                if($connection_type==3)
                {
    
                   while($connection_date<$next_conn_date)
                   {
                        echo $demand_from=date('01-m-Y',strtotime($connection_date));
                        echo $demand_upto=date('t-m-Y',strtotime($connection_date));
                        exit();
    
                        $insert_demand="insert into tbl_consumer_demand(consumer_id,ward_mstr_id,consumer_tax_id,generation_date,amount,paid_status,emp_details_id,created_on,status,demand_from,demand_upto)values($consumer_id,$ward_mstr_id,1,'$generation_date',$rate_amount,0,".$this->emp_id.",'".date('Y-m-d H:i:s')."',1,'$demand_from','$demand_upto')";
    
                        $connection_date=date("Y-m-d",strtotime("+1 month",$connection_date));
    
                   }
    
                }
    
            }
        }
    
    
    }*/

    /* public function generate_demand_prev($consumer_id,$final_reading,$consumer_connection_dtls)
    {
        $get_demanddetails=$this->consumer_demand_model->getLastDemand($consumer_id);
        $demand_upto=$get_demanddetails['demand_upto'];
        $consumer_details=$this->model_water_consumer->consumerDetailsbyid($consumer_id);
        //print_r($consumer_details);
    
        $area_sqmt=$consumer_details['area_sqmt'];
        $ward_mstr_id=$consumer_details['ward_mstr_id'];
        $property_type_id=$consumer_details['property_type_id'];
        $generation_date=date('Y-m-d');
        
        
           // echo $consumer_id;
    
            $connection_dtls=$this->meter_status_model->getLastConnectionDetails($consumer_id);
                
                 
                $connection_id=$connection_dtls['id'];
    
                $get_prev_conn_date=$this->meter_status_model->getPreviousConnectionDetails($consumer_id,$connection_id);
                //  print_r($get_prev_conn_date);
    
                $prev_conn_date=$get_prev_conn_date['connection_date'];
    
                $next_conn_date=$connection_dtls['connection_date'];
                $connection_type=$get_prev_conn_date['connection_type'];
    
                if($connection_type==1)
                {
                    $conn_type='Meter';
                    $charge_type='Meter';
                }
                else if($connection_type==2)
                {
                    $conn_type='Gallon';
                    $charge_type='Gallon';
                }
                else if($connection_type==3)
                {
                    $conn_type='Fixed';
                    $charge_type='Fixed';
                }
    
                $get_rate=$this->rate_chart_model->getRate($conn_type,$property_type_id,$area_sqmt);
                $rate_amount=$get_rate['amount'];
                $rate_id=$get_rate['id'];
    
                $consumer_tax=array();
                $consumer_tax['ward_mstr_id']=$ward_mstr_id;
                $consumer_tax['consumer_id']=$consumer_id;
                $consumer_tax['charge_type']=$charge_type;
                $consumer_tax['rate_id']=$rate_id;
                $consumer_tax['initial_reading']=0;
                $consumer_tax['final_reading']=0;
                $consumer_tax['effective_from']=$connection_date;
                $consumer_tax['amount']=$rate_amount;
                $consumer_tax['emp_details_id']=$this->emp_id;
                $consumer_tax['created_on']=date('Y-m-d H:i:s');
                
                
    
    /*if($connection_type==3)
                {
    */
    /* echo $prev_conn_date;
                    echo "--".$next_conn_date;
                    
                   while($prev_conn_date<$next_conn_date)
                   {
                        echo $demand_from=date('01-m-Y',strtotime($prev_conn_date));
    
    
                        echo $demand_upto=date('t-m-Y',strtotime($prev_conn_date));
                         if($next_conn_date>$demand_upto)
                         {
    
                         }
    
                        exit();
                        
                        $insert_demand="insert into tbl_consumer_demand(consumer_id,ward_mstr_id,consumer_tax_id,generation_date,amount,paid_status,emp_details_id,created_on,status,demand_from,demand_upto)values($consumer_id,$ward_mstr_id,1,'$generation_date',$rate_amount,0,".$this->emp_id.",'".date('Y-m-d H:i:s')."',1,'$demand_from','$demand_upto')";
    
                        $connection_date=date("Y-m-d",strtotime("+1 month",$connection_date));
    
                   }
    
    /*   }*/

    /*   if(count($consumer_connection_dtls)>0)
                {
                    $this->update_connection_type($consumer_connection_dtls);
                }
    
    }*/
}
