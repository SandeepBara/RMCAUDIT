<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;
use App\Models\model_fy_mstr;

use Exception;


class AllModuleDCBReport extends AlphaController
{   

    protected $db;
    protected $property;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    protected $water_report_model;
    
    
    public function __construct()
    {   
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property = db_connect($db_name);     
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->water_report_model=new WaterReportModel($this->db);
        $this->model_fy_mstr=new model_fy_mstr($this->dbSystem);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}
    
    public function index()
    {
    	$data=array();
        $prop_where=null;
        $water_where=null;
        $demand_water_where =null;
        if($this->request->getMethod()=='post')
        {
            $join="";
            $curr_fy=$data['fin_year']=$this->request->getVar('fin_year');
            $explode=explode('-', $data['fin_year']);
            $first=$explode[0];
            $second=$explode[1];

            $last_date=$first.'-03-31';
            $curr_last_date=$second.'-03-31';

            //updated rule
            $fy_start_date=$first.'-04-01';
            $fy_start_date = date('Y-m-d',strtotime($fy_start_date));
            $fy_end_date=$second.'-03-31';
            $fy_end_date = date('Y-m-d',strtotime($fy_end_date));



            $data['property_type1']=$this->request->getVar('property_type1');
            $data['property_type2']=$this->request->getVar('property_type2');
            $data['property_type3']=$this->request->getVar('property_type3');
            $st=0;
            $stt=0;
            
            if($data['property_type1']==1)
            {   
                //$join=" right join view_residential_consumer on tbl_consumer.id=view_residential_consumer.id";
                $water_where="tbl_consumer.property_type_id=1";
                $prop_where.="  tbl_prop_dtl.holding_type in (''PURE_RESIDENTIAL'')";
                
            }
            if($data['property_type2']==2)
            {
               // $join.=" right join view_non_residential_consumer on view_non_residential_consumer.id=tbl_consumer.id ";
                if($prop_where!="")
                {
                    $st=1;
                    $prop_where.=" or ( ";
                }
                $prop_where.="  tbl_prop_dtl.holding_type not in (''PURE_RESIDENTIAL'',''PURE_GOVERNMENT'',''MIX_GOVERNMENT'')";

                if($st==1)
                {
                    $prop_where.=" ) ";
                }

                if($water_where!="")
                {
                    $stt=1;
                    $water_where.=" or ( ";
                }
                $water_where.=" tbl_consumer.property_type_id not in(1,3)";

                if($stt==1)
                {
                    $water_where.=" ) ";
                }
            }
            if($data['property_type3']==3)
            {
               // $join.=" right join view_gov_consumer on view_gov_consumer.id=tbl_consumer.id ";
                if($prop_where!="")
                {
                    $st=1;
                    $prop_where.=" or ( ";
                }

                $prop_where.="  tbl_prop_dtl.holding_type in (''PURE_GOVERNMENT'',''MIX_GOVERNMENT'')";

                if($st==1)
                {
                    $prop_where.=" ) ";
                }

                if($water_where!="")
                {
                    $stt=1;
                    $water_where.=" or ( ";
                }
                $water_where.=" tbl_consumer.property_type_id=3";

                if($stt==1)
                {
                    $water_where.=" ) ";
                }
                

            }

            
        }
        else
        {

            if(date('m')=='03' || date('m')=='02' || date('m')=='01')
            {
                 $last_date=(date('Y')-1).'-03-31';
                 $curr_last_date=date('Y').'-03-31';
                 //updated rule
                 $fy_start_date=(date('Y')-1).'-04-01';
                 $fy_start_date = date('Y-m-d',strtotime($fy_start_date));
                 $fy_end_date=date('Y').'-03-31';
                $fy_end_date = date('Y-m-d',strtotime($fy_end_date));

                

                 $curr_fy=(date('Y')-1).'-'.date('Y');
                 $data['fin_year']=$curr_fy;
            }
            else
            {
                 $last_date=date('Y').'-03-31';
                 
                 $curr_last_date=(date('Y')+1).'-03-31';

                 //updated rule
                 $fy_start_date=date('Y').'-04-01';
                 $fy_start_date = date('Y-m-d',strtotime($fy_start_date));
                 $fy_end_date=(date('Y')+1).'-03-31';
                $fy_end_date = date('Y-m-d',strtotime($fy_end_date));


                

                 $curr_fy=date('Y').'-'.(date('Y')+1);
                 $data['fin_year']=$curr_fy;


            }

        }

        
        $arr['fy']=$curr_fy;
        $get_fy=$this->model_fy_mstr->getFyByFy($arr);
        $fy_mstr_id=$get_fy['id'];

        if($prop_where!="")
        {
            $prop_where=" where $prop_where";
        }

        if($water_where!="")
        {
        	$water_where=" ( $water_where )";
        }
        // $data['all_module_dcb']=$this->water_report_model->viewDcbNew($last_date,$curr_last_date,$fy_mstr_id,$water_where,$prop_where);
        $data['all_module_dcb']=$this->water_report_model->viewDcbNew($fy_start_date,$fy_end_date,$fy_mstr_id,$water_where,$prop_where);

		// print_var($data);
        // return;

        return view('report/all_module_demand',$data);
        
    }

    public function allModuleDCB()
    {
        $data=array();

        $prop_where='';
        $water_where='';
	        if($this->request->getMethod()=='post')
	        {
                 	$join="";
		            $curr_fy=$data['fin_year']=$this->request->getVar('fin_year');
		            $explode=explode('-', $data['fin_year']);
		            $first=$explode[0];
		            $second=$explode[1];

		            $last_date=$first.'-03-31';
		            $curr_last_date=$second.'-03-31';

                    //updated rule
                    $fy_start_date=$first.'-04-01';
                    $fy_start_date = date('Y-m-d',strtotime($fy_start_date));
                    $fy_end_date=$second.'-03-31';
                    $fy_end_date = date('Y-m-d',strtotime($fy_end_date));
                    

		            $data['property_type1']=$this->request->getVar('property_type1');
		            $data['property_type2']=$this->request->getVar('property_type2');
		            $data['property_type3']=$this->request->getVar('property_type3');
		            
                    $st=null;
                    $stt=null;
		            if($data['property_type1']==1)
		            {   
		                //$join=" right join view_residential_consumer on tbl_consumer.id=view_residential_consumer.id";
		                $water_where="tbl_consumer.property_type_id=1";
		                $prop_where.="  tbl_prop_dtl.holding_type in (''PURE_RESIDENTIAL'')";
		                
		            }
		            if($data['property_type2']==2)
		            {
		               // $join.=" right join view_non_residential_consumer on view_non_residential_consumer.id=tbl_consumer.id ";
		                if($prop_where!="")
		                {
		                    $st=1;
		                    $prop_where.=" or ( ";
		                }
		                $prop_where.="  tbl_prop_dtl.holding_type not in (''PURE_RESIDENTIAL'',''PURE_GOVERNMENT'',''MIX_GOVERNMENT'')";

		                if($st==1)
		                {
		                    $prop_where.=" ) ";
		                }

		                if($water_where!="")
		                {
		                    $stt=1;
		                    $water_where.=" or ( ";
		                }
		                $water_where.=" tbl_consumer.property_type_id not in(1,3)";

		                if($stt==1)
		                {
		                    $water_where.=" ) ";
		                }
		            }
		            if($data['property_type3']==3)
		            {
		               // $join.=" right join view_gov_consumer on view_gov_consumer.id=tbl_consumer.id ";
		                if($prop_where!="")
		                {
		                    $st=1;
		                    $prop_where.=" or ( ";
		                }

		                $prop_where.="  tbl_prop_dtl.holding_type in (''PURE_GOVERNMENT'',''MIX_GOVERNMENT'')";

		                if($st==1)
		                {
		                    $prop_where.=" ) ";
		                }

		                if($water_where!="")
		                {
		                    $stt=1;
		                    $water_where.=" or ( ";
		                }
		                $water_where.=" tbl_consumer.property_type_id=3";

		                if($stt==1)
		                {
		                    $water_where.=" ) ";
		                }
		                

		            }

            }
            else
            {

                if(date('m')=='03' || date('m')=='02' || date('m')=='01')
                {
                     $last_date=(date('Y')-1).'-03-31';
                     $curr_last_date=date('Y').'-03-31';

                     $curr_fy=(date('Y')-1).'-'.date('Y');

                     //updated rule
                    $fy_start_date=(date('Y')-1).'-04-01';
                    $fy_start_date = date('Y-m-d',strtotime($fy_start_date));
                    $fy_end_date=date('Y').'-03-31';
                    $fy_end_date = date('Y-m-d',strtotime($fy_end_date));
                }
                else
                {
                     $last_date=date('Y').'-03-31';
                     $curr_last_date=(date('Y')+1).'-03-31';

                     $curr_fy=date('Y').'-'.(date('Y')+1);

                      //updated rule
                    $fy_start_date=date('Y').'-04-01';
                    $fy_start_date = date('Y-m-d',strtotime($fy_start_date));
                    $fy_end_date=(date('Y')+1).'-03-31';
                    $fy_end_date = date('Y-m-d',strtotime($fy_end_date));

                }

            }
            
            $arr['fy']=$curr_fy;
            $get_fy=$this->model_fy_mstr->getFyByFy($arr);
            $fy_mstr_id=$get_fy['id'];

            if($prop_where!="")
            {
                $prop_where=" where $prop_where";
            }
            else
            {
            	$prop_where=" where 1=1";
            }

            if($water_where!="")
	        {
	        	$water_where=" ( $water_where )";
	        }
            // $data['all_module_dcb']=$this->water_report_model->viewAllModuleDcbNew($last_date,$curr_last_date,$fy_mstr_id,$water_where,$prop_where);
            $data['all_module_dcb']=$this->water_report_model->viewAllModuleDcbNew($fy_start_date,$fy_end_date,$fy_mstr_id,$water_where,$prop_where);
            
            // print_r($data['all_module_dcb']);
            // print_var($data);

            // return;
            return view('report/all_module_dcb',$data);
            

    }
    
}

