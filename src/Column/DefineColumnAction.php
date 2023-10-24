<?php

namespace Lawondyss\DeGiulietta\Column;

use Lawondyss\DeGiulietta\ActionDefineType;
use Lawondyss\DeGiulietta\Allowed;
use Lawondyss\DeGiulietta\DataType;
use Lawondyss\DeGiulietta\Exception\CannotUse;
use Lawondyss\DeGiulietta\Exception\InvalidArgument;
use Lawondyss\DeGiulietta\Expression;
use Lawondyss\DeGiulietta\Helpers;
use function array_filter;
use function count;
use function explode;
use function str_contains;

readonly class DefineColumnAction extends ColumnAction
{
  public function __construct(
    string $name,
    public ActionDefineType $defineType,
    public DataType $dataType,
    public bool $null = false,
    public string|int|float|Expression|null $default = null,
    public bool $autoIncrement = false,
    public ?string $comment = null,
    public ?string $characterSet = null,
    public ?string $collate = null,
    public bool $first = false,
    public ?string $after = null,
  ) {
    if (isset($default)) {
      if ($dataType->allowed->missing(Allowed::Default)) {
        throw new CannotUse("DEFAULT for {$name} with data type {$dataType->name}");
      }

      if (!$this->default instanceof Expression) {
        if (
          $this->dataType->name === DataType::enum('')->name &&
          !Helpers::inArray($this->default, $this->dataType->values)
        ) {
          throw new InvalidArgument("DEFAULT for {$this->name} must be from ENUM values, given {$this->default}");
        }

        if ($this->dataType->name === DataType::set('')->name) {
          if (str_contains($this->default, ' ')) {
            throw new InvalidArgument("DEFAULT for {$this->name} cannot contain space");
          }

          $parts = explode(',', $this->default);
          $checks = array_filter($parts, fn(string $part): bool => Helpers::inArray($part, $this->dataType->values));

          if (count($parts) !== count($checks)) {
            throw new InvalidArgument("DEFAULT for {$this->name} must be from SET values, given {$this->default}");
          }
        }

        $defaultType = Helpers::getVarType($default);

        if ($defaultType !== $dataType->defaultType) {
          throw new InvalidArgument("DEFAULT must be {$dataType->defaultType}, given {$defaultType}");
        }
      }
    }

    if ($autoIncrement && $dataType->allowed->missing(Allowed::AutoIncrement)) {
      throw CannotUse::withDataType('AUTO_INCREMENT', $name, $dataType);
    }

    if (isset($this->comment) && Helpers::stringLength($this->comment) > 1_024) {
      throw new InvalidArgument('COMMENT cannot be longer than 1024 chars');
    }

    if ($characterSet && $dataType->allowed->missing(Allowed::Collation)) {
      throw CannotUse::withDataType('CHARACTER SET', $name, $dataType);
    }

    if ($collate && $dataType->allowed->missing(Allowed::Collation)) {
      throw CannotUse::withDataType('COLLATE', $name, $dataType);
    }

    if ($first && isset($after)) {
      throw new CannotUse("FIRST and AFTER for {$name} in the same time");
    }

    parent::__construct($name);
  }
}