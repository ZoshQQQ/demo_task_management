<?php

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Task;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class TaskPersistProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security
    ) {
    }

    /**
     * @param Task $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Task
    {
        if ($operation instanceof Post) {
            // add user
            $data->user = $this->security->getUser();
        }

        // save entity
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
