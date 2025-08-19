<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class tradeapplicationtypemstrmodel extends Model 
{
    protected $db;
    protected $table = 'tbl_application_type_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'application_type','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }    
    public function getapplicationTypeList(){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->orderBy('id', 'asc')                   
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getTradeLevelPendingReport21_22(){
        try
        {
            $sql = "WITH dealing_assistant AS (
                         SELECT COUNT(lp.*) as da_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=17 and lp.sender_user_type_id=0 
                        and lp.forward_date ISNULL and al.apply_date between '2021-04-01' and '2022-03-31'
                    ),
                    tax_daroga AS (
                        SELECT COUNT(lp.*) as td_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=20 and lp.sender_user_type_id=17 
                        and lp.forward_date ISNULL and al.apply_date between '2021-04-01' and '2022-03-31'

                    ),
                    section_head AS (
                        SELECT COUNT(lp.*) as sh_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=18 and lp.sender_user_type_id=20 
                        and lp.forward_date ISNULL and al.apply_date between '2021-04-01' and '2022-03-31'

                    ),
                    executive_officer AS (
                        SELECT COUNT(lp.*) as eo_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=19 and lp.sender_user_type_id=18 
                        and lp.forward_date ISNULL and al.apply_date between '2021-04-01' and '2022-03-31'
                    )
                    SELECT dealing_assistant.da_total_pending, tax_daroga.td_total_pending, 
                    section_head.sh_total_pending, executive_officer.eo_total_pending
                    FROM dealing_assistant
                    JOIN tax_daroga ON 1=1 JOIN section_head ON 1=1 JOIN executive_officer ON 1=1
                    ";
                $builder = $this->db->query($sql);
                return $result = $builder->getFirstRow('array');
            
            /*
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->orderBy('id', 'asc')                   
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
            */
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getTradeLevelPendingReport22_23(){
        try
        {
            $sql = "WITH dealing_assistant AS (
                         SELECT COUNT(lp.*) as da_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=17 and lp.sender_user_type_id=0 
                        and lp.forward_date ISNULL and al.apply_date between '2022-04-01' and '2023-03-31'
                    ),
                    tax_daroga AS (
                        SELECT COUNT(lp.*) as td_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=20 and lp.sender_user_type_id=17 
                        and lp.forward_date ISNULL and al.apply_date between '2022-04-01' and '2023-03-31'

                    ),
                    section_head AS (
                        SELECT COUNT(lp.*) as sh_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=18 and lp.sender_user_type_id=20 
                        and lp.forward_date ISNULL and al.apply_date between '2022-04-01' and '2023-03-31'

                    ),
                    executive_officer AS (
                        SELECT COUNT(lp.*) as eo_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=19 and lp.sender_user_type_id=18 
                        and lp.forward_date ISNULL and al.apply_date between '2022-04-01' and '2023-03-31'
                    )
                    SELECT dealing_assistant.da_total_pending, tax_daroga.td_total_pending, 
                    section_head.sh_total_pending, executive_officer.eo_total_pending
                    FROM dealing_assistant
                    JOIN tax_daroga ON 1=1 JOIN section_head ON 1=1 JOIN executive_officer ON 1=1
                    ";
                $builder = $this->db->query($sql);
                return $result = $builder->getFirstRow('array');
            
            /*
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->orderBy('id', 'asc')                   
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
            */
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getIdByapplicationtype($applicationtype){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('application_type',$applicationtype)
                    ->where('status',1)                                       
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

     public function getapplicationtypeById($id){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('application_type')
                    ->where('id',$id)
                    ->where('status',1)                                       
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('application_type', $input['application_type']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "application_type"=>$input["application_type"]
                  ]);
        return $insert_id = $this->db->insertID();
    }

     public function getdatabyid($id){
        $builder = $this->db->table($this->table);
        $builder->select('id,application_type,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        return $builder = $builder->getFirstRow('array');

    }
    public function getdatabymd5id($id){

        $builder = $this->db->table($this->table);
        $builder->select('id, application_type, status');
        $builder->where('md5(id::text)', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getFirstRow('array');
        // echo $this->db->getLastQuery();die();   
        // print_var($builder);die;
        return $builder;

    }
	
	public function getdatabyapplication_type($application_type){
        $builder = $this->db->table($this->table);
        $builder->select('id,application_type,status');
        $builder->where('application_type', $application_type);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

    public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('application_type', $input['application_type']);
        $builder->where('id!=', $input['id']);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];

    }

    public function updatedataById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'application_type'=>$input['application_type']
                                    ]);

    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }


    public function getTradeApplyCount()
    {
        $sql = "Select count(*) from tbl_apply_licence where status=1 and update_status=0 and payment_status=1 and apply_date BETWEEN '2021-04-01' AND '2022-03-31'";
        // var_dump($sql);

        $builder = $this->db->query($sql);
        return $result = $builder->getFirstRow('array');
    }


    public function getTradeApplyCount_22_23(){
        $sql = "Select count(*) from tbl_apply_licence where status=1 and update_status=0 and payment_status=1 and apply_date BETWEEN '2022-04-01' AND '2023-03-31'";
        // var_dump($sql);

        $builder = $this->db->query($sql);
        return $result = $builder->getFirstRow('array');
    }

    public function getTradeLevelPendingReportFYear($fyear=null){
        if(!$fyear){
            $fyear = getFY();
        }
        list($from,$upto) = explode("-",$fyear);
        $fromDate = ($from)."-04-01";
        $uptoDate = ($upto)."-03-31";
        try
        {
            $sql = "WITH dealing_assistant AS (
                         SELECT COUNT(lp.*) as da_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=17 and lp.sender_user_type_id=0 
                        and lp.forward_date ISNULL and al.apply_date between '$fromDate' and '$uptoDate'
                    ),
                    tax_daroga AS (
                        SELECT COUNT(lp.*) as td_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=20 and lp.sender_user_type_id=17 
                        and lp.forward_date ISNULL and al.apply_date between '$fromDate' and '$uptoDate'

                    ),
                    section_head AS (
                        SELECT COUNT(lp.*) as sh_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=18 and lp.sender_user_type_id=20 
                        and lp.forward_date ISNULL and al.apply_date between '$fromDate' and '$uptoDate'

                    ),
                    executive_officer AS (
                        SELECT COUNT(lp.*) as eo_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        where lp.receiver_user_type_id=19 and lp.sender_user_type_id=18 
                        and lp.forward_date ISNULL and al.apply_date between '$fromDate' and '$uptoDate'
                    )
                    SELECT dealing_assistant.da_total_pending, tax_daroga.td_total_pending, 
                    section_head.sh_total_pending, executive_officer.eo_total_pending
                    FROM dealing_assistant
                    JOIN tax_daroga ON 1=1 JOIN section_head ON 1=1 JOIN executive_officer ON 1=1
                    ";
                $builder = $this->db->query($sql);
                return $result = $builder->getFirstRow('array');
            
            /*
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->orderBy('id', 'asc')                   
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
            */
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getTradeApplyCountOnFyear($fyear=null){
        if(!$fyear){
            $fyear =getFY();
        }
        list($from,$upto) = explode(",",$fyear);
        $fromDate = $from."-04-01";
        $uptoDate = $upto."-03-31";

        $sql = "Select count(*) from tbl_apply_licence where status=1 and update_status=0 and payment_status=1 and apply_date BETWEEN '$fromDate' AND '$uptoDate'";
        // var_dump($sql);

        $builder = $this->db->query($sql);
        return $result = $builder->getFirstRow('array');
    }

    
}