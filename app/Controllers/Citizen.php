<?php namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\model_ulb_mstr;
use Exception;

class Citizen extends Controller
{
    protected $db;
    protected $dbSystem;
    public function __construct() {
        //parent::__construct();
        helper(['db_helper']);
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
           
    }

    function __destruct() {
		if (isset($this->dbSystem)) $this->dbSystem->close();
	}

    public function index($url = null) {
        try {
            if ($this->request->getMethod()=='get') {
                //$url = hashDecrypt($url);
                cSetCookie('ulb_dtl', getUlbDtl());
                return view('citizen/basic/select_module');
                /* $input = ['ulb_mstr_id'=>$url];
                if($ulb_dtl = $this->model_ulb_mstr->getULBDetailsByMD5Id($input)){
                    cSetCookie('ulb_dtl', $ulb_dtl);
                    return view('citizen/basic/select_module');
                } */
            }
        } catch (Exception $e) {

        }
    }

    public function SelectMunicipal_copy($url = null)
    {
        try { 
            echo(base_url()."/".hashDecrypt($url));
            if ($this->request->getMethod()=='post') 
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                
                $input = ['ulb_mstr_id'=>$inputs['ulb_mstr_id']];
                if($ulb_dtl = $this->model_ulb_mstr->getULBDetailsByMD5Id($input))
                {
                    $session = session();
                    $session->set('ulb_dtl', $ulb_dtl);
                    $LINK = base_url()."/".hashDecrypt($inputs['url']);
                    return redirect()->to($LINK);
                } 
                else 
                {
                    
                }
            } 
            else 
            {
                $ulb_list = $this->model_ulb_mstr->getUlbList();
                $data['ulb_list'] = $ulb_list;
                $data['url'] = $url;
                return view('citizen/smunicipal', $data);
            }
        } 
        catch (Exception $e) 
        {

        }
    }

    public function SelectMunicipal($url = null)
    {
        //echo(base_url()."/".hashDecrypt($url));
        //print_var(session()->get('ulb_dtl'));
        //$LINK = base_url()."/".hashDecrypt($inputs['url']);
        $LINK = base_url()."/".hashDecrypt($url);
        return redirect()->to($LINK);
    }
}