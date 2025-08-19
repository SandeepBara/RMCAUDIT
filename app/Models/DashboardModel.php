<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class DashboardModel extends Model
{

    protected $table1 = 'tbl_dashboard_tmp_data_fysummary';
    protected $table2 = 'tbl_dashboard_tmp_data_monthly_collection';

	public function __construct(ConnectionInterface $db) {
	    $this->db = $db;
	}

    public function getSummaryData($fyear) {
        // select  sum(no_of_sam_generated) as no_of_sam_generated, sum(no_of_verification) as no_of_verification, 
        // sum(no_of_geo_tagging) as no_of_geo_tagging, sum(no_of_fam_generated) as no_of_fam_generated, sum(arrear_demand) as arrear_demand, sum(arrear_collection) as arrear_collection, sum(current_demand) as current_demand, sum(current_collection) as current_collection, 
        // sum(new_assessment) as new_assessment, sum(reassessment) as reassessment, sum(mutation) as mutation , sum(legacy)  as legacy
        // from tbl_dashboard_tmp_data_fysummary 
        // where fyear='2021-2022' and status=1

        $result=$this->db->table($this->table1)
                    ->select('sum(no_of_sam_generated) as no_of_sam_generated, sum(no_of_verification) as no_of_verification, 
                                sum(no_of_geo_tagging) as no_of_geo_tagging, sum(no_of_fam_generated) as no_of_fam_generated, sum(arrear_demand) as arrear_demand, sum(arrear_collection) as arrear_collection, sum(current_demand) as current_demand, sum(current_collection) as current_collection, 
                                sum(new_assessment) as new_assessment, sum(reassessment) as reassessment, sum(mutation) as mutation , sum(legacy)  as legacy,
                                sum(total_property) as total_property')
                    ->where('status', 1)
                    ->where('fyear', $fyear)
                    ->get()
                    ->getFirstRow('array');
        //echo $this->db->getLastquery();
        //print_var($result);
        return $result;
    }

    public function getMonthlyCollection($fyear) {

        $result=$this->db->table($this->table2)
                    ->select('*')
                    ->where('status', 1)
                    ->where('fyear', $fyear)
                    ->get()
                    ->getResultArray();
        //echo $this->db->getLastquery();
        //print_var($result);
        return $result;
    }
    
}