<?php

namespace Lawondyss\DeGiulietta\Exception;

use Lawondyss\DeGiulietta\DataType;
use Throwable;

class CannotUse extends DeGiuliettaException
{
  public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
  {
    parent::__construct('Cannot use ' . $message, $code, $previous);
  }


  public static function inCreateTable(string $what, string $tableName): self
  {
    return new self("{$what} in CREATE TABLE {$tableName}");
  }


  public static function withDataType(string $what, string $column, DataType $given): self
  {
    return new self("{$what} for {$column} with data type {$given->name}");
  }
}