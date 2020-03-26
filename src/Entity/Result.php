<?php

namespace Laijim\PlaybookConclusion\Entity;
/**
 * Class Result
 * @package Laijim\PlaybookConclusion\Entity
 */
class Result implements \ArrayAccess
{
    /**
     * @var bool
     */
    public $finalResult = true;

    /**
     * @var array
     */
    public $statistics = [];

    /**
     * @var array
     */
    public $verbose = [];

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->verbose[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->verbose[$offset] : null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->verbose[] = $value;
        } else {
            $this->verbose[$offset] = $value;
        }

    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->verbose[$offset]);
        }
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->finalResult === true;
    }

    /**
     * @return bool
     */
    public function isFail()
    {
        return $this->finalResult === false;
    }

    /**
     * @return array
     */
    public function getHostExecResultStatistics()
    {
        return $this->statistics;
    }

}