<?php

namespace Lawondyss\DeGiulietta\Table;

use Lawondyss\DeGiulietta\Action;

abstract readonly class TableAction implements Action
{
  public function __construct(
    public string $name,
  ) {}
}
