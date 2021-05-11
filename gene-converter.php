<?php 
/*
Plugin Name: Genes Converter
Description: Convert the text raw to genes result foods
Version: 0.0.0.1
Author: Noly Soriao
*/



define( 'GENECONVERTER_WPADMIN_PATH', ABSPATH."wp-admin" );

define( 'GENECONVERTER_PATH', plugin_dir_path( __FILE__ ) );

//61419
define( 'GENECONVERTER_UPLOAD_PATH', trailingslashit( wp_upload_dir()['basedir'] ) );



foreach ( glob( plugin_dir_path( __FILE__ ) . "includes/*.php" ) as $file ) {
    include_once $file;
}



class Geneconverter_Core extends ReportGeneratorClass{
   
    public function __construct(){
        add_shortcode( 'genes_report_generator', array($this,'func_shorcode_report_generator' ));
        add_action( 'init',array($this, 'load_script_admin_ajax' ) , 9999);
        add_action( 'init',array($this, 'load_style_css_genes' ) );
        add_action("wp_ajax_load_generated_genes", array($this,"func_load_generated_genes"));
        add_action("wp_ajax_nopriv_load_generated_genes", array($this,"func_load_generated_genes"));
        register_activation_hook( __FILE__, array( $this, 'plugin_on_activation' ) );
    }
    function plugin_on_activation(){
			
        global $wpdb;
        
        $table_name = 'user_genes_details';
        
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE `{$table_name}` (
            ID int(12) NOT NULL AUTO_INCREMENT,
            user_id int(12) NOT NULL,
            file_path varchar(255) DEFAULT '' NOT NULL,
            file_size int(16) NOT NULL,
            file_name varchar(55) DEFAULT '' NOT NULL,
            file_url varchar(255) DEFAULT '' NOT NULL,
            IP varchar(55) DEFAULT '' NOT NULL,
            date_upload timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (ID)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function func_load_generated_genes()
    {
        global $wpdb;
       include(GENECONVERTER_PATH."/lib/upload-file.php");

       

        $user_ID = isset( $user->data->ID ) ?  wp_get_current_user()->data->ID :0;
      
        
           $this->set_dnapath(GENECONVERTER_PATH.'assets/dna-reports-combined.json');
           $this->set_rawdatapath( $filepath);
           
          
            $this->generate_data();
            $this->display_table_template(); 
           

          
      


           $table="user_genes_details";
           $wpdb->insert($table, 
               array(
                 'user_id'=>  $user_ID,
                 'file_path'=> $filepath,
                 'file_size'=> intval($_FILES["file"]["size"]),
                 'file_name'=> $_FILES["file"]["name"],
                 'file_url'=> $url . $_FILES["file"]["name"],
                 'IP'=> $_SERVER['REMOTE_ADDR']
               )
           );  
           
        
        die();
    }

    function func_shorcode_report_generator()
    {
        # code...
        ob_start();
		
        include(GENECONVERTER_PATH."/templates/template-shortcode-report.php");
        
        $output = ob_get_clean();
        
        return $output; 
    }
  
    
    function load_style_css_genes() {
            wp_enqueue_style('theme-override', plugins_url( '/assets/style.css' , __FILE__ ), array(), '0.1.0', 'all');
    }
    function load_script_admin_ajax() {
            wp_register_script( "load_ajax_parameter", plugins_url( '/assets/genes_script.js' , __FILE__ ), array('jquery') );
            wp_localize_script( 'load_ajax_parameter', 'ajaxAdmin', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
            wp_enqueue_script( 'load_ajax_parameter' );

    }

}
if ( class_exists( 'Geneconverter_Core' ) ) {
     new Geneconverter_Core();
} 
?>
