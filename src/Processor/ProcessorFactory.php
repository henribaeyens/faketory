<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor;

use PrestaShop\Module\Faketory\Exception\ProcessorNotFoundException;

class ProcessorFactory
{
    /**
     * Returns a table processor.
     *
     * @throws ProcessorNotFoundException
     */
    public function create(
        string $processor,
        string $table,
        string $primaryKey
    ): ProcessorInterface {
        $processorClass = sprintf('PrestaShop\Module\Faketory\Processor\Table\%sProcessor', ucfirst(str_replace('_', '', ucwords($processor, '_'))));

        if (class_exists($processorClass)) {
            return new $processorClass($table, $primaryKey);
        }
        
        throw new ProcessorNotFoundException(sprintf('Processor "%s" not found.', $processorClass));
    }

}