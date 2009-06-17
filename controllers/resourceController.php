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
        /*
        echo "<pre>".sprint_r($_REQUEST)."</pre>";
        die('lalala');
        */

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

        foreach(param('type') as $type_arr) {
            
            $type = new Type($type_arr);
            //message('Checking type ' . sprint_r($type));
            if($type_arr['remove']) {
                $type->remove();
            }
            else if($type->name != "") {
                $type->save();
            }
        }
        
        foreach(param('tag') as $tag_arr) {
            
            $tag = new Tag($tag_arr);
            //message('Checking type ' . sprint_r($type));
            if($tag_arr['remove']) {
                $tag->remove();
            }
            else if($tag->description != "") {
                $tag->save();
            }
        }
        
        util::redirect(makeUrl(array('task'=>'view')));
    }
    

}

?>