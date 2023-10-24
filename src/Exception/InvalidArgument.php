<?php

namespace Lawondyss\DeGiulietta\Exception;

class InvalidArgument extends DeGiuliettaException
{
  public static function mustBeBetween(string $what, int $from, int $to): self
  {
    return new self("{$what} must be between {$from} and {$to} inclusive");
  }


  public static function mustBeGE(string $what, int $min): self
  {
    return new self("{$what} must be greater than or equal to {$min}");
  }
}