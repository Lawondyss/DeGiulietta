<?php

namespace Lawondyss\DeGiulietta\CLI\Command;

use Lawondyss\DeGiulietta\CLI\Migrations;
use Lawondyss\DeGiulietta\CLI\Printer;
use PDO;
use function is_dir;
use function mkdir;

final readonly class Initialize
{
  public function __construct()
  {
    Printer::subheader('Initialize');

    $directory = $this->readInput(' Directory of migrations: ');
    if (!is_dir($directory) && mkdir($directory, recursive: true) === false) {
      Printer::error("Can't create directory '$directory'.");
    }

    $table = $this->readInput(' Table name of migrations: ');
    $dsn = $this->readInput(' Database DSN: ');
    $user = $this->readInput(' Database username: ');
    $pass = $this->readInput(' Database password: ');
    new Migrations(dir($directory), new PDO($dsn, $user, $pass), $table)->createTableIfNotExists();

    file_put_contents(
      'degita.config.php', <<<CODE
      <?php\n\nfinal readonly class Config\n{
      \tpublic function __construct(
      \t\tpublic string \$directory = __DIR__ . '/$directory',
      \t\tpublic string \$table = '$table',
      \t\tpublic string \$dsn = '$dsn',
      \t\tpublic string \$user = '$user',
      \t\tpublic string \$pass = '$pass',
      \t) {}\n}\n
      return new Config;
      CODE
    );

    Printer::writeLn(' Created migrations directory, DB table and config file.');
  }


  private function readInput(string $prompt = ''): string
  {
    echo $prompt;

    return trim(fgets(STDIN));
  }
}