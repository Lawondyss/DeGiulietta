<?php

namespace Lawondyss\DeGiulietta\CLI\Command;

use Lawondyss\DeGiulietta\CLI\CliParams;
use Lawondyss\DeGiulietta\CLI\Helper;
use Lawondyss\DeGiulietta\CLI\Migrations;
use Lawondyss\DeGiulietta\CLI\Printer;

final readonly class Clean
{
  public function __construct(
    private CliParams $cliParams,
    private Migrations $migrations,
  ) {
    Printer::subheader('Clean');

    if ($this->cliParams->containsHelp()) {
      Printer::helpClean();
      Helper::exitSuccess();
    }

    $this->migrations->clean();
  }
}