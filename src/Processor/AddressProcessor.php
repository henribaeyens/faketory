<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor;

use DbQuery;

class AddressProcessor extends Processor
{
    protected function getBatch(int $batchSize, int $batchOffset): array
    {
        $query = new DbQuery();
        $query->select('id_address');
        $query->from($this->table);
        $query->where('id_customer <> 0');
        if ($this->anonymousCustomerId) {
            $query->where('id_customer <> ' . $this->anonymousCustomerId);
        }
        $query->limit($batchSize, $batchSize * ($batchOffset - 1));
        $query->orderBy('id_address ASC');

        return $this->dbInstance->executeS($query);
    }

    protected function anonymize(array $objectIds): void
    {
        foreach($objectIds as $objectId) {
            $this->dbInstance->update(
                $this->table,
                [
                    'firstname' => $this->faker->firstName(),
                    'lastname' => $this->faker->lastName(),
                    'address1' => $this->faker->streetAddress(),
                    'postcode' => $this->faker->postcode(),
                    'city' => $this->faker->city(),
                    'id_country' => 8,
                    'phone' => $this->faker->phoneNumber(),
                ],
                'id_address = ' . (int) $objectId['id_address']
            );
        }
    }
}
