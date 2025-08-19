<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_emp_details extends Model
{
    protected $db;
    protected $table = 'tbl_emp_details';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'user_type_mstr_id', 'user_mstr_id', 'emp_name', 'personal_phone_no', 'created_on', 'updated_on', 'middle_name', 'last_name', 'created_by_emp_details_id', 'email_id', 'guardian_name', 'report_to', 'photo_path', 'status', 'designation'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }
    public function getLoginEmpDetails($input)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder->select('id, user_type_mstr_id,user_mstr_id, emp_name, personal_phone_no, photo_path, email_id');
            $builder->where('user_mstr_id', $input['user_mstr_id']);
            $builder->where('status', 1);
            $builder = $builder->get();
            $builder = $builder->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function insertData($input)
    {
        try {
            $builder = $this->db->table($this->table)
                ->insert([
                    "emp_name" => $input['emp_name'],
                    "middle_name" => $input['middle_name'],
                    "last_name" => $input['last_name'],
                    "guardian_name" => $input['guardian_name'],
                    "user_type_mstr_id" => $input['user_type_mstr_id'],
                    "user_mstr_id" => $input['user_mstr_id'],
                    "personal_phone_no" => $input['personal_phone_no'],
                    "created_by_emp_details_id" => $input['created_by_emp_details_id'],
                    "email_id" => $input['email_id'],
                    "created_on" => $input['created_on'],
                    "updated_on" => $input['created_on'],
                    "employee_code" => $input['employee_code'],
                    "report_to" => $input['report_to']
                ]);
            return $insert_id = $this->db->insertID();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function Update_user_mstr_id($emp_id, $user_mstr_id)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $emp_id)
                ->update([
                    'user_mstr_id' => $user_mstr_id
                ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function reportingList($user_type_mstr_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id,emp_name,middle_name,last_name,guardian_name,personal_phone_no')
                ->where('status', 1)
                ->where('user_type_mstr_id', $user_type_mstr_id)
                ->get();
            return $result = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getEmpDetailsById($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('status', 1)
                ->where('md5(id::text)', $id)
                ->get();
            return $result = $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateEmpDetailsById($input)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $input['id'])
                ->update([
                    "emp_name" => $input['emp_name'],
                    "middle_name" => $input['middle_name'],
                    "last_name" => $input['last_name'],
                    "guardian_name" => $input['guardian_name'],
                    "user_type_mstr_id" => $input['user_type_mstr_id'],
                    "personal_phone_no" => $input['personal_phone_no'],
                    "email_id" => $input['email_id'],
                    "updated_on" => $input['updated_on'],
                    "report_to" => $input['report_to']
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getUserMstrIdByEmpDetailsId($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('user_mstr_id')
                ->where('status', 1)
                ->where('id', $id)
                ->get();
            return $result = $builder->getResultArray()[0];
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    //get team leader
    public function get_team_leader(){

          $result=$this->db->table($this->table)
                            ->select("id,emp_name")
                            ->where('status',1)
                            ->where('user_type_mstr_id',4)
                            ->get();    
                              //echo $this->db->getLastQuery();                      
          return $result->getResultArray();
    }

      //get team leader
    public function get_team_leaderActive(){

          $result=$this->db->table($this->table)
                            ->select("tbl_emp_details.id,emp_name")
                            ->join('tbl_user_mstr','tbl_user_mstr.id=tbl_emp_details.id')
                            ->where('tbl_emp_details.status',1)
                            ->where('tbl_emp_details.user_type_mstr_id',4)
                            ->Where('tbl_user_mstr.lock_status',0)
                            ->get();    
                              // echo $this->db->getLastQuery();                      
          return $result->getResultArray();
    }
    
    //get tax collector 
    public function get_tax_collector($report_to){

          $result=$this->db->table($this->table)
                            ->select("id,emp_name")
                            ->where('status',1)
                            ->where('report_to',$report_to)
                            ->get();    
                              //echo $this->db->getLastQuery();                      
          return $result->getResultArray();
    }

    public function getReportToDataByEmpdetailsId($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('report_to')
                ->where('status', 1)
                ->where('id', $id)
                ->get();
            $result = $builder->getResultArray()[0];
            if ($result) {
                return $result;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function uploadImage($newName, $emp_id)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $emp_id)
                ->update([
                    "photo_path" => $newName
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function uploadSignature($newName, $emp_id)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $emp_id)
                ->update([
                    "signature_path" => $newName
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //ULB Operation 
    public function insertUlbData($input)
    {
        try {
            $builder = $this->db->table($this->table)
                ->insert([
                    "emp_name" => $input['emp_name'],
                    "designation" => $input['designation'],
                    "user_type_mstr_id" => $input['user_type_mstr_id'],
                    "user_mstr_id" => $input['user_mstr_id'],
                    "personal_phone_no" => $input['personal_phone_no'],
                    "email_id" => $input['email_id'],
                    "created_on" => $input['created_on']
                ]);
            return $insert_id = $this->db->insertID();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function updateUlbEmpDetailsById($input)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $input['id'])
                ->update([
                    "emp_name" => $input['emp_name'],
                    "designation" => $input['designation'],
                    "user_type_mstr_id" => $input['user_type_mstr_id'],
                    "personal_phone_no" => $input['personal_phone_no'],
                    "email_id" => $input['email_id']
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function emp_dtls($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('emp_name,personal_phone_no')
                ->where('id', $data)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function employeeDetails($emp_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('emp_name,middle_name,last_name,personal_phone_no')
                ->where('id', $emp_id)
                ->where('status', 1)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getTcDetails()
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id,emp_name,employee_code')
                ->where('user_type_mstr_id', 5)
                ->where('status', 1)
                ->get();
            // echo $this->getLastQuery();

            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTCList()
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('user_type_mstr_id', 5)
                ->orwhere('user_type_mstr_id', 4)
                ->orwhere('user_type_mstr_id', 8)
                ->where('status', 1)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getWaterUsers()
    {
        return $this->db->table($this->table)
            ->select("id,emp_name,designation,user_type_mstr_id")
            ->whereIn('user_type_mstr_id', [12, 13, 14, 15, 16])
            ->where('status', 1)
            ->get()
            ->getResultArray();

        //   echo $this->getLastQuery();


    }


    public function taxcoll_details($ulb_mstr_id)
    {

        $sql = "SELECT 
                    emp.emp_name,
                    emp.middle_name,
                    emp.last_name, 
                    emp.personal_phone_no,
                    emp.employee_code,
                    emp.photo_path,
                    ward_permission.ward_id
                FROM view_emp_details emp
                INNER JOIN ( 
                    SELECT tbl_ward_permission.emp_details_id,
                        string_agg(tbl_ward_mstr.ward_no::text, ', '::text ORDER BY (substring(ward_no, '^[0-9]+'))::int, ward_no) AS ward_id
                    FROM tbl_ward_permission
                    INNER JOIN tbl_ward_mstr on tbl_ward_mstr.id = tbl_ward_permission.ward_mstr_id AND tbl_ward_permission.status=1
                    GROUP BY tbl_ward_permission.emp_details_id
                ) ward_permission ON ward_permission.emp_details_id = emp.id
                WHERE 
                    emp.status=1 
                    AND emp.lock_status=0
                    AND emp.user_type_mstr_id=5 
                    AND emp.ulb_mstr_id='" . $ulb_mstr_id . "'";
                    //print_var($sql);
        $ql = $this->query($sql);
        //echo $this->db->getLastQuery();
        if ($ql) {
            return $ql->getResultArray();
        } else {
            return false;
        }
    }


    // public function profile_details($emp_id)
    // {
    //     print_r($this->db->getDatabase());
    //     return;
    //     $sql = "select emp.emp_name,emp.middle_name,emp.last_name, emp.personal_phone_no,ward_permission.ward_id,
	// 			emp.employee_code,emp.email_id,emp.photo_path,emp.user_type,emp.user_name,emp.guardian_name
	// 			from view_emp_details emp
	// 			LEFT JOIN ( SELECT tbl_ward_permission.emp_details_id,
	// 				string_agg(tbl_ward_mstr.ward_no::text, ', '::text) AS ward_id
	// 				FROM tbl_ward_permission
	// 				left join tbl_ward_mstr on tbl_ward_mstr.id = tbl_ward_permission.ward_mstr_id
	// 				GROUP BY tbl_ward_permission.emp_details_id) ward_permission ON ward_permission.emp_details_id = emp.id 
	// 				where emp.status=1 AND emp.id='" . $emp_id . "'";
    //     $ql = $this->query($sql);
    //     // echo $this->db->getLastQuery();
    //     if($ql)
    //     {
    //         return $ql->getResultArray()[0];
    //     }
    //     else
    //     {
    //         return false;
    //     }
    // }
    public function profile_details($emp_id, $ulb_mstr_id)
    {
        // print_r($this->db->getDatabase());
        // return;
        $sql = "select emp.emp_name,emp.middle_name,emp.last_name, emp.personal_phone_no,ward_permission.ward_id,
        emp.employee_code,emp.email_id,emp.photo_path,emp.user_type,emp.user_name,emp.guardian_name
        from view_emp_details emp
        LEFT JOIN (SELECT tbl_ward_permission.emp_details_id,
            string_agg(tbl_ward_mstr.ward_no::text, ', '::text) AS ward_id
            FROM tbl_ward_permission
            left join(select id,ward_no from tbl_ward_mstr where ulb_mstr_id='".$ulb_mstr_id."')
                   as tbl_ward_mstr on tbl_ward_mstr.id = tbl_ward_permission.ward_mstr_id
                   
            GROUP BY tbl_ward_permission.emp_details_id) ward_permission ON ward_permission.emp_details_id = emp.id 
            where emp.status=1 AND emp.id='".$emp_id."' and emp.ulb_mstr_id='".$ulb_mstr_id."'";
        $ql = $this->query($sql);
        // echo $this->db->getLastQuery();
        if ($ql) {
            return $ql->getResultArray()[0];
        } else {
            return false;
        }
    }
	
	
	public function getempnamebyempid($input)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder->select('id,emp_name');
            $builder->where('id', $input);
            $builder->where('status', 1);
            $builder = $builder->get();
            $builder = $builder->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
	
	public function getempnamelist()
    {
        try {
            $builder = $this->db->table($this->table);
            $builder->select('id, emp_name');
            $builder->where('status', 1);
			$builder->orderBy('id', 'ASC');
            $builder = $builder->get();
            $builder = $builder->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getAllTcList()
    {
        try {
            $builder = $this->db->table('view_emp_details');
            $builder->select('*');
            $builder->where('user_type', 'Tax Collector');
            $builder->where('emp_details_status', '1');
			$builder->orderBy('emp_name', 'ASC');
            $builder = $builder->get();
            $builder = $builder->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getActivatedAllTcList()
    {
        try {
            $builder = $this->db->table('view_active_emp_details');
            $builder->select('*');
            $builder->where('user_type', 'Tax Collector');
            $builder->where('emp_details_status', '1');
			$builder->orderBy('emp_name', 'ASC');
            $builder = $builder->get();
            $builder = $builder->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function refreshEmpMaterialView(){
        $sql = "SELECT dblink('host=localhost port=".getEnv("db.pgsql.port")." user=".getEnv("db.pgsql.uname")." password=".getEnv("db.pgsql.pass")." dbname=db_rmc_property'::text, 'REFRESH MATERIALIZED VIEW view_emp_details');";
        $this->db->query($sql);
        $sql = "SELECT dblink('host=localhost port=".getEnv("db.pgsql.port")." user=".getEnv("db.pgsql.uname")." password=".getEnv("db.pgsql.pass")." dbname=db_rmc_water'::text, 'REFRESH MATERIALIZED VIEW view_emp_details');";
        $this->db->query($sql);
        $sql = "SELECT dblink('host=localhost port=".getEnv("db.pgsql.port")." user=".getEnv("db.pgsql.uname")." password=".getEnv("db.pgsql.pass")." dbname=db_rmc_trade'::text, 'REFRESH MATERIALIZED VIEW view_emp_details');";
        $this->db->query($sql);
    }
}
