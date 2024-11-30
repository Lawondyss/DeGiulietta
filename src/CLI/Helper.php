<?php
namespace Lawondyss\DeGiulietta\CLI;

use function array_filter;
use function explode;
use function in_array;
use function ltrim;
use function microtime;
use function round;
use function strlen;
use function trim;

final class Helper
{
  public static function createId(): string
  {
    $chars = '0123456789abcdefghjkmnpqrstvwxyz';
    $length = strlen($chars);
    $milliseconds = (int)(microtime(true) * 1000);
    $id = '';

    for ($i = 0; $i < 8; $i++) {
      $mod = $milliseconds % $length;
      $id = $chars[$mod] . $id;
      $milliseconds = ($milliseconds - $mod) / $length;
    }

    return $id;
  }


  public static function trimDash(string $s): string
  {
    return trim(ltrim($s, '-'));
  }


  public static function valueOf(string $name): ?string
  {
    global $cliParams;

    foreach ($cliParams as $index => $param) {
      if (str_starts_with($param, "$name=")) {
        return explode('=', $param, 2)[1];
      } elseif ($name === $param) {
        return $cliParams[++$index];
      }
    }

    return null;
  }


  public static function stopwatch(string $name = "\b0010"): ?int
  {
    static $runs = [];
    $mt = static fn(): int => round(microtime(as_float: true) * 1000);

    if (isset($runs[$name])) {
      return $mt() - $runs[$name];
    } else {
      $runs[$name] = $mt();
      return null;
    }
  }


  public static function exitSuccess(): never
  {
    exit(0);
  }


  public static function exitError(): never
  {
    exit(1);
  }
}