<?php

use Lawondyss\DeGiulietta\DataType;
use Lawondyss\DeGiulietta\Driver;
use Lawondyss\DeGiulietta\Exception\CannotUse;
use Lawondyss\DeGiulietta\Exception\InvalidArgument;
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

test('MODIFY COLUMN', function (): void {
  $mig = migration();

  $table = $mig->alterTable('t');
  Assert::type(Table::class, $table);

  $table->modifyColumn('c', DataType::int());
  Assert::with($mig, function() {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` INT NOT NULL;'], $this->tablesStatements());
  });
});

test('MODIFY COLUMN NULL', function (): void {
  $mig = migration();

  $mig->alterTable('t')
      ->modifyColumn('c', DataType::int(), null: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` INT NULL;'], $this->tablesStatements());
  });
});

test('MODIFY COLUMN DEFAULT', function (): void {
  $mig = migration();

  $mig->alterTable('t')
      ->modifyColumn('c', DataType::int(), default: 0);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` INT NOT NULL DEFAULT 0;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::tinyText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::text(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::mediumText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::longText(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::tinyBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::blob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::mediumBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::longBlob(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::json(), default: ''), CannotUse::class);
});

test('MODIFY COLUMN AUTO_INCREMENT', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::bit(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` BIT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::tinyInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` TINYINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::smallInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` SMALLINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::mediumInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` MEDIUMINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::int(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` INT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::bigInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` BIGINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::float(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` FLOAT(1) NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::decimal(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` DECIMAL(1, 0) NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::char(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::varChar(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::binary(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::varBinary(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::enum(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::set(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::json(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::tinyText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::text(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::mediumText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::longText(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::tinyBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::blob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::mediumBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::longBlob(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::year(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::date(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::dateTime(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::time(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::timestamp(), autoIncrement: true), CannotUse::class);
});

test('MODIFY COLUMN COMMENT', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::int(), comment: 'foo bar');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` INT NOT NULL COMMENT "foo bar";'], $this->tablesStatements());
  });
});

test('MODIFY COLUMN CHARACTER SET', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::char(), characterSet: 'utf8');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` CHAR NOT NULL CHARACTER SET utf8;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::bool(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::bit(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::tinyInt(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::smallInt(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::mediumInt(''), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::int(''), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::bigInt(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::float(1), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::decimal(1), characterSet: 'utf8'), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::year(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::date(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::dateTime(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::time(), characterSet: 'utf8'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::timestamp(), characterSet: 'utf8'), CannotUse::class);
});

test('MODIFY COLUMN COLLATE', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::char(), collate: 'utf8_bin');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` CHAR NOT NULL COLLATE utf8_bin;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::bool(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::bit(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::tinyInt(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::smallInt(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::mediumInt(''), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::int(''), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::bigInt(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::float(1), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::decimal(1), collate: 'utf8_bin'), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::year(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::date(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::dateTime(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::time(), collate: 'utf8_bin'), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->modifyColumn('c', DataType::timestamp(), collate: 'utf8_bin'), CannotUse::class);
});

test('MODIFY COLUMN FIRST', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::char(), first: true);
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` CHAR NOT NULL FIRST;'], $this->tablesStatements());
  });
});

test('MODIFY COLUMN AFTER', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->modifyColumn('c', DataType::char(), after: 'x');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` MODIFY COLUMN `c` CHAR NOT NULL AFTER `x`;'], $this->tablesStatements());
  });
});
