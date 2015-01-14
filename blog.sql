/*
Navicat MySQL Data Transfer

Source Server         : local_dev
Source Server Version : 50528
Source Host           : localhost:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50528
File Encoding         : 65001

Date: 2015-01-14 15:31:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `feifei_category`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_category`;
CREATE TABLE `feifei_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `order` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `intro` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_category
-- ----------------------------
INSERT INTO `feifei_category` VALUES ('25', '2015', '1', '0', 'new year get strong');
INSERT INTO `feifei_category` VALUES ('26', '2015', '1', '0', 'new year get strong');
INSERT INTO `feifei_category` VALUES ('27', '2015', '1', '0', 'new year get strong');
INSERT INTO `feifei_category` VALUES ('28', '2014', '2', '0', 'old year');
INSERT INTO `feifei_category` VALUES ('29', '没有', '3', '0', '哈哈');
INSERT INTO `feifei_category` VALUES ('30', '你说', '21', '0', '好汪');

-- ----------------------------
-- Table structure for `feifei_comment`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_comment`;
CREATE TABLE `feifei_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `is_checking` tinyint(1) NOT NULL DEFAULT '0',
  `root_id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `author_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `home_page` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `post_time` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `agent` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `feifei_comm_PT` (`post_time`),
  KEY `feifei_comm_RIL` (`root_id`,`is_checking`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `feifei_config`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_config`;
CREATE TABLE `feifei_config` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_config
-- ----------------------------
INSERT INTO `feifei_config` VALUES ('system', 'a:91:{s:15:\"ZC_SITE_TURNOFF\";b:0;s:12:\"ZC_BLOG_HOST\";s:16:\"{#ZC_BLOG_HOST#}\";s:12:\"ZC_BLOG_NAME\";s:13:\"hello zhaofei\";s:15:\"ZC_BLOG_SUBNAME\";s:17:\"Good Luck To You!\";s:13:\"ZC_BLOG_THEME\";s:9:\"HTML5CSS3\";s:11:\"ZC_BLOG_CSS\";s:9:\"html5css3\";s:17:\"ZC_BLOG_COPYRIGHT\";s:44:\"Copyright Your WebSite.Some Rights Reserved.\";s:16:\"ZC_BLOG_LANGUAGE\";s:5:\"zh-CN\";s:20:\"ZC_BLOG_LANGUAGEPACK\";s:11:\"SimpChinese\";s:16:\"ZC_DATABASE_TYPE\";s:5:\"mysql\";s:14:\"ZC_SQLITE_NAME\";s:0:\"\";s:13:\"ZC_SQLITE_PRE\";s:4:\"APP_\";s:15:\"ZC_MYSQL_SERVER\";s:9:\"localhost\";s:17:\"ZC_MYSQL_USERNAME\";s:4:\"root\";s:17:\"ZC_MYSQL_PASSWORD\";s:7:\"zhaofei\";s:13:\"ZC_MYSQL_NAME\";s:4:\"blog\";s:16:\"ZC_MYSQL_CHARSET\";s:4:\"utf8\";s:12:\"ZC_MYSQL_PRE\";s:5:\"APP__\";s:15:\"ZC_MYSQL_ENGINE\";s:6:\"MyISAM\";s:13:\"ZC_MYSQL_PORT\";s:4:\"3306\";s:19:\"ZC_MYSQL_PERSISTENT\";b:0;s:20:\"ZC_USING_PLUGIN_LIST\";s:17:\"AppCentre|UEditor\";s:11:\"ZC_YUN_SITE\";s:0:\"\";s:13:\"ZC_DEBUG_MODE\";b:0;s:20:\"ZC_DEBUG_MODE_STRICT\";b:0;s:21:\"ZC_DEBUG_MODE_WARNING\";b:1;s:13:\"ZC_BLOG_CLSID\";s:22:\"549975ca0499e330559900\";s:17:\"ZC_TIME_ZONE_NAME\";s:13:\"Asia/Shanghai\";s:18:\"ZC_UPDATE_INFO_URL\";s:31:\"http://update.zblogcn.com/info/\";s:26:\"ZC_PERMANENT_DOMAIN_ENABLE\";b:0;s:23:\"ZC_MULTI_DOMAIN_SUPPORT\";b:0;s:15:\"ZC_BLOG_PRODUCT\";s:9:\"Z-BlogPHP\";s:15:\"ZC_BLOG_VERSION\";s:22:\"1.3 Wonce Build 140614\";s:20:\"ZC_BLOG_PRODUCT_FULL\";s:32:\"Z-BlogPHP 1.3 Wonce Build 140614\";s:20:\"ZC_BLOG_PRODUCT_HTML\";s:93:\"<a href=\"http://www.zblogcn.com/\" title=\"RainbowSoft Z-BlogPHP\" target=\"_blank\">Z-BlogPHP</a>\";s:24:\"ZC_BLOG_PRODUCT_FULLHTML\";s:116:\"<a href=\"http://www.zblogcn.com/\" title=\"RainbowSoft Z-BlogPHP\" target=\"_blank\">Z-BlogPHP 1.3 Wonce Build 140614</a>\";s:18:\"ZC_COMMENT_TURNOFF\";b:0;s:24:\"ZC_COMMENT_VERIFY_ENABLE\";b:0;s:24:\"ZC_COMMENT_REVERSE_ORDER\";b:0;s:20:\"ZC_VERIFYCODE_STRING\";s:30:\"ABCDEFGHKMNPRSTUVWXYZ123456789\";s:19:\"ZC_VERIFYCODE_WIDTH\";i:90;s:20:\"ZC_VERIFYCODE_HEIGHT\";i:30;s:18:\"ZC_VERIFYCODE_FONT\";s:26:\"zb_system/defend/arial.ttf\";s:16:\"ZC_DISPLAY_COUNT\";i:10;s:15:\"ZC_SEARCH_COUNT\";i:25;s:16:\"ZC_PAGEBAR_COUNT\";i:10;s:25:\"ZC_COMMENTS_DISPLAY_COUNT\";i:100;s:23:\"ZC_DISPLAY_SUBCATEGORYS\";b:0;s:13:\"ZC_RSS2_COUNT\";i:10;s:19:\"ZC_RSS_EXPORT_WHOLE\";b:1;s:15:\"ZC_MANAGE_COUNT\";i:50;s:21:\"ZC_EMOTICONS_FILENAME\";s:4:\"face\";s:21:\"ZC_EMOTICONS_FILETYPE\";s:11:\"png|gif|jpg\";s:21:\"ZC_EMOTICONS_FILESIZE\";s:2:\"16\";s:18:\"ZC_UPLOAD_FILETYPE\";s:185:\"jpg|gif|png|jpeg|bmp|psd|wmf|ico|rpm|deb|tar|gz|sit|7z|bz2|zip|rar|xml|xsl|svg|svgz|doc|docx|ppt|pptx|xls|xlsx|wps|chm|txt|pdf|mp3|avi|mpg|rm|ra|rmvb|mov|wmv|wma|swf|fla|torrent|apk|zba\";s:18:\"ZC_UPLOAD_FILESIZE\";i:2;s:15:\"ZC_USERNAME_MIN\";i:3;s:15:\"ZC_USERNAME_MAX\";i:50;s:15:\"ZC_PASSWORD_MIN\";i:8;s:15:\"ZC_PASSWORD_MAX\";i:20;s:12:\"ZC_EMAIL_MAX\";i:50;s:15:\"ZC_HOMEPAGE_MAX\";i:100;s:14:\"ZC_CONTENT_MAX\";i:1000;s:22:\"ZC_ARTICLE_EXCERPT_MAX\";i:250;s:22:\"ZC_COMMENT_EXCERPT_MAX\";i:20;s:14:\"ZC_STATIC_MODE\";s:6:\"ACTIVE\";s:16:\"ZC_ARTICLE_REGEX\";s:18:\"{%host%}?id={%id%}\";s:13:\"ZC_PAGE_REGEX\";s:18:\"{%host%}?id={%id%}\";s:17:\"ZC_CATEGORY_REGEX\";s:34:\"{%host%}?cate={%id%}&page={%page%}\";s:15:\"ZC_AUTHOR_REGEX\";s:34:\"{%host%}?auth={%id%}&page={%page%}\";s:13:\"ZC_TAGS_REGEX\";s:34:\"{%host%}?tags={%id%}&page={%page%}\";s:13:\"ZC_DATE_REGEX\";s:36:\"{%host%}?date={%date%}&page={%page%}\";s:14:\"ZC_INDEX_REGEX\";s:22:\"{%host%}?page={%page%}\";s:25:\"ZC_INDEX_DEFAULT_TEMPLATE\";s:5:\"index\";s:24:\"ZC_POST_DEFAULT_TEMPLATE\";s:6:\"single\";s:16:\"ZC_SIDEBAR_ORDER\";s:74:\"calendar|searchpanel|controlpanel|catalog|comments|tags|authors|statistics\";s:17:\"ZC_SIDEBAR2_ORDER\";s:0:\"\";s:17:\"ZC_SIDEBAR3_ORDER\";s:0:\"\";s:17:\"ZC_SIDEBAR4_ORDER\";s:0:\"\";s:17:\"ZC_SIDEBAR5_ORDER\";s:0:\"\";s:14:\"ZC_GZIP_ENABLE\";b:0;s:21:\"ZC_ADMIN_HTML5_ENABLE\";b:1;s:27:\"ZC_SYNTAXHIGHLIGHTER_ENABLE\";b:1;s:20:\"ZC_CODEMIRROR_ENABLE\";b:1;s:20:\"ZC_HTTP_LASTMODIFIED\";b:0;s:23:\"ZC_MODULE_CATALOG_STYLE\";i:0;s:19:\"ZC_VIEWNUMS_TURNOFF\";b:0;s:20:\"ZC_LISTONTOP_TURNOFF\";b:0;s:20:\"ZC_RELATEDLIST_COUNT\";i:10;s:18:\"ZC_RUNINFO_DISPLAY\";b:1;s:30:\"ZC_POST_ALIAS_USE_ID_NOT_TITLE\";b:0;}');
INSERT INTO `feifei_config` VALUES ('cache', 'a:12:{s:13:\"templates_md5\";s:32:\"05a05e2fb4f74862caf773b3f4dbdc25\";s:16:\"reload_statistic\";s:687:\"<tr><td class=\'td20\'>当前用户</td><td class=\'td30\'>{$zbp->user->Name}</td><td class=\'td20\'>当前版本</td><td class=\'td30\'>1.3 Wonce Build 140614</td></tr><tr><td class=\'td20\'>文章总数</td><td>1</td><td>分类总数</td><td>1</td></tr><tr><td class=\'td20\'>页面总数</td><td>1</td><td>标签总数</td><td>0</td></tr><tr><td class=\'td20\'>评论总数</td><td>0</td><td>浏览总数</td><td>6</td></tr><tr><td class=\'td20\'>当前主题/当前样式</td><td>HTML5CSS3/html5css3</td><td>用户总数</td><td>1</td></tr><tr><td class=\'td20\'>离线客户端地址</td><td>{#ZC_BLOG_HOST#}system/xml-rpc/</td><td>系统环境</td><td>WINNT;nginx1.7.0;PHP5.4.30;mysql;curl</td></tr>\";s:21:\"reload_statistic_time\";i:1419413805;s:18:\"system_environment\";s:37:\"WINNT;nginx1.7.0;PHP5.4.30;mysql;curl\";s:19:\"normal_article_nums\";s:1:\"1\";s:17:\"reload_updateinfo\";s:1093:\"<tr><td><p><a href=\"http://www.zblogcn.com/zblogphp/\" target=\"_blank\" style=\"color:crimson\"><b>Z-BlogPHP 1.3 Wonce 正式版发布了，欢迎下载安装和升级。 (2014-6-14)</b></a></p>\r\n\r\n<p><a href=\"http://www.zblogcn.com/\" target=\"_blank\" style=\"color:#ff6600\"><b>Z-Blog启用新域名：www.zblogcn.com，原官方网站www.rainbowsoft.org正式变更为www.zblogcn.com。</b></a></p>\r\n\r\n<p><a href=\"http://www.zblogcn.com/zblogphp/\" target=\"_blank\" style=\"color:crimson\">2014年重磅发布，Z-BlogPHP 1.2 Hippo 正式版发布了！ (2014-2-20)</a></p>\r\n\r\n<p><a href=\"http://bbs.zblogcn.com/thread-83785-1-1.html\" target=\"_blank\" style=\"color:blue\">2014年ASP版全新发布！Z-Blog 2.2 Prism Build 140101 发布了。(2014-01-02)</a></p>\r\n\r\n<p><a href=\"http://www.zblogcn.com/zblogphp/\" target=\"_blank\">Z-BlogPHP 1.1 Taichi 正式版已于2013年12月21号发布，欢迎下载！(2013-12-22)</a></p>\r\n\r\n<p><a href=\"http://bbs.zblogcn.com/thread-77001-1-1.html\" target=\"_blank\">Z-Blog ASP和PHP的应用中心正式上线了!欢迎开发者进驻。(2013-01-01)</a></p></td></tr>\";s:22:\"reload_updateinfo_time\";i:1419407026;s:17:\"ZC_SIDEBAR_ORDER1\";s:78:\"calendar|controlpanel|catalog|searchpanel|comments|archives|favorite|link|misc\";s:17:\"ZC_SIDEBAR_ORDER2\";s:0:\"\";s:17:\"ZC_SIDEBAR_ORDER3\";s:0:\"\";s:17:\"ZC_SIDEBAR_ORDER4\";s:0:\"\";s:17:\"ZC_SIDEBAR_ORDER5\";s:0:\"\";}');

-- ----------------------------
-- Table structure for `feifei_counter`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_counter`;
CREATE TABLE `feifei_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `agent` text NOT NULL,
  `refer` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `post_time` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `post_data` text NOT NULL,
  `all_request_header` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_counter
-- ----------------------------

-- ----------------------------
-- Table structure for `feifei_module`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_module`;
CREATE TABLE `feifei_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `file_name` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sidebar_id` int(11) NOT NULL DEFAULT '0',
  `html_id` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(5) NOT NULL DEFAULT '',
  `max_li` int(11) NOT NULL DEFAULT '0',
  `source` varchar(50) NOT NULL DEFAULT '',
  `is_hide` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_module
-- ----------------------------
INSERT INTO `feifei_module` VALUES ('1', '导航栏', 'navbar', '<li id=\"nvabar-item-index\"><a href=\"{#ZC_BLOG_HOST#}\">首页</a></li><li id=\"navbar-page-2\"><a href=\"{#ZC_BLOG_HOST#}?id=2\">留言本</a></li>', '0', 'divNavBar', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('2', '日历', 'calendar', '', '0', 'divCalendar', 'div', '0', 'system', '1');
INSERT INTO `feifei_module` VALUES ('3', '控制面板', 'controlpanel', '<span class=\"cp-hello\">您好,欢迎到访网站!</span><br/><span class=\"cp-login\"><a href=\"{#ZC_BLOG_HOST#}zb_system/cmd.php?act=login\">[管理登陆]</a></span>&nbsp;&nbsp;<span class=\"cp-vrs\"><a href=\"{#ZC_BLOG_HOST#}zb_system/cmd.php?act=misc&amp;type=vrs\">[查看权限]</a></span>', '0', 'divContorPanel', 'div', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('4', '网站分类', 'catalog', '', '0', 'divCatalog', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('5', '搜索', 'searchpanel', '<form name=\"search\" method=\"post\" action=\"{#ZC_BLOG_HOST#}zb_system/cmd.php?act=search\"><input type=\"text\" name=\"q\" size=\"11\" /> <input type=\"submit\" value=\"搜索\" /></form>', '0', 'divSearchPanel', 'div', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('6', '最新留言', 'comments', '', '0', 'divComments', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('7', '文章归档', 'archives', '', '0', 'divArchives', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('8', '站点信息', 'statistics', '<li>文章总数:1</li><li>页面总数:1</li><li>分类总数:1</li><li>标签总数:0</li><li>评论总数:0</li><li>浏览总数:6</li>', '0', 'divStatistics', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('9', '网站收藏', 'favorite', '<li><a href=\"http://bbs.zblogcn.com/\" target=\"_blank\">ZBlogger社区</a></li><li><a href=\"http://app.zblogcn.com/\" target=\"_blank\">Z-Blog应用中心</a></li><li><a href=\"http://weibo.com/zblogcn\" target=\"_blank\">Z-Blog新浪官微</a></li><li><a href=\"http://t.qq.com/zblogcn\" target=\"_blank\">Z-Blog腾讯官微</a></li>', '0', 'divFavorites', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('10', '友情链接', 'link', '<li><a href=\"http://www.dbshost.cn/\" target=\"_blank\" title=\"独立博客服务 Z-Blog官方主机\">DBS主机</a></li>', '0', 'divLinkage', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('11', '图标汇集', 'misc', '<li><a href=\"http://www.zblogcn.com/\" target=\"_blank\"><img src=\"{#ZC_BLOG_HOST#}zb_system/image/logo/zblog.gif\" height=\"31\" width=\"88\" alt=\"RainbowSoft Studio Z-Blog\" /></a></li><li><a href=\"{#ZC_BLOG_HOST#}feed.php\" target=\"_blank\"><img src=\"{#ZC_BLOG_HOST#}zb_system/image/logo/rss.png\" height=\"31\" width=\"88\" alt=\"订阅本站的 RSS 2.0 新闻聚合\" /></a></li>', '0', 'divMisc', 'ul', '0', 'system', '1');
INSERT INTO `feifei_module` VALUES ('12', '作者列表', 'authors', '', '0', 'divAuthors', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('13', '最近发表', 'previous', '', '0', 'divPrevious', 'ul', '0', 'system', '0');
INSERT INTO `feifei_module` VALUES ('14', '标签列表', 'tags', '', '0', 'divTags', 'ul', '0', 'system', '0');

-- ----------------------------
-- Table structure for `feifei_post`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_post`;
CREATE TABLE `feifei_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` smallint(6) NOT NULL DEFAULT '0',
  `author` varchar(40) NOT NULL DEFAULT '0',
  `tag_ids` varchar(100) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_top` tinyint(1) NOT NULL DEFAULT '0',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `intro` text NOT NULL,
  `content` longtext NOT NULL,
  `post_time` int(11) NOT NULL DEFAULT '0',
  `comment_nums` int(11) NOT NULL DEFAULT '0',
  `view_nums` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `feifei_log_PT` (`post_time`),
  KEY `feifei_log_TISC` (`is_top`,`status`,`cate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_post
-- ----------------------------
INSERT INTO `feifei_post` VALUES ('3', '29', 'zhaofei', '1,4,5', '1', '1', '1', '大家好', '大家好', '大家好', '12345353', '0', '0');
INSERT INTO `feifei_post` VALUES ('4', '25', 'zhaofei', '1,3', '1', '1', '1', '你好 world', '你好 world', '你好 world', '1421164800', '0', '0');
INSERT INTO `feifei_post` VALUES ('5', '25', 'zhaofei', '1,3', '1', '1', '1', '你好 world', '你好 world', '你好 world', '1421164800', '0', '0');

-- ----------------------------
-- Table structure for `feifei_tag`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_tag`;
CREATE TABLE `feifei_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `order` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_tag
-- ----------------------------
INSERT INTO `feifei_tag` VALUES ('1', '生活', '1', '0');
INSERT INTO `feifei_tag` VALUES ('2', '旅行', '1', '0');
INSERT INTO `feifei_tag` VALUES ('3', '旅行', '1', '0');
INSERT INTO `feifei_tag` VALUES ('4', 'life', '3', '0');
INSERT INTO `feifei_tag` VALUES ('5', 'english', '1', '0');

-- ----------------------------
-- Table structure for `feifei_upload`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_upload`;
CREATE TABLE `feifei_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `source_name` varchar(255) NOT NULL DEFAULT '',
  `mime_type` varchar(50) NOT NULL DEFAULT '',
  `post_time` int(11) NOT NULL DEFAULT '0',
  `down_nums` int(11) NOT NULL DEFAULT '0',
  `log_id` int(11) NOT NULL DEFAULT '0',
  `intro` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_upload
-- ----------------------------

-- ----------------------------
-- Table structure for `feifei_user`
-- ----------------------------
DROP TABLE IF EXISTS `feifei_user`;
CREATE TABLE `feifei_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(36) NOT NULL DEFAULT '',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `home_page` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `post_time` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `intro` text NOT NULL,
  `articles` int(11) NOT NULL DEFAULT '0',
  `pages` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `uploads` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `feifei_mem_Name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feifei_user
-- ----------------------------
INSERT INTO `feifei_user` VALUES ('1', '549975caa85ef473382715', '1', '0', 'zhaofei', '54d0b0b95576f6864eac95b3bb418f4f', '', '', '127.0.0.1', '1419343306', '', '', '0', '0', '0', '0');
