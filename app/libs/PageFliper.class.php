<?php
/**
  * 抓虾系统的通用分页类
 * @package		libraries
 * @author		Kevin Chen
 * @copyright	Copyright (c) 2007, ZHUAXIA.COM, Inc.
 * @link		http://www.zhuaxia.com
 * @since		Version 1.0
 */
 
/**
 * usage:
 		// url like : http://www.zhuaxia.com/pop/1 (last parm is the current page )
 		$config['base_url'] = "http://www.zhuaxia.com/pop/";
		$config['total_rows'] = 200;
		$config['limit'] = 20;
		$config['show_pages'] = 10;
		$config['cur_page'] = $pgn;
		$pager = new PageFile($config);
		echo $pager->doPageFilp();
	注意: 当前显示为第五页的时候， url上需要显示为　4 ,分页的第一页从０开始计数
 */

class PageFliper{
	private $base_url			= ''; // The page we are linking to
	private $total_rows  	= ''; // Total number of items (database results)
	private $limit		= 20;
	private $show_pages	 		= 10; // Max number of items you want shown per page
	private $cur_page	 		=  0; // The current page being viewed
	private $next_link			= '&gt;';
	private $prev_link			= '&lt;';
	private $extra_data			= '';
	private $ed			= '';
	/**
	 * Constructor
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	public function __construct($params = array()){
		if (count($params) > 0){
			foreach ($params as $key => $val){
				if (isset($this->$key)){
					$this->$key = $val;
				}
			}
			if($this->extra_data!=''){
				$this->ed = "?$this->extra_data";
			}else{
				$this->ed = "";
			}
		}
	}
	
	public function doPageFilper( $is_form = true ){
		if(!$this->total_rows || $this->total_rows<$this->show_pages)return $output='';//为零的总数处理
	
		$page_cnt = ceil($this->total_rows/$this->limit);//计算页数（取整返回）
		$output="<ul class=\"pagination\">";#输出的分页链接初值
		$this->cur_page += 1;
		if($this->cur_page < 1){
			$this->cur_page=1;
		}elseif( $this->cur_page>$page_cnt){
			$this->cur_page=$page_cnt;
		}
		$next_page = ($this->cur_page + 1) - 1;
		$prev_page = ($this->cur_page - 1) - 1;
	
		/*功能：让当前页永远距中，并使分页链接数量保持平衡*/
		$offset = floor($this->show_pages/2);
		$current_page = $this->cur_page;
		if($current_page - $offset <= 0){
			$current_page = $offset+1;
		}
		if($current_page+$offset>$page_cnt){
			$current_page=$page_cnt-$offset;
		}
		$begin = ( $current_page-$offset)>0 ? $current_page-$offset : 1;
		$end = ($current_page+$offset) < $page_cnt ? $current_page+$offset : $page_cnt;
		/*Over*/
		if($page_cnt==1){
			$output.="";
		}else{
			if($this->cur_page > 1){	
				$output.=  '<li>'."<a href=\"{$this->base_url}{$prev_page}{$this->ed}\" class=\"first\" title='上一页'>{$this->prev_link}</a> </li>";
			}elseif($this->cur_page == 1){
				$output.=  "<li class=\"active\">"."<b class=\"first\">{$this->prev_link}</b> </li>";
			}
			if ( $this->cur_page - 1 > $offset ){
				$output.= "<li class=\"active\">"."<b>...</b> </li>";
			}
			for($i=$begin;$i<=$end;$i++){
				$pgn = $i -1;
				$output.= $this->cur_page== $i
					? " <li class=\"active\"><b>{$i}</b></li>"  
					: " <li><a href=\"{$this->base_url}{$pgn}{$this->ed}\" >$i</a></li>";
			}
			
			if ( $page_cnt - $this->cur_page > $offset ){
				$output.= "<li class=\"active\">"."<b>...</b> </li>";
			}
			
			if($this->cur_page<$page_cnt){
				$output.= "<li><a href=\"{$this->base_url}{$next_page}{$this->ed}\" title=\"下一页\" >{$this->next_link}</a></li>";
			}
			if ($is_form){
				$output .= "<span>共{$page_cnt}页，到第<form><input type=\"text\" value=\"{$this->cur_page}\" data-value=\"{$this->base_url}\" data-extend=\"{$this->ed}\" name=\"page\"/>页<button type=\"submit\">确定</button></form></span>";
			}
		}
		$output = preg_replace("#([^:])//+#", "\\1/", $output).'</ul>';
		return trim($output);
		
	}
}
