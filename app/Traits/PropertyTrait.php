<?php

namespace App\Traits;

trait PropertyTrait
{
    public function getPropertyDbCon(){
        helper(['db_helper']);
		$db_name = dbConfig("property");
        return db_connect($db_name);
    }

    public function adjustHoldingValue(array $holding): array
    {
        $db = $this->getPropertyDbCon();
        $wardSql = "select * from view_ward_mstr where status = 1 order by id ";
        $ownerShipSql = "select * from tbl_ownership_type_mstr where status = 1 order by id";
        $propertySql = "select * from tbl_prop_type_mstr where status = 1 order by id";
        $roadSql = "select * from tbl_road_type_mstr where status = 1 order by id";
        $apartmentSql = "select * from tbl_apartment_details where status = 1 order by id";

        $wardList = $db->query($wardSql)->getResultArray();
        $ownerShipList = $db->query($ownerShipSql)->getResultArray();
        $propertyTypeList = $db->query($propertySql)->getResultArray();
        $roadList = $db->query($roadSql)->getResultArray();
        $apartmentList = $db->query($apartmentSql)->getResultArray();
        $zoneList = [
            ["id"=>1,"zone"=>"Zone 1"],
            ["id"=>2,"zone"=>"Zone 2"],
        ];

        $wardMap = array_column($wardList, 'ward_no', 'id');
        $ownerShipMap = array_column($ownerShipList, 'ownership_type', 'id');
        $propertyTypeMap = array_column($propertyTypeList, 'property_type', 'id');
        $roadMap = array_column($roadList, 'road_type', 'id');
        $apartmentMap = array_column($apartmentList, 'apartment_name', 'id');
        $zoneMap = array_column($zoneList, 'zone', 'id');

        $holding['ward_no'] = $wardMap[$holding['ward_mstr_id']] ?? '';
        $holding['new_ward_no'] = $wardMap[$holding['new_ward_mstr_id']] ?? '';
        $holding['ownership_type'] = $ownerShipMap[$holding['ownership_type_mstr_id']] ?? '';
        $holding['property_type'] = $propertyTypeMap[$holding['prop_type_mstr_id']] ?? '';
        $holding['road_type'] = $roadMap[$holding['road_type_mstr_id']] ?? '';
        $holding['zone'] = $zoneMap[$holding['zone_mstr_id']] ?? '';
        $holding['apartment_name'] = $apartmentMap[$holding['apartment_details_id']] ?? '';

        return $holding;
    }

    public function adjustFloor(array $floors): array
    {
        $db = $this->getPropertyDbCon();

        // Master queries
        $floorTypeSql = "SELECT * FROM tbl_floor_mstr  ORDER BY id";
        $usageTypeSql = "SELECT * FROM tbl_usage_type_mstr   ORDER BY id";
        $constructionTypeSql = "SELECT * FROM tbl_const_type_mstr  ORDER BY id";
        $occupancyTypeSql = "SELECT * FROM tbl_occupancy_type_mstr  ORDER BY id";

        // Fetch master data
        $floorTypeList = $db->query($floorTypeSql)->getResultArray();
        $usageTypeList = $db->query($usageTypeSql)->getResultArray();
        $constructionTypeList = $db->query($constructionTypeSql)->getResultArray();
        $occupancyTypeList = $db->query($occupancyTypeSql)->getResultArray();

        // Create ID-to-name maps
        $floorTypeMap = array_column($floorTypeList, 'floor_name', 'id');
        $usageTypeMap = array_column($usageTypeList, 'usage_type', 'id');
        $constructionTypeMap = array_column($constructionTypeList, 'construction_type', 'id');
        $occupancyTypeMap = array_column($occupancyTypeList, 'occupancy_name', 'id');

        // Adjust each floor record
        foreach ($floors as &$floor) {
            $floor['floor_name'] = $floorTypeMap[$floor['floor_mstr_id']] ?? '';
            $floor['usage_type'] = $usageTypeMap[$floor['usage_type_mstr_id']] ?? '';
            $floor['construction_type'] = $constructionTypeMap[$floor['const_type_mstr_id']] ?? '';
            $floor['occupancy_type'] = $occupancyTypeMap[$floor['occupancy_type_mstr_id']] ?? '';
        }

        return $floors;
    }

    public function adjustHoldingPhysicalValue(array $holding): array
    {
        $db = $this->getPropertyDbCon();
        $wardSql = "select * from view_ward_mstr where status = 1 order by id ";
        $ownerShipSql = "select * from tbl_ownership_type_mstr where status = 1 order by id";
        $propertySql = "select * from tbl_prop_type_mstr where status = 1 order by id";
        $roadSql = "select * from tbl_road_type_mstr where status = 1 order by id";
        $apartmentSql = "select * from tbl_apartment_details where status = 1 order by id";

        $wardList = $db->query($wardSql)->getResultArray();
        $ownerShipList = $db->query($ownerShipSql)->getResultArray();
        $propertyTypeList = $db->query($propertySql)->getResultArray();
        $roadList = $db->query($roadSql)->getResultArray();
        $apartmentList = $db->query($apartmentSql)->getResultArray();
        $zoneList = [
            ["id"=>1,"zone"=>"Zone 1"],
            ["id"=>2,"zone"=>"Zone 2"],
        ];

        $wardMap = array_column($wardList, 'ward_no', 'id');
        $ownerShipMap = array_column($ownerShipList, 'ownership_type', 'id');
        $propertyTypeMap = array_column($propertyTypeList, 'property_type', 'id');
        $roadMap = array_column($roadList, 'road_type', 'id');
        $apartmentMap = array_column($apartmentList, 'apartment_name', 'id');
        $zoneMap = array_column($zoneList, 'zone', 'id');

        $holding['physical_ward_no'] = $wardMap[$holding['physical_ward_mstr_id']] ?? '';
        $holding['physical_new_ward_no'] = $wardMap[$holding['physical_new_ward_mstr_id']] ?? '';
        $holding['physical_ownership_type'] = $ownerShipMap[$holding['physical_ownership_type_mstr_id']] ?? '';
        $holding['physical_property_type'] = $propertyTypeMap[$holding['physical_prop_type_mstr_id']] ?? '';
        $holding['physical_road_type'] = $roadMap[$holding['physical_road_type_mstr_id']] ?? '';
        $holding['physical_zone'] = $zoneMap[$holding['physical_zone_mstr_id']] ?? '';
        $holding['physical_apartment_name'] = $apartmentMap[$holding['physical_apartment_details_id']] ?? '';

        return $holding;
    }

    public function adjustPhysicalFloor(array $floors): array
    {
        $db = $this->getPropertyDbCon();

        // Master queries
        $floorTypeSql = "SELECT * FROM tbl_floor_mstr  ORDER BY id";
        $usageTypeSql = "SELECT * FROM tbl_usage_type_mstr   ORDER BY id";
        $constructionTypeSql = "SELECT * FROM tbl_const_type_mstr  ORDER BY id";
        $occupancyTypeSql = "SELECT * FROM tbl_occupancy_type_mstr  ORDER BY id";

        // Fetch master data
        $floorTypeList = $db->query($floorTypeSql)->getResultArray();
        $usageTypeList = $db->query($usageTypeSql)->getResultArray();
        $constructionTypeList = $db->query($constructionTypeSql)->getResultArray();
        $occupancyTypeList = $db->query($occupancyTypeSql)->getResultArray();

        // Create ID-to-name maps
        $floorTypeMap = array_column($floorTypeList, 'floor_name', 'id');
        $usageTypeMap = array_column($usageTypeList, 'usage_type', 'id');
        $constructionTypeMap = array_column($constructionTypeList, 'construction_type', 'id');
        $occupancyTypeMap = array_column($occupancyTypeList, 'occupancy_name', 'id');

        // Adjust each floor record
        foreach ($floors as &$floor) {
            $floor['physical_floor_name'] = $floorTypeMap[$floor['physical_floor_mstr_id']] ?? '';
            $floor['physical_usage_type'] = $usageTypeMap[$floor['physical_usage_type_mstr_id']] ?? '';
            $floor['physical_construction_type'] = $constructionTypeMap[$floor['physical_const_type_mstr_id']] ?? '';
            $floor['physical_occupancy_type'] = $occupancyTypeMap[$floor['physical_occupancy_type_mstr_id']] ?? '';
        }

        return $floors;
    }

    
    public function replaceHoldingByPhysicalVal(array $holding): array
    {
        $pattern = "/^physical_/";

        foreach ($holding as $key => $val) {
            if (is_array($val)) {
                // Handle arrays (both indexed and associative)
                if (array_keys($val) === range(0, count($val) - 1)) {
                    // Indexed array
                    foreach ($val as $index => $item) {
                        if (is_array($item)) {
                            $holding[$key][$index] = $this->replaceHoldingByPhysicalVal($item);
                        }
                    }
                } else {
                    // Associative array
                    $holding[$key] = $this->replaceHoldingByPhysicalVal($val);
                }
            } elseif (preg_match($pattern, $key)) {
                $actualKey = substr($key, 9); // remove "physical_" prefix
                if ($val !== null && $val !== '' && array_key_exists($actualKey, $holding)) {
                    $holding[$actualKey] = $val; // overwrite actual value
                }
            }
        }

        return $holding;
    }



    public function filterPhysicalData(array $holding): array
    {
        $surveyInputs = [];
        $pattern = "/^physical_/";

        foreach ($holding as $key => $val) {
            if (is_array($val)) {
                // Handle numerically indexed arrays (e.g., floors)
                if (array_keys($val) === range(0, count($val) - 1)) {
                    $filteredArray = [];
                    foreach ($val as $item) {
                        $filteredItem = $this->filterPhysicalData($item);
                        if (!empty($filteredItem)) {
                            $filteredArray[] = $filteredItem;
                        }
                    }
                    if (!empty($filteredArray)) {
                        $surveyInputs[$key] = $filteredArray;
                    }
                } else {
                    // Handle associative arrays
                    $nested = $this->filterPhysicalData($val);
                    if (!empty($nested)) {
                        $surveyInputs = array_merge($surveyInputs, $nested);
                    }
                }
            } elseif (preg_match($pattern, $key)) {
                $surveyInputs[$key] = $val;
            }
        }

        return $surveyInputs;
    }



}