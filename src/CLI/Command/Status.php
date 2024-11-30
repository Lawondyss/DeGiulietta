<?php

namespace Lawondyss\DeGiulietta\CLI\Command;

use Lawondyss\DeGiulietta\CLI\CliParams;
use Lawondyss\DeGiulietta\CLI\Colors;
use Lawondyss\DeGiulietta\CLI\Helper;
use Lawondyss\DeGiulietta\CLI\Migrations;
use Lawondyss\DeGiulietta\CLI\Printer;
use function sprintf;

final readonly class Status
{
  public function __construct(
    private CliParams $cliParams,
    private Migrations $migrations,
  ) {
    Printer::subheader('Status');

    if ($this->cliParams->containsHelp()) {
      Printer::help();
      Helper::exitSuccess();
    }

    $I = Colors::grey('|');

    Printer::writeLn('   ID         Name                                                Start migration       Duration');
    Printer::writeLn(Colors::grey(' -----------|---------------------------------------------------|---------------------|-----------'));
    foreach ($this->migrations->list() as $migration) {
      Printer::writeLn(sprintf(
        ' %s %8s ' . $I . ' %-50s' . $I . ' %19s ' . $I . ($migration->duration ? ' %6.d ms' : '%s'),
        $migration->state->value, $migration->id, $migration->name, $migration->start ?? '', $migration->duration ?? ''
      ));
    }
    Printer::writeLn(Colors::grey(' -----------|---------------------------------------------------|---------------------|-----------'));
  }
}