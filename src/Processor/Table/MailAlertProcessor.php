<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Processor\Table;

use PrestaShop\Module\Faketory\Processor\Processor;

class MailAlertProcessor extends Processor
{
    protected function anonymize(array $objectIds): void
    {
        foreach($objectIds as $objectId) {
            $this->dbInstance->update(
                $this->table,
                [
                    'customer_email' => sprintf('%06d.%s', (int) $objectId[$this->primaryKey], $this->faker->email()),
                ],
                $this->primaryKey . ' = ' . (int) $objectId[$this->primaryKey]
            );
        }
    }
}
