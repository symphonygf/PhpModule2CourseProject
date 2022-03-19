<?php

use App\Commands\CreateCommand;
use App\Enums\Argument;
use App\Exceptions\NotFoundException;
use App\Factories\EntityManagerFactory;
use App\Factories\EntityManagerFactoryInterface;

try {
    if(count($argv) < 2)
    {
        throw new NotFoundException('404');
    }

    if(!in_array($argv[1], Argument::getArgumentValues()))
    {
        throw new NotFoundException('404');
    }
    /**
     * @var EntityManagerFactoryInterface $entityMangerFactory
     */
    $entityMangerFactory = EntityManagerFactory::getInstance();

    $command = new CreateCommand($entityMangerFactory->getEntityManager());
    $command->handle($entityMangerFactory->createEntityByInputArguments($argv));

}catch (Exception $exception)
{
    echo $exception->getMessage().PHP_EOL;
    http_response_code(404);
}



