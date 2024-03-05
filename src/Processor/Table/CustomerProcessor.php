<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

 namespace PrestaShop\Module\Faketory\Processor\Table;

use DbQuery;
use PrestaShop\Module\Faketory\Processor\Processor;

class CustomerProcessor extends Processor
{
    protected function getBatch(int $batchSize, int $batchOffset): array
    {
        $query = new DbQuery();
        $query->select($this->primaryKey);
        $query->from($this->table);
        if ($this->anonymousCustomerId) {
            $query->where('id_customer <> ' . $this->anonymousCustomerId);
        }
        $query->limit($batchSize, $batchSize * ($batchOffset - 1));
        $query->orderBy($this->primaryKey . ' ASC');

        return $this->dbInstance->executeS($query);
    }

    protected function anonymize(array $objectIds): void
    {
        foreach($objectIds as $objectId) {
            $this->dbInstance->update(
                $this->table,
                [
                    'email' => sprintf('%06d.%s', (int) $objectId[$this->primaryKey], $this->faker->email()),
                    'firstname' => $this->faker->firstName(),
                    'lastname' => $this->faker->lastName(),
                ],
                $this->primaryKey . ' = ' . (int) $objectId[$this->primaryKey]
            );
        }
    }
}
