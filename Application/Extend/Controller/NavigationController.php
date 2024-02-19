<?php

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

namespace VanillaThunder\DevUtils\Application\Extend\Controller;

/**
 * Navigation Controller extension for vt-DevUtils Module.
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\NavigationController
 */
class NavigationController extends NavigationController_parent
{
    public function render()
    {
        $r = parent::render();

        return ('header.tpl' == $r) ? 'vtdev_navigation_header.tpl' : $r;
    }
}
