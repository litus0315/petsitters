<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

if ($po['po_point']) $point = "<span class='point'>({$po['po_point']} point 적립)</span>";
?>
<style>
.mw-poll { border:1px solid #e1e1e1; text-align:left; padding:0 0 10px 0; }
.mw-poll a:hover { text-decoration:underline; }
.mw-poll .subject { background:url(<?php echo $poll_skin_path?>/img/box-bg.gif); height:24px; margin:0 0 7px 0; }
.mw-poll .subject { font-size:12px; color:#555; font-weight:bold; letter-spacing:-1px; text-decoration:none; text-align:left; }
.mw-poll .subject div { margin:5px 0 0 10px;}
.mw-poll table { margin:0 0 0 5px;}
.mw-poll .question { margin:10px 5px 10px 5px; text-align:left; }
.mw-poll .button { text-align:center; }
.mw-poll .point { font-weight:normal; font-size:11px; color:#888; }
</style>

<div class="mw-poll">
<div style="border:1px solid #fff;">

<form name="fpoll" action="<?php echo G5_BBS_URL ?>/poll_update.php" onsubmit="return fpoll_submit(this);" method="post">
<input type="hidden" name="po_id" value="<?php echo $po_id?>">
<input type="hidden" name="skin_dir" value="<?php echo $skin_dir?>">

<div class="subject"><div>설문조사 <?php echo $point?></div></div>
<div class="question">
    <?php echo $po['po_subject']?>
    <?php if ($is_admin) { ?>
    <a href="<?php echo G5_ADMIN_URL?>/poll_form.php?w=u&amp;po_id=<?php echo $po_id?>" style="font:normal 10px dotum;">[A]</a>
    <?php } ?>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<?php for ($i=1; $i<=9 && $po["po_poll{$i}"]; $i++) { ?>
<tr>
    <td width="10" height="20"><input type="radio" name="gb_poll" value="<?php echo $i?>" id='gb_poll_<?php echo $i?>'></td>
    <td><label for='gb_poll_<?php echo $i?>'><?php echo $po['po_poll'.$i]?></label></td>
</tr>
<?php } ?>
</table>
<br/>
<div class="button">
<input type="image" src="<?php echo $poll_skin_url?>/img/poll_button.gif" width="70" height="25" border="0">
<a href="<?php echo G5_BBS_URL."/poll_result.php?po_id=$po_id&amp;skin_dir=$skin_dir" ?>" target="_blank" onclick="poll_result(this.href); return false;"><img src="<?php echo $poll_skin_url?>/img/poll_view.gif" width="70" height="25" border="0"></a>
</div>
</form>

</div>
</div>

<script>
function fpoll_submit(f)
{
    <?php
    if ($member['mb_level'] < $po['po_level'])
        echo " alert('권한 {$po['po_level']} 이상의 회원만 투표에 참여하실 수 있습니다.'); return false; ";
     ?>

    var chk = false;
    for (i=0; i<f.gb_poll.length;i ++) {
        if (f.gb_poll[i].checked == true) {
            chk = f.gb_poll[i].value;
            break;
        }
    }

    if (!chk) {
        alert("투표하실 설문항목을 선택하세요");
        return false;
    }

    var new_win = window.open("about:blank", "win_poll", "width=616,height=500,scrollbars=yes,resizable=yes");
    f.target = "win_poll";

    return true;
}

function poll_result(url)
{
    <?php
    if ($member['mb_level'] < $po['po_level'])
        echo " alert('권한 {$po['po_level']} 이상의 회원만 결과를 보실 수 있습니다.'); return false; ";
     ?>

    win_poll(url);
}
</script>
