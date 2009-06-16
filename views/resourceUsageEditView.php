<?php

class resourceUsageEditView
	extends View
{

	function render($controller)
	{
            util::setTitle("Edit resource usage");

            $form = "<p>\n\n";
            
            $hidden = array('task'=>'saveEdit');
            
            $resource_select = form::makeSelect("resource_id", Resource::findAll(), $controller->usage->resource_id);
            $start = form::makeText('start', $controller->usage->start , 'start','date_input');
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
            $stop = form::makeText('stop', $controller->usage->stop, 'stop','date_input');
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

            $usage = form::makeText('usage', $controller->usage->usage,null,'usage_input');
            $description = form::makeText('description',$controller->usage->description);
            $type_select = form::makeSelect("type_id", form::makeSelectList(Type::findAll(),'id','name'), $controller->usage->type_id);
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
<button type='submit'>Update</button>
</td>
</tr>
</table>

\n";
            $content = form::makeForm($form, $hidden);
            $controller->show($content);

	}

}

?>