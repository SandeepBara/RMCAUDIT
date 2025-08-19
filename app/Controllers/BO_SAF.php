<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_datatable;
use App\Models\model_view_saf_receive_list;
use App\Models\model_ward_mstr;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_emp_dtl_permission;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
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
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_view_ward_permission;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Exception;

class BO_SAF extends AlphaController
{
    protected $db;
    protected $dbSystem;

    protected $model_ward_mstr;
    protected $model_emp_dtl_permission;
    protected $model_view_ward_permission;
    protected $model_fy_mstr;
    protected $model_view_saf_receive_list;
    protected $model_view_saf_doc_dtl;
    protected $model_level_pending_dtl;
    protected $model_saf_owner_detail;
    protected $model_saf_doc_dtl;
    protected $model_saf_dtl;
    protected $model_saf_memo_dtl;
    protected $model_saf_floor_details;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_prop_floor_details;
    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_saf_demand;
    protected $model_prop_demand;
    protected $model_prop_type_mstr;
    protected $model_road_type_mstr;
    protected $model_view_saf_floor_details;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_datatable;
    
    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'utility_helper']);
        if($db_name = dbConfig("property"))
        {
            $this->db = db_connect($db_name);            
        }

        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_dtl_permission = new model_emp_dtl_permission($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
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
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_datatable = new model_datatable($this->db);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}

    public function index() {
        $data =(array)null;
        $Session = Session();
        $ulb_dtl = $Session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List       
        
        $data = arrFilterSanitizeString($this->request->getVar());
        $data['wardList'] = $this->model_ward_mstr->getWardListWithSession(["ulb_mstr_id"=>$ulb_mstr_id], $Session);

        if (isset($data["from_date"]) && isset($data["to_date"]) && isset($data["ward_mstr_id"]) && isset($data["pending_on"]) && isset($data["property_type"]) && isset($data["assessment_type"]) && isset($data["saf_no"])) {
            $from_date = $data["from_date"];
            $to_date = $data["to_date"];
            $ward_mstr_id = $data["ward_mstr_id"];
            $pending_on = $data["pending_on"];
            $property_type = $data["property_type"];
            $assessment_type = $data["assessment_type"];
            $saf_no = $data["saf_no"];

            $whereSafNo=null;
            $whereDateRange = "";
            $wardNoWhere = "";
            $pendingOnWhere = "";
            $propertyTypeWhere = "";
            $assessmentTypeWhere = "";
            
            if ($saf_no!="") {
                $whereSafNo = " AND tbl_saf_dtl.saf_no ILIKE '%".$saf_no."%'";
            } else {
                if($ward_mstr_id!="All") {
                    $wardNoWhere = " AND tbl_saf_dtl.ward_mstr_id='".$ward_mstr_id."'";
                }

                if ($from_date!="" && $to_date!="") {
                    $whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
                }
                if($pending_on!="All")
                {
                    if($pending_on=="Payment Done But Document Upload Is Pending")
                    $pendingOnWhere = " and tbl_saf_dtl.payment_status=1 and tbl_saf_dtl.doc_upload_status=0";

                    if($pending_on=="Document Upload Done But Payment Is Pending")
                    $pendingOnWhere = " and tbl_saf_dtl.payment_status=0 and tbl_saf_dtl.doc_upload_status=1";

                    if($pending_on=="Payment Pending And Document Upload Pending")
                    $pendingOnWhere = " and tbl_saf_dtl.payment_status=0 and tbl_saf_dtl.doc_upload_status=0";

                    if($pending_on=="Payment Done")
                    $pendingOnWhere = " and tbl_saf_dtl.payment_status=1";
                }

                if($property_type!="All")
                {
                    $propertyTypeWhere = " and tbl_saf_dtl.prop_type_mstr_id=".$property_type;
                }

                if($assessment_type!="All")
                {
                    if($assessment_type=="New Assessment")
                    $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='New Assessment'";

                    if($assessment_type=="Re-Assessment")
                    $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='Reassessment'";

                    if($assessment_type=="Mutation")
                    $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='Mutation'";
                }
            }
            # Pagination
            $sql="SELECT 
                    tbl_saf_dtl.id, 
                    saf_no, 
                    apply_date, 
                    assessment_type,
                    property_type, 
                    view_saf_owner_detail.*, 
                    ward_no, 
                    CASE WHEN tbl_saf_dtl.emp_details_id=0 THEN 'ONLINE' ELSE emp_name END AS emp_name
            FROM tbl_saf_dtl
            INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
            join view_saf_owner_detail on view_saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
            join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id ".$wardNoWhere."
            left join view_emp_details on view_emp_details.id=tbl_saf_dtl.emp_details_id
            WHERE tbl_saf_dtl.status=1 ".$whereSafNo.$whereDateRange.$pendingOnWhere.$propertyTypeWhere.$assessmentTypeWhere.$whereSafNo;
            //print_var($sql);
            $data['posts'] = $this->model_datatable->getDatatable($sql);
            //print_var($data['posts']);

        }  //echo"<pre>";print_r($data);echo"</pre>"; 
        return view('property/saf/bo_saf_list', $data);
    }

    public function appliedApplicationExport() {
        try {           
            $data = arrFilterSanitizeString($this->request->getVar());
            if (isset($data["from_date"]) && isset($data["to_date"]) && isset($data["ward_mstr_id"]) && isset($data["pending_on"]) && isset($data["property_type"]) && isset($data["assessment_type"]) && isset($data["saf_no"])) {
                $from_date = $data["from_date"];
                $to_date = $data["to_date"];
                $ward_mstr_id = $data["ward_mstr_id"];
                $pending_on = $data["pending_on"];
                $property_type = $data["property_type"];
                $assessment_type = $data["assessment_type"];
                $saf_no = $data["saf_no"];

                $whereSafNo=null;
                $whereDateRange = "";
                $wardNoWhere = "";
                $pendingOnWhere = "";
                $propertyTypeWhere = "";
                $assessmentTypeWhere = "";
                
                if ($saf_no!="") {
                    $whereSafNo = " AND tbl_saf_dtl.saf_no ILIKE '%".$saf_no."%'";
                } else {
                    if($ward_mstr_id!="All") {
                        $wardNoWhere = " AND tbl_saf_dtl.ward_mstr_id='".$ward_mstr_id."'";
                    }

                    if ($from_date!="" && $to_date!="") {
                        $whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
                    }
                    if($pending_on!="All")
                    {
                        if($pending_on=="Payment Done But Document Upload Is Pending")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=1 and tbl_saf_dtl.doc_upload_status=0";

                        if($pending_on=="Document Upload Done But Payment Is Pending")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=0 and tbl_saf_dtl.doc_upload_status=1";

                        if($pending_on=="Payment Pending And Document Upload Pending")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=0 and tbl_saf_dtl.doc_upload_status=0";

                        if($pending_on=="Payment Done")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=1";
                    }

                    if($property_type!="All")
                    {
                        $propertyTypeWhere = " and tbl_saf_dtl.prop_type_mstr_id=".$property_type;
                    }

                    if($assessment_type!="All")
                    {
                        if($assessment_type=="New Assessment")
                        $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='New Assessment'";

                        if($assessment_type=="Re-Assessment")
                        $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='Reassessment'";

                        if($assessment_type=="Mutation")
                        $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='Mutation'";
                    }
                }
                # Pagination
                $sql="SELECT 
                        ward_no, 
                        saf_no, 
                        view_saf_owner_detail.owner_name,
                        view_saf_owner_detail.mobile_no,
                        property_type,
                        assessment_type, 
                        apply_date, 
                        CASE WHEN tbl_saf_dtl.emp_details_id=0 THEN 'ONLINE' ELSE emp_name END AS emp_name
                FROM tbl_saf_dtl
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                join view_saf_owner_detail on view_saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id ".$wardNoWhere."
                left join view_emp_details on view_emp_details.id=tbl_saf_dtl.emp_details_id
                WHERE tbl_saf_dtl.status=1 ".$whereSafNo.$whereDateRange.$pendingOnWhere.$propertyTypeWhere.$assessmentTypeWhere.$whereSafNo;
                //print_var($sql);
                $records = $this->model_datatable->getRecords($sql);
                /* print_var($records);
                die; */

                $spreadsheet = new Spreadsheet();
                
                $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', 'Saf No');
                            $activeSheet->setCellValue('C1', 'Owner Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'Property Type');
                            $activeSheet->setCellValue('F1', 'Assessment Type');
                            $activeSheet->setCellValue('G1', 'Apply Date');
                            $activeSheet->setCellValue('H1', 'Apply By');
                            $activeSheet->fromArray($records, NULL, 'A2');
                $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_TEXT);
                $filename = "applied_application_".date('Ymd-hisa').".xlsx";
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer = new Xlsx($spreadsheet);
                ob_end_clean();
                $writer->save('php://output');
            }
        } catch(Exception $e){
            print_r($e);
        }
    }

    
    public function appliedApplicationExportFull(){
        try {           
            $data = arrFilterSanitizeString($this->request->getVar());
            if (isset($data["from_date"]) && isset($data["to_date"]) && isset($data["ward_mstr_id"]) && isset($data["pending_on"]) && isset($data["property_type"]) && isset($data["assessment_type"]) && isset($data["saf_no"])) {
                $from_date = $data["from_date"];
                $to_date = $data["to_date"];
                $ward_mstr_id = $data["ward_mstr_id"];
                $pending_on = $data["pending_on"];
                $property_type = $data["property_type"];
                $assessment_type = $data["assessment_type"];
                $saf_no = $data["saf_no"];

                $whereSafNo=null;
                $whereDateRange = "";
                $wardNoWhere = "";
                $pendingOnWhere = "";
                $propertyTypeWhere = "";
                $assessmentTypeWhere = "";
                
                if ($saf_no!="") {
                    $whereSafNo = " AND tbl_saf_dtl.saf_no ILIKE '%".$saf_no."%'";
                } else {
                    if($ward_mstr_id!="All") {
                        $wardNoWhere = " AND tbl_saf_dtl.ward_mstr_id='".$ward_mstr_id."'";
                    }

                    if ($from_date!="" && $to_date!="") {
                        $whereDateRange = "AND date(apply_date) between '$from_date' AND '$to_date'";
                    }
                    if($pending_on!="All")
                    {
                        if($pending_on=="Payment Done But Document Upload Is Pending")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=1 and tbl_saf_dtl.doc_upload_status=0";

                        if($pending_on=="Document Upload Done But Payment Is Pending")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=0 and tbl_saf_dtl.doc_upload_status=1";

                        if($pending_on=="Payment Pending And Document Upload Pending")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=0 and tbl_saf_dtl.doc_upload_status=0";

                        if($pending_on=="Payment Done")
                        $pendingOnWhere = " and tbl_saf_dtl.payment_status=1";
                    }

                    if($property_type!="All")
                    {
                        $propertyTypeWhere = " and tbl_saf_dtl.prop_type_mstr_id=".$property_type;
                    }

                    if($assessment_type!="All")
                    {
                        if($assessment_type=="New Assessment")
                        $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='New Assessment'";

                        if($assessment_type=="Re-Assessment")
                        $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='Reassessment'";

                        if($assessment_type=="Mutation")
                        $assessmentTypeWhere = " and tbl_saf_dtl.assessment_type='Mutation'";
                    }
                }
                # Pagination
                $sql="SELECT 
                        ward_no, 
                        saf_no, 
                        tbl_road_type_mstr.road_type AS saf_road_type,
                        view_saf_owner_detail.owner_name,
                        view_saf_owner_detail.mobile_no,
                        property_type,
                        assessment_type, 
                        apply_date, 
                        CASE WHEN tbl_saf_dtl.emp_details_id=0 THEN 'ONLINE' ELSE emp_name END AS emp_name,

                        tbl_saf_dtl.area_of_plot as saf_area_of_plot, 
                        tbl_saf_floor_details.builtup_area as saf_builtup_area, 
                        tbl_saf_dtl.is_water_harvesting AS saf_is_water_harvesting,

                        tc_verification.geo_tag_date as tc_geo_tag_date,
                        tc_verification.area_of_plot as tc_area_of_plot,
                        tc_verification.builtup_area as tc_builtup_area,
                        tc_verification.road_type as tc_road_type,
                        tc_verification.is_water_harvesting as tc_is_water_harvesting,

                        utc_verification.created_on as utc_created_on,
                        utc_verification.area_of_plot as utc_area_of_plot,
                        utc_verification.builtup_area as utc_builtup_area,
                        utc_verification.road_type as utc_road_type,
                        utc_verification.is_water_harvesting as utc_is_water_harvesting
                FROM tbl_saf_dtl
                LEFT JOIN (
                    SELECT sum(builtup_area) as builtup_area,saf_dtl_id 
                    FROM tbl_saf_floor_details 
                    WHERE status=1 
                    GROUP BY saf_dtl_id
                ) as tbl_saf_floor_details on tbl_saf_floor_details.saf_dtl_id = tbl_saf_dtl.id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                join view_saf_owner_detail on view_saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id ".$wardNoWhere."
                left join view_emp_details on view_emp_details.id=tbl_saf_dtl.emp_details_id
                LEFT JOIN tbl_road_type_mstr ON tbl_road_type_mstr.id = tbl_saf_dtl.road_type_mstr_id
                LEFT JOIN(
                    SELECT tbl_field_verification_dtl.* ,tbl_field_verification_floor_details.*,tbl_road_type_mstr.road_type,
                        geo_taging_date.geo_tag_date	
                    FROM tbl_field_verification_dtl
                    JOIN(
                        SELECT MAX(id) as max_id,saf_dtl_id
                        FROM tbl_field_verification_dtl
                        WHERE verified_by='AGENCY TC' AND status =1
                        GROUP BY saf_dtl_id
                    )as last ON last.max_id = tbl_field_verification_dtl.id
                    LEFT JOIN (
                        select sum(builtup_area) as builtup_area,field_verification_dtl_id 
                        from tbl_field_verification_floor_details 
                        where status=1 
                        group by field_verification_dtl_id
                    )AS tbl_field_verification_floor_details on tbl_field_verification_floor_details.field_verification_dtl_id = last.max_id
                    LEFT JOIN(
                        select max(created_on)as geo_tag_date ,geotag_dtl_id
                        from tbl_saf_geotag_upload_dtl 
                        group by geotag_dtl_id 
                    ) as geo_taging_date on geo_taging_date.geotag_dtl_id = tbl_field_verification_dtl.saf_dtl_id
                    LEFT JOIN tbl_road_type_mstr ON tbl_road_type_mstr.id = tbl_field_verification_dtl.road_type_mstr_id
                ) AS tc_verification ON tc_verification.saf_dtl_id = tbl_saf_dtl.id
                LEFT JOIN(
                    SELECT tbl_field_verification_dtl.* ,tbl_field_verification_floor_details.*, tbl_road_type_mstr.road_type
                    FROM tbl_field_verification_dtl
                    JOIN(
                        SELECT MAX(id) as max_id,saf_dtl_id
                        FROM tbl_field_verification_dtl
                        WHERE verified_by='ULB TC' AND status =1
                        GROUP BY saf_dtl_id
                    )as last ON last.max_id = tbl_field_verification_dtl.id
                    LEFT JOIN (
                        select sum(builtup_area) as builtup_area,field_verification_dtl_id 
                        from tbl_field_verification_floor_details 
                        where status=1 
                        group by field_verification_dtl_id
                    )AS tbl_field_verification_floor_details on tbl_field_verification_floor_details.field_verification_dtl_id = last.max_id
                    LEFT JOIN tbl_road_type_mstr ON tbl_road_type_mstr.id = tbl_field_verification_dtl.road_type_mstr_id
                ) AS utc_verification ON utc_verification.saf_dtl_id = tbl_saf_dtl.id
                WHERE tbl_saf_dtl.status=1 ".$whereSafNo.$whereDateRange.$pendingOnWhere.$propertyTypeWhere.$assessmentTypeWhere.$whereSafNo;
                // print_var($sql);die;
                $records = $this->model_datatable->getRecords($sql);
                // print_var($records);
                // die;

                $spreadsheet = new Spreadsheet();
                
                $activeSheet = $spreadsheet->getActiveSheet();
                // Header Styling
                $styleArray = [
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ];
                // Header Row 1 (First Layer Headers with Merging)
                $activeSheet->setCellValue("A1", "Ward No")->mergeCells('A1:A2');
                $activeSheet->setCellValue("B1", "Saf No")->mergeCells('B1:B2');
                $activeSheet->setCellValue("C1", "Road Type")->mergeCells('C1:C2');
                $activeSheet->setCellValue("D1", "Owner Name")->mergeCells('D1:D2');
                $activeSheet->setCellValue("E1", "Mobile No.")->mergeCells('E1:E2');
                $activeSheet->setCellValue("F1", "Property Type")->mergeCells('F1:F2');
                $activeSheet->setCellValue("G1", "Assessment Type")->mergeCells('G1:G2');
                $activeSheet->setCellValue("H1", "Apply Date")->mergeCells('H1:H2');
                $activeSheet->setCellValue("I1", "Apply By")->mergeCells('I1:I2');
                $activeSheet->setCellValue("J1", "Plot Area as per SAF")->mergeCells('J1:J2');
                $activeSheet->setCellValue("K1", "Built-up Area as per SAF")->mergeCells('K1:K2');
                $activeSheet->setCellValue("L1", "RWH as per SAF")->mergeCells('L1:L2');

                // Agency TCA Verification Section
                $activeSheet->setCellValue("M1", "Agency TCA Verification")->mergeCells('M1:P1');
                $activeSheet->setCellValue("M2", "Geo Tagging Date");
                $activeSheet->setCellValue("N2", "Plot Area Verified");
                $activeSheet->setCellValue("O2", "Built-up Area Verified");
                $activeSheet->setCellValue("P2", "Road Verified");

                // ULB TCA Verification Section
                $activeSheet->setCellValue("Q1", "ULB TCA Verification")->mergeCells('Q1:V1');
                $activeSheet->setCellValue("Q2", "RWH");
                $activeSheet->setCellValue("R2", "Date");
                $activeSheet->setCellValue("S2", "Plot Area");
                $activeSheet->setCellValue("T2", "Built-up Area");
                $activeSheet->setCellValue("U2", "Road Width");
                $activeSheet->setCellValue("V2", "RWH");

                // Apply header styles to the whole header section
                $activeSheet->getStyle('A1:V2')->applyFromArray($styleArray);

                // Sample Data to Display (Replace this with dynamic data)
                
                // Add data from row 3 onwards
                $activeSheet->fromArray($records, NULL, 'A3');

                // Apply border styling to data rows
                $rowCount = count($records) + 2; // +2 because data starts from row 3
                $activeSheet->getStyle("A3:V$rowCount")->applyFromArray($styleArray);

                // Adjust column widths for better readability
                foreach (range('A', 'V') as $columnID) {
                    $activeSheet->getColumnDimension($columnID)->setAutoSize(true);
                }


                $filename = "applied_application_".date('Ymd-hisa').".xlsx";
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer = new Xlsx($spreadsheet);
                ob_end_clean();
                $writer->save('php://output');
            }
        } catch(Exception $e){
            print_r($e);
        }
    }

    public function indexAjax()
    {
        if($this->request->getMethod()=='post')
        {
			try
            {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="from_upto_fy_qtr")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_transaction.transaction_mode';
                if ($columnName=="cheque_no")
                    $columnName = 'tbl_cheque_details.cheque_no';
                if ($columnName=="bank_name")
                    $columnName = 'tbl_cheque_details.bank_name';
                if ($columnName=="branch_name")
                    $columnName = 'tbl_cheque_details.branch_name';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_transaction.payable_amt';
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $search_tran_mode_mstr_id = sanitizeString($this->request->getVar('search_tran_mode_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                if ($search_tran_mode_mstr_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode_mstr_id='".$search_tran_mode_mstr_id."'";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (saf_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR saf_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_saf_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/safdtl/full/', MD5(tbl_saf_dtl.id::TEXT), '/backbtnhide', chr(39), ' target=', chr(39), 'blank', chr(39), '><b><u>', tbl_saf_dtl.saf_no, '</u></b></a>') AS saf_no,
                                    tbl_saf_dtl.holding_no,
                                    saf_owner_detail.owner_name,
                                    saf_owner_detail.mobile_no,
                                    CONCAT(from_fy_mstr.fy, '(', tbl_transaction.from_qtr, ')', ' / ', upto_fy_mstr.fy, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_tran_mode_mstr.transaction_mode,
                                    tbl_transaction.payable_amt,
                                    (CASE WHEN view_emp_details.emp_name IS NOT NULL THEN view_emp_details.emp_name ELSE 'N/A' END) AS emp_name,
                                    tbl_transaction.tran_no,
                                    (CASE WHEN tbl_cheque_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_cheque_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_cheque_details.bank_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_cheque_details.branch_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.branch_name END) AS branch_name";
                $sql =  " FROM tbl_transaction
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Saf'
                INNER JOIN (
                        SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, saf_dtl_id 
                    FROM tbl_saf_owner_detail GROUP BY saf_dtl_id
                ) saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_transaction.prop_dtl_id
                INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id
                INNER JOIN view_fy_mstr AS from_fy_mstr ON from_fy_mstr.id=tbl_transaction.from_fy_mstr_id
                INNER JOIN view_fy_mstr AS upto_fy_mstr ON upto_fy_mstr.id=tbl_transaction.from_fy_mstr_id
                LEFT JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                WHERE tbl_transaction.tran_type='Saf' ".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_transaction.payable_amt), 0) AS total_payable_amt";
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                    
                    $records = $this->model_datatable->getRecords($fetchSql);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    //$records['totall'] = 1245;
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                );
                return json_encode($response);
            }
            catch(Exception $e)
            {

            }
        }
    }

    public function SAFStatus($data)
    {
        $data['SAFLevelPending']=NULL;
        $data['msg'] = $this->model_saf_dtl->msg($data);
        # print_var($data['msg']);
		if($data['msg']['payment_status']==1 && $data['msg']['doc_upload_status']==1)
        {
			if($data['msg']['saf_pending_status']==0)
            {
				if($data['msglevelPending'] = $this->model_level_pending_dtl->msglevelPending($data))
                {
					if($data['msglevelPending']['receiver_user_type_id']==6)
                    {
						$data['SAFLevelPending'] = "Pending At Dealing Assistant";
					}
                    elseif($data['msglevelPending']['receiver_user_type_id']==5)
                    {
						$data['SAFLevelPending'] = "Pending At Agency Tax Collector";
					}
                    elseif($data['msglevelPending']['receiver_user_type_id']==7)
                    {
						$data['SAFLevelPending'] = "Pending At ULB Tax Collector";
					}
                    elseif($data['msglevelPending']['receiver_user_type_id']==9)
                    {
						$data['SAFLevelPending'] = "Pending At Section Incharge";
					}
                    elseif($data['msglevelPending']['receiver_user_type_id']==10)
                    {
						$data['SAFLevelPending'] = "Pending At Executive Officer";
					}
				}
			}
            elseif($data['msg']['saf_pending_status']==1)
            {
                $data['SAFLevelPending'] = "Application Approved";
            }
			elseif($data['msg']['saf_pending_status']==2)
            {
                $data['msglevelPending'] = $this->model_level_pending_dtl->getLastBkctznRecord($data);
                
                if($data['msglevelPending']['sender_user_type_id']==6)
                {
                    $data['SAFLevelPending'] = "Application sent back to citizen by Dealing Assistant";
                }
                elseif($data['msglevelPending']['sender_user_type_id']==5)
                {
                    $data['SAFLevelPending'] = "Application sent back to citizen by Tax Collector";
                }
                elseif($data['msglevelPending']['sender_user_type_id']==7)
                {
                    $data['SAFLevelPending'] = "Application sent back to citizen by ULB Tax Collector";
                }
                elseif($data['msglevelPending']['sender_user_type_id']==9)
                {
                    $data['SAFLevelPending'] = "Application sent back to citizen by Section Incharge";
                }
                elseif($data['msglevelPending']['sender_user_type_id']==10)
                {
                    $data['SAFLevelPending'] = "Application sent back to citizen by Executive Officer";
                }
            }
		}
        elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0)
        {
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}
        elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1)
        {
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}
        elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0)
        {
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}
        elseif($data['msg']['payment_status']==2 AND $data['msg']['doc_upload_status']==1)
        {
			$data['SAFLevelPending'] = "Document Upload Done But Cheque Not Cleared"; 
		}
        elseif($data['msg']['payment_status']==2 AND $data['msg']['doc_upload_status']==0)
        {
			$data['SAFLevelPending'] = "Cheque Not Cleared And Document Upload Pending"; 
		}
        return $data['SAFLevelPending'];
    }

    public function view($id=null)
	{
        $data =(array)null;
		$data['id']=$id;
		//print_r($data);
		$data['basic_details'] = $this->model_saf_dtl->basic_details($data);
        $data['saf_dtl'] = $this->model_saf_dtl->Saf_details_md5($id);
		$data['owner_details'] = $this->model_saf_owner_detail->ownerdetails($data['basic_details']['saf_dtl_id']);
        //print_r($data['saf_dtl']);
		$data['tax_list'] = $this->model_saf_tax->tax_list($data['basic_details']['saf_dtl_id']);
		$data['demand_detail'] = $this->model_saf_demand->demand_detail($data);

		$data['occupancy_detail'] = $this->model_saf_floor_details->occupancy_detail($data['basic_details']['saf_dtl_id']);
		
		
        $data['SAFLevelPending']=$this->SAFStatus(["id"=> $id]);
		//$data['payment_detail'] = $this->model_transaction->payment_detail($data['basic_details']['saf_dtl_id']);
        /******* verification code ends**********/
        return view('property/saf/bo_saf_view', $data);
    }


}