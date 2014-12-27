<?php
/**
 * @package ABlog
 * @author zhaofei
 * @copyright ablog
 */

/**
 * 返回配置
 * @param
 * @return array
 */
return array(

	// '---------------------------------关闭网站-----------------------------------
	'A_SITE_TURNOFF'=>false,

	// '---------------------------------网站基本设置-----------------------------------
	'A_BLOG_HOST' => 'http://localhost/',
	'A_BLOG_NAME' => '我的a blog',
	'A_BLOG_SUBNAME' => 'happy every day',
	'A_BLOG_THEME' => 'default',
	'A_BLOG_CSS' => 'default',
	'A_BLOG_COPYRIGHT' => 'Copyright by a blog',
	'A_BLOG_LANGUAGE' => 'zh-CN',
	'A_BLOG_LANGUAGEPACK' => 'SimpChinese',

	// '----------------------------数据库配置---------------------------------------
	//mysql|sqlite|sqlite3|pdo_mysql
	'A_DATABASE_TYPE'=> '',

	'A_SQLITE_NAME' => '',
	'A_SQLITE_PRE' => 'APP_',

	'A_MYSQL_SERVER' => 'localhost',
	'A_MYSQL_USERNAME' => 'root',
	'A_MYSQL_PASSWORD' => '',
	'A_MYSQL_NAME' => '',
	'A_MYSQL_CHARSET' => 'utf8',
	'A_MYSQL_PRE' => 'APP_',
	'A_MYSQL_ENGINE'=>'MyISAM',
    'A_MYSQL_PORT' => '3306',
    'A_MYSQL_PERSISTENT' => false,

	// '---------------------------------插件----------------------------------------
	'A_USING_PLUGIN_LIST' => '',

	// '-------------------------------全局配置-----------------------------------
	'A_YUN_SITE'=>'',
	'A_DEBUG_MODE' => true,
	'A_DEBUG_MODE_STRICT' => false,
	'A_DEBUG_MODE_WARNING' => true,
	'A_BLOG_CLSID' => '',
	'A_TIME_ZONE_NAME' => 'Asia/Shanghai',
	'A_UPDATE_INFO_URL' => 'http://update.zblogcn.com/info/',
	// '固定域名,默认为false,如启用则'A_BLOG_HOST生效而'A_MULTI_DOMAIN_SUPPORT无效
	'A_PERMANENT_DOMAIN_ENABLE' => false,
	'A_MULTI_DOMAIN_SUPPORT' => false,

	// '当前 Z-Blog 版本

	'A_BLOG_PRODUCT' => 'Z-BlogPHP',
	'A_BLOG_VERSION' => '',
	'A_BLOG_PRODUCT_FULL' => '',
	'A_BLOG_PRODUCT_HTML' => '',
	'A_BLOG_PRODUCT_FULLHTML' => '',


	// '留言评论
	'A_COMMENT_TURNOFF' => false,
	'A_COMMENT_VERIFY_ENABLE' => false,
	'A_COMMENT_REVERSE_ORDER' => false,


	// '验证码
	'A_VERIFYCODE_STRING' => 'ABCDEFGHKMNPRSTUVWXYZ123456789',
	'A_VERIFYCODE_WIDTH' => 90,
	'A_VERIFYCODE_HEIGHT' => 30,
	'A_VERIFYCODE_FONT' => 'system/defend/arial.ttf',

	// '页面各项列数
	'A_DISPLAY_COUNT' => 10,
	'A_SEARCH_COUNT' => 25,
	'A_PAGEBAR_COUNT' => 10,
	'A_COMMENTS_DISPLAY_COUNT' => 100,

	'A_DISPLAY_SUBCATEGORYS' => false,

	// '杂项
	'A_RSS2_COUNT' => 10,
	'A_RSS_EXPORT_WHOLE' => true,

	// '后台管理
	'A_MANAGE_COUNT' => 50,

	// '表情相关
	'A_EMOTICONS_FILENAME' => 'face',

	'A_EMOTICONS_FILETYPE' => 'png|gif|jpg',

	'A_EMOTICONS_FILESIZE' => '16',


	// '上传相关
	'A_UPLOAD_FILETYPE' => 'jpg|gif|png|jpeg|bmp|psd|wmf|ico|rpm|deb|tar|gz|sit|7z|bz2|zip|rar|xml|xsl|svg|svgz|doc|docx|ppt|pptx|xls|xlsx|wps|chm|txt|pdf|mp3|avi|mpg|rm|ra|rmvb|mov|wmv|wma|swf|fla|torrent|apk|zba',

	'A_UPLOAD_FILESIZE' => 2,

	// '用户名,密码,评论长度等限制
	'A_USERNAME_MIN' => 3,

	'A_USERNAME_MAX' => 50,

	'A_PASSWORD_MIN' => 8,

	'A_PASSWORD_MAX' => 20,

	'A_EMAIL_MAX' => 50,

	'A_HOMEPAGE_MAX' => 100,

	'A_CONTENT_MAX' => 1000,

	// '自动摘要字数
	'A_ARTICLE_EXCERPT_MAX' => 250,

	// '侧栏评论最大字数
	'A_COMMENT_EXCERPT_MAX' => 20,

	// '---------------------------------静态化配置-----------------------------------
	// '文章,页面类,列表页的静态模式ACTIVE or REWRITE
	'A_STATIC_MODE' => 'ACTIVE',

	'A_ARTICLE_REGEX' => '{%host%}?id={%id%}',

	'A_PAGE_REGEX' => '{%host%}?id={%id%}',

	'A_CATEGORY_REGEX' => '{%host%}?cate={%id%}&page={%page%}',

	'A_AUTHOR_REGEX' => '{%host%}?auth={%id%}&page={%page%}',

	'A_TAGS_REGEX' => '{%host%}?tags={%id%}&page={%page%}',

	'A_DATE_REGEX' => '{%host%}?date={%date%}&page={%page%}',

	'A_INDEX_REGEX' => '{%host%}?page={%page%}',

	#首页，分类页，文章页，页面页的默认模板
	'A_INDEX_DEFAULT_TEMPLATE' => 'index',
	'A_POST_DEFAULT_TEMPLATE' => 'single',

	'A_SIDEBAR_ORDER' => 'calendar|controlpanel|catalog|searchpanel|comments|archives|favorite|link|misc',

	'A_SIDEBAR2_ORDER' => '',

	'A_SIDEBAR3_ORDER' => '',

	'A_SIDEBAR4_ORDER' => '',

	'A_SIDEBAR5_ORDER' => '',
	// '--------------------------其它----------------------------------------
	'A_GZIP_ENABLE'=>false,
	'A_ADMIN_HTML5_ENABLE'=>true,
	// '代码高亮
	'A_SYNTAXHIGHLIGHTER_ENABLE' => true,

	// '源码编辑高亮
	'A_CODEMIRROR_ENABLE' => true,
	'A_HTTP_LASTMODIFIED' => false,
	'A_MODULE_CATALOG_STYLE'=>0,
	'A_VIEWNUMS_TURNOFF' => false,
	'A_LISTONTOP_TURNOFF' => false,
	'A_RELATEDLIST_COUNT'=>10,
	'A_RUNINFO_DISPLAY' => true,
	'A_POST_ALIAS_USE_ID_NOT_TITLE' => false,
)
?>