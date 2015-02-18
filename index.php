<?php
define('_INDEX_', true);
include_once('./_common.php');

// 초기화면 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_index']) {
    if (!@include_once($config['cf_include_index'])) {
        die('기본환경 설정에서 초기화면 파일 경로가 잘못 설정되어 있습니다.');
    }
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/index.php');
    return;
}

include_once('./_head.php');
?>

<style>
.item { margin:0 0 10px 0; }
</style>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?php
$sql = " select * from {$g5['menu_table']} ";
$sql.= "  where me_link like '%bo_table%' ";
$sql.= "  order by me_order, me_code ";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    preg_match("/bo_table=([0-9a-zA-Z]+)&/", $row['me_link'].'&', $match);
    $bo_table = $match[1];

    if (!$bo_table) continue;
    ?>
    <tr>
        <td width="350" valign="top">
            <div class="item"><?php echo latest("mw.list", $bo_table, 5, 30, 0)?></div>
        </td>
        <td width="10"></td>
        <td width="345" valign="top">
            <?php
            $row = sql_fetch_array($qry);
            preg_match("/bo_table=([0-9a-zA-Z]+)&/", $row['me_link'].'&', $match);
            $bo_table = $match[1];
            if ($bo_table) {
            ?>
            <div class="item"><?php echo latest("mw.list", $bo_table, 5, 30, 0)?></div>
            <?php } ?>
        </td>
    </tr>
<?php } ?>
</table>

<?php
include_once('./_tail.php');
