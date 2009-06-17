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
<h2>People</h2>
<table class='striped'>
<tr>
<th>Name</th>
<th>Tags</th>
<th></th>
</tr>
";
        $i = 0;
        $tags = Tag::findAll();

        
        foreach(Resource::findAll() as $resource) {
            $text = form::makeText("name_$i", $resource->name);
            $hidden["id_$i"] = $resource->id;
            $sel = form::makeSelect("tag_$i", $tags, $resource->getTags());
            $remove = form::makeButton('Remove',"remove_$i","1");
            
            $form .= "<tr>
<td>
$text
</td>
<td>
$sel
</td>
<td>
$remove
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
<td>
</td>
</tr>";
                
            
        
        $form .= "
</table>
";
        
        $form .= "
<h2>Usage types</h2>
<table class='striped'>
<thead>
<th>Name</th>
<th>Color</th>
<th></th>
</thead>
<tbody>
";
        
        $i = 0;
        foreach(array_merge(Type::findAll(),array(new Type(array('color'=>'#000000')))) as $type) {

            $remove = "";
            
            if($type->id!==null) {
                $hidden["type[$i][id]"] = $type->id;
                $remove = form::makeButton('Remove',"type[$i][remove]","1");
            }
            
            $color = form::makeText("type[$i][color]", $type->color, "type_color_$i");
            
            $color .= "
<div id='type_colorpicker_$i'></div>
<script type='text/javascript'>
  $(document).ready(function() {
    $('#type_colorpicker_$i').farbtastic('#type_color_$i');
  });
</script>
";
 
            $name = form::makeText("type[$i][name]", $type->name);
            
            $form .= "
<tr>
<td>
$name
</td>
<td>
$color
</td>
<td>
$remove
</td>
</tr>
";
            $i++;
        }
        $form .= "
</tbody>
</table>
";
        
        $form .= "
<h2>Tags</h2>
<table class='striped'>
<thead>
<th>Name</th>
<th></th>
</thead>
<tbody>
";
        
        $i = 0;
        foreach(array_merge(Tag::findAll(),array(new Tag())) as $tag) {
            
            $remove = "";
            
            if($tag->id!==null) {
                $hidden["tag[$i][id]"] = $tag->id;
                $remove = form::makeButton('Remove',"tag[$i][remove]","1");
            }
            
            $description = form::makeText("tag[$i][description]", $tag->description);
            
            $form .= "
<tr>
<td>
$description
</td>
<td>
$remove
</td>
</tr>
";
            $i++;
        }
        $form .= "
</tbody>
</table>
";
                
        $form .="
          <button type='submit'>Save</button>
          </p>\n\n";
        $content = form::makeForm($form, $hidden);
        $controller->show($content);
    }
}

?>