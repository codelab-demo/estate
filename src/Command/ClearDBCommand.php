<?php

namespace App\Command;

use App\Document\Estate;

use App\Repository\MenuRepository;
use App\Repository\ReservationRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearDBCommand extends Command
{
// the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:cleardb';

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;

        parent::__construct();
    }

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $collection = $this->dm->getDocumentCollection(Estate::class);
        $collection->drop();

        return Command::SUCCESS;


    }
}