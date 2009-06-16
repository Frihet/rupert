<?php

include("controllers/adminController.php");

class resourceUsageController
	extends AdminController
{

    function saveRun()
    {
        $r = Resource::find(param('resource_id'));
        $r->addUsage(param('start'),param('stop'),param('usage'),param('description'), param('type_id'));
        
        util::redirect(makeUrl(array('task'=>'view')));
    }
    
    function removeRun()
    {
        Resource::removeUsage(param('id'));
        util::redirect(makeUrl(array('task'=>'view')));
    }

    function viewRun()
    {
        return $this->render("resourceUsage");
    }

    function editRun()
    {
        $this->usage = new ResourceUsage(param('id'));
        return $this->render("resourceUsageEdit");
    }

    function saveEditRun()
    {
        $r = new ResourceUsage($_REQUEST);
        $r->save();
        util::redirect(makeUrl(array('task'=>'view')));
    }
    
}

?>