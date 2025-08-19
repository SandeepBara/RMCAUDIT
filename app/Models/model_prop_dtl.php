<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_prop_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_prop_dtl';
    protected $allowedFields = ['id', 'has_previous_holding_no', 'previous_holding_no', 'previous_ward_mstr_id', 'is_owner_changed', 'previous_holding_owner_name', 'previous_holding_owner_address', 'transfer_mode_mstr_id', 'saf_no', 'holding_no', 'ward_mstr_id', 'ownership_type_mstr_id', 'prop_type_mstr_id', 'appartment_name', 'no_electric_connection', 'elect_consumer_no', 'elect_acc_no', 'elect_bind_book_no', 'elect_cons_category', 'building_plan_approval_no', 'building_plan_approval_date', 'water_conn_no', 'water_conn_date', 'khata_no', 'plot_no', 'village_mauja_name', 'road_type_mstr_id', 'area_of_plot', 'prop_address', 'prop_city', 'prop_dist', 'prop_pin_code', 'corr_address', 'corr_city', 'corr_dist', 'corr_pin_code', 'is_mobile_tower', 'tower_area', 'tower_installation_date', 'is_hoarding_board', 'hoarding_area', 'hoarding_installation_date', 'is_petrol_pump', 'under_ground_area', 'petrol_pump_completion_date', 'is_water_harvesting', 'occupation_date', 'payment_status', 'doc_verify_status', 'doc_verify_date', 'doc_verify_emp_details_id', 'doc_verify_cancel_remarks', 'field_verify_status', 'field_verify_date', 'field_verify_emp_details_id', 'emp_details_id', 'created_on', 'status'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function updateProp($prop_dtl_id_md5, $inputs)
    {
        $builder = $this->db->table($this->table)
            ->where('md5(id::text)', $prop_dtl_id_md5)
            ->update($inputs);
        //echo ($this->db->getLastQuery());
        return $builder;
    }

    // code added on 11-05-2022

    function getbulkDemandData(){

        $sql ="";
        $run  = $this->db->query($sql);
        $result = $run->getResultArray();
    }


    // ends here

    public function propertyDetailsList()
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function propertyDetailsListbyHoldingNo($holding_no)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('holding_no', strtoupper($holding_no))
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function propertyDetailsbyHoldingNo($holding_no)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('tbl_prop_dtl.id,holding_no,ward_mstr_id,prop_address,prop_city,prop_dist,prop_pin_code,ward_no')
                ->join('view_ward_mstr', 'view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id')
                ->where('tbl_prop_dtl.holding_no', strtoupper($holding_no))
                ->where('tbl_prop_dtl.status', 1)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function propertyDetailsfortradebyHoldingNo($input)
    {
        try {
            $builder = "with data as (
                        select tbl_prop_dtl.id,holding_no, new_holding_no, ward_mstr_id,prop_address,prop_city,prop_dist, prop_pin_code,ward_no ,holding_type
                         from tbl_prop_dtl
                         inner join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                ),
                owner as (select string_agg(owner_name, ',') as owner_name,string_agg(mobile_no::varchar,',') as mobile_no,prop_dtl_id from tbl_prop_owner_detail group by prop_dtl_id
                )
                select data.*,owner.* 
                from data inner 
                join owner on data.id= owner.prop_dtl_id 
                where data.new_holding_no='" . strtoupper($input["holding_no"]) . "'"; 
                if(isset($input["ward_mstr_id"]) && $input["ward_mstr_id"])
                {
                    $builder .= " and data.ward_mstr_id='" . $input["ward_mstr_id"] . "'";
                }

            $ql = $this->db->query($builder);
            //echo $this->db->getLastQuery();
            return $ql->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getPropertyDetails($input)
    {
        try {
            $builder = "with data as (
                        select tbl_prop_dtl.id,new_holding_no,ward_mstr_id,prop_address,prop_city,prop_dist, prop_pin_code,ward_no ,holding_type
                         from tbl_prop_dtl
                         inner join view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                ),
                owner as (select string_agg(owner_name, ',') as owner_name,string_agg(mobile_no::varchar,',') as mobile_no,prop_dtl_id from tbl_prop_owner_detail group by prop_dtl_id
                )
                select data.*,owner.* from data inner join owner on data.id= owner.prop_dtl_id where data.new_holding_no='" . strtoupper($input["holding_no"]) . "' and data.ward_mstr_id='" . $input["ward_mstr_id"] . "'
                and data.holding_type in('MIX_COMMERCIAL','PURE_COMMERCIAL')";

            $ql = $this->query($builder);
            //echo $this->getLastQuery();
            return $ql->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getholdingnobypropid($prop_dtl_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('holding_no')
                ->where('id', $prop_dtl_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function propertyDetailsListbyWardid($ward_mstr_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('ward_mstr_id', $ward_mstr_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function propertyDetailsListbypropid($prop_dtl_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id', $prop_dtl_id)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function consumer_details($where)
    {
        $builder = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type
		where " . $where;
        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }

    public function citizen_details($where)
    {
        $builder = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type
		where " . $where;


        $builder = $this->query($builder);

        // print_var($builder->getResultArray());
        // die;

        return $builder = $builder->getResultArray();
        // return $builder = $builder->getResultArray()[0];
        // return $builder = isset($builder->getResultArray()[0])?$builder->getResultArray()[0]:null;
        // return $builder = $builder->getResultArray();

    }
    public function citizen_details2($where)
    {
        $builder = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type
		where " . $where;

        $builder = $this->query($builder);

        return $builder = $builder->getResultArray()[0];
        // return $builder = isset($builder->getResultArray()[0])?$builder->getResultArray()[0]:null;
        // return $builder = $builder->getResultArray();

    }


    public function basic_details($data)
    {
        try {
            $builder = $this->db->table("view_prop_dtl_owner_ward_prop_type_ownership_type")
                ->select('*')
                ->where('md5(prop_dtl_id::text)', $data['id'])
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function basic_dtl($data)
    {
        try {
            $builder = $this->db->table("view_prop_dtl_owner_ward_prop_type_ownership_type")
                ->select('*')
                ->where('md5(prop_dtl_id::text)', $data)
                ->get();

            return $builder->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
//llll
    public function basic_receipt_dtl($data)
    {
        try {
            $builder = $this->db->table("view_receipt_prop_owner_dtl")
                ->select('*')
                ->where('md5(prop_dtl_id::text)', $data)
                ->get();

            return $builder->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getholdWard($data)
    {
        try {
            $builder = $this->db->table("view_prop_dtl_owner_ward_prop_type_ownership_type")
                ->select('holding_no,ward_no')
                ->where('md5(prop_dtl_id::text)', $data)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function get_prop_dtl($prop_dtl_id)
    {
        try {
            $builder = $this->db->table("view_prop_dtl_owner_ward_prop_type_ownership_type")
                ->select('*')
                ->where('prop_dtl_id', $prop_dtl_id)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getholdingnobysafid($saf_dtl_id)
    {
        try {
            $builder = $this->db->table("tbl_prop_dtl")
                ->select('id,holding_no,prop_address,prop_city,prop_dist,prop_pin_code,new_holding_no,new_ward_mstr_id, ward_mstr_id')
                ->where('saf_dtl_id', $saf_dtl_id)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function holding_no()
    {
        try {
            $builder = $this->db->table("tbl_prop_dtl")
                ->select('id,holding_no')
                ->where('status', 1)
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getPropdetailsbyid($prop_dtl_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id, for_sub_holding, holding_no, new_holding_no, entry_type, prop_address, prop_city, ward_mstr_id')
                ->where('status', 1)
                ->where('id', $prop_dtl_id)
                ->get();
            //echo $this->db->getLastQuery();
            //return $builder->getResultArray()[0];
            return $builder->getFirstRow('array');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function updateUniqueHoldingNoInPropDtlByDocVerifyTime($input)
    {
        $this->db->table($this->table)
            ->where('id', $input['prop_dtl_id'])
            ->update([
                'new_holding_no' => $input['new_holding_no']
            ]);
    }
    public function updatePropDetByDocVerifyTime($input)
    {
        try {
            $builder = $this->db->table("tbl_saf_dtl")
                ->select('ward_mstr_id, new_ward_mstr_id, ownership_type_mstr_id,
                                    prop_type_mstr_id, appartment_name, zone_mstr_id, no_electric_connection,
                                    elect_consumer_no, elect_acc_no, elect_bind_book_no,
                                    elect_cons_category, building_plan_approval_no, building_plan_approval_date,
                                    water_conn_no, water_conn_date, khata_no, plot_no,
                                    village_mauja_name, road_type_mstr_id, area_of_plot,
                                    prop_address, prop_city, prop_dist, prop_pin_code,
                                    corr_address,corr_city, corr_dist,
                                    corr_pin_code, is_mobile_tower, tower_area,
                                    tower_installation_date, is_hoarding_board, hoarding_area,
                                    hoarding_installation_date, is_petrol_pump, under_ground_area,
                                    petrol_pump_completion_date, is_water_harvesting, assessment_type')
                ->where('id', $input['saf_dtl_id'])
                ->get();
            $safDtl = $builder->getFirstRow("array");

            $this->db->table($this->table)
                ->where('id', $input['prop_dtl_id'])
                ->update([
                    'saf_dtl_id' => $input['saf_dtl_id'], 'new_ward_mstr_id' => $safDtl['new_ward_mstr_id'], 'ownership_type_mstr_id' => $safDtl['ownership_type_mstr_id'],
                    'prop_type_mstr_id' => $safDtl['prop_type_mstr_id'], 'appartment_name' => $safDtl['appartment_name'], 'zone_mstr_id' => $safDtl['zone_mstr_id'], 'no_electric_connection' => $safDtl['no_electric_connection'],
                    'elect_consumer_no' => $safDtl['elect_consumer_no'], 'elect_acc_no' => $safDtl['elect_acc_no'], 'elect_bind_book_no' => $safDtl['elect_bind_book_no'], '
                                elect_cons_category' => $safDtl['elect_cons_category'], 'building_plan_approval_no' => $safDtl['building_plan_approval_no'], 'building_plan_approval_date' => $safDtl['building_plan_approval_date'],
                    'water_conn_no' => $safDtl['water_conn_no'], 'water_conn_date' => $safDtl['water_conn_date'], 'khata_no' => $safDtl['khata_no'], 'plot_no' => $safDtl['plot_no'],
                    'village_mauja_name' => $safDtl['village_mauja_name'], 'road_type_mstr_id' => $safDtl['road_type_mstr_id'], 'area_of_plot' => $safDtl['area_of_plot'],
                    'prop_address' => $safDtl['prop_address'], 'prop_city' => $safDtl['prop_city'], 'prop_dist' => $safDtl['prop_dist'], 'prop_state' => $safDtl['prop_state'], 'prop_pin_code' => $safDtl['prop_pin_code'],
                    'corr_address' => $safDtl['corr_address'], 'corr_city' => $safDtl['corr_city'], 'corr_dist' => $safDtl['corr_dist'], 'corr_state' => $safDtl['corr_state'],
                    'corr_pin_code' => $safDtl['corr_pin_code'], 'is_mobile_tower' => $safDtl['is_mobile_tower'], 'tower_area' => $safDtl['tower_area'],
                    'tower_installation_date' => $safDtl['tower_installation_date'], 'is_hoarding_board' => $safDtl['is_hoarding_board'], 'hoarding_area' => $safDtl['hoarding_area'],
                    'hoarding_installation_date' => $safDtl['hoarding_installation_date'], 'is_petrol_pump' => $safDtl['is_petrol_pump'], 'under_ground_area' => $safDtl['under_ground_area'],
                    'petrol_pump_completion_date' => $safDtl['petrol_pump_completion_date'], 'is_water_harvesting' => $safDtl['is_water_harvesting'], 'occupation_date' => $safDtl['occupation_date'],
                    'assessment_type' => $safDtl['assessment_type']
                ]);
            //echo $this->db->getLastQuery();
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function insertpropdetbysafid($input)
    {
        $sql_prop = "INSERT INTO tbl_prop_dtl
        (saf_dtl_id, holding_no, ward_mstr_id, ownership_type_mstr_id,
        prop_type_mstr_id, appartment_name, no_electric_connection,
        elect_consumer_no, elect_acc_no, elect_bind_book_no,
        elect_cons_category, building_plan_approval_no, building_plan_approval_date,
        water_conn_no, water_conn_date, khata_no, plot_no,
        village_mauja_name, road_type_mstr_id, area_of_plot,
        prop_address, prop_city, prop_dist, prop_pin_code,
        corr_address, corr_city, corr_dist,
        corr_pin_code, is_mobile_tower, tower_area,
        tower_installation_date, is_hoarding_board, hoarding_area,
        hoarding_installation_date, is_petrol_pump, under_ground_area,
        petrol_pump_completion_date, is_water_harvesting, occupation_date,
        emp_details_id, created_on, status,
        prop_state, corr_state, zone_mstr_id, entry_type, assessment_type, holding_type)
    SELECT
        '" . $input['saf_dtl_id'] . "', '" . $input['generated_holding_no'] . "', ward_mstr_id, ownership_type_mstr_id,
        prop_type_mstr_id, appartment_name, no_electric_connection,
        elect_consumer_no, elect_acc_no, elect_bind_book_no,
        elect_cons_category, building_plan_approval_no, building_plan_approval_date,
        water_conn_no, water_conn_date, khata_no, plot_no,
        village_mauja_name, road_type_mstr_id, area_of_plot,
        prop_address, prop_city, prop_dist, prop_pin_code,
        corr_address,corr_city, corr_dist,
        corr_pin_code, is_mobile_tower, tower_area,
        tower_installation_date, is_hoarding_board, hoarding_area,
        hoarding_installation_date, is_petrol_pump, under_ground_area,
        petrol_pump_completion_date, is_water_harvesting, NULL,
        '" . $input['emp_details_id'] . "', '" . $input['created_on'] . "', status,
        prop_state, corr_state, zone_mstr_id , assessment_type, assessment_type, holding_type
    FROM tbl_saf_dtl WHERE id='" . $input['saf_dtl_id'] . "'";
        $this->db->query($sql_prop);
        //echo $this->db->getLastQuery();
        $prop_dtl_id = $this->db->insertID();
        return $prop_dtl_id;
    }

    public function insertlegacypropdetbysafid($input)
    {

        $sql_prop = "insert into tbl_prop_dtl(saf_dtl_id,holding_no,ward_mstr_id,
        ownership_type_mstr_id,prop_type_mstr_id,appartment_name,no_electric_connection,elect_consumer_no,elect_acc_no,elect_bind_book_no,
        elect_cons_category,building_plan_approval_no,building_plan_approval_date,water_conn_no,water_conn_date,khata_no,plot_no,
        village_mauja_name,road_type_mstr_id,area_of_plot,prop_address,prop_city,prop_dist,prop_pin_code,corr_address,corr_city,corr_dist,
        corr_pin_code,is_mobile_tower,tower_area,tower_installation_date,is_hoarding_board,hoarding_area,hoarding_installation_date,
        is_petrol_pump,under_ground_area,petrol_pump_completion_date,is_water_harvesting,occupation_date,emp_details_id,
        created_on,status,prop_state,corr_state,zone_mstr_id,old_ward_mstr_id,old_holding_no)select '" . $input['saf_dtl_id'] . "','" . $input['generated_holding_no'] . "',ward_mstr_id,ownership_type_mstr_id,prop_type_mstr_id,appartment_name,no_electric_connection,elect_consumer_no,
        elect_acc_no,elect_bind_book_no,elect_cons_category,building_plan_approval_no,building_plan_approval_date,water_conn_no,water_conn_date,
        khata_no,plot_no,village_mauja_name,road_type_mstr_id,area_of_plot,prop_address,prop_city,prop_dist,prop_pin_code,corr_address,corr_city,
        corr_dist,corr_pin_code,is_mobile_tower,tower_area,tower_installation_date,is_hoarding_board,hoarding_area,hoarding_installation_date,
        is_petrol_pump,under_ground_area,petrol_pump_completion_date,is_water_harvesting,NULL,'" . $input['emp_details_id'] . "','" . $input['created_on'] . "',
        status,prop_state,corr_state,zone_mstr_id,'" . $input['old_ward_mstr_id'] . "','" . $input['old_holding_no'] . "' from tbl_saf_dtl where id='" . $input['saf_dtl_id'] . "'";
        $this->db->query($sql_prop);
        //echo $this->db->getLastQuery();
        $prop_dtl_id = $this->db->insertID();
        return $prop_dtl_id;
    }

    public function getPropIdByHodingNoEntryType($input)
    {
        try {
            return $this->db->table($this->table)
                ->select('*')
                ->where('coalesce(new_holding_no, holding_no)', $input['holding_no'])
                ->where('status', 1)
                ->get()
                ->getFirstRow("array");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getPropIdHodingNoWardByMD5ID($input)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select("*, (CASE WHEN new_holding_no='' THEN holding_no ELSE new_holding_no END) as holding_no")
                ->where('md5(id::text)', $input['id'])
                ->where('status', 1)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getPropIdByWardNoHodingNo($input)
    {
        try {
            $sql = "SELECT
                        tbl_prop_dtl.id,
                        tbl_prop_dtl.new_holding_no,
                        tbl_prop_dtl.prop_address,
                        tbl_prop_dtl.prop_type_mstr_id,
                        tbl_prop_owner_detail.owner_name,
                        tbl_prop_owner_detail.guardian_name,
                        tbl_prop_dtl.saf_dtl_id,
                        tbl_saf_dtl.saf_no,
                        tbl_saf_dtl.saf_pending_status
                    FROM tbl_prop_dtl
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                    LEFT JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id and tbl_saf_dtl.saf_pending_status=0
                    INNER JOIN (SELECT
                            prop_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(guardian_name, ',') AS guardian_name
                        FROM tbl_prop_owner_detail
                        WHERE status=1 GROUP BY prop_dtl_id
                    ) AS tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status =1 
                        and tbl_prop_dtl.ward_mstr_id='" . $input['ward_mstr_id'] . "'
                        AND (tbl_prop_dtl.holding_no ILIKE '" . $input['holding_no'] . "'
						OR tbl_prop_dtl.new_holding_no ILIKE '" . $input['holding_no'] . "');
                    ";
            $result = $this->db->query($sql);
            return $result->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getPropdetails($prop_dtl_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('holding_no')
                ->where('status', 1)
                ->where('id', $prop_dtl_id)
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder["holding_no"];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getWardMstrId($prop_dtl_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('ward_mstr_id')
                ->where('status', 1)
                ->where('id', $prop_dtl_id)
                ->get();
            // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder["ward_mstr_id"];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function updatePropertyByEO($saf_dtl_id, $input)
    {
        $builder = $this->db->table($this->table)
            ->where('saf_dtl_id', $saf_dtl_id)
            ->update($input);
        //echo $this->db->getLastQuery();
        return $builder;
    }

    public function updateforsubholdingbypropid($prop_dtl_id, $for_sub_holding_no)
    {
        return $this->db->table($this->table)
            ->where('id', $prop_dtl_id)
            ->update(
                [
                    'for_sub_holding' => $for_sub_holding_no
                ]
            );
        //echo $this->db->getLastQuery();
    }
    public function getHoldingWardMstrId($from_date, $to_date)
    {
        try {
            $builder = $this->table($this->table)
                ->select('id,holding_no,ward_mstr_id')
                ->where('status', 1)
                ->where('date(created_on) >=', $from_date)
                ->where('date(created_on) <=', $to_date)
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDataByWardMstrId($ward_mstr_id, $from_date, $to_date)
    {
        try {
            $builder = $this->table($this->table)
                ->select('id, holding_no, ward_mstr_id')
                ->where('ward_mstr_id', $ward_mstr_id)
                ->where('date(created_on) >=', $from_date)
                ->where('date(created_on) <=', $to_date)
                ->where('status', 1)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getPropDtlIdBySafDtlId($input)
    {
        try {
            $builder = $this->table($this->table)
                ->select('id, holding_no, occupation_date AS land_occupation_date')
                ->where('saf_dtl_id', $input['saf_dtl_id'])
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updatesaf_hold_statusbypropid($prop_dtl_id)
    {
        return $this->db->table($this->table)
            ->where('id', $prop_dtl_id)
            ->update(
                [
                    'saf_hold_status' => 0
                ]
            );
        //echo $this->db->getLastQuery();
    }
    public function updateentry_type_statusbypropid($prop_dtl_id)
    {
        $entry_type = 'Legacy Saf';
        return $this->db->table($this->table)
            ->where('id', $prop_dtl_id)
            ->update(
                [
                    'entry_type' => $entry_type
                ]
            );
        //echo $this->db->getLastQuery();
    }
    public function propertyDetails($holding_no)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('holding_no', $holding_no)
                ->where('status', 1)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function count_ward_by_wardid($ward_mstr_id)
    {
        try {
            return $this->db->table($this->table)
                ->select('count(id) as ward_cnt')
                ->where('ward_mstr_id', $ward_mstr_id)
                ->get()
                ->getFirstRow("array");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function updatePropDtlStatus($prop_dtl_id, $status)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $prop_dtl_id)
                ->update([
                    "status" => $status
                ]);

            //echo $this->db->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function prop_hold($data)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $data)
                ->update([
                    "saf_hold_status" => 1
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDeactivateHoldingNo($prop_dtl_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('holding_no,ward_mstr_id')
                ->where('id', $prop_dtl_id)
                ->where('status', 0)
                ->get();
            // echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function prop_basic_details($data)
    {
        try {
            $builder = $this->db->table("view_prop_dtl_owner_ward_prop_type_ownership_type")
                ->select('*')
                ->where('md5(prop_dtl_id::text)', $data['id'])
                ->get();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function propertyDetailsHolding($holding_no)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(holding_no::text)', $holding_no)
                ->where('status', 1)
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function PropDetails()
    {
        $sql = "SELECT *
		FROM view_prop_dtl_owner_ward_prop_type_ownership_type LIMIT 5
		";
        $ql = $this->db->query($sql);
        $result = $ql->getResultArray();
        return $result;
    }
    public function insertData($input)
    {
        $builder = $this->db->table($this->table)
            ->insert($input);
        //echo $this->getLastQuery();
        return $this->db->insertID();
    }
    public function getDataBySafDtlId($prop_dtl_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('holding_no')
                ->where('status', 1)
                ->where('id', $prop_dtl_id)
                ->get();
            return $builder->getFirstRow("array");
            //echo $this->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function paidsts($input)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $input)
                ->update([
                    "payment_status" => 1
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function legacy()
    {
        $sql = "SELECT coalesce(count(entry_type), 0) as total_legacy_id
			    FROM tbl_prop_dtl
			    WHERE status=1 AND entry_type='Legacy'";
        $ql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $ql->getResultArray()[0];
        return $result;
    }
    public function getPropDetailsByPropId($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id', $id)
                ->where('status', 1)
                ->get();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getHoldingNoBySafDtlId($input)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('holding_no')
                ->where('saf_dtl_id', $input['saf_dtl_id'])
                ->where('status', 1)
                ->get();
            return $builder->getFirstRow("array");
            //echo $this->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getHoldingDtlBySafDtlId($input)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('saf_dtl_id', $input['saf_dtl_id'])
                ->get();
            return $builder->getFirstRow("array");
            //echo $this->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    public function getwardhousehold($ward_id)
    {
        $sql = "select count(holding_no) as no_of_houseHold from tbl_prop_dtl where ward_mstr_id=" . $ward_id . " and status=1";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }

    public function gethouseholdNo()
    {
        $sql = "select count(holding_no) as no_of_houseHold from tbl_prop_dtl where status=1";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }

    public function getRecords($whereQuery)
    {
        $sql = "SELECT view_ward_mstr.ward_no,prop_demand.total_demand,prop_collection.total_collection,
			 online_transaction.online_collection, (prop_collection.total_collection-online_transaction.online_collection) AS other_than_online
			FROM tbl_prop_dtl
			INNER JOIN (SELECT ward_mstr_id, SUM(amount) AS total_demand FROM tbl_prop_demand WHERE status=1 GROUP BY ward_mstr_id) AS prop_demand ON prop_demand.ward_mstr_id=tbl_prop_dtl.ward_mstr_id
			INNER JOIN (SELECT ward_mstr_id, SUM(amount) AS total_collection FROM tbl_collection WHERE status=1 GROUP BY ward_mstr_id) AS prop_collection ON prop_collection.ward_mstr_id=tbl_prop_dtl.ward_mstr_id
			INNER JOIN (SELECT ward_mstr_id, SUM(payable_amt) AS online_collection FROM tbl_transaction WHERE status=1 and tran_mode_mstr_id=4 GROUP BY ward_mstr_id) AS online_transaction ON online_transaction.ward_mstr_id=tbl_prop_dtl.ward_mstr_id
			INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
			WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1" . $whereQuery;
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function get_prop_full_details($prop_dtl_id_md5)
    {
        // print_r($this->db->getDatabase());
        // return;
        // echo $prop_dtl_id_md5;
        try {
            if (is_numeric($prop_dtl_id_md5)) {
                $sql = "select * from get_prop_full_details(".$prop_dtl_id_md5.");";
            } else {
                $sql = "select * from get_prop_full_details('$prop_dtl_id_md5');";    
            }
            $query = $this->db->query($sql);
            return $query->getFirstRow('array');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function get_prop_full_details_upto_floor($prop_dtl_id_md5)
    {
        try {
            if (is_numeric($prop_dtl_id_md5)) {
                $sql = "select * from get_prop_full_details_upto_floor(".$prop_dtl_id_md5.");";
            } else {
                $sql = "select * from get_prop_full_details_upto_floor('$prop_dtl_id_md5');";    
            }
            $query = $this->db->query($sql);
            return $query->getFirstRow('array');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function get_prop_full_details_int($prop_dtl_id)
    {
        // print_r($this->db->getDatabase());
        // return;
        // echo $prop_dtl_id_md5;
        try {
              $sql = "select * from get_prop_full_details(".(int)$prop_dtl_id.");";
            // die;
            $query = $this->db->query($sql);
            // die;
            // print_r($query->getFirstRow('array'));
            // return;
            return $query->getFirstRow('array');
            //echo $this->db->getLastQuery();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getPropDtlByMD5PropDtlId($prop_dtl_id)
    {
        try {
            $sql = "SELECT
                        tbl_prop_dtl.id AS prop_dtl_id,
                        tbl_prop_dtl.new_holding_no,
                        tbl_prop_dtl.holding_no,
                        tbl_prop_dtl.ward_mstr_id,
                        view_ward_mstr.ward_no,
                        tbl_prop_dtl.ownership_type_mstr_id,
                        tbl_ownership_type_mstr.ownership_type,
                        tbl_prop_dtl.prop_type_mstr_id,
                        tbl_prop_type_mstr.property_type,
                        tbl_prop_dtl.appartment_name,
                        tbl_apartment_details.apt_code,
                        tbl_prop_dtl.flat_registry_date,
                        tbl_prop_dtl.zone_mstr_id,
                        tbl_prop_dtl.no_electric_connection,
                        tbl_prop_dtl.elect_consumer_no,
                        tbl_prop_dtl.elect_acc_no,
                        tbl_prop_dtl.elect_bind_book_no,
                        tbl_prop_dtl.elect_cons_category,
                        tbl_prop_dtl.building_plan_approval_no,
                        tbl_prop_dtl.building_plan_approval_date,
                        tbl_prop_dtl.water_conn_no,
                        tbl_prop_dtl.water_conn_date,
                        tbl_prop_dtl.khata_no,
                        tbl_prop_dtl.plot_no,
                        tbl_prop_dtl.village_mauja_name,
                        tbl_prop_dtl.road_type_mstr_id,
                        tbl_road_type_mstr.road_type,
                        tbl_prop_dtl.area_of_plot,
                        tbl_prop_dtl.prop_address,
                        tbl_prop_dtl.prop_city,
                        tbl_prop_dtl.prop_dist,
                        tbl_prop_dtl.prop_pin_code,
                        tbl_prop_dtl.corr_address,
                        tbl_prop_dtl.corr_city,
                        tbl_prop_dtl.corr_dist,
                        tbl_prop_dtl.corr_pin_code,
                        tbl_prop_dtl.is_mobile_tower,
                        tbl_prop_dtl.tower_area,
                        tbl_prop_dtl.tower_installation_date,
                        tbl_prop_dtl.is_hoarding_board,
                        tbl_prop_dtl.hoarding_area,
                        tbl_prop_dtl.hoarding_installation_date,
                        tbl_prop_dtl.is_petrol_pump,
                        tbl_prop_dtl.under_ground_area,
                        tbl_prop_dtl.petrol_pump_completion_date,
                        tbl_prop_dtl.is_water_harvesting,
                        tbl_prop_dtl.emp_details_id,
                        tbl_prop_dtl.created_on,
                        tbl_prop_dtl.status,
                        tbl_prop_dtl.assessment_type,
                        tbl_prop_dtl.saf_dtl_id,
                        tbl_prop_dtl.prop_state,
                        tbl_prop_dtl.corr_state,
                        tbl_prop_dtl.holding_type,
                        tbl_prop_dtl.entry_type,
                        v2.ward_no as new_ward_no

                    FROM tbl_prop_dtl
                    LEFT JOIN tbl_apartment_details on tbl_prop_dtl.apartment_details_id=tbl_apartment_details.id
                    LEFT JOIN view_ward_mstr ON tbl_prop_dtl.ward_mstr_id = view_ward_mstr.id
                    LEFT JOIN view_ward_mstr as v2 ON tbl_prop_dtl.new_ward_mstr_id = v2.id
                    LEFT JOIN tbl_ownership_type_mstr ON tbl_prop_dtl.ownership_type_mstr_id = tbl_ownership_type_mstr.id
                    LEFT JOIN tbl_prop_type_mstr ON tbl_prop_dtl.prop_type_mstr_id = tbl_prop_type_mstr.id
                    LEFT JOIN tbl_road_type_mstr ON tbl_prop_dtl.road_type_mstr_id = tbl_road_type_mstr.id

                    WHERE md5(tbl_prop_dtl.id::text)='" . $prop_dtl_id . "'";
            //print_var($sql);
            $builder = $this->db->query($sql);
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    public function prop_details($data)
    {
        $builder = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type
		where holding_no='" . $data . "'";
        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }
    public function pro_det($data)
    {
        $builder = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type
		where md5(prop_dtl_id::TEXT)='" . $data . "'";
        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray()[0];
    }


    public function propdtl_deactive($data)
    {
        return $this->db->table($this->table)
            ->where('id', $data)
            ->update([
                'status' => 0
            ]);
    }

    public function propdtl_active($data)
    {
        return $this->db->table($this->table)
            ->where('id', $data)
            ->update([
                'status' => 1
            ]);
    }


    public function pro_detail_update($data)
    {
        return $this->db->table($this->table)
            ->where('id', $data['prop_dtl_id'])
            ->update([
                'ward_mstr_id' => $data['ward_mstr_id'],
                'new_ward_mstr_id' => $data['new_ward_mstr_id'],
                'khata_no' => $data['khata_no'],
                'plot_no' => $data['plot_no'],
                'village_mauja_name' => $data['mauja_name'],
                'prop_address' => $data['prop_address'],
            ]);
        //echo $this->db->getLastQuery();
    }

    public function getPropDtlIdAddressDtlByHoldingWardNo($input)
    {
        try {
            $builder = "SELECT id AS prop_dtl_id, prop_address FROM tbl_prop_dtl WHERE holding_no ILIKE ('" . $input['holding_no'] . "') AND ward_mstr_id=" . $input['ward_mstr_id'] . " AND status=1";
            $builder = $this->query($builder);
            //$query = $this->db->getLastQuery();
            return $builder = $builder->getFirstRow('array');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    public function update_add_prop_dtl($prop_dtl_id)
    {
        return $this->db->table($this->table)
            ->where('id', $prop_dtl_id)
            ->update(
                [
                    'is_water_harvesting' => 'false'
                ]
            );
        //echo $this->db->getLastQuery();
    }
    public function update_remove_prop_dtl($prop_dtl_id)
    {
        return $this->db->table($this->table)
            ->where('id', $prop_dtl_id)
            ->update(
                [
                    'is_water_harvesting' => 'true'
                ]
            );
        //echo $this->db->getLastQuery();
    }

    public function propSearchUsingWardHoldingNoOwnerMobile($input)
    {
        $WHERE = "";
        if ($input['holding_no'] != "") {
            $WHERE .= "tbl_prop_dtl.holding_no ILIKE '" . $input['holding_no'] . "' OR tbl_prop_dtl.new_holding_no ILIKE '" . $input['holding_no'] . "'";
        } else {
            if ($input['owner_name'] != "") {
                $WHERE .= "tbl_prop_owner_detail.owner_name ILIKE '%" . $input['owner_name'] . "%'";
            }
            if ($input['mobile_no'] != "") {
                if ($WHERE != "") {
                    $WHERE .= " OR ";
                }
                $WHERE .= "tbl_prop_owner_detail.mobile_no ILIKE '%" . $input['mobile_no'] . "%'";
            }
        }
        $sql = "SELECT
            tbl_prop_dtl.id,
            view_ward_mstr.ward_no,
            tbl_prop_dtl.holding_no,
            tbl_prop_dtl.new_holding_no,
            tbl_prop_owner_detail.owner_name,
            tbl_prop_owner_detail.mobile_no
        FROM tbl_prop_dtl
        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
        INNER JOIN (
            SELECT
                prop_dtl_id,
                STRING_AGG(owner_name::TEXT, ',')owner_name,
                STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
            FROM tbl_prop_owner_detail WHERE status=1 GROUP BY prop_dtl_id
        ) AS tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
        WHERE
            tbl_prop_dtl.ward_mstr_id='" . $input['ward_mstr_id'] . "'
            AND (" . $WHERE . ")";
        $builder = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }

    public function getOwnerDetailById($prop_dtl_id)
    {
        try {
             $sql = "select * from tbl_prop_owner_detail where md5(prop_dtl_id::text)='".$prop_dtl_id."' and status=1";
            //echo $this->db->getLastQuery();
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getOwnerDetailByOwnerId($ownerId)
    {
        try {
             $sql = "select * from tbl_prop_owner_detail where id='".$ownerId."' and status=1";
            //echo $this->db->getLastQuery();
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function insertSpecialDoc($input)
    {

        try {
               $sql = "INSERT INTO tbl_prop_doc_special_dtl(
                 prop_dtl_id,
                 prop_owner_details_id,
                 doc_mstr_id,
                 other_doc,
                 uploaded_emp_dtl_id,
                 created_on,
                 status,
                 verify_status,
                 gender,
                 dob,
                 is_specially_abled,
                 is_armed_force
                 ) VALUES (
                     ".$input['prop_dtl_id'].",
                     ".$input['prop_owner_details_id'].",
                     ".$input['doc_mstr_id'].",
                     '".$input['other_doc']."',
                     ".$input['uploaded_emp_dtl_id'].",
                     ".$input['created_on'].",
                     ".$input['status'].",
                     ".$input['verify_status'].",
                     '".$input['gender']."',
                     '".$input['dob']."',
                     '".$input['is_specially_abled']."',
                     '".$input['is_armed_force']."')
                 ";
            $this->db->query($sql);
            // die;
            // print_var($this->db->insertID());
            // die;
            return $this->db->insertID();
        } catch (Exception $e) {
            print_var("error at insert".$e->getMessage());
            return $e->getMessage();
        }
    }
    public function insertLevelDocVerify($input)
    {
        try {
             $sql = "INSERT INTO tbl_level_doc_verify_dtl(
                 prop_dtl_id,
                 sender_user_type_id,
                 receiver_user_type_id,
                 forward_date_time,
                 created_on,
                 sender_emp_details_id,
                 status
                 ) VALUES (
                     ".$input['prop_dtl_id'].",
                     ".$input['sender_user_type_id'].",
                     ".$input['receiver_user_type_id'].",
                     ".$input['forward_date_time'].",
                     ".$input['created_on'].",
                     ".$input['sender_emp_details_id'].",
                     ".$input['status'].")

                 ";
            $this->db->query($sql);
            return $this->db->insertID();
        } catch (Exception $e) {
            print_var($e->getMessage());
            return $e->getMessage();
        }
    }
    public function updatePropDocDtl($input)
    {
        try {
             $sql = "UPDATE tbl_prop_doc_special_dtl SET level_doc_verify_dtl_id=".$input['level_doc_verify_dtl_id'].",doc_path='".$input['doc_path']."' where id=".$input['prop_special_doc_tbl_id']."";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            print_var($e->getMessage());
            return $e->getMessage();
        }
    }
    public function uploadConsessionDocViaBackOffice($input)
    {
        try {
            echo $sql = "UPDATE tbl_prop_doc_special_dtl SET doc_path='".$input['doc_path']."' where id=".$input['prop_special_doc_tbl_id']."";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            print_var($e->getMessage());
            return $e->getMessage();
        }
    }
    public function getIdFromMd5Id($prop_dtl_id)
    {
        try {
              $sql = "SELECT id FROM tbl_prop_dtl where md5(id::text)='".$prop_dtl_id."'";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getSpecialDocDataByOwnerId($prop_doc_id)
    {
        try {
              $sql = "SELECT * from  tbl_prop_doc_special_dtl where id=".$prop_doc_id." and status=1";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getSpecialDocDataPropDtlId($prop_dtl_id)
    {
        try {
            //   $sql = "SELECT * from  tbl_prop_doc_special_dtl where prop_dtl_id=".$prop_dtl_id." and status=1";
              $sql = "SELECT tbl_prop_doc_special_dtl.*,tbl_prop_owner_detail.owner_name as owner_name from  tbl_prop_doc_special_dtl inner join tbl_prop_owner_detail on tbl_prop_doc_special_dtl.prop_owner_details_id=tbl_prop_owner_detail.id where tbl_prop_doc_special_dtl.prop_dtl_id=".$prop_dtl_id." and tbl_prop_doc_special_dtl.status=1 and tbl_prop_doc_special_dtl.verify_status=0 and tbl_prop_doc_special_dtl.doc_path!=''";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getSpecialDocOnwerGroupedDataPropDtlId($prop_dtl_id)
    {
        try {
            //   $sql = "SELECT * from  tbl_prop_doc_special_dtl where prop_dtl_id=".$prop_dtl_id." and status=1";
                $sql = "SELECT tbl_prop_doc_special_dtl.prop_owner_details_id,STRING_AGG(tbl_prop_doc_special_dtl.doc_path,',') as doc_path,STRING_AGG(tbl_prop_doc_special_dtl.other_doc,',') as other_doc,tbl_prop_owner_detail.owner_name as owner_name,
                tbl_prop_owner_detail.guardian_name as guardian_name,
                tbl_prop_owner_detail.relation_type as relation_type,
                tbl_prop_owner_detail.mobile_no as mobile_no,
                tbl_prop_owner_detail.email as email,
                tbl_prop_owner_detail.aadhar_no as aadhar_no,
                tbl_prop_owner_detail.pan_no as pan_no
                 from  tbl_prop_doc_special_dtl inner join tbl_prop_owner_detail on tbl_prop_doc_special_dtl.prop_owner_details_id=tbl_prop_owner_detail.id where tbl_prop_doc_special_dtl.prop_dtl_id=".$prop_dtl_id." and tbl_prop_doc_special_dtl.status=1 and tbl_prop_doc_special_dtl.verify_status=0 group by tbl_prop_doc_special_dtl.prop_owner_details_id,tbl_prop_owner_detail.owner_name,
                 tbl_prop_owner_detail.guardian_name,
                 tbl_prop_owner_detail.relation_type,
                 tbl_prop_owner_detail.mobile_no,
                 tbl_prop_owner_detail.email,
                 tbl_prop_owner_detail.aadhar_no,
                 tbl_prop_owner_detail.pan_no";
            //   die;
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function getHoldingPdfDetails($prop_dtl_id)
    {
        try {
              $sql = "SELECT * from  tbl_prop_doc_special_dtl where id=".$prop_doc_id." and verify_status=0 and status=1";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function verifySpecialDocument($input)
    {
        try {
             $sql = "UPDATE tbl_prop_doc_special_dtl SET verify_status=".$input['verify_status'].",verified_by_emp_details_id='".$input['verified_by_emp_details_id']."',verified_on='".$input['verified_on']."',remarks='".$input['remarks']."' where id=".$input['prop_doc_id']."";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            print_var($e->getMessage());
            return $e->getMessage();
        }
    }
    public function getSpecialDocData($prop_owner_id)
    {
        try {
            //  $sql = "SELECT * from tbl_prop_doc_special_dtl where prop_owner_details_id=".$prop_owner_id." and status=1 order by id desc limit 1;";
             $sql = "SELECT * from tbl_prop_doc_special_dtl where prop_owner_details_id=".$prop_owner_id." and status=1 order by id desc;";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            print_var($e->getMessage());
            return $e->getMessage();
        }
    }
    public function getPropDocPrevData($prev_doc_status_id)
    {
        try {

             $sql = "SELECT * from tbl_prop_doc_special_dtl where id=".$prev_doc_status_id." and status=1;";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            print_var($e->getMessage());
            return $e->getMessage();
        }
    }
    public function updateSpecialDocStatus($prev_doc_status_id)
    {
        try {

             $sql = "UPDATE tbl_prop_doc_special_dtl set status=0 where id=".$prev_doc_status_id." and status=1;";
            $run = $this->db->query($sql);
            return $run->getResultArray();
        } catch (Exception $e) {
            print_var($e->getMessage());
            return $e->getMessage();
        }
    }
}
