<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ModelTradeLicense extends Model 
{
    protected $db;
    protected $table = 'view_trade_licence';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function get_licence_list($from_date, $to_date, $ward_permission)
    {
        $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1) // active
                        ->where('pending_status', 5) // approved
                        ->where('date(license_date) >=', $from_date)
                        ->where('date(license_date) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->orderBy('id', 'DESC')
                        ->get()
                        ->getResultArray();
        //echo $this->db->getLastQuery();
        return $builder;
    }

    public function apply_licence_md5($applyid)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(id::text)', $applyid)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function get_wardwiselicence_list($from_date,$to_date,$ward_permission)
    {
        try
        {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1) // active
                        ->where('pending_status', 5) // approved
                        ->where('date(license_date) >=', $from_date)
                        ->where('date(license_date) <=', $to_date)
                        ->where('ward_mstr_id', $ward_permission)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    

    public function rejectedLicenseList($data)
    {
        try
        {
            $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('status', 1) // active
                            ->where('pending_status', 4) // rejected
                            ->where('date(license_date) >=', $data['from_date'])
                            ->where('date(license_date) <=', $data['to_date'])
                            ->orderBy('id','DESC')
                            ->get()
                            ->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function rejectedLicenseListByKeyword($keyword)
    {
        try
        {
            $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('status', 1) // active
                            ->where('pending_status', 4) // rejected
                            ->where("( firm_name like '%$keyword%'")
                            ->Orwhere("owner_name like '%$keyword%'")
                            ->Orwhere("mobile like '%$keyword%'")
                            ->Orwhere("application_no like '%$keyword%' )")
                            ->orderBy('id','DESC')
                            ->get()
                            ->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function cancellicencebyId($data)
	{
        $data['crrnt_date'] = date("Y-m-d");
        try
        {
            return  $this->db->table($this->table)
                            ->where('md5(id::text)', $data['id'])
                            ->update([
                                        "status" => 4,
                                        "cancelDate" => $data['crrnt_date']
                                    ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
	}
}
