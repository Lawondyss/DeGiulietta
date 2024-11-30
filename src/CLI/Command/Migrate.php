<?php

namespace Lawondyss\DeGiulietta\CLI\Command;

use Lawondyss\DeGiulietta\CLI\CliParams;
use Lawondyss\DeGiulietta\CLI\Helper;
use Lawondyss\DeGiulietta\CLI\Migration;
use Lawondyss\DeGiulietta\CLI\Migrations;
use Lawondyss\DeGiulietta\CLI\Printer;
use Lawondyss\DeGiulietta\CLI\State;
use function array_filter;

final readonly class Migrate
{
  public function __construct(
    private CliParams $cliParams,
    private Migrations $migrations,
  ) {
    Printer::subheader('Migrate');

    if ($this->cliParams->containsHelp()) {
      Printer::helpMigrate();
      Helper::exitSuccess();
    }

    if (array_filter($this->migrations->list(), static fn(Migration $m) => $m->state === State::Runs) !== []) {
      Printer::error('Migration seems to run in a different process. If not, use the clean command.');
    }

    $name = $this->cliParams->valueOf('n');
    $id = $this->cliParams->valueOf('id');

    foreach ($this->migrations->list() as $migration) {
      if ($migration->state !== State::Wait) {
        continue;
      }

      $this->migrations->apply($migration);

      if ($name === $migration->name || $id === $migration->id) {
        break;
      }
    }
  }
}