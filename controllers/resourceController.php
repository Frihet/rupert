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
        foreach(param('resource') as $arr) {
            
            $obj = new Resource($arr);
            
            if($arr['remove']) {
                $obj->remove();
            }
            else if($obj->name != "") {
                $obj->setTags($arr['_tags']);
                $obj->save();
            }
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