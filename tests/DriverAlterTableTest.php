<?php

use Lawondyss\DeGiulietta\Driver;
use Lawondyss\DeGiulietta\Engine;
use Lawondyss\DeGiulietta\Exception\CannotUse;
use Lawondyss\DeGiulietta\Exception\DeGiuliettaException;
use Lawondyss\DeGiulietta\ForeignKey\ForeignKeyRestriction;
use Lawondyss\DeGiulietta\Migration;
use Lawondyss\DeGiulietta\Table\Table;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

function migration(): Migration
{
  return new class ( new Driver ) extends Migration { };
}

test('ADD PRIMARY COLUMN', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addPrimaryColumn();
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`);'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->createTable('t')->addPrimaryColumn()->addPrimaryColumn(), DeGiuliettaException::class);
});

test('DROP COLUMN', function (): void {
  $mig = migration();

  $table = $mig->alterTable('t');
  Assert::type(Table::class, $table);

  $table->dropColumn('c');
  Assert::with($mig, function() {
    Assert::same(['ALTER TABLE `t` DROP COLUMN `c`;'], $this->tablesStatements());
  });
});

test('RENAME COLUMN', function (): void {
  $mig = migration();

  $table = $mig->alterTable('t');
  Assert::type(Table::class, $table);

  $table->renameColumn('c1', 'c2');
  Assert::with($mig, function() {
    Assert::same(['ALTER TABLE `t` RENAME COLUMN `c1` TO `c2`;'], $this->tablesStatements());
  });
});

test('DROP INDEX', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->dropIndex('i');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` DROP INDEX `i`;'], $this->tablesStatements());
  });
});

test('DROP PRIMARY KEY', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->dropPrimaryKey();
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` DROP PRIMARY KEY;'], $this->tablesStatements());
  });
});

test('RENAME INDEX', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->renameIndex('i1', 'i2');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` RENAME INDEX `i1` TO `i2`;'], $this->tablesStatements());
  });
});

test('ADD INDEX', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addIndex('c');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD INDEX `IDX_c`(`c`);'], $this->tablesStatements(), 'single column');
  });

  $mig = migration();
  $mig->alterTable('t')
      ->addIndex('c1', 'c2');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD INDEX `IDX_c1__c2`(`c1`,`c2`);'], $this->tablesStatements(), 'multi columns');
  });
});

test('ADD INDEX UNIQUE', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addUniqueIndex('c');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD UNIQUE INDEX `UNQ_c`(`c`);'], $this->tablesStatements(), 'single column');
  });

  $mig = migration();
  $mig->alterTable('t')
      ->addUniqueIndex('c1', 'c2');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD UNIQUE INDEX `UNQ_c1__c2`(`c1`,`c2`);'], $this->tablesStatements(), 'multi columns');
  });
});

test('ADD INDEX FULLTEXT', function (): void {
  $mig = migration();
  $mig->alterTable('t', engine: Engine::MyISAM)
      ->addFulltextIndex('c');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD FULLTEXT INDEX `FLT_c`(`c`) ENGINE MyISAM;'], $this->tablesStatements(), 'single column');
  });

  Assert::exception(fn() => migration()->alterTable('t')->addFulltextIndex('c'), CannotUse::class);
});

test('ADD PRIMARY KEY', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addPrimaryKey('c');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD PRIMARY KEY (`c`);'], $this->tablesStatements(), 'single column');
  });

  $mig = migration();
  $mig->alterTable('t')
      ->addPrimaryKey('c1', 'c2');
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD PRIMARY KEY (`c1`,`c2`);'], $this->tablesStatements(), 'multi columns');
  });

  Assert::exception(fn() => migration()->createTable('t')->addPrimaryKey('c')->addPrimaryKey('c'), DeGiuliettaException::class);
});


test('ADD FOREIGN KEY', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addForeignKey('c', 'rt');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD FOREIGN KEY `FK_t_c`(`c`) REFERENCES `rt`(`id`) ON UPDATE RESTRICT ON DELETE RESTRICT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->addForeignKey('c', 'rt', 'rc');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD FOREIGN KEY `FK_t_c`(`c`) REFERENCES `rt`(`rc`) ON UPDATE RESTRICT ON DELETE RESTRICT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->addForeignKey('c', 'rt', onUpdate: ForeignKeyRestriction::Cascade, onDelete: ForeignKeyRestriction::SetNull);
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD FOREIGN KEY `FK_t_c`(`c`) REFERENCES `rt`(`id`) ON UPDATE CASCADE ON DELETE SET NULL;'], $this->tablesStatements());
  });
});

test('DROP FOREIGN KEY', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->dropForeignKey('fk');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` DROP FOREIGN KEY `fk`;'], $this->tablesStatements());
  });
});