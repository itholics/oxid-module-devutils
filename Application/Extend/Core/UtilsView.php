<?php

namespace VanillaThunder\DevUtils\Application\Extend\Core;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererBridgeInterface;
use VanillaThunder\DevUtils\Application\Core\DevSmarty;

class UtilsView extends UtilsView_parent
{
    private static ?\Smarty $_oSmarty = null;
    /**
     * returns existing or creates smarty object
     * Returns smarty object. If object not yet initiated - creates it. Sets such
     * default parameters, like cache lifetime, cache/templates directory, etc.
     *
     * @param bool $blReload set true to force smarty reload
     *
     * @return \Smarty
     */
    public function getSmarty($blReload = false)
    {
        if (!self::$_oSmarty || $blReload) {
            self::$_oSmarty = ContainerFactory::getInstance()->getContainer()->get(TemplateRendererBridgeInterface::class)->getEngine();
        }

        return self::$_oSmarty;
    }
}
