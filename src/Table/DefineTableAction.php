<?php

namespace Lawondyss\DeGiulietta\Table;

use Lawondyss\DeGiulietta\ActionDefineType;
use Lawondyss\DeGiulietta\Engine;

readonly class DefineTableAction extends TableAction
{
  public Table $table;


  public function __construct(
    string $name,
    public ActionDefineType $defineType,
    public ?string $comment = null,
    public ?string $characterSet = null,
    public ?string $collate = null,
    public ?Engine $engine = null,
    public ?bool $encryption = null,
    public ?string $password = null,
  ) {
    parent::__construct($name);

    $this->table = new Table($this);
  }
}