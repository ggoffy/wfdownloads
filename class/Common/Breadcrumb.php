<?php

namespace XoopsModules\Wfdownloads\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Breadcrumb Class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Wfdownloads
 *
 * Example:
 * $breadcrumb = new Common\Breadcrumb();
 * $breadcrumb->addLink( 'bread 1', 'index1.php' );
 * $breadcrumb->addLink( 'bread 2', '' );
 * $breadcrumb->addLink( 'bread 3', 'index3.php' );
 * echo $breadcrumb->render();
 */

use XoopsModules\Wfdownloads\{
    Common,
    Helper,
    Utility
};
/** @var Helper $helper */
/** @var Utility $utility */

require_once dirname(__DIR__, 2) . '/include/common.php';

/**
 * Class Breadcrumb
 */
class Breadcrumb
{
    public $helper;
    private $dirname;
    private $bread = [];

    public function __construct()
    {
        $this->helper  = Helper::getInstance();
        $this->dirname = \basename(dirname(__DIR__, 2));
    }

    /**
     * Add link to breadcrumb
     *
     * @param string $title
     * @param string $link
     */
    public function addLink($title = '', $link = '')
    {
        $this->bread[] = [
            'link'  => $link,
            'title' => $title,
        ];
    }

    /**
     * Render BreadCrumb
     */
    public function render()
    {
        $ret = '';

        if (!isset($GLOBALS['xoTheme']) || !\is_object($GLOBALS['xoTheme'])) {
            require_once $GLOBALS['xoops']->path('/class/theme.php');
            $GLOBALS['xoTheme'] = new \xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('/class/template.php');
        $breadcrumbTpl = new \XoopsTpl();
        $breadcrumbTpl->assign('breadcrumb', $this->bread);
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        $ret .= $breadcrumbTpl->fetch("db:{$this->helper->getDirname()}_co_breadcrumb.tpl");
        unset($breadcrumbTpl);

        return $ret;
    }
}
