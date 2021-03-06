<?php
/**
 * The zh-cn file of install module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install 
 * @version     $Id: zh-cn.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhi.org
 */
$lang->install = new stdclass();
$lang->install->common  = '安装';
$lang->install->next    = '下一步';
$lang->install->pre     = '返回';
$lang->install->reload  = '刷新';
$lang->install->error   = '错误 ';

$lang->install->start            = '开始安装';
$lang->install->keepInstalling   = '继续安装当前版本';
$lang->install->seeLatestRelease = '看看最新的版本';
$lang->install->welcome          = "您睿智地选择了$lang->ranzhi!";
$lang->install->license          = '然之协同使用 Z PUBLIC LICENSE(ZPL) 1.2 授权协议。';
$lang->install->desc             = <<<EOT
<blockquote>
  <strong>{$lang->ranzhi}</strong>由<strong><a href='http://www.cnezsoft.com' target='_blank' class='red'>青岛易软天创网络科技有限公司</a>开发</strong>，
  <!--内置项目、客户、现金流、办公和沟通共五大核心功能模块，-->
  专为中小型团队量身打造，是中小型团队信息化的首选工具！

  官方网站：<a href='http://www.ranzhi.org' target='_blank'>http://www.ranzhi.org</a>
  技术支持: <a href='http://www.ranzhi.org/forum/' target='_blank'>http://www.ranzhi.org/forum/</a>
  您现在正在安装的版本是 <strong class='red'>%s</strong>。
</blockquote>
EOT;

$lang->install->choice     = '您可以选择：';
$lang->install->checking   = '系统检查';
$lang->install->ok         = '通过(√)';
$lang->install->fail       = '失败(×)';
$lang->install->loaded     = '已加载';
$lang->install->unloaded   = '未加载';
$lang->install->exists     = '目录存在 ';
$lang->install->notExists  = '目录不存在 ';
$lang->install->writable   = '目录可写 ';
$lang->install->notWritable= '目录不可写 ';
$lang->install->phpINI     = 'PHP配置文件';
$lang->install->checkItem  = '检查项';
$lang->install->current    = '当前配置';
$lang->install->result     = '检查结果';
$lang->install->action     = '如何修改';

$lang->install->phpVersion = 'PHP版本';
$lang->install->phpFail    = 'PHP版本必须大于5.2.0';

$lang->install->pdo          = 'PDO扩展';
$lang->install->pdoFail      = '修改PHP配置文件，加载PDO扩展。';
$lang->install->pdoMySQL     = 'PDO_MySQL扩展';
$lang->install->pdoMySQLFail = '修改PHP配置文件，加载pdo_mysql扩展。';
$lang->install->tmpRoot      = '临时文件目录';
$lang->install->dataRoot     = '上传文件目录';
$lang->install->sessionRoot  = 'session目录';
$lang->install->mkdir        = '<p>需要创建目录%s。linux下面命令为：<br /> <code>mkdir -p %s</code></p>';
$lang->install->chmod        = '需要修改目录 "%s" 的权限。linux下面命令为：<br /><code>chmod o=rwx -R %s</code>';
$lang->install->sessionChmod = '需要修改目录 "%s" 的权限。linux下面命令为：<br /><code>sudo chmod o=wtx %s</code>';

$lang->install->settingDB  = '设置数据库';
$lang->install->dbHost     = '数据库服务器';
$lang->install->dbHostNote = '如果127.0.0.1无法访问，尝试使用localhost';
$lang->install->dbPort     = '服务器端口';
$lang->install->dbUser     = '数据库用户名';
$lang->install->dbPassword = '数据库密码';
$lang->install->dbName     = '数据库名';
$lang->install->dbPrefix   = '建表使用的前缀';
$lang->install->createDB   = '自动创建数据库';
$lang->install->clearDB    = '清空现有数据';

$lang->install->errorDBName        = "数据库名不能带'.'";
$lang->install->errorConnectDB     = '数据库连接失败。 ';
$lang->install->errorCreateDB      = '数据库创建失败。';
$lang->install->errorDBExists      = '数据库已经存在，继续安装返回上步并选中“清空数据”选项。';
$lang->install->errorCreateTable   = '创建表失败。';

$lang->install->setConfig  = '数据库配置';
$lang->install->key        = '配置项';
$lang->install->value      = '值';
$lang->install->saveConfig = '保存配置文件';
$lang->install->save2File  = '<span class="red">尝试写入配置文件，失败！</span>拷贝上面文本框中的内容，将其保存到 "<strong> %s </strong>"中。';
$lang->install->saved2File = '配置信息已经成功保存到" <strong>%s</strong> "中。您后面还可继续修改此文件。';
$lang->install->errorNotSaveConfig = '还没有保存配置文件';

$lang->install->success  = "安装成功！";
$lang->install->domainIP = '域名映射的IP是：%s';
$lang->install->serverIP = '服务器的内网IP是：%s';
$lang->install->publicIP = '服务器的公网IP是：%s';
$lang->install->setAdmin = '设置管理员';
$lang->install->account  = '帐号';
$lang->install->password = '密码';
$lang->install->errorEmptyPassword = '密码不能为空';

$lang->install->import['area']     = '导入区域数据';
$lang->install->import['industry'] = '导入行业数据';

$lang->install->buildinEntry = new stdclass();
$lang->install->buildinEntry->crm['name']  = '客户管理';
$lang->install->buildinEntry->crm['abbr']  = '客户';
$lang->install->buildinEntry->cash['name'] = '现金记账';
$lang->install->buildinEntry->cash['abbr'] = '记账';
$lang->install->buildinEntry->oa['name']   = '日常办公';
$lang->install->buildinEntry->oa['abbr']   = '办公';
$lang->install->buildinEntry->team['name'] = '团队';
$lang->install->buildinEntry->team['abbr'] = '团队';
$lang->install->buildinEntry->doc['name']  = '文档';
$lang->install->buildinEntry->doc['abbr']  = '文档';
$lang->install->buildinEntry->proj['name'] = '项目';
$lang->install->buildinEntry->proj['abbr'] = '项目';

$lang->install->categoryList[1] = '主营业务收入';
$lang->install->categoryList[2] = '非主营业务收入';
$lang->install->categoryList[3] = '主营业务成本';
$lang->install->categoryList[4] = '非主营业务成本';
$lang->install->categoryList[5] = '理财盈利';
$lang->install->categoryList[6] = '理财亏损';
$lang->install->categoryList[7] = '手续费';
$lang->install->categoryList[8] = '借贷利息';

$lang->install->schemaList[1] = '支付宝';
$lang->install->schemaList[2] = '中信银行简版';

$lang->install->cronList[1] = '监控定时任务';
$lang->install->cronList[2] = '定时备份任务';
$lang->install->cronList[3] = '删除30天前的备份';
$lang->install->cronList[4] = '每日提醒';
$lang->install->cronList[5] = '出队列';
$lang->install->cronList[6] = '检查是否添加待办';

$lang->install->groupList[1]['name'] = '管理员';
$lang->install->groupList[1]['desc'] = '管理员拥有前台所有权限';
$lang->install->groupList[2]['name'] = '财务专员';
$lang->install->groupList[2]['desc'] = '财务专员拥有现金流所有权限';
$lang->install->groupList[3]['name'] = '销售经理';
$lang->install->groupList[3]['desc'] = '销售经理拥有客户管理所有权限';
$lang->install->groupList[4]['name'] = '销售人员';
$lang->install->groupList[4]['desc'] = '默认的销售人员权限';
$lang->install->groupList[5]['name'] = '普通用户';
$lang->install->groupList[5]['desc'] = '默认的普通账号权限';
