<?php

namespace Lawondyss\DeGiulietta\CLI;

use Directory;
use Lawondyss\DeGiulietta\Driver;
use PDO;
use function basename;
use function explode;
use function file_put_contents;

final readonly class Migrations
{
  private string $table;


  public function __construct(
    private Directory $directory,
    private PDO $pdo,
    string $table,
  ) {
    $this->table = "`{$table}`";
  }


  public function createTableIfNotExists(): void
  {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS {$this->table} (
      `id` CHAR(8) NOT NULL PRIMARY KEY,
      `name` VARCHAR(100) NOT NULL,
      `start` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `duration` SMALLINT unsigned NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
  }


  /**
   * @return Migration[]
   */
  public function list(bool $reverse = false): array
  {
    $migrations = [];

    foreach ($this->files() as $filepath) {
      $class = basename($filepath, '.php');
      [$id, $name] = explode('_', $class, 2);
      $migrations[$id] = new Migration(State::Wait, $id, $name, $filepath);
    }

    $result = $this->pdo->query("SELECT * FROM {$this->table}");

    foreach ($result as $row) {
      $id = $row['id'];

      if (!isset($migrations[$id])) {
        $migrations[$id] = new Migration(State::Miss, ...$row);
        continue;
      }

      $migration = $migrations[$id];
      $migration->start = $row['start'];
      $migration->duration = $row['duration'];
      $migration->state = isset($row['duration']) ? State::Done : State::Runs;
    }

    $reverse
      ? krsort($migrations)
      : ksort($migrations);

    return array_values($migrations);
  }


  public function create(string $id, string $name): string
  {
    $filepath = $this->directory->path . '/' . $id . '_' . $name . '.php';
    file_put_contents(
      $filepath, <<<CODE
      <?php\n
      use Lawondyss\DeGiulietta\Migration;\n
      final class {$id}_{$name} extends Migration
      {\n\tpublic function up(): void {}
      \n\tpublic function down(): void {}\n}
      CODE
    );

    return $filepath;
  }


  public function apply(Migration $migration): void
  {
    Helper::stopwatch($migration->id);
    Printer::write(' ', State::Runs->value, "[{$migration->id}]", "{$migration->name}...");
    require $migration->filepath;
    $this->start($migration->id, $migration->name);

    $mig = new ($migration->class)(new Driver);
    $mig->up();
    $mig->migrate($this->pdo);

    $duration = Helper::stopwatch($migration->id);
    $this->end($migration->id, $duration);
    Printer::writeLn("\r ", State::Done->value, "[{$migration->id}]", $migration->name, 'done in', Colors::yellow("{$duration} ms"));
  }


  public function revert(Migration $migration): void
  {
    Helper::stopwatch($migration->id);
    Printer::write(' ', State::Done->value, "[{$migration->id}]", "{$migration->name}...");

    require $migration->filepath;

    $mig = new ($migration->class)(new Driver);
    $mig->down();
    $mig->migrate($this->pdo);

    $duration = Helper::stopwatch($migration->id);
    $this->remove($migration->id);

    Printer::writeLn("\r ", State::Wait->value, "[{$migration->id}]", $migration->name, 'reverted in', Colors::yellow("{$duration} ms"));
  }


  public function clean(): void
  {
    $this->pdo->exec("DELETE FROM {$this->table} WHERE `duration` IS NULL");
  }


  private function start(string $id, string $name): void
  {
    $this->pdo->exec("INSERT INTO {$this->table} (`id`, `name`) VALUES ('{$id}', '{$name}')");
  }


  private function end(string $id, int $duration): void
  {
    $this->pdo->exec("UPDATE {$this->table} SET `duration` = {$duration} WHERE `id` = '{$id}'");
  }


  private function remove(string $id): void
  {
    $this->pdo->exec("DELETE FROM {$this->table} WHERE `id` = '{$id}'");
  }


  private function files(?Directory $dir = null): array
  {
    $files = [];
    $dir ??= $this->directory;
    $dir->rewind();

    while ($entry = $dir->read()) {
      $path = $dir->path . '/' . $entry;
      if (is_dir($path) && !str_starts_with($entry, '.')) {
        array_push($files, ...$this->files(dir($path)));
      } elseif (is_file($path) && str_ends_with($entry, '.php')) {
        $files[] = $path;
      }
    }

    return $files;
  }
}
