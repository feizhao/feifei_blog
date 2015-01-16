<?php
/**
 * 数据库操作路由
 *
 * @copyright (c) feifei_blog
 */

class Database {

    public static function getInstance() {
        switch (Option::DEFAULT_MYSQLCONN) {
            case 'mysqli':
                return MySqlii::getInstance();
                break;
            case 'mysql':
            default :
                return MySql::getInstance();
                break;
        }
    }

}