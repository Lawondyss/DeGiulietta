<?php

require __DIR__ . '/../vendor/autoload.php';

const TempPath = __DIR__ . '/temp';

Tester\Environment::setup();
Tester\Environment::setupFunctions();

if (!is_dir(TempPath)) {
  mkdir(TempPath);
}
