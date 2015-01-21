//tab1
function cur(ele){ 
	$(ele).addClass("SELECTED").siblings().removeClass("SELECTED"); 
} 
function tab(id_tab,tag_tab,id_con,tag_con,act){ 
	$(id_tab).find(tag_tab).eq(0).addClass("SELECTED"); 
	$(id_con).find(tag_con).eq(0).show().siblings(tag_con).hide(); 
	if(!act){ act="click"}; 
	if(act=="click"){ 
	$(id_tab).find(tag_tab).each(function(i){ 
	$(id_tab).find(tag_tab).eq(i).click(function(){ 
	cur(this); 
	$(id_con).find(tag_con).eq(i).show().siblings(tag_con).hide();})}) 
} if(act=="mouseover"){ 
	$(id_tab).find(tag_tab).each(function(i){ 
	$(id_tab).find(tag_tab).eq(i).mouseover(function(){ 
	cur(this); 
	$(id_con).find(tag_con).eq(i).show().siblings(tag_con).hide(); 
})})} 
} 
//

(function () {
    $.fn.infiniteCarousel = function () {
        function repeat(str, n) {
            return new Array( n + 1 ).join(str);
        }
        return this.each(function () {
            // magic!
            var $wrapper = $('> div', this).css('overflow', 'hidden'),
                $slider = $wrapper.find('> ul').width(9999),
                $items = $slider.find('> li'),
                $single = $items.filter(':first')
                
                singleWidth = $single.outerWidth(),
                visible = Math.ceil($wrapper.innerWidth() / singleWidth),
                currentPage = 1,
                pages = Math.ceil($items.length / visible);
            /* TASKS */
            // 1. pad the pages with empty element if required
            if ($items.length % visible != 0) {
                // pad
                $slider.append(repeat('<li class="empty" />', visible - ($items.length % visible)));
                $items = $slider.find('> li');
            }
            // 2. create the carousel padding on left and right (cloned)
            $items.filter(':first').before($items.slice(-visible).clone().addClass('cloned'));
            $items.filter(':last').after($items.slice(0, visible).clone().addClass('cloned'));
            $items = $slider.find('> li');
            // 3. reset scroll
            $wrapper.scrollLeft(singleWidth * visible);
            // 4. paging function
            function gotoPage(page) {
                var dir = page < currentPage ? -1 : 1,
                    n = Math.abs(currentPage - page),
                    left = singleWidth * dir * visible * n;
                $wrapper.filter(':not(:animated)').animate({
                    scrollLeft : '+=' + left
                }, 500, function () {
                    // if page == last page - then reset position
                    if (page > pages) {
                        $wrapper.scrollLeft(singleWidth * visible);
                        page = 1;
                    } else if (page == 0) {
                        page = pages;
                        $wrapper.scrollLeft(singleWidth * visible * pages);
                    }
                    currentPage = page;
                });
            }
            // 5. insert the back and forward link
            $wrapper.after('<a href="#" class="arrow back">&lt;</a><a href="#" class="arrow forward">&gt;</a>');
            // 6. bind the back and forward links
            $('a.back', this).click(function () {
                gotoPage(currentPage - 1);
                return false;
            });
            $('a.forward', this).click(function () {
                gotoPage(currentPage + 1);
                return false;
            });
            $(this).bind('goto', function (event, page) {
                gotoPage(page);
            });
            // THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL
            $(this).bind('next', function () {
                gotoPage(currentPage + 1);
            });
        });
    };
})(jQuery);

//================
function resizepic(thispic){
	if(thispic.width>650) thispic.width=650;
}

//无级缩放图片大小
function bbimg(o){
	var zoom=parseInt(o.style.zoom, 10)||100;
	zoom+=event.wheelDelta/12;
	if (zoom>0) o.style.zoom=zoom+'%';
	return false;
}

function showMood(){
	$("#MOOD").load(str00+"/themes/Sean_Cms/plugin/Sean_case/Sean_Action.asp?act=showmood");
﻿}

function ShowLabel(c,id,str){
	switch (c){ 
	case 1: ﻿idbox="."+id;break;
	case 2: idbox="#"+id;break;
	}$(idbox).load(str00+"/themes/Sean_Cms/INCLUDE/"+str+".TXT");
};
//flash幻灯开始
$(function(){
	var len  = $(".num > li").length;
	var index = 0;
	var adTimer;
	$(".num li").mouseover(function(){
	  index  =   $(".num li").index(this);
	  showImg(index);
	}).eq(0).mouseover();	
	
	$('.FLASH_IMG').hover(function(){clearInterval(adTimer);
	   },function(){
		   adTimer = setInterval(function(){
			  index++;
			  if(index==len){index=0;}
			  showImg(index);	
			} , 5000);
	}).trigger("mouseleave");
})

function showImg(index){
	var adHeight = $(".FLASH_IMG").height();
	$(".slider").stop(true,false).animate({top : -adHeight*index},1000);
	$(".num li").removeClass("on")
		.eq(index).addClass("on");
}
//flash幻灯结束
$(document).ready(function(){
    var autoscrolling = true;
    $('.infiniteCarousel').infiniteCarousel().mouseover(function(){autoscrolling=false;}).mouseout(function(){autoscrolling=true;});
    setInterval(function(){if (autoscrolling){$('.infiniteCarousel').trigger('next');}},5000);
	tab("#TRI","LI","#PICBOX","UL","mouseover");
	tab("#TRI_SIDEBAR","LI","#PICBOX_SIDEBAR","UL","mouseover");
	showMood();var id1=0;id1=window.setInterval("showMood()",60000);
});