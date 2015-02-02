<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['pagination_admin'] = array(
			'per_page'		 => 50,
			'num_links'		 => 10,
			// full link
			'full_tag_open'	 => '<ul class=\"pagination\">',
			'full_tag_close' => '</ul>',
			// digit link
			'num_tag_open'	 => '<li>',
			'num_tag_close'	 => '</li>',
			// next link
			'next_link'		 => 'next',
			'next_tag_open'  => '<li>',
			'next_tag_close' => '</li>',
			// previous link
			'prev_link' 	 => 'previous',
			'prev_tag_open'	 => '<li>',
			'prev_tag_close' => '</i>',
			// current link
			'cur_tag_open'	 => '<li class="select">',
			'cur_tag_close'	 => '</a></li>',
			// first link
			'first_link' 	 => '&laquo;',
			'first_tag_open' => '<li>',
			'first_tag_close'=> '</li>',
			// last link
			'last_link' 	 => '&raquo;',
			'last_tag_open'  => '<li>',
			'last_tag_close' => '</li>'
		);

$config['pagination'] = array(
			'index_page'	 => 10,
			'per_page'		 => 10,
			'num_links'		 => 5,
			// full link
			'full_tag_open'	 => '<ul class="pagination">',
			'full_tag_close' => '</ul>',
			// digit link
			'num_tag_open'	 => '<li>',
			'num_tag_close'	 => '</li>',
			// next link
			'next_link'		 => 'next',
			'next_tag_open'  => '<li>',
			'next_tag_close' => '</li>',
			// previous link
			'prev_link' 	 => 'previous',
			'prev_tag_open'	 => '<li>',
			'prev_tag_close' => '</i>',
			// current link
			'cur_tag_open'	 => '<li class="select">',
			'cur_tag_close'	 => '</a></li>',
			// first link
			'first_link' 	 => '&laquo;',
			'first_tag_open' => '<li>',
			'first_tag_close'=> '</li>',
			// last link
			'last_link' 	 => '&raquo;',
			'last_tag_open'  => '<li>',
			'last_tag_close' => '</li>'
		);
$config['pagination_production'] = array(
			'per_page'		 => 3,
			'per_page_ajax'  => 10
		);