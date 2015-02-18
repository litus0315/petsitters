<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$swfupload_path         = "$board_skin_path/mw.swfupload"; // swfupload 경로
$thumb_width            = 80; // 썸네일 가로 사이즈
$thumb_height           = 60; // 썸네일 세로 사이즈

$file_types             = "*.*"; // 업로드 가능 파일확장자 (*.gif; *.jpg; *.png)
$file_types_description = "All Files"; // 업로드 가능 확장자설명 (Image Files)
$file_allsize_limit     = "100 MB"; // 업로드 가능 전체용량제한
$file_size_limit        = getfilesize($board[bo_upload_size]); // 업로드 가능 개별파일제한
$file_count_limit       = $board[bo_upload_count]; // 업로드 가능 파일갯수제한 (0은 무한대)

if ($wr_id) {
	$board_file_path    = "$g4[path]/data/file/$bo_table"; // 파일폴더 경로
	$sql  = " select * from $g4[board_file_table] where bo_table = '$bo_table' and wr_id = '$wr_id' ";
} else {
	$board_file_path    = "$g4[path]/data/guploader"; // 파일폴더 경로
	$sql  = " select * from $mw[guploader_table] where bo_table = '$bo_table' and mb_id = '$member[mb_id]' and bf_ip = '$_SERVER[REMOTE_ADDR]' ";
}

$qry = sql_query($sql, false);
if (!$qry) { // guploader 테이블이 없다면 생성
    $sql_table = "create table $mw[guploader_table] (
        id int not null auto_increment,
        bo_table varchar(20) not null,
        bf_no int not null,
        mb_id varchar(20) not null,
        bf_source varchar(255) not null,
        bf_file varchar(255) not null,
        bf_filesize int not null,
        bf_width int not null,
        bf_height int not null,
        bf_type tinyint not null,
        bf_datetime datetime not null,
        bf_ip varchar(20) not null,
        primary key (id),
        index (bo_table, mb_id, bf_no)
    ) $default_charset";
    $qry = sql_query($sql_table, false);
}

$result = sql_query($sql);
$sum_filesize = 0;
$last_bf_no = 0;
for ($i=0; $row=sql_fetch_array($result); $i++) {
	if ($row[bf_file]) {
		$file_list_option .= "<option value='$row[bf_no]|$row[bf_source]|$row[bf_file]|$row[bf_filesize]|$row[bf_width]|$row[bf_type]'>$row[bf_source] (" . getfilesize($row[bf_filesize]) . ")</option>\n";
		$sum_filesize = $sum_filesize + $row[bf_filesize];
		$last_bf_no++;
	}
}

// 업로드 상태확인
if ($file_count_limit == 0) {
	$file_count_status = "제한없음";
} else {
	$file_count_status = $last_bf_no . " 개 / " . $file_count_limit . " 개";
}
$uploader_status = "파일갯수제한 : " . $file_count_status . "<br />전체용량제한 : " . getfilesize($sum_filesize) . " / " . $file_allsize_limit . "<br />개별파일제한 : " . getfilesize($board[bo_upload_size]) . " (허용확장자 : " . $file_types_description . ")";

// 파일 사이즈를 kb, mb에 맞추어서 변환해서 리턴 
function getfilesize($size) {
    if (!$size) return "0 Byte";
    if ($size < 1024) {
        return ($size." Byte");
    } else if ($size > 1024 && $size < 1024 *1024) {
        return sprintf("%0.1f KB",$size / 1024);
    }
    else return sprintf("%0.2f MB",$size / (1024*1024));
}
?>

<style type="text/css">
#swfup_success { font-family:Tahoma,굴림; font-size:11px; color:#797979; }
#swfup_success td, #swfup_success span { font-family:Tahoma,굴림; font-size:11px; color:#797979; line-height:12px; }
#swfup_success select { font-family:Tahoma,굴림; font-size:11px; color:#797979; font-family:Tahoma,굴림; }

/* 버튼설정 */
#swfup_success div.button20 { background:url(<?=$board_skin_path?>/img/buttonWhite.gif) no-repeat; background-position:left -29px; }
#swfup_success div.button20 input { position:relative; border:0; font:11px 돋움; background:url(<?=$board_skin_path?>/img/buttonWhite.gif) no-repeat; background-position:right -29px; }
#swfup_success div.button20 input { height:20px; left:2px; text-align:center; cursor:pointer; overflow:visible; }

#swfup_success .swfupload { position: absolute; z-index: 1; }
</style>

<script type="text/javascript" src="<?=$swfupload_path?>/swfupload.js"></script>
<script type="text/javascript" src="<?=$swfupload_path?>/swfupload.swfobject.js"></script>
<script type="text/javascript" src="<?=$swfupload_path?>/swfupload.queue.js"></script>
<script type="text/javascript" src="<?=$swfupload_path?>/fileprogress.js"></script>
<script type="text/javascript" src="<?=$swfupload_path?>/handlers.js"></script>
<script type="text/javascript">
var swfu;

// File Delete Settings
var board_skin_path        = "<?=$board_skin_path?>"
var swfupload_path         = "<?=$swfupload_path?>";
var bo_table               = "<?=$bo_table?>";
var mb_id                  = "<?=$member[mb_id]?>";
var wr_id                  = "<?=$wr_id?>";
var board_file_table       = "<?=$board_file_table?>";
var board_file_path        = "<?=$board_file_path?>";
var thumb_width            = "<?=$thumb_width?>";
var thumb_height           = "<?=$thumb_height?>";
var file_types             = "<?=$file_types?>";
var file_types_description = "<?=$file_types_description?>";
var file_allsize_limit     = "<?=$file_allsize_limit?>";
var file_size_limit        = "<?=$file_size_limit?>";
var sum_filesize           = <?=$sum_filesize?>;
var file_upload_limit      = <?=$file_count_limit-$last_bf_no?>;
var file_count_limit       = <?=$file_count_limit?>;
var last_bf_no             = <?=$last_bf_no?>;

SWFUpload.onload = function () {
	var settings = {
		// Flash Settings
		flash_url : "<?=$swfupload_path?>/swfupload.swf",

		// File Upload Settings
		upload_url : "<?=$swfupload_path?>/file_upload.php",
		post_params : {
			"bo_table"         : bo_table,
			"mb_id"            : mb_id,
			"wr_id"            : wr_id,
			"board_file_path"  : board_file_path
		},

		// File Upload Settings
		file_types             : file_types,
		file_types_description : file_types_description,
		file_size_limit        : file_size_limit,
		file_upload_limit      : file_upload_limit,
		file_count_limit       : file_count_limit,
		custom_settings        : {
			fileListAreaID     : "uploaded_file_list",
			cancelButtonId     : "btnCancel",
			progressCount      : 0
		},

		// Button settings
		button_placeholder_id : "spanButtonPlaceholder",
		button_width          : 70,
		button_height         : 20,
		button_window_mode    : SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor         : SWFUpload.CURSOR.HAND,

		// The event handler functions are defined in handlers.js
		swfupload_loaded_handler     : swfUploadLoaded,
		file_queued_handler          : fileQueued,
		file_queue_error_handler     : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler         : uploadStart,
		upload_progress_handler      : uploadProgress,
		upload_error_handler         : uploadError,
		upload_success_handler       : uploadSuccess,
		upload_complete_handler      : uploadComplete,
		
		// SWFObject settings
		minimum_flash_version         : "9.0.28",
		swfupload_pre_load_handler    : swfUploadPreLoad,
		swfupload_load_failed_handler : swfUploadLoadFailed
	};

	swfu = new SWFUpload(settings);
};
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="swfup_success" style="display:block;">
<colgroup width=100>
<colgroup width=''>
<tr>
<td class=mw_basic_write_title>· <? if ($mw_basic[cf_zzal]) echo "짤방"; else echo "SWF 멀티업로더"; ?></td>
<td class=mw_basic_write_content style="padding:5px 0;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<col width=90 />
		<col width=5 />
		<col width=200 />
		<col width=5 />
		<col />
    	<tr>
        	<td height="70" rowspan="2" id="image_preview" align="center" style="background-color:#efefef; border:1px solid #ccc;">미리보기</td>
            <td rowspan="2"></td>
            <td rowspan="2"><select id="uploaded_file_list" name="uploaded_file_list" style="width:200px; overflow:hidden;" size="5" onchange="preview();" multiple="multiple"><?=$file_list_option?></select></td>
            <td rowspan="2"></td>
            <td valign="top">
				<div style="clear:both;">
					<div style="float:left;">
						<div class="button20" style="width:70px;"><span id="spanButtonPlaceholder"></span><input id="btnUpload" type="button" value="파일첨부" style="width:100%; font-weight:bold;" /></div>
					</div>
					<div style="float:left; margin-left:5px;">
						<div class="button20" style="width:70px;"><input id="btnDelete" type="button" value="선택삭제" style="width:100%;" onClick="delete_file();" /></div>
					</div>
					<div style="float:left; margin-left:5px;">
						<div class="button20" style="width:70px;"><input id="btnInsert" type="button" value="본문삽입" style="width:100%;" onClick="file_to_editor();" /></div>
					</div>
				</div>
            </td>
        </tr>
		<tr>
			<td valign="bottom"><span id="uploader_status"><?=$uploader_status?></span></td>
		</tr>
    </table>
	<? if ($mw_basic[cf_zzal] && $mw_basic[cf_zzal_must]) { ?>
	<div style="margin-top:5px; padding:5px; border-top:1px #ccc dotted; border-bottom:1px #ccc dotted;">반드시 첫번째에 짤방 이미지를 첨부하셔야 합니다.</div>
	<? } ?>
	
	<div id="divSWFUploadUI" style="width:100%;">
		<noscript style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px;">
			We're sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.
		</noscript>
		<!--<div id="divLoadingContent" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
			SWFUpload is loading. Please wait a moment...
		</div>-->
		<div id="divLongLoading" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
			SWFUpload is taking a long time to load or the load has failed.  Please make sure that the Flash Plugin is enabled and that a working version of the Adobe Flash Player is installed.
		</div>
		<div id="divAlternateContent" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
			We're sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player.
			Visit the <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a> to get the Flash Player.
		</div>
	</div>
</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="swfup_fail" style="display:none;">
<colgroup width=100>
<colgroup width=''>
<tr>
<td class=mw_basic_write_title>
· <? if ($mw_basic[cf_zzal]) echo "짤방"; else echo "파일"; ?>
<span onclick="add_file();" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>+</span>
<span onclick="del_file();" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>-</span>
</td>
<td class=mw_basic_write_content style="padding:5px 0;">
	<table id="variableFiles"></table>
	<? if ($mw_basic[cf_zzal] && $mw_basic[cf_zzal_must]) { ?>
	<div style="margin-top:5px; padding:5px; border-top:1px #ccc dotted; border-bottom:1px #ccc dotted;">반드시 첫번째에 짤방 이미지를 첨부하셔야 합니다.</div>
	<? } ?>
	<script type="text/javascript">
	var flen = 0;
	function add_file(delete_code)
	{
		var upload_count = <?=(int)$board[bo_upload_count]?>;
		if (upload_count && flen >= upload_count)
		{
			alert("이 게시판은 "+upload_count+"개 까지만 파일 업로드가 가능합니다.");
			return;
		}

		var objTbl;
		var objRow;
		var objCell;
		if (document.getElementById)
			objTbl = document.getElementById("variableFiles");
		else
			objTbl = document.all["variableFiles"];

		objRow = objTbl.insertRow(objTbl.rows.length);
		objCell = objRow.insertCell(0);

		objCell.innerHTML = "<input type='file' id=bf_file_" + flen + " name='bf_file[]' title='파일 용량 <?=$upload_max_filesize?> 이하만 업로드 가능' class=mw_basic_text>";

	/*
	str = "<input type='file' id=bf_file_" + flen + " name='bf_file[]' title='파일 용량 <?=$upload_max_filesize?> 이하만 업로드 가능' class=mw_basic_text> ";
	str+= " <input type='button' value='본문에 넣기' onclick=\"document.getElementById('wr_content').value += '{이미지:" + flen + "}'\"";
	objCell.innerHTML = str;
	*/

		if (delete_code)
			objCell.innerHTML += delete_code;
		else
		{
			<? if ($is_file_content) { ?>
			objCell.innerHTML += "<br><input type='text' size=50 name='bf_content[]' title='업로드 이미지 파일에 해당 되는 내용을 입력하세요.' class=mw_basic_text>";
			<? } ?>
			;
		}

		flen++;
	}

	<?=$file_script; //수정시에 필요한 스크립트?>

	function del_file()
	{
		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = <?=(int)$file_length?>;
		var objTbl = document.getElementById("variableFiles");
		if (objTbl.rows.length - 1 > file_length)
		{
			objTbl.deleteRow(objTbl.rows.length - 1);
			flen--;
		}
	}
	</script>
</td>
</tr>
</table>