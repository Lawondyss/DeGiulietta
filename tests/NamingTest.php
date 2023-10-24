<?php

use Lawondyss\DeGiulietta\Naming;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test('Common index', function (): void {
  Assert::same('IDX_c', Naming::commonIndex(['c']), 'single column');
  Assert::same('IDX_c1__c2', Naming::commonIndex(['c1', 'c2']), 'multi columns');
});

test('Unique index', function (): void {
  Assert::same('UNQ_c', Naming::uniqueIndex(['c']), 'single column');
  Assert::same('UNQ_c1__c2', Naming::uniqueIndex(['c1', 'c2']), 'multi columns');
});

test('Fulltext index', function (): void {
  Assert::same('FLT_c', Naming::fulltextIndex('c'));
});

test('Foreign key', function (): void {
  Assert::same('FK_t_c', Naming::foreignKey('t', 'c'));
});