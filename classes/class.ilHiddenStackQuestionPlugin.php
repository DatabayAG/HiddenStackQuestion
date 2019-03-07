<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once 'Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php';

/**
 * Class ilHiddenStackQuestionPlugin
 * @author Michael Jansen <mjansen@databay.de>
 */
class ilHiddenStackQuestionPlugin extends ilUserInterfaceHookPlugin
{
	/**
	 * @var string
	 */
	const CTYPE = 'Services';

	/**
	 * @var string
	 */
	const CNAME = 'UIComponent';

	/**
	 * @var string
	 */
	const SLOT_ID = 'uihk';

	/**
	 * @var string
	 */
	const PNAME = 'HiddenStackQuestion';

	/**+
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * @var ilSetting
	 */
	protected $settings;

	/**
	 * @return string
	 */
	public function getPluginName()
	{
		return self::PNAME;
	}

	/**
	 *
	 */
	protected function init()
	{
		parent::init();
		$this->settings = new ilSetting('pl_hsqst');
	}

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if (self::$instance instanceof self) {
			return self::$instance;
		}

		self::$instance = ilPluginAdmin::getPluginObject(
			self::CTYPE,
			self::CNAME,
			self::SLOT_ID,
			self::PNAME
		);

		return self::$instance;
	}

	/**
	 * @param string $keyword
	 * @param mixed  $value
	 */
	public function setSetting($keyword, $value)
	{
		$this->settings->set('ecr_' . $keyword, $value);
	}

	/**
	 * @param string $keyword
	 * @return mixed
	 */
	public function getSetting($keyword)
	{
		return $this->settings->get('ecr_' . $keyword, '');
	}

	/**
	 * @param $usr_id
	 * @return bool
	 */
	public function isAssignedToRequiredRole($usr_id)
	{
		/**
		 * @var $rbacreview ilRbacReview
		 */
		global $rbacreview;

		$plugin = self::getInstance();

		if (!$plugin->getSetting('limit_to_groles')) {
			return true;
		}

		$groles = explode(',', $plugin->getSetting('global_roles'));
		$groles = array_filter($groles);

		if (!$groles) {
			return true;
		}

		foreach ($groles as $role_id) {
			if ($rbacreview->isAssigned($usr_id, $role_id)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function afterUninstall()
	{
		parent::afterUninstall();
	}
}