<?php

use Lawondyss\DeGiulietta\DataType;
use Lawondyss\DeGiulietta\Driver;
use Lawondyss\DeGiulietta\Engine;
use Lawondyss\DeGiulietta\Exception\CannotUse;
use Lawondyss\DeGiulietta\Exception\DeGiuliettaException;
use Lawondyss\DeGiulietta\ForeignKey\ForeignKeyRestriction;
use Lawondyss\DeGiulietta\Migration;
use Lawondyss\DeGiulietta\Table\Table;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

function migration(): Migration
{
  return new class ( new Driver ) extends Migration {
    public function up(): void {}
    public function down(): void {}
  };
}

test('DROP COLUMN', function (): void {
  $table = migration()->createTable('t');
  Assert::type(Table::class, $table);
  Assert::exception(fn() => $table->dropColumn('c'), CannotUse::class);
});

test('RENAME COLUMN', function (): void {
  $table = migration()->createTable('t');
  Assert::type(Table::class, $table);
  Assert::exception(fn() => $table->renameColumn('c1', 'c2'), CannotUse::class);
});

test('CHANGE COLUMN', function (): void {
  $table = migration()->createTable('t');
  Assert::type(Table::class, $table);
  Assert::exception(fn() => $table->changeColumn('c1', 'c2', DataType::int()), CannotUse::class);
});

test('ADD COLUMN', function (): void {
  $mig = migration();
  $table = $mig->createTable('t');
  Assert::type(Table::class, $table);

  $table->addColumn('c', DataType::int());
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` INT NOT NULL);'], $this->tablesStatements());
  });
});

test('ADD COLUMN NULL', function (): void {
  $mig = migration();

  $mig->createTable('t')
      ->addColumn('c', DataType::int(), null: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` INT NULL);'], $this->tablesStatements());
  });
});

test('ADD COLUMN DEFAULT', function (): void {
  $mig = migration();

  $mig->createTable('t')
      ->addColumn('c', DataType::int(), default: 0);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` INT NOT NULL DEFAULT 0);'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::tinyText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::text(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::mediumText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::longText(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::tinyBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::blob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::mediumBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::longBlob(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::json(), default: ''), CannotUse::class);
});

test('ADD COLUMN AUTO_INCREMENT', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::bit(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` BIT NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::tinyInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` TINYINT NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::smallInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` SMALLINT NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::mediumInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` MEDIUMINT NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::int(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` INT NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::bigInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` BIGINT NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::float(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` FLOAT(1) NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::decimal(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (`c` DECIMAL(1, 0) NOT NULL AUTO_INCREMENT);'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::char(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::varChar(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::binary(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::varBinary(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::enum(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::set(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::json(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::tinyText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::text(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::mediumText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::longText(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::tinyBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::blob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::mediumBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::longBlob(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::year(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::date(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::dateTime(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::time(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->createTable('t')->addColumn('c', DataType::timestamp(), autoIncrement: true), CannotUse::class);
});

test('ADD COLUMN COMMENT', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::int(), comment: 'foo bar');
  Assert::with($mig, function (): void {
    Assert::same(['CREATE TABLE `t` (`c` INT NOT NULL COMMENT "foo bar");'], $this->tablesStatements());
  });
});

test('ADD COLUMN CHARACTER SET', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::char(), characterSet: 'utf8');
  Assert::with($mig, function (): void {
    Assert::same(['CREATE TABLE `t` (`c` CHAR NOT NULL CHARACTER SET utf8);'], $this->tablesStatements());
  });
});

test('ADD COLUMN COLLATE', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addColumn('c', DataType::char(), collate: 'utf8_bin');
  Assert::with($mig, function (): void {
    Assert::same(['CREATE TABLE `t` (`c` CHAR NOT NULL COLLATE utf8_bin);'], $this->tablesStatements());
  });
});

test('ADD COLUMN FIRST', function (): void {
  $table = migration()->createTable('t');
  Assert::exception(fn() => $table->addColumn('c', DataType::char(), first: true), CannotUse::class);
});

test('ADD COLUMN AFTER', function (): void {
  $table = migration()->createTable('t');
  Assert::exception(fn() => $table->addColumn('c', DataType::char(), after: 'x'), CannotUse::class);
});

test('ADD PRIMARY COLUMN', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addPrimaryColumn();
  Assert::with($mig, function (): void {
    Assert::same(['CREATE TABLE `t` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`));'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->createTable('t')->addPrimaryColumn()->addPrimaryColumn(), DeGiuliettaException::class);
});

test('DROP INDEX', function (): void {
  $table = migration()->createTable('t');
  Assert::exception(fn() => $table->dropIndex('i'), CannotUse::class);
});

test('DROP PRIMARY KEY', function (): void {
  $table = migration()->createTable('t');
  Assert::exception(fn() => $table->dropPrimaryKey(), CannotUse::class);
});

test('RENAME INDEX', function (): void {
  $table = migration()->createTable('t');
  Assert::exception(fn() => $table->renameIndex('i1', 'i2'), CannotUse::class);
});

test('ADD INDEX', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addIndex('c');
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (INDEX `IDX_c`(`c`));'], $this->tablesStatements(), 'single column');
  });

  $mig = migration();
  $mig->createTable('t')
      ->addIndex('c1', 'c2');
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (INDEX `IDX_c1__c2`(`c1`,`c2`));'], $this->tablesStatements(), 'multi columns');
  });
});

test('ADD INDEX UNIQUE', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addUniqueIndex('c');
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (UNIQUE INDEX `UNQ_c`(`c`));'], $this->tablesStatements(), 'single column');
  });

  $mig = migration();
  $mig->createTable('t')
      ->addUniqueIndex('c1', 'c2');
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (UNIQUE INDEX `UNQ_c1__c2`(`c1`,`c2`));'], $this->tablesStatements(), 'multi columns');
  });
});

test('ADD INDEX FULLTEXT', function (): void {
  $mig = migration();
  $mig->createTable('t', engine: Engine::MyISAM)
      ->addFulltextIndex('c');
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (FULLTEXT INDEX `FLT_c`(`c`)) ENGINE MyISAM;'], $this->tablesStatements(), 'single column');
  });

  Assert::exception(fn() => migration()->createTable('t')->addFulltextIndex('c'), CannotUse::class);
});

test('ADD PRIMARY KEY', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addPrimaryKey('c');
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (PRIMARY KEY (`c`));'], $this->tablesStatements(), 'single column');
  });

  $mig = migration();
  $mig->createTable('t')
      ->addPrimaryKey('c1', 'c2');
  Assert::with($mig, function () {
    Assert::same(['CREATE TABLE `t` (PRIMARY KEY (`c1`,`c2`));'], $this->tablesStatements(), 'multi columns');
  });

  Assert::exception(fn() => migration()->createTable('t')->addPrimaryKey('c')->addPrimaryKey('c'), DeGiuliettaException::class);
});

test('ADD FOREIGN KEY', function (): void {
  $mig = migration();
  $mig->createTable('t')
      ->addForeignKey('c', 'rt');
  Assert::with($mig, function (): void {
    Assert::same(['CREATE TABLE `t` (FOREIGN KEY `FK_t_c`(`c`) REFERENCES `rt`(`id`) ON UPDATE RESTRICT ON DELETE RESTRICT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addForeignKey('c', 'rt', 'rc');
  Assert::with($mig, function (): void {
    Assert::same(['CREATE TABLE `t` (FOREIGN KEY `FK_t_c`(`c`) REFERENCES `rt`(`rc`) ON UPDATE RESTRICT ON DELETE RESTRICT);'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->createTable('t')
      ->addForeignKey('c', 'rt', onUpdate: ForeignKeyRestriction::Cascade, onDelete: ForeignKeyRestriction::SetNull);
  Assert::with($mig, function (): void {
    Assert::same(['CREATE TABLE `t` (FOREIGN KEY `FK_t_c`(`c`) REFERENCES `rt`(`id`) ON UPDATE CASCADE ON DELETE SET NULL);'], $this->tablesStatements());
  });
});

test('DROP FOREIGN KEY', function (): void {
  Assert::exception(fn() => migration()->createTable('t')->dropForeignKey('fk'), CannotUse::class);

});