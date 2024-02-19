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

use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleConfigurationDaoBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\DataObject\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Module\MetaData\Dao\ModuleConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;
use PDO;
use Symfony\Component\Filesystem\Path;
use VanillaThunder\DevUtils\Application\Core\DevUtils;

class DevMetadata extends AdminDetailsController
{
    protected $_sThisTemplate = 'devutils_metadata.tpl';

    public function __construct()
    {
        parent::__construct();
    }

    public function render()
    {
        return parent::render();
    }

    public function check($check, $type)
    {
        $cfg = Registry::getConfig();

        switch ($type) {
            case 'ext':
                return (file_exists($cfg->getModulesDir(true) . $check . '.php')) ? 1 : -1;

            case 'file':
                return (file_exists($cfg->getModulesDir(true) . $check)) ? 1 : -1;

            case 'path':
                return (is_dir($cfg->getModulesDir(true) . $check)) ? 1 : -1;

            case 'block':
                $sModule = $check['OXMODULE'];
                $sFile   = $check['OXFILE'];

                $aModuleInfo = $cfg->getConfigParam('aModulePaths');
                $sModulePath = $aModuleInfo[$sModule];
                // for 4.5 modules, since 4.6 insert in oxtplblocks the full file name
                if ('.tpl' != substr($sFile, -4)) {
                    $sFile = $sFile . '.tpl';
                }
                // for < 4.6 modules, since 4.7/5.0 insert in oxtplblocks the full file name and path
                if (basename($sFile) == $sFile) {
                    $sFile = "out/blocks/{$sFile}";
                }
                $sFileName = $this->getConfig()->getConfigParam('sShopDir') . "/modules/{$sModulePath}/{$sFile}";

                return (file_exists($sFileName) && is_readable($sFileName)) ? [1, $sFileName] : [-1, $sFileName];
        }

        /*
        $query = json_decode(file_get_contents('php://input'), true);
        var_dump($query);
        exit;
        */
        return -1;
    }

    /*
       public function aModules()
       {
          $aData = [];
          foreach (Registry::getConfig()->getConfigParam("aModules") as $cl => $ext)
          {
             $items = [];
             foreach (explode('&', $ext) as $file)
             {
                $items[] = ['file' => $file, 'status' => $this->check($file, 'ext')];
             }
             $aData[] = ['label' => $cl, 'items' => $items, 'filter' => $cl . json_encode($items)];
          }
          echo json_encode($aData);
          exit;
       }*/

    public function getMetadata()
    {
        $request = oxNew(Request::class);
        $varname = $request->getRequestEscapedParameter('oxvarname');
        if (!in_array($varname, ['aModules', 'aModuleExtensions', 'aModuleControllers'])) {
            exit;
        }
        $value = \OxidEsales\EshopCommunity\Core\Registry::getConfig()->getConfigParam($varname);
    }

    public function getModules()
    {
        $aModules = Registry::getConfig()->getConfigParam('aModules');
        foreach ($aModules ?? [] as $key => $val) {
            $aModules[$key] = (strchr($val, '&') ? explode('&', $val) : [$val]);
        }

        /*
         {
        foreach ($val as $cl => $path)
        {
           $items[] = ['file' => $cl, 'path' => $path, 'status' => $this->check($path, 'file')];
        }
        $aData[] = ['label' => $key, 'items' => $items, 'filter' => $key . json_encode($items)];
        }
        */

        echo json_encode($aModules);

        exit;
    }

    public function getModuleExtensions()
    {
        $cfg               = Registry::getConfig();
        $sModulesDir       = ''; // $cfg->getModulesDir();
        $aModuleExtensions = Registry::getConfig()->getConfigParam('aModuleExtensions');
        foreach ($aModuleExtensions ?? [] as $module => $extensions) {
            $items = [];
            foreach ($extensions as $extension) {
                $status = 0;

                if (false !== strpos($extension, '\\')) {
                    /* namespace
                     * das ist dirty AF
                     * class_exists versucht scheinbar die Klasse zu instanziieren
                     * wegen dem dynamischen Mapping von "class extends class_parent" existiert class_parent natürlich nicht
                     * deswegen wirft class_exists eine "class not found" Fehlermeldung.
                     * Bekommen wir diese Fehlermeldung, dann wurde die Erweiterung gefunden, also alles gut
                     */
                    // print $extension . "<br/>";
                    // print $extension."_parent" . "<br/>";
                    // print $e->getMessage() . "<br/>";
                    // var_dump(strpos($e->getMessage(), $extension."_parent"));
                    try {
                        // $status = (class_exists($extension) ? 1 : -1);
                        // \var_dump($status);
                    } catch (Error $e) {
                        var_dump($e);
                    } catch (\Error $e) {
                        var_dump($e);
                    } catch (Exception $e) {
                        var_dump($e);
                    } catch (\Exception $e) {
                        var_dump($e);
                    } catch (Throwable $e) {
                        var_dump($e);
                    } catch (\Throwable $e) {
                        var_dump($e);
                    }
                // $status = (strpos($e->getMessage(), "_parent") > 0 ? 1 : -1); }
                } else {
                    $status = file_exists($sModulesDir . DIRECTORY_SEPARATOR . $extension . '.php') && is_readable($sModulesDir . DIRECTORY_SEPARATOR . $extension . '.php');
                }

                // die();
                $items[] = [
                    'class'  => $extension,
                    'status' => $status,

                ];
            }
            $aModuleExtensions[$module] = $items;
        }
        echo json_encode($aModuleExtensions);

        exit;
    }

    public function getModuleControllers()
    {
        $aModuleControllers = Registry::getConfig()->getConfigParam('aModuleControllers');
        foreach ($aModuleControllers ?? [] as $module => $controllers) {
            $items = [];
            foreach ($controllers as $class => $path) {
                $items[] = [
                    'class'  => $class,
                    'path'   => $path,
                    'status' => (class_exists($path) ? 1 : -1),
                ];
            }
            $aModuleControllers[$module] = $items;
        }
        echo json_encode($aModuleControllers);

        exit;
    }

    public function getModuleTemplates()
    {
        $cfg         = Registry::getConfig();
        $sModulesDir = ''; // $cfg->getModulesDir();
        $aValue      = $cfg->getConfigParam('aModuleTemplates');

        $aData = [];
        foreach ($aValue ?? [] as $key => $val) {
            $items = [];
            foreach ($val as $name => $path) {
                $modulePath = $this->getModulePath($key);
                $fullPath   = Path::join($modulePath, $path);
                $items[]    = [
                    'file'     => $name,
                    'path'     => $path,
                    'fullpath' => $fullPath,
                    'status'   => (file_exists($fullPath) && is_readable($fullPath) ? 1 : -1),
                ];
            }
            $aData[$key] = $items; // , 'filter' => $key . json_encode($items)
        }
        // var_dump($aData);
        echo json_encode($aData);

        exit;
    }

    public function getTplBlocks()
    {
        $queryBuilder = DevUtils::getQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from('oxtplblocks')
            ->where('oxshopid = :shopId')
            ->orderBy('OXMODULE, OXTEMPLATE, OXBLOCKNAME, OXPOS')
            ->setParameters([
                'shopId' => Registry::getConfig()->getShopId(),
            ]);

        $blocksData = $queryBuilder->execute();
        $blocksData = $blocksData->fetchAll(PDO::FETCH_ASSOC);

        $aTplBLocks = [];
        // erst array umbauen
        foreach ($blocksData as $row) {
            $oxmodule = (!empty($row['OXMODULE']) ? $row['OXMODULE'] : 'general');
            if (!array_key_exists($oxmodule, $aTplBLocks)) {
                $aTplBLocks[$oxmodule] = [];
            }
            $aTplBLocks[$oxmodule][] = $row;
        }

        $oConf     = Registry::getConfig();
        $oViewConf = $oConf->getActiveView()->getViewConfig();

        // dann Dateien prüfen
        foreach ($aTplBLocks as $module => $blocks) {
            foreach ($blocks as $index => $block) {
                $fullpath                                =  $oViewConf->getModulePath($module, $block['OXFILE']);
                $aTplBLocks[$module][$index]['OXACTIVE'] = intval($block['OXACTIVE']);
                $aTplBLocks[$module][$index]['fullpath'] = $fullpath;
                $aTplBLocks[$module][$index]['status']   = (file_exists($fullpath) && is_readable($fullpath) ? 1 : -1);
            }
        }

        echo json_encode($aTplBLocks);

        exit;
    }

    public function getTplBlockSorting()
    {
        $queryBuilder = DevUtils::getQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from('oxtplblocks')
            ->where('oxshopid = :shopId')
            ->orderBy('OXTEMPLATE, OXBLOCKNAME, OXPOS, OXTHEME, OXID')
            ->setParameters([
                'shopId' => Registry::getConfig()->getShopId(),
            ]);

        $blocksData = $queryBuilder->execute();
        $blocksData = $blocksData->fetchAll(PDO::FETCH_ASSOC);

        $aTplBLocks = [];
        // erst array umbauen
        foreach ($blocksData as $block) {
            if (!array_key_exists($block['OXTEMPLATE'], $aTplBLocks)) {
                $aTplBLocks[$block['OXTEMPLATE']] = [];
            }
            if (!array_key_exists($block['OXBLOCKNAME'], $aTplBLocks[$block['OXTEMPLATE']])) {
                $aTplBLocks[$block['OXTEMPLATE']][$block['OXBLOCKNAME']]  = [];
            }

            $aTplBLocks[$block['OXTEMPLATE']][$block['OXBLOCKNAME']][] = [
                'OXID'        => $block['OXID'],
                'OXACTIVE'    => intval($block['OXACTIVE']),
                'OXPOS'       => intval($block['OXPOS']),
                'OXMODULE'    => $block['OXMODULE'],
                'OXTEMPLATE'  => $block['OXTEMPLATE'],
                'OXTHEME'     => $block['OXTHEME'],
                'OXBLOCKNAME' => $block['OXBLOCKNAME'],
                'OXFILE'      => $block['OXFILE'],
            ];
        }

        // clean up
        foreach ($aTplBLocks as $tpl => $blocknames) {
            foreach ($blocknames as $block => $blocks) {
                if (count($blocks) < 2) {
                    unset($aTplBLocks[$tpl][$block]);
                }
            }
            if (empty($aTplBLocks[$tpl])) {
                unset($aTplBLocks[$tpl]);
            }
        }

        echo json_encode($aTplBLocks);

        exit;
    }

    public function getModulePaths()
    {
        $aData = [];
        foreach (Registry::getConfig()->getConfigParam('aModulePaths') as $key => $val) {
            $aData[] = [
                'name' => $key,
                // 'active' => $oVC->isModuleActive($key),
                'path'   => $val,
                'status' => $this->check($val, 'path'),
            ];
        }
        echo json_encode($aData);

        exit;
    }

    public function getModuleVersions()
    {
        $cfg = Registry::getConfig();
        echo json_encode($cfg->getConfigParam('aModuleVersions'));

        exit;
    }

    public function getModuleEvents()
    {
        $cfg = Registry::getConfig();
        echo json_encode($cfg->getConfigParam('aModuleEvents'));

        exit;
    }

    public function toggleTplBlock()
    {
        $request = oxNew(Request::class);
        $oxid    = $request->getRequestEscapedParameter('block');

        if (!$oxid) {
            exit('nope');
        }

        $queryBuilder = DevUtils::getQueryBuilder();

        $queryBuilder
            ->update('oxtplblocks')
            ->set('OXACTIVE', 'IF(OXACTIVE = 0, 1, 0)')
            ->where('oxshopid = :shopId')
            ->andWhere('OXID = :oxid')
            ->setParameters([
                'shopId' => Registry::getConfig()->getShopId(),
                'oxid'   => $oxid,
            ]);

        $blocksData = $queryBuilder->execute();

        echo $blocksData ? 'ok' : 'no';

        exit;
    }

    public function updateBlockOrder()
    {
        $aNewOrder = json_decode(file_get_contents('php://input'));
        if (empty($aNewOrder)) {
            exit('no');
        }

        foreach ($aNewOrder as $index => $oxid) {
            DevUtils::getQueryBuilder()
                ->update('oxtplblocks')
                ->set('OXPOS', ':oxpos')
                ->where('OXID = :oxid')
                ->setParameters([
                    'oxpos' => $index,
                    'oxid'  => $oxid,
                ])
                ->execute();
        }

        exit('ok');
    }

    private function getModueConfiguration(string $moduleId): ModuleConfiguration
    {
        return ContainerFactory::getInstance()->getContainer()->get(ModuleConfigurationDaoBridgeInterface::class)->get($moduleId);
    }

    private function getModulePath(string $moduleId): string
    {
        $configuration = $this->getModueConfiguration($moduleId);
        $context       = ContainerFactory::getInstance()->getContainer()->get(BasicContextInterface::class);

        return Path::join($context->getShopRootpath(), $configuration->getModuleSource());
    }
}
