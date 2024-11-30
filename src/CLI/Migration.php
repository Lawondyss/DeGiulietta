<?php

namespace Lawondyss\DeGiulietta\CLI;

final class Migration
{
  public string $class {
    get => "{$this->id}_{$this->name}";
  }


  public function __construct(
    public State $state,
    public string $id,
    public string $name,
    public ?string $start = null,
    public ?int $duration = null,
    public ?string $filepath = null,
  ) {}
}
