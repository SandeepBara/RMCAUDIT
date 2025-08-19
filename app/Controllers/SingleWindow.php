<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;

class SingleWindow extends Controller
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
	

    public function __construct(){
		
        //parent::__construct();
    	//helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper']);
        
    }

	public function index()
	{
		echo json_encode($_REQUEST);
		
	}
}
?>
