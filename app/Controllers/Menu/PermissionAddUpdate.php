<?php
namespace App\Controllers\Menu;
use CodeIgniter\Controller;
use App\Models\model_menu_mstr;
use Predis\Client;
use CodeIgniter\Database\ConnectionInterface;

class PermissionAddUpdate extends Controller
{
	protected $dbSystem;
	protected $model_menu_mstr;

    public function __construct(ConnectionInterface $db) {
    	helper(['db_helper', 'form', "cookie"]);
        $this->db = $db;
		$this->model_menu_mstr = new model_menu_mstr( $this->db);
    }

	public function menuPermissionUpdate($userTypeId) {
		$user_type_mstr_id = $userTypeId;
		if (!is_null($user_type_mstr_id)) {
			$client = new \Predis\Client();
			$menuList = $client->del("menu_list_".$user_type_mstr_id);
			//if (!$menuList) {
				$menuList = $this->model_menu_mstr->getMenuMstrListByUserTypeMstrId($user_type_mstr_id);
				if ($menuList) {
					foreach ($menuList as $key => $value) {
						if ($value['parent_menu_mstr_id'] == 0) {
							$subMenuList = $this->model_menu_mstr->getMenuSubListByUserTypeMstrId($user_type_mstr_id, $value['id']);
							if ($subMenuList) {
								$menuList[$key]['sub_menu'] = $subMenuList;
								foreach ($subMenuList as $keyy => $valueSub) {
									$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $valueSub['id']);
									if ($subMenuList) {
										$menuList[$key]['sub_menu'][$keyy]['link_menu'] = $linkMenuList;
									}
								}
							}
						}
					}
					foreach ($menuList as $key => $value) {
						$linkMenuList = $this->model_menu_mstr->getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $value['id']);
						if ($linkMenuList) {
							$menuList[$key]['link_menu'] = $linkMenuList;
						}
					}
					//json_encode($menuList);
					$client->set("menu_list_".$user_type_mstr_id, json_encode($menuList));
					$menuList = $client->get("menu_list_".$user_type_mstr_id);
				}
			//}
			
		}
		return true;
	}

    
}