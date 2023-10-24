<?php

namespace Lawondyss\DeGiulietta;

use function array_reduce;

readonly class Allowed
{
  public const Default = 1;
  public const AutoIncrement = 1 << self::Default;
  public const Collation = 1 << self::AutoIncrement;


  protected function __construct(
    protected int $permissions,
  ) {}


  public static function new(int $permission, int ...$permissions): self
  {
    $perms = array_reduce([$permission, ...$permissions], fn(int $perms, int $perm): int => $perms | $perm, 0);

    return new self($perms);
  }


  public function have(int $permission): bool
  {
    return ($this->permissions & $permission) === $permission;
  }


  public function missing(int $permission): bool
  {
    return !$this->have($permission);
  }
}