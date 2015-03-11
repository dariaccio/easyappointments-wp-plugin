<?php 
/* ----------------------------------------------------------------------------
 * Easy!Appointments - WordPress Plugin 
 *
 * @license GPLv3
 * @copyright A.Tselegidis (C) 2015
 * @link http://easyappointments.org
 * @since v1.0.0
 * ---------------------------------------------------------------------------- */

namespace EAWP\Core;

use wpdb;

/**
 * Plugin Class
 * 
 * This class handles the core operations of the plugin. It coordinates the 
 * libraries and registers the required hooks for WordPresss. 
 */
class Plugin {
    /**
     * WordPress Database Handler
     * 
     * @var WPDB
     */
    protected $wpdb; 
    
    /**
     * Handles WordPress Action + Filters Routing
     * 
     * @var Route
     */
    protected $route;

    /**
     * Class Constructor
     * 
     * @param \WPDB $wpdb WordPress database handler.
     */
    public function __construct(wpdb $wpdb, Route $route) {
        $this->wpdb = $wpdb; 
        $this->route = $route;
    }
    
    /**
     * Initialize Plugin 
     * 
     * Bind necessary actions and filters, register WP admin menu. 
     */
    public function initialize() {
        $this->route->action('plugins_loaded', function() {
            load_plugin_textdomain('eawp', false, dirname(plugin_basename(__DIR__)) . '/assets/lang');
        });
        
        $this->route->view('Easy!Appointments', 'Easy!Appointments', 
                'eawp-settings', 'admin', array('admin.js', 'style.css'));
        
        $plugin = $this; // Closure Argument
        
        $this->route->ajax('install', function() use($plugin) {
            try {
                $library = new EAWP\Libraries\Install($plugin, $_POST['path'], $_POST['url']); 
                $library->invoke();
            } catch(Exception $ex) {
                echo json_encode($ex); 
            }
        });
        
        $this->route->ajax('bridge', function() use($plugin) {
            try {
                $library = new EAWP\Libraries\Bridge($plugin, $_POST['path'], $_POST['url']); 
                $library->invoke();
            } catch(Exception $ex) {
                echo json_encode($ex); 
            }
        });
        
        $this->route->shortcode('easyappointments', function() use($plugin) {
            $library = new EAWP\Libraries\Shortcode($plugin); 
            $library->invoke();
        }); 
        
    }
    
    /**
     * Get WordPress Database Object. 
     * 
     * @return wpdb
     */
    public function getDatabase() {
        return $this->wpdb; 
    }
    
    /**
     * Install Plugin 
     * 
     * Performs the required actions for installing this plugin.
     */
    public function install() {
        // Add the required commands here ... 
        return;
    }
    
    /**
     * Uninstall Plugin 
     * 
     * Performs the required actions for uninstalling this plugin.
     */
    public function uninstall() {
        // Add the required commands here ... 
        return;
    } 
}