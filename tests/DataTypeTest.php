<?php

use Lawondyss\DeGiulietta\Allowed;
use Lawondyss\DeGiulietta\DataType;
use Lawondyss\DeGiulietta\Exception\InvalidArgument;
use Lawondyss\DeGiulietta\Helpers;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test('CHAR', function (): void {
  $dataType = DataType::char();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('CHAR', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::same(1, DataType::char(1)->size, 'size 1');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::char(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::char(256), InvalidArgument::class);
});

test('VARCHAR', function (): void {
  $dataType = DataType::varChar();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('VARCHAR', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::same(1, DataType::varChar(1)->size, 'size 1');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::varChar(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::varChar(65_536), InvalidArgument::class);
});

test('BINARY', function (): void {
  $dataType = DataType::binary();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('BINARY', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::same(1, DataType::binary(1)->size, 'size 1');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::binary(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::binary(256), InvalidArgument::class);
});

test('VARBINARY', function (): void {
  $dataType = DataType::varBinary();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('VARBINARY', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::same(1, DataType::varBinary(1)->size, 'size 1');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::varBinary(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::varBinary(65_536), InvalidArgument::class);
});

test('TINYTEXT', function (): void {
  $dataType = DataType::tinyText();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('TINYTEXT', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('TEXT', function (): void {
  $dataType = DataType::text();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('TEXT', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('MEDIUMTEXT', function (): void {
  $dataType = DataType::mediumText();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('MEDIUMTEXT', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('LONGTEXT', function (): void {
  $dataType = DataType::longText();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('LONGTEXT', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('TINYBLOB', function (): void {
  $dataType = DataType::tinyBlob();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('TINYBLOB', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('BLOB', function (): void {
  $dataType = DataType::blob();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('BLOB', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('MEDIUMBLOB', function (): void {
  $dataType = DataType::mediumBlob();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('MEDIUMBLOB', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::null($dataType->values, 'values');
});

test('LONGBLOB', function (): void {
  $dataType = DataType::longBlob();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('LONGBLOB', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('ENUM', function (): void {
  $dataType = DataType::enum('foo');
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('ENUM', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::equal(['foo'], $dataType->values, 'values');
  Assert::equal(['foo', 'bar', 'baz'], DataType::enum('foo', 'bar', 'baz')->values, 'values +');
});

test('SET', function (): void {
  $dataType = DataType::set('foo');
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('SET', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::equal(['foo'], $dataType->values, 'values');
  Assert::equal(['foo', 'bar', 'baz'], DataType::set('foo', 'bar', 'baz')->values, 'values');
});

test('JSON', function (): void {
  $dataType = DataType::json();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('JSON', $dataType->name, 'name');
  Assert::null($dataType->defaultType, 'defaultType');
  Assert::false($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('BOOL', function (): void {
  $dataType = DataType::bool();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('BOOL', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_BOOL, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('BIT', function (): void {
  $dataType = DataType::bit();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('BIT', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_INT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::same(1, DataType::bit(1)->size, 'size 1');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::bit(0), InvalidArgument::class);
  Assert::exception(fn() => DataType::bit(65), InvalidArgument::class);
});

test('TINYINT', function (): void {
  $dataType = DataType::tinyInt();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('TINYINT', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_INT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::true(DataType::tinyInt(unsigned: true)->unsigned, 'unsigned true');
  Assert::null($dataType->values, 'values');
});

test('SMALLINT', function (): void {
  $dataType = DataType::smallInt();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('SMALLINT', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_INT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::true(DataType::smallInt(unsigned: true)->unsigned, 'unsigned true');
  Assert::null($dataType->values, 'values');
});

test('MEDIUMINT', function (): void {
  $dataType = DataType::mediumInt();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('MEDIUMINT', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_INT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::true(DataType::mediumInt(unsigned: true)->unsigned, 'unsigned true');
  Assert::null($dataType->values, 'values');
});

test('INT', function (): void {
  $dataType = DataType::int();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('INT', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_INT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::true(DataType::int(unsigned: true)->unsigned, 'unsigned true');
  Assert::null($dataType->values, 'values');
});

test('BIGINT', function (): void {
  $dataType = DataType::bigInt();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('BIGINT', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_INT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::true(DataType::bigInt(unsigned: true)->unsigned, 'unsigned true');
  Assert::null($dataType->values, 'values');
});

test('FLOAT', function (): void {
  $dataType = DataType::float(1);
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('FLOAT', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_FLOAT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::same(1, DataType::float(1)->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::float(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::float(54), InvalidArgument::class);
});

test('DECIMAL', function (): void {
  $dataType = DataType::decimal(1);
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('DECIMAL', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_FLOAT, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::true($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::same(1, $dataType->precision, 'precision');
  Assert::same(0, $dataType->scale, 'scale 0');
  Assert::same(1, DataType::decimal(1, 1)->scale, 'scale 1');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::decimal(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::decimal(1, -1), InvalidArgument::class);
  Assert::exception(fn() => DataType::decimal(1, 2), InvalidArgument::class);
});

test('YEAR', function (): void {
  $dataType = DataType::year();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('YEAR', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('DATE', function (): void {
  $dataType = DataType::date();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('DATE', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');
});

test('DATETIME', function (): void {
  $dataType = DataType::dateTime();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('DATETIME', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::same(1, DataType::dateTime(1)->fsp, 'fractionalSecondsPrecision 1');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::dateTime(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::dateTime(7), InvalidArgument::class);
});

test('TIME', function (): void {
  $dataType = DataType::time();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('TIME', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::same(1, DataType::time(1)->fsp, 'fractionalSecondsPrecision 1');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::time(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::time(7), InvalidArgument::class);
});

test('TIMESTAMP', function (): void {
  $dataType = DataType::timestamp();
  Assert::type(DataType::class, $dataType, 'type');
  Assert::same('TIMESTAMP', $dataType->name, 'name');
  Assert::same(Helpers::VAR_TYPE_STRING, $dataType->defaultType, 'defaultType');
  Assert::true($dataType->allowed->have(Allowed::Default), 'default');
  Assert::false($dataType->allowed->have(Allowed::AutoIncrement), 'autoIncrement');
  Assert::null($dataType->size, 'size');
  Assert::null($dataType->precision, 'precision');
  Assert::null($dataType->scale, 'scale');
  Assert::null($dataType->fsp, 'fractionalSecondsPrecision');
  Assert::same(1, DataType::timestamp(1)->fsp, 'fractionalSecondsPrecision 1');
  Assert::false($dataType->unsigned, 'unsigned');
  Assert::null($dataType->values, 'values');

  Assert::exception(fn() => DataType::timestamp(-1), InvalidArgument::class);
  Assert::exception(fn() => DataType::timestamp(7), InvalidArgument::class);
});

test('', function (): void {});