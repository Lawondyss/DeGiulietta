<?php

namespace Lawondyss\DeGiulietta;

use function implode;

class Naming
{
  public const PrefixIndex = 'IDX_';
  public const PrefixUnique = 'UNQ_';
  public const PrefixFulltext = 'FLT_';
  public const PrefixForeignKey = 'FK_';
  public const ColumnsSeparator = '__';


  private function __construct() {}


  /**
   * @param string[] $columns
   */
  public static function commonIndex(array $columns): string
  {
    return self::PrefixIndex . implode(self::ColumnsSeparator, $columns);
  }


  /**
   * @param string[] $columns
   */
  public static function uniqueIndex(array $columns): string
  {
    return self::PrefixUnique . implode(self::ColumnsSeparator, $columns);
  }


  public static function fulltextIndex(string $column): string
  {
    return self::PrefixFulltext . $column;
  }


  public static function foreignKey(string $table, string $column): string
  {
    return self::PrefixForeignKey . "{$table}_{$column}";
  }
}