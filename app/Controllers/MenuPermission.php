<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_menu_mstr;
//use App\Models\model_user_type_mstr;
use App\Models\model_menu_permission;
use App\Models\model_menu_view;

class MenuPermission extends AlphaController
{
    protected $dbsystem;
    protected $model_menu_permission;
    protected $model_menu_view;
    protected $model_menu_mstr;
	public function __construct(){
        parent::__construct();
        helper(['db_helper', 'form']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_menu_permission = new model_menu_permission($this->dbSystem);
        $this->model_menu_view = new model_menu_view($this->dbSystem);
        $this->model_menu_mstr = new model_menu_mstr($this->dbSystem);
    }

    function __destruct() {
		$this->dbSystem->close();
	}
	public function menuList()
	{
       $data['menulist'] = $this->model_menu_mstr->menu_list();
       foreach ($data['menulist'] as $key => $value) {
            $menu_mstr_id = $value['id'];
            if($result = $this->model_menu_view->menu_permission_list($menu_mstr_id)){
                $data['menulist'][$key]['user_name'] = $result;
            }
       }
      return view('system/menu_permission_list', $data);
	}
	public function menu_add_update($id=null)
    {
        $data =(array)null;
        //Designation List
        $user_type_list = $this->model_menu_permission->user_list();
        $data['user_type_list'] = $user_type_list;
        //Under menu list
        $data['underMenuNameList'] = $this->model_menu_mstr->gate_under_menu_list();
        if($this->request->getMethod()=='post'){
            if($this->request->getVar('id')=="") //Insert Operation 
            {
                //Data preparation For Insert
                $data = [
                        'menu_name' => $this->request->getVar('menu_name'),
                        'parent_menu_mstr_id' => $this->request->getVar('parent_menu_mstr_id'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'url_path' => $this->request->getVar('url_path'),
                        'menu_icon' => $this->request->getVar('menu_icon'),
                        'order_no' => $this->request->getVar('order_no')
                    ];
                $lastInsert_id = $this->model_menu_mstr->insert_menu($data);
                if($lastInsert_id)
                {
                    $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                    $len = sizeof($user_type_mstr_id);
                    for($i=0;$i<$len;$i++)
                    {
                        $data = [
                                    'menu_mstr_id' => $lastInsert_id,
                                    'user_type_mstr_id' => $user_type_mstr_id[$i],
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];
                        $this->model_menu_permission->menu_permission($data);
                    }
                    return $this->response->redirect(base_url('MenuPermission/menuList'));
                }
            }
            else
            {
                //update the data
                $id = $this->request->getVar('id');
                $menu_mstr_id = $id;
                $data = [
                        'menu_name' => $this->request->getVar('menu_name'),
                        'url_path' => $this->request->getVar('url_path'),
                        'menu_icon' => $this->request->getVar('menu_icon'),
                        'parent_menu_mstr_id' => $this->request->getVar('parent_menu_mstr_id'),
                        'order_no' => $this->request->getVar('order_no')
                    ];
                    $data['id'] = $id;
                    if($this->model_menu_mstr->update_menu($data, $id))
                    {
                     //Set All Status Zero
                     $this->model_menu_permission->updateMenuPermissionStatusZero($menu_mstr_id);
                     $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                     
                     $len = sizeof($user_type_mstr_id);
                     for ($i =0; $i<$len; $i++) 
                     {
                        $use_id = $user_type_mstr_id[$i];
                        echo $user_type_mstr_id;
                        $isExists = $this->model_menu_permission->checkMenuPermissionIsExist($menu_mstr_id,$use_id);
                        if($isExists)
                        {
                            $this->model_menu_permission->updateMenuPermission($menu_mstr_id,$use_id);
                        }
                       else
                        {
                            $data = [
                                    'menu_mstr_id' => $menu_mstr_id,
                                    'user_type_mstr_id' =>$use_id,
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];
                               // print_r($data);
                            $this->model_menu_permission->menu_permission($data);
                        }
                    } 
                }
               return $this->response->redirect(base_url('MenuPermission/menuList'));
            }
        }
        else if(isset($id))
        {
            //Retrive Single Data
            $data['menu'] = $this->model_menu_mstr->get_menu_by_id($id);
            $menuPermissionList = $this->model_menu_permission->menu_permission_list($id);
            $data['menuPermissionList'] = $menuPermissionList;
                foreach ($data['user_type_list'] as $key=>$user_type_list) {
                    $data['user_type_list'][$key]['isChecked'] = false;
                    foreach ($data['menuPermissionList'] as $value) {
                        if($user_type_list['id']==$value['user_type_mstr_id']){
                            $data['user_type_list'][$key]['isChecked'] = true;
                        }
                    }
                }
			return view('system/menu_add_update', $data);
        }
        else
        {
	       return view('system/menu_add_update',$data);
        }
    }
    public function MenuDeactivate($id=null)
    {
        $this->model_menu_mstr->MenuDeactivate($id);
        return $this->response->redirect(base_url('MenuPermission/menuList'));
    }
	//--------------------------------------------------------------------
}
?>
