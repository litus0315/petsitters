<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 하단 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_tail']) {
    if (!@include_once($config['cf_include_tail'])) {
        die('기본환경 설정에서 하단 파일 경로가 잘못 설정되어 있습니다.');
    }
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/tail.php');
    return;
}
?>
    </td>
</tr>
</table>

<?/* sitemap 필요하신분은 주석 제거후 사용하세요 
<style type="text/css">
#tail { margin:5px 0 0 0; border:1px solid #dedede; background-color:#f4f4f4; }
#tail .sitemap { margin:5px; padding:10px 0 0 10px; background-color:#fff; border:1px solid #fff; letter-spacing:0px; }
#tail .sitemap ul { margin:0; padding:0; list-style:none; height:25px; }
#tail .sitemap ul li { margin:0; padding:0; float:left; }
#tail .sitemap ul li .group { font-weight:bold; padding:0 0 0 10px; float:left; width:80px; } 
#tail .sitemap ul li .group a { color:#5695D4; }
#tail .sitemap ul li .menu { margin-left:1px; padding:0 0 0 10px; background:url(<?=$mw_index_skin_main_path?>/img/dot.gif) 3px 5px no-repeat; }
#tail .sl { float:left; }
#tail .sitemap .gag { clear:both; height:1px; line-height:1px; font-size:1px; }
</style>

<div id="tail">
<div class="sitemap">
<?
$sql = "select gr_id, gr_subject from $g4[group_table] ";
$qry = sql_query($sql);
for ($i=0; $row=sql_fetch_array($qry); $i++) {
    echo "<ul $sline>\n";
    echo "<li><div class=\"group\"><a href=\"{$g4[bbs_path]}/group.php?gr_id={$row[gr_id]}\">{$row[gr_subject]}</a></div></li>\n";
    $sql2 = "select bo_table, bo_subject from $g4[board_table] where gr_id = '$row[gr_id]' order by bo_order_search";
    $qry2 = sql_query($sql2);
    for ($j=0; $row2=sql_fetch_array($qry2); $j++) {
	echo "<li><div class=\"menu\"><a href=\"{$g4[bbs_path]}/board.php?bo_table={$row2[bo_table]}\">{$row2[bo_subject]}</a></div></li>\n";
    }
    echo "</ul>\n";
    if (($i+1)%6==0) echo "<div class=gag>&nbsp;</div>";
}
?>
<div class="gag"></div>
</div><!-- sitemap -->
</div><!-- tail -->
*/
?>



<style type="text/css">
#mw-site-info { border-top:1px solid #ddd; }  
#mw-site-info { clear:both; text-align:center; margin:10px 0 20px 0; padding:0px; color:#555; font-size:8pt; }
#mw-site-info .stats { clear:both; text-align:center; margin:0px 0 20px 0; padding:0; color:#555; font-size:8pt; }
#mw-site-info .mw-banner { height:30px; margin:0 0 10px 0; text-align:center; }
#mw-site-info .mw-banner span { margin:0 5px 0 5px; }
#mw-site-info .menu { color:#ddd; line-height:25px; }
#mw-site-info .menu a { color:#555;  }
#mw-site-info .d { color:#ddd; margin:0 2px 0 2px; }
#mw-site-info a.site { color:#3173B6;  }
#mw-site-info a:hover { text-decoration:underline; }
#mw-site-info .copyright { margin:0 0 10px 0; }
</style>

<div id="mw-site-info">
    <div class="stats">
        <?php echo popular('basic'); // 인기검색어  ?>
        <?php echo visit('basic'); // 접속자집계 ?>
    </div>
    <div class="mw-banner">
        <span><a href="http://www.miwit.com" target=_blank><img src="<?php echo G5_IMG_URL?>/b1.gif" alt="miwit.com"></a></span>
        <span><a href="http://www.sir.co.kr" target=_blank><img src="<?php echo G5_IMG_URL?>/b2.gif" alt="sir.co.kr"></a></span>
        <span><a href="#" target=_blank><img src="<?php echo G5_IMG_URL?>/banner-tail.gif"></a></span>
        <span><a href="#" target=_blank><img src="<?php echo G5_IMG_URL?>/banner-tail.gif"></a></span>
    </div>
    <div class="menu">
        <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a>
        <span class="d">|</span> <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보취급방침</a>
        <span class="d">|</span> <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">서비스이용약관</a>
        <span class="d">|</span> <a href="#top" id="ft_totop">상단으로</a>
    </div>
    <div class="copyright">Copyright ⓒ <a href="<?php echo G5_URL?>" class="site"><?php echo G5_URL?></a>.  All rights reserved.</div>
</div>

</div> <!-- #mw-index -->

<?php
if(defined('_INDEX_')) { // index에서만 실행
    include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
}


include_once(G5_PATH."/tail.sub.php");
