<?php

namespace Lawondyss\DeGiulietta\Exception;

class Unsupported extends DeGiuliettaException
{
  public static function action(string $type, string $given): self
  {
    return new self("Unsupported {$type} action, given {$given}");
  }


  public static function valueType(string $given): self
  {
    return new self("Unsupported value type, given {$given}");
  }
}