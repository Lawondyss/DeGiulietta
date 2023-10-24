<?php

namespace Lawondyss\DeGiulietta\Index;

readonly class DropPrimaryIndexAction extends DropIndexAction
{
  public function __construct() {
    parent::__construct('PRIMARY');
  }
}