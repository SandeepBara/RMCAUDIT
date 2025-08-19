<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;

class AlphaController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	public function __construct(){
		date_default_timezone_set("Asia/Kolkata");
    	helper(['db_helper']);
    	if(!isLogin()){
    		echo view('users/login');
    		die();	
    	}else if(!dbConfig("property")){
        	echo view('users/welcome');
        	die();	
        }
        logger_on_off(false);
		inMaintenanceServer();
		blockPaymentApp();
		checkMultipleLogin();

		/*$router = service('router'); 
		$controller  = $router->controllerName();
		$app_path = explode("\\",$controller);
		$controllerName = $app_path[3];		
		$controller_arr = ['Login', 'Prop_report', 'AllModuleDCBReport', 'Dashboard', 'test', 'AllModuleCollection', 'AllModuleCollectionReport', 'AllmoduleCollectionSummary_TCwise', 'MiniDashboard', 'water_report', 'WaterConsumerWiseDCBReport', 'Trade_report', 'BOC_SAF', 'SafDoc', 'Bo_saf', 'Safdtl', 'SAF', 'Water_report', 'WaterCollectionReport', 'Saf', 'BankReconciliationAllModuleList','WaterWardWiseDCBReport', 'TradeTCWiseCollectionReports'];
		$cr_date = strtotime(date('d-m-Y'));
		$maintance_date = strtotime(date('01-04-2023'));
		if(!in_array($controllerName, $controller_arr) && $cr_date>=$maintance_date)
		{
			echo view('maintenance');
			die();
			
		}*/
        
    }
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
	}

}
