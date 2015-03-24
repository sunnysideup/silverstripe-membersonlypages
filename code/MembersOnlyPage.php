<?php

class MembersOnlyPage extends Page {

	private static $add_action = 'Members Only Page';

	private static $icon = 'mysite/images/treeicons/MembersOnlyPage';

	private static $default_parent = 'MembersOnlyPage';

	private static $allowed_children = array("MembersOnlyPage");

	private static $group_code = "intranet-users";
		static function set_group_code($v) {self::$group_code = $v;}
		static function get_group_code() {return self::$group_code;}

	private static $group_name = "intranet users";
		static function set_group_name($v) {self::$group_name = $v;}
		static function get_group_name() {return self::$group_name;}

	private static $permission_code = "INTRANET_USERS";
		static function set_permission_code($v) {self::$permission_code = $v;}
		static function get_permission_code() {return self::$permission_code;}

	private static $defaults = array(
		"ProvideComments" => 1,
		"ShowInSearch" => 0
	);


	public function getCMSFields() {
		$fields = parent::getCMSFields();
		return $fields;
	}

	function canView($member = null) {
		if ($member = Member::currentUser()) {
			if($member->inGroup("ADMIN") || Permission::checkMember($member, self::$permission_code)) {
				return true;
			}
		}
		return false;
	}

	public function getShowInMenus() {
		return $this->canView();
	}

	public function ShowInMenus() {
		return $this->canView();
	}

	public function getShowInSearch() {
		return $this->canView();
	}

	public function ShowInSearch() {
		return $this->canView();
	}

	public function requireDefaultRecords() {
		parent::requireDefaultRecords();
		$intranetGroup = Group::get()->filter(array("Code" => $this->Config()->get("group_code")))->first();
		if($intranetGroup && $intranetGroup->exists()) {
			//do nothing
		}
		else {
			$intranetGroup = new Group();
			DB::alteration_message($this->Config()->get("group_name").' group created',"created");
		}
		if($intranetGroup) {
			$intranetGroup->Code = $this->Config()->get("group_code");
			$intranetGroup->Title = $this->Config()->get("group_name");
			$intranetGroup->write();
			Permission::grant( $intranetGroup->ID, $this->Config()->get("permission_code"));		
			if(DB::query("
				SELECT *
				FROM Permission
				WHERE \"GroupID\" = '".$intranetGroup->ID."'
					AND \"Code\" LIKE '".$this->Config()->get("permission_code")."'")->numRecords() == 0
			) {
				Permission::grant($intranetGroup->ID, $this->Config()->get("permission_code"));
				DB::alteration_message($this->Config()->get("group_name").' permissions granted',"created");
			}
		}
	}

}

class MembersOnlyPage_Controller extends Page_Controller {

	public function init() {
		parent::init();
		Requirements::themedCSS("MembersOnlyPage", "membersonlypages");
	}


}

