<?php declare(strict_types=1);

/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

namespace PrestaShop\Module\Faketory\Exception;

use PrestaShopException;

class TableNotFoundException extends PrestaShopException
{
    public function __toString()
    {
        return $this->message;
    }
}
