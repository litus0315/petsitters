<?php
/**
 * Bechu-Basic Skin for Gnuboard4
 *
 * Copyright (c) 2008 Choi Jae-Young <www.miwit.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

?>
    <?php if ($is_admin == "super") { ?>
    <script>
    function mw_config() {
        var url = "<?php echo $board_skin_path?>/mw.adm/mw.config.php?bo_table=<?php echo $bo_table?>";
        var config = window.open(url, "config", "width=980, height=700, scrollbars=yes");
        config.focus();
    }
    </script>
    <?php } ?>

    <?php include("$board_skin_path/mw.proc/mw.smart-alarm-config.php") ?>
    <span class=mw_basic_total>총 게시물 <?=number_format($total_count)?>건, 최근 <?=number_format($new_count)?> 건</span>

    <? if ($mw_basic[cf_social_commerce]) { ?>
    <a class="fa-button" onclick="win_open('<?=$social_commerce_path?>/order_list.php?bo_table=<?=$bo_table?>', 'order_list', 'width=800,height=600,scrollbars=1');"><i class="fa fa-shopping-cart"></i> 주문내역</a>
    <? } ?>

    <? if ($mw_basic[cf_talent_market] && $is_admin) { ?>
    <a class="fa-button" onclick="win_open('<?=$talent_market_path?>/order_list.php?bo_table=<?=$bo_table?>', 'order_list', 'width=800,height=600,scrollbars=1');"><i class="fa fa-shopping-cart"></i> 주문내역</a>
    <? } ?>

    <? if ($is_admin && $mw_basic[cf_collect] == 'rss' && file_exists("$g4[path]/plugin/rss-collect/_lib.php")) {?>
    <a class="fa-button" onclick="win_open('<?=$g4[path]?>/plugin/rss-collect/config.php?bo_table=<?=$bo_table?>', 'rss_collect', 'width=800,height=600,scrollbars=1')"><i class="fa fa-wifi"></i> RSS수집</a>
    <? } ?>

    <? if ($is_admin && $mw_basic[cf_collect] == 'youtube' && file_exists("$g4[path]/plugin/youtube-collect/_lib.php")) {?>
    <a class="fa-button" onclick="win_open('<?=$g4[path]?>/plugin/youtube-collect/config.php?bo_table=<?=$bo_table?>', 'youtube_collect', 'width=800,height=600,scrollbars=1')"><i class="fa fa-youtube"></i> 유투브</a>
    <? } ?>

    <? if ($is_admin && $mw_basic[cf_collect] == 'kakao' && file_exists("$g4[path]/plugin/kakao-collect/_lib.php")) {?>
    <a class="fa-button" onclick="win_open('<?=$g4[path]?>/plugin/kakao-collect/config.php?bo_table=<?=$bo_table?>', 'kakao_collect', 'width=800,height=600,scrollbars=1')"><i class="fa fa-wifi"></i> 카카오</a>
    <? } ?>

    <a class="tooltip fa-button" title="읽기:<?=$board[bo_read_point]?>,
쓰기:<?=$board[bo_write_point]?><?
if ($mw_basic[cf_contents_shop_write]) { echo " ($mw_cash[cf_cash_name]$mw_basic[cf_contents_shop_write_cash]$mw_cash[cf_cash_unit])"; } ?>,
댓글:<?=$board[bo_comment_point]?>,
다운:<?=$board[bo_download_point]?>"><!--
    --><i class="fa fa-info-circle"></i> 안내</a>

    <? if ($mw_basic[cf_social_commerce] && $rss_href && file_exists("$social_commerce_path/img/xml.png")) { ?>
        <a href='<?=$social_commerce_path?>/xml.php?bo_table=<?=$bo_table?>' class="fa-button"><i class="fa fa-rss"></i> XML</a>
    <? } else if ($rss_href) { ?><a href='<?=$rss_href?>' class="fa-button"><i class="fa fa-rss"></i> RSS</a><?}?>

    <? if ($is_admin == "super") { ?><a href="#;" onclick="mw_config()" class="fa-button"><i class="fa fa-gear"></i> 배추스킨설정</a><?}?>

    <? if ($admin_href) { ?><a href="<?=$admin_href?>" class="fa-button"><i class="fa fa-wrench"></i> 관리자</a><?}?>

    <?php if ($write_href) { ?>
        <a class="fa-button primary" href="<?php echo $write_href?>"><i class="fa fa-pencil"></i> 글쓰기</a>
    <?php } ?>

