<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor;

class ProcessorFactory
{
    public function create(
        string $table,
    ): ProcessorInterface|null {
        $processorClass = sprintf('PrestaShop\Module\Faketory\Processor\%sProcessor', ucfirst(str_replace('_', '', ucwords($table, '_'))));

        if (class_exists($processorClass)) {
            return new $processorClass($table);
        }
        
        return null;
    }

}