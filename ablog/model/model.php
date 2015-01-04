<?php 
/**
 * @author zhaofei
 * @package datainfo
 * @description 数据结构
 */
return array(
    'feifei_config'=>array(
        'Name'=>array('conf_Name','string',250,''),
        'Value'=>array('conf_Value','string','',''),
    ),
    'feifei_post'=> array(
        'ID'=>array('log_ID','integer','',0),
        'CateID'=>array('log_CateID','integer','',0),
        'AuthorID'=>array('log_AuthorID','integer','',0),
        'Tag'=>array('log_Tag','string',250,''),
        'Status'=>array('log_Status','integer','',0),
        'Type'=>array('log_Type','integer','',0),
        'Alias'=>array('log_Alias','string',250,''),
        'IsTop'=>array('log_IsTop','boolean','',false),
        'IsLock'=>array('log_IsLock','boolean','',false),
        'Title'=>array('log_Title','string',250,''),
        'Intro'=>array('log_Intro','string','',''),
        'Content'=>array('log_Content','string','',''),
        'PostTime'=>array('log_PostTime','integer','',0),
        'CommNums'=>array('log_CommNums','integer','',0),
        'ViewNums'=>array('log_ViewNums','integer','',0),
        'Template'=>array('log_Template','string',50,''),
        'Meta'=>array('log_Meta','string','',''),
    ),
    'feifei_category'=>array(
        'ID'=>array('cate_ID','integer','',0),
        'Name'=>array('cate_Name','string',50,''),
        'Order'=>array('cate_Order','integer','',0),
        'Count'=>array('cate_Count','integer','',0),
        'Alias'=>array('cate_Alias','string',50,''),
        'Intro'=>array('cate_Intro','string','',''),
        'RootID'=>array('cate_RootID','integer','',0),
        'ParentID'=>array('cate_ParentID','integer','',0),
        'Template'=>array('cate_Template','string',50,''),
        'LogTemplate'=>array('cate_LogTemplate','string',50,''),
        'Meta'=>array('cate_Meta','string','',''),
    ),
    'feifei_comment'=> array(
        'ID'=>array('comm_ID','integer','',0),
        'LogID'=>array('comm_LogID','integer','',0),
        'IsChecking'=>array('comm_IsChecking','boolean','',false),
        'RootID'=>array('comm_RootID','integer','',0),
        'ParentID'=>array('comm_ParentID','integer','',0),
        'AuthorID'=>array('comm_AuthorID','integer','',0),
        'Name'=>array('comm_Name','string',20,''),
        'Content'=>array('comm_Content','string','',''),
        'Email'=>array('comm_Email','string',50,''),
        'HomePage'=>array('comm_HomePage','string',250,''),
        'PostTime'=>array('comm_PostTime','integer','',0),
        'IP'=>array('comm_IP','string',15,''),
        'Agent'=>array('comm_Agent','string','',''),
        'Meta'=>array('comm_Meta','string','',''),
    ),
    'feifei_counter'=> array(
        'ID'=>array('coun_ID','integer','',0),
        'MemID'=>array('coun_MemID','integer','',0),
        'IP'=>array('coun_IP','string',15,''),
        'Agent'=>array('coun_Agent','string','',''),
        'Refer'=>array('coun_Refer','string',250,''),
        'Title'=>array('coun_Title','string',250,''),
        'PostTime'=>array('coun_PostTime','integer','',0),
        'Description'=>array('coun_Description','string','',''),
        'PostData'=>array('coun_PostData','string','',''),
        'AllRequestHeader'=>array('coun_AllRequestHeader','string','',''),
    ),
    'feifei_module'=> array(
        'ID'=>array('mod_ID','integer','',0),
        'Name'=>array('mod_Name','string',100,''),
        'FileName'=>array('mod_FileName','string',50,''),
        'Content'=>array('mod_Content','string','',''),
        'HtmlID'=>array('mod_HtmlID','string',50,''),
        'Type'=>array('mod_Type','string',5,'div'),
        'MaxLi'=>array('mod_MaxLi','integer','',0),
        'Source'=>array('mod_Source','string',50,'user'),
        'IsHideTitle'=>array('mod_IsHideTitle','boolean','',false),
        'Meta'=>array('mod_Meta','string','',''),
    ),
    'feifei_member'=> array(
        'ID'=>array('mem_ID','integer','',0),
        'Guid'=>array('mem_Guid','string',36,''),
        'Level'=>array('mem_Level','integer','',6),
        'Status'=>array('mem_Status','integer','',0),
        'Name'=>array('mem_Name','string',50,''),
        'Password'=>array('mem_Password','string',32,''),
        'Email'=>array('mem_Email','string',50,''),
        'HomePage'=>array('mem_HomePage','string',250,''),
        'IP'=>array('mem_IP','string',15,''),
        'PostTime'=>array('mem_PostTime','integer','',0),
        'Alias'=>array('mem_Alias','string',250,''),
        'Intro'=>array('mem_Intro','string','',''),
        'Articles'=>array('mem_Articles','integer','',0),
        'Pages'=>array('mem_Pages','integer','',0),
        'Comments'=>array('mem_Comments','integer','',0),
        'Uploads'=>array('mem_Uploads','integer','',0),
        'Template'=>array('mem_Template','string',50,''),
        'Meta'=>array('mem_Meta','string','',''),
    ),
    'feifei_tag'=> array(
        'ID'=>array('tag_ID','integer','',0),
        'Name'=>array('tag_Name','string',250,''),
        'Order'=>array('tag_Order','integer','',0),
        'Count'=>array('tag_Count','integer','',0),
        'Alias'=>array('tag_Alias','string',250,''),
        'Intro'=>array('tag_Intro','string','',''),
        'Template'=>array('tag_Template','string',50,''),
        'Meta'=>array('tag_Meta','string','',''),
    ),
    'feifei_upload'=> array(
        'ID'=>array('ul_ID','integer','',0),
        'AuthorID'=>array('ul_AuthorID','integer','',0),
        'Size'=>array('ul_Size','integer','',0),
        'Name'=>array('ul_Name','string',250,''),
        'SourceName'=>array('ul_SourceName','string',250,''),
        'MimeType'=>array('ul_MimeType','string',50,''),
        'PostTime'=>array('ul_PostTime','integer','',0),
        'DownNums'=>array('ul_DownNums','integer','',0),
        'LogID'=>array('ul_LogID','integer','',0),
        'Intro'=>array('ul_Intro','string','',''),
        'Meta'=>array('ul_Meta','string','',''),
    ),
);
?>