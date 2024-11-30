<?php

namespace Lawondyss\DeGiulietta\CLI;

use PDOException;
use Throwable;

final class Printer
{
  public static function write(string ...$content): void
  {
    echo implode(' ', $content);
  }


  public static function writeLn(string ...$content): void
  {
    $content[] = PHP_EOL;
    self::write(...$content);
  }


  public static function header(): void
  {
    self::writeLn(' |                           |');
    self::writeLn(' |== DeGiulietta == v1.0.0 ==|');
  }


  public static function subheader(?string $title = null): void
  {
    if (isset($title)) {
      self::writeLn(' |                           |');
      self::writeLn(' |' . str_pad(" {$title} ", 27, '=', STR_PAD_BOTH) . '|');
    }
    self::writeLn();
  }


  public static function help(): void
  {
    self::writeLn(' Description:');
    self::writeLn('   Managing changes to the database');
    self::writeLn(' Available commands:');
    self::writeLn(Colors::yellow('   status'), '   - Display the status of all migrations');
    self::writeLn(Colors::yellow('   create'), '   - Create a new migration file');
    self::writeLn(Colors::yellow('   migrate'), '  - Apply pending migrations');
    self::writeLn(Colors::yellow('   rollback'), ' - Revert the last applied migration');
    self::writeLn(' Available options:');
    self::writeLn(Colors::yellow('   -h --help'), '   - Display this help message');
    self::writeLn(Colors::yellow('   -c --config'), ' - Specify the path to the configuration file');
  }


  public static function helpCreate(): void
  {
    self::writeLn(' Description:');
    self::writeLn('   Create a new migration file');
    self::writeLn(' Usage:');
    self::writeLn(Colors::yellow('   degita create <name>'));
    self::writeLn(' Arguments:');
    self::writeLn(Colors::yellow('   name'), ' - The name of the migration, must be CamelCase');
  }


  public static function helpMigrate(): void
  {
    self::writeLn(' Description:');
    self::writeLn('   Runs all waiting a migrations or all migrations until the name or ID check migration, include');
    self::writeLn(' Usage:');
    self::writeLn(Colors::yellow('   degita migrate [-n=<name>] [--id=<id>]'));
    self::writeLn(' Options:');
    self::writeLn(Colors::yellow('   -n'), '   - The name of the migration that will run last');
    self::writeLn(Colors::yellow('   --id'), ' - The ID of the migration that will run last');
  }


  public static function helpRollback(): void
  {
    self::writeLn(' Description:');
    self::writeLn('   Reverting the last applied migration or all migrations until the name or ID check migration, include');
    self::writeLn(' Usage:');
    self::writeLn(Colors::yellow('   degita rollback [-n=<name>] [--id=<id>]'));
    self::writeLn(' Options:');
    self::writeLn(Colors::yellow('   -n'), '   - The name of the migration that will rolling last');
    self::writeLn(Colors::yellow('   --id'), ' - The ID of the migration that will rolling last');
  }


  public static function helpClean(): void
  {
    self::writeLn(' Description:');
    self::writeLn('   Removed all unprocessed migrations from the database');
    self::writeLn(' Usage:');
    self::writeLn(Colors::yellow('   degita clean'));
  }


  public static function error(string|Throwable $message, ?callable $help = null): never
  {
    $sql = $message instanceof PDOException
      ? $message->getTrace()[0]['args'][0]
      : null;

    if ($message instanceof Throwable) {
      $message = sprintf('%s [%s]: %s', $message::class, $message->getCode(), $message->getMessage());
    }

    self::writeLn();
    self::writeLn('', Colors::red($message));

    $message && self::writeLn();
    $sql && self::writeLn(' In SQL:', Colors::cyan($sql));
    $help && $help();

    Helper::exitError();
  }
}
