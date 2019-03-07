<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once 'Services/UIComponent/classes/class.ilUIHookPluginGUI.php';

/**
 * Class ilHiddenStackQuestionUIHookGUI
 * @author Michael Jansen <mjansen@databay.de>
 * @ilCtrl_isCalledBy ilHiddenStackQuestionUIHookGUI: ilUIPluginRouterGUI
 */
class ilHiddenStackQuestionUIHookGUI extends ilUIHookPluginGUI
{
	/**
	 * @param       $a_comp
	 * @param       $a_part
	 * @param array $a_par
	 * @return array
	 * @throws InvalidArgumentException
	 */
	public function getHTML($a_comp, $a_part, $a_par = array())
	{
		if($a_part == 'template_get'
			&& isset($a_par['tpl_id']) && 
			$a_par['tpl_id'] == 'Services/Form/tpl.prop_select.html' &&
			(strpos($a_par['html'], '"sel_question_types"') !== false || strpos($a_par['html'], '"qtype"') !== false)
		)
		{
			if(!$this->plugin_object->isAssignedToRequiredRole($GLOBALS['ilUser']->getId()))
			{
				$html = $a_par['html'];

				require_once 'Modules/TestQuestionPool/classes/class.ilObjQuestionPool.php';
				$types = ilObjQuestionPool::_getQuestionTypes();

				$html = preg_replace('/<option[\s]+?value="assStackQuestion".*?>.*?<\/option>/', '', $html);
				$stackType = array_filter($types, function(array $qst) {
					return $qst['type_tag'] == 'assStackQuestion';
				});

				if(count($stackType) == 1)
				{
					$stackType = current($stackType);
					$html = preg_replace('/<option[\s]+?value="' . $stackType['question_type_id']  . '".*?>.*?<\/option>/', '', $html);
				}

				return array(
					"mode" => ilUIHookPluginGUI::REPLACE,
					"html" => $html
				);
			}
		}

		return parent::getHTML($a_comp, $a_part, $a_par);
	}
}