<?php namespace App\Controllers;
use CodeIgniter\Controller;
use Predis\Client;

class API extends BaseController
{
	protected $db_property;
	protected $db_water;
	protected $db_trade;
	protected $redis_client;
	public function __construct()
    {
    	$this->redis_client = new Client();
    }

    public function property_details() {
    	$sql = "WITH total_prop_count AS (
				    SELECT COUNT(*) AS total_holding FROM tbl_prop_dtl WHERE status=1
				),
				total_prop_collection AS (
				    SELECT SUM(payable_amt) AS total_collection FROM tbl_transaction WHERE tran_type='Property' AND status=1 AND tran_date BETWEEN '2022-04-01' AND '2023-03-31'
				),
				total_saf_count AS (
				    SELECT COUNT(DISTINCT(tbl_saf_dtl.id)) AS saf_apply FROM tbl_saf_dtl
				    INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_saf_dtl.id AND tbl_transaction.tran_type='Saf' AND tbl_transaction.status=1
				),
				total_saf_collection AS  (
				    SELECT SUM(payable_amt) AS saf_collection FROM tbl_transaction WHERE tran_type='Saf' AND status=1 AND tran_date BETWEEN '2022-04-01' AND '2023-03-31'
				)
				SELECT 
				    total_prop_count.total_holding, 
				    total_prop_collection.total_collection, 
				    total_saf_count.saf_apply, 
				    total_saf_collection.saf_collection
				FROM total_prop_count, total_prop_collection, total_saf_count, total_saf_collection;";

		//$this->redis_client->del("smart_city_api_property_detail");
		$property_detail = $this->redis_client->get("smart_city_api_property_detail");
		if (!$property_detail) {
			$this->db_property = db_connect("db_rmc_property");
			if ($property_detail = $this->db_property->query($sql)->getResultArray()) {
				$this->redis_client->setEx("smart_city_api_property_detail", 3600, json_encode($property_detail));
			}
		} else {
			$property_detail = json_decode($property_detail, true);
		}
		return json_encode($property_detail);
    }

    public function water_api() {
        $sql = "SELECT 
        			*
				FROM (SELECT count (id) AS application
				             FROM tbl_apply_water_connection ) a
				JOIN (SELECT count(id)consumer
				     FROM tbl_consumer WHERE status=1
				) c on 1=1
				JOIN (SELECT sum(paid_amount) AS collection
				      FROM tbl_transaction WHERE status = 1 AND transaction_date BETWEEN '2022-04-01' AND '2023-03-31'
				) t on 1=1";
		$this->redis_client->del("smart_city_api_water_detail");
        $water_detail = $this->redis_client->get("smart_city_api_water_detail");
		if (!$water_detail) {
			$this->db_water = db_connect("db_rmc_water");
			if ($water_detail = $this->db_water->query($sql)->getResultArray()) {
				$this->redis_client->setEx("smart_city_api_water_detail", 3600, json_encode($water_detail));
			}
		} else {
			$water_detail = json_decode($water_detail, true);
		}
		return json_encode($water_detail);
	}

	public function trade_api() {
        $sql = "WITH 
					total_licence_issued AS (
						SELECT COUNT(*) AS license FROM tbl_apply_licence WHERE status=1 and pending_status=5 and update_status=0
					),
					total_trade_collection AS (
						SELECT SUM(paid_amount) AS collection FROM tbl_transaction WHERE status=1 AND verify_status=1 AND transaction_date BETWEEN '2022-04-01' AND '2023-03-31'
					),
					total_application_count AS (
						SELECT COUNT(*) as application FROM tbl_apply_licence where status=1
					)
					SELECT 
						total_application_count.application, 
						total_licence_issued.license, 
						total_trade_collection.collection
					FROM total_licence_issued, total_trade_collection, total_application_count;";

        // $this->redis_client->del("smart_city_api_trade_detail");
        $trade_detail = $this->redis_client->get("smart_city_api_trade_detail");
		if (!$trade_detail) {
			$this->db_trade = db_connect("db_rmc_trade");		
			if ($trade_detail = $this->db_trade->query($sql)->getResultArray()) {
				$this->redis_client->setEx("smart_city_api_trade_detail", 3600, json_encode($trade_detail));
			}
		} else {
			$trade_detail = json_decode($trade_detail, true);
		}
		return json_encode($trade_detail);
	}
}
