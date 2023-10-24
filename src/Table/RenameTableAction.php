<?php

namespace Lawondyss\DeGiulietta\Table;

readonly class RenameTableAction extends TableAction
{
  public function __construct(
    string $oldName,
    public string $newName,
  ) {
    parent::__construct($oldName);
  }
}