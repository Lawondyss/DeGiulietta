<?php

namespace Lawondyss\DeGiulietta;

enum Engine: string
{
  case InnoDB = 'InnoDB';
  case MyISAM = 'MyISAM';
  case Memory = 'MEMORY';
  case CSV = 'CSV';
  case Archive = 'ARCHIVE';
}