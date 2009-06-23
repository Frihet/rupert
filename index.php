<?php

require_once('common/index.php');
require_once("model.php");

class MyApp 
extends Application
{

    function __construct()
    {
        $this->addScript('static/rupert.js');
        $this->addScript('static/jquery.flot.js');
        $this->addStyle('static/rupert.css');
        $this->addScript('static/farbtastic.js');
        $this->addStyle('static/farbtastic.css');
    }
    
    
    /**
     Write out the top menu.
    */    
    function writeMenu($controller)
    {
        $is_admin = $controller->isAdmin();
        $is_help = $controller->isHelp();
        $is_ci = !$is_admin && !$is_help;
	
        echo "<div class='main_menu'>\n";
        echo "<div class='main_menu_inner'>";
        echo "<div class='logo'><a href='?'>Rupert - The Freecode Resource Usage Planner</a></div>";
		
        echo "<ul>\n";
        
        echo "<li>";
		echo makeLink("?controller=report", "Resouce usage", $is_ci?'selected':null);
        echo "</li>\n";
        
        echo "<li>";
		echo makeLink("?controller=admin", "Administration", $is_admin?'selected':null);
        echo "</li>\n";
        
        echo "<li>";
		echo makeLink("?controller=help", "Help", $is_help?'selected':null);

        /*
        echo "<li>";
        echo makeLink("?controller=logout", "Log out", null);
        echo "</li>\n";
        */        
        echo "</ul></div></div>\n";
    }

    function getDefaultController()
    {
        return "report";
    }
    
    function getApplicationName()
    {
        return "FreeCode Resource Tracker";
    }
    
}

$app = new MyApp();
$app->main();

?>