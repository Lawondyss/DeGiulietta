<?php

use Lawondyss\DeGiulietta\Helpers;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test('isInRange', function (): void {
  Assert::true(Helpers::isInRange(1, 0, 3));
  Assert::false(Helpers::isInRange(5, 0, 3));
});

test('isInRange inclusive', function (): void {
  Assert::true(Helpers::isInRange(0, 0, 3));
  Assert::true(Helpers::isInRange(3, 0, 3));
});

test('getVarType', function (): void {
  Assert::same(Helpers::VAR_TYPE_STRING, Helpers::getVarType(''), 'string');
  Assert::same(Helpers::VAR_TYPE_INT, Helpers::getVarType(0), 'int');
  Assert::same(Helpers::VAR_TYPE_FLOAT, Helpers::getVarType(0.0), 'float');
  Assert::same(Helpers::VAR_TYPE_BOOL, Helpers::getVarType(false), 'bool');
  Assert::same(Helpers::VAR_TYPE_NULL, Helpers::getVarType(null), 'null');
});

test('getVarType not allowed', function (): void {
  Assert::null(Helpers::getVarType([]), 'array');
  Assert::null(Helpers::getVarType(new stdClass), 'class instance');
  Assert::null(Helpers::getVarType(new class extends stdClass {}), 'anonymous class instance');
  $stream = fopen('php://memory', 'r');
  Assert::null(Helpers::getVarType($stream), 'open stream');
  fclose($stream);
  Assert::null(Helpers::getVarType($stream), 'closed stream');
});

test('inArray', function (): void {
  Assert::true(Helpers::inArray(1, [0, 1]));
  Assert::false(Helpers::inArray(2, [0, 1]));
  Assert::true(Helpers::inArray('foo', ['foo', 'bar']));
  Assert::false(Helpers::inArray('baz', ['foo', 'bar']));
});

test('inArray strict', function (): void {
  Assert::false(Helpers::inArray(1, [false, true]));
  Assert::false(Helpers::inArray(1, ['0', '1']));
  Assert::false(Helpers::inArray(true, [0, 1]));
  Assert::false(Helpers::inArray(true, ['0', '1']));
  Assert::false(Helpers::inArray(false, ['', '1']));
  Assert::false(Helpers::inArray('', [0, 1]));
  Assert::false(Helpers::inArray('', [false, true]));
});

test('stringLength', function (): void {
  Assert::same(0, Helpers::stringLength(''));
  Assert::same(3, Helpers::stringLength('foo'));
  Assert::same(3, Helpers::stringLength('FOO'));
  Assert::same(7, Helpers::stringLength('FOO bar'));
});

test('stringLength UTF', function (): void {
  Assert::same(3, Helpers::stringLength('čáp'));
  Assert::same(3, Helpers::stringLength('ČÁp'));
});

test('splitColumns', function (): void {
  Assert::same(['foo', 'bar'], Helpers::splitColumns('`foo`,`bar`'));
  Assert::same(['foo', 'bar'], Helpers::splitColumns('foo,bar'));
  Assert::same(['foo'], Helpers::splitColumns('`foo`'));
});