<?php

namespace Muravian\CiGen\Commands;

use Muravian\CiGen\CiGen;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CiCommand extends BaseCommand
{

    protected $group = 'GenerateModeks';

    protected $name = 'cigen:publish';

    protected $description = 'Publish Models in App\Models';

    protected $usage = 'cigen:publish';

    public function run(array $params) {
        $cigen = new CiGen();
        $cigen->index();
    }

}