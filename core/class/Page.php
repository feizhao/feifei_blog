<?php
/**
 *  class Page
 *  独立分页类
 *  调用方式：
 *  $pagenation = new Page( 4, 10, 200 ); // 4(第一个参数) = currentPage, 10(第二个参数) = pageSize, 200(第三个参数) = 总数
 *  $pagenation->set_link( 'http://www.360hqb.com' );
 *  $pagenation->show();
 */
class Page
{
    protected $_total = 0;
    protected $_total_page = 0;
    protected $_page = 1;
    protected $_page_size = 10;
    protected $_link = '';
    protected $_grep = 3;
 
    protected $_admin = false;
    protected $_css_next = 'next-page';
    protected $_css_prev = 'am-disabled';
    protected $_css_curr = 'am-active';
    protected $_css_page = 'am-pagination';
 
    public function __construct ( $page, $page_size, $total, $admin = false )
    {
        $this->set_current_page( $page );
        $this->set_page_size( $page_size );
        $this->set_total( $total );
 
        if ( $admin )
        {
            $this->_admin = $admin;
        }
        //$this->_link = $_SERVER['REQUEST_URI'];
        $this->set_link( $_SERVER['REQUEST_URI'] );
    }
 
    public function set_link ( $link, $is_shop = TRUE )
    {
        $len  = strlen( $link );
        $substr = substr( $link, $len - 1 );
        if ( '&' == $substr )
        {
            $link = substr( $link, 0, $len - 1 );
        }
        $pos = strpos( $link, '?' );
        if ( $pos )
        {
            $link = substr( $link, 0, $pos );
        }
        if ( !empty( $_GET ) )
        {
            $link .= '?';
            foreach ( $_GET as $k=>$v )
            {
                if ( 'page' == strtolower( $k ) )
                {
                    continue;
                }
                $link .= $k.'='.$v.'&';
            }
            $len  = strlen( $link );
            $substr = substr( $link, $len - 1 );
            if ( '&' == $substr )
            {
                $link = substr( $link, 0, $len - 1);
            }
        } 
        elseif ( isset( $_SERVER['QUERY_STRING'] ) AND !empty( $_SERVER['QUERY_STRING'] ) AND $is_shop )
        {  
            $link .= '?'.$_SERVER['QUERY_STRING'];
            $len  = strlen( $link );
            $substr = substr( $link, $len - 1 );
            if ( '&' == $substr )
            {
                $link = substr( $link, 0, $len - 1);
            }
        } 
        $this->_link = $link;
    }
 
    public function set_page_size ( $page_size )
    {
        if ( empty( $page_size ) )
        {
            $this->_page_size = 10;
        }
        else
        {
            $this->_page_size = (int) $page_size;
        }
    }
 
    public function set_total ( $total )
    {
        $page_size = empty( $this->_page_size )?10:$this->_page_size;
        $this->_total = $total;
        if ( 0 == ( $total % $page_size ) )
        {
            $this->_total_page = intval( $total / $page_size );
        }
        else
        {
            $this->_total_page = intval( $total / $page_size ) + 1;
        }
        if ( $this->_page > $this->_total_page )
        {
            $this->_page = $this->_total_page;
        }
    }
 
    public function set_current_page ( $page )
    {
        if ( empty( $page ) )
        {
            $this->_page = 1;
        }
        else
        {
            $this->_page = (int) $page;
        }
    }
 
    public function get_next_page_btn ()
    {
        if ( $this->_page < $this->_total_page )
        {
            $link = '';
            if ( strpos( $this->_link, '?' ) )
            {
                $link = $this->_link.'&page='.( $this->_page + 1 );
            }
            else
            {
                $link = $this->_link.'?page='.( $this->_page + 1 );
            }
            if ( $this->_admin )
            {
                return '<a href="'.$link.'">下一页</a>';
            }
            else
            {
                return '<li class="'.$this->_css_next.'"><a href="'.$link.'">下一页</a></li>';
            }
        }
        if ( $this->_admin )
            return '下一页&nbsp;»';
        else
            return '';
    }
 
    public function get_prev_page_btn ()
    {
        if ( $this->_page > 1 )
        {
            $link = '';
            if ( strpos( $this->_link, '?' ) )
            {
                $link = $this->_link.'&page='.( $this->_page - 1 );
            }
            else
            {
                $link = $this->_link.'?page='.( $this->_page - 1 );
            }
            if ( $this->_admin )
            {
                return '<a href="'.$link.'">上一页</a>';
            }
            else
            {
                return '<li class="'.$this->_css_prev.'"><a href="'.$link.'">上一页</a></li>';
            }
        }
        if ( $this->_admin )
            return '«&nbsp;上一页';
        else
            return '';
    }
 
    public function get_current_page ()
    {
        if ( $this->_admin )
            return '<strong>'.$this->_page.'</strong>';
        else
            return '<li class="'.$this->_css_curr.'"><a class="selected" href="javascript:void(0)">'.$this->_page.'</a></li>';
    }
 
    public function get_page_link ( $page )
    {
        $link = '';
        if ( strpos( $this->_link, '?' ) )
        {
            $link = $this->_link.'&page='.$page;
        }
        else
        {
            $link = $this->_link.'?page='.$page;
        }
        if ( $this->_admin )
        {
            return '<a href="'.$link.'">'.$page.'</a>';
        }
        else
        {
            return '<li><a href="'.$link.'">'.$page.'</a></li>';
        }
    }
 
    public function get_prev_pages ()
    {
        $pages = array();
        $begin = $this->_page - $this->_grep;
        if ( $begin < 1 )
        {
            $begin = 1;
        }
        elseif ( $begin > 2 )
        {
            $pages[] = $this->get_page_link( 1 );            
            if ( $this->_admin )
            {
                $pages[] = '&nbsp;...&nbsp;';
            }
            else
            {
                $pages[] = '<li>...</li>';
            }
        }
        elseif ( $begin == 2 )
        {
            $pages[] = $this->get_page_link( 1 );
        }
        for ( $i = $begin; $i < $this->_page; $i++ )
        {
            $pages[] = $this->get_page_link( $i );
        }
        return $pages;
    }
 
    public function get_next_pages ()
    {
        $pages = array();
        $begin = $this->_page + 1;
        if ( $begin < $this->_total_page )
        {
            $end = $begin + $this->_grep;
            if ( $end > $this->_total_page )
            {
                $end = $this->_total_page;
            }
            for ( $i = $begin; $i < $end; $i++ )
            {
                $pages[] = $this->get_page_link( $i );
            }
            if ( $i < $this->_total_page )
            {
                if ( $this->_admin )
                {
                    $pages[] = '&nbsp;...&nbsp;';
                }
                else
                {
                    $pages[] = '<li>...</li>';
                }
                $pages[] = $this->get_page_link( $this->_total_page );
            }
            else
            {
                $pages[] = $this->get_page_link( $this->_total_page );
            }
        }
        elseif ( $begin == $this->_total_page )
        {
            $pages[] = $this->get_page_link( $this->_total_page );
        }
        return $pages;
    }
 
    public function show ()
    {
        if ( $this->_total_page <= 1 )
        {
            return;
        }
        if ( $this->_admin )
        {
            echo '<p class="pagination">';
            echo '<span>共有'.$this->_total.'条记录</span>';
        }
        else
        {
            echo '<ul class="'.$this->_css_page.'">';
        }
        echo $this->get_prev_page_btn();
        $prev_pages = $this->get_prev_pages ();
        if ( !empty( $prev_pages ) )
        {
            foreach ( $prev_pages as $page )
            {
                echo $page;
            }
        }
        echo $this->get_current_page();
        $next_pages = $this->get_next_pages ();
        if ( !empty( $next_pages ) )
        {
            foreach ( $next_pages as $page )
            {
                echo $page;
            }
        }
        echo $this->get_next_page_btn();
        if ( $this->_admin )
        {
            echo '</p>';
        }
        else
        {
            echo '</ul>';
        }
    }
}