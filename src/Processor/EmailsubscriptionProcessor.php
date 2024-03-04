<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor;

use DbQuery;

class EmailsubscriptionProcessor extends Processor
{
    protected function getBatch(int $batchSize, int $batchOffset): array
    {
        $query = new DbQuery();
        $query->select('id');
        $query->from($this->table);
        $query->limit($batchSize, $batchSize * ($batchOffset - 1));
        $query->orderBy('id ASC');

        return $this->dbInstance->executeS($query);
    }

    protected function anonymize(array $objectIds): void
    {
        foreach($objectIds as $objectId) {
            $this->dbInstance->update(
                $this->table,
                [
                    'email' => sprintf('%06d.%s', (int) $objectId['id'], $this->faker->email()),
                ],
                'id = ' . (int) $objectId['id']
            );
        }
    }
}
