<?php

/**
 The help page
 */
class helpController
extends Controller
{
	
    private $content="";
    private $action_items=array();
    

    function add($header, $text)
    {
        $count = count($this->action_items);

        $this->content .= "<h2><a name='$count'>$header</a></h2> $text";
        $this->action_items[] = "<a href='#$count'>".$header."</a>";
    }
    
    function get()
    {
        return $this->content;
    }
    
    function getActionMenu()
    {
        return $this->action_items;
    }
    

    function viewRun()
    {
		util::setTitle("FreeCode Resource Tracker help");
		
            $this->add("Introduction", "
<p> The Resource Tracker is a tool for showing what types of resources are available over time. For each individual resource, you can add periods of time with a specified resource usage level. These can then be graphed to give an overview of what resources are available. 
</p>
");

            $this->add("Entering new resources", "
To enter new resources, click the \"Administration\" link at the top of the page, and then click on the \"Resources\" link. This will take you to a page with a list of all current resources. You can change the names of resources here, and change what tags they should be associated with. To add a new resources, enter some data into the empty bottom row of the table. 
");

            $this->add("Updating resource usage", "
To enter new resources, click the \"Administration\" link at the top of the page, and then click on the \"Resource usage\" link. This will take you to a page with a list of all pesource usage periods entered. You can click the \"remove\" link to remove an existing resource usage period, or you can create a new resource usage period by entering the relevant information into the form at the bottom of the page.
When two or more usage periods overlap, the shortest period (in time) will be used.
            ");

            $this->add("Viewing resource usage", "
To view current resource usage, simply click on the \"Resource usage\" link at the top of the page. This will take you to a page showing resource usage for all resources. You can filter this list by chosing to only show resources associated with a specific tag, and move forward and backward in time to view resource usage at different times.
");
            
            
                       
            $this->show($this->getActionMenu(),$this->get());
		
	}
	

	function isHelp()
	{
		return true;
		
	}
	
}


?>