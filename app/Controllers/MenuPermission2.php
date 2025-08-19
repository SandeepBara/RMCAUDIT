<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\Menu\PermissionAddUpdate;
use App\Models\filePermission;
use App\Models\model_menu_mstr;
//use App\Models\model_user_type_mstr;
use App\Models\model_menu_permission;
use App\Models\model_menu_view;
class MenuPermission2 extends AlphaController
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
        $this->filePermission =  new filePermission($this->dbSystem);
    }

    function __destruct() {
		$this->dbSystem->close();
	}


    public function updateMenuUserType() {
        $user_type_id = $this->request->getVar('user_type_id');
        if (isset($user_type_id)) {
            $PermissionAddUpdate = new PermissionAddUpdate($this->dbSystem);
            flashToast("message", "Menu Update !!!");
            $PermissionAddUpdate->menuPermissionUpdate($user_type_id);
            return $this->response->redirect(base_url('MenuPermission2/updateMenuUserType'));
        }
        $data['user_type_list'] = $this->model_menu_permission->user_list();
        return view('system/menu_user_type_list', $data);
    }

    public function menuList()
	{
       $data['menulist'] = $this->model_menu_mstr->menu_list();
       
       foreach ($data['menulist'] as $key => $value)
       {
            $menu_type = $value['menu_type'];
            if ($menu_type!=0) {
                $parent_menu_mstr_id = $value['parent_menu_mstr_id'];
                $data['menulist'][$key]['parent_menu'] = $this->model_menu_mstr->getParentMenuNameById($parent_menu_mstr_id);
            } else {
                $data['menulist'][$key]['parent_menu'] = "";
            }
            $menu_mstr_id = $value['id'];
            if($result = $this->model_menu_view->menu_permission_list($menu_mstr_id)){
                $data['menulist'][$key]['user_name'] = $result;
            }
       }
       
       return view('system/menu_permission_list', $data);
	}

	// public function menu_add_update($id=null)
    // {
    //     $data =(array)null;
    //     //Designation List
    //     $user_type_list = $this->model_menu_permission->user_list();
    //     $data['user_type_list'] = $user_type_list;
    //     //Under menu list
    //     $data['underMenuNameList'] = $this->model_menu_mstr->gate_under_menu_list();
    //     if($this->request->getMethod()=='post')
    //     { 
    //         // $this->dbSystem->transBegin();
    //         if($this->request->getVar('id')=="") { //Insert Operation 
    //             $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
    //             $parent_sub_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
    //             $url_path = $this->request->getVar('url_path');
    //             $menu_type = 2;
    //             if ($parent_menu_mstr_id==-1) {
    //                 $menu_type = 0;
    //             } else if ($parent_menu_mstr_id==0) {
    //                 $menu_type = 0;
    //             } else if ($parent_menu_mstr_id!=0 && $parent_sub_menu_mstr_id==0 && $url_path=="") {
    //                 $menu_type = 1;
    //             }
    //             if ($parent_sub_menu_mstr_id!=0) {
    //                 $parent_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
    //             } else if ($parent_sub_menu_mstr_id!=0) {
    //                 $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
    //             }
    //             $data = [
    //                 'menu_name' => $this->request->getVar('menu_name'),
    //                 'parent_menu_mstr_id' => $parent_menu_mstr_id,
    //                 'created_on' =>date('Y-m-d H:i:s'),
    //                 'url_path' => $this->request->getVar('url_path'),
    //                 'menu_icon' => $this->request->getVar('menu_icon'),
    //                 'order_no' => $this->request->getVar('order_no'),
    //                 'menu_type' => $menu_type
    //             ];
                
    //             $lastInsert_id = $this->model_menu_mstr->insert_menu($data);
    //             if($lastInsert_id)
    //             {
    //                 $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
    //                 $len = sizeof($user_type_mstr_id);
    //                 for($i=0;$i<$len;$i++)
    //                 {
    //                     $data = [
    //                         'menu_mstr_id' => $lastInsert_id,
    //                         'user_type_mstr_id' => $user_type_mstr_id[$i],
    //                         'created_on'=>date('Y-m-d H:i:s')
    //                     ];
    //                     $model_menu_permission_id = $this->model_menu_permission->menu_permission($data);
    //                     if($this->request->getVar('url_path'))
    //                     {
    //                         $url_path = explode("?",$this->request->getVar('url_path'));  
    //                         $url_path = explode("/",$url_path()); 
    //                         $className =   $url_path[0]??null;
    //                         $functionName = $url_path[1]??null;

    //                         $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$user_type_mstr_id[$i]];   
    //                         $filePermited = $this->filePermission->checkPermitFile($inser_data);
    //                         if($filePermited)
    //                         {
    //                             $inser_data  = ["status"=>1,"is_access"=>true];
    //                             $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
    //                         }
    //                         else
    //                         {
    //                             $inser_data["menu_mstr_id"]=$lastInsert_id;
    //                             $inser_data["menu_permission_id"]=$model_menu_permission_id;
    //                             $filePermission = $this->filePermission->insert_data($inser_data);

    //                         }
    //                     }
    //                 }
    //                 $data = [
    //                     'menu_mstr_id' => $lastInsert_id,
    //                     'user_type_mstr_id' => 1,
    //                     'created_on'=>date('Y-m-d H:i:s')
    //                 ];
    //                 $model_menu_permission_id = $this->model_menu_permission->menu_permission($data);
    //                 if($this->request->getVar('url_path'))
    //                 {
    //                     $url_path = explode("?",$this->request->getVar('url_path'));  
    //                     $url_path = explode("/",$url_path()); 
    //                     $className =   $url_path[0]??null;
    //                     $functionName = $url_path[1]??null;

    //                     $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>1];   
    //                     $filePermited = $this->filePermission->checkPermitFile($inser_data);
    //                     if($filePermited)
    //                     {
    //                         $inser_data  = ["status"=>1,"is_access"=>true];
    //                         $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
    //                     }
    //                     else
    //                     {
    //                         $inser_data["menu_mstr_id"]=$lastInsert_id;
    //                         $inser_data["menu_permission_id"]=$model_menu_permission_id;
    //                         $filePermission = $this->filePermission->insert_data($inser_data);

    //                     }
    //                 }
    //                 return $this->response->redirect(base_url('MenuPermission2/menuList'));
    //             }
                
                
    //         } else { // update
    //             $id = $this->request->getVar('id');
    //             $menu_mstr_id = $id;
    //             $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
    //             $parent_sub_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
    //             $url_path = $this->request->getVar('url_path');
    //             $menu_type = 2;
    //             if ($parent_menu_mstr_id==-1) {
    //                 $menu_type = 0;
    //             } else if ($parent_menu_mstr_id==0) {
    //                 $menu_type = 0;
    //             } else if ($parent_menu_mstr_id!=0 && $parent_sub_menu_mstr_id==0 && $url_path=="") {
    //                 $menu_type = 1;
    //             }
    //             if ($parent_sub_menu_mstr_id!=0) {
    //                 $parent_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
    //             } else if ($parent_sub_menu_mstr_id!=0) {
    //                 $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
    //             }
    //             $data = [
    //                 'menu_name' => $this->request->getVar('menu_name'),
    //                 'parent_menu_mstr_id' => $parent_menu_mstr_id,
    //                 'created_on' =>date('Y-m-d H:i:s'),
    //                 'url_path' => $this->request->getVar('url_path'),
    //                 'menu_icon' => $this->request->getVar('menu_icon'),
    //                 'order_no' => $this->request->getVar('order_no'),
    //                 'menu_type' => $menu_type
    //             ];
                
                
    //             if($this->model_menu_mstr->update_menu($data, $id)) {
    //                 $url_path = explode("?",$this->request->getVar('url_path'));  
    //                 $url_path = explode("/",$url_path[0]); 
    //                 $className =   $url_path[0]??null;
    //                 $functionName = $url_path[1]??null;
    //                 $deactivate = $this->filePermission->deactivateAllfile(["class_name"=>$className,"function_name"=>$functionName]); 
    //                 $this->model_menu_permission->updateMenuPermissionStatusZero($menu_mstr_id);
    //                 $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                     
    //                 $len = sizeof($user_type_mstr_id);
    //                 for ($i =0; $i<$len; $i++)  
    //                 {
    //                     $use_id = $user_type_mstr_id[$i];
    //                     $isExists = $this->model_menu_permission->checkMenuPermissionIsExist($menu_mstr_id,$use_id);                        
    //                     if($isExists) 
    //                     {
    //                         $this->model_menu_permission->updateMenuPermission($menu_mstr_id, $use_id);
    //                         if($this->request->getVar('url_path'))
    //                         {
    //                             $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$use_id];   
    //                             $filePermited = $this->filePermission->checkPermitFile($inser_data);
    //                             if($filePermited)
    //                             {
    //                                 $inser_data  = ["status"=>1,"is_access"=>true];
    //                                 $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
    //                             }
    //                             else
    //                             {
    //                                 $inser_data["menu_mstr_id"]=$menu_mstr_id;
    //                                 $inser_data["menu_permission_id"]= $isExists["id"]??null;
    //                                 $filePermission = $this->filePermission->insert_file($inser_data);
        
    //                             } 
    //                         }
    //                     } 
    //                     else 
    //                     {
    //                         $data = [
    //                                 'menu_mstr_id' => $menu_mstr_id,
    //                                 'user_type_mstr_id' =>$use_id,
    //                                 'created_on'=>date('Y-m-d H:i:s')
    //                             ];
    //                            // print_r($data);
    //                         $model_menu_permission_id = $this->model_menu_permission->menu_permission($data);
    //                         if($this->request->getVar('url_path'))
    //                         {
    //                             $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$use_id];   
    //                             $filePermited = $this->filePermission->checkPermitFile($inser_data);
    //                             if($filePermited)
    //                             {
    //                                 $inser_data  = ["status"=>1,"is_access"=>true];
    //                                 $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
    //                             }
    //                             else
    //                             {
    //                                 $inser_data["menu_mstr_id"]=$menu_mstr_id;
    //                                 $inser_data["menu_permission_id"]= $model_menu_permission_id;
    //                                 $filePermission = $this->filePermission->insert_file($inser_data);
        
    //                             } 
    //                         }
    //                     }
    //                 }
    //                 // $this->dbSystem->transRollback();die;
    //                 return $this->response->redirect(base_url('MenuPermission2/menuList'));
    //             }
    //         }
    //     }
    //     else if(isset($id))
    //     {
    //         //Retrive Single Data
    //         $data['menuDtl'] = $this->model_menu_mstr->getMenuDtlById($id);
    //         if ($data['menuDtl']['menu_type']==2) {
    //             $parent_menu_mstr_id = $data['menuDtl']['parent_menu_mstr_id'];
    //             $parentMenuDtl = $this->model_menu_mstr->getParentMenuDtlNameById($parent_menu_mstr_id);
    //             if($parentMenuDtl['menu_type']==1) {
    //                 $data['menuDtl']['parent_menu_mstr_id'] = $parentMenuDtl['parent_menu_mstr_id'];
    //                 $data['menuDtl']['parent_sub_menu_mstr_id'] = $parentMenuDtl['id'];
    //             }
    //         }
    //         $menuPermissionList = $this->model_menu_permission->menu_permission_list($id);
    //         $data['menuPermissionList'] = $menuPermissionList;
    //         foreach ($data['user_type_list'] as $key=>$user_type_list) {
    //             $data['user_type_list'][$key]['isChecked'] = false;
    //             foreach ($data['menuPermissionList'] as $value) {
    //                 if($user_type_list['id']==$value['user_type_mstr_id']){
    //                     $data['user_type_list'][$key]['isChecked'] = true;
    //                 }
    //             }
    //         }
	// 		return view('system/menu_add_update2', $data);
    //     } else {
	//        return view('system/menu_add_update2',$data);
    //     }
    // }

    public function menu_add_update($id=null)
    {
        $data =(array)null;
        //Designation List
        $user_type_list = $this->model_menu_permission->user_list();
        $data['user_type_list'] = $user_type_list;
        //Under menu list
        $data['underMenuNameList'] = $this->model_menu_mstr->gate_under_menu_list();
        if($this->request->getMethod()=='post')
        { 
            // $this->dbSystem->transBegin();
            if($this->request->getVar('id')=="") { //Insert Operation 
                $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
                $parent_sub_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
                $url_path = $this->request->getVar('url_path');
                $menu_type = 2;
                if ($parent_menu_mstr_id==-1) {
                    $menu_type = 0;
                } else if ($parent_menu_mstr_id==0) {
                    $menu_type = 0;
                } else if ($parent_menu_mstr_id!=0 && $parent_sub_menu_mstr_id==0 && $url_path=="") {
                    $menu_type = 1;
                }
                if ($parent_sub_menu_mstr_id!=0) {
                    $parent_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
                } else if ($parent_sub_menu_mstr_id!=0) {
                    $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
                }
                $data = [
                    'menu_name' => $this->request->getVar('menu_name'),
                    'parent_menu_mstr_id' => $parent_menu_mstr_id,
                    'created_on' =>date('Y-m-d H:i:s'),
                    'url_path' => $this->request->getVar('url_path'),
                    'menu_icon' => $this->request->getVar('menu_icon'),
                    'order_no' => $this->request->getVar('order_no'),
                    'menu_type' => $menu_type
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
                        $model_menu_permission_id = $this->model_menu_permission->menu_permission($data);
                        if($this->request->getVar('url_path'))
                        {
                            $url_path = explode("?",$this->request->getVar('url_path'));
                            $url_path = explode("/",$url_path[0]??"");
                           // dd($url_path);
                            $className =   $url_path[0]??null;
                            $functionName = $url_path[1]??null;

                            $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$user_type_mstr_id[$i]];   
                            $filePermited = $this->filePermission->checkPermitFile($inser_data);
                            if($filePermited)
                            {
                                $inser_data  = ["status"=>1,"is_access"=>true];
                                $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
                            }
                            else
                            {
                                $inser_data["menu_mstr_id"]=$lastInsert_id;
                                $inser_data["menu_permission_id"]=$model_menu_permission_id;
                                $filePermission = $this->filePermission->insert_file($inser_data);

                            }
                        }
                    }
                    $data = [
                        'menu_mstr_id' => $lastInsert_id,
                        'user_type_mstr_id' => 1,
                        'created_on'=>date('Y-m-d H:i:s')
                    ];
                    $model_menu_permission_id = $this->model_menu_permission->menu_permission($data);
                    if($this->request->getVar('url_path'))
                    {
                        //$url_path=$this->request->getVar('url_path');
                        $url_path = explode("?",$this->request->getVar('url_path')); 
                        $url_path = explode("/",$url_path[0]??""); 
                        $className =   $url_path[0]??null;
                        $functionName = $url_path[1]??null;

                        $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>1];   
                        $filePermited = $this->filePermission->checkPermitFile($inser_data);
                        if($filePermited)
                        {
                            $inser_data  = ["status"=>1,"is_access"=>true];
                            $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
                        }
                        else
                        {
                            $inser_data["menu_mstr_id"]=$lastInsert_id;
                            $inser_data["menu_permission_id"]=$model_menu_permission_id;
                            $filePermission = $this->filePermission->insert_file($inser_data);

                        }
                    }
                    return $this->response->redirect(base_url('MenuPermission2/menuList'));
                }
                
                
            } else { // update
                $id = $this->request->getVar('id');
                $menu_mstr_id = $id;
                $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
                $parent_sub_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
                $url_path = $this->request->getVar('url_path');
                $menu_type = 2;
                if ($parent_menu_mstr_id==-1) {
                    $menu_type = 0;
                } else if ($parent_menu_mstr_id==0) {
                    $menu_type = 0;
                } else if ($parent_menu_mstr_id!=0 && $parent_sub_menu_mstr_id==0 && $url_path=="") {
                    $menu_type = 1;
                }
                if ($parent_sub_menu_mstr_id!=0) {
                    $parent_menu_mstr_id = $this->request->getVar('parent_sub_menu_mstr_id');
                } else if ($parent_sub_menu_mstr_id!=0) {
                    $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
                }
                $data = [
                    'menu_name' => $this->request->getVar('menu_name'),
                    'parent_menu_mstr_id' => $parent_menu_mstr_id,
                    'created_on' =>date('Y-m-d H:i:s'),
                    'url_path' => $this->request->getVar('url_path'),
                    'menu_icon' => $this->request->getVar('menu_icon'),
                    'order_no' => $this->request->getVar('order_no'),
                    'menu_type' => $menu_type
                ];
                
                
                if($this->model_menu_mstr->update_menu($data, $id)) {
                    $url_path = explode("?",$this->request->getVar('url_path'));  
                    $url_path = explode("/",$url_path[0]); 
                    $className =   $url_path[0]??null;
                    $functionName = $url_path[1]??null;
                    $deactivate = $this->filePermission->deactivateAllfile(["class_name"=>$className,"function_name"=>$functionName]); 
                    $this->model_menu_permission->updateMenuPermissionStatusZero($menu_mstr_id);
                    $user_type_mstr_id = $this->request->getVar('user_type_mstr_id');
                     
                    $len = sizeof($user_type_mstr_id);
                    for ($i =0; $i<$len; $i++)  
                    {
                        $use_id = $user_type_mstr_id[$i];
                        $isExists = $this->model_menu_permission->checkMenuPermissionIsExist($menu_mstr_id,$use_id);                        
                        if($isExists) 
                        {
                            $this->model_menu_permission->updateMenuPermission($menu_mstr_id, $use_id);
                            if($this->request->getVar('url_path'))
                            {
                                $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$use_id];   
                                $filePermited = $this->filePermission->checkPermitFile($inser_data);
                                if($filePermited)
                                {
                                    $inser_data  = ["status"=>1,"is_access"=>true];
                                    $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
                                }
                                else
                                {
                                    $inser_data["menu_mstr_id"]=$menu_mstr_id;
                                    $inser_data["menu_permission_id"]= $isExists["id"]??null;
                                    $filePermission = $this->filePermission->insert_file($inser_data);
        
                                } 
                            }
                        } 
                        else 
                        {
                            $data = [
                                    'menu_mstr_id' => $menu_mstr_id,
                                    'user_type_mstr_id' =>$use_id,
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];
                               // print_r($data);
                            $model_menu_permission_id = $this->model_menu_permission->menu_permission($data);
                            if($this->request->getVar('url_path'))
                            {
                                $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$use_id];   
                                $filePermited = $this->filePermission->checkPermitFile($inser_data);
                                if($filePermited)
                                {
                                    $inser_data  = ["status"=>1,"is_access"=>true];
                                    $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
                                }
                                else
                                {
                                    $inser_data["menu_mstr_id"]=$menu_mstr_id;
                                    $inser_data["menu_permission_id"]= $model_menu_permission_id;
                                    $filePermission = $this->filePermission->insert_file($inser_data);
        
                                } 
                            }
                        }
                    }
                    // $this->dbSystem->transRollback();die;
                    return $this->response->redirect(base_url('MenuPermission2/menuList'));
                }
            }
        }
        else if(isset($id))
        {
            //Retrive Single Data
            $data['menuDtl'] = $this->model_menu_mstr->getMenuDtlById($id);
            if ($data['menuDtl']['menu_type']==2) {
                $parent_menu_mstr_id = $data['menuDtl']['parent_menu_mstr_id'];
                $parentMenuDtl = $this->model_menu_mstr->getParentMenuDtlNameById($parent_menu_mstr_id);
                if($parentMenuDtl['menu_type']==1) {
                    $data['menuDtl']['parent_menu_mstr_id'] = $parentMenuDtl['parent_menu_mstr_id'];
                    $data['menuDtl']['parent_sub_menu_mstr_id'] = $parentMenuDtl['id'];
                }
            }
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
			return view('system/menu_add_update2', $data);
        } else {
	       return view('system/menu_add_update2',$data);
        }
    }

    public function ajaxGetSubMenuDtl () {
        if($this->request->getMethod()=='post') {
            $parent_menu_mstr_id = $this->request->getVar('parent_menu_mstr_id');
            $input = [
                'parent_menu_mstr_id'=>$parent_menu_mstr_id
            ];
            
            if ($result  = $this->model_menu_mstr->getSubMenuByMenuMstrId($input)) {
                $option = '<option value="0">#</option>';
                foreach ($result AS $list) {
                    $option .= '<option value="'.$list['id'].'" >'.$list['menu_name'].'</option>';
                }
                $response = ['response'=>true, 'data'=> $option];
            } else {
                $response = ['response'=>false];
            }
            return json_encode($response);
        }
    }

    public function MenuDeactivate($id=null) {
        $this->model_menu_mstr->MenuDeactivate($id);
        return $this->response->redirect(base_url('MenuPermission/menuList'));
    }
	//--------------------------------------------------------------------

    public function addFilePermission()
    {
        $data=(array)null;
        helper(['filesystem']);
        $controller = get_filenames(APPPATH . 'controllers/');
        $user_type_list = $this->model_menu_permission->user_list();
        $data['user_type_list'] = $user_type_list;
        if($this->request->getMethod()=='post')
        {
            $this->dbSystem->transBegin();            
            $className = $data["controller_name"]=$this->request->getVar("controller_name");
            $functionName = $data["methods"] = $this->request->getVar("methods");
            $list = $data["list"] = $this->request->getVar("user_type_mstr_id")??[];
            $deactivate = $this->filePermission->deactivateAllfile(["class_name"=>$className,"function_name"=>$functionName]);             
            foreach($list as $user_type)  
            {
                $use_id = $user_type;
                $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$use_id];   
                $filePermited = $this->filePermission->checkPermitFile($inser_data);
                if($filePermited)
                {
                    $inser_data  = ["status"=>1,"is_access"=>true];
                    $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
                }
                else
                {
                    $filePermission = $this->filePermission->insert_file($inser_data);

                } 
            }
            $use_id = 1;
            $inser_data  = ["class_name"=>$className,"function_name"=>$functionName,'user_type_mstr_id'=>$use_id];   
            $filePermited = $this->filePermission->checkPermitFile($inser_data);
            if($filePermited)
            {
                $inser_data  = ["status"=>1,"is_access"=>true];
                $filePermission = $this->filePermission->update_data($inser_data,$filePermited['id']);
            }
            else
            {
                $filePermission = $this->filePermission->insert_file($inser_data);

            } 
            if($this->dbSystem->transStatus()===FALSE)
            {
                $this->dbSystem->transRollback();	
                flashToast("message", "Something errordue to Update!!!"); 
            }
            else
            {						
                $this->dbSystem->transCommit();	
                flashToast("message", "File Permission Given successfully!!!"); 
            }
        }
        foreach($controller as $file)
        { 
            if(file_exists(APPPATH . 'controllers/'.$file) && is_file(APPPATH . 'controllers/'.$file))
            {
                $name = explode('.',$file)[0];
                $data["controllers"][]=$name;

            }
            elseif(is_dir(APPPATH . 'controllers/'.$file))
            {
                $data["dir"][]=$file;
            }
        }        

        return view("system/addFilePermission",$data);
    }
    public function PermitedFileUserType()
    {
        if($this->request->getMethod()=='post')
        {
            $className = $this->request->getVar("className");
            $methodName = $this->request->getVar("methodName");
            $where = [
                "upper(class_name)"=>strtoupper($className),
                "upper(function_name)"=>strtoupper($methodName),
                "status"=>1,
                "is_access"=>true,
            ];
            $filePermited = $this->filePermission->getData($where);
            $response = ['response'=>true, 'data'=> $filePermited];
            return json_encode($response);
        }
    }

}

?>

