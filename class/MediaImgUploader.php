<?php

namespace XoopsModules\Wfdownloads;

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
/**
 * !
 * Example
 *
 * require_once __DIR__ . '/uploader.php';
 * $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
 * $maxfilesize = 50000;
 * $maxfilewidth = 120;
 * $maxfileheight = 120;
 * $uploader = new \XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
 * if ($uploader->fetchMedia($_POST['uploade_file_name'])) {
 * if (!$uploader->upload()) {
 * echo $uploader->getErrors();
 * } else {
 * echo '<h4>File uploaded successfully!</h4>'
 * echo 'Saved as: ' . $uploader->getSavedFileName() . '<br>';
 * echo 'Full path: ' . $uploader->getSavedDestination();
 * }
 * } else {
 * echo $uploader->getErrors();
 * }
 */

/**
 * Upload Media files
 *
 * Example of usage:
 * <code>
 * require_once __DIR__ . '/uploader.php';
 * $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
 * $maxfilesize = 50000;
 * $maxfilewidth = 120;
 * $maxfileheight = 120;
 * $uploader = new \XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
 * if ($uploader->fetchMedia($_POST['uploade_file_name'])) {
 *            if (!$uploader->upload()) {
 *               echo $uploader->getErrors();
 *            } else {
 *               echo '<h4>File uploaded successfully!</h4>'
 *               echo 'Saved as: ' . $uploader->getSavedFileName() . '<br>';
 *               echo 'Full path: ' . $uploader->getSavedDestination();
 *            }
 * } else {
 *            echo $uploader->getErrors();
 * }
 * </code>
 *
 * @package       kernel
 * @subpackage    core
 * @author        Kazumi Ono <onokazu@xoops.org>
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */

use XoopsModules\Wfdownloads;

//require_once XOOPS_ROOT_PATH . '/modules/wfdownloads/class/uploader.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';

/**
 * Class MediaImgUploader
 */
class MediaImgUploader extends \XoopsMediaUploader
{
    public $randomfilename;

    /**
     * Constructor
     *
     * @param string    $uploadDir
     * @param array|int $allowedMimeTypes
     * @param int       $maxFileSize
     * @param int       $maxWidth
     * @param int       $maxHeight
     */
    public function __construct($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth = 0, $maxHeight = 0)
    {
        parent::__construct($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth, $maxHeight);
        $this->randomfilename = false;
    }
}
