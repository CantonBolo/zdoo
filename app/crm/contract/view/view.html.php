<?php
/**
 * The view view file of contract module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     contract
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/kindeditor.html.php';?>
<ul id='menuTitle'>
  <li><?php commonModel::printLink('contract', 'browse', '', $lang->contract->list);?></li>
  <li class='divider angle'></li>
  <li class='title'><?php echo $contract->name;?></li>
</ul>
<div class="row-table">
  <div class='col-main'>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->contract->items;?></strong></div>
      <div class='panel-body'>
        <?php echo $contract->items;?>
        <div><?php echo $this->fetch('file', 'printFiles', array('files' => $contract->files, 'fieldset' => 'false'))?></div>
      </div>
    </div>
    <?php if(!empty($contract->tradeList)):?>
    <div class='panel'>
      <table class='table table-condensed table-hover table-striped table-fixed'>
        <thead>
          <tr class='text-center'>
            <th class='w-80px'><?php echo $lang->trade->date;?></th>
            <th class='w-100px'><?php echo $lang->trade->depositor;?></th>
            <th class='w-60px'><?php echo $lang->trade->type;?></th>
            <th class='w-140px'><?php echo $lang->trade->trader;?></th>
            <th class='w-60px'><?php echo $lang->trade->money;?></th>
            <th class='w-80px'><?php echo $lang->trade->dept;?></th>
            <th class='w-80px'><?php echo $lang->trade->handlers;?></th>
            <th class='w-100px'><?php echo $lang->trade->product;?></th>
            <th class='w-100px'><?php echo $lang->trade->category;?></th>
            <th class='desc'><?php echo $lang->trade->desc;?></th>
          </tr>
        </thead>
        <?php foreach($contract->tradeList as $trade):?>
        <tr class='text-center text-middle'>
          <?php 
          $depositor = zget($depositorList, $trade->depositor, '');
          $customer  = zget($allCustomers, $trade->trader, '');
          $dept      = zget($deptList, $trade->dept, '');
          $handlers  = '';
          foreach(explode(',', $trade->handlers) as $handler) $handlers .= zget($users, $handler) . ' ';
          $product   = zget($products, $trade->product, '');
          $category  = zget($categories, $trade->category, '');
          ?>
          <td><?php echo formatTime($trade->date, DT_DATE1);?></td>
          <td class='text-left text-ellipsis' title='<?php echo $depositor;?>'><?php echo $depositor;?></td>
          <td><?php echo $lang->trade->typeList[$trade->type];?></td>
          <td class='text-left text-ellipsis' title='<?php echo $customer;?>'><?php echo $customer;?></td>
          <td class='text-right'><?php echo zget($currencySign, $trade->currency) . formatMoney($trade->money);?></td>
          <td class='text-ellipsis' title='<?php echo $dept;?>'><?php echo $dept;?></td>
          <td class='text-left text-ellipsis' title='<?php echo $handlers;?>'><?php echo $handlers;?></td>
          <td class='text-left text-ellipsis' title='<?php echo $product;?>'><?php echo $product;?></td>
          <td class='text-left text-ellipsis' title='<?php echo $category;?>'><?php echo $category;?></td>
          <td class='text-left'><div title="<?php echo $trade->desc;?>" class='text-ellipsis'><?php echo $trade->desc;?><div></td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
    <?php endif;?>
    <?php echo $this->fetch('action', 'history', "objectType=contract&objectID={$contract->id}")?>
    <div class='page-actions'>
      <?php
      echo $this->contract->buildOperateMenu($contract, 'btn', 'view');

      $browseLink = $this->session->contractList ? $this->session->contractList : inlink('browse');
      commonModel::printRPN($browseLink, $preAndNext);
      ?>
    </div>
    <fieldset id='commentBox' class='hide'>
      <legend><?php echo $lang->comment;?></legend>
      <form id='ajaxForm' method='post' action='<?php echo inlink('edit', "contractID={$contract->id}&comment=true")?>'>
        <div class='form-group'><?php echo html::textarea('remark', '',"rows='5' class='w-p100'");?></div>
        <?php echo html::submitButton();?>
      </form>
    </fieldset>      
  </div>
  <div class='col-side'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->basicInfo;?></strong>
      </div>
      <div class='panel-body'>
        <table class='table table-info'>
          <tr>
            <th class='w-80px'><?php echo $lang->contract->code;?></th>
            <td><?php echo $contract->code;?></td>
          </tr>
          <tr>
            <th class='w-80px'><?php echo $lang->contract->customer;?></th>
            <td><?php echo html::a($this->createLink('customer', 'view', "customerID={$contract->customer}"), zget($customers, $contract->customer));?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->order;?></th>
            <td>
              <?php foreach($orders as $order):?>
              <div><?php echo html::a($this->createLink('order', 'view', "orderID={$order->id}"), $order->title);?></div>
              <?php endforeach;?>
            </td>
          </tr>
          <?php $contractProducts = explode(',', trim($contract->product, ','));?>
          <?php if(!empty($contractProducts)):?>
          <tr>
            <th><?php echo $lang->order->product;?></th>
            <td>
              <?php foreach($contractProducts as $productID):?>
              <span><?php echo zget($products, $productID, '');?> </span>
              <?php endforeach;?>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->contract->amount;?></th>
            <td><?php echo zget($currencySign, $contract->currency, '') . formatMoney($contract->amount);?></td>
          </tr>
          <tr>
            <th class='w-70px'><?php echo $lang->contract->delivery;?></th>
            <td><?php echo $lang->contract->deliveryList[$contract->delivery];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->return;?></th>
            <td><?php echo $lang->contract->returnList[$contract->return];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->status;?></th>
            <td><?php echo $lang->contract->statusList[$contract->status];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->contact;?></th>
            <td><?php if(isset($contacts[$contract->contact]) and trim($contacts[$contract->contact]) != "") echo html::a($this->createLink('contact', 'view', "contactID={$contract->contact}"), $contacts[$contract->contact]);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->address;?></th>
            <td><?php echo zget($addresses, $contract->address, '');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->begin;?></th>
            <td><?php echo $contract->begin;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->end;?></th>
            <td><?php echo $contract->end;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->handlers;?></th>
            <td>
              <?php
              foreach(explode(',', $contract->handlers) as $handler)
              {
                  if($handler and isset($users[$handler])) echo $users[$handler] . ' ';
              }
              ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <?php if($members):?>
    <div class='panel'>
      <table id='memberTable' class='table table-data table-condensed table-fixed'>
        <tr class='text-center'>
          <th><?php echo $lang->contract->team->account;?></th>
          <th><?php echo $lang->contract->team->contribution;?></th>
          <th class='w-90px'><?php echo $lang->contract->team->status;?></th>
        </tr>
        <?php foreach($members as $member):?>
        <tr class='text-center'>
          <td><?php echo zget($users, $member->account);?></td>
          <td><?php echo $member->contribution == 0 ? '' : $member->contribution;?></td>
          <td class='team-<?php echo $member->status;?>'>
            <?php
            $status     = zget($lang->contract->team->statusList, $member->status);
            $canConfirm = $member->account == $this->app->user->account && commonModel::hasPriv('crm.contract', 'confirmTeam');
            if($member->status == 'wait' && $canConfirm)
            {
                echo html::a(inlink('confirmTeam', "contractID={$contract->id}&status=accept"), $lang->contract->team->accept, "class='btn btn-xs jsoner'");
                echo html::a(inlink('confirmTeam', "contractID={$contract->id}&status=reject"), $lang->contract->team->reject, "class='btn btn-xs jsoner'");
            }
            elseif($member->status == 'reject' && $canConfirm)
            {
                echo html::a(inlink('confirmTeam', "contractID={$contract->id}"), $status);
            }
            else
            {
                echo $status;
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
    <?php endif;?>
    <?php if(!empty($contract->returnList)):?>
    <div class='panel'>
      <table class='table table-data table-condensed table-fixed'>
        <tr>
          <th class='w-80px'><?php echo $lang->contract->returnedDate;?></th>
          <th class='w-80px'><?php echo $lang->contract->returnedBy;?></th> 
          <th><?php echo $lang->contract->amount;?></th>
          <th class='w-50px'><?php echo $lang->actions;?></th>
        </tr>
        <?php foreach($contract->returnList as $return):?>
        <tr>
          <td><?php echo $return->returnedDate;?></td>
          <td><?php echo zget($users, $return->returnedBy, $return->returnedBy);?></td>
          <td><?php echo zget($currencySign, $contract->currency, '') . formatMoney($return->amount);?></td>
          <td class='text-center'>
            <?php commonModel::printLink('contract', 'editReturn', "id=$return->id", "<i class='icon-pencil'></i>", "data-toggle='modal' title='{$lang->edit}'");?>
            <?php commonModel::printLink('contract', 'deleteReturn', "id=$return->id", "<i class='icon-remove'></i>", "class='deleter' title='{$lang->delete}'");?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
    <?php endif;?>
    <?php if(!empty($contract->deliveryList)):?>
    <div class='panel'>
      <table class='table table-data table-condensed table-fixed'>
        <tr>
          <th class='w-80px'><?php echo $lang->contract->deliveredDate;?></th>
          <th class='w-80px'><?php echo $lang->contract->deliveredBy;?></th> 
          <th><?php echo $lang->comment;?></th>
          <th class='w-50px'><?php echo $lang->actions;?></th>
        </tr>
        <?php foreach($contract->deliveryList as $delivery):?>
        <tr>
          <td><?php echo $delivery->deliveredDate;?></td>
          <td><?php echo zget($users, $delivery->deliveredBy, $delivery->deliveredBy);?></td>
          <td title='<?php echo $delivery->comment;?>'><?php echo $delivery->comment;?></td>
          <td class='text-center'>
            <?php commonModel::printLink('contract', 'editDelivery', "id=$delivery->id", "<i class='icon-pencil'></i>", "data-toggle='modal' title='{$lang->edit}'");?>
            <?php commonModel::printLink('contract', 'deleteDelivery', "id=$delivery->id", "<i class='icon-remove'></i>", "class='deleter' title='{$lang->delete}'");?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
    <?php endif;?>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->contract->lifetime;?></strong>
      </div>
      <div class='panel-body'>
        <table class='table table-info' id='contractLife'>
          <tr>
            <th class='w-70px'><?php echo $lang->contract->createdBy;?></th>
            <td><?php echo zget($users, $contract->createdBy, $contract->createdBy) . $lang->at . $contract->createdDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->signedBy;?></th>
            <td><?php if($contract->signedBy) echo zget($users, $contract->signedBy, $contract->signedBy) . $lang->at . $contract->signedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->deliveredBy;?></th>
            <td><?php if($contract->deliveredBy) echo zget($users, $contract->deliveredBy, $contract->deliveredBy) . $lang->at . $contract->deliveredDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->returnedBy;?></th>
            <td><?php if($contract->returnedBy) echo zget($users, $contract->returnedBy, $contract->returnedBy) . $lang->at . $contract->returnedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->finishedBy;?></th>
            <td><?php if($contract->finishedBy) echo zget($users, $contract->finishedBy, $contract->finishedBy) . $lang->at . $contract->finishedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->canceledBy;?></th>
            <td><?php if($contract->canceledBy) echo zget($users, $contract->canceledBy, $contract->canceledBy) . $lang->at . $contract->canceledDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->contract->editedBy;?></th>
            <td><?php if($contract->editedBy) echo zget($users, $contract->editedBy, $contract->editedBy) . $lang->at . $contract->editedDate;?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
<?php helper::import('../js/team.js');?>
</script>
<?php include '../../common/view/footer.html.php';?>
