<?php

class resourceUsageView
	extends View
{

	function render($controller)
	{
            util::setTitle("Update resource usage");

            $form = "<p>\n\n";
                        
            $hidden = array('task'=>'save');
            
            $resource_select = form::makeSelect("resource_id", Resource::findAll(), null);
            $start = form::makeText('start', "", 'start','date_input');
            $start .= '<script type="text/javascript">
$(function()
        {
                $("#start").datePicker(
                        {
                                startDate: "1970-01-01"
                        }
                );
        }
);
</script>
';
            $stop = form::makeText('stop', "", 'stop','date_input');
            $stop .= '<script type="text/javascript">
$(function()
        {
                $("#stop").datePicker(
                        {
                                startDate: "1970-01-01"
                        }
                );
        }
);
</script>
';
            $usage = form::makeText('usage', "",null,'usage_input');
            $description = form::makeText('description','');
            $type_select = form::makeSelect("type_id", form::makeSelectList(Type::findAll(),'id','name'), null);
            $form .= "

<table>
<tr>
<th>
Resource
</th>
<th>
Start date
</th>
<th>
Stop date
</th>
<th>
Usage
</th>
<th>
Description
</th>
<th>
Type
</th>
<th>
</th>
</tr>
<tr>
<td>
$resource_select
</td>
<td>
$start 
</td>
<td>
$stop 
</td>
<td>
$usage
</td>
<td>
$description
</td>
<td>
$type_select
</td>
<td>
<button type='submit'>Add</button>
</td>
</tr>
</table>


\n";

            $form .= "
<table class='striped'>
<tr colspan='5'>
</tr>
";
            $i = 0;
            $tags = Tag::findAll();
            
            foreach(Resource::findAll() as $resource) {
                $text = htmlEncode($resource->name);
                $hidden["id_$i"] = $resource->id;
                
                $form .= "
<tr>
<th colspan='5'>
$text
</th>
</tr>
<tr>
<th>
Start
</th>
<th>
Stop
</th>
<th>
Usage
</th>
<th>
Description
</th>
<th>
Type
</th>
<th>
</th>
</tr>
";

                $resource->getAvailability();
                foreach($resource->_resource_usage as $usg) {
                    $start = $usg['start'];
                    $stop = $usg['stop'];
                    $usage = $usg['usage'];
                    $desc = htmlEncode($usg['description']);
                    $type = htmlEncode(type::getType($usg['type_id'])->name);
                    $remove = makeLink(makeURL(array('task'=>'remove', 'id'=>$usg['id'])), 'remove');
                    $edit = makeLink(makeURL(array('task'=>'edit', 'id'=>$usg['id'])), 'edit');
                    
                    $form .= "<tr><td>$start</td><td>$stop</td><td>$usage</td><td>$desc</td><td>$type</td><td>$remove $edit</td></tr>";
                    
                }
                
                
                $i++;
            }

            $form .= "</table>";
            


            $form .= "</p>";
            
            $content = form::makeForm($form, $hidden);
            

          $controller->show($content);


	}

}

?>