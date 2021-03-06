<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Wfdownloads module
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use Xmf\Request;
use XoopsModules\Wfdownloads\{
    Common,
    Common\LetterChoice,
    Helper,
    Utility,
    DownloadHandler,
    ObjectTree
};
/** @var Helper $helper */
/** @var Utility $utility */

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_newlistindex.tpl";
require_once XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];

/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler        = xoops_getHandler('groupperm');
$catArray['imageheader'] = Utility::headerImage();
//$catArray['letters']     = Utility::lettersChoice();
$db                  = XoopsDatabaseFactory::getDatabaseConnection();
$objHandler          = new DownloadHandler($db);
$choicebyletter      = new LetterChoice($objHandler, null, null, range('a', 'z'), 'letter');
$catarray['letters'] = $choicebyletter->render();

$catArray['toolbar'] = Utility::toolbar();
$xoopsTpl->assign('catarray', $catArray);

// Breadcrumb
$breadcrumb = new Common\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);

// Get number of downloads...
$allowedCategories = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $helper->getModule()->mid());
// ... in the last week
$oneWeekAgo       = strtotime('-1 week'); //$oneWeekAgo = time() - 3600*24*7; //@TODO: Change to strtotime (TODAY-1week);
$criteria         = new Criteria('published', $oneWeekAgo, '>=');
$allWeekDownloads = $helper->getHandler('Download')->getActiveCount($criteria);
// ... in the last month
$oneMonthAgo       = strtotime('-1 month'); //$one_month_ago = time() - 3600*24*7; //@TODO: Change to strtotime (TODAY-1month);
$criteria          = new Criteria('published', $oneMonthAgo, '>=');
$allMonthDownloads = $helper->getHandler('Download')->getActiveCount($criteria);
$xoopsTpl->assign('allweekdownloads', $allWeekDownloads);
$xoopsTpl->assign('allmonthdownloads', $allMonthDownloads);

// Get latest downloads
$criteria = new CriteriaCompo(new Criteria('offline', 0));
if (Request::hasVar('newdownloadshowdays', 'GET')) {
    $days       = Request::getInt('newdownloadshowdays', 0, 'GET');
    $days_limit = [7, 14, 30];
    if (in_array($days, $days_limit)) {
        $xoopsTpl->assign('newdownloadshowdays', $days);
        $downloadshowdays = time() - (3600 * 24 * $days);
        $criteria->add(new Criteria('published', $downloadshowdays, '>='), 'AND');
    }
}
$criteria->setSort('published');
$criteria->setOrder('DESC');
$criteria->setLimit($helper->getConfig('perpage'));
$criteria->setStart(0);
$downloadObjs = $helper->getHandler('Download')->getActiveDownloads($criteria);
foreach ($downloadObjs as $downloadObj) {
    $downloadInfo = $downloadObj->getDownloadInfo();
    $xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFDOWNLOADS_DLTIMES, $downloadInfo['hits']));
    $xoopsTpl->assign('lang_subdate', $downloadInfo['is_updated']);
    $xoopsTpl->append('file', $downloadInfo);
    $xoopsTpl->append('downloads', $downloadInfo); // this definition is not removed for backward compatibility issues
}

// Screenshots display
$xoopsTpl->assign('show_screenshot', false);
if (1 == $helper->getConfig('screenshot')) {
    $xoopsTpl->assign('shots_dir', $helper->getConfig('screenshots'));
    $xoopsTpl->assign('shotwidth', $helper->getConfig('shotwidth'));
    $xoopsTpl->assign('shotheight', $helper->getConfig('shotheight'));
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('viewcat', true);
}
if (isset($days)) {
    $which_new_downloads = ' > ' . sprintf(_MD_WFDOWNLOADS_NEWDOWNLOADS_INTHELAST, (int)$days);
    $xoopsTpl->assign('categoryPath', '<a href="' . WFDOWNLOADS_URL . '/newlist.php">' . _MD_WFDOWNLOADS_NEWDOWNLOADS . '</a>' . $which_new_downloads);
    $breadcrumb->addLink(_MD_WFDOWNLOADS_LATESTLIST, $currentFile);
    $breadcrumb->addLink(sprintf(_MD_WFDOWNLOADS_NEWDOWNLOADS_INTHELAST, (int)$days), '');
} else {
    $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_NEWDOWNLOADS);
    $breadcrumb->addLink(_MD_WFDOWNLOADS_LATESTLIST, '');
}

// Breadcrumb
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

$xoopsTpl->assign('module_home', Utility::moduleHome(true));
require_once __DIR__ . '/footer.php';
