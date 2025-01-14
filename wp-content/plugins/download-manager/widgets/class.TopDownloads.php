<?php

class WPDM_TopDownloads extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM Top Downloads');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        global $post;
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $sdc = $instance['sdc'];
        $nop = $instance['nop'];

        $newp = new WP_Query(array('post_type'=>'wpdmpro','posts_per_page'=>$nop, 'order_by'=>'publish_date','order'=>'desc','orderby' => 'meta_value_num','meta_key'=>'__wpdm_download_count','order'=>'desc'));

        ?>
        <?php echo $before_widget; ?>
        <?php if ( $title )
            echo $before_title . $title . $after_title;
        echo "<div class='w3eden'>";
        while($newp->have_posts()){
            $newp->the_post();

            $pack = (array)$post;
            echo FetchTemplate($sdc, $pack);
        }
        echo "</div>";
        echo $after_widget;
        wp_reset_query();
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['sdc'] = strip_tags($new_instance['sdc']);
        $instance['nop'] = strip_tags($new_instance['nop']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = isset($instance['title'])?esc_attr($instance['title']):"";
        $sdc = isset($instance['sdc'])?esc_attr($instance['sdc']):"link-template-default.php";
        $nop = isset($instance['nop'])?esc_attr($instance['nop']):5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nop'); ?>"><?php _e('Number of packages to show:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('nop'); ?>" name="<?php echo $this->get_field_name('nop'); ?>" type="text" value="<?php echo $nop; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sdc'); ?>"><?php _e('Link Template:'); ?></label>

            <?php echo \WPDM\admin\menus\Templates::Dropdown(array('name' => $this->get_field_name('sdc'), 'id' => $this->get_field_id('sdc'), 'selected' => $sdc)); ?>

        </p>
        <?php
    }

}

//add_action('widgets_init', create_function('', 'return register_widget("WPDM_TopDownloads");'));
