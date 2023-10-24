<?php

namespace Lawondyss\DeGiulietta\Index;

readonly class DropIndexAction extends IndexAction
{
  public function __construct(string $name)
  {
    parent::__construct([], $name);
  }
}