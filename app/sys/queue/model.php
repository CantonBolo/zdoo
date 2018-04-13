<?php
/**
 * The model file of queue module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     queue
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
class queueModel extends model
{
    public function getQueues($status = '')
    {
        return $this->dao->select('*')->from(TABLE_QUEUE)
            ->beginIF($status)->where('status')->eq($status)->fi()
            ->fetchAll('id');
    }

    /**
     * Check send.
     *
     * @param  int    $objectID
     * @param  int    $actionID
     * @param  string $toList
     * @access public
     * @return void
     */
    public function send($objectID, $actionID, $toList = '')
    {
        $queueSetting = $this->config->queue->setting;
        if(is_string($queueSetting)) $queueSetting = json_decode($queueSetting, true);

        $action = $this->loadModel('action')->getByID($actionID);
        if(!$action) return;
        $objectType = $action->objectType;
        $actionType = $action->action;

        if(isset($queueSetting['mail']))
        {
            $actions = $queueSetting['mail']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType]))
            {
                $this->loadModel($objectType);
                if(method_exists($this->{$objectType}, 'sendmail')) $this->{$objectType}->sendmail($objectID, $actionID);
            }
        }

        //Not implemented.
        if(isset($queueSetting['webhook']))
        {
            $actions = $queueSetting['webhook']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType]))
            {
                $this->loadModel('webhook')->send($objectType, $objectID, $actionType, $actionID);
            }
        }

        //Not implemented.
        if(isset($queueSetting['message']))
        {
            $actions = $queueSetting['message']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType]))
            {
                $this->loadModel('message')->saveNotice($objectType, $objectID, $actionType, $actionID);
            }
        }

        if(isset($queueSetting['xuanxuan']))
        {
            $actions = $queueSetting['xuanxuan']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType]))
            {
                $this->sendNoticeToXuanXuan($toList, $action);
            }
        }
    }

    /**
     * Send notice to XuanXuan.
     *
     * @param string $toList
     * @param object $action
     * @access public
     * @return void
     */
    public function sendNoticeToXuanXuan($toList, $action)
    {
        if(empty($toList)) return;
        $target = $this->dao->select('id')->from(TABLE_USER)->where('account')->in($toList)->fetchPairs();

        $this->loadModel($action->objectType);
        $info = $this->{$action->objectType}->getById($action->objectID);
        if(!$info) return;

        $actionName = isset($this->lang->{$action->objectType}->{$action->action}) ? $this->lang->{$action->objectType}->{$action->action} : '';
        $subject    = $actionName . $this->lang->{$action->objectType}->common;
        $content    = $this->lang->{$action->objectType}->common . "#{$info->id} {$info->name}";
        $link       = '';
        $actions    = array();
        $appInfo    = array('id' => 'ranzhi');

        $this->loadModel('chat')->createNotify(array_keys($target), $subject, '', $content, 'text', $link, $actions, $appInfo);
    }

    /**
     * Insert Queue.
     *
     * @param  int    $objectID
     * @param  int    $actionID
     * @param  string $toList
     * @access public
     * @return void
     */
    public function insertQueue($objectID, $actionID, $toList)
    {
        $queue = new stdclass();
        $queue->objectID    = $objectID;
        $queue->action      = $actionID;
        $queue->toList      = $toList;
        $queue->status      = 'wait';
        $queue->createdBy   = $this->app->user->account;
        $queue->createdDate = helper::now();

        $this->dao->insert(TABLE_QUEUE)->data($queue)->exec();
    }
}
