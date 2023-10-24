<?php

use Lawondyss\DeGiulietta\Allowed;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test('Single permission', function (): void {
  $default = Allowed::new(Allowed::Default);
  Assert::false($default->missing(Allowed::Default), 'Default missing');
  Assert::true($default->have(Allowed::Default), 'Default x Default');
  Assert::false($default->have(Allowed::AutoIncrement), 'Default x AutoIncrement');
  Assert::false($default->have(Allowed::Collation), 'Default x Collation');

  $autoIncrement = Allowed::new(Allowed::AutoIncrement);
  Assert::false($autoIncrement->missing(Allowed::AutoIncrement), 'AutoIncrement missing');
  Assert::false($autoIncrement->have(Allowed::Default), 'AutoIncrement x Default');
  Assert::true($autoIncrement->have(Allowed::AutoIncrement), 'AutoIncrement x AutoIncrement');
  Assert::false($autoIncrement->have(Allowed::Collation), 'AutoIncrement x Collation');

  $collation = Allowed::new(Allowed::Collation);
  Assert::false($collation->missing(Allowed::Collation), 'Collation missing');
  Assert::false($collation->have(Allowed::Default), 'Collation x Default');
  Assert::false($collation->have(Allowed::AutoIncrement), 'Collation x AutoIncrement');
  Assert::true($collation->have(Allowed::Collation), 'Collation x Collation');
});

test('Multiple permissions', function (): void {
  $all = Allowed::new(Allowed::Default, Allowed::AutoIncrement, Allowed::Collation);
  Assert::true($all->have(Allowed::Default), 'Default');
  Assert::true($all->have(Allowed::AutoIncrement), 'AutoIncrement');
  Assert::true($all->have(Allowed::Collation), 'Collation');

  $some = Allowed::new(Allowed::Default, Allowed::Collation);
  Assert::true($some->have(Allowed::Default));
  Assert::true($some->missing(Allowed::AutoIncrement));
  Assert::true($some->have(Allowed::Collation));
});