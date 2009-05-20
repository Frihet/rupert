<?php

include("controllers/adminController.php");

class resourceController
	extends AdminController
{

    function viewRun()
    {
        return $this->render("resource");
    }

    function saveRun()
    {
        $i = 0;
        while(($id = param("id_$i")) !== null) {
            $r = new Resource(array("id"=>$id, "name"=>param("name_$i")));
            $r->setTags(param("tag_$i"));
        
            $r->save();
            $i++;
        }

        $new_name = param("name_new");
        if($new_name) {
            $r = new Resource(array("name"=>$new_name));
            $r->setTags(param("tag_new"));
            $r->save();            
        }
        
        
        util::redirect(makeUrl(array('task'=>'view')));
    }
    

}

?>