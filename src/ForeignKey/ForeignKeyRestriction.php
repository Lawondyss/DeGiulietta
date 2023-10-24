<?php

namespace Lawondyss\DeGiulietta\ForeignKey;

enum ForeignKeyRestriction: string
{
  case Restrict = 'RESTRICT';
  case NoAction = 'NO ACTION';
  case Cascade = 'CASCADE';
  case SetNull = 'SET NULL';
  case SetDefault = 'SET DEFAULT';
}
