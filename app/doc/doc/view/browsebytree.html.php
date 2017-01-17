<style>
.tree .doc-actions {display: none;}
.tree li:hover > .doc-actions {display: inline-block; margin-left:10px;}
</style>
<?php $this->doc->setMenu($lib->project, $lib->id, $moduleID);?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div id='menuActions'>
  <div class='btn-group'>
    <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><i class='icon icon-branch'></i> <?php echo $lang->doc->browseTypeList['tree']?> <span class='caret'></span></button>
    <ul class='dropdown-menu' role='menu'>
      <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
      <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
      <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-branch'></i> {$lang->doc->browseTypeList['tree']}");?></li>
    </ul>
  </div>
  <?php commonModel::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&projectID=$projectID", '<i class="icon-plus"></i> ' . $lang->doc->create, 'class="btn btn-primary"');?>
</div>
<div class='row with-menu page-content'>
  <div class='panel'>
    <div class='panel-body'>
      <?php if($tree):?>
      <?php echo $tree;?>
      <?php else:?>
      <?php echo $lang->pager->noRecord;?>
      <?php commonModel::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&projectID=$projectID", $lang->doc->create);?>
      <?php endif;?>
    </div>
  </div>
</div>

<script>
$(function()
{
    $('.hitarea').click(function()
    {
        $(this).parent('li').find('.icon').toggleClass('icon-folder-open-alt');
        $(this).parent('li').find('.icon').toggleClass('icon-folder-close-alt');
    });
});
</script>
