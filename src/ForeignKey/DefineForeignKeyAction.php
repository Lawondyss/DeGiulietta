<?php

namespace Lawondyss\DeGiulietta\ForeignKey;

readonly class DefineForeignKeyAction extends ForeignKeyAction
{
  public function __construct(
    string $name,
    public string $column,
    public string $referredTable,
    public string $referredColumn,
    public ForeignKeyRestriction $onUpdate,
    public ForeignKeyRestriction $onDelete,
  ) {
    parent::__construct($name);
  }
}