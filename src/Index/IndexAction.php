<?php

namespace Lawondyss\DeGiulietta\Index;

use Lawondyss\DeGiulietta\Action;

abstract readonly class IndexAction implements Action
{
  public function __construct(
    public array $columns,
    public ?string $name,
  ) {}
}