<?php
/**
 * The model file of product module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
class productModel extends model
{
    /**
     * Get produt by id.
     * 
     * @param  int    $id 
     * @param  bool   $getFiles
     * @access public
     * @return object 
     */
    public function getByID($id = 0, $getFiles = true)
    {
        $product = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq($id)->fetch();
        if($product && $getFiles) $product->files = $this->loadModel('file')->getByObject('product', $product->id);
        return $product;
    }

    /** 
     * Get product list.
     * 
     * @param  string  $mode
     * @param  string  $status
     * @param  string  $category
     * @param  string  $orderBy 
     * @param  object  $pager 
     * @access public
     * @return array
     */
    public function getList($mode = 'browse', $status = '', $category = '', $orderBy = 'order_desc', $pager = null)
    {
        /* Process search condition. */
        if($this->session->productQuery == false) $this->session->set('productQuery', ' 1 = 1');
        $productQuery = $this->loadModel('search')->replaceDynamic($this->session->productQuery);

        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';

        $categories = array();
        if($category) $categories = $this->loadModel('tree')->getFamily($category);
        return $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($mode == 'browse' && $status && $status != 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF($mode == 'browse' && $categories)->andWhere('category')->in($categories)->fi()
            ->beginIF($mode == 'bysearch')->andWhere($productQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /** 
     * Get product pairs.
     * 
     * @param  string  $status
     * @param  int     $category
     * @param  string  $orderBy 
     * @access public
     * @return array
     */
    public function getPairs($status = '', $category = '', $orderBy = 'order_desc')
    {
        $categories = array();
        if($category) $categories = $this->loadModel('tree')->getFamily($category);
        return $this->dao->select('id, name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($status)->andWhere('status')->in($status)->fi()
            ->beginIF(!empty($categories))->andWhere('category')->in($categories)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * Create a product.
     * 
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $now = helper::now();
        $product = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('editedDate', $now)
            ->get();

        $this->dao->insert(TABLE_PRODUCT)
            ->data($product, 'files,labels')
            ->autoCheck()
            ->batchCheck($this->config->product->require->create, 'notempty')
            ->check('code', 'unique')
            ->check('code', 'code')
            ->check('code', 'notInt')
            ->exec();

        $productID = $this->dao->lastInsertID();
        $this->loadModel('file')->saveUpload('product', $productID);
        return $productID;
    }

    /**
     * Update a product.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function update($productID = 0)
    {
        $oldProduct = $this->getByID($productID, $getFiles = false);

        $product = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_PRODUCT)
            ->data($product, 'files,labels')
            ->autoCheck()
            ->batchCheck($this->config->product->require->edit, 'notempty')
            ->check('code', 'unique', "id!={$productID}")
            ->check('code', 'code')
            ->check('code', 'notInt')
            ->where('id')->eq($productID)
            ->exec();

        if(dao::isError()) return false;

        return commonModel::createChanges($oldProduct, $product);
    }

    /**
     * Check product is unique.
     * 
     * @param  string  $name
     * @access public
     * @return array
     */
    public function checkUnique($name = '')
    {
        if($name) $data = $this->dao->select('*')->from(TABLE_PRODUCT)->where('name')->eq($name)->fetch();
        if(!empty($data))
        {
            $error = sprintf($this->lang->error->unique, $this->lang->product->name, html::a(helper::createLink('product', 'view', "productID={$data->id}"), $data->name, "target='_blank'"));
            return array('result' => 'fail', 'error' => $error);
        }

        return array('result' => 'success');
    }

    /**
     * Get product line list.
     * 
     * @access public
     * @return array 
     */
    public function getLines()
    {
        return $this->dao->select('id, name')->from(TABLE_CATEGORY)->where('type')->eq('product')->andWhere('grade')->eq(1)->fetchPairs();
    }
}
