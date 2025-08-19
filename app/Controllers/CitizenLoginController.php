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

class CitizenLoginController extends Controller
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
    	helper(['db_helper', 'utility_helper']);
		if(!dbConfig("property")){
			echo '<script> window.location = "'.base_url().'/'.'"; </script>';
        	//echo view('index');
        	//die();	
        }elseif (!cHasCookie('saf_dtl')) {
			echo view('Citizen/SAF/searchApplication');
			die();
		}
        
    }
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
	}

}
