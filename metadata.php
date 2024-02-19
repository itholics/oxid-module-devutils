<?php

use OxidEsales\Eshop\Application\Controller\Admin\NavigationController;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\UtilsView;
use VanillaThunder\DevUtils\Application\Controller\Admin\DevChildTpl;
use VanillaThunder\DevUtils\Application\Controller\Admin\DevConfigViewer;
use VanillaThunder\DevUtils\Application\Controller\Admin\DevGui;
use VanillaThunder\DevUtils\Application\Controller\Admin\DevLogs;
use VanillaThunder\DevUtils\Application\Controller\Admin\DevMetadata;
use VanillaThunder\DevUtils\Application\Controller\Admin\DevModuleMetadata;
use VanillaThunder\DevUtils\Application\Controller\Admin\DevTranslations;
use VanillaThunder\DevUtils\Application\Controller\DevMails;

/*
 * vanilla-thunder/oxid-module-devutils
 * developent utilities for OXID eShop V6.2 and newer
 *
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 *  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
 */

$sMetadataVersion = '2.1';
$aModule          = [
    'id'          => 'ith_moduleinternals',
    'title'       => "<div style=\"display:flex; align-items: center;\"><img src=\"../out/modules/ith_european_vat/thumb.png\" alt=\"ith\" title=\"ITholics\" style=\"height: 15px; margin-right: 5px;\" /> <span><strong>IT</strong>holics - Module Internals</span></div>",
    'description' => 'Dev Utils for OXID v7',
    'thumbnail'   => 'logo.png',
    'version'     => '1.0.0',
    'author'      => 'ITholics GmbH',
    'email'       => 'info@itholics.de',
    'url'         => 'https://github.com/itholics/oxid-module-devutils',
    'extend'      => [
        NavigationController::class => VanillaThunder\DevUtils\Application\Extend\Controller\NavigationController::class,
        Order::class                => VanillaThunder\DevUtils\Application\Extend\Model\Order::class,
        DbMetaDataHandler::class    => VanillaThunder\DevUtils\Application\Extend\Core\DbMetaDataHandler::class,
        Email::class                => VanillaThunder\DevUtils\Application\Extend\Core\Email::class,
        Language::class             => VanillaThunder\DevUtils\Application\Extend\Core\Language::class,
        UtilsView::class            => VanillaThunder\DevUtils\Application\Extend\Core\UtilsView::class,
    ],
    'controllers' => [
        'devchildtpl'       => DevChildTpl::class,
        'devconfigviewer'   => DevConfigViewer::class,
        'devgui'            => DevGui::class,
        'devlogs'           => DevLogs::class,
        'devmetadata'       => DevMetadata::class,
        'devmodulemetadata' => DevModuleMetadata::class,
        'devtranslations'   => DevTranslations::class,
        'devmails'          => DevMails::class,
    ],
    'templates' => [
        'vtdev_module_main.tpl'       => 'views/admin_smarty/vtdev_module_main.tpl',
        'vtdev_navigation_header.tpl' => 'views/admin_smarty/vtdev_navigation_header.tpl',

        'devutils__header.tpl' => 'views/admin_smarty/devutils__header.tpl',
        'devutils__footer.tpl' => 'views/admin_smarty/devutils__footer.tpl',

        // admin templates
        'devutils_chiltpl.tpl'        => 'views/admin_smarty/devutils_chiltpl.tpl',
        'devutils_configviewer.tpl'   => 'views/admin_smarty/devutils_configviewer.tpl',
        'devutils_gui.tpl'            => 'views/admin_smarty/devutils_gui.tpl',
        'devutils_logs.tpl'           => 'views/admin_smarty/devutils_logs.tpl',
        'devutils_metadata.tpl'       => 'views/admin_smarty/devutils_metadata.tpl',
        'devutils_modulemetadata.tpl' => 'views/admin_smarty/devutils_modulemetadata.tpl',
        'devutils_translations.tpl'   => 'views/admin_smarty/devutils_translations.tpl',

        // frontend templates
        'devutils_mails.tpl' => 'views/devutils_mails.tpl',

    ],
    'blocks'   => [],
    'settings' => [],
];
