<?php
if(!isset($lang->attend)) $lang->attend = new stdclass();
$lang->attend->common       = '考勤';
$lang->attend->personal     = '我的考勤';
$lang->attend->department   = '部門考勤';
$lang->attend->company      = '公司考勤';
$lang->attend->detail       = '考勤明細';
$lang->attend->edit         = '補錄';
$lang->attend->edited       = '已補錄';
$lang->attend->leave        = '請假';
$lang->attend->leaved       = '已請假';
$lang->attend->makeup       = '補班';
$lang->attend->makeuped     = '已補班';
$lang->attend->lieu         = '調休';
$lang->attend->lieud        = '已調休';
$lang->attend->trip         = '出差';
$lang->attend->triped       = '已出差';
$lang->attend->egress       = '外出';
$lang->attend->egressed     = '已外出';
$lang->attend->overtime     = '加班';
$lang->attend->overtimed    = '已加班';
$lang->attend->review       = '補錄審核';
$lang->attend->settings     = '設置';
$lang->attend->export       = '導出';
$lang->attend->stat         = '統計';
$lang->attend->saveStat     = '保存考勤統計';
$lang->attend->exportStat   = '導出考勤統計表';
$lang->attend->exportDetail = '導出考勤明細';
$lang->attend->browseReview = '補錄列表';
$lang->attend->batchReview  = '批量審核';
$lang->attend->batchPass    = '批量通過';

$lang->attend->id            = '編號';
$lang->attend->date          = '日期';
$lang->attend->account       = '用戶';
$lang->attend->signIn        = '簽到';
$lang->attend->signOut       = '簽退';
$lang->attend->status        = '狀態';
$lang->attend->ip            = 'IP';
$lang->attend->device        = '設備';
$lang->attend->desc          = '描述';
$lang->attend->dayName       = '星期';
$lang->attend->report        = '考勤表';
$lang->attend->AM            = '上午';
$lang->attend->PM            = '下午';
$lang->attend->ipList        = 'IP列表';
$lang->attend->noAttendUsers = '無需考勤者';
$lang->attend->signInClient  = '簽到途徑';
$lang->attend->rejectReason  = '拒絶原因';

$lang->attend->user          = '用戶';
$lang->attend->begin         = '開始';
$lang->attend->end           = '截至';
$lang->attend->search        = '搜索';

$lang->attend->manualIn     = '簽到時間';
$lang->attend->manualOut    = '簽退時間';
$lang->attend->reason       = '原因';
$lang->attend->reviewStatus = '補錄狀態';
$lang->attend->reviewedBy   = '審核人';
$lang->attend->reviewedDate = '審核時間';
$lang->attend->deserveDays  = '應出勤天數';
$lang->attend->actualDays   = '實際出勤天數';

$lang->attend->clientList['all']     = '所有';
$lang->attend->clientList['desktop'] = '電腦版';

$lang->attend->statusList['normal']   = '正常';
$lang->attend->statusList['late']     = '遲到';
$lang->attend->statusList['early']    = '早退';
$lang->attend->statusList['both']     = '遲到+早退';
$lang->attend->statusList['absent']   = '曠工';
$lang->attend->statusList['leave']    = '請假';
$lang->attend->statusList['makeup']   = '補班';
$lang->attend->statusList['overtime'] = '加班';
$lang->attend->statusList['lieu']     = '調休';
$lang->attend->statusList['trip']     = '出差';
$lang->attend->statusList['egress']   = '外出';
$lang->attend->statusList['rest']     = '休息日';

$lang->attend->abbrStatusList['normal']   = '√';
$lang->attend->abbrStatusList['late']     = '遲';
$lang->attend->abbrStatusList['early']    = '早';
$lang->attend->abbrStatusList['both']     = '遲+早';
$lang->attend->abbrStatusList['absent']   = '曠';
$lang->attend->abbrStatusList['leave']    = '假';
$lang->attend->abbrStatusList['makeup']   = '補';
$lang->attend->abbrStatusList['overtime'] = '加';
$lang->attend->abbrStatusList['lieu']     = '調';
$lang->attend->abbrStatusList['trip']     = '差';
$lang->attend->abbrStatusList['egress']   = '出';
$lang->attend->abbrStatusList['rest']     = '休';

$lang->attend->markStatusList['normal']   = '√';
$lang->attend->markStatusList['late']     = '=';
$lang->attend->markStatusList['early']    = '>';
$lang->attend->markStatusList['both']     = '=>';
$lang->attend->markStatusList['absent']   = 'x';
$lang->attend->markStatusList['leave']    = '!';
$lang->attend->markStatusList['makeup']   = '↑';
$lang->attend->markStatusList['overtime'] = '+';
$lang->attend->markStatusList['lieu']     = '↓';
$lang->attend->markStatusList['trip']     = '$';
$lang->attend->markStatusList['egress']   = '#';
$lang->attend->markStatusList['rest']     = '~';

$lang->attend->reasonList['normal']   = '準點上下班';
$lang->attend->reasonList['leave']    = '請假';
$lang->attend->reasonList['makeup']   = '補班';
$lang->attend->reasonList['overtime'] = '加班';
$lang->attend->reasonList['lieu']     = '調休';
$lang->attend->reasonList['trip']     = '出差';
$lang->attend->reasonList['egress']   = '外出';

$lang->attend->reviewStatusList['wait']   = '等待審核';
$lang->attend->reviewStatusList['pass']   = '通過';
$lang->attend->reviewStatusList['reject'] = '拒絶';

$lang->attend->inSuccess  = '簽到成功';
$lang->attend->inFail     = '簽到失敗';
$lang->attend->outSuccess = '簽退成功';
$lang->attend->outFail    = '簽退失敗';

$lang->attend->signInLimit   = '最晚簽到';
$lang->attend->signOutLimit  = '最早簽退';
$lang->attend->workingDays   = '每週工作天數';
$lang->attend->workingHours  = '每天工作工時';
$lang->attend->mustSignOut   = '必須簽退';
$lang->attend->denied        = '信息訪問受限';
$lang->attend->nodata        = '沒有選擇數據';
$lang->attend->reviewSuccess = '審核成功';

$lang->attend->workingDaysList['5']  = "周一～周五";
$lang->attend->workingDaysList['6']  = "周一～周六";
$lang->attend->workingDaysList['7']  = "周一～周日";
$lang->attend->workingDaysList['12'] = "周日～周四";
$lang->attend->workingDaysList['13'] = "周日～周五";

$lang->attend->mustSignOutList['yes'] = '需要';
$lang->attend->mustSignOutList['no']  = '不需要';

$lang->attend->weeks = array('第一周', '第二周', '第三周', '第四周', '第五周', '第六周');

$lang->attend->notice['today']    = "<p>您今天的考勤狀態為：%s，<a href='%s' %s>去補錄</a>。</p>";
$lang->attend->notice['yestoday'] = "<p>您昨天的考勤狀態為：%s，<a href='%s' %s>去補錄</a>。</p>";
$lang->attend->notice['absent']   = "沒有記錄";

$lang->attend->confirmReview['pass']   = '您確定要執行通過操作嗎？';
$lang->attend->confirmReview['reject'] = '您確定要執行拒絶操作嗎？';

$lang->attend->settings         = '公司考勤設置';
$lang->attend->personalSettings = '個人考勤設置';
$lang->attend->setManager       = '部門經理設置';
$lang->attend->setDept          = '部門設置';

$lang->attend->beginDate = new stdClass();
$lang->attend->beginDate->company  = '公司開始考勤日期';
$lang->attend->beginDate->personal = '個人開始考勤日期';

$lang->attend->note = new stdClass();
$lang->attend->note->ip           = "允許簽到的ip，多個ip用逗號隔開。支持IP段，如192.168.1.*";
$lang->attend->note->allip        = '無限制';
$lang->attend->note->IPDenied     = '簽到IP受限，無法簽到';
$lang->attend->note->beginDate    = '設置開始考勤的日期，在該日期之前不記錄考勤狀態。如果不設置則根據實際數據記錄考勤狀態。預設使用公司開始考勤日期計算考勤狀態，如果設置了個人開始考勤日期則使用個人日期。';
$lang->attend->note->signInClient = '設置為所有時可以通過任意途徑簽到，否則只能通過選定的訪問方式簽到。';

$lang->attend->h = '小時';
$lang->attend->m = '分';
$lang->attend->s = '秒';

$lang->attend->signInClientError = '簽到失敗！已設置只能通過 %s 簽到。';
$lang->attend->waitReviews       = '<strong>%s</strong> 存在未審批的記錄，請審批之後再進行統計。';
