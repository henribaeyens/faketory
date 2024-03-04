<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor;

class Processor extends AbstractProcessor
{
    public function process(): int
    {
        $items = 0;
        if ($this->tableExists()) {
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
        }
        
        return $items;
    }

    private function tableExists(): bool
    {
        return count($this->dbInstance->executeS(sprintf('SHOW TABLES LIKE "%s%s"', $this->dbInstance->getPrefix(), $this->table))) === 1;
    }

    protected function getBatch(int $batchSize, int $batchOffset): array {
        return [];
    }
    protected function anonymize(array $objectIds): void {

    }

}
