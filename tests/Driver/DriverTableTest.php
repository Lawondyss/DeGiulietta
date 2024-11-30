<?php

use Lawondyss\DeGiulietta\Driver;
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

test('CREATE TABLE', function (): void {
  $mig = migration();
  $table = $mig->createTable('t');
  Assert::type(Table::class, $table);
  Assert::with($mig, function(): void {
    Assert::same(['CREATE TABLE `t` ();'], $this->tablesStatements());
  });
});

test('ALTER TABLE', function (): void {
  $mig = migration();
  $table = $mig->alterTable('t');
  Assert::type(Table::class, $table);
  Assert::with($mig, function(): void {
    Assert::same(['ALTER TABLE `t` ;'], $this->tablesStatements());
  });
});

test('DROP TABLE', function (): void {
  $mig = migration();
  $mig->dropTable('t');
  Assert::with($mig, function(): void {
    Assert::same(['DROP TABLE `t`;'], $this->tablesStatements());
  });
});

test('TRUNCATE TABLE', function (): void {
  $mig = migration();
  $mig->truncateTable('t');
  Assert::with($mig, function(): void {
    Assert::same(['TRUNCATE TABLE `t`;'], $this->tablesStatements());
  });
});

test('RENAME TABLE', function (): void {
  $mig = migration();
  $mig->renameTable('t1', 't2');
  Assert::with($mig, function(): void {
    Assert::same(['RENAME TABLE `t1` TO `t2`;'], $this->tablesStatements());
  });
});