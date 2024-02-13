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
 * Class ilHiddenStackQuestionPlugin
 * @author Michael Jansen <mjansen@databay.de>
 */
class ilHiddenStackQuestionPlugin extends ilUserInterfaceHookPlugin
{
    public const CTYPE = 'Services';
    public const CNAME = 'UIComponent';
    public const SLOT_ID = 'uihk';
    public const PNAME = 'HiddenStackQuestion';

    private static ?self $instance = null;

    private ilSetting $settings;

    public function getPluginName(): string
    {
        return self::PNAME;
    }

    protected function init(): void
    {
        parent::init();
        $this->settings = new ilSetting('pl_hsqst');
    }

    public static function getInstance(): self
    {
        global $DIC;

        if (self::$instance instanceof self) {
            return self::$instance;
        }

        /** @var ilComponentRepository $component_repository */
        $component_repository = $DIC['component.repository'];
        /** @var ilComponentFactory $component_factory */
        $component_factory = $DIC['component.factory'];

        $plugin_info = $component_repository->getComponentByTypeAndName(
            self::CTYPE,
            self::CNAME
        )->getPluginSlotById(self::SLOT_ID)->getPluginByName(self::PNAME);

        self::$instance = $component_factory->getPlugin($plugin_info->getId());

        return self::$instance;
    }

    public function setSetting(string $keyword, string $value): void
    {
        $this->settings->set('ecr_' . $keyword, $value);
    }

    public function getSetting(string $keyword): string
    {
        return (string) $this->settings->get('ecr_' . $keyword, '');
    }

    public function isAssignedToRequiredRole(int $usr_id): bool
    {
        global $DIC;

        $rbacreview = $DIC->rbac()->review();

        $plugin = self::getInstance();

        if ($plugin->getSetting('limit_to_groles') == '') {
            return true;
        }

        $groles = explode(',', $plugin->getSetting('global_roles'));
        $groles = array_filter($groles);

        if (!$groles) {
            return true;
        }

        foreach ($groles as $role_id) {
            if ($rbacreview->isAssigned($usr_id, (int) $role_id)) {
                return true;
            }
        }

        return false;
    }

    protected function afterUninstall(): void
    {
        parent::afterUninstall();
    }
}
