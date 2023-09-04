<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once 'Services/Form/classes/class.ilPropertyFormGUI.php';
require_once 'Services/Component/classes/class.ilPluginConfigGUI.php';

class ilHiddenStackQuestionConfigGUI extends ilPluginConfigGUI
{
    /**
     * @var ilHiddenStackQuestionPlugin
     */
    public $pluginObj = null;

    /**
     * @var ilPropertyFormGUI
     */
    public $form = null;

    /**
     * @param string $cmd
     */
    public function performCommand($cmd)
    {
        $this->pluginObj = ilPlugin::getPluginObject('Services', 'UIComponent', 'uihk', 'HiddenStackQuestion');
        switch ($cmd) {
            default:
                $this->$cmd();
                break;
        }
    }

    protected function configure()
    {
        $this->editSettings();
    }

    protected function editSettings()
    {
        /**
         * @var $tpl ilTemplate|ilGlobalTemplateInterface
         */
        global $tpl;

        $this->initSettingsForm();
        $this->populateValues();
        $tpl->setContent($this->form->getHTML());
    }

    protected function populateValues()
    {
        $this->form->setValuesByArray(array(
            'limit_to_groles' => $this->pluginObj->getSetting('limit_to_groles'),
            'global_roles' => explode(',', $this->pluginObj->getSetting('global_roles'))
        ));
    }

    protected function initSettingsForm()
    {
        /**
         * @var $lng    ilLanguage
         * @var $ilCtrl ilCtrl
         * @var $ilObjDataCache ilObjectDataCache
         * @var $rbacreview ilRbacReview
         */
        global $lng, $ilCtrl, $rbacreview, $ilObjDataCache;

        if ($this->form instanceof ilPropertyFormGUI) {
            return;
        }

        $this->form = new ilPropertyFormGUI();
        $this->form->setFormAction($ilCtrl->getFormAction($this, 'saveSettings'));
        $this->form->setTitle($lng->txt('settings'));
        $this->form->addCommandButton('saveSettings', $lng->txt('save'));

        $form_limit_to_groles = new ilCheckboxInputGUI($this->pluginObj->txt('limit_to_groles'), 'limit_to_groles');
        include_once 'Services/Form/classes/class.ilMultiSelectInputGUI.php';
        $sub_mlist = new ilMultiSelectInputGUI(
            $this->pluginObj->txt('global_roles'),
            'global_roles'
        );
        $roles = array();
        foreach ($rbacreview->getGlobalRoles() as $role_id) {
            if ($role_id != ANONYMOUS_ROLE_ID) {
                $roles[$role_id] = $ilObjDataCache->lookupTitle($role_id);
            }
        }
        $sub_mlist->setOptions($roles);
        $form_limit_to_groles->addSubItem($sub_mlist);

        $this->form->addItem($form_limit_to_groles);
    }

    public function saveSettings()
    {
        /**
         * @var $tpl    ilTemplate|ilGlobalTemplateInterface
         * @var $lng    ilLanguage
         * @var $ilCtrl ilCtrl
         */
        global $tpl, $lng, $ilCtrl;

        $this->initSettingsForm();

        if ($this->form->checkInput()) {
            $this->pluginObj->setSetting('limit_to_groles', (int) $this->form->getInput('limit_to_groles'));
            $this->pluginObj->setSetting('global_roles', implode(',', (array) $this->form->getInput('global_roles')));

            ilUtil::sendSuccess($lng->txt('saved_successfully'), true);
            $ilCtrl->redirect($this);
        }

        $this->form->setValuesByPost();
        $tpl->setContent($this->form->getHTML());
    }
}
