<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class alphaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper(['db_helper']);
    	if(!isLogin()){
            return redirect()->to('login');	
    	}else if(!dbConfig("property")){
        	return redirect()->to('login');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}