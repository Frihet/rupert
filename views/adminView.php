<?php

class adminView
	extends View
{

	function render($controller)
	{
            util::setTitle("Administration");
            $content .= "<p>";
            
            $content .= "<ul>\n";
            $content .= "<li>".makeLink("?controller=resource", "Update resources", null)."</li>\n";
            $content .= "<li>".makeLink("?controller=resourceUsage", "Update resource usage", null)."</li>\n";
            $content .= "</ul>\n";
            $content .= "</p>\n\n";
            
            $controller->show($content);
	}

}

?>