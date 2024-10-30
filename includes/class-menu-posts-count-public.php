<?php
/**
 * Add posts count in menu base on settings.
 *
 * @link       http://www.sanjayojha.com
 * @since      1.0.0
 *
 * @package    Menu_Posts_Count
 */

class Menu_Posts_Count_Public
{
    public function __construct()
    {
       add_action( 'init', array( $this, 'mpc_sa_initialize') );       
       
    }

    public function mpc_sa_initialize()
    {
        add_action( 'plugins_loaded', array( $this, 'mpc_sa_load_textdomain' ) );
        add_filter( 'walker_nav_menu_start_el', array( $this, 'mpc_sa_add_post_count' ), 10, 4);
        add_filter( 'mpc_sa_add_bracket', array( $this, 'mpc_sa_add_bracket_cb'), 10, 3 );
    }

    /**
    * Define the locale for this plugin for internationalization.    
    */
    public function mpc_sa_load_textdomain() 
    {            
        load_plugin_textdomain( 'mpc-sa', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
    }

    /**
    * Add total post/page count at right side of menu item base on settings
    *
    */
    public function mpc_sa_add_post_count( $item_output, $item, $depth, $args )
    {
        
        // Getting all the option and its default
        $options = get_option('mpc_sa_options');

        $topmenu = isset($options['mpc_sa_field_top_level_menu']) ? intval($options['mpc_sa_field_top_level_menu']) : 0;
        $countl = isset($options['mpc_sa_field_count_greater']) ? $options['mpc_sa_field_count_greater'] : 0;
        
        $menuArr = isset($options['mpc_sa_field_which_menu']) ? $options['mpc_sa_field_which_menu'] : '';
        
        $taxonomyArr = isset($options['mpc_sa_field_select_taxonomy']) ? $options['mpc_sa_field_select_taxonomy'] : '';

        if(!$topmenu && $depth == 0) {
            return $item_output;
        }

        if( $menuArr != '' && $taxonomyArr != '') {
            
            $menuArr = explode(',', $menuArr);
            $taxonomyArr = explode(',', $taxonomyArr);
            if( in_array($args->theme_location, $menuArr) && in_array($item->object, $taxonomyArr) ) {
                
                $term = get_term($item->object_id, $item->object);
                //printf( '<pre>%s</pre>', var_export( $term, true ) );
                //$term->count;
                if($term->count > $countl){                   
                    
                    //Getting bracket type
                    $bracket = isset($options['mpc_sa_field_bracket']) ? intval($options['mpc_sa_field_bracket']) : 0;
                    $spacer = isset($options['mpc_sa_field_bracket_space']) ? ' ' : '';
                     
                    $count = apply_filters( 'mpc_sa_add_bracket', $term->count, $bracket, $spacer  );
                    
                    
                    //logic to mody menu text(title)
                    $atts = array();
                    $atts['title'] = !empty( $item->title ) ? esc_attr( $item->title ) : '';
                    $atts['target'] = !empty( $item->target ) ? esc_attr( $item->target ) : '';
                    $atts['rel'] = !empty( $item->xfn ) ? esc_attr( $item->xfn ) : '';
                    $atts['href'] = !empty( $item->url ) ? esc_url( $item->url ) : '';
                    
                    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

                    $attributes = '';
                    foreach ( $atts as $attr => $value ) {
                        if ( ! empty( $value ) ) {
                            $attributes .= ' ' . $attr . '="' . $value . '"';
                        }
                    }
                    $item_output = '<a'. $attributes .'>';
                    $item_output .= apply_filters( 'the_title', $item->title, $item->ID ) . ' <span class="mpc-count">'.$count.'</span>';
                    $item_output .= '</a>';
                }
            }
        }
        
        // Check $item and get the data you need
        //echo $args->menu->name;
        //printf( '<pre>%s</pre>', var_export( $item, true ) );
        // Then append whatever you need to the $output
        //$item->title = $item->title . ' (56)';
        //$item_output .= $item->title;

        return $item_output;
    }

    public function mpc_sa_add_bracket_cb($count, $bracket, $spacer)
    {
        
        if ($bracket == 1) {                        
            $count = '('.$spacer.$count.$spacer. ')';                        
        } else if ($bracket == 2) {
            $count = '['.$spacer.$count.$spacer. ']'; 
        } else if ($bracket == 3) {
            $count = '{'.$spacer.$count.$spacer. '}'; 
        } else if ($bracket == 4) {
            $count = '<'.$spacer.$count.$spacer. '>'; 
        }
        return $count;
    }
}