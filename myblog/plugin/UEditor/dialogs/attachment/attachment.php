<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ueditor图片对话框</title>
    <script type="text/javascript" src="../internal.js"></script>

    <!-- jquery -->
    <script type="text/javascript" src="../../../../../system/script/common.js"></script>

    <!-- webuploader -->
    <script src="../../third-party/webuploader/webuploader.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../third-party/webuploader/webuploader.css">

    <!-- attachment dialog -->
    <link rel="stylesheet" href="attachment.css" type="text/css" />
</head>
<body>

    <div class="wrapper">
        <div id="tabhead" class="tabhead">
            <span class="tab focus" data-content-id="upload"><var id="lang_tab_upload"></var></span>
        </div>
        <div id="tabbody" class="tabbody">
            <!-- 上传图片 -->
            <div id="upload" class="panel focus">
                <div id="queueList" class="queueList">
                    <div class="statusBar element-invisible">
                        <div class="progress">
                            <span class="text">0%</span>
                            <span class="percentage"></span>
                        </div><div class="info"></div>
                        <div class="btns">
                            <div id="filePickerBtn"></div>
                            <div class="uploadBtn"><var id="lang_start_upload"></var></div>
                        </div>
                    </div>
                    <div id="dndArea" class="placeholder">
                        <div class="filePickerContainer">
                            <div id="filePickerReady"></div>
                        </div>
                    </div>
                    <ul class="filelist element-invisible">
                        <li id="filePickerBlock" class="filePickerBlock"></li>
                    </ul>
                </div>
            </div>


        </div>
    </div>
    <script type="text/javascript" src="attachment.js"></script>

</body>
</html>