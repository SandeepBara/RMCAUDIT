<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\TradeFirmTypeMstrModel;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeOwnershipTypeMstrModel;
use App\Models\TradeItemsMstrModel;
use App\Models\TradeLicenceRateModel;
use App\Models\TradeViewLicenceRateModel;

class trademaster extends AlphaController
{
    protected $db;
    protected $tradefirmtypemstrmodel;
    protected $tradeapplicationtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $tradeitemsmstrmodel;
    protected $tradelicenceratemodel;
    protected $tradeviewlicenceratemodel;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name); 
        }
        $this->tradefirmtypemstrmodel = new tradefirmtypemstrmodel($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->tradeownershiptypemstrmodel =  new tradeownershiptypemstrmodel($this->db);
        $this->tradeitemsmstrmodel =  new tradeitemsmstrmodel($this->db);
        $this->tradelicenceratemodel =  new tradelicenceratemodel($this->db);
        $this->tradeviewlicenceratemodel =  new tradeviewlicenceratemodel($this->db);
    }


/********************** Licence rate Master***************************/
    public function licenceratelist()
    {//print_r($this->db);
        $data['posts'] = $this->tradeviewlicenceratemodel->getlicencerateList();
       
        return view('trade/master/LicenceRateList', $data);
        
    }

    public function licenceratecreate($id=null)
    {
        $data =(array)null;        
        helper(['form']);
        $Session = Session();        
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        if($this->request->getMethod()=='post'){           
            $rules=[
                    'application_type'=>'required',
                    'range_from'=>'required',
                    'range_to'=>'required',
                    'rate'=>'required',
                    'effective_date'=>'required',
                ];
        $inputs = filterSanitizeStringtoUpper($this->request->getVar()); 

            if($id==null) // insert
            {
                $data = [
                        'application_type_id' => $inputs["application_type"],
                        'range_from' => $inputs["range_from"],
                        'range_to' => $inputs["range_to"],
                        'rate' => $inputs["rate"],
                        'effective_date' => $inputs["effective_date"],
                        'emp_details_id' => $emp_details_id
                    ];                    
                    
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                   return view('trade/master/LicenceRateAdd',$data);
                }
                else
                {
                    
                    $data['data_exist']=$this->tradelicenceratemodel->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/LicenceRateAdd',$data);
                     }
                    else{
                        if($insert_last_id = $this->tradelicenceratemodel->insertData($data)){
                            return $this->response->redirect(base_url('TradeMaster/licenceratelist'));
                        }
                        else{
                            return view('trade/master/LicenceRateAdd',$data);
                        }

                    }

                }
            }
            else
            {
               
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                    return view('trade/master/LicenceRateAdd',$data);
                }
                else
                {                    
                //update the data                    
                     $data = [
                        'id' => $id,
                        'application_type_id' => $inputs["application_type"],
                        'range_from' => $inputs["range_from"],
                        'range_to' => $inputs["range_to"],
                        'rate' => $inputs["rate"],
                        'effective_date' => $inputs["effective_date"],
                        'emp_details_id' => $emp_details_id
                    ];
                    $data['data_exist']=$this->tradelicenceratemodel->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/LicenceRateAdd',$data);
                     }
                    else{
                        if($updaterow = $this->tradelicenceratemodel->updatedataById($data)){echo 'aaa';
                            return $this->response->redirect(base_url('TradeMaster/licenceratelist'));
                        }
                        else{
                            return view('trade/master/LicenceRateAdd',$data);
                        }                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['application_type'] = $this->tradeapplicationtypemstrmodel->getapplicationTypeList();
            $data['licencerate']=$this->tradelicenceratemodel->getdatabyid($id);
            return view('trade/master/LicenceRateAdd',$data);

        }
        else
        {

            $data['title']="Add";
            $data['application_type'] = $this->tradeapplicationtypemstrmodel->getapplicationTypeList();            
            return view('trade/master/LicenceRateAdd',$data);

        }
    }
    public function licenceratedelete($id=null)
    {
        $data['firmtype']=$this->tradelicenceratemodel->deletedataById($id);
        return $this->response->redirect(base_url('TradeMaster/licenceratelist'));
    }

    /********************** Licence rate Master End *******************************/
/********************** Firm Type Master***************************/
	public function firmtypelist()
	{//print_r($this->db);
        $data['posts'] = $this->tradefirmtypemstrmodel->getFirmTypeList();
       // print_r($data['posts']);
        return view('trade/master/firmtypelist', $data);
        
	}

    public function firmtypecreate($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            $rules=[
                    'firm_type'=>'required',
                ];
            if($this->request->getVar('id')=="") // insert
            {
                
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'firm_type' => strtoupper($this->request->getVar('firm_type'))
                    ];
                    $data['data_exist']=$this->tradefirmtypemstrmodel->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/firmadd',$data);
                     }
                    else{
                        if($insert_last_id = $this->tradefirmtypemstrmodel->insertData($data)){
							return $this->response->redirect(base_url('TradeMaster/firmtypelist'));
						}
						else{
							return view('trade/master/firmadd',$data);
						}

                    }

                }
            }
            else
            {
               
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'firm_type' => strtoupper($this->request->getVar('firm_type')),
                        'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->tradefirmtypemstrmodel->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/firmadd',$data);
                     }
                    else{
                        if($updaterow = $this->tradefirmtypemstrmodel->updatedataById($data)){
							return $this->response->redirect(base_url('TradeMaster/firmtypelist'));
						}
						else{
							return view('trade/master/firmadd',$data);
						}                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['firmtype']=$this->tradefirmtypemstrmodel->getdatabyid($id);
            return view('trade/master/firmadd',$data);

        }
        else
        {
            $data['title']="Add";
            return view('trade/master/firmadd',$data);

        }
    }
    public function firmtypedelete($id=null)
    {
        $data['firmtype']=$this->tradefirmtypemstrmodel->deletedataById($id);
        return $this->response->redirect(base_url('TradeMaster/firmtypelist'));
    }

    /********************** Firm Type Master End *******************************/

    /********************** Application Type Master***************************/
    public function applicationtypelist()
    {//print_r($this->db);
        $data['posts'] = $this->tradeapplicationtypemstrmodel->getapplicationTypeList();
       // print_r($data['posts']);
        return view('trade/master/applicationtypelist', $data);
        
    }

    public function applicationtypecreate($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            $rules=[
                    'application_type'=>'required',
                ];
            if($this->request->getVar('id')=="") // insert
            {
                
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'application_type' => strtoupper($this->request->getVar('application_type'))
                    ];
                    $data['data_exist']=$this->tradeapplicationtypemstrmodel->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/applicationadd',$data);
                     }
                    else{
                        if($insert_last_id = $this->tradeapplicationtypemstrmodel->insertData($data)){
                            return $this->response->redirect(base_url('TradeMaster/applicationtypelist'));
                        }
                        else{
                            return view('trade/master/applicationadd',$data);
                        }

                    }

                }
            }
            else
            {
               
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'application_type' => strtoupper($this->request->getVar('application_type')),
                        'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->tradeapplicationtypemstrmodel->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/applicationadd',$data);
                     }
                    else{
                        if($updaterow = $this->tradeapplicationtypemstrmodel->updatedataById($data)){
                            return $this->response->redirect(base_url('TradeMaster/applicationtypelist'));
                        }
                        else{
                            return view('trade/master/applicationadd',$data);
                        }                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['applicationtype']=$this->tradeapplicationtypemstrmodel->getdatabyid($id);
            return view('trade/master/applicationadd',$data);

        }
        else
        {
            $data['title']="Add";
            return view('trade/master/applicationadd',$data);

        }
    }
    public function applicationtypedelete($id=null)
    {
        $data['applicationtype']=$this->tradeapplicationtypemstrmodel->deletedataById($id);
        return $this->response->redirect(base_url('TradeMaster/applicationtypelist'));
    }

    /********************** Application Type Master End *******************************/

    /********************** Ownership Type Master***************************/
    public function ownershiptypelist()
    {//print_r($this->db);
        $data['posts'] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
       // print_r($data['posts']);
        return view('trade/master/ownershiptypelist', $data);
        
    }

    public function ownershiptypecreate($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            $rules=[
                    'ownership_type'=>'required',
                ];
            if($this->request->getVar('id')=="") // insert
            {
                
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'ownership_type' => strtoupper($this->request->getVar('ownership_type'))
                    ];
                    $data['data_exist']=$this->tradeownershiptypemstrmodel->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/ownershipadd',$data);
                     }
                    else{
                        if($insert_last_id = $this->tradeownershiptypemstrmodel->insertData($data)){
                            return $this->response->redirect(base_url('TradeMaster/ownershiptypelist'));
                        }
                        else{
                            return view('trade/master/ownershipadd',$data);
                        }

                    }

                }
            }
            else
            {
               
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'ownership_type' => strtoupper($this->request->getVar('ownership_type')),
                        'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->tradeownershiptypemstrmodel->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/ownershipadd',$data);
                     }
                    else{
                        if($updaterow = $this->tradeownershiptypemstrmodel->updatedataById($data)){
                            return $this->response->redirect(base_url('TradeMaster/ownershiptypelist'));
                        }
                        else{
                            return view('trade/master/ownershipadd',$data);
                        }                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['ownershiptype']=$this->tradeownershiptypemstrmodel->getdatabyid($id);
            return view('trade/master/ownershipadd',$data);

        }
        else
        {
            $data['title']="Add";
            return view('trade/master/ownershipadd',$data);

        }
    }
    public function ownershiptypedelete($id=null)
    {
        $data['ownershiptype']=$this->tradeownershiptypemstrmodel->deletedataById($id);
        return $this->response->redirect(base_url('TradeMaster/ownershiptypelist'));
    }

    /********************** Ownership Type Master End *******************************/

    /********************** Trade Items Master***************************/
    public function tradeitemslist()
    {//print_r($this->db);
        $data['posts'] = $this->tradeitemsmstrmodel->gettradeitemsList();
       // print_r($data['posts']);
        return view('trade/master/tradeitemslist', $data);
        
    }

    public function tradeitemscreate($id=null)
    {
        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
            $rules=[
                    'trade_code'=>'required',
                    'trade_item'=>'required',
                ];
            if($this->request->getVar('id')=="") // insert
            {
                
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                }
                else
                {
                    //store the data
                    $data = [
                        'trade_code' => strtoupper($this->request->getVar('trade_code')),
                        'trade_item' => strtoupper($this->request->getVar('trade_item'))
                    ];
                    $data['data_exist']=$this->tradeitemsmstrmodel->checkdata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/tradeitemsadd',$data);
                     }
                    else{
                        if($insert_last_id = $this->tradeitemsmstrmodel->insertData($data)){
                            return $this->response->redirect(base_url('TradeMaster/tradeitemslist'));
                        }
                        else{
                            return view('trade/master/tradeitemsadd',$data);
                        }

                    }

                }
            }
            else
            {
               
                if(!$this->validate($rules)){
                    $data['validation']=$this->validator;
                }
                else
                {
                    //update the data
                    $id = $this->request->getVar('id');
                    $data = [
                        'trade_code' => strtoupper($this->request->getVar('trade_code')),
                        'trade_item' => strtoupper($this->request->getVar('trade_item')),
                        'id' => $this->request->getVar('id')
                    ];
                    $data['data_exist']=$this->tradeitemsmstrmodel->checkupdatedata($data);


                    if($data['data_exist'])
                        {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('trade/master/tradeitemsadd',$data);
                     }
                    else{
                        if($updaterow = $this->tradeitemsmstrmodel->updatedataById($data)){
                            return $this->response->redirect(base_url('TradeMaster/tradeitemslist'));
                        }
                        else{
                            return view('trade/master/tradeitemsadd',$data);
                        }                       
                    }

                }
            }
        }
        else if(isset($id))
        {
            //retrive data
            $data['title']="Update";
            $data['tradeitems']=$this->tradeitemsmstrmodel->getdatabyid($id);
            return view('trade/master/tradeitemsadd',$data);

        }
        else
        {
            $data['title']="Add";
            return view('trade/master/tradeitemsadd',$data);

        }
    }
    public function tradeitemsdelete($id=null)
    {
        $data['tradeitems']=$this->tradeitemsmstrmodel->deletedataById($id);
        return $this->response->redirect(base_url('TradeMaster/tradeitemstypelist'));
    }

    /********************** Trade Items Master End *******************************/

}
?>
