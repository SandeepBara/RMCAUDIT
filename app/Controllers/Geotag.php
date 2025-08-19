<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_saf_dtl;

class Geotag extends AlphaController
{
    protected $db;
    protected $model_saf_geotag_upload_dtl;
    protected $model_saf_dtl;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        $this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
    }
	public function index()
	{
        $data =(array)null;
        $data['img_loc'] = $this->model_saf_geotag_upload_dtl->getallImgpath();
        foreach($data['img_loc'] as $key => $value){
               $saf_no = $this->model_saf_dtl->getsafnoBySafDistDtlId($value['geotag_dtl_id']);
            //print_r($saf_no);
               $data['img_loc'][$key]['saf_no'] = $saf_no['saf_no'];
               }
        //print_r($data['img_loc']);

        return view('property/saf/geo_map', $data);

	}
    }
?>
