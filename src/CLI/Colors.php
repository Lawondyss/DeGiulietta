<?php

namespace Lawondyss\DeGiulietta\CLI;

final class Colors
{
  public const string Grey = "\033[1;30m";
  public const string Red = "\033[1;31m";
  public const string Green = "\033[1;32m";
  public const string Yellow = "\033[1;33m";
  public const string Cyan = "\033[1;36m";
  public const string White = "\033[1;37m";
  public const string Reset = "\033[0m";


  public static function grey(?string $text = null): string
  {
    return self::colorize(self::Grey, $text);
  }


  public static function red(?string $text = null): string
  {
    return self::colorize(self::Red, $text);
  }


  public static function green(?string $text = null): string
  {
    return self::colorize(self::Green, $text);
  }


  public static function yellow(?string $text = null): string
  {
    return self::colorize(self::Yellow, $text);
  }


  public static function cyan(?string $text = null): string
  {
    return self::colorize(self::Cyan, $text);
  }


  public static function white(?string $text = null): string
  {
    return self::colorize(self::White, $text);
  }


  private static function colorize(string $color, ?string $text): string
  {
    $str = $color;

    if (isset($text)) {
      $str .= $text . self::Reset;
    }

    return $str;
  }
}
