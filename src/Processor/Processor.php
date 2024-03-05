<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor;

use DbQuery;
use PrestaShop\Module\Faketory\Exception\EmptyTableException;
use PrestaShop\Module\Faketory\Exception\TableNotFoundException;

class Processor extends AbstractProcessor
{
    /**
     * Process a table.
     *
     * @throws EmptyTableException
     * @throws TableNotFoundException
     */
    public function process(): int
    {
        if ($this->tableExists()) {
            $items = 0;
            $done = false;
            $batchSize = 500;
            $batchOffset = 1;
            while (!$done) {
                $objectIds = $this->getBatch($batchSize, $batchOffset);
                $this->anonymize($objectIds);
                $batchOffset++;
                if (count($objectIds) < $batchSize) {
                    $done = true;
                }
                $items += count($objectIds);
            }
            if (0 == $items) {
                throw new EmptyTableException(sprintf('Table "%s" is empty.', $this->table));
            }
            return $items;
        } else {
            throw new TableNotFoundException(sprintf('Unable to process non-existant table "%s".', $this->table));
        }
    }

    protected function getBatch(int $batchSize, int $batchOffset): array
    {
        // see overriden implementations in the Processor\Table namespace
        
        $query = new DbQuery();
        $query->select($this->primaryKey);
        $query->from($this->table);
        $query->limit($batchSize, $batchSize * ($batchOffset - 1));
        $query->orderBy($this->primaryKey . ' ASC');

        return $this->dbInstance->executeS($query);
    }

    protected function anonymize(array $objectIds): void
    {
        // see implementations in the Processor\Table namespace
    }

    private function tableExists(): bool
    {
        return count($this->dbInstance->executeS(sprintf('SHOW TABLES LIKE "%s%s"', $this->dbInstance->getPrefix(), $this->table))) === 1;
    }
}
