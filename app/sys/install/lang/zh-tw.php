<?php
/**
 * The zh-tw file of install module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install 
 * @version     $Id: zh-tw.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhi.org
 */
$lang->install = new stdclass();
$lang->install->common  = '安裝';
$lang->install->next    = '下一步';
$lang->install->pre     = '返回';
$lang->install->reload  = '刷新';
$lang->install->error   = '錯誤 ';

$lang->install->start            = '開始安裝';
$lang->install->keepInstalling   = '繼續安裝當前版本';
$lang->install->seeLatestRelease = '看看最新的版本';
$lang->install->welcome          = "您睿智地選擇了$lang->ranzhi!";
$lang->install->license          = '然之協同使用 Z PUBLIC LICENSE(ZPL) 1.2 授權協議。';
$lang->install->desc             = <<<EOT
<blockquote>
  <strong>{$lang->ranzhi}</strong>由<strong><a href='http://www.cnezsoft.com' target='_blank' class='red'>青島易軟天創網絡科技有限公司</a>開發</strong>，
  <!--內置項目、客戶、現金流、辦公和溝通共五大核心功能模組，-->
  專為中小型團隊量身打造，是中小型團隊信息化的首選工具！

  官方網站：<a href='http://www.ranzhi.org' target='_blank'>http://www.ranzhi.org</a>
  技術支持: <a href='http://www.ranzhi.org/forum/' target='_blank'>http://www.ranzhi.org/forum/</a>
  您現在正在安裝的版本是 <strong class='red'>%s</strong>。
</blockquote>
EOT;

$lang->install->choice     = '您可以選擇：';
$lang->install->checking   = '系統檢查';
$lang->install->ok         = '通過(√)';
$lang->install->fail       = '失敗(×)';
$lang->install->loaded     = '已加載';
$lang->install->unloaded   = '未加載';
$lang->install->exists     = '目錄存在 ';
$lang->install->notExists  = '目錄不存在 ';
$lang->install->writable   = '目錄可寫 ';
$lang->install->notWritable= '目錄不可寫 ';
$lang->install->phpINI     = 'PHP配置檔案';
$lang->install->checkItem  = '檢查項';
$lang->install->current    = '當前配置';
$lang->install->result     = '檢查結果';
$lang->install->action     = '如何修改';

$lang->install->phpVersion = 'PHP版本';
$lang->install->phpFail    = 'PHP版本必須大於5.2.0';

$lang->install->pdo          = 'PDO擴展';
$lang->install->pdoFail      = '修改PHP配置檔案，加載PDO擴展。';
$lang->install->pdoMySQL     = 'PDO_MySQL擴展';
$lang->install->pdoMySQLFail = '修改PHP配置檔案，加載pdo_mysql擴展。';
$lang->install->tmpRoot      = '臨時檔案目錄';
$lang->install->dataRoot     = '上傳檔案目錄';
$lang->install->sessionRoot  = 'session目錄';
$lang->install->mkdir        = '<p>需要創建目錄%s。linux下面命令為：<br /> <code>mkdir -p %s</code></p>';
$lang->install->chmod        = '需要修改目錄 "%s" 的權限。linux下面命令為：<br /><code>chmod o=rwx -R %s</code>';
$lang->install->sessionChmod = '需要修改目錄 "%s" 的權限。linux下面命令為：<br /><code>sudo chmod o=wtx %s</code>';

$lang->install->settingDB  = '設置資料庫';
$lang->install->dbHost     = '資料庫伺服器';
$lang->install->dbHostNote = '如果127.0.0.1無法訪問，嘗試使用localhost';
$lang->install->dbPort     = '伺服器連接埠';
$lang->install->dbUser     = '資料庫用戶名';
$lang->install->dbPassword = '資料庫密碼';
$lang->install->dbName     = '資料庫名';
$lang->install->dbPrefix   = '建表使用的首碼';
$lang->install->createDB   = '自動創建資料庫';
$lang->install->clearDB    = '清空現有數據';

$lang->install->errorDBName        = "資料庫名不能帶'.'";
$lang->install->errorConnectDB     = '資料庫連接失敗。 ';
$lang->install->errorCreateDB      = '資料庫創建失敗。';
$lang->install->errorDBExists      = '資料庫已經存在，繼續安裝返回上步並選中“清空數據”選項。';
$lang->install->errorCreateTable   = '創建表失敗。';

$lang->install->setConfig  = '資料庫配置';
$lang->install->key        = '配置項';
$lang->install->value      = '值';
$lang->install->saveConfig = '保存配置檔案';
$lang->install->save2File  = '<span class="red">嘗試寫入配置檔案，失敗！</span>拷貝上面文本框中的內容，將其保存到 "<strong> %s </strong>"中。';
$lang->install->saved2File = '配置信息已經成功保存到" <strong>%s</strong> "中。您後面還可繼續修改此檔案。';
$lang->install->errorNotSaveConfig = '還沒有保存配置檔案';

$lang->install->success  = "安裝成功！";
$lang->install->domainIP = '域名映射的IP是：%s';
$lang->install->serverIP = '伺服器的內網IP是：%s';
$lang->install->publicIP = '伺服器的公網IP是：%s';
$lang->install->setAdmin = '設置管理員';
$lang->install->account  = '帳號';
$lang->install->password = '密碼';
$lang->install->errorEmptyPassword = '密碼不能為空';

$lang->install->import['area']     = '導入區域數據';
$lang->install->import['industry'] = '導入行業數據';

$lang->install->buildinEntry = new stdclass();
$lang->install->buildinEntry->crm['name']  = '客戶管理';
$lang->install->buildinEntry->crm['abbr']  = '客戶';
$lang->install->buildinEntry->cash['name'] = '現金記賬';
$lang->install->buildinEntry->cash['abbr'] = '記賬';
$lang->install->buildinEntry->oa['name']   = '日常辦公';
$lang->install->buildinEntry->oa['abbr']   = '辦公';
$lang->install->buildinEntry->team['name'] = '團隊';
$lang->install->buildinEntry->team['abbr'] = '團隊';
$lang->install->buildinEntry->doc['name']  = '文檔';
$lang->install->buildinEntry->doc['abbr']  = '文檔';
$lang->install->buildinEntry->proj['name'] = '項目';
$lang->install->buildinEntry->proj['abbr'] = '項目';

$lang->install->categoryList[1] = '主營業務收入';
$lang->install->categoryList[2] = '非主營業務收入';
$lang->install->categoryList[3] = '主營業務成本';
$lang->install->categoryList[4] = '非主營業務成本';
$lang->install->categoryList[5] = '理財盈利';
$lang->install->categoryList[6] = '理財虧損';
$lang->install->categoryList[7] = '手續費';
$lang->install->categoryList[8] = '借貸利息';

$lang->install->schemaList[1] = '支付寶';
$lang->install->schemaList[2] = '中信銀行簡版';

$lang->install->cronList[1] = '監控定時任務';
$lang->install->cronList[2] = '定時備份任務';
$lang->install->cronList[3] = '刪除30天前的備份';
$lang->install->cronList[4] = '每日提醒';
$lang->install->cronList[5] = '出隊列';
$lang->install->cronList[6] = '檢查是否添加待辦';

$lang->install->groupList[1]['name'] = '管理員';
$lang->install->groupList[1]['desc'] = '管理員擁有前台所有權限';
$lang->install->groupList[2]['name'] = '財務專員';
$lang->install->groupList[2]['desc'] = '財務專員擁有現金流所有權限';
$lang->install->groupList[3]['name'] = '銷售經理';
$lang->install->groupList[3]['desc'] = '銷售經理擁有客戶管理所有權限';
$lang->install->groupList[4]['name'] = '銷售人員';
$lang->install->groupList[4]['desc'] = '預設的銷售人員權限';
$lang->install->groupList[5]['name'] = '普通用戶';
$lang->install->groupList[5]['desc'] = '預設的普通賬號權限';
