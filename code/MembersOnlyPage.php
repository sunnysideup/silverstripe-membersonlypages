<?php

class MembersOnlyPage extends Page {

	static $add_action = 'Members Only Page';

	static $icon = 'mysite/images/treeicons/MembersOnlyPage';

	static $default_parent = 'MembersOnlyPage';

	static $allowed_children = array("MembersOnlyPage");

	protected static $group_code = "intranet-users";
		static function set_group_code($v) {self::$group_code = $v;}
		static function get_group_code() {return self::$group_code;}

	protected static $group_name = "intranet users";
		static function set_group_name($v) {self::$group_name = $v;}
		static function get_group_name() {return self::$group_name;}

	protected static $permission_code = "INTRANET_USERS";
		static function set_permission_code($v) {self::$permission_code = $v;}
		static function get_permission_code() {return self::$permission_code;}

	static $defaults = array(
		"ProvideComments" => 1,
		"ShowInSearch" => 0
	);


	public function getCMSFields() {
		$fields = parent::getCMSFields();
		return $fields;
	}

	public function canView() {
		if ($member = Member::currentUser()) {
			if($member->isAdmin() || Permission::checkMember($member, self::$permission_code)) {
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
		$bt = defined('DB::USE_ANSI_SQL') ? "\"" : "`";
		parent::requireDefaultRecords();
		if(!$intranetGroup = DataObject::get_one("Group", "Code = '".self::get_group_code()."'")) {
			$group = new Group();
			$group->Code = self::get_group_code();
			$group->Title = self::get_group_name();
			$group->write();

			Permission::grant( $group->ID, self::get_permission_code());
			DB::alteration_message(self::get_group_name().' group created',"created");
		}
		else if(DB::query("SELECT * FROM Permission WHERE {$bt}GroupID{$bt} = '".$intranetGroup->ID."' AND {$bt}Code{$bt} LIKE '".self::get_permission_code()."'")->numRecords() == 0 ) {
			Permission::grant($intranetGroup->ID, self::get_permission_code());
			DB::alteration_message(self::get_group_name().' permissions granted',"created");
		}
	}

}

class MembersOnlyPage_Controller extends Page_Controller {

	public function init() {
		parent::init();
		Requirements::themedCSS("MembersOnlyPage");
	}


}

