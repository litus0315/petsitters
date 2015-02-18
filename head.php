<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

// 상단 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_head']) {
    if (!@include_once($config['cf_include_head'])) {
        die('기본환경 설정에서 상단 파일 경로가 잘못 설정되어 있습니다.');
    }
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/head.php');
    return;
}

$memo_not_read = 0;
if ($is_member) {
    $sql = " select count(*) as cnt ";
    $sql.= "   from {$g5['memo_table']} ";
    $sql.= "  where me_recv_mb_id = '{$member['mb_id']}' ";
    $sql.= "    and substring(me_read_datetime, 1, 1) = '0' ";
    $row = sql_fetch($sql);
    $memo_not_read = $row['cnt'];
}

$my_url = null;
$menu = null;

if (strlen($_SERVER["REQUEST_URI"]) > 1) {
    $my_url = set_http($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
    $sql = " select * from {$g5['menu_table']} where me_use = '1' order by me_order ";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
        if (strstr($my_url, $row['me_link'])) {
            $menu = $row;
            break;
        }
        //$menu = sql_fetch(" select * from {$g5['menu_table']} where me_link like '{$my_url}%' limit 1");
    }
}

if (!$theme && get_cookie("ck_theme"))
    $theme = get_cookie("ck_theme");

if ($theme) {
    if (preg_match("/^[a-z0-9_-]+$/i", $theme)) {
        set_cookie("ck_theme", $theme, 60*60*24*30);
        $g5_theme = $theme;
    }
}

$theme_path = G5_PATH."/theme/".$g5_theme;
$theme_url = G5_URL."/theme/".$g5_theme;

if (!is_dir($theme_path) || !file_exists($theme_path)) {
    $theme_path = G5_PATH."/theme/basic";
    $theme_url = G5_URL."/theme/basic";
}

//if ($bo_table) echo "<script src=\"".G5_URL."\"/js/mw.g5.adapter.js.php?bo_table={$bo_table}\"></script>";
?>
<link rel="stylesheet" href="<?php echo $theme_url?>/style.css" type="text/css"/>

<style>
#head .mw-index-menu-bar { background:url(<?php echo $theme_url?>/img/mm.png); }
#head .mw-index-menu-div { background:url(<?php echo $theme_url?>/img/md.png) center no-repeat; }
#head .mw-index-menu-select1 { background:url(<?php echo $theme_url?>/img/msm.png); }
#head .mw-index-menu-select2 { background:url(<?php echo $theme_url?>/img/msl.png) top left no-repeat; }
#head .mw-index-menu-select3 { background:url(<?php echo $theme_url?>/img/msr.png) top right no-repeat; }
#head .mw-index-menu-bar .mw-drop-menu div { background:url(<?php echo G5_IMG_URL?>/dot.gif) 0 7px no-repeat; }
#sm .sm_sub { background:url(<?php echo G5_IMG_URL?>/menu.gif) left top no-repeat;  }
</style>

<div id="mw-index">

<div id="top">
    <div style="float:left;">
        <a href="javascript:window.external.AddFavorite('http://<?php echo $_SERVER['HTTP_HOST']?>/' , '<?php echo $config['cf_title']?>');">즐겨찾기</a>
        <span class="span"> | </span>
        <a href="<?php echo G5_BBS_URL ?>/current_connect.php">현재접속자 (<?php echo connect()?>)</a>
        <span class="span"> | </span>
        <a href="<?php echo G5_BBS_PATH?>/new.php">최근게시물</a>
    </div>
    <div style="float:right;">
        <?php if ($is_admin == "super") { ?>
        <a href="<?php echo G5_ADMIN_URL?>/">관리자</a>
        <span class="span"> | </span>
        <?php } ?>
        <?php if (!$is_member) { ?>
        <a href="<?php echo G5_BBS_URL?>/login.php">로그인</a>
        <span class="span"> | </span>
        <a href="<?php echo G5_BBS_PATH?>/register.php">회원가입</a>
        <?php } else { ?>
        <a href="<?php echo G5_BBS_URL?>/memo.php" class="win_memo">쪽지 (<?php echo $memo_not_read?>)</a>
        <span class="span"> | </span>
        <?php if ($config['cf_use_point']) { ?>
        <a href="<?php echo G5_BBS_URL?>/point.php" class="win_point">포인트 (<?php echo number_format($member['mb_point'])?>)</a>
        <span class="span"> | </span>
        <?php } ?>
        <a href="<?php echo G5_BBS_URL?>/logout.php">로그아웃</a>
        <span class="span"> | </span>
        <a href="<?php echo G5_BBS_URL?>/member_confirm.php?url=register_form.php">정보수정</a>
        <?php } ?>
        <span class="span"> | </span>
        <a href="<?php echo G5_BBS_URL ?>/faq.php">FAQ</a>
        <span class="span"> | </span>
        <a href="<?php echo G5_BBS_URL ?>/qalist.php">1:1문의</a>
        <span class="span"> | </span>
        <a href="<?php echo G5_BBS_URL ?>/new.php">새글</a>
    </div>
</div>

<!-- 헤더 시작 -->
<div id="head">

<table border=0 cellpadding=0 cellspacing=0 style="margin:0 auto 0 auto;" align="center">
<tr>
<td class="logo"><!-- 사이트 로고 --><a href="<?php echo G5_URL?>"><img src="<?php echo G5_IMG_URL?>/logo.png"></a></td>
<td width=10></td>
<td>
    <!-- 상단검색창 시작 -->
    <form name=fmainsearch action="<?php echo G5_BBS_PATH?>/search.php" class="search-box">
        <input type="hidden" name="sfl" value="wr_subject||wr_content">
        <input type="hidden" name="sop" value="and">
        <?//=$group_select?>
        <span class="search-text"><input type=text name=stx></span>
        <input type=submit value="검색" class="search-button">
    </form>
    <!-- 상단검색창 끝 -->

    <!-- 퀵링크 시작 -->
    <table class="quick-link-box" cellpadding=0 cellspacing=0 border=0>
    <tr>
	<td>테마 : </td>
	<td><a href="<?php echo G5_URL?>/?theme=basic" class="quick-link">Basic</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=green1" class="quick-link">Green1</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=green2" class="quick-link">Green2</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=black" class="quick-link">Black</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=red" class="quick-link">Red</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=violet" class="quick-link">Violet</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=orange" class="quick-link">Orange</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=pink" class="quick-link">Pink</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=sky" class="quick-link">Sky</a></td><td class="quick-div">|</td>
	<td><a href="<?php echo G5_URL?>/?theme=chocolate" class="quick-link">Chocolate</a></td>
    </tr>
    </table>
    <!-- 퀵링크 끝 -->

</td>
<td width=70></td>
</tr>
</table>

<div class="mw-index-menu-bar">
    <div class="mw-index-menu-left"><img src="<?php echo $theme_url?>/img/ml.png"></div>
    <!-- 그룹 메뉴 시작 -->
    <?php
    $select_div_begin = "<div class='mw-index-menu-select1'><div class='mw-index-menu-select2'><div class='mw-index-menu-select3'>";
    $select_div_end = "</div></div></div>";

    $sql = " select *
               from {$g5['menu_table']}
              where me_use = '1'
                and length(me_code) = '2'
              order by me_order, me_id ";
    $qry = sql_query($sql, false);

    for ($i=0; $row=sql_fetch_array($qry); $i++)
    {
        if ($i > 0) echo "<span class='mw-index-menu-div'></span>"; 

        $menu1 = substr($row['me_code'], 0, 2);
        if ($menu1 == substr($menu['me_code'], 0, 2)) {
            $div_begin = $select_div_begin;
            $div_end = $select_div_end;
        }
        else {
            $div_begin = "<div class='mw-index-menu-item' menu1='{$menu1}'>";
            $div_end = "</div>";
        }

        //echo "$div_begin<a href=\"$g5['bbs_path']/$menu_file?$menu_type=$row_id\">$row_subject</a>$div_end";
        echo $div_begin."<a href=\"{$row['me_link']}\" target=\"_{$row['me_target']}\">{$row['me_name']}</a>".$div_end;

        ob_start();
        ?>
        <div id="mw-drop-menu-<?php echo $menu1?>" class="mw-drop-menu">
        <?php
        $sql2 = " select *
                    from {$g5['menu_table']}
                    where me_use = '1'
                      and length(me_code) = '4'
                      and substring(me_code, 1, 2) = '{$row['me_code']}'
                    order by me_order, me_id ";
        $qry2 = sql_query($sql2);
        for ($j=0; $row2=sql_fetch_array($qry2); $j++) {
            //if ($row2['bo_table'] == $bo_table) $class = "sm_sub selected"; else $class = "sm_sub";
            ?><div><a href="<?php echo $row2['me_link']?>" target="_<?php echo $row2['me_target']?>"><?php echo $row2['me_name']?></a></div><?
        }
        ?>
        </div> <!-- mw-drop-menu -->
        <?php
        $drop_menu = ob_get_contents();
        ob_end_clean();

        if ($j>1) echo $drop_menu;
    } ?>

    <!-- 그룹 메뉴 끝 -->
    <div class="mw-index-menu-right"><img src="<?php echo $theme_url?>/img/mr.png"></div>
</div>

<script>
$(document).ready(function () {
    $(".mw-index-menu-item").mouseenter(function () {
        $(".mw-drop-menu").hide();
        menu1 = $(this).attr("menu1");
        t = $(this).offset().top;
        l = $(this).offset().left;
        $("#mw-drop-menu-"+menu1).css("top", t+30);
        $("#mw-drop-menu-"+menu1).css("left", l);
        $("#mw-drop-menu-"+menu1).show();
    });
    $(".mw-index-menu-bar").mouseleave(function () {
        $(".mw-drop-menu").hide();
    });
});
</script>

</div><!-- head -->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td valign="top" width="180">
        <div class="outlogin"><?php echo outlogin("mw_lite")?></div>

        <div id="sm">
            <div class="sm_border">
            <div class="sm_margin">
                <?php
                $sql_menu = null;
                if ($menu['me_code'])
                    $sql_menu = " and substring(me_code, 1, 2) = '".substr($menu['me_code'], 0, 2)."' ";

                $sql = " select *
                           from {$g5['menu_table']}
                          where me_use = '1'
                            and length(me_code) = '2' {$sql_menu}
                          order by me_order, me_id ";
                $qry = sql_query($sql);
                for ($i=0; $row=sql_fetch_array($qry); $i++) {
                ?>
                    <div class="sm_item">
                    <div class="sm_title"><a href="<?php echo $row['me_link']?>" target="_<?php echo $row['me_target']?>"><?php echo $row['me_name']?></a></div>
                    <?php
                    $sql2 = " select *
                               from {$g5['menu_table']}
                              where me_use = '1'
                                and length(me_code) = '4'
                                and substring(me_code, 1, 2) = '{$row['me_code']}'
                              order by me_order, me_id ";
                    $qry2 = sql_query($sql2);
                    for ($j=0; $row2=sql_fetch_array($qry2); $j++) {
                        if ($menu['me_code'] == $row2['me_code'])
                            $class = "sm_sub selected";
                        else
                            $class = "sm_sub";
                    ?>
                        <div class="<?php echo $class?>"><a href="<?php echo $row2['me_link']?>" target="_<?php echo $row2['me_target']?>"><?php echo $row2['me_name']?></a></div>
                    <?php } ?>
                    </div> <!-- sm_item -->
                <?php }  ?>
            </div> <!-- sm_margin -->
            </div> <!-- sm_border -->

        </div> <!-- sm -->
        <div class="poll"><?php echo poll("mw.poll")?></div>
    </td>
    <td width="10"></td>
    <td valign="top">

