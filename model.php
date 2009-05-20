<?php


class Resource
extends DbItem
{
    var $id;
    var $name;

    var $_resource_usage=null;
    var $_tags = null;
    
    function __construct($param=null) 
    {
        if($param) {
            $this->initFromArray($param);
        }
    }
    
    
    function getAvailability()
    {
        if( $this->_resource_usage === null) {
            $this->_resource_usage = db::fetchList("
select id, start, stop, usage, description
from rt_resource_usage 
where resource_id = :id", array(":id"=>$this->id));
        }
    }

    function addUsage($start, $stop, $usage, $description) 
    {
        db::query("insert into rt_resource_usage (resource_id, start, stop, usage, description) values (:id, :start, :stop, :usage, :description)",
                  array(':id'=>$this->id,
                        ':start'=>$start,
                        ':stop'=>$stop,
                        ':usage'=>$usage,
                        ':description'=>$description));
    }
    
    function save()
    {
        if($this->id !== null) {
            db::query('update rt_resource set name=:name where id=:id',
                      array(':name'=>$this->name,
                            ':id'=>$this->id));
        } else {
            db::query('insert into rt_resource (name) values (:name)',
                      array(':name'=>$this->name));
            $this->id = db::lastInsertId("rt_resource_id_seq");
        }

        if ($this->_tags !== null) {
            
            db::query('delete from rt_resource_tag where resource_id = :id',
                      array(':id'=>$this->id));
            foreach($this->_tags as $tag_id) {
                db::query('insert into rt_resource_tag (resource_id, tag_id) values (:resource_id, :tag_id)',
                          array(':tag_id'=>$tag_id,
                                ':resource_id'=>$this->id));
            }
        }
    }
    
    
    
    function find($id)
    {
        return dbItem::find("id", $id, "Resource", "rt_resource");
    }

    function findByTag($tag_id)
    {
        $resources = db::fetchList("
select r.* 
from rt_resource r
join rt_resource_tag rt
on rt.resource_id = r.id
where rt.tag_id = :tag
order by r.name
", array(':tag'=>$tag_id));
        $res=array();
        foreach( $resources as $resource_arr) {
            $r = new Resource();
            $r->initFromArray($resource_arr);
            $r->getAvailability();
            
            $res[] = $r;
        }
        return $res;
    }

    function findAll()
    {
        $resources = db::fetchList("select * from rt_resource order by name");
        $res=array();
        foreach( $resources as $resource_arr) {
            $r = new Resource($resource_arr);
            $r->getAvailability();
            $res[] = $r;
        }
        return $res;
    }

    function getTags()
    {
        if($this->_tags !== null) {
            return $this->_tags;
        }
                
        $tags = db::fetchList("select tag_id from rt_resource_tag where resource_id = :id",
                              array(':id'=>$this->id));
        $this->_tags=array();
        foreach( $tags as $tag) {
            $this->_tags[] = $tag['tag_id'];
        }
        return $this->_tags;
    }

    function setTags($tags)
    {
        $this->_tags = $tags;
    }

    function getId()
    {
        return $this->id;
    }
    
    function getDescription()
    {
        return $this->name;
    }

    function removeUsage($id) 
    {
        db::query('delete from rt_resource_usage where id=:id', array(':id'=>$id));
        
    }
    

}



class Tag
extends DbItem
{
    var $id;
    var $description;
    
    function findAll()
    {
        $resources = db::fetchList("select * from rt_tag order by description");
        $res=array();
        foreach( $resources as $resource_arr) {
            $r = new Tag();
            $r->initFromArray($resource_arr);
            $res[] = $r;
        }
        return $res;
    }

    function getId()
    {
        return $this->id;
    }
    
    function getDescription()
    {
        return $this->description;
    }
}



?>