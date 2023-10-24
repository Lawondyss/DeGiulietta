<?php

namespace Lawondyss\DeGiulietta\Table;

use Lawondyss\DeGiulietta\ActionDefineType;
use Lawondyss\DeGiulietta\Actions;
use Lawondyss\DeGiulietta\Column\ChangeColumnAction;
use Lawondyss\DeGiulietta\Column\ColumnAction;
use Lawondyss\DeGiulietta\Column\DefineColumnAction;
use Lawondyss\DeGiulietta\Column\DropColumnAction;
use Lawondyss\DeGiulietta\Column\RenameColumnAction;
use Lawondyss\DeGiulietta\DataType;
use Lawondyss\DeGiulietta\Engine;
use Lawondyss\DeGiulietta\Exception\CannotUse;
use Lawondyss\DeGiulietta\Exception\DeGiuliettaException;
use Lawondyss\DeGiulietta\Expression;
use Lawondyss\DeGiulietta\ForeignKey\DefineForeignKeyAction;
use Lawondyss\DeGiulietta\ForeignKey\DropForeignKeyAction;
use Lawondyss\DeGiulietta\ForeignKey\ForeignKeyAction;
use Lawondyss\DeGiulietta\ForeignKey\ForeignKeyRestriction;
use Lawondyss\DeGiulietta\Index\DefineIndexAction;
use Lawondyss\DeGiulietta\Index\DropIndexAction;
use Lawondyss\DeGiulietta\Index\DropPrimaryIndexAction;
use Lawondyss\DeGiulietta\Index\IndexAction;
use Lawondyss\DeGiulietta\Index\RenameIndexAction;
use Lawondyss\DeGiulietta\Naming;

class Table
{
  /** @var Actions<ColumnAction> */
  public readonly Actions $columns;

  /** @var Actions<IndexAction> */
  public readonly Actions $indexes;

  /** @var Actions<ForeignKeyAction> */
  public readonly Actions $foreignKeys;

  private bool $isCreate;

  private ?bool $hasPrimary;


  public function __construct(
    public DefineTableAction $tableAction,
  ) {
    $this->columns = new Actions;
    $this->indexes = new Actions;
    $this->foreignKeys = new Actions;
    $this->isCreate = $this->tableAction->defineType === ActionDefineType::Create;
    $this->hasPrimary = $this->isCreate ? false : null;
  }


  /****************************** COLUMN *************************************/

  public function addColumn(
    string $name,
    DataType $dataType,
    bool $null = false,
    string|int|float|Expression|null $default = null,
    bool $autoIncrement = false,
    ?string $comment = null,
    ?string $characterSet = null,
    ?string $collate = null,
    bool $first = false,
    ?string $after = null,
  ): self {
    if ($this->isCreate) {
      if ($first) {
        throw CannotUse::inCreateTable('FIRST', $this->tableAction->name);
      }
      if (isset($after)) {
        throw CannotUse::inCreateTable('AFTER', $this->tableAction->name);
      }
    }

    $this->columns->add(new DefineColumnAction(
      $name,
      ActionDefineType::Create,
      $dataType,
      $null,
      $default,
      $autoIncrement,
      $comment,
      $characterSet,
      $collate,
      $first,
      $after,
    ));

    return $this;
  }


  public function modifyColumn(
    string $name,
    DataType $dataType,
    bool $null = false,
    string|int|float|Expression|null $default = null,
    bool $autoIncrement = false,
    ?string $comment = null,
    ?string $characterSet = null,
    ?string $collate = null,
    bool $first = false,
    ?string $after = null,
  ): self {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('MODIFY COLUMN ' . $name, $this->tableAction->name);
    }

    $this->columns->add(new DefineColumnAction(
      $name,
      ActionDefineType::Modify,
      $dataType,
      $null,
      $default,
      $autoIncrement,
      $comment,
      $characterSet,
      $collate,
      $first,
      $after,
    ));

    return $this;
  }


  public function changeColumn(
    string $oldName,
    string $newName,
    DataType $dataType,
    bool $null = false,
    string|int|float|Expression|null $default = null,
    bool $autoIncrement = false,
    ?string $comment = null,
    ?string $characterSet = null,
    ?string $collate = null,
    bool $first = false,
    ?string $after = null,
  ): self {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('CHANGE COLUMN ' . $oldName, $this->tableAction->name);
    }

    $this->columns->add(new ChangeColumnAction(
      $oldName,
      $newName,
      $dataType,
      $null,
      $default,
      $autoIncrement,
      $comment,
      $characterSet,
      $collate,
      $first,
      $after,
    ));

    return $this;
  }


  public function renameColumn(string $oldName, string $newName): self
  {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('RENAME COLUMN ' . $oldName, $this->tableAction->name);
    }

    $this->columns->add(new RenameColumnAction($oldName, $newName));

    return $this;
  }


  public function dropColumn(string $name): self
  {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('DROP COLUMN ' . $name, $this->tableAction->name);
    }

    $this->columns->add(new DropColumnAction($name));

    return $this;
  }


  public function addPrimaryColumn(string $name = 'id'): self
  {
    $this->addPrimaryKey($name);
    $this->addColumn($name, DataType::int(unsigned: true), autoIncrement: true);

    return $this;
  }


  /****************************** INDEX **************************************/

  public function addIndex(string $column, string ...$columns): self
  {
    $columns = [$column, ...$columns];
    $this->indexes->add(new DefineIndexAction($columns, Naming::commonIndex($columns)));

    return $this;
  }


  public function addUniqueIndex(string $column, string ...$columns): self
  {
    $columns = [$column, ...$columns];
    $this->indexes->add(new DefineIndexAction($columns, Naming::uniqueIndex($columns), unique: true));

    return $this;
  }


  public function addFulltextIndex(string $column): self
  {
    if ($this->tableAction->engine !== Engine::MyISAM) {
      throw new CannotUse("FULLTEXT INDEX for table {$this->tableAction->name} without MyISAM engine");
    }

    $columns = [$column];
    $this->indexes->add(new DefineIndexAction($columns, Naming::fulltextIndex($column), fulltext: true));

    return $this;
  }


  public function addPrimaryKey(string $column, string ...$columns): self
  {
    if ($this->hasPrimary ?? false) {
      throw new DeGiuliettaException('More than one primary key cannot be defined');
    }

    $this->indexes->add(new DefineIndexAction([$column, ...$columns], null, primary: true));
    $this->hasPrimary = true;

    return $this;
  }


  public function renameIndex(string $oldName, string $newName): self
  {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('RENAME INDEX ' . $oldName, $this->tableAction->name);
    }

    $this->indexes->add(new RenameIndexAction($oldName, $newName));

    return $this;
  }


  public function dropIndex(string $name): self
  {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('DROP INDEX ' . $name, $this->tableAction->name);
    }

    $this->indexes->add(new DropIndexAction($name));

    return $this;
  }


  public function dropPrimaryKey(): self
  {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('DROP PRIMARY KEY', $this->tableAction->name);
    }

    $this->indexes->add(new DropPrimaryIndexAction());

    return $this;
  }


  /****************************** FOREIGN KEY ********************************/

  public function addForeignKey(
    string $column,
    string $referredTable,
    string $referredColumn = 'id',
    ForeignKeyRestriction $onUpdate = ForeignKeyRestriction::Restrict,
    ForeignKeyRestriction $onDelete = ForeignKeyRestriction::Restrict,
  ): self {
    $this->foreignKeys->add(new DefineForeignKeyAction(
      Naming::foreignKey($this->tableAction->name, $column),
      $column,
      $referredTable,
      $referredColumn,
      $onUpdate,
      $onDelete,
    ));
    return $this;
  }


  public function dropForeignKey(string $name): self
  {
    if ($this->isCreate) {
      throw CannotUse::inCreateTable('DROP FOREIGN KEY ' . $name, $this->tableAction->name);
    }

    $this->foreignKeys->add(new DropForeignKeyAction($name));

    return $this;
  }


  /****************************** UTILS **************************************/

}
