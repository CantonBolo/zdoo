<?php
/**
 * The model file of contact module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     contact
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
class contactModel extends model
{
    /**
     * Get contact by id.
     * 
     * @param  int    $id 
     * @param  string $status
     * @access public
     * @return object
     */
    public function getByID($id = 0, $status = 'normal')
    {
        $contact = $this->dao->select('*')->from(TABLE_CONTACT)->where('id')->eq($id)->fetch();
        if(!$contact) return $contact;

        $contact->customer = '';
        $contact->maker    = '';
        $contact->title    = '';
        $contact->dept     = '';
        $contact->join     = '';
        $contact->left     = '';

        if($contact->status == 'normal')
        {
            $customerIdList = $this->loadModel('customer')->getCustomersSawByMe();
            if(empty($customerIdList)) return null;

            $resume = $this->dao->select('`customer`, `maker`, `title`, `dept`, `join`, `left`')->from(TABLE_RESUME)->where('id')->eq($contact->resume)->andWhere('customer')->in($customerIdList)->fetch();

            $contact->customer = $resume->customer;
            $contact->maker    = $resume->maker;
            $contact->title    = $resume->title;
            $contact->dept     = $resume->dept;
            $contact->join     = $resume->join;
            $contact->left     = $resume->left;
        }

        return $contact;
    }

    /**
     * Get my contact id list.
     * 
     * @access public
     * @return array
     */
    public function getMine()
    {
        $contactList = $this->dao->select('*')->from(TABLE_CONTACT)
            ->beginIF(!isset($this->app->user->rights['crm']['manageall']) and ($this->app->user->admin != 'super'))
            ->where('createdBy')->eq($this->app->user->account)
            ->fi()
            ->fetchAll('id');

        return array_keys($contactList);
    }

    /**
     * Get contacts saw by me. 
     * 
     * @param  string $type view|edit
     * @param  array  $contactIdList 
     * @param  object $user 
     * @access public
     * @return array
     */
    public function getContactsSawByMe($type = 'view', $contactIdList = array(), $user = null)
    {
        if(!$user) $user = $this->app->user;

        $customerIdList = $this->loadModel('customer')->getCustomersSawByMe($type, array(), $user);
        $contactList = $this->dao->select('t1.id')->from(TABLE_CONTACT)->alias('t1')
            ->leftJoin(TABLE_RESUME)->alias('t2')->on('t1.resume = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.status')->eq('normal')
            ->beginIF(!empty($contactIdList))->andWhere('t1.id')->in($contactIdList)->fi()
            ->beginIF(!isset($user->rights['crm']['manageall']) and ($user->admin != 'super'))
            ->andWhere('t2.customer')->in($customerIdList)
            ->fi()
            ->fetchPairs();
        $leadsList = $this->dao->select('id')->from(TABLE_CONTACT)
            ->where('deleted')->eq('0')
            ->andWhere('status', true)->eq('ignore')
            ->orWhere('assignedTo')->eq($user->account)
            ->markRight(1)
            ->fetchPairs();

        return array_merge($contactList, $leadsList);
    }

    /** 
     * Get contact list.
     * 
     * @param  int    $customer
     * @param  string $relation
     * @param  string $mode
     * @param  string $status
     * @param  string $origin
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getList($customer = 0, $relation = 'client', $mode = 'all', $status = 'normal', $origin = '', $orderBy = 't2.maker_desc', $pager = null)
    {
        $contacts = array();
        if($status != 'normal')
        {
            $contacts = $this->getLeadsList($mode, $status, $origin, $orderBy, $pager);
        }
        else
        {
            $contacts = $this->getContactList($customer, $relation, $mode, $status, $origin, $orderBy, $pager);
        }

        foreach($contacts as $contact) $this->formatContact($contact);

        return $contacts;
    }

    /**
     * Get leads list. 
     * 
     * @param  string $mode 
     * @param  string $status 
     * @param  string $origin 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getLeadsList($mode, $status, $origin, $orderBy, $pager)
    {
        if($orderBy == 'maker_desc') $orderBy = 'id_desc';
        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';

        if($this->session->leadsQuery == false) $this->session->set('leadsQuery', ' 1 = 1');
        $leadsQuery = $this->loadModel('search')->replaceDynamic($this->session->leadsQuery);

        $leads = array();
        if($mode == 'bysearch' && strpos($leadsQuery, '`nextDate`') !== false)
        {
            $leads = $this->dao->select('t1.*')->from(TABLE_CONTACT)->alias('t1')
                ->leftJoin(TABLE_DATING)->alias('t2')->on('t1.id=t2.objectID')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.status')->eq('wait')
                ->andWhere('t2.objectType')->eq('contact')
                ->beginIF($origin)->andWhere('origin')->like("%,$origin,%")->fi()
                ->beginIF($mode == 'bysearch')->andWhere(str_replace('`nextDate`', 't2.date', $leadsQuery))->andWhere('origin')->ne('')->fi() // Make sure won't search the contacts which aren't transformed from leads.

                ->beginIF($mode != 'assignedToNull' && $this->app->user->admin != 'super')
                ->andWhere('assignedTo', true)->eq($this->app->user->account)
                ->orWhere('status')->eq('ignore')
                ->markRight(1)
                ->fi()

                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        if(empty($leads))
        {
            $leads = $this->dao->select('*')->from(TABLE_CONTACT)
                ->where('deleted')->eq(0)
                ->beginIF($mode != 'bysearch' && $status)->andWhere('status')->eq($status)->fi()    // Makr sure can search the leads which had been transformed to contacts.
                ->beginIF($origin)->andWhere('origin')->like("%,$origin,%")->fi()
                ->beginIF($mode == 'assignedToNull')->andWhere('assignedTo')->eq('')->fi()
                ->beginIF($mode == 'assignedTo')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
                ->beginIF($mode == 'ignoredBy')->andWhere('ignoredBy')->eq($this->app->user->account)->fi()
                ->beginIF($mode == 'bysearch')->andWhere($leadsQuery)->andWhere('origin')->ne('')->fi() // Make sure won't search the contacts which aren't transformed from leads.
                ->beginIF($mode == 'next')->andWhere('assignedTo')->eq($this->app->user->account)->andWhere('nextDate')->fi()

                ->beginIF($mode != 'assignedToNull' && $this->app->user->admin != 'super')
                ->andWhere('assignedTo', true)->eq($this->app->user->account)
                ->orWhere('status')->eq('ignore')
                ->markRight(1)
                ->fi()

                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        return $leads;
    }

    /**
     * Get contact list. 
     * 
     * @param  int    $customer 
     * @param  string $relation 
     * @param  string $mode 
     * @param  string $status 
     * @param  string $origin 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getContactList($customer, $relation, $mode, $status, $origin, $orderBy, $pager)
    {
        $customerIdList = array();
        if($relation != 'provider' and $status == 'normal')
        {
            $customerIdList = $this->loadModel('customer')->getCustomersSawByMe();
            if(empty($customerIdList)) return array();
        }

        $this->app->loadClass('date', $static = true);
        $thisMonth = date::getThisMonth();
        $thisWeek  = date::getThisWeek();

        $resumes = $this->dao->select('*')->from(TABLE_RESUME)
            ->where('deleted')->eq(0)
            ->andWhere('customer')->eq($customer)
            ->andWhere('`left`', true)->eq('')
            ->orWhere('`left`')->gt(helper::today())
            ->markRight(1)
            ->fetchAll('contact');

        if($relation == 'client') 
        {
            $customers = $this->dao->select('id')->from(TABLE_CUSTOMER)->where('relation')->ne('provider')->fetchPairs();
            foreach($customerIdList as $id)
            {
                if(!isset($customers[$id])) unset($customerIdList[$id]);
            }
        }
        if($relation == 'provider') 
        {
            $customerIdList = $this->dao->select('id')->from(TABLE_CUSTOMER)->where('relation')->ne('client')->fetchPairs();
        }

        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';

        if($this->session->contactQuery == false) $this->session->set('contactQuery', ' 1 = 1');
        $contactQuery = $this->loadModel('search')->replaceDynamic($this->session->contactQuery);

        $contacts = array();
        if(strpos(',contactedby,past,today,tomorrow,thisweek,thismonth,', ",{$mode},") !== false or ($mode == 'bysearch' && strpos($contactQuery, '`nextDate`') !== false))
        {
            $contacts = $this->dao->select('t1.*, t2.customer, t2.maker, t2.title, t2.dept, t2.join, t2.left')->from(TABLE_CONTACT)->alias('t1')
                ->leftJoin(TABLE_RESUME)->alias('t2')->on('t1.resume = t2.id')
                ->leftJoin(TABLE_DATING)->alias('t3')->on('t1.id=t3.objectID')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t3.status')->eq('wait')
                ->andWhere('t3.objectType')->eq('contact')
                ->andWhere('t2.customer')->in($customerIdList)
                ->beginIF($status)->andWhere('t1.status')->eq($status)->fi()
                ->beginIF($origin)->andWhere('t1.origin')->like("%,$origin,%")->fi()
                ->beginIF($customer)->andWhere('t1.id')->in(array_keys($resumes))->fi()
                ->beginIF($mode == 'contactedby')->andWhere('t3.account')->eq($this->app->user->account)->fi()
                ->beginIF($mode == 'past')->andWhere('t3.date')->lt(helper::today())->andWhere('t3.date')->ne('0000-00-00')->fi()
                ->beginIF($mode == 'today')->andWhere('t3.date')->eq(helper::today())->fi()
                ->beginIF($mode == 'tomorrow')->andWhere('t3.date')->eq(formattime(date::tomorrow(), DT_DATE1))->fi()
                ->beginIF($mode == 'thisweek')->andWhere('t3.date')->between($thisWeek['begin'], $thisWeek['end'])->fi()
                ->beginIF($mode == 'thismonth')->andWhere('t3.date')->between($thisMonth['begin'], $thisMonth['end'])->fi()
                ->beginIF($mode == 'bysearch')->andWhere(str_replace('`nextDate`', 't3.date', $contactQuery))->fi()
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }

        if(strpos(',all,assignedTo,public,', ",{$mode},") !== false or ($mode == 'bysearch' && empty($contacts)))
        {
            $contacts = $this->dao->select('t1.*, t2.customer, t2.maker, t2.title, t2.dept, t2.join, t2.left')->from(TABLE_CONTACT)->alias('t1')
                ->leftJoin(TABLE_RESUME)->alias('t2')->on('t1.resume = t2.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.customer')->in($customerIdList)
                ->beginIF($status)->andWhere('t1.status')->eq($status)->fi()
                ->beginIF($origin)->andWhere('t1.origin')->like("%,$origin,%")->fi()
                ->beginIF($customer)->andWhere('t1.id')->in(array_keys($resumes))->fi()
                ->beginIF($mode == 'assignedTo')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
                ->beginIF($mode == 'public')->andWhere('public')->eq('1')->fi()
                ->beginIF($mode == 'bysearch')->andWhere($contactQuery)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        foreach($resumes as $contactID => $resume)
        {
            if(isset($contacts[$contactID]))
            {
                $contacts[$contactID]->customer = $resume->customer;
                $contacts[$contactID]->maker    = $resume->maker;
                $contacts[$contactID]->title    = $resume->title;
                $contacts[$contactID]->dept     = $resume->dept;
                $contacts[$contactID]->join     = $resume->join;
                $contacts[$contactID]->left     = $resume->left;
            }
        }

        return $contacts;
    }

    /**
     * Get common selecter of contact.
     * 
     * @param  int    $customer 
     * @param  bool   $emptyOption 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function getPairs($customer = 0, $emptyOption = true, $status = 'normal')
    {
        $customerIdList = $this->loadModel('customer')->getCustomersSawByMe();
        if(empty($customerIdList)) return array();

        $contacts = $this->dao->select('t1.id, t1.realname')->from(TABLE_CONTACT)->alias('t1')
            ->leftJoin(TABLE_RESUME)->alias('t2')->on('t1.id = t2.contact')
            ->where('t1.deleted')->eq(0)
            ->beginIF($status)->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($customer)->andWhere('t2.customer')->eq($customer)->fi()
            ->beginIF($status == 'normal')->andWhere('t2.customer')->in($customerIdList)->fi()
            ->fetchPairs();

        if($emptyOption) $contacts = array(0 => '') + $contacts;

        return $contacts;
    }

    /**
     * Get customer option menu of a contact.
     * 
     * @param  int    $contactID 
     * @access public
     * @return array
     */
    public function getCustomerPairs($contactID = 0)
    {
        $customerIdList = $this->loadModel('customer')->getCustomersSawByMe();
        if(empty($customerIdList)) return array();

        return $this->dao->select('customer,name')
            ->FROM(TABLE_RESUME)->alias('r')
            ->leftJoin(TABLE_CUSTOMER)->alias('c')->on('r.customer=c.id')
            ->where('r.contact')->eq($contactID)
            ->andWhere('r.customer')->in($customerIdList)
            ->andWhere('c.deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Create a contact.
     * 
     * @param  object $contact   //create with the data of contact.
     * @access public
     * @return void
     */
    public function create($contact = null, $type = '')
    {
        $now = helper::now();
        if(empty($contact))
        {
            $contact = fixer::input('post')
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', $now)
                ->remove('newCustomer,type,size,status,level,name,files')
                ->setIF($type == 'leads', 'status', 'wait')
                ->setIF($type == 'leads', 'assignedTo', $this->app->user->account)
                ->get();

            $contact->email = str_replace(array(' ', '，'), ',', trim($contact->email));

            if($this->post->newCustomer)
            {
                $customer = new stdclass();
                $customer->name        = $this->post->name ? $this->post->name : $contact->realname;
                $customer->type        = $this->post->type;
                $customer->size        = $this->post->size;
                $customer->status      = $this->post->status;
                $customer->level       = $this->post->level;
                $customer->desc        = $contact->desc;
                $customer->assignedTo  = $this->app->user->account;
                $customer->createdBy   = $this->app->user->account;
                $customer->createdDate = $now;

                $this->dao->insert(TABLE_CONTACT)->data($contact)
                    ->autoCheck()
                    ->checkIF($contact->phone, 'phone', 'length', 20, 7);

                if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

                if(!$this->post->continue)
                {
                    $return = $this->checkContact($contact);
                    if(isset($return['result']) && $return['result'] == 'fail') return $return;
                }

                $return = $this->loadModel('customer')->create($customer);
                if($return['result'] == 'fail') return $return;
                $contact->customer = $return['customerID'];
            }
        }

        if(!$this->post->continue)
        {
            $return = $this->checkContact($contact);
            if($return['result'] == 'fail') return $return;
        }

        if($type == 'leads') $this->config->contact->require->create = 'realname, origin';

        $this->dao->insert(TABLE_CONTACT)
            ->data($contact, 'customer,title,dept,maker,join,continue')
            ->autoCheck()
            ->batchCheck($this->config->contact->require->create, 'notempty')
            ->checkIF(!empty($contact->phone), 'phone', 'length', 20, 7)
            ->exec();

        if(!dao::isError())
        {
            $contactID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('contact', $contactID, 'Created', '');

            if($type != 'leads')
            {
                $resume = new stdclass();
                $resume->contact  = $contactID;
                $resume->customer = $contact->customer;
                $resume->maker    = isset($contact->maker) ? $contact->maker : 0;
                $resume->dept     = isset($contact->dept) ? $contact->dept : '';
                $resume->title    = isset($contact->title) ? $contact->title : '';
                $resume->join     = isset($contact->join) ? $contact->join : '';

                $this->dao->insert(TABLE_RESUME)->data($resume)->exec();
                if(!dao::isError()) $this->dao->update(TABLE_CONTACT)->set('resume')->eq($this->dao->lastInsertID())->where('id')->eq($contactID)->exec();
            }

            $result  = $this->updateAvatar($contactID);
            $message = $result['result'] ? $this->lang->saveSuccess : $result['message'];
            $link    = $type == 'leads' ? helper::createLink('leads', 'browse', 'mode=assignedTo') : helper::createLink('contact', 'browse');
            return array('result' => 'success', 'message' => $message, 'locate' => $link, 'contactID' => $contactID);
        }

        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Update a contact.
     * 
     * @param  int    $contactID 
     * @access public
     * @return string
     */
    public function update($contactID)
    {
        $oldContact = $this->getByID($contactID);

        $contact = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->setDefault('birthday', '0000-00-00')
            ->setIF($this->post->avatar == '', 'avatar', $oldContact->avatar)
            ->setIF($this->post->weibo == 'http://weibo.com/', 'weibo', '')
            ->setIF($this->post->site == 'http://', 'site', '')
            ->remove('files')
            ->get();

        if($contact->site and strpos($contact->site, '://') === false )  $contact->site  = 'http://' . $contact->site;
        if($contact->weibo and strpos($contact->weibo, 'http://weibo.com/') === false ) $contact->weibo = 'http://weibo.com/' . $contact->weibo;
        $contact->email = str_replace(array(' ', '，'), ',', trim($contact->email));

        if($oldContact->status != 'normal') $this->config->contact->require->edit = 'realname';

        $this->dao->update(TABLE_CONTACT)
            ->data($contact, 'customer,dept,maker,title,join')
            ->autoCheck()
            ->batchCheck($this->config->contact->require->edit, 'notempty')
            ->where('id')->eq($contactID)
            ->exec();

        if(dao::isError()) return false;

        if($oldContact->status == 'normal')
        {
            $resume = new stdclass();
            $resume->contact  = $contactID;
            $resume->customer = $contact->customer;
            $resume->dept     = isset($contact->dept) ? $contact->dept : '';
            $resume->maker    = isset($contact->maker) ? $contact->maker : 0;
            $resume->title    = isset($contact->title) ? $contact->title : '';
            $resume->join     = isset($contact->join) ? $contact->join : '';

            if($oldContact->customer != $contact->customer)
            {
                $this->dao->insert(TABLE_RESUME)->data($resume)->exec();
                if(!dao::isError()) $this->dao->update(TABLE_CONTACT)->set('resume')->eq($this->dao->lastInsertID())->where('id')->eq($contactID)->exec();
            }
            else
            {
                $this->dao->update(TABLE_RESUME)->data($resume)->where('id')->eq($oldContact->resume)->exec();
            }
        }

        return commonModel::createChanges($oldContact, $contact);
    }

    /**
     * Update contact avatar. 
     * 
     * @param  int    $contactID 
     * @access public
     * @return void
     */
    public function updateAvatar($contactID)
    {
        if(!$_FILES) return array('result' => true, 'contactID' => $contactID);

        $fileModel = $this->loadModel('file');

        if(!$this->file->checkSavePath()) return array('result' => false, 'message' => $this->lang->file->errorUnwritable);

        /* Delete old files. */
        $oldFiles = $this->dao->select('id')->from(TABLE_FILE)->where('objectType')->eq('avatar')->andWhere('objectID')->eq($contactID)->fetchAll('id');
        if($oldFiles)
        {
            foreach($oldFiles as $file) $fileModel->delete($file->id);
            if(dao::isError()) return array('result' => false, 'message' => $this->lang->contact->failedAvatar);
        }

        /* Upload new avatar. */
        $uploadResult = $fileModel->saveUpload('avatar', $contactID);
        if(!$uploadResult) return array('result' => false, 'message' => $this->lang->contact->failedAvatar);

        $fileIdList = array_keys($uploadResult);
        $file       = $fileModel->getById($fileIdList[0]);

        $avatarPath = $this->config->webRoot . 'data/upload/' . $file->pathname;
        $this->dao->update(TABLE_CONTACT)->set('avatar')->eq($avatarPath)->where('id')->eq($contactID)->exec();
        if(!dao::isError()) return array('result' => true, 'contactID' => $contactID);

        return array('result' => false, 'message' => $this->lang->contact->failedAvatar);
    }

    /**
     * Format contact. 
     * 
     * @param  object $contact 
     * @access public
     * @return void
     */
    public function formatContact($contact)
    {
        if($contact->phone and strlen($contact->phone) == 11 and substr($contact->phone, 0, 1) == '1') $contact->phone = substr($contact->phone, 0, 3) . ' ' . substr($contact->phone, 3, 4) . ' ' . substr($contact->phone, 7, 4);

        if($contact->mobile and strlen($contact->mobile) == 11) $contact->mobile = substr($contact->mobile, 0, 3) . ' ' . substr($contact->mobile, 3, 4) . ' ' . substr($contact->mobile, 7, 4);
    }

    /**
     * Check contact when insert table. 
     * 
     * @param  object $contact 
     * @access public
     * @return void
     */
    public function checkContact($contact)
    {
        $contactList = $this->dao->select('*')->from(TABLE_CONTACT)->where('realname')->eq($contact->realname)->fetchAll('id');

        if(!empty($contactList))
        {
            if(!empty($contact->customer))
            {
                $customerContactList = $this->dao->select('contact')->from(TABLE_RESUME)->where('customer')->eq($contact->customer)->fetchAll('contact');

                foreach($contactList as $id => $data)
                {
                    if(isset($customerContactList[$id]))
                    {
                        $error = sprintf($this->lang->error->unique, $this->lang->customer->contact, html::a(helper::createLink('contact', 'view', "contactID={$data->id}"), $data->realname, "target='_blank'"));
                        return array('result' => 'fail', 'error' => $error);
                    }
                }
            }
        }

        foreach($this->config->contact->contactWayList as $item)
        {
            if(!$this->post->$item) continue;

            if(!empty($contact->$item))
            {
                if($item == 'phone' or $item == 'mobile')
                {
                    $existContact = $this->dao->select('*')->from(TABLE_CONTACT)->where('phone')->eq($contact->$item)->orWhere('mobile')->eq($contact->$item)->fetch();
                }
                else
                {
                    $existContact = $this->dao->select('*')->from(TABLE_CONTACT)->where($item)->eq($contact->$item)->fetch();
                }
            }

            if(!empty($existContact))
            {
                if($item == 'phone' or $item == 'mobile')
                {
                    $error = sprintf($this->lang->error->unique, $this->lang->contact->phone . $this->lang->slash . $this->lang->contact->mobile, html::a(helper::createLink('contact', 'view', "contactID={$existContact->id}"), !empty($existContact->phone) ? $existContact->phone : $existContact->mobile, "target='_blank'"));
                }
                else
                {
                    $error = sprintf($this->lang->error->unique, $this->lang->contact->{$item}, html::a(helper::createLink('contact', 'view', "contactID={$existContact->id}"), $existContact->$item, "target='_blank'"));
                }
                return array('result' => 'fail', 'error' => $error);
            }
        }

        return true;
    }

    /**
     * Transform status for contact.
     * 
     * @param  int    $contactID 
     * @access public
     * @return void
     */
    public function transform($contactID)
    {
        $customerID = 0;
        if(!$this->post->selectCustomer)
        {
            $customer = new stdclass();
            $customer->name        = $this->post->name;
            $customer->relation    = 'client';
            $customer->createdBy   = $this->app->user->account;
            $customer->assignedTo  = $this->app->user->account;
            $customer->createdDate = helper::now();

            if(!$this->post->continue)
            {
                $return = $this->loadModel('customer')->checkUnique($customer);
                if($return['result'] == 'fail') return $return;
            }

            $this->dao->insert(TABLE_CUSTOMER)->data($customer, $skip = 'uid,contact,email,qq,phone,continue')->autoCheck()->batchCheck('name', 'notempty')->exec();
            if(dao::isError()) return false;
            $customerID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('customer', $customerID, 'Created');

            $resume = new stdclass();
            $resume->contact  = $contactID;
            $resume->customer = $customerID;

            $this->dao->insert(TABLE_RESUME)->data($resume)->exec();
            if(!dao::isError()) $this->dao->update(TABLE_CONTACT)->set('resume')->eq($this->dao->lastInsertID())->set('status')->eq('normal')->where('id')->eq($contactID)->exec();
        }
        else
        {
            $customerID = $this->post->customer;
            $resume = new stdclass();
            $resume->contact  = $contactID;
            $resume->customer = $customerID;

            $this->dao->insert(TABLE_RESUME)->data($resume)->exec();
            if(!dao::isError()) $this->dao->update(TABLE_CONTACT)->set('resume')->eq($this->dao->lastInsertID())->set('status')->eq('normal')->where('id')->eq($contactID)->exec();
        }

        $this->dao->update(TABLE_ACTION)->set('customer')->eq($customerID)->where('contact')->eq($contactID)->andWhere('action')->eq('record')->exec();

        return !dao::isError();
    }

    /**
     * Ignore contact in leads.
     * 
     * @param  int    $contactID 
     * @access public
     * @return bool
     */
    public function ignore($contactID)
    {
        $this->dao->update(TABLE_CONTACT)->set('status')->eq('ignore')->set('ignoredBy')->eq($this->app->user->account)->where('id')->eq($contactID)->exec();
        return !dao::isError();
    }

    /**
     * Import contacts. 
     * 
     * @access public
     * @return array
     */
    public function import()
    {
        $fields = array();
        foreach($this->config->contact->templateFields as $field)
        {
            $fields[$field] = $this->lang->contact->$field;
        }
        $contactList = $this->loadModel('file')->parseExcel($fields);

        $errorList   = array();
        $successList = array();
        $gender      = $this->lang->contact->genderList;
        foreach($contactList as $key => $contact)
        {
            if(empty($contact->realname)) continue;

            foreach($this->config->contact->listFields as $field)
            {
                $contact->$field = array_search($contact->$field, $$field);
            }

            $contact->customer   = $contact->realname;
            $contact->status     = 'wait';
            $contact->assignedTo = $this->app->user->account;
            $result = $this->checkContact($contact);
            if($result['result'] == 'fail') 
            {
                $contact->reason = $result['error'];
                $errorList[$key] = $contact;
                continue;
            }

            if(empty($contact->birthday)) $skip = 'birthday'; 
            $this->dao->insert(TABLE_CONTACT)->data($contact, 'customer')->autoCheck($skip)->exec();
            if(dao::isError())
            {
                $contact->reason = '';
                foreach(dao::getError() as $error)
                {
                    $contact->reason .= implode(';', $error);
                }
                $errorList[$key] = $contact;
            }
            else
            {
                $successList[$key] = $contact;
            }

            $contactID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('contact', $contactID, 'imported');
        }
        $this->session->set('errorList', $errorList);

        return $successList;
    }

    /**
     * Assign a contact to a member.
     * 
     * @param  int    $contactID 
     * @access public
     * @return void
     */
    public function assign($contactID)
    {
        $contact = fixer::input('post')
            ->add('status', 'wait')
            ->add('ignoredBy', '')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_CONTACT)
            ->data($contact, $skip = 'uid, comment')
            ->autoCheck()
            ->where('id')->eq($contactID)
            ->exec();

        return !dao::isError();
    }
}
