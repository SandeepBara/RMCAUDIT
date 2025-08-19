<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_trade_level_pending_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_level_pending';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'apply_licence_id', 'sender_user_type_id', 'receiver_user_type_id', 'forward_date', 'forward_time', 'created_on', 'remarks', 'verification_status', 'status'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function getLastRecord($level_pending_id)
    {
        $builder = $this->db->table('view_trade_level_pending')
            ->select('*')
            ->where('id', $level_pending_id)
            // ->where('verification_status', 0)
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow('array');
    }
    public function getDataNew($where = array(), $column = array('*'), $tbl = '', $groupBy = array('id'), $orderBy = array('id' => 'ASC'))
    {
        if ($tbl == '')
            $tbl = $this->table;
        try {
            $data = array();
            $builder = $this->db->table($tbl)
                ->select($column);
            if (!empty($where)) {
                $builder = $builder->where($where);
            }
            $builder = $builder->groupBy($groupBy);
            foreach ($orderBy as $key => $val) {
                $builder = $builder->orderBy($key, $val);
            }
            $data = $builder->get()->getResultArray();
            if (sizeof($data) == 1) {
                $data = $data[0];
            } else
                $data = $data;
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function insrtlevelpendingdtl($input)
    {
        //print_r($input);
        $builder = $this->db->table($this->table)
            ->insert([
                "apply_licence_id" => $input["apply_licence_id"],
                "sender_user_type_id" => $input["sender_user_type_id"],
                "receiver_user_type_id" => $input["receiver_user_type_id"],
                "created_on" => $input["created_on"],
                "remarks" => $input["remarks"],
                "emp_details_id" => $input["emp_details_id"],
                "status" => '1'
            ]);
        //echo $this->db->getLastQuery();die;
        return $insert_id = $this->db->insertID();
    }

    public function tbl_level_sent_back_dtl($input)
    {
        $builder = $this->db->table('tbl_level_sent_back_dtl')
            ->insert([
                'level_id' => $input['level_id'],
                'apply_connection_id ' => $input['apply_licence_id'],
                'sender_user_type_id' => $input['sender_user_type_id'],
                'receiver_user_type_id' => $input['receiver_user_type_id'],
                'forward_date'  => $input['forward_date'],
                'forward_time' => $input['forward_time'],
                'created_on' => $input['created_on'],
                'remarks' => $input['remarks'],
                'verification_status'  => $input['verification_status'],
                'emp_details_id' => $input['emp_details_id'],
                'status' => $input['status'],
                'send_date' => $input['send_date'],
                'receiver_user_id' => $input['receiver_user_id'],
                'ip_address' => $input['ip_address'] ?? null,
            ]);
        return $insert_id = $this->db->insertID();
    }

    public function updatebacktocitizenById($input)
    {
        $builder = $this->db->table($this->table)
            ->where('md5(id::text)', $input['level_pending_dtl_id'])
            ->update([
                'remarks' => $input['remarks'],
                'forward_date' => $input['forward_date'],
                'forward_time' => $input['forward_time'],
                // 'emp_details_id'=> $input['emp_details_id'],
                'receiver_user_id' => $input['emp_details_id'],
                'status' => 2
            ]);
        //echo $this->db->getLastQuery();
        return  $builder;
    }
    public function backtocitizen_dl_remarks_by_con_id($apply_licence_id)
    {
        try {
            return $this->db->table($this->table)
                ->select('remarks')
                ->where('sender_user_type_id', 0)
                ->where('receiver_user_type_id', 17)
                ->where('verification_status ', 2)
                ->where('apply_licence_id', $apply_licence_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get()
                // ->getResultArray()[0];
                ->getFirstRow('array');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function updatelevelpendingById($input)
    {
        if ($input['sender_user_type_id'] == 19) {
            $status = 5;
        } else {
            $status = 3;
        }
        $builder = $this->db->table($this->table)
            ->where('md5(id::text)', $input['level_pending_dtl_id'])
            ->update([
                'forward_date' => $input['forward_date'],
                'forward_time' => $input['forward_time'],
                'status' =>  $status,
                'receiver_user_id' => $input['emp_details_id'] ?? null,
            ]);
        // echo $this->db->getLastQuery();
        // print_var($builder);
        return $builder;
    }

    public function updatestatuslevelpendingById($levelid)
    {
        $builder = $this->db->table($this->table)
            ->where('md5(id::text)', $levelid)
            ->update([
                'status' => 1
            ]);
        //echo $this->db->getLastQuery();
        return $builder;
    }

    public function approved_dl_remarks_by_con_id($apply_licence_id)
    {
        try {
            $levelid = [17, 18, 19, 20];
            $builder = $this->db->table($this->table)
                ->select('remarks, sender_user_type_id')
                ->whereIn('sender_user_type_id', $levelid)
                //->where('verification_status ',0)
                ->where('apply_licence_id', $apply_licence_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get()
                ->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function forward_remarks_by_con_id($apply_licence_id, $sender_user_type_id)
    {
        try {
            return $this->db->table($this->table)
                ->select('remarks')
                ->where('sender_user_type_id', $sender_user_type_id)
                ->where('verification_status ', 0)
                ->where('apply_licence_id', $apply_licence_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get()
                ->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function approved_sh_remarks_by_con_id($apply_licence_id)
    {
        try {
            return $this->db->table($this->table)
                ->select('remarks')
                ->where('sender_user_type_id', 18)
                //->where('verification_status ',0)
                ->where('apply_licence_id', $apply_licence_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get()
                ->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getusertypebyid_md5($applyid)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('receiver_user_type_id')
                //->where('verification_status', 0)
                ->where('status', 1)
                ->where('md5(apply_licence_id::text)', $applyid)
                ->orderBy('id', 'DESC')
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function check_btcz_to_which_level($applyid)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('receiver_user_type_id')
                //->where('verification_status', 2)
                ->where('status', 2)
                ->where('md5(apply_licence_id::text)', $applyid)
                ->orderBy('id', 'DESC')
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getLevelRecordBtczn($apply_licence_id)
    {
        try {
            $builder = $this->db->table('view_backtocitizenlist')
                ->select('receiver_user_type_id')
                ->where('md5(apply_licence_id::text)', $apply_licence_id)
                ->orderBy('id', 'DESC')
                ->get();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function insrtTradeLevelPendingDtl($input)
    {
        try {
            $builder = $this->db->table($this->table)
                ->insert([
                    "apply_licence_id" => $input['apply_licence_id'],
                    "sender_user_type_id" => $input['sender_user_type_id'],
                    "receiver_user_type_id" => $input['receiver_user_type_id'],
                    "forward_date" => $input['forward_date'],
                    "forward_time" => $input['forward_time'],
                    "created_on" => $input['created_on'],
                    "emp_details_id" => $input['emp_details_id']
                ]);
            // echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function hide_rmc_btn($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('verification_status')
                ->where('status', 1)
                ->where('apply_licence_id', $data)
                ->orderBy('id', 'DESC')
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getLevelDendingDetailsIdForEo($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('md5(apply_licence_id::text)', $apply_licence_id)
                ->where('receiver_user_type_id', 19)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();
            // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function approved_dl_remarks($apply_licence_id)
    {
        try {
            return $this->db->table($this->table)
                ->select('remarks')
                ->where('sender_user_type_id', 17)
                ->where('apply_licence_id', $apply_licence_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get()
                ->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getLevelPendingDetailsIdForSectionHead($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('md5(apply_licence_id::text)', $apply_licence_id)
                ->where('receiver_user_type_id', 18)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();
            /*echo $this->db->getLastQuery();*/
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getLevelPendingDetailsIdForExecutiveOfficer($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('md5(apply_licence_id::text)', $apply_licence_id)
                ->where('receiver_user_type_id', 19)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();
            /*echo $this->db->getLastQuery();*/
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getLevelPendingDetailsIdForTaxDaroga($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('md5(apply_licence_id::text)', $apply_licence_id)
                ->where('receiver_user_type_id', 17)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();
            /*echo $this->db->getLastQuery();*/
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateRejectStatus($id, $remarks, $emp_details_id)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $id)
                ->update([
                    'emp_details_id' => $emp_details_id,
                    'remarks' => $remarks,
                    'status' => 4
                ]);
            /*echo $this->db->getLastQuery();*/
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDealingOfficerLevelRemaeks($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('apply_licence_id', $data['apply_licence_id'])
                ->where('sender_user_type_id', 17)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();
            /*echo $this->db->getLastQuery();*/
            return $builder = $builder->getFirstRow('array');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getLevelRemaks($apply_licence_id,$where)
    {
        try {
            $sql = "SELECT case when tbl_level_pending.status =2 and tbl_level_sent_back_dtl.id is not null then tbl_level_sent_back_dtl.remarks	
                            else tbl_level_pending.remarks end as remarks, 
                        case when tbl_level_pending.status=2 and tbl_level_sent_back_dtl.id is not null then tbl_level_sent_back_dtl.created_on	
                            else tbl_level_pending.created_on end as created_on,
                        case when tbl_level_pending.status=2 and tbl_level_sent_back_dtl.id is not null then tbl_level_sent_back_dtl.forward_date	
                            else tbl_level_pending.forward_date end as forward_date, 
                        case when tbl_level_pending.status=2 and tbl_level_sent_back_dtl.id is not null then  tbl_level_sent_back_dtl.forward_time	
                            else tbl_level_pending.forward_time end as forward_time,
                        tbl_level_pending.status,tbl_level_sent_back_dtl.level_id,tbl_level_pending.id
                    FROM tbl_level_pending 
                    LEFT JOIN tbl_level_sent_back_dtl ON tbl_level_sent_back_dtl.level_id = tbl_level_pending.id 
                        AND tbl_level_pending.status = 2
                    WHERE tbl_level_pending.apply_licence_id = $apply_licence_id 
                        AND tbl_level_pending.status <> 0 
                        $where
                    ORDER BY tbl_level_pending.id ASC ";

            //    echo $this->db->getLastQuery();
            $builder = $this->db->query($sql);
            return $builder = $builder->getResultArray();
        }
        catch (Exception $e) 
        {
            echo $e->getMessage();
        }
    }

    public function getDealingLevelData($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('remarks,created_on,forward_date,forward_time')
                ->where('apply_licence_id', $apply_licence_id)
                ->whereIn('sender_user_type_id ', [17, 0])
                ->where('status<>', 0)
                ->orderBy('id', 'asc')
                ->get();
            //    echo $this->db->getLastQuery();
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDealingReceiveDate($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('created_on')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('receiver_user_type_id', 17)
                ->where('status<>', 0)
                ->get();
            /*echo $this->db->getLastQuery();*/
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTaxDarogaLevelData($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('remarks,created_on,forward_date,forward_time')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('sender_user_type_id', 20)
                //   ->where('status',1)
                ->where('status<>', 0)
                ->orderBy('id', 'asc')
                ->get();
            // echo $this->db->getLastQuery();
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTaxDarogaReceiveDate($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('created_on')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('receiver_user_type_id', 20)
                ->where('status', 1)
                ->get();
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getSectionHeadLevelData($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('remarks,created_on,forward_date,forward_time')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('sender_user_type_id', 18)
                ->where('status<>', 0)
                ->orderBy('id', 'asc')
                ->get();
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getSectionHeadReceiveDate($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('created_on')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('receiver_user_type_id', 18)
                ->where('status', 1)
                ->get();
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    public function getExecutiveLevelData($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('remarks,created_on,forward_date,forward_time')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('sender_user_type_id', 19)
                ->where('status<>', 0)
                ->orderBy('id', 'asc')
                ->get();
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getExecutiveReceiveDate($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('created_on')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('receiver_user_type_id', 19)
                ->where('status', 1)
                ->get();
            return $builder = $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getLevelPendingDtlId($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('apply_licence_id', $apply_licence_id)
                ->where('sender_user_type_id', 17)
                ->where('sender_user_type_id', 20)
                ->where('verification_status', 3)
                ->where('status', 1)
                ->get();
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function backapplyLicense($where)
    {
        $sql = "select count(id)
		from tbl_level_pending where $where and status=1 and verification_status=2";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }
    public function pendingapplyLicense($where)
    {
        $sql = "select count(id),apply_licence_id
		from tbl_level_pending where $where and status=1 and verification_status=0 group by apply_licence_id";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
    public function finalapplyLicense($where)
    {
        $sql = "select count(id)
		from tbl_level_pending where $where and status=1 and verification_status=5";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }


    public function pending_at_level($where)
    {
        $sql = "select count(tbl_level_pending.id) from tbl_level_pending
        left join tbl_apply_licence 
        on tbl_level_pending.apply_licence_id = tbl_apply_licence.id 
        where $where and tbl_level_pending.id in (select max(id) 
        from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status = 1";

        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        // echo $this->getLastQuery();exit;
        return $result;
    }


    public function back_to_citizen($where)
    {
        $sql = "select count(tbl_level_pending.status) from tbl_level_pending 
        left join tbl_apply_licence 
        on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
        where $where and tbl_level_pending.id in (select max(id) 
        from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status=2";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }

    public function get_receiver_user_type_id_orderbydesc($applyid)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('receiver_user_type_id')
                ->where('md5(apply_licence_id::text)', $applyid)
                ->orderBy('id', 'desc')
                ->get();
            //echo $this->db->getLastQuery(); 
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function final_licence($where)
    {
        $sql = "select count(tbl_level_pending.status) from tbl_level_pending 
        left join tbl_apply_licence 
        on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
        where $where and tbl_level_pending.id in (select max(id) 
        from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status =5";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //  echo $this->getLastQuery();exit;
        return $result;
    }

    public function pendingAtda($where)
    {
        $sql = "select count(tbl_level_pending.id) from tbl_level_pending 
        left join tbl_apply_licence 
        on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
        where $where and tbl_level_pending.id in (select max(id) 
        from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status = 1 
        and tbl_level_pending.receiver_user_type_id = 17";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function pendingAttaxdaroga($where)
    {
        $sql = "select count(tbl_level_pending.id) from tbl_level_pending 
        left join tbl_apply_licence 
        on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
        where $where and tbl_level_pending.id in (select max(id) 
        from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status = 1 
        and tbl_level_pending.receiver_user_type_id = 20";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function pendingAtsec($where)
    {
        $sql = "select count(tbl_level_pending.id) from tbl_level_pending 
        left join tbl_apply_licence 
        on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
        where $where and tbl_level_pending.id in (select max(id) 
        from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status = 1 
        and tbl_level_pending.receiver_user_type_id = 18";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }




    public function pendingAteo($where)
    {
        $sql = "select count(tbl_level_pending.id) from tbl_level_pending 
        left join tbl_apply_licence 
        on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
        where $where and tbl_level_pending.id in (select max(id) 
        from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status = 1 
        and tbl_level_pending.receiver_user_type_id = 19";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }


    // get highest level pending id 
    public function get_level_id($apply_licence_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('md5(apply_licence_id::text)', $apply_licence_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow('array');
            return $builder['id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function insrtdeniallevelpendingdtl($input)
    {
        //print_r($input);
        return $builder = $this->db->table($this->table)
            ->insert([
                "apply_licence_id" => $input["denial_id"],
                "sender_user_type_id" => 0,
                "receiver_user_type_id" => 10,
                "created_on" => $input["created_on"],
                "remarks" => $input["remarks"],
                "verification_status" => 0,
                "status" => '1'
            ]);
        /*echo $this->db->getLastQuery();*/
        //return $insert_id = $this->db->insertID();
    }

    public function total_rejected_form($where)
    {
        $sql = "select count(tbl_level_pending.id) from tbl_level_pending 
      left join tbl_apply_licence 
      on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
       where $where and tbl_level_pending.id in (select max(id) 
      from tbl_level_pending group by apply_licence_id) and tbl_level_pending.status = 4";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function totalprovisional($where)
    {
        $sql = "select count(id) from tbl_apply_licence  where $where and provisional_license_no is not null and status = 1 and payment_status = 1";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        // echo $this->getLastQuery();exit;
        return $result;
    }

    //denial code start 
    public function sendToEO($input)
    {
        $builder = $this->db->table('tbl_denial_mail_dtl')
            ->insert([
                "denial_id" => $input["denial_id"],
                "sender_id" => $input["sender_id"],
                "sender_user_type_id" => $input["sender_user_type_id"],
                "receiver_user_type_id" => $input["receiver_user_type_id"],
                "remarks" => $input["remarks"],
                "created_on" => $input["created_on"],
            ]);
        //echo $this->db->getLastQuery();exit;
        return $insert_id = $this->db->insertID();
    }

    public function updateMail($input)
    {
        try {
            return $builder = $this->db->table('tbl_denial_mail_dtl')
                ->where('md5(denial_id::text)', $input["denial_id"])
                ->update([
                    'forward_date' => $input["forward_date"],
                    'forward_time' => $input["forward_time"],
                    'status' => $input["status"],
                    'emp_details_id' => $input["emp_details_id"]
                ]);
            //echo $this->db->getLastQuery();exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function totalprovisionalview($where)
    {
        $sql = "select * from tbl_apply_licence  where $where and provisional_license_no is not null and status = 1 and payment_status = 1 and 
              apply_from ='TC'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function view_licenceFromlevel($where)
    {
        $sql = "select * from  
            (select apply_licence_id from tbl_level_pending where $where and id in (select max(id) 
            from tbl_level_pending group by apply_licence_id)) as lvl
            left join tbl_apply_licence
            on lvl.apply_licence_id = tbl_apply_licence.id where tbl_apply_licence.apply_from ='TC'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function back_to_citizenTc($where)
    {
        $sql = "select count(lvl.id) from 
        (select id,apply_licence_id from tbl_level_pending where $where 
        and id in (select max(id) from tbl_level_pending group by apply_licence_id) and status=2) as lvl 
        left join tbl_apply_licence on lvl.apply_licence_id  = tbl_apply_licence.id 
        where tbl_apply_licence.apply_from = 'TC'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        return $result;
    }

    public function pending_at_levelTc($where)
    {
        $sql = "select count(lvl.id) from 
        (select id,apply_licence_id from tbl_level_pending where $where 
        and id in (select max(id) from tbl_level_pending group by apply_licence_id) and status not in (2,4,5)) as lvl 
        left join tbl_apply_licence on lvl.apply_licence_id  = tbl_apply_licence.id 
        where tbl_apply_licence.apply_from = 'TC'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        // echo $this->getLastQuery();exit;
        return $result;
    }

    public function final_licenceTc($where)
    {
        $sql = "select count(lvl.id) from 
        (select id,apply_licence_id from tbl_level_pending where $where 
        and id in (select max(id) from tbl_level_pending group by apply_licence_id) and status = 5) as lvl 
        left join tbl_apply_licence on lvl.apply_licence_id  = tbl_apply_licence.id 
        where tbl_apply_licence.apply_from = 'TC'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        // echo $this->getLastQuery();exit;
        return $result;
    }

    public function total_rejected_formTc($where)
    {
        $sql = "select count(lvl.id) from 
      (select id,apply_licence_id from tbl_level_pending where $where 
      and id in (select max(id) from tbl_level_pending group by apply_licence_id) and status = 4) as lvl 
      left join tbl_apply_licence on lvl.apply_licence_id  = tbl_apply_licence.id 
      where tbl_apply_licence.apply_from = 'TC'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        // echo $this->getLastQuery();exit;
        return $result;
    }

    public function totalprovisionalTc($where)
    {
        $sql = "select count(id) from tbl_apply_licence  where $where 
      and provisional_license_no is not null and status = 1 and payment_status = 1 and apply_from = 'TC'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function rowQuery($sql, $args = array())
    {
        $qury = $this->db->query($sql, $args)->getResultArray();
        //echo $this->db->getLastQuery(); print_var($qury);die;
        return $qury;
    }


    public function employeedetails($ward_mstr_id, $user_type_id, $ward_no)
    {
        $data = [];
        $data['emp_name'] = "";
        if ($user_type_id == "11" || $user_type_id == "5") {
            return $data;
        }

        $sql = "
            select distinct view_emp_details.emp_name ,view_emp_details.id 
            from view_ward_permission 
            left join view_emp_details on view_emp_details.id=view_ward_permission.emp_details_id
            where ward_mstr_id=$ward_mstr_id and user_type_id=$user_type_id
                and lock_status=0 
            order by view_emp_details.id desc
            limit 1;
        ";
        return $this->db->query($sql)->getResultArray()[0] ?? "";//['emp_name'];
    }
}
