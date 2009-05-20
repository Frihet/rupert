<?php

class reportView
	extends View
{

    function addMonth($str, $count) 
    {
        $arr = explode('-', $str);
        return date("Y-m-d", mktime(0, 0, 0, $arr[1], $arr[2], $arr[0])+60*60*24*30*$count);
        
    }
    

	function render($controller)
	{
            $start = param('start', date("Y-m-d",time()));
            $stop = param('stop', date("Y-m-d",time() + 60*60*24*31*3));

            util::setTitle("Resource usage from $start to $stop");
            

            $content = "\n\n";
            $tag_id = param('tag_id');

            $actions = array();

            $tags = Tag::findAll();
            if( !$tag_id) {
                $tags = array(null=>'Pick a tag') + $tags;
                $resources = Resource::findAll();
            } else {
                $resources = Resource::findByTag($tag_id);
            }
            
            $form .= form::makeSelect('tag_id',$tags, $tag_id, null, array('onchange'=>'javascript:submit();'));

            $content .= "<table><tr><td>";
            
            $content .= form::makeForm($form, array(), get);
            $content .= "</td><td>";
            
            $content .= form::makeForm("<button type='submit'>Earlier</button>",
                                       array('tag_id'=>$tag_id,
                                             'start'=>$this->addMonth($start,-1),
                                             'stop'=>$this->addMonth($stop,-1)),
                                       'get');
            $content .= "</td><td>";
            $content .= form::makeForm("<button type='submit'>Later</button>",
                                       array('tag_id'=>$tag_id,
                                             'start'=>$this->addMonth($start,1),
                                             'stop'=>$this->addMonth($stop,1)),
                                       'get');
            $content .= "</td></tr></table>";
            
            foreach( $resources as $r) {
                $content .= "<h2><a name='{$r->id}'>Usage for " . htmlEncode($r->name). "</a></h2>";
                $actions[] = makeLink("#{$r->id}", "Show ".$r->name, null);
                $content .= "<div id='plot_{$r->id}' style='width:100%;height:200px;'></div>\n";
            }
            
            $content .= '
<script>
ResourceTracker.data = '.json_encode($resources).';
ResourceTracker.start = Date.fromString("'.$start.'");
ResourceTracker.stop = Date.fromString("'.$stop.'");
ResourceTracker.initData();
ResourceTracker.plotAll();
</script>
';
	    $controller->show($actions,
			      $content);
	    
	}

}

?>