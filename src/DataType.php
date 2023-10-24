<?php

namespace Lawondyss\DeGiulietta;

use Lawondyss\DeGiulietta\Exception\InvalidArgument;
use function array_unshift;

readonly class DataType
{
  protected function __construct(
    public string $name,
    public ?string $defaultType,
    public Allowed $allowed,
    public ?int $size = null,
    public ?int $precision = null,
    public ?int $scale = null,
    public ?int $fsp = null,
    public bool $unsigned = false,
    public ?array $values = null,
  ) {}


  /****************************** STRING *************************************/

  public static function char(?int $length = null): self
  {
    if (isset($length) && !Helpers::isInRange($length, 0, 255)) {
      throw InvalidArgument::mustBeBetween('Length of CHAR', 0, 255);
    }

    return new self(
      'CHAR',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function varChar(?int $length = null): self
  {
    if (isset($length) && !Helpers::isInRange($length, 0, 65_535)) {
      throw InvalidArgument::mustBeBetween('Length of VARCHAR', 0, 65_535);
    }

    return new self(
      'VARCHAR',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function binary(?int $length = null): self
  {
    if (isset($length) && !Helpers::isInRange($length, 0, 255)) {
      throw InvalidArgument::mustBeBetween('Length of BINARY', 0, 255);
    }

    return new self(
      'BINARY',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function varBinary(?int $length = null): self
  {
    if (isset($length) && !Helpers::isInRange($length, 0, 65_535)) {
      throw InvalidArgument::mustBeBetween('Length of VARBINARY', 0, 65_535);
    }

    return new self(
      'VARBINARY',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function tinyText(): self
  {
    return new self(
      'TINYTEXT',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function text(): self
  {
    return new self(
      'TEXT',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function mediumText(): self
  {
    return new self(
      'MEDIUMTEXT',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function longText(): self
  {
    return new self(
      'LONGTEXT',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function tinyBlob(): self
  {
    return new self(
      'TINYBLOB',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function blob(): self
  {
    return new self(
      'BLOB',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function mediumBlob(): self
  {
    return new self(
      'MEDIUMBLOB',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function longBlob(): self
  {
    return new self(
      'LONGBLOB',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  public static function enum(string $value, string ...$values): self
  {
    array_unshift($values, $value);

    return new self(
      'ENUM',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default, Allowed::Collation),
      values: $values,
    );
  }


  public static function set(string $value, string ...$values): self
  {
    array_unshift($values, $value);

    return new self(
      'SET',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default, Allowed::Collation),
      values: $values,
    );
  }


  public static function json(): self
  {
    return new self(
      'JSON',
      null,
      Allowed::new(Allowed::Collation),
    );
  }


  /****************************** NUMERIC ************************************/

  public static function bool(): self
  {
    return new self(
      'BOOL',
      Helpers::VAR_TYPE_BOOL,
      Allowed::new(Allowed::Default),
    );
  }


  public static function bit(?int $size = null): self
  {
    if (isset($size) && !Helpers::isInRange($size, 1, 64)) {
      throw InvalidArgument::mustBeBetween('Size of BIT', 1, 64);
    }

    return new self(
      'BIT',
      Helpers::VAR_TYPE_INT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      size: $size,
    );
  }


  public static function tinyInt(bool $unsigned = false): self
  {
    return new self(
      'TINYINT',
      Helpers::VAR_TYPE_INT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function smallInt(bool $unsigned = false): self
  {
    return new self(
      'SMALLINT',
      Helpers::VAR_TYPE_INT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function mediumInt(bool $unsigned = false): self
  {
    return new self(
      'MEDIUMINT',
      Helpers::VAR_TYPE_INT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function int(bool $unsigned = false): self
  {
    return new self(
      'INT',
      Helpers::VAR_TYPE_INT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function bigInt(bool $unsigned = false): self
  {
    return new self(
      'BIGINT',
      Helpers::VAR_TYPE_INT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function float(int $totalOfDigits): self
  {
    if (!Helpers::isInRange($totalOfDigits, 0, 53)) {
      throw InvalidArgument::mustBeBetween('Precision of FLOAT', 0, 53);
    }

    return new self(
      'FLOAT',
      Helpers::VAR_TYPE_FLOAT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      precision: $totalOfDigits,
    );
  }


  public static function decimal(int $totalOfDigits, int $numberOfDecimals = 0): self
  {
    if ($totalOfDigits < 1) {
      throw InvalidArgument::mustBeGE('Total of digits of DECIMAL', 1);
    }

    if ($numberOfDecimals < 0) {
      throw InvalidArgument::mustBeGE('Decimals of DECIMAL', 0);
    }

    if ($totalOfDigits < $numberOfDecimals) {
      throw new InvalidArgument('Decimals of DECIMAL cannot be greater than total of digits');
    }

    return new self(
      'DECIMAL',
      Helpers::VAR_TYPE_FLOAT,
      Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      precision: $totalOfDigits,
      scale: $numberOfDecimals,
    );
  }


  /****************************** DATE & TIME ********************************/

  public static function year(): self
  {
    return new self(
      'YEAR',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default),
    );
  }


  public static function date(): self
  {
    return new self(
      'DATE',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default),
    );
  }


  public static function dateTime(?int $fractionalSecondsPrecision = null): self
  {
    if (isset($fractionalSecondsPrecision) && !Helpers::isInRange($fractionalSecondsPrecision, 0, 6)) {
      throw InvalidArgument::mustBeBetween('Fractional seconds precision of DATETIME', 0, 6);
    }

    return new self(
      'DATETIME',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default),
      fsp: $fractionalSecondsPrecision,
    );
  }


  public static function time(?int $fractionalSecondsPrecision = null): self
  {
    if (isset($fractionalSecondsPrecision) && !Helpers::isInRange($fractionalSecondsPrecision, 0, 6)) {
      throw InvalidArgument::mustBeBetween('Fractional seconds precision of TIME', 0, 6);
    }

    return new self(
      'TIME',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default),
      fsp: $fractionalSecondsPrecision,
    );
  }


  public static function timestamp(?int $fractionalSecondsPrecision = null): self
  {
    if (isset($fractionalSecondsPrecision) && !Helpers::isInRange($fractionalSecondsPrecision, 0, 6)) {
      throw InvalidArgument::mustBeBetween('Fractional seconds precision of TIMESTAMP', 0, 6);
    }

    return new self(
      'TIMESTAMP',
      Helpers::VAR_TYPE_STRING,
      Allowed::new(Allowed::Default),
      fsp: $fractionalSecondsPrecision,
    );
  }
}