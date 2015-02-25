<?php
/* ----------------------------------------------------------------------------
 * Easy!Appointments - WordPress Plugin
 *
 * @license GPLv3
 * @copyright A.Tselegidis (C) 2015
 * @link http://easyappointments.org
 * @since v1.0.0
 * ---------------------------------------------------------------------------- */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../src/core/Plugin.php';

class PluginTest extends PHPUnit_Framework_TestCase {
    // ------------------------------------------------------------------------
    // TEST OBJECT INSTANTIATION
    // ------------------------------------------------------------------------
    public function testObjectInstantiation() {
        $wpdb = $this->getMock('wpdb');
        $route = $this->getMock('EAWP\Core\Route');
        $plugin = new EAWP\Core\Plugin($wpdb, $route);
        $this->assertInstanceOf('EAWP\Core\Plugin', $plugin);
    }
    
    // ------------------------------------------------------------------------
    // TEST INITIALIZE METHOD
    // ------------------------------------------------------------------------
    public function testInitializeMustRegisterTheRequiredRoutes() {
        $wpdb = $this->getMock('wpdb');
        $route = $this->getMock('EAWP\Core\Route');
        
        $route->expects($this->once())->method('view'); 
        $route->expects($this->exactly(2))->method('ajax'); 
        $route->expects($this->once())->method('shortcode');
        
        $plugin = new EAWP\Core\Plugin($wpdb, $route);
        $plugin->initialize();
    }
}