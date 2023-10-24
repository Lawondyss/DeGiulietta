<?php

namespace Lawondyss\DeGiulietta\Index;

readonly class DefineIndexAction extends IndexAction
{
  /**
   * @param string[] $columns
   */
  public function __construct(
    array $columns,
    ?string $name,
    public bool $unique = false,
    public bool $primary = false,
    public bool $fulltext = false,
  ) {
    parent::__construct($columns, $name);
  }
}