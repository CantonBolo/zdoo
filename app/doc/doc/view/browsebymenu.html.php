<style>
#filesPanel {margin-bottom:0px;}
#filesPanel .panel-footer {position: relative; height: 40px}
#filesPanel .pager {position: absolute; top: 4px; right: 4px}
#filesPanel .file {float: left; width: 90px; position: relative; background-color: transparent; margin: 0 10px 10px 0; border: 1px solid transparent; transition: all .2s; text-align:center;}
#filesPanel .file:hover {border-color: #ddd; background-color: #EBF2F9}
#filesPanel .file > a {display: block; text-decoration: none;}
#filesPanel .file-icon {height: 60px; text-align: center; line-height: 60px; opacity: .7; margin-bottom:5px;}
#filesPanel .file:hover .file-icon {opacity: 1}
#filesPanel .file-name {text-align: center; overflow: hidden; white-space:nowrap; text-overflow: ellipsis;}
#filesPanel .file.create {height:88px; border:1px dashed #ddd; width:75px;}
#filesPanel .file.create .addbtn{display:block;margin-top:22px;padding-top:10px;}
#filesPanel .file.create .file-icon {height:100%; opacity: 0.5; color:#ddd;}
#filesPanel .file.create .icon-plus {font-size: 18px; display: block; opacity: 0.5; transition: opacity .2s; text-shadow: 1px 1px 3px rgba(0,0,0,.2);}
#filesPanel .file.create:hover .icon-plus {opacity: .9; animation: flash-icon 1s linear alternate infinite}
#filesPanel .file.create:hover .file-icon {opacity: .9; animation: flash-icon 1s linear alternate infinite}
</style>
<?php $this->doc->setMenu($lib->project, $lib->id, $moduleID);?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div id='menuActions'>
  <div class='btn-group'>
    <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><i class='icon icon-th'></i> <?php echo $lang->doc->browseTypeList['menu']?> <span class='caret'></span></button>
    <ul class='dropdown-menu' role='menu'>
      <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
      <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
      <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-branch'></i> {$lang->doc->browseTypeList['tree']}");?></li>
    </ul>
  </div>
  <?php commonModel::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&projectID=$projectID", '<i class="icon-plus"></i> ' . $lang->doc->create, 'class="btn btn-primary"');?>
</div>
<div class='row with-menu page-content'>
  <div class='panel' id='filesPanel'>
    <div class='panel-body clearfix'>
      <?php foreach($modules as $module):?>
      <div class='file file-dir'>
        <a href='<?php echo inlink('browse', "libID=$libID&moduleID=$module->id&orderBy=$orderBy")?>'>
          <i class='icon icon-2x icon-folder-open-alt file-icon'></i>
          <div class='file-name' title='<?php echo $module->name?>'><?php echo $module->name?></div>
        </a>
      </div>
      <?php endforeach;?>
      <?php foreach($docs as $doc):?>
      <div class='file'>
        <a href='<?php echo inlink('view', "docID=$doc->id")?>'>
          <i class='icon icon-2x icon-file-text-o file-icon'></i>
          <div class='file-name' title='<?php echo $doc->title;?>'><?php echo $doc->title?></div>
        </a>
      </div>
      <?php endforeach;?>
      <div class='file create'>
          <div class='dropdown'>
            <a href='###' class='addbtn dropdown-toggle' data-toggle='dropdown'> <i class='icon icon-plus'></i></a>
            <ul class='dropdown-menu' role='menu'>
              <?php commonModel::printLink('tree', 'browse', "type=doc&moduleID=0&rootID=$libID", $lang->doc->manageType, '', true, '', 'li');?>
              <?php commonModel::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&projectID=$projectID", $lang->doc->create, '', true, '', 'li');?>
            </ul>
          </div>
      </div>
    </div>
    <?php if($docs):?>
    <div class='panel-footer'><?php $pager->show();?></div>
    <?php endif;?>
  </div>
</div>
