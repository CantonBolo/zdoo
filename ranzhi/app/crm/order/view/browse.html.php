<?php 
/**
 * The browse view file of order module of RanZhi.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     LGPL
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     order 
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
  <strong><i class="icon-list-ul"></i> <?php echo $lang->order->list;?></strong>
  <div class='panel-actions pull-right'><?php echo html::a($this->inlink('create'), '<i class="icon-plus"></i> ' . $lang->order->create, 'class="btn btn-primary"');?></div>
  </div>
  <table class='table table-hover table-striped tablesorter table-data'>
    <thead>
      <tr>
        <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='w-60px text-center' ><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->order->id);?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('customer', $orderBy, $vars, $lang->order->customer);?></th>
        <th class='w-40px'><?php echo $lang->customer->level;?></th>
        <th><?php commonModel::printOrderLink('product', $orderBy, $vars, $lang->order->product);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('plan', $orderBy, $vars, $lang->order->plan);?>
        <th class='w-120px'><?php commonModel::printOrderLink('real', $orderBy, $vars, $lang->order->real);?>
        <th class='w-120px'><?php commonModel::printOrderLink('assignedTo', $orderBy, $vars, $lang->order->assignedTo);?></th>
        <th class='w-60px' ><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->order->status);?></th>
        <th class='w-100px' ><?php commonModel::printOrderLink('contactedDate', $orderBy, $vars, $lang->order->contactedDate);?></th>
        <th class='w-100px' ><?php commonModel::printOrderLink('nextDate', $orderBy, $vars, $lang->order->nextDate);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->order->createdBy);?></th>
        <th class='w-160px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($orders as $order):?>
      <tr data-url='<?php echo $this->createLink('order', 'view', "orderID=$order->id"); ?>'>
        <td class='text-center'><?php echo $order->id;?></td>
        <td><?php echo $customers[$order->customer]->name;?></td>
        <td class='text-center'><?php echo $customers[$order->customer]->level;?></td>
        <td><?php echo $products[$order->product];?></td>
        <td><?php echo $order->plan;?></td>
        <td><?php echo $order->real;?></td>
        <td><?php echo $users[$order->assignedTo];?></td>
        <td><?php echo isset($lang->order->statusList[$order->status]) ? $lang->order->statusList[$order->status] : $order->status;?></td>
        <td><?php echo substr($order->contactedDate, 0, 10);?></td>
        <td><?php echo $order->nextDate;?></td>
        <td><?php echo $users[$order->createdBy];?></td>
        <td class='actions'>
          <?php echo $this->order->buildOperateMenu($order); ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot><tr><td colspan='12'><?php $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
