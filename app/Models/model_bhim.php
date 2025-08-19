<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_bhim extends Model
{
    protected $db;
    protected $table = 'tbl_transaction';
    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function searchHoldingDtl($holdingNo) {
        $sql = "SELECT
                    tbl_prop_dtl.id,
                    tbl_prop_dtl.new_holding_no,
                    owner_dtl.owner_name
                    --CASE WHEN demand_dtl.amount IS NULL THEN 0 ELSE demand_dtl.amount END AS amount
                FROM tbl_prop_dtl
                INNER JOIN (
                    SELECT 
                        prop_dtl_id,
                        STRING_AGG(owner_name, ', ') AS owner_name
                    FROM tbl_prop_owner_detail
                    WHERE status=1
                    GROUP BY prop_dtl_id
                ) owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
                /* LEFT JOIN (
                    SELECT
                        prop_dtl_id,
                        SUM(tbl_prop_demand.balance) AS amount
                    FROM tbl_prop_demand
                    WHERE
                        status=1
                        AND paid_status=0
                    GROUP BY prop_dtl_id
                ) AS demand_dtl ON demand_dtl.prop_dtl_id=tbl_prop_dtl.id */
                WHERE 
                    tbl_prop_dtl.new_holding_no ILIKE '".$holdingNo."'";
        return $this->db->query($sql)->getFirstRow();
    }
}
