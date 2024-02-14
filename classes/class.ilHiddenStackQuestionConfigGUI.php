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
 * @ilCtrl_isCalledBy ilHiddenStackQuestionConfigGUI: ilObjComponentSettingsGUI
 */
class ilHiddenStackQuestionConfigGUI extends ilPluginConfigGUI
{
    public ?ilHiddenStackQuestionPlugin $pluginObj = null;
    public ?ilPropertyFormGUI $form = null;

    public function performCommand(string $cmd): void
    {
        $this->pluginObj = ilHiddenStackQuestionPlugin::getInstance();
        switch ($cmd) {
            default:
                $this->$cmd();
                break;
        }
    }

    protected function configure(): void
    {
        $this->editSettings();
    }

    protected function editSettings(): void
    {
        global $DIC;

        $tpl = $DIC->ui()->mainTemplate();

        $this->initSettingsForm();
        $this->populateValues();
        $tpl->setContent($this->form->getHTML());
    }

    protected function populateValues(): void
    {
        $this->form->setValuesByArray(array(
            'limit_to_groles' => (bool) $this->pluginObj->getSetting('limit_to_groles'),
            'global_roles' => explode(',', $this->pluginObj->getSetting('global_roles'))
        ));
    }

    protected function initSettingsForm(): void
    {
        global $DIC;

        $lng = $DIC->language();
        $ilCtrl = $DIC->ctrl();
        $rbacreview = $DIC->rbac()->review();
        $ilObjDataCache = $DIC['ilObjDataCache'];

        if ($this->form instanceof ilPropertyFormGUI) {
            return;
        }

        $this->form = new ilPropertyFormGUI();
        $this->form->setFormAction($ilCtrl->getFormAction($this, 'saveSettings'));
        $this->form->setTitle($lng->txt('settings'));
        $this->form->addCommandButton('saveSettings', $lng->txt('save'));

        $form_limit_to_groles = new ilCheckboxInputGUI($this->pluginObj->txt('limit_to_groles'), 'limit_to_groles');
        $sub_mlist = new ilMultiSelectInputGUI(
            $this->pluginObj->txt('global_roles'),
            'global_roles'
        );
        $roles = [];
        foreach ($rbacreview->getGlobalRoles() as $role_id) {
            if ($role_id != ANONYMOUS_ROLE_ID) {
                $roles[$role_id] = $ilObjDataCache->lookupTitle($role_id);
            }
        }
        $sub_mlist->setOptions($roles);
        $form_limit_to_groles->addSubItem($sub_mlist);

        $this->form->addItem($form_limit_to_groles);
    }

    public function saveSettings(): void
    {
        global $DIC;

        $tpl = $DIC->ui()->mainTemplate();
        $lng = $DIC->language();
        $ilCtrl = $DIC->ctrl();

        $this->initSettingsForm();

        if ($this->form->checkInput()) {
            $this->pluginObj->setSetting('limit_to_groles', (string) $this->form->getInput('limit_to_groles'));
            $this->pluginObj->setSetting('global_roles', implode(',', (array) $this->form->getInput('global_roles')));

            $tpl->setOnScreenMessage('success', $lng->txt('saved_successfully'), true);
            $ilCtrl->redirect($this);
        }

        $this->form->setValuesByPost();
        $tpl->setContent($this->form->getHTML());
    }
}
