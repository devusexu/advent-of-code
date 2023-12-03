<?php

class Pair
{
  private $index;
  private $value;


  function __construct($index, $value) {
    $this->index = $index;
    $this->value = $value;
  }

  function getIndex() {
    return $this->index;
  }

  function setIndex($index) {
    $this->index = $index;
  }

  function getValue() {
    return $this->value;
  }

  function setValue($value) {
    $this->value = $value;
  }

  function setPair($index, $value) {
    $this->index = $index;
    $this->value = $value;
  }
}