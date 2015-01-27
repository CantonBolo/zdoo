<?php
/**
 * The control file of upgrade module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class upgrade extends control
{
    /**
     * Construct, check the user can upgrade or not.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * The index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        if(version_compare($this->config->installedVersion, '1.3.beta', '>'))
        {
            if(version_compare($this->config->installedVersion, '2.0', '<')) $this->locate(inlink('upgradeLicense'));
            $this->locate(inlink('backup'));
        }

        $this->display();
    }

    /**
     * The backup page.
     * 
     * @access public
     * @return void
     */
    public function backup()
    {
        $this->view->title = $this->lang->upgrade->backup;
        $this->view->db    = $this->config->db;
        $this->display();
    }

    public function upgradeLicense()
    {
        if($this->get->agree == true) $this->locate(inlink('backup'));

        $this->view->license = file_get_contents($this->app->getBasePath() . 'doc/LICENSE');
        $this->display();
    }

    /**
     * Select the version of old ranzhi.
     * 
     * @access public
     * @return void
     */
    public function selectVersion()
    {
        $version = str_replace(array(' ', '.'), array('', '_'), $this->config->installedVersion);
        $version = strtolower($version);

        $this->view->title   = $this->lang->upgrade->common . $this->lang->colon . $this->lang->upgrade->selectVersion;
        $this->view->version = $version;
        $this->display();
    }

    /**
     * Confirm the version.
     * 
     * @access public
     * @return void
     */
    public function confirm()
    {
        $confirmContent = $this->upgrade->getConfirm($this->post->fromVersion);
        if(empty($confirmContent)) $this->locate(inlink('execute', "fromVersion={$this->post->fromVersion}"));

        $this->view->title       = $this->lang->upgrade->confirm;
        $this->view->confirm     = $confirmContent;
        $this->view->fromVersion = $this->post->fromVersion;

        $this->display();
    }

    /**
     * Execute the upgrading.
     * 
     * @param  string  $fromVersion
     * @access public
     * @return void
     */
    public function execute($fromVersion)
    {
        $fromVersion = isset($_POST['fromVersion']) ? $this->post->fromVersion : $fromVersion;
        $this->upgrade->execute($fromVersion);

        $this->view->title = $this->lang->upgrade->result;

        if(!$this->upgrade->isError())
        {
            $this->view->result = 'success';
        }
        else
        {
            $this->view->result = 'fail';
            $this->view->errors = $this->upgrade->getError();
        }
        $this->display();
    }
}
