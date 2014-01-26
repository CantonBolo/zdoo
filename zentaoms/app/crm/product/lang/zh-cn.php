<?php
/**
 * The product category zh-cn file of ZenTaoMS.
 *
 * @copyright   Copyright 2013-2014 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->product->common    = '产品维护';

$lang->product->id          = '编号';
$lang->product->name        = '名称';
$lang->product->code        = '代号';
$lang->product->type        = '类型';
$lang->product->status      = '状态';
$lang->product->summary     = '简介';
$lang->product->roles       = '角色';
$lang->product->createdBy   = '添加者';
$lang->product->createdDate = '添加时间';
$lang->product->editedDate  = '编辑时间';
$lang->product->editedBy    = '编辑者';

$lang->product->list        = '产品列表';
$lang->product->browse      = '维护产品';
$lang->product->create      = '发布产品';

$lang->product->typeList['real']    = '实体类';
$lang->product->typeList['service'] = '服务类';
$lang->product->typeList['virtual'] = '虚拟类';

$lang->product->statusList['wait']    = '制作中';
$lang->product->statusList['normal']  = '正常';
$lang->product->statusList['offline'] = '下线';

$lang->product->field  = new stdclass();
$lang->product->field->name        = '名称';
$lang->product->field->field       = '字段名';
$lang->product->field->options     = '可选值';
$lang->product->field->control     = '控件';
$lang->product->field->default     = '默认值';
$lang->product->field->rules       = '验证规则';
$lang->product->field->desc        = '描述';
$lang->product->field->placeholder = '提示文字';

$lang->product->field->admin  = '属性';
$lang->product->field->create = '添加属性';

$lang->product->field->controlTypeList = array();
$lang->product->field->controlTypeList['input']    = '文本框';
$lang->product->field->controlTypeList['textarea'] = '富文本';
$lang->product->field->controlTypeList['date']     = '日期';
$lang->product->field->controlTypeList['datetime'] = '时间';
$lang->product->field->controlTypeList['select']   = '下拉菜单';
$lang->product->field->controlTypeList['radio']    = '单选按钮';
$lang->product->field->controlTypeList['checkbox'] = '复选框';

$lang->product->field->rulesList = array();
$lang->product->field->rulesList['require'] = '必填';
$lang->product->field->rulesList['unique']  = '唯一';
$lang->product->field->rulesList['date']    = '日期';
$lang->product->field->rulesList['email']   = 'email';
$lang->product->field->rulesList['number']  = '数字';
$lang->product->field->rulesList['phone']   = '电话';
$lang->product->field->rulesList['ip']      = 'IP';

$lang->product->field->optionsPlaceholder = '多个值之间用空格或逗号隔开';
$lang->product->field->lengthNotice       = '该类型字段长度比原来小，修改后可能会造成数据错误！';

$lang->product->action = new stdclass();
$lang->product->action->action     = '动作';
$lang->product->action->name       = '名称';
$lang->product->action->conditions = '条件';
$lang->product->action->param      = '参数';
$lang->product->action->inputs     = '输入';
$lang->product->action->results    = '结果';
$lang->product->action->tasks      = '任务';

$lang->product->action->admin           = '流程';
$lang->product->action->create          = '添加动作';
$lang->product->action->edit            = '编辑动作';
$lang->product->action->adminConditions = '动作条件';

$lang->product->task = new stdclass();
$lang->product->task->role = '角色';
$lang->product->task->date = '时间';
$lang->product->task->name = '名称';
