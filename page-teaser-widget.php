<?php
/*
Plugin Name: Page Teaser Widget
Plugin URI: http://www.jonaspiela.com/2010/12/24/wordpress-plugin-page-teaser-widget/
Description: Promote single pages in you sidebar.
Version: 0.4
Author: Jonas Piela
Author URI: http://www.jonaspiela.com/
Text Domain: page-teaser-widget
*/

class Page_Teaser_Widget extends WP_Widget {

	function Page_Teaser_Widget() {
		$widget_ops = array('classname' => 'widget_page_teaser', 'description' => __( "Promote a single page in you sidebar.") );
		$this->WP_Widget('page_teaser', __('Page Teaser', 'page-teaser-widget'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;
		query_posts(array('posts_per_page' => 999, 'post_type' => 'page', 'orderby' => 'menu_order', 'order' => 'asc'));
		while (have_posts()) { the_post();
			if(get_the_ID() == $instance['page']){
				echo $before_title.'<a href="'.get_permalink().'">'.($instance['title'] == "" ? get_the_title() : $instance['title']).'</a>'.$after_title;
				if($instance['thumbnail'] == 1 && has_post_thumbnail()){
					echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($page->ID, array(48,48)).'</a>';
				}
				echo '<p>'.$instance['text'].'<br><br><a href="'.get_permalink().'">'.($instance['link'] == "" ? printf(__("Go to %s", 'page-teaser-widget'), get_the_title()) : $instance['link']).'</a></p>';
			}
		}
	
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$page = $instance['page'];
		$text = $instance['text'];
		$title = $instance['title'];
		$link = $instance['link'];
		$thumbnail = $instance['thumbnail'];

		echo '<p>
			<label for="' . $this->get_field_id('title') . '">' . __('Title:', 'page-teaser-widget') . '
				<input type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="'.$title.'">
			</label>
		</p>
		
		<p><label for="' . $this->get_field_id('page') . '">' . __('Page:', 'page-teaser-widget') . '
			<select id="' . $this->get_field_id('page') . '" name="' . $this->get_field_name('page') . '">';

		query_posts(array('posts_per_page' => 999, 'post_type' => 'page', 'orderby' => 'menu_order', 'order' => 'asc'));
		while (have_posts()) { the_post();
		    echo '<option value="'.get_the_ID().'" ' . ( $page == get_the_ID() ? 'selected="selected"' : '' ) . '>'.get_the_title().'</option>';
		}			

		echo '</select>
		</label></p>
		
		<p>
			<label for="' . $this->get_field_id('text') . '">
				<textarea id="' . $this->get_field_id('text') . '" name="' . $this->get_field_name('text') . '">'.$text.'</textarea>
			</label>
		</p>
		<p>
			<label for="' . $this->get_field_id('link') . '">' . __('Link text:', 'page-teaser-widget') . '
				<input type="text" id="' . $this->get_field_id('link') . '" name="' . $this->get_field_name('link') . '" value="'.$link.'">
			</label>
		</p>
		<p>
			<label for="' . $this->get_field_id('thumbnail') . '">
				<input type="checkbox" id="' . $this->get_field_id('thumbnail') . '" name="' . $this->get_field_name('thumbnail') . '" value="1" ' . ( $thumbnail == 1 ? 'checked="checked"' : '' ) .'>
				' . __('Use page thumbnail if available.', 'page-teaser-widget') . '
			</label>
		</p>';
		
	}

}

add_action( 'widgets_init', 'page_teaser_widget_init' );
function page_teaser_widget_init() {
	register_widget('Page_Teaser_Widget');
	
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'page-teaser-widget', null, $plugin_dir );
}

?>