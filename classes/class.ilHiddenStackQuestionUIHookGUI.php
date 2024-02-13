<?php


/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

/**
 * Class ilHiddenStackQuestionUIHookGUI
 * @author            Michael Jansen <mjansen@databay.de>
 * @ilCtrl_isCalledBy ilHiddenStackQuestionUIHookGUI: ilUIPluginRouterGUI
 */
class ilHiddenStackQuestionUIHookGUI extends ilUIHookPluginGUI
{
    public const STACK_QUESTION_TYPE = 'assStackQuestion';

    /**
     * @param       $a_comp
     * @param       $a_part
     * @param array $a_par
     * @return array
     * @throws InvalidArgumentException
     */
    public function getHTML(
        string $a_comp,
        string $a_part,
        array $a_par = array()
    ): array {
        if ($a_part == 'template_get'
            && isset($a_par['tpl_id']) &&
            $a_par['tpl_id'] == 'Services/Form/tpl.prop_select.html'
            && (strpos($a_par['html'], '"sel_question_types"') !== false || strpos($a_par['html'], '"qtype"') !== false)
        ) {
            if (!$this->plugin_object->isAssignedToRequiredRole($GLOBALS['ilUser']->getId())) {
                $html = $a_par['html'];

                require_once 'Modules/TestQuestionPool/classes/class.ilObjQuestionPool.php';
                $types = ilObjQuestionPool::_getQuestionTypes();

                $html = preg_replace(
                    '/<option[\s]+?value="' . self::STACK_QUESTION_TYPE . '".*?>.*?<\/option>/',
                    '',
                    $html
                );

                $stackType = array_filter($types, function (array $qst) {
                    return $qst['type_tag'] === self::STACK_QUESTION_TYPE;
                });
                if (1 === count($stackType)) {
                    $stackType = current($stackType);
                    $html = preg_replace(
                        '/<option[\s]+?value="' . $stackType['question_type_id'] . '".*?>.*?<\/option>/',
                        '',
                        $html
                    );
                }

                return [
                    'mode' => ilUIHookPluginGUI::REPLACE,
                    'html' => $html
                ];
            }
        }

        return parent::getHTML($a_comp, $a_part, $a_par);
    }
}
