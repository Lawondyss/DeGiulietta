<?php

namespace Lawondyss\DeGiulietta\Column;

use Lawondyss\DeGiulietta\ActionDefineType;
use Lawondyss\DeGiulietta\DataType;
use Lawondyss\DeGiulietta\Expression;

readonly class ChangeColumnAction extends DefineColumnAction
{
  public function __construct(
    string $oldName,
    public string $newName,
    DataType $dataType,
    bool $null = false,
    string|int|float|Expression|null $default = null,
    bool $autoIncrement = false,
    ?string $comment = null,
    ?string $characterSet = null,
    ?string $collate = null,
    bool $first = false,
    ?string $after = null
  ) {
    parent::__construct(
      $oldName,
      ActionDefineType::Change,
      $dataType,
      $null,
      $default,
      $autoIncrement,
      $comment,
      $characterSet,
      $collate,
      $first,
      $after,
    );
  }
}