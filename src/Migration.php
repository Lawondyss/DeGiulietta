<?php

namespace Lawondyss\DeGiulietta;

use Lawondyss\DeGiulietta\Exception\DeGiuliettaException;
use Lawondyss\DeGiulietta\Exception\Unsupported;
use Lawondyss\DeGiulietta\Table\DefineTableAction;
use Lawondyss\DeGiulietta\Table\DropTableAction;
use Lawondyss\DeGiulietta\Table\RenameTableAction;
use Lawondyss\DeGiulietta\Table\Table;
use Lawondyss\DeGiulietta\Table\TruncateTableAction;

abstract class Migration
{
  protected readonly Actions $tables;


  public function __construct(
    public readonly Driver $driver,
  ) {
    $this->tables = new Actions;
  }


  public function createTable(
    string $name,
    ?string $comment = null,
    ?string $characterSet = null,
    ?string $collate = null,
    ?Engine $engine = null,
    ?bool $encryption = null,
    ?string $password = null,
  ): Table {
    $this->checkTable($name);

    $action = new DefineTableAction(
      $name,
      ActionDefineType::Create,
      $comment,
      $characterSet,
      $collate,
      $engine,
      $encryption,
      $password,
    );
    $this->tables->add($action);

    return $action->table;
  }


  public function alterTable(
    string $name,
    ?string $comment = null,
    ?string $characterSet = null,
    ?string $collate = null,
    ?Engine $engine = null,
    ?bool $encryption = null,
    ?string $password = null,
  ): Table {
    $this->checkTable($name);

    $action = new DefineTableAction(
      $name,
      ActionDefineType::Alter,
      $comment,
      $characterSet,
      $collate,
      $engine,
      $encryption,
      $password,
    );
    $this->tables->add($action);

    return $action->table;
  }


  public function renameTable(string $oldName, string $newName): self
  {
    $this->checkTable($oldName);
    $this->checkTable($newName);

    $this->tables->add(new RenameTableAction($oldName, $newName));

    return $this;
  }


  public function dropTable(string $name): self
  {
    $this->checkTable($name);

    $this->tables->add(new DropTableAction($name));

    return $this;
  }


  public function truncateTable(string $name): self
  {
    $this->checkTable($name);

    $this->tables->add(new TruncateTableAction($name));

    return $this;
  }


  /**
   * @throws DeGiuliettaException
   */
  protected function checkTable(string $name): void
  {
    if ($name === '') {
      throw new DeGiuliettaException('Name of table cannot be empty');
    }
  }


  protected function migrate(): void
  {
    foreach ($this->tablesStatements() as $statement) {
      dump($statement);
    }
  }


  /**
   * @return string[]
   * @throws Unsupported
   */
  protected function tablesStatements(): array
  {
    $statements = [];

    foreach ($this->tables as $tableAction) {
      $statements[] = $this->driver->table($tableAction);
    }

    return $statements;
  }
}
