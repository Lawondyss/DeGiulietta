<?php

namespace Lawondyss\DeGiulietta;

use Lawondyss\DeGiulietta\Column\ChangeColumnAction;
use Lawondyss\DeGiulietta\Column\DefineColumnAction;
use Lawondyss\DeGiulietta\Column\DropColumnAction;
use Lawondyss\DeGiulietta\Column\RenameColumnAction;
use Lawondyss\DeGiulietta\Exception\Unsupported;
use Lawondyss\DeGiulietta\ForeignKey\DefineForeignKeyAction;
use Lawondyss\DeGiulietta\ForeignKey\DropForeignKeyAction;
use Lawondyss\DeGiulietta\Index\DefineIndexAction;
use Lawondyss\DeGiulietta\Index\DropIndexAction;
use Lawondyss\DeGiulietta\Index\DropPrimaryIndexAction;
use Lawondyss\DeGiulietta\Index\RenameIndexAction;
use Lawondyss\DeGiulietta\Table\DefineTableAction;
use Lawondyss\DeGiulietta\Table\DropTableAction;
use Lawondyss\DeGiulietta\Table\RenameTableAction;
use Lawondyss\DeGiulietta\Table\TableAction;
use Lawondyss\DeGiulietta\Table\TruncateTableAction;
use function array_map;
use function implode;
use function sprintf;

class Driver
{
  protected const string StringQuote = '"';
  protected const string NameQuote = '`';
  protected const string SchemaDelimiter = '.';


  public static function CurrentTimestamp(): Expression
  {
    return new Expression('CURRENT_TIMESTAMP');
  }


  public static function CurrentTimestampOnChange(): Expression
  {
    return new Expression('NULL ON UPDATE CURRENT_TIMESTAMP');
  }


  public function table(TableAction $tableAction): string
  {
    return match (true) {
      $tableAction instanceof DefineTableAction => $this->defineTableStatement($tableAction),
      $tableAction instanceof RenameTableAction => $this->renameTableStatement($tableAction),
      $tableAction instanceof DropTableAction => $this->dropTableStatement($tableAction),
      $tableAction instanceof TruncateTableAction => $this->truncateTableStatement($tableAction),
      default => throw Unsupported::action('table', $tableAction::class),
    };
  }


  /****************************** TABLE **************************************/

  protected function defineTableStatement(DefineTableAction $tableAction): string
  {
    $tableName = $this->quoteName($tableAction->name);
    $sql = "{$tableAction->defineType->value} TABLE {$tableName}";

    $hasParentheses = $tableAction->defineType === ActionDefineType::Create;
    $sql .= $hasParentheses ? ' (' : ' ';

    $itemsSql = [];
    $isAlter = $tableAction->defineType === ActionDefineType::Alter;

    foreach ($tableAction->table->columns as $columnAction) {
      $itemsSql[] = match (true) {
        $columnAction instanceof DefineColumnAction => $this->defineColumnStatement($isAlter, $columnAction),
        $columnAction instanceof RenameColumnAction => $this->renameColumnStatement($columnAction),
        $columnAction instanceof DropColumnAction => $this->dropColumnStatement($columnAction),
        default => throw Unsupported::action('column', $columnAction::class),
      };
    }

    foreach ($tableAction->table->indexes as $indexAction) {
      $itemsSql[] = match (true) {
        $indexAction instanceof DefineIndexAction => $this->defineIndexStatement($isAlter, $indexAction),
        $indexAction instanceof RenameIndexAction => $this->renameIndexStatement($indexAction),
        $indexAction instanceof DropIndexAction => $this->dropIndexStatement($indexAction),
        default => throw Unsupported::action('index', $indexAction::class),
      };
    }

    foreach ($tableAction->table->foreignKeys as $fkAction) {
      $itemsSql[] = match (true) {
        $fkAction instanceof DefineForeignKeyAction => $this->defineFkStatement($isAlter, $fkAction),
        $fkAction instanceof DropForeignKeyAction => $this->dropFkStatement($fkAction),
        default => throw Unsupported::action('foreign key', $fkAction::class),
      };
    }

    if ($itemsSql !== []) {
      $sql .= implode(', ', $itemsSql);
    }

    $sql .= $hasParentheses ? ')' : '';

    if (isset($tableAction->comment)) {
      $comment = $this->quoteValue($tableAction->comment);
      $sql .= " COMMENT {$comment}";
    }

    if (isset($tableAction->characterSet)) {
      $sql .= " CHARACTER SET {$tableAction->characterSet}";
    }

    if (isset($tableAction->collate)) {
      $sql .= " COLLATE {$tableAction->collate}";
    }

    if (isset($tableAction->encryption)) {
      $encryption = $tableAction->encryption ? 'Y' : 'N';
      $sql .= " ENCRYPTION {$encryption}";
    }

    if (isset($tableAction->password)) {
      $password = $this->quoteValue($tableAction->password);
      $sql .= " PASSWORD {$password}";
    }

    if (isset($tableAction->engine)) {
      $sql .= " ENGINE {$tableAction->engine->value}";
    }

    return $sql . ';';
  }


  protected function renameTableStatement(RenameTableAction $tableAction): string
  {
    $oldName = $this->quoteName($tableAction->name);
    $newName = $this->quoteName($tableAction->newName);

    return "RENAME TABLE {$oldName} TO {$newName};";
  }


  protected function dropTableStatement(DropTableAction $tableAction): string
  {
    $name = $this->quoteName($tableAction->name);

    return "DROP TABLE {$name};";
  }


  protected function truncateTableStatement(TruncateTableAction $tableAction): string
  {
    $name = $this->quoteName($tableAction->name);

    return "TRUNCATE TABLE {$name};";
  }


  /****************************** COLUMN *************************************/

  protected function defineColumnStatement(bool $isAlter, DefineColumnAction $columnAction): string
  {
    $sql = match(true) {
      $columnAction instanceof ChangeColumnAction => 'CHANGE COLUMN ',
      $columnAction->defineType === ActionDefineType::Modify => 'MODIFY COLUMN ',
      $isAlter => 'ADD COLUMN ',
      default => '',
    };

    $sql .= $this->quoteName($columnAction->name);

    if ($columnAction instanceof ChangeColumnAction) {
      $sql .= ' ' . $this->quoteName($columnAction->newName);
    }

    $sql .= ' ' . $this->dataTypeStatement($columnAction->dataType);

    $sql .= (!$columnAction->null ? ' NOT' : '') . ' NULL';

    if (isset($columnAction->default)) {
      if ($columnAction->default instanceof Expression) {
        $sql .= " DEFAULT {$columnAction->default->statement}";
      } else {
        $defaultType = get_debug_type($columnAction->default);
        $defaultValue = $columnAction->default;

        $sql .= ' DEFAULT ' . match ($defaultType) {
            'bool' => sprintf('%d', (int)$defaultValue),
            'string' => $this->quoteValue($defaultValue),
            'int', 'float' => "{$defaultValue}",
            default => throw Unsupported::valueType($defaultType),
          };
      }
    }

    if ($columnAction->autoIncrement) {
      $sql .= ' AUTO_INCREMENT';
    }

    if (isset($columnAction->comment)) {
      $comment = $this->quoteValue($columnAction->comment);
      $sql .= " COMMENT {$comment}";
    }

    if (isset($columnAction->characterSet)) {
      $sql .= " CHARACTER SET {$columnAction->characterSet}";
    }

    if (isset($columnAction->collate)) {
      $sql .= " COLLATE {$columnAction->collate}";
    }

    if ($columnAction->first) {
      $sql .= ' FIRST';
    }

    if (isset($columnAction->after)) {
      $after = $this->quoteName($columnAction->after);
      $sql .= " AFTER {$after}";
    }

    return $sql;
  }


  protected function renameColumnStatement(RenameColumnAction $columnAction): string
  {
    $oldName = $this->quoteName($columnAction->name);
    $newName = $this->quoteName($columnAction->newName);

    return "RENAME COLUMN {$oldName} TO {$newName}";
  }


  protected function dropColumnStatement(DropColumnAction $columnAction): string
  {
    $name = $this->quoteName($columnAction->name);

    return "DROP COLUMN {$name}";
  }


  protected function dataTypeStatement(DataType $dataType): string
  {
    $sql = $dataType->name;

    $hasParentheses = false;

    if (isset($dataType->size) || isset($dataType->precision) || isset($dataType->fsp)) {
      $sql .= '(' . ($dataType->size ?? $dataType->precision ?? $dataType->fsp);
      $hasParentheses = true;
    }

    if ($hasParentheses && isset($dataType->scale)) {
      $sql .= ", {$dataType->scale}";
    }

    if (isset($dataType->values)) {
      $values = array_map($this->quoteValue(...), $dataType->values);
      $sql .= '(' . implode(', ', $values) . ')';
    }

    if ($hasParentheses) {
      $sql .= ')';
    }

    if ($dataType->unsigned) {
      $sql .= ' UNSIGNED';
    }

    return $sql;
  }


  /****************************** INDEX **************************************/

  protected function defineIndexStatement(bool $isAlter, DefineIndexAction $indexAction): string
  {
    $sql = $isAlter ? 'ADD ' : '';

    if ($indexAction->primary) {
      $sql .= 'PRIMARY KEY ';
    } else {
      $sql .= match(true) {
        $indexAction->unique => 'UNIQUE INDEX ',
        $indexAction->fulltext => 'FULLTEXT INDEX ',
        default => 'INDEX ',
      };

      $sql .= $this->quoteName($indexAction->name);
    }

    $columns = array_map(fn(string $col): string => $this->quoteName($col), $indexAction->columns);
    $sql .= '(' . implode(',', $columns) . ')';

    return $sql;
  }


  protected function renameIndexStatement(RenameIndexAction $indexAction): string
  {
    $oldName = $this->quoteName($indexAction->name);
    $newName = $this->quoteName($indexAction->newName);

    return "RENAME INDEX {$oldName} TO {$newName}";
  }


  protected function dropIndexStatement(DropIndexAction $indexAction): string
  {
    return 'DROP ' . (
      $indexAction instanceof DropPrimaryIndexAction
        ? 'PRIMARY KEY'
        : ('INDEX ' . $this->quoteName($indexAction->name))
      );
  }


  /****************************** FOREIGN KEY ********************************/

  protected function defineFkStatement(bool $isAlter, DefineForeignKeyAction $fkAction): string
  {
    $sql = $isAlter ? 'ADD ' : '';

    $sql .= 'FOREIGN KEY ' . $this->quoteName($fkAction->name) .
      '(' . $this->quoteName($fkAction->column) . ') REFERENCES ' .
      $this->quoteName($fkAction->referredTable) . '(' . $this->quoteName($fkAction->referredColumn) . ')';

    $sql .= ' ON UPDATE ' . $fkAction->onUpdate->value;
    $sql .= ' ON DELETE ' . $fkAction->onDelete->value;

    return $sql;
  }


  protected function dropFkStatement(DropForeignKeyAction $fkAction): string
  {
    return 'DROP FOREIGN KEY ' . $this->quoteName($fkAction->name);
  }


  /****************************** UTILS **************************************/

  protected function quoteValue(string $value): string
  {
    $valueType = get_debug_type($value);

    return match ($valueType) {
      'bool' => sprintf('%d', (int)$value),
      'string' => sprintf('%s%s%s', static::StringQuote, $value, static::StringQuote),
      'int', 'float' => "{$value}",
      default => throw Unsupported::valueType($valueType),
    };
  }


  protected function quoteName(string $name): string
  {
    $names = explode(self::SchemaDelimiter, $name);
    $names = array_map(
      static fn(string $name) => sprintf('%s%s%s', static::NameQuote, $name, static::NameQuote),
      $names,
    );

    return join(self::SchemaDelimiter, $names);
  }
}
