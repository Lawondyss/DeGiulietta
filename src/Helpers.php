<?php

namespace Lawondyss\DeGiulietta;

use Lawondyss\DeGiulietta\Exception\DeGiuliettaException;
use function get_debug_type;
use function in_array;
use function mb_strlen;

class Helpers
{
  public const VAR_TYPE_STRING = 'string';
  public const VAR_TYPE_INT = 'int';
  public const VAR_TYPE_FLOAT = 'float';
  public const VAR_TYPE_BOOL = 'bool';
  public const VAR_TYPE_ALLOWED = [
    self::VAR_TYPE_STRING,
    self::VAR_TYPE_INT,
    self::VAR_TYPE_FLOAT,
    self::VAR_TYPE_BOOL,
  ];



  private function __construct() {}


  public static function isInRange(int $number, int $from, int $to): bool
  {
    return $from <= $number && $number <= $to;
  }


  public static function getVarType(mixed $var): ?string
  {
    $varType = get_debug_type($var);

    return self::inArray($varType, self::VAR_TYPE_ALLOWED)
      ? $varType
      : null;
  }


  public static function inArray(mixed $needle, array $haystack): bool
  {
    return in_array($needle, $haystack, true);
  }


  public static function stringLength(string $s): int
  {
    return mb_strlen($s);
  }
}