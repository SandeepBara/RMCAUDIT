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

class BaseController extends Controller
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
    	helper(['db_helper']);
    	if(!dbConfig("property")){
        	echo view('users/welcome');
        	die();	
        }
		
		inMaintenanceServer();
		/*$router = service('router'); 
		$controller  = $router->controllerName();
		$app_path = explode("\\",$controller);
		$controllerName = $app_path[3];
		$controller_arr = ['Login', 'Prop_report', 'AllModuleCollectionReport', 'AllModuleDCBReport', 'Dashboard', 'test', 'AllModuleCollection', 'AllmoduleCollectionSummary_TCwise', 'MiniDashboard', 'water_report', 'WaterConsumerWiseDCBReport', 'Trade_report', 'safdtl', 'BO_SAF'];
		$cr_date = strtotime(date('d-m-Y'));
		$maintance_date = strtotime(date('01-04-2023'));
		if(!in_array($controllerName, $controller_arr) && $cr_date>=$maintance_date)
		{
			echo view('maintenance');
			die();
			
		}*/
    }
	/**
	 * Constructor.
	 */
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
