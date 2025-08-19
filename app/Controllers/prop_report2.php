<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\Reports\PropReports;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
use App\Models\model_view_emp_details;
use App\Models\model_fy_mstr;
use App\Models\model_tran_mode_mstr;
use App\Models\model_datatable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Exception;

//include APPPATH . './Libraries/phpoffice/autoload.php';

class prop_report extends AlphaController
{
	protected $db;
    protected $PropReports;
    protected $model_ward_mstr;
	protected $model_ward_permission;
    protected $model_view_emp_details;
    protected $model_fy_mstr;
    protected $model_tran_mode_mstr;
    protected $model_datatable;

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        error_reporting(-1);
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){  $this->db = db_connect($db_name); }
        /* $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system');  */
        $this->PropReports = new PropReports();
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        $this->model_datatable = new model_datatable($this->db);
        helper('form_helper');
    }

    function __destruct() {
		$this->db->close();
	}
}

