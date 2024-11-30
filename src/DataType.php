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
      name: 'CHAR',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function varChar(?int $length = null): self
  {
    if (isset($length) && !Helpers::isInRange($length, 0, 65_535)) {
      throw InvalidArgument::mustBeBetween('Length of VARCHAR', 0, 65_535);
    }

    return new self(
      name: 'VARCHAR',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function binary(?int $length = null): self
  {
    if (isset($length) && !Helpers::isInRange($length, 0, 255)) {
      throw InvalidArgument::mustBeBetween('Length of BINARY', 0, 255);
    }

    return new self(
      name: 'BINARY',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function varBinary(?int $length = null): self
  {
    if (isset($length) && !Helpers::isInRange($length, 0, 65_535)) {
      throw InvalidArgument::mustBeBetween('Length of VARBINARY', 0, 65_535);
    }

    return new self(
      name: 'VARBINARY',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default, Allowed::Collation),
      size: $length,
    );
  }


  public static function tinyText(): self
  {
    return new self(
      name: 'TINYTEXT',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function text(): self
  {
    return new self(
      name: 'TEXT',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function mediumText(): self
  {
    return new self(
      name: 'MEDIUMTEXT',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function longText(): self
  {
    return new self(
      name: 'LONGTEXT',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function tinyBlob(): self
  {
    return new self(
      name: 'TINYBLOB',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function blob(): self
  {
    return new self(
      name: 'BLOB',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function mediumBlob(): self
  {
    return new self(
      name: 'MEDIUMBLOB',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function longBlob(): self
  {
    return new self(
      name: 'LONGBLOB',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  public static function enum(string $value, string ...$values): self
  {
    array_unshift($values, $value);

    return new self(
      name: 'ENUM',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default, Allowed::Collation),
      values: $values,
    );
  }


  public static function set(string $value, string ...$values): self
  {
    array_unshift($values, $value);

    return new self(
      name: 'SET',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default, Allowed::Collation),
      values: $values,
    );
  }


  public static function json(): self
  {
    return new self(
      name: 'JSON',
      defaultType: null,
      allowed: Allowed::new(Allowed::Collation),
    );
  }


  /****************************** NUMERIC ************************************/

  public static function bool(): self
  {
    return new self(
      name: 'BOOL',
      defaultType: Helpers::VAR_TYPE_BOOL,
      allowed: Allowed::new(Allowed::Default),
    );
  }


  public static function bit(?int $size = null): self
  {
    if (isset($size) && !Helpers::isInRange($size, 1, 64)) {
      throw InvalidArgument::mustBeBetween('Size of BIT', 1, 64);
    }

    return new self(
      name: 'BIT',
      defaultType: Helpers::VAR_TYPE_INT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      size: $size,
    );
  }


  public static function tinyInt(bool $unsigned = false): self
  {
    return new self(
      name: 'TINYINT',
      defaultType: Helpers::VAR_TYPE_INT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function smallInt(bool $unsigned = false): self
  {
    return new self(
      name: 'SMALLINT',
      defaultType: Helpers::VAR_TYPE_INT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function mediumInt(bool $unsigned = false): self
  {
    return new self(
      name: 'MEDIUMINT',
      defaultType: Helpers::VAR_TYPE_INT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function int(bool $unsigned = false): self
  {
    return new self(
      name: 'INT',
      defaultType: Helpers::VAR_TYPE_INT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function bigInt(bool $unsigned = false): self
  {
    return new self(
      name: 'BIGINT',
      defaultType: Helpers::VAR_TYPE_INT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      unsigned: $unsigned,
    );
  }


  public static function float(int $totalOfDigits): self
  {
    if (!Helpers::isInRange($totalOfDigits, 0, 53)) {
      throw InvalidArgument::mustBeBetween('Precision of FLOAT', 0, 53);
    }

    return new self(
      name: 'FLOAT',
      defaultType: Helpers::VAR_TYPE_FLOAT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
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
      name: 'DECIMAL',
      defaultType: Helpers::VAR_TYPE_FLOAT,
      allowed: Allowed::new(Allowed::Default, Allowed::AutoIncrement),
      precision: $totalOfDigits,
      scale: $numberOfDecimals,
    );
  }


  /****************************** DATE & TIME ********************************/

  public static function year(): self
  {
    return new self(
      name: 'YEAR',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default),
    );
  }


  public static function date(): self
  {
    return new self(
      name: 'DATE',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default),
    );
  }


  public static function dateTime(?int $fractionalSecondsPrecision = null): self
  {
    if (isset($fractionalSecondsPrecision) && !Helpers::isInRange($fractionalSecondsPrecision, 0, 6)) {
      throw InvalidArgument::mustBeBetween('Fractional seconds precision of DATETIME', 0, 6);
    }

    return new self(
      name: 'DATETIME',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default),
      fsp: $fractionalSecondsPrecision,
    );
  }


  public static function time(?int $fractionalSecondsPrecision = null): self
  {
    if (isset($fractionalSecondsPrecision) && !Helpers::isInRange($fractionalSecondsPrecision, 0, 6)) {
      throw InvalidArgument::mustBeBetween('Fractional seconds precision of TIME', 0, 6);
    }

    return new self(
      name: 'TIME',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default),
      fsp: $fractionalSecondsPrecision,
    );
  }


  public static function timestamp(?int $fractionalSecondsPrecision = null): self
  {
    if (isset($fractionalSecondsPrecision) && !Helpers::isInRange($fractionalSecondsPrecision, 0, 6)) {
      throw InvalidArgument::mustBeBetween('Fractional seconds precision of TIMESTAMP', 0, 6);
    }

    return new self(
      name: 'TIMESTAMP',
      defaultType: Helpers::VAR_TYPE_STRING,
      allowed: Allowed::new(Allowed::Default),
      fsp: $fractionalSecondsPrecision,
    );
  }
}