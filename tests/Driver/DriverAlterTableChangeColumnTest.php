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

test('ADD COLUMN', function (): void {
  $mig = migration();

  $table = $mig->alterTable('t');
  Assert::type(Table::class, $table);

  $table->addColumn('c', DataType::int());
  Assert::with($mig, function() {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` INT NOT NULL;'], $this->tablesStatements());
  });
});

test('ADD COLUMN NULL', function (): void {
  $mig = migration();

  $mig->alterTable('t')
      ->addColumn('c', DataType::int(), null: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` INT NULL;'], $this->tablesStatements());
  });
});

test('ADD COLUMN DEFAULT', function (): void {
  $mig = migration();

  $mig->alterTable('t')
      ->addColumn('c', DataType::int(), default: 0);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` INT NOT NULL DEFAULT 0;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::tinyText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::text(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::mediumText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::longText(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::tinyBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::blob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::mediumBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::longBlob(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::json(), default: ''), CannotUse::class);
});

test('ADD COLUMN AUTO_INCREMENT', function (): void {
  $mig = migration();
  $mig->alterTable('t_bit')
      ->addColumn('c', DataType::bit(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_bit` ADD COLUMN `c` BIT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_tint')
      ->addColumn('c', DataType::tinyInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_tint` ADD COLUMN `c` TINYINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_sint')
      ->addColumn('c', DataType::smallInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_sint` ADD COLUMN `c` SMALLINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_mint')
      ->addColumn('c', DataType::mediumInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_mint` ADD COLUMN `c` MEDIUMINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_int')
      ->addColumn('c', DataType::int(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_int` ADD COLUMN `c` INT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_bint')
      ->addColumn('c', DataType::bigInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_bint` ADD COLUMN `c` BIGINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_float')
      ->addColumn('c', DataType::float(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_float` ADD COLUMN `c` FLOAT(1) NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_dec')
      ->addColumn('c', DataType::decimal(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_dec` ADD COLUMN `c` DECIMAL(1, 0) NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::char(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::varChar(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::binary(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::varBinary(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::enum(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::set(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::json(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::tinyText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::text(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::mediumText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::longText(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::tinyBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::blob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::mediumBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::longBlob(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::year(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::date(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::dateTime(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::time(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->addColumn('c', DataType::timestamp(), autoIncrement: true), CannotUse::class);
});

test('ADD COLUMN COMMENT', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addColumn('c', DataType::int(), comment: 'foo bar');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` INT NOT NULL COMMENT "foo bar";'], $this->tablesStatements());
  });
});

test('ADD COLUMN CHARACTER SET', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addColumn('c', DataType::char(), characterSet: 'utf8');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` CHAR NOT NULL CHARACTER SET utf8;'], $this->tablesStatements());
  });
});

test('ADD COLUMN COLLATE', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addColumn('c', DataType::char(), collate: 'utf8_bin');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` CHAR NOT NULL COLLATE utf8_bin;'], $this->tablesStatements());
  });
});

test('ADD COLUMN FIRST', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addColumn('c', DataType::char(), first: true);
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` CHAR NOT NULL FIRST;'], $this->tablesStatements());
  });
});

test('ADD COLUMN AFTER', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->addColumn('c', DataType::char(), after: 'x');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` ADD COLUMN `c` CHAR NOT NULL AFTER `x`;'], $this->tablesStatements());
  });
});

test('CHANGE COLUMN', function (): void {
  $mig = migration();

  $table = $mig->alterTable('t');
  Assert::type(Table::class, $table);

  $table->changeColumn('c1', 'c2', DataType::int());
  Assert::with($mig, function() {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` INT NOT NULL;'], $this->tablesStatements());
  });
});

test('CHANGE COLUMN NULL', function (): void {
  $mig = migration();

  $mig->alterTable('t')
      ->changeColumn('c1', 'c2', DataType::int(), null: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` INT NULL;'], $this->tablesStatements());
  });
});

test('CHANGE COLUMN DEFAULT', function (): void {
  $mig = migration();

  $mig->alterTable('t')
      ->changeColumn('c1', 'c2', DataType::int(), default: 0);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` INT NOT NULL DEFAULT 0;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::tinyText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::text(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::mediumText(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('e1', 'e2', DataType::longText(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::tinyBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::blob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::mediumBlob(), default: ''), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::longBlob(), default: ''), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::json(), default: ''), CannotUse::class);
});

test('CHANGE COLUMN AUTO_INCREMENT', function (): void {
  $mig = migration();
  $mig->alterTable('t_bit')
      ->changeColumn('c1', 'c2', DataType::bit(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_bit` CHANGE COLUMN `c1` `c2` BIT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_tint')
      ->changeColumn('c1', 'c2', DataType::tinyInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_tint` CHANGE COLUMN `c1` `c2` TINYINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_sint')
      ->changeColumn('c1', 'c2', DataType::smallInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_sint` CHANGE COLUMN `c1` `c2` SMALLINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_mint')
      ->changeColumn('c1', 'c2', DataType::mediumInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_mint` CHANGE COLUMN `c1` `c2` MEDIUMINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_int')
      ->changeColumn('c1', 'c2', DataType::int(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_int` CHANGE COLUMN `c1` `c2` INT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_bint')
      ->changeColumn('c1', 'c2', DataType::bigInt(), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_bint` CHANGE COLUMN `c1` `c2` BIGINT NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_float')
      ->changeColumn('c1', 'c2', DataType::float(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_float` CHANGE COLUMN `c1` `c2` FLOAT(1) NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  $mig = migration();
  $mig->alterTable('t_dec')
      ->changeColumn('c1', 'c2', DataType::decimal(1), autoIncrement: true);
  Assert::with($mig, function () {
    Assert::same(['ALTER TABLE `t_dec` CHANGE COLUMN `c1` `c2` DECIMAL(1, 0) NOT NULL AUTO_INCREMENT;'], $this->tablesStatements());
  });

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::char(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::varChar(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::binary(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::varBinary(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::enum(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::set(''), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::json(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::tinyText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::text(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::mediumText(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::longText(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::tinyBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::blob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::mediumBlob(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::longBlob(), autoIncrement: true), CannotUse::class);

  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::year(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::date(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::dateTime(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::time(), autoIncrement: true), CannotUse::class);
  Assert::exception(fn() => migration()->alterTable('t')->changeColumn('c1', 'c2', DataType::timestamp(), autoIncrement: true), CannotUse::class);
});

test('CHANGE COLUMN COMMENT', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->changeColumn('c1', 'c2', DataType::int(), comment: 'foo bar');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` INT NOT NULL COMMENT "foo bar";'], $this->tablesStatements());
  });
});

test('CHANGE COLUMN CHARACTER SET', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->changeColumn('c1', 'c2', DataType::char(), characterSet: 'utf8');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` CHAR NOT NULL CHARACTER SET utf8;'], $this->tablesStatements());
  });
});

test('CHANGE COLUMN COLLATE', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->changeColumn('c1', 'c2', DataType::char(), collate: 'utf8_bin');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` CHAR NOT NULL COLLATE utf8_bin;'], $this->tablesStatements());
  });
});

test('CHANGE COLUMN FIRST', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->changeColumn('c1', 'c2', DataType::char(), first: true);
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` CHAR NOT NULL FIRST;'], $this->tablesStatements());
  });
});

test('CHANGE COLUMN AFTER', function (): void {
  $mig = migration();
  $mig->alterTable('t')
      ->changeColumn('c1', 'c2', DataType::char(), after: 'x');
  Assert::with($mig, function (): void {
    Assert::same(['ALTER TABLE `t` CHANGE COLUMN `c1` `c2` CHAR NOT NULL AFTER `x`;'], $this->tablesStatements());
  });
});