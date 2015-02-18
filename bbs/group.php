<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
$g5['title'] = $group['gr_subject'];

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/group.php');
    return;
}

if(!$is_admin && $group['gr_device'] == 'mobile')
    alert($group['gr_subject'].' 그룹은 모바일에서만 접근할 수 있습니다.');

include_once('./_head.php');
?>


<style>
.item { margin:0 0 10px 0; }
</style>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
$sql = " select bo_table, bo_subject
            from {$g5[board_table]}
            where gr_id = '{$gr_id}'
              and bo_list_level <= '{$member[mb_level]}'
              and bo_device <> 'mobile' ";
if(!$is_admin)
    $sql .= " and bo_use_cert = '' ";
$sql .= " order by bo_order ";

$qry = sql_query($sql);
for ($i=0; $row=sql_fetch_array($qry); $i++) {
?>
<tr>
    <td width="350" valign="top">
        <div class="item"><?php echo latest("mw.list", $row['bo_table'], 5, 45)?></div>
    </td>
    <td width="10"></td>
    <td width="345" valign="top">
        <?php
        $row = sql_fetch_array($qry); $i++;
        if ($row) {
        ?>
        <div class="item"><?php echo latest("mw.list", $row['bo_table'], 5, 45)?></div>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>

<?php
include_once('./_tail.php');
?>
