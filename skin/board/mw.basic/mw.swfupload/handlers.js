/*
@author romeojks (romeojks@gmail.com)
@version 1.0
@brief 파일 업로드 관련
*/

function swfUploadPreLoad() {
	var self = this;
	var loading = function () {
		document.getElementById("divLoadingContent").style.display = "";

		var longLoad = function () {
			document.getElementById("divLoadingContent").style.display = "none";
			document.getElementById("divLongLoading").style.display = "";
		};
		this.customSettings.loadingTimeout = setTimeout(function () {
				longLoad.call(self)
			},
			15 * 1000
		);
	};

	this.customSettings.loadingTimeout = setTimeout(function () {
			loading.call(self);
		},
		1 * 1000
	);
}

function swfUploadLoaded() {
	clearTimeout(this.customSettings.loadingTimeout);
	document.getElementById("swfup_success").style.display = "block";
	document.getElementById("swfup_fail").style.display = "none";
}

function swfUploadLoadFailed() {
	clearTimeout(this.customSettings.loadingTimeout);
	document.getElementById("swfup_success").style.display = "none";
	document.getElementById("swfup_fail").style.display = "block";
}

function fileQueued(file) {
	try {
		if (file_count_limit > 0 && file_count_limit <= last_bf_no) {
			fileQueueError(file, SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED, (file_count_limit-last_bf_no));
		} else {
			var obj = document.getElementById(this.customSettings.fileListAreaID);
			var filename = file.name;
			
			if (filename.length > 20) {
				filename = filename.substr(0,20) + "...";
			};
			
			var text = filename + " (대기중...)";
			var value = file.id;
			var opt_obj = new Option(text, value, true, true);
			obj.options[obj.options.length] = opt_obj;
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function fileQueueError(file, errorCode, message) {
	try {
		switch (errorCode) {
			case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED :
				alert(message == 0 ? "더이상 업로드 할 수 없습니다." : "파일갯수제한 : " + file_count_limit + " 개\n\n현재 첨부가능한 수량은 " + message + " 개 입니다.");
				break;
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT :
				alert("개별파일제한 : " + file_size_limit + "\n\n" + "파일명 : " + file.name + "\n사이즈 : " + getfilesize(file.size));
				break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE :
				alert("파일 사이즈가 '0' 입니다.\n\n" + "파일명 : " + file.name);
				break;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE :
				alert("파일 타입이 올바르지 않습니다.\n\n" + "파일명 : " + file.name);
				break;
			default :
				alert("Error Code : " + errorCode + "\n\nFile name : " + file.name + "\nFile size : " + getfilesize(file.size) + "\n\nMessage : " + message);
				break;
		};
	} catch (ex) {
		this.debug(ex);
	};
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		this.startUpload();
	} catch (ex) {
        this.debug(ex);
	};
}

function uploadStart(file) {
	try {
		if (getfilesize1(file_allsize_limit) < (sum_filesize+file.size)) {
			uploadError(file, SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED, "");
			
			var obj = document.getElementById("uploaded_file_list");
			var length = obj.options.length - 1;
			for (var i=length; i>=0; i--) {
				var file_info = obj.options[i].value.split("|");
				if (file_info.length == 1) {
					op = obj.options[i];
					obj.removeChild(op);
					swfu.cancelUpload(file_info, "FILE_VALIDATION_FAILED");
				};
			};
			return false;
		} else {
			return true;
		};
	} catch (ex) {
        this.debug(ex);
	};
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var obj = document.getElementById(this.customSettings.fileListAreaID);
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		var filename = file.name;

		if (filename.length > 20) {
			filename = filename.substr(0,20) + "...";
		};
		
		var text = filename + " (" + percent + " %)";
		var bf_position = last_bf_no + this.customSettings.progressCount;
		
		obj.options[bf_position].text = text;
	} catch (ex) {
		this.debug(ex);
	};
}

function uploadSuccess(file, serverData) {
	try {
		var obj = document.getElementById(this.customSettings.fileListAreaID);
		var bf_position = last_bf_no + this.customSettings.progressCount;
		
		var params = {
			"bo_table"        : bo_table,
			"wr_id"           : wr_id,
			"bf_position"     : bf_position + 1
		};
		var url = swfupload_path + "/get_file_info.php";
		$.ajax({
			type: 'post',
			url: url,
			data: params,
			success : after_upload_success = function(req) {
				var file = eval('('+req+')');
				var value = file.bf_no + "|" + file.bf_source + "|" + file.bf_file + "|" + file.bf_filesize + "|" + file.bf_width + "|" + file.bf_type;
				obj.options[bf_position].value = value;
				var text = file.bf_source + " (" + getfilesize(file.bf_filesize) + ")";
				obj.options[bf_position].text = text;
				eval("preview(file.bf_file)");
			}
		});
		
		var file_count = bf_position + 1;
		sum_filesize = sum_filesize + file.size;
		if (file_count_limit == 0) {
			var file_count_status = "제한없음";
		} else {
			var file_count_status = file_count + " 개 / " + file_count_limit + " 개";
		};
		document.getElementById("uploader_status").innerHTML = "파일갯수제한 : " + file_count_status + "<br />전체용량제한 : " + getfilesize(sum_filesize) + " / " + file_allsize_limit + "<br />개별파일제한 : " + file_size_limit + " (허용확장자 : " + file_types_description + ")";
		
		this.customSettings.progressCount++;
	} catch (ex) {
		this.debug(ex);
	};
}

function uploadError(file, errorCode, message) {
	try {
		switch (errorCode) {
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR :
				alert("네트워크 에러가 발생하였습니다. 관리자에게 문의하세요.\n\n" + "파일명 : " + file.name);
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED :
				alert("파일 업로드가 실패하였습니다.\n\n" + "파일명 : " + file.name);
				break;
			case SWFUpload.UPLOAD_ERROR.IO_ERROR :
				alert("입출력 에러가 발생하였습니다.\n\n" + "다른 프로그램에서 이 파일(" + file.name + ")을 사용중인지 확인하세요.");
				break;
			case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR :
				alert("보안 에러가 발생하였습니다. 관리자에게 문의하세요.\n\n" + "파일명 : " + file.name);
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED :
				alert("개별파일제한 : " + file_size_limit + "\n\n" + "파일명 : " + file.name + "\n사이즈 : " + getfilesize(file.size));
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED :
				alert("전체용량제한 : " + file_allsize_limit + "\n\n" + "남은용량 : " + getfilesize(getfilesize1(file_allsize_limit) - sum_filesize));
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED :
				// If there aren't any files left (they were all cancelled) disable the cancel button
				if (this.getStats().files_queued === 0) {
					document.getElementById(this.customSettings.cancelButtonId).disabled = true;
				};
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED :
				break;
			default :
				alert("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + getfilesize(file.size) + ", Message: " + message);
				break;
		}
	} catch (ex) {
		this.debug(ex);
	};
}

function uploadComplete(file) {
	
}

function getfilesize(size) {
    if (!size) {
		return "0 Byte";
	};
    if (size < 1024) {
        return (size + " Byte");
    } else if (size > 1024 && size < 1024*1024) {
        return (size / 1024).toFixed(1) + " KB";
    } else {
		return (size / (1024*1024)).toFixed(2) + " MB";
	};
}

function getfilesize1(size) {
	var file_size  = size.split(" ");
    if (!file_size[0]) {
		return 0;
	};
    if (file_size[1] == "MB") {
		return (file_size[0] * (1024*1024));
	} else if (file_size[1] == "KB") {
		return (file_size[0] * 1024);
	} else {
		return (file_size[0]);
	};
}

function delete_file() {
	try {
		var obj = document.getElementById("uploaded_file_list");
		
		if (!obj.value) {
			alert('파일을 선택해주세요.');
			obj.focus();
			return false;
		};
		
    	var url = swfupload_path + "/file_delete.php";
		var file_count = obj.options.length;
	    for (var i=0; i<obj.options.length; i++) {
			if (obj.options[i].selected == true) {
				var file = get_file_info(obj.options[i].value);
				var params = {
					"bo_table"        : bo_table,
					"wr_id"           : wr_id,
					"bf_no"           : file.bf_no,
					"board_file_path" : board_file_path
				};
				
				$.ajax({
					type: 'post',
					url: url,
					data: params,
					success : eval("delete_file_complete")
					});
				
				file_count = file_count - 1;
				sum_filesize = sum_filesize - file.bf_filesize;
				if (file_count_limit == 0) {
					var file_count_status = "제한없음";
				} else {
					var file_count_status = file_count + " 개 / " + file_count_limit + " 개";
				};
				document.getElementById("uploader_status").innerHTML = "파일갯수제한 : " + file_count_status + "<br />전체용량제한 : " + getfilesize(sum_filesize) + " / " + file_allsize_limit + "<br />개별파일제한 : " + file_size_limit + " (허용확장자 : " + file_types_description + ")";
			};
		};
	} catch (ex) {
		this.debug(ex);
	};
}

function delete_file_complete() {
	try {
		var obj = document.getElementById("uploaded_file_list");
		for (var i=0; i<obj.options.length; i++) {
        	if (obj.options[i].selected == true) {
				op = obj.options[i];
	            obj.removeChild(op);
				last_bf_no--;
				file_upload_limit++;
				swfu.setFileUploadLimit(file_upload_limit);
			};
	    };

    	if (obj.options.length) {
        	obj.options[obj.options.length-1].selected = true;
		};

		eval("preview()");
	} catch (ex) {
		this.debug(ex);
	};
}

function preview(thumb) {
	try {
		if (!thumb) {
			var file = get_file_info(document.getElementById("uploaded_file_list").value);
			var thumb = file.bf_file;
		}
		
		var pattern = /\.(jpg|png|gif)$/i;
		if (pattern.test(thumb)) {
			var thumb_kind = "img";
		} else {
			var thumb_kind = "etc";
		}
		
		if (thumb && thumb_kind == "img") {
			document.getElementById("image_preview").innerHTML = "<img src=" + board_file_path + "/" + thumb + " width=" + thumb_width + " height=" + thumb_height + ">";
		} else if (thumb && thumb_kind == "etc") {
			document.getElementById("image_preview").innerHTML = "<img src=" + board_skin_path + "/img/icon_etc.gif" + " width=" + thumb_width + " height=" + thumb_height + ">";
		} else {
			document.getElementById("image_preview").innerHTML = "미리보기";
		};
	} catch (ex) {
		this.debug(ex);
	};
}

function file_to_editor() {
	try {
		var files_list = document.getElementById("uploaded_file_list");
		var html = '';

		if (!files_list.value) {
			alert('파일을 선택해주세요.');
			files_list.focus();
			return false;
		};
		
		for (var i=0; i<files_list.options.length; i++) {
			if (files_list.options[i].selected == true) {
				var file = get_file_info(files_list.options[i].value);
				var path = board_file_path + '/' + file.bf_file;
				
				var pattern  = /\.(gif|jpg|jpeg|png)$/i;
				var pattern1 = /\.(asx|asf|wmv|wma)$/i;
				var pattern2 = /\.(mpg|mpeg|mov|avi|mp4|m4v|f4v|mov|flv|webm|aac|m4a|f4a|mp3|ogg|oga)$/i;
				if (pattern.test(file.bf_file)) {
					if (wr_id) {
						html = "{이미지:" + file.bf_no + "}";
					} else {
						html = "{이미지:" + i + "}";
					}
				} else if (pattern1.test(file.bf_file)) {
					if (wr_id) {
						path = "/data/file/"+bo_table+"/"+file.bf_file;
					} else {
						path = "/data/guploader/"+file.bf_file;
					}
					html = "<EMBED src='"+path+"' loop='true' autostart='true'></EMBED>";
				} else if (pattern2.test(file.bf_file)) {
					if (wr_id) {
						html = "{동영상:" + file.bf_no + "}";
					} else {
						html = "{동영상:" + i + "}";
					}
				} else {
					path = "download.php?bo_table=" + bo_table + "&filename=" + file.bf_file + "&filesource=" + file.bf_source + "";
					html += "<a href=\"" + path + "\">" + file.bf_source + "</a>";
				};
				if (typeof geditor_wr_content != "undefined") {
					insert_editor(html);
				} else if (typeof ed_wr_content != "undefined") {
					insert_cheditor(html);
				} else {
				};
			};
		};
	} catch (ex) {
		this.debug(ex);
	};
}

function insert_editor(html) {
	try {
		if (typeof geditor_wr_content != "undefined") {
			if (geditor_wr_content.get_mode() == "WYSIWYG") {
				document.getElementById("geditor_wr_content_frame").contentWindow.document.body.focus();
				geditor_wr_content.get_range();
				html = "<DIV>" + html + "</DIV>";
			} else if (geditor_wr_content.get_mode() == "TEXT") {
				html = "<DIV>" + html + "</DIV>";
			} else {
				html = html;
			}
			geditor_wr_content.insert_editor(html);
		} else {
			document.getElementById("wr_content").value += html + "\n";
		}
	} catch (ex) {
		this.debug(ex);
	};
}

function insert_cheditor(html) {
	try {
		if (typeof ed_wr_content != "undefined") {
			if (ed_wr_content.cheditor.mode == "rich") {
				html = "<DIV>" + html + "</DIV>";
				ed_wr_content.insertContents(html);
			} else if (ed_wr_content.cheditor.mode == "code") {
				html = "&lt;DIV&gt;" + html + "&lt;/DIV&gt;";
				ed_wr_content.insertContents(html);
			} else {
				html = html;
			}
		} else {
			document.getElementById("wr_content").value += html + "\n";
		}
	} catch (ex) {
		this.debug(ex);
	};
}

function get_file_info(val) {
	try {
		var arr = val.split('|');
		var ret = {"bf_no":arr[0], "bf_source":arr[1], "bf_file":arr[2], "bf_filesize":arr[3], "bf_width":arr[4], "bf_type":arr[5]};
		return ret;
	} catch (ex) {
		this.debug(ex);
	};
}
