<?php 
class model_sub_item
{
    private $db;
    private $tbl_name = "codetest";
    public function __construct()
    {
        $this->db = new Database();

    }
    public function add_update($data)
    {
        $result = $this->db->table($this->tbl_name)->
            insert([
                "name"=>$data["name"],
                "email"=>$data["email"],
                "pass"=>$data["pass"],
                "mobile"=>$data["mobile"],
                "course"=>$data["course"]
            ]);
        return $result;
    }
    
}


?>