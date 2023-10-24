<?php

namespace Lawondyss\DeGiulietta;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
use function usort;

/**
 * @template T of Action
 */
final class Actions implements IteratorAggregate
{
  /** @var T[] */
  private array $actions = [];


  /**
   * @param Action<T> $action
   */
  public function add(Action $action): Action
  {
    return $this->actions[] = $action;
  }


  /**
   * @return Traversable<T>
   */
  public function getIterator(): Traversable
  {
    return new ArrayIterator($this->actions);
  }
}