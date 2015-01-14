
<!-- content start -->
<div class="admin-content">
  <div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><?php echo $core->name; ?></strong> - <small><?php echo $core->lang['msg']['article_edit']; ?></small></div>
  </div>

  <div class="am-tabs am-margin" data-am-tabs>
    <ul class="am-tabs-nav am-nav am-nav-tabs">
      <li class="am-active"><a href="#tab1">新建文章</a></li>
   <!--    <li><a href="#tab2">文章类别</a></li>
      <li><a href="#tab3">文章标签</a></li> -->
    </ul>

    <div class="am-tabs-bd">
      <div class="am-tab-panel am-fade am-in am-active" id="tab1">
         <form class="am-form" action="#" id='article_form' method="post">
          <div class="am-g am-margin-top">
            <div class="am-u-sm-2 am-text-right">
              文章标题
            </div>
            <div class="am-u-sm-4">
              <input type="text" name="title" id='title' class="am-input-sm">
            </div>
            <div class="am-u-sm-6">*必填，不可重复</div>
          </div>

          <div class="am-g am-margin-top">
            <div class="am-u-sm-2 am-text-right">
              文章作者
            </div>
            <div class="am-u-sm-4 col-end">
              <input type="text" id="author" name="author" value="<?php echo getVars('username','COOKIE'); ?>" class="am-input-sm">
            </div>
            <div class="am-u-sm-6">*必填</div>
          </div>

          <div class="am-g am-margin-top">
            <div class="am-u-sm-2 am-text-right">
              内容摘要
            </div>
            <div class="am-u-sm-4">
              <input type="text" name="intro" id="intro" class="am-input-sm">
            </div>
            <div class="am-u-sm-6">不填写则自动截取内容前255字符</div>
          </div>

          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              内容描述
            </div>
            <div class="am-u-sm-10">
              <textarea id='content' name="content"  rows="20" cols="100" style="width:95%;height:400px;visibility:hidden;"></textarea>
            </div>
          </div>

          <div class="am-g am-margin-top">
          <div class="am-u-sm-2 am-text-right">所属类别</div>
          <div class="am-u-sm-8">
          <select id=cate name=cate>
            <?php getArtCate(); ?>
          </select>
          </div>
         <div class="am-u-sm-2"><a class="am-btn am-btn-link" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0, width: 400, height: 225}">新建类别</a></div>
        </div>
        <div class="am-g am-margin-top">
          <div class="am-u-sm-2 am-text-right">文章标签</div>
          <div class="am-u-sm-8">
            <div id='tag' class="am-btn-group" data-am-button>
            <?php getTags(); ?>
            </div>
          </div>
         <div class="am-u-sm-2"><a class="am-btn am-btn-link" data-am-modal="{target: '#doc-modal-2', closeViaDimmer: 0, width: 400, height: 225}">新建标签</a></div>
        </div>
        <div class="am-g am-margin-top">
          <div class="am-u-sm-2 am-text-right">显示状态</div>
          <div class="am-u-sm-8">
            <div class="am-btn-group" data-am-button>
              <label class="am-btn am-btn-default am-btn-xs">
                <input type="radio" name="status" value="1" id="option1"> 正常
              </label>
              <label class="am-btn am-btn-default am-btn-xs">
                <input type="radio" name="status" value="0" id="option2"> 不显示
              </label>
            </div>
          </div>
          <div class="am-u-sm-2 am-text-right">不选择将显示</div>
        </div>

        <div class="am-g am-margin-top">
          <div class="am-u-sm-2 am-text-right">推荐类型</div>
          <div class="am-u-sm-10">
            <div class="am-btn-group" data-am-button>
              <label class="am-btn am-btn-default am-btn-xs">
                <input type="checkbox" name="type[]" value="is_top"> 置顶
              </label>
              <label class="am-btn am-btn-default am-btn-xs">
                <input type="checkbox" name="type[]" value="is_re"> 推荐
              </label>
            </div>
          </div>
        </div>

        <div class="am-g am-margin-top">
          <div class="am-u-sm-2 am-text-right">
            发布时间
          </div>
          <div class="am-u-sm-6">
              <div class="am-form-group am-form-icon">
                <i class="am-icon-calendar"></i>
                <input type="text" name="time" class="am-form-field am-input-sm" value="<?php echo date('Y-m-d',time()); ?>" placeholder="时间">
              </div>
          </div>
          <div class="am-u-sm-4">
           	选填.不填显示系统当前时间
          </div>
        </div>

        <div class="am-margin">
          <button type="submit" id='article_save' class="am-btn am-btn-primary am-btn-xs">发布文章</button>
          <button type="button" class="am-btn am-btn-primary am-btn-xs">放弃保存</button>
        </div>
        </form>

      </div>
    </div>
  </div>

</div>
<!-- 新建类别 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">新建类别
      <a id='cate_close' href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <input type="text" id='cate_name' class="am-form-field am-input-sm" placeholder="请输入类别名称">
      <input type="text" id="cate_intro" class="am-form-field am-input-sm" placeholder="简单的描述一下">
      <input type="text" id="cate_order" class="am-form-field am-input-sm" placeholder="请输入显示序号">
      <button type="button" id='save_cate' class="am-btn am-btn-primary am-btn-xs">保存</button>
    </div>
  </div>
</div>
<!-- 新建标签 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-2">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">新建标签
      <a id='tag_close' href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <input type="text" id='tag_name' class="am-form-field am-input-sm" placeholder="请输入标签名称">
      <input type="text" id='tag_order' class="am-form-field am-input-sm" placeholder="显示序号(输入一个数字)">
      <button type="button" id='save_tag' class="am-btn am-btn-primary am-btn-xs">保存</button>      
    </div>
  </div>
</div>
<!-- content end -->
<script src="<?php echo $core->assets; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $core->assets.'plugin/kindeditor/kindeditor-min.js' ; ?>"></script>
<script type='text/javascript'>
KindEditor.ready(function(K) {
	K.create('#content', {
		allowFileManager : true
	});
});
$('#save_cate').click(function(){
	var n = $('#cate_name').val();
	var o = $('#cate_order').val();
	var i = $('#cate_intro').val();
	if(isNaN(o)){
		alert('显示序号不规范');
	}
	$.post('./cmd.php?act=saveCate',{'name':n,'order':o,'intro':i},function(data){
    //var result = eval("("+data+")"); 转数组
    var result = $.parseJSON(data); //转对象
		if(result.status){
      var id = result.value;
      $("#cate").prepend("<option value="+id+">"+n+"</option>");
      $("#cate_close").trigger("click"); 
    }
    alert(result.msg);
	})

})

$('#save_tag').click(function(){
  var n = $('#tag_name').val();
  var o = $('#tag_order').val();
  if(isNaN(o)){
    alert('显示序号不规范');
  }
  $.post('./cmd.php?act=saveTag',{'name':n,'order':o},function(data){
    //var result = eval("("+data+")"); 转数组
    var result = $.parseJSON(data); //转对象
    if(result.status){
      var id = result.value;
      $("#tag").prepend(result.value);
      $("#tag_close").trigger("click"); 
    }
    alert(result.msg);
  })

})

$('#article_save').click(function(){
  var title = $('#title').val();
  if((title=='')){
    alert('敢不敢不要留空');
    return false;
  }
  $("form").attr("action","cmd.php?act=saveActicle");

})
 

</script>
