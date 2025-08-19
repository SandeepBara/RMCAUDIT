<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class Model_water_notice extends Model
{
    protected $db;
    protected $table = 'tbl_water_notices';


    protected $allowedFields = ['id', 'subject', 'sender_id', 'receiver_id', 'related_id', 'created_on', 'remarks', 'status', 'ulb_id', 'ink', 'status'];

    public function __construct(ConnectionInterface $db = null)
    {
        if ($db === null) {
            // If $db is not provided, use the default connection
            $this->db = $db ?: \Config\Database::connect('db_rmc_water');
        } else {
            $this->db = $db;
        }

//        $this->db = $db ?: \Config\Database::connect();

        try {
            // Switch to the correct database
            $this->db->setDatabase('db_rmc_water');

            $this->db->initialize();
//            echo 'Connected to the database. Database name: ' . $this->db->getDatabase();
        } catch (\Exception $e) {
//            die('Unable to connect to the database. Error: ' . $e->getMessage());
        }
    }

    public function insertWaterNoticeData($input)
    {
        try {
            $this->db->table($this->table)->insert($input);
            return $this->db->insertID();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getWaterNotice($consumer_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('wtr_dtl_id', $consumer_id)
                ->where('status', 1)
                ->orderBy('created_on', 'DESC')
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getWaterNoticeById($notice_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('MD5(id::text)', $notice_id)
                ->where('status', 1)
                ->orderBy('created_on', 'DESC')
                ->get();
            return $builder->getRowArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getWaterCount($consumer_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('count(*) as serial')
                ->where('wtr_dtl_id', $consumer_id)
                ->where('status', 1)
                ->get();
            return $builder->getRowArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function updateWater_Record($data, $notice_id)
    {
        return $this->db->table($this->table)
            ->where("id", $notice_id)
            ->update($data);
    }

    public function getBasicDetails($consumer_id)
    {
        try {
            $builder = $this->db->table('tbl_consumer')
                ->select('tbl_consumer.consumer_no, tbl_consumer.id, tbl_consumer.category, tbl_consumer.address, tbl_meter_status.meter_no AS meter_no_status, 
                tbl_consumer_details.applicant_name, tbl_consumer_details.mobile_no')
                ->join('tbl_meter_status', 'tbl_meter_status.consumer_id = tbl_consumer.id')
                ->join('tbl_consumer_details', 'tbl_consumer_details.consumer_id = tbl_consumer.id')
                ->where('tbl_consumer.id', $consumer_id);

            $result = $builder->get()->getRowArray();

            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    public function getWaterDemandDetails($consumer_id)
    {
       $sql = "SELECT 
                tbl_consumer.id AS tbl_consumer_id,
                (SELECT meter_no FROM tbl_meter_status WHERE tbl_meter_status.consumer_id = tbl_consumer.id 
                                                       ORDER BY created_on DESC LIMIT 1) AS meter_no,
                tbl_consumer.category,
                tbl_consumer.consumer_no,
                tbl_consumer.address,
                tbl_consumer_details.applicant_name,
                tbl_consumer_details.mobile_no,
                tbl_consumer_demand.penalty,
                tbl_consumer.connection_type_id AS connection_type,
                MIN(tbl_consumer_demand.demand_from) AS demand_from, 
                MAX(tbl_consumer_demand.demand_upto) AS demand_upto, 
                SUM(tbl_consumer_demand.amount) AS total_amount,
                SUM(tbl_consumer_demand.penalty) AS penalty_total, 
                SUM(tbl_consumer_demand.balance_amount) AS demand_total 
            FROM 
            tbl_consumer
        JOIN 
            tbl_consumer_demand ON tbl_consumer_demand.consumer_id = tbl_consumer.id 
        JOIN 
            tbl_consumer_details ON tbl_consumer_details.consumer_id = tbl_consumer.id
        WHERE 
            tbl_consumer_demand.paid_status = 0 
            AND tbl_consumer_demand.status = 1 
            AND tbl_consumer.id = '$consumer_id'
        GROUP BY 
            tbl_consumer.id,
            tbl_consumer.consumer_no,
            tbl_consumer.category,
            tbl_consumer.address,
            tbl_consumer_details.applicant_name,
            tbl_consumer_demand.penalty,
            tbl_consumer.connection_type_id,
            tbl_consumer_details.mobile_no";
            $result = $this->query($sql)->getFirstRow("array");
            return $result;

    }


    public function getUniqueNoticeId()
    {
        try{
            $builder = $this->db->table('tbl_water_notices')
                ->select('serial_no');
            $result = $builder->get()->getResultArray();
//            dd($result);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    public function getNoticeByNumber($notice_no)
    {
        try {
           $builder = $this->db->table('tbl_water_notices')
            ->select('serial_no');
           $result = $builder->get()->getResultArray();
//           dd($result);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }









}
