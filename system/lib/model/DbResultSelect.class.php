<?php
/**
 * WebAsyst.net
 * 
 * @copyright Copyright (c) 2008, WebAsyst.net
 * @link http://webasyst.net
 * @package Lib
 * @subpackage Model
 * @since 12.12.2008
 */

/**
 * DbResultSelect
 */
class DbResultSelect extends DbResult implements IteratorAggregate
{

    const ASSOC = MYSQL_ASSOC;
    const NUM   = MYSQL_NUM;
    const BOTH  = MYSQL_BOTH;
    
    public $time;
    /**
     * Iterator
     *
     * @var DbResultIterator
     */
    protected $iterator;
    
    /**
     * Preload
     */
    protected function onConstruct()
    {
        $this->iterator = new DbResultIterator($this->result);
    }
    
    /**
     * Get Itterator
     *
     * @return DbResultIterator
     */
    public function getIterator()
    {
        $this->iterator->rewind();
        return $this->iterator;
    }
    
    /**
     * Returns array 
     * 
     * @return array
     */
    public function fetch()
    {
        return $this->iterator->fetch();
    }
    
    /**
     * Returns array with types of keys
     *
     * @param int $type
     * @return array
     */
    public function fetchArray($type = self::BOTH)
    {
        return $this->iterator->fetch($type);
    }
    
    /**
     * Returns assoc array
     *
     * @return array
     */
    public function fetchAssoc()
    {
        return $this->iterator->fetch(self::ASSOC);
    }
    
    /**
     * Returns numeric array
     *
     * @return array
     */
    public function fetchRow()
    {
        return $this->iterator->fetch(self::NUM);
    }
    
    /**
     * Returns value of the column (field)
     *
     * @param string $field     имя колонки
     * @param int $seek         номер строки
     * @return array
     */
    public function fetchField($field, $seek = false)
    {
        /* Seek to the need position */
        if ($seek !== false) {
            $this->iterator->seek($seek);
        }
        
        $data   = $this->iterator->fetch();
        return (isset($data[$field])) ? $data[$field] : false;
    }
    
    /**
     * Returns all values
     *
     * @param string $key - use one of the values as array index
     * @return array
     */
    public function fetchAll($key = null)
    {
        return $this->getIterator()->export($key);
    }
    
    /**
     * Returns numbers of the records
     *
     * @return int
     */
    public function count()
    {
        return $this->iterator->count();
    }
    
    /**
     * Free the resource
     * 
     * @return boolean
     */
    public function free()
    {
        if($this->iterator instanceof DbResultIterator)
        {
            return $this->iterator->free();
        }
        
        return true;
    }
    
    /**
     * Rewind itterator
     *
     * @return DbResultSelect
     */
    public function rewind()
    {
        $this->getIterator()->rewind();
        return $this;
    }
    
}

?>