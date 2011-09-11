<?php
/**
 *
 */

namespace Gedcom;

/**
 *
 */
abstract class Record
{   
    /**
     *
     */
    public function __set($var, $val)
    {
        if(!property_exists($this, '_' . $var))
            throw new \Exception('Unknown ' . get_class($this) . '::' . $var . ' in SET');
        
        $this->{'_' . $var} = $val;
    }
    
    /**
     *
     */
    public function __get($var)
    {
        if(!property_exists($this, '_' . $var))
            throw new \Exception('Unknown ' . get_class($this) . '::' . $var . ' in GET');
        
        return $this->{'_' . $var};
    }
    
    /**
     *
     */
    public function __call($method, $args)
    {
        if(substr($method, 0, 3) == 'add')
        {
            $arr = strtolower(substr($method, 3));
            
            if(!property_exists($this, '_' . $arr) || !is_array($this->{'_' . $arr}))
                throw new \Exception('Unknown ' . get_class($this) . '::' . $arr);
            
            if(!is_array($args) || !isset($args[0]))
                throw new \Exception('Incorrect arguments to ' . $method);
            
            if(is_object($args[0]))
            {
                // Type safety?
            }
            
            $this->{'_' . $arr}[] = $args[0];
        }
        else if(substr($method, 0, 3) == 'set')
        {
            $arr = strtolower(substr($method, 3));
            
            if(!property_exists($this, '_' . $arr))
                throw new \Exception('Unknown ' . get_class($this) . '::' . $arr);
            
            if(!is_array($args) || !isset($args[0]))
                throw new \Exception('Incorrect arguments to ' . $method);
            
            if(is_object($args[0]))
            {
                // Type safety?
            }
            
            $this->{'_' . $arr} = $args[0];
        }
        else
        {
            throw new \Exception('Unknown method called: ' . $method);
        }
    }
    
    /**
     * Checks if this GEDCOM object has the provided attribute (ie, if the provided
     * attribute exists below the current object in its tree).
     * 
     * @param string $var The name of the attribute
     * @return bool True if this object has the provided attribute
     */
    public function hasAttribute($var)
    {
        return property_exists($this, '_' . $var);
    }
}
