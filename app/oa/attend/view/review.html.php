<?php
/**
 * The review view file of refund module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     refund 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<form method='post' id='ajaxForm' action='<?php echo inlink('review', "id={$attend->id}")?>'>
  <table class='table table-fixed table-bordered'>

    <thead>
    <tr class='text-center'>
      <th class='w-80px'><?php echo $lang->attend->account;?></th>
      <th class='w-80px'><?php echo $lang->attend->date;?></th>
      <th class='w-80px'><?php echo $lang->attend->manualIn;?></th>
      <th class='w-80px'><?php echo $lang->attend->manualOut;?></th>
      <th class='w-90px text-nowrap'><?php echo $lang->attend->desc;?></th>
      <th class='w-160px'></th>
    </tr>
    </thead>

    <tr class='text-center'>
      <td><?php echo zget($users, $attend->account);?></td>
      <td><?php echo $attend->date;?></td>
      <td><?php echo substr($attend->manualIn, 0, 5);?></td>
      <td><?php echo substr($attend->manualOut, 0, 5);?></td>
      <td class='text-ellipsis' title="<?php echo $attend->desc;?>"><?php echo $attend->desc;?></td>
      <td><?php unset($lang->attend->reviewStatusList['wait']); echo html::radio("status", $lang->attend->reviewStatusList, $attend->status == 'reject' ? 'reject' : 'pass');?></td>
    </tr>

  </table>
  <table class='table table-borderless'>
    <tr class='comment'>
      <th class='w-50px text-center text-middle'><?php echo $lang->attend->comment;?></th>
      <td><?php echo html::textarea("comment", '', "class='form-control rowspan=4'");?></td>
      <td class='text-middle'><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../../sys/common/view/footer.modal.html.php';?>