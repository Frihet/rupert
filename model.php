<?php

class ResourceUsage
extends DbItem
{
    var $id;
    var $start;
    var $stop;
    var $usage;
    var $description;
    var $type_id;
    
    function __construct($param=null) 
    {
        $this->table = 'rt_resource_usage';
        
        if ((int)$param == $param) {
            $this->load($param);
        }
        else if (is_array($param)) {
                $this->initFromArray($param);
        }
    }
    
    function save() 
    {
        return $this->saveInternal();
    }
    
}


class Resource
extends DbItem
{
    var $id;
    var $name;
    
    var $_resource_usage=null;
    var $_tags = null;
    
    function __construct($param=null) 
    {
        $this->table = 'rt_resource';
        if($param) {
            if ((int)$param == $param) {
                $this->load($param);
            }
            else if (is_array($param)) {
                $this->initFromArray($param);
            }
        }
    }
    
    function getAvailability()
    {
        if( $this->_resource_usage === null) {
            $this->_resource_usage = db::fetchList("
select ru.id, ru.start, ru.stop, ru.usage, ru.description, ru.type_id
from rt_resource_usage ru
join rt_type t
on ru.type_id = t.id
where ru.resource_id = :id", array(":id"=>$this->id));
        }
    }

    function addUsage($start, $stop, $usage, $description, $type_id) 
    {
        db::query("insert into rt_resource_usage (resource_id, start, stop, usage, description, type_id) values (:id, :start, :stop, :usage, :description, :type_id)",
                  array(':id'=>$this->id,
                        ':start'=>$start,
                        ':stop'=>$stop,
                        ':usage'=>$usage,
                        ':description'=>$description,
                        ':type_id'=>$type_id));
    }

    function save()
    {
        db::begin();
        
        $ok = $this->saveInternal();
        if ($this->_tags !== null) {
            $ok &= db::query('delete from rt_resource_tag where resource_id = :id',
                      array(':id'=>$this->id));
            foreach($this->_tags as $tag_id) {
                $ok &= db::query('insert into rt_resource_tag (resource_id, tag_id) values (:resource_id, :tag_id)',
                          array(':tag_id'=>$tag_id,
                                ':resource_id'=>$this->id));
            }
        }
        if( $ok) {
            db::commit();
        }
        else {
            db::rollback();
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

    function remove() 
    {
        db::query('delete from rt_resource_usage where resource_id=:id', array(':id'=>$this->id));             db::query('delete from rt_resource_tag where resource_id=:id', array(':id'=>$this->id));             $this->removeInternal();
    }
        
}

class Tag
extends DbItem
{
    var $id;
    var $description;
    
    function __construct($param=null) 
    {
        $this->table = 'rt_tag';

        if ((int)$param == $param) {
            $this->load($param);
        }
        else if (is_array($param)) {
            $this->initFromArray($param);
        }
    }


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
    
    function remove() 
    {
        $this->removeInternal();
    }

    function save() 
    {
        $this->saveInternal();
    }


}


class Type
extends DbItem
{
    var $id;
    var $name;
    var $color;
    
    static $_all=null;
    
    function __construct($param=null) 
    {
        $this->table = 'rt_type';

        if ((int)$param == $param) {
            $this->load($param);
        }
        else if (is_array($param)) {
            $this->initFromArray($param);
        }
    }

    function findAll()
    {
        if (self::$_all !== null) {
            return self::$_all;
        }
        

        $resources = db::fetchList("select * from rt_type order by name");
        $res=array();
        foreach( $resources as $resource_arr) {
            $r = new Type();
            $r->initFromArray($resource_arr);
            $res[] = $r;
        }
        self::$_all = $res;
        
        return $res;
    }

    function getType($id) 
    {
        $all = self::findAll();
        foreach($all as $el) {
            if ($el->id == $id) {
                return $el;
            }
        }
        return null;
    }
    
    function getId()
    {
        return $this->id;
    }
    
    function getName()
    {
        return $this->name;
    }

    function remove() 
    {
        $this->removeInternal();
    }

    function save() 
    {
        $this->saveInternal();
    }

 
}

?>