<?php

include("controllers/adminController.php");

class resourceUsageController
	extends AdminController
{

    function saveRun()
    {
        $r = Resource::find(param('resource_id'));
        $r->addUsage(param('start'),param('stop'),param('usage'),param('description'));
        
        util::redirect(makeUrl(array('task'=>'view')));
    }
    
    function removeUsageRun()
    {
        Resource::removeUsage(param('usage_id'));
        util::redirect(makeUrl(array('task'=>'view')));
    }

    function viewRun()
    {
        return $this->render("resourceUsage");
    }

}

?>