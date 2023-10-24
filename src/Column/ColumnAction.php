<?php

namespace Lawondyss\DeGiulietta\Column;

use Lawondyss\DeGiulietta\Action;

abstract readonly class ColumnAction implements Action
{
  public function __construct(
    public string $name,
  ) {}
}
