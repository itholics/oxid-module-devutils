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

namespace VanillaThunder\DevUtils\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use VanillaThunder\DevUtils\Application\Core\DevUtils;

class DevGui extends AdminController
{
    protected $_devUtils;
    protected $_sThisTemplate = 'devutils_gui.tpl';

    public function init()
    {
        parent::init();
        if (null === $this->_devUtils) {
            $this->_devUtils = new DevUtils();
        }
    }

    public function success($content, $time = false)
    {
        header('Content-Type: application/json; charset=UTF-8');
        if ($time) {
            header('Last-Modified: ' . date('r', $time));
        }
        echo json_encode($content);

        exit;
    }

    public function error($content)
    {
        header('HTTP/1.1 500 It didnt work... ');
        header('Content-Type: application/json; charset=UTF-8');

        exit(json_encode(['error' => $content]));
    }

    public function clearTmp()
    {
        exit($this->_devUtils->clearTmp());
    }

    public function clearTpl()
    {
        exit($this->_devUtils->clearTpl());
    }

    public function updateViews()
    {
        exit($this->_devUtils->updateViews());
    }

    public function keepalive()
    {
        exit('ok');
    }
}
