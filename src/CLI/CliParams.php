<?php

namespace Lawondyss\DeGiulietta\CLI;

use function array_filter;
use function explode;
use function in_array;

final class CliParams
{
  private array $items;


  public function __construct(array $params)
  {
    $this->items = array_map(Helper::trimDash(...), $params);
    // first is always name of this script
    array_shift($this->items);
  }


  public function hasParam(string $name): bool
  {
    return
      in_array($name, $this->items, strict: true)
      || array_filter($this->items, static fn(string $i) => str_starts_with($i, "$name="));
  }


  public function containsHelp(): bool
  {
    return $this->hasParam('h') || $this->hasParam('help');
  }


  public function next(): ?string
  {
    return array_shift($this->items);
  }


  public function valueOf(string $name): ?string
  {
    foreach ($this->items as $index => $param) {
      if (str_starts_with($param, "$name=")) {
        return explode('=', $param, 2)[1];
      } elseif ($name === $param) {
        return $this->items[++$index];
      }
    }

    return null;
  }
}