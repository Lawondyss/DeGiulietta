<?php

namespace Lawondyss\DeGiulietta\CLI\Command;

use Lawondyss\DeGiulietta\CLI\CliParams;
use Lawondyss\DeGiulietta\CLI\Colors;
use Lawondyss\DeGiulietta\CLI\Helper;
use Lawondyss\DeGiulietta\CLI\Migrations;
use Lawondyss\DeGiulietta\CLI\Printer;
use function preg_match;

final readonly class Create
{
  public function __construct(
    private CliParams $cliParams,
    private Migrations $migrations,
  ) {
    Printer::subheader('Create');

    if ($this->cliParams->containsHelp()) {
      Printer::helpCreate();
      Helper::exitSuccess();
    }

    $name = $this->cliParams->next();

    if ($name === null) {
      Printer::error('Missing required argument for create migration!', Printer::helpCreate(...));
    }

    if (preg_match('~^[A-Z][a-zA-Z0-9]+$~', $name) === false) {
      Printer::error("Argument has invalid format! Should be CamelCase, given '{$name}'.", Printer::helpCreate(...));
    }

    $filepath = $this->migrations->create(Helper::createId(), $name);
    Printer::writeLn(' Create migration file:', Colors::cyan($filepath));
  }
}