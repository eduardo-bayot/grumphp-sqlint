<?php

declare(strict_types=1);

namespace Edubayot\GrumPHP\Task;

use GrumPHP\Collection\ProcessArgumentsCollection;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use GrumPHP\Task\AbstractExternalTask;
use RuntimeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use GrumPHP\Runner\TaskResultInterface;

/**
 * PhpcsDiff task
 *
 */
class Sqlint extends AbstractExternalTask
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'sqlint';
    }

    /**
     * @return OptionsResolver
     */
    public function getConfigurableOptions(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'branch' => 'master'
        ]);

        $resolver->addAllowedTypes('branch', ['string']);

        return $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function canRunInContext(ContextInterface $context): bool
    {
        return ($context instanceof GitPreCommitContext || $context instanceof RunContext);
    }

    /**
     * {@inheritdoc}
     */
    public function run(ContextInterface $context): TaskResultInterface
    {
        /** @var array $config */
        $config = $this->getConfiguration();
        /** @var array $whitelistPatterns */

        $arguments = $this->processBuilder->createArgumentsForCommand('php-sqlint-wrapper-diff');
        $arguments->add($config['branch']);

        $process = $this->processBuilder->buildProcess($arguments);
        $process->run();

        if (!$process->isSuccessful()) {
            $output = $this->formatter->format($process);
            return TaskResult::createFailed($this, $context, $output);
        }

        return TaskResult::createPassed($this, $context);
    }
}
