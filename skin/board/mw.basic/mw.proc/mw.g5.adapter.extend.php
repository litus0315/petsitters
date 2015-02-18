<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!defined("G5_PATH")) return;

$g4 = $g5;

$g4['path'] = '';
for ($i=0, $m=substr_count(dirname(str_replace(G5_PATH, '', $_SERVER['SCRIPT_FILENAME'])), '/'); $i<$m; ++$i) {
    $g4['path'] .= '../';
}
$g4['path']             = substr($g4['path'], 0, strlen($g4['path'])-1);
$g4['url']              = G5_URL;

$g4['skin_path']        = $g4['path'].'/'.G5_SKIN_DIR;
$g4['bbs']              = G5_BBS_DIR;
$g4['bbs_path']         = $g4['path'].'/'.G5_BBS_DIR;

$g4['server_time']      = time();
$g4['time_ymd']         = date("Y-m-d", $g4['server_time']);
$g4['time_his']         = date("H:i:s", $g4['server_time']);
$g4['time_ymdhis']      = date("Y-m-d H:i:s", $g4['server_time']);
$g4['charset']          = "utf-8";

$g4['admin_path']       = $g4['path'].'/'.G5_ADMIN_DIR;
$g4['link_count']       = G5_LINK_COUNT;

if (G5_IS_MOBILE) {
    $board_skin_path    = $g4['path'].'/board/'.$board['bo_mobile_skin'];
}
else {
    $board_skin_path    = $g4['skin_path'].'/board/'.$board['bo_skin'];
    $member_skin_path   = $g4['skin_path'].'/member/'.$config['cf_member_skin'];
    $new_skin_path      = $g4['skin_path'].'/new/'.$config['cf_new_skin'];
    $search_skin_path   = $g4['skin_path'].'/search/'.$config['cf_search_skin'];
    $connect_skin_path  = $g4['skin_path'].'/connect/'.$config['cf_connect_skin'];
    $faq_skin_path      = $g4['skin_path'].'/faq/'.$config['cf_faq_skin'];
}

$g4['bbs_img_path']     = $board_skin_path."/bbs-img";
