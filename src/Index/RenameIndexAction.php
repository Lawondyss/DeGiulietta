<?php

namespace Lawondyss\DeGiulietta\Index;

readonly class RenameIndexAction extends IndexAction
{
  public function __construct(
    string $oldName,
    public string $newName,
  ) {
    parent::__construct([], $oldName);
  }
}