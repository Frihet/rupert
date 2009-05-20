<?php

class resourceView
	extends View
{

	function render($controller)
	{
            util::setTitle("Update resources");
            
            $hidden = array('task'=>'save');
            $form = "<p>
<button type='submit'>Save</button>
<table class='striped'>
<tr>
<th>Name</th>
<th>Tags</th>
</tr>
";
            $i = 0;
            $tags = Tag::findAll();
            
            foreach(Resource::findAll() as $resource) {
                $text = form::makeText("name_$i", $resource->name);
                $hidden["id_$i"] = $resource->id;
                $sel = form::makeSelect("tag_$i", $tags, $resource->getTags());
                
                $form .= "<tr>
<td>
$text
</td>
<td>
$sel
</td>
</tr>";
                
                $i++;
            }

            $text = form::makeText("name_new", "");
            $sel = form::makeSelect("tag_new", $tags, array());
            
            $form .= "<tr>
<td>
$text
</td>
<td>
$sel
</td>
</tr>";
                
            
            
            $form .= "
</table>
<button type='submit'>Save</button>
</p>\n\n";
            $content = form::makeForm($form, $hidden);
            
            $controller->show($content);

	}

}

?>