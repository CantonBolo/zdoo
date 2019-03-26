<?php
/**
 * The control file of file module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: control.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhi.org
 */
class file extends control
{
    /**
     * Build the upload form.
     *
     * @param int $fileCount
     * @param float $percent
     * @access public
     * @return void
     */
    public function buildForm($fileCount = 2, $percent = 0.9)
    {
        $this->view->writable  = $this->file->checkSavePath();
        $this->view->fileCount = $fileCount;
        $this->view->percent   = $percent;
        $this->display();
    }

    /**
     * Build the list part of files.
     *
     * @param array $files
     * @access public
     * @return string
     */
    public function buildList($files)
    {
        $this->view->files = $files;
        $this->display();
    }

    /**
     * Print files.
     *
     * @param  array  $files
     * @param  string $fieldset
     * @access public
     * @return void
     */
    public function printFiles($files, $fieldset)
    {
        $this->view->files    = $files;
        $this->view->fieldset = $fieldset;
        $this->display();
    }

    /**
     * AJAX: the api to recive the file posted through ajax.
     *
     * @param  string $uid
     * @access public
     * @return array
     */
    public function ajaxUpload($uid)
    {
        $file = $this->file->getUpload('imgFile');
        $file = $file[0];
        if($file)
        {
            if(!$this->file->checkSavePath()) $this->send(array('error' => 1, 'message' => $this->lang->file->errorUnwritable));
            move_uploaded_file($file['tmpname'], $this->file->savePath . $this->file->getSaveName($file['pathname']));

            /* Compress image for jpg and bmp. */
            $file = $this->file->compressImage($file);

            $file['createdBy']   = $this->app->user->account;
            $file['createdDate'] = helper::now();
            $file['editor']      = 1;
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();

            $fileID = $this->dao->lastInsertID();
            $url    = $this->createLink('file', 'download', "fileID=$fileID");
            if($uid) $_SESSION['album'][$uid][] = $fileID;
            die(json_encode(array('error' => 0, 'url' => $url)));
        }
    }

    /**
     * AJAX: get upload request from the web editor.
     *
     * @access public
     * @return void
     */
    public function ajaxUeditorUpload($uid = '')
    {
        if($this->get->action == 'config')
        {
            die(json_encode($this->config->file->ueditor));
        }

        $file = $this->file->getUpload('upfile');
        $file = $file[0];
        if($file)
        {
            if($file['size'] == 0) die(json_encode(array('state' => $this->lang->file->errorFileUpload)));
            if(!$this->file->checkSavePath()) $this->send(array('state' => $this->lang->file->errorUnwritable));

            move_uploaded_file($file['tmpname'], $this->file->savePath . $this->file->getSaveName($file['pathname']));

            /* Compress image for jpg and bmp. */
            $file = $this->file->compressImage($file);

            $file['createdBy']   = $this->app->user->account;
            $file['createdDate'] = helper::today();
            $file['editor']      = 1;
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();

            $fileID = $this->dao->lastInsertID();
            $url    = $this->createLink('file', 'read', "fileID=$fileID", $file['extension']);
            if($uid) $_SESSION['album'][$uid][] = $fileID;
            die(json_encode(array('state' => 'SUCCESS', 'url' => $url)));
        }
    }

    /**
     * The list page of an object
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function browse($objectType, $objectID)
    {
        $this->view->writable   = $this->file->checkSavePath();
        $this->view->objectType = $objectType;
        $this->view->objectID   = $objectID;
        $this->view->files      = $this->file->getByObject($objectType, $objectID);
        $this->display();
    }

    /**
     * Edit for the file
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function edit($fileID)
    {
        $file = $this->file->getById($fileID);
        if(!empty($_POST))
        {
            if(!$this->file->checkSavePath()) $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorUnwritable));
            $this->file->edit($fileID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($_FILES or $this->post->title != $file->title)
            {
                $extension = "." . $file->extension;
                $actionID = $this->loadModel('action')->create($file->objectType, $file->objectID, 'editfile', '', $this->post->title . $extension);

                if($this->post->title != $file->title)
                {
                    $changes[] = array('field' => 'fileName', 'old' => $file->title . $extension, 'new' => $this->post->title . $extension);
                    $this->action->logHistory($actionID, $changes);
                }
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title      = $this->lang->file->edit;
        $this->view->modalWidth = '450';
        $this->view->file       = $file;
        $this->display();
    }

    /**
     * Upload files for an object.
     *
     * @param  string $objectType
     * @param  string $objectID
     * @access public
     * @return void
     */
    public function upload($objectType, $objectID)
    {
        if(!$this->file->checkSavePath()) $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorUnwritable));
        $files = $this->file->getUpload('files');
        if($files) $this->file->saveUpload($objectType, $objectID);
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Down a file.
     *
     * @param  int    $fileID
     * @param  string $mouse
     * @param  int    $time
     * @param  string $token
     * @access public
     * @return void
     */
    public function download($fileID, $mouse = '', $time = 0, $token = 0)
    {
        $file = $this->file->getById($fileID);

        if(!file_exists($file->realPath)) die('file not found!');

        /* Judge the mode, down or open. */
        $mode  = 'down';
        $fileTypes = 'txt|jpg|jpeg|gif|png|bmp|xml|html';
        if(stripos($fileTypes, $file->extension) !== false and $mouse == 'left') $mode = 'open';

        $verification = true;
        if(!empty($token))
        {
            if(($time + 600) < time() || md5($file->pathname . $time) != $token) $verification = false;
        }
        else
        {
            if(!$file->public && $this->app->user->account == 'guest') $verification = false;
        }

        if($verification == false) $this->locate($this->createLink('user', 'login'));

        /* If the mode is open, locate directly. */
        if($mode == 'open')
        {
            /* If the web server is nginx, it will download the file because the extension of file is empty. Use php to output file to avoid this situation. */
            $mime = in_array($file->extension, $this->config->file->imageExtensions) ? "image/{$file->extension}" : $this->config->file->mimes['default'];
            header("content-type: $mime");
            die(file_get_contents($file->realPath));
        }
        else
        {
            /* Down the file. */
            setcookie('downloading', 1);
            $fileType = $file->extension;
            $fileName = $file->title . '.' . $fileType;
            $fileSize = filesize($file->realPath);
            $isIE = (strpos($this->server->http_user_agent, 'Trident') !== false) or (strpos($this->server->http_user_agent, 'MSIE') !== false) ;
            if($isIE) $fileName = urlencode($fileName);
            /* Judge the content type. */
            $mimes = $this->config->file->mimes;
            $contentType = isset($mimes[$fileType]) ? $mimes[$fileType] : $mimes['default'];

            header("Content-type: $contentType");
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Content-length: $fileSize");
            readfile($file->realPath);
            die();
        }
    }

    /**
     * set a image as primary image.
     *
     * @param  int  $fileID
     * @access public
     * @return void
     */
    public function setPrimary($fileID)
    {
        $file = $this->file->getByID($fileID);
        if(!$file) $this->send(array( 'result' => 'fail', 'message' => $this->lang->fail));

        $this->dao->update(TABLE_FILE)
            ->set('primary')->eq(0)
            ->where('id')->ne($fileID)
            ->andWhere('objectType')->eq($file->objectType)
            ->andWhere('objectID')->eq($file->objectID)
            ->exec(false);

        $this->dao->update(TABLE_FILE)->set('primary')->eq(1)->where('id')->eq($fileID)->exec();

        $this->send(array( 'result' => 'success', 'message' => $this->lang->setSuccess));
    }

    /**
     * Export as csv format.
     *
     * @access public
     * @return void
     */
    public function export2CSV()
    {
        $this->view->fields = $this->post->fields;
        $this->view->rows   = $this->post->rows;
        $output = $this->parse('file', 'export2csv');

        /* If the language is zh-cn, convert to gbk. */
        $clientLang = $this->app->getClientLang();
        if($clientLang == 'zh-cn')
        {
            if(function_exists('mb_convert_encoding'))
            {
                $output = @mb_convert_encoding($output, 'gbk', 'utf-8');
            }
            elseif(function_exists('iconv'))
            {
                $output = @iconv('utf-8', 'gbk', $output);
            }
        }

        $this->file->sendDownHeader($this->post->fileName, 'csv', $output);
    }

    /**
     * Delet a file.
     *
     * @param  int  $fileID
     * @return void
     */
    public function delete($fileID)
    {
        $file = $this->file->getById($fileID);

        $this->file->delete($fileID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create($file->objectType, $file->objectID, 'deletedFile', '', $extra=$file->title);
        $this->send(array('result' => 'success'));
    }

    /**
     * Paste image in kindeditor at firefox and chrome.
     *
     * @param  string uid
     * @access public
     * @return void
     */
    public function ajaxPasteImage($uid)
    {
        if($_POST)
        {
            echo $this->file->pasteImage($this->post->editor, $uid);
        }
    }

    /**
     * Get file from file directory in kindeditor.
     *
     * @access public
     * @return void
     */
    public function fileManager()
    {
        $fileTypes = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
        $order = $this->get->order ? strtolower($this->get->order) : 'name';

        if(empty($_GET['path']))
        {
            $currentPath    = $this->file->savePath;
            $currentUrl     = $this->file->webPath;
            $currentDirPath = '';
            $moveupDirPath  = '';
        }
        else
        {
            $currentPath    = $this->file->savePath . '/' . $this->get->path;
            $currentUrl     = $this->file->webPath . $this->get->path;
            $currentDirPath = $this->get->path;
            $moveupDirPath  = preg_replace('/(.*?)[^\/]+\/$/', '$1', $currentDirPath);
        }

        if(preg_match('/\.\./', $currentPath)) die($this->lang->file->noAccess);
        if(!preg_match('/\/$/', $currentPath)) die($this->lang->file->invalidParameter);
        if(!file_exists($currentPath) || !is_dir($currentPath)) die($this->lang->file->unWritable);

        $fileList = array();
        if($fileDir = opendir($currentPath))
        {
            $i = 0;
            while(($filename = readdir($fileDir)) !== false)
            {
                if($filename{0} == '.') continue;
                $file = $currentPath . $filename;
                $fileList[$i]['filename'] = $filename;
                if(is_dir($file))
                {
                    $fileList[$i]['is_dir']   = true;
                    $fileList[$i]['has_file'] = (count(scandir($file)) > 2);
                    $fileList[$i]['filesize'] = 0;
                    $fileList[$i]['is_photo'] = false;
                    $fileList[$i]['filetype'] = '';
                }
                else
                {
                    if(strpos($filename, 'f_') === false) continue;
                    $fileExtension = $this->file->getExtension($file);
                    $fileList[$i]['is_dir']    = false;
                    $fileList[$i]['has_file']  = false;
                    $fileList[$i]['filesize']  = filesize($file);
                    $fileList[$i]['dir_path']  = '';
                    $fileList[$i]['is_photo']  = in_array($fileExtension, $fileTypes);
                    $fileList[$i]['filetype']  = $fileExtension;
                    $fileList[$i]['filename']  = $filename . "?fromSpace=y";
                }

                $fileList[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));
                $fileList[$i]['order']    = $order;
                $i++;
            }
            closedir($fileDir);
        }

        usort($fileList, "file::sort");

        $result = array();
        $result['moveup_dir_path']  = $moveupDirPath;
        $result['current_dir_path'] = $currentDirPath;
        $result['current_url']      = $currentUrl;
        $result['total_count']      = count($fileList);
        $result['file_list']        = $fileList;

        die(json_encode($result));
    }

    /**
     * Sort the file.
     *
     * @access public
     * @return void
     */
    static public function sort($a, $b)
    {
        if(isset($a['is_dir']) && !isset($b['is_dir']))
        {
            return -1;
        }
        elseif(!isset($a['is_dir']) && isset($b['is_dir']))
        {
            return 1;
        }
        else
        {
            if($a['order'] == 'size')
            {
                if($a['filesize'] > $b['filesize']) return 1;
                if($a['filesize'] < $b['filesize']) return -1;
                if($a['filesize'] = $b['filesize']) return 0;
            }
            if($a['order'] == 'type') return strcmp($a['filetype'], $b['filetype']);
            if($a['order'] == 'name') return strcmp($a['filename'], $b['filename']);
        }
    }

    /**
     * Read file.
     *
     * @param  int    $fileID
     * @access public
     * @return void
     */
    public function read($fileID)
    {
        $file = $this->file->getById($fileID);
        if(empty($file) or !file_exists($file->realPath)) return false;

        $mime = in_array($file->extension, $this->config->file->imageExtensions) ? "image/{$file->extension}" : $this->config->file->mimes['default'];
        header("Content-type: $mime");

        $handle = fopen($file->realPath, "r");
        if($handle)
        {
            while(!feof($handle)) echo fgets($handle);
            fclose($handle);
        }
    }
}
