# UserInterfaceHook Plugin - HiddenStackQuestion

## Requirements

| Component             | Version(s)                                                                                           | Link                                               |
|-----------------------|------------------------------------------------------------------------------------------------------|----------------------------------------------------|
| PHP                   | ![](https://img.shields.io/badge/8.1-blue.svg) ![](https://img.shields.io/badge/8.2-blue.svg)        | [PHP](https://php.net)                             |
| ILIAS                 | ![](https://img.shields.io/badge/9-orange.svg) to ![](https://img.shields.io/badge/9.999-orange.svg) | [ILIAS](https://ilias.de)                          |
| StackQuestion Plugin  | Branch ![](https://img.shields.io/badge/ilias9__stack-orange.svg)                                    | [GitHub](https://github.com/surlabs/STACKForILIAS) |

---

## Table of contents

<!-- TOC -->
* [UserInterfaceHook Plugin - HiddenStackQuestion](#userinterfacehook-plugin---hiddenstackquestion)
  * [Requirements](#requirements)
  * [Table of contents](#table-of-contents)
  * [Installation](#installation)
<!-- TOC -->

---

## Installation

1. Clone this repository to **Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HiddenStackQuestion**
2. Install the Composer dependencies
   ```bash
   cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HiddenStackQuestion
   composer install --no-dev
   ```
   Developers **MUST** omit the `--no-dev` argument.
3. Run ``composer install --no-dev`` in the ilias root directory!
4. Login to ILIAS with an administrator account (e.g. root)
5. Select **Plugins** in **Extending ILIAS** inside the **Administration** main menu.
6. Search for the **HiddenStackQuestion** plugin in the list of plugin and choose **Install** from the **Actions**
   drop-down.
7. Choose **Activate** from the **Actions** dropdown.
