<?php

namespace Lawondyss\DeGiulietta\CLI;

enum State: string
{
  case Done = Colors::Green . '✓' . Colors::Reset;
  case Runs = Colors::Cyan . '➤' . Colors::Reset;
  case Miss = Colors::Red . '!' . Colors::Reset;
  case Wait = Colors::Yellow . '✗' . Colors::Reset;
}
