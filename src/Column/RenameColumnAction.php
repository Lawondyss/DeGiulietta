<?php

namespace Lawondyss\DeGiulietta\Column;

readonly class RenameColumnAction extends ColumnAction
{
  public function __construct(
    string $oldName,
    public string $newName)
  {
    parent::__construct($oldName);
  }
}