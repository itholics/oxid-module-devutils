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

namespace VanillaThunder\DevUtils\Application\Extend\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 *  Email extension for vt-DevUtils Module.
 *
 * @mixin \OxidEsales\Eshop\Core\Language
 */
class Language extends Language_parent
{
    public function getTranslationsArray($iLang, $blAdminMode)
    {
        return $this->getLangTranslationArray($iLang, $blAdminMode);
    }

    /**
     * collects all relevant language files
     * mostly copied from Language->_getLangFilesPathArray.
     *
     * @param mixed $iLang
     *
     * @return array
     */
    public function getAllLangFiles($iLang)
    {
        return $this->getLangFilesPathArray($iLang);
    }

    public function getCustomLangFiles($iLang)
    {
        $sLang = Registry::getLang()->getLanguageAbbr($iLang);

        return $this->_appendCustomLangFiles([], $sLang);
    }

    public function getFrontendCustLangFilePath($iLang)
    {
        $oConfig  = Registry::getConfig();
        $language = Registry::getLang()->getLanguageAbbr($iLang);

        return $oConfig->getAppDir() .
            'views' . DIRECTORY_SEPARATOR .
            ($oConfig->getConfigParam('sCustomTheme')
                ? $oConfig->getConfigParam('sCustomTheme')
                : $oConfig->getConfigParam('sTheme')) . DIRECTORY_SEPARATOR .
            $language . DIRECTORY_SEPARATOR .
            'cust_lang.php';
    }
}
