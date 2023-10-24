<?php

namespace Lawondyss\DeGiulietta;


enum ActionDefineType: string
{
  case Create = 'CREATE';
  case Alter = 'ALTER';
  case Change = 'CHANGE';
  case Modify = 'MODIFY';
}