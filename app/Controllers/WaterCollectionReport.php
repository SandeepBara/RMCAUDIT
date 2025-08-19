<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_pagination;
use App\Models\model_ward_mstr;

use App\Models\WaterReportModel;
use Exception;


// SITE INSPECTION DETAILS REPORT
class WaterCollectionReport extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;

    protected $water_report_model;
    protected $ward_model;
    //protected $db_name;


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
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system);
        }

        helper(['form']);
        //$db_name = db_connect("db_rmc_property");

        $this->water_report_model=new WaterReportModel($this->db);
        $this->ward_model=new model_ward_mstr($this->dbSystem);

    }

    public function index()
    {

        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_r($data);
        $data['view']=$view??null;

        $where=1;
        if($this->request->getMethod()=='post')
        {

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
            $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');
            $data['ward_id']=$inputs['ward_id'];


            if($data['ward_id']!="")
            {
                $where=" and tbl_transaction.ward_mstr_id=".$data['ward_id']." and transaction_date between '".$data['date_from']."' and '".$data['date_upto']."'";
                $where_conn=" and ward_mstr_id=".$data['ward_id']." and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
                $where_adv=" and ward_mstr_id=".$data['ward_id']." and payment_date between '".$data['date_from']."' and '".$data['date_upto']."'";

            }
            else
            {
                $where=" and tbl_transaction.transaction_date between '".$data['date_from']."' and '".$data['date_upto']."'";
                $where_conn=" and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
                $where_adv=" and payment_date between '".$data['date_from']."' and '".$data['date_upto']."'";

            }
            
            $success_transaction=$this->water_report_model->collectionPaymentModeWise($where);
            $connectionType_transaction=$this->water_report_model->collectionConnectionTypeWise($where);// new || regula
            
            $bounced_transaction=$this->water_report_model->bouncedCollectionPaymentModeWise($where);
            $connection_type_wise_transaction=$this->water_report_model->connectionTypeWisePayment($where_conn);
            
            $get_adv_dtls=$this->water_report_model->consumerTotalAdvance($where_adv);
            $data['advance_count']=$get_adv_dtls['count'];
            $data['advance_amt']=$get_adv_dtls['advance_amt'];
            $property_type_wise_transaction=$this->water_report_model->propertyTypeWiseCollection($where);
            
            // print_var($property_type_wise_transaction);die;

            $cash_count=$cheque_count=$dd_count=$online_count=$cash_amt=$cheque_amt=$dd_amt=$online_amt=
            $cash_consumer=$cheque_consumer=$dd_consumer=$online_consumer=0;

            $bounced_cash_count=$bounced_cheque_count=$bounced_dd_count=$bounced_online_count=$bounced_cash_amt=
            $bounced_cheque_amt=$bounced_dd_amt=$bounced_online_amt=$bounced_cash_consumer=
            $bounced_cheque_consumer=$bounced_dd_consumer=$bounced_online_consumer=0;

            if($success_transaction)
            {
                foreach ($success_transaction as $value)
                {

                    if($value['payment_mode']=='CASH')
                    {
                        $data['cash_count']=$value['count_trans'];
                        $data['cash_consumer']=$value['count_consumer'];
                        $data['cash_amt']=$value['paid_amount'];
                    }
                    else if($value['payment_mode']=='CHEQUE')
                    {
                        $data['cheque_count']=$value['count_trans'];
                        $data['cheque_consumer']=$value['count_consumer'];
                        $data['cheque_amt']=$value['paid_amount'];
                    }
                    else if($value['payment_mode']=='DD')
                    {
                        $data['dd_count']=$value['count_trans'];
                        $data['dd_consumer']=$value['count_consumer'];
                        $data['dd_amt']=$value['paid_amount'];
                    }
                    else if($value['payment_mode']=='ONLINE')
                    {
                        $data['online_count']=$value['count_trans'];
                        $data['online_consumer']=$value['count_consumer'];
                        $data['online_amt']=$value['paid_amount'];
                    }

                }

            }


            if($connectionType_transaction)
            {
                foreach ($connectionType_transaction as $value)
                {

                    if($value['connection_type']=='NEW CONNECTION')
                    {
                        $data['new_connection_count']=$value['count_trans'];
                        $data['new_connection_consumer']=$value['count_consumer'];
                        $data['new_connection_amt']=$value['paid_amount'];
                    }
                    else
                    {
                        $data['regularization_count']=$value['count_trans'];
                        $data['regularization_consumer']=$value['count_consumer'];
                        $data['regularization_amt']=$value['paid_amount'];
                    }

                }

            }


            if($bounced_transaction)
            {
                foreach ($bounced_transaction as $value2)
                {

                    if($value2['payment_mode']=='CASH')
                    {
                        $data['bounced_cash_count']=$value2['count_trans'];
                        $data['bounced_cash_consumer']=$value2['count_consumer'];
                        $data['bounced_cash_amt']=$value2['paid_amount'];
                    }
                    else if($value2['payment_mode']=='CHEQUE')
                    {
                        $data['bounced_cheque_count']=$value2['count_trans'];
                        $data['bounced_cheque_consumer']=$value2['count_consumer'];
                        $data['bounced_cheque_amt']=$value2['paid_amount'];
                    }
                    else if($value2['payment_mode']=='DD')
                    {
                        $data['bounced_dd_count']=$value2['count_trans'];
                        $data['bounced_dd_consumer']=$value2['count_consumer'];
                        $data['bounced_dd_amt']=$value2['paid_amount'];
                    }
                    else if($value2['payment_mode']=='ONLINE')
                    {
                        $data['bounced_online_count']=$value2['count_trans'];
                        $data['bounced_online_consumer']=$value2['count_consumer'];
                        $data['bounced_online_amt']=$value2['paid_amount'];
                    }

                }
            }


            $meter_amount=$fixed_amount=0;
            if($connection_type_wise_transaction)
            {
                foreach($connection_type_wise_transaction as $value3)
                {


                    if($value3['connection_type']=='FIXED')
                    {
                        $data['fixed_count']=$value3['count'];
                        $data['fixed_amount']=$value3['paid_amount'];
                    }
                    else if($value3['connection_type']=='METERED')
                    {
                        $data['meter_count']=$value3['count'];
                        $data['meter_amount']=$value3['paid_amount'];
                    }
                }
            }

            if($property_type_wise_transaction)
            {
                foreach($property_type_wise_transaction as $value4)
                {
                    if($value4['property_type_id']==1)
                    {
                        $data['residential_count']=$value4['count'];
                        $data['residential_amount']=$value4['paid_amount'];
                    }
                    else if($value4['property_type_id']==2)
                    {
                        $data['commercial_count']=$value4['count'];
                        $data['commercial_amount']=$value4['paid_amount'];
                    }
                    else if($value4['property_type_id']==3)
                    {
                        $data['gov_psu_count']=$value4['count'];
                        $data['gov_psu_amount']=$value4['paid_amount'];
                    }
                    else if($value4['property_type_id']==4)
                    {
                        $data['institutional_count']=$value4['count'];
                        $data['institutional_amount']=$value4['paid_amount'];
                    }
                    else if($value4['property_type_id']==5)
                    {
                        $data['ssi_count']=$value4['count'];
                        $data['ssi_amount']=$value4['paid_amount'];
                    }
                    else if($value4['property_type_id']==6)
                    {
                        $data['industrial_count']=$value4['count'];
                        $data['industrial_amount']=$value4['paid_amount'];
                    }
                    else if($value4['property_type_id']==7)
                    {
                        $data['appartment_count']=$value4['count'];
                        $data['appartment_amount']=$value4['paid_amount'];
                    }
                    else if($value4['property_type_id']==8)
                    {
                        $data['trust_ngo_count']=$value4['count'];
                        $data['trust_ngo_amount']=$value4['paid_amount'];
                    }

                }
            }



        }
        else
        {
             $data['date_from']=isset($inputs['date_from'])?$inputs['date_from']:date('Y-m-d');
             $data['date_upto']=isset($inputs['date_upto'])?$inputs['date_upto']:date('Y-m-d');

        }

        //print_var(session() );die;
        return view('water/report/water_collection_summary', $data);


    }

    public function getPagination() 
    {
        if($this->request->getMethod()=='post'){
            try{
                ## Read value
              $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
                if ($rowperpage=='-1')
                    $rowperpage = 1000000000; // 1,00,00,00,000 (All Condition)
                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no")
                    $columnName = 'id';
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                ## Search
                $searchQuery = "";
                if($searchValue != ''){
                    $searchQuery = "(ward_no ILIKE '%".$searchValue."%')";
                }

                // Date filter
                $search_by_from_ward_id = sanitizeString($this->request->getVar('search_by_from_ward_id'));
                $search_by_upto_ward_id = sanitizeString($this->request->getVar('search_by_upto_ward_id'));
                if ($search_by_from_ward_id != '' && $search_by_upto_ward_id != '') {
                    if($searchQuery!="")
                        $searchQuery = " AND ";
                    $searchQuery .= " (id between '".$search_by_from_ward_id."' AND '".$search_by_upto_ward_id."' ) ";
                }

                ## Total number of records without filtering
                $builder = $this->db->table('view_ward_mstr');
                $builder = $builder->select('count(*) as allcount');
                $builder = $builder->get();
                $totalRecords = $builder->getFirstRow('array')['allcount'];

                ## Total number of records with filtering
                $builder = $this->db->table('view_ward_mstr');
                $builder = $builder->select('count(*) as allcount');
                if($searchQuery != '')
                    $builder = $builder->where($searchQuery);
                $builder = $builder->get();
                $totalRecordwithFilter = $builder->getFirstRow('array')['allcount'];

                ## Fetch records
                $builder = $this->db->table('view_ward_mstr');
                $builder = $builder->select('*');
                if($searchQuery != '')
                    $builder = $builder->where($searchQuery);
                $builder = $builder->orderBy($columnName, $columnSortOrder);
                $builder = $builder->limit($rowperpage, $start);
                $builder = $builder->get();
                //echo $this->db->getLastQuery();
                $records = $builder->getResultArray();

                $data = array();
                $sno = 0;
                foreach ($records AS $record) {
                    $sno++;
                    $data[] = array(
                        "s_no"=>$sno,
                        "id"=>$record['id'],
                        "ward_no"=>$record['ward_no'],
                        "ulb_mstr_id"=>$record['ulb_mstr_id'],
                        "status"=>$record['status']
                    );
                }

                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $data
                );

                // return json_encode($response);
                // Get data
                /* $data = $this->model_pagination->getWard($inputs);
                return json_encode($data); */
            }catch(Extention $e){

            }
        } else {
            echo "GET";
        }
    }
    


}
