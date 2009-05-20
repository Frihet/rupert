<?php

/** Base class of all controllers in the admin section. All these controllers
 have a common, simple action menu, defined in this class.
 */
class adminController
extends Controller
{

	function show($content)
	{
            Controller::show(array(makeLink("?controller=resource", "Resources", null),
                                   makeLink("?controller=resourceUsage", "Resource usage", null)),
                             $content);
	}
		
	function viewRun()
	{
            $this->render("admin");
	}
        
        function isAdmin() 
        {
	    return true;
        }
        
}

?>