<?php

namespace Lawondyss\DeGiulietta\ForeignKey;

use Lawondyss\DeGiulietta\Action;

readonly class ForeignKeyAction implements Action
{
  public function __construct(
    public string $name,
  ) {}
}
