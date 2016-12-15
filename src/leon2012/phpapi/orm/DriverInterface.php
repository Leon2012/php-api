<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\orm;

interface DriverInterface
{
    public function open(array $config);
    public function close();
    public function exec($sql);
    public function query($sql, $assoc = false);
    public function one($tableName, array $params = [], array $fields = [], $assoc = false);
    public function all($tableName, array $params = [], array $fields = [], $assoc = false);
    public function getLastInsertId();
    public function getLastError();
    public function getLastSql();
    public function quote($string);

    public function beginTransaction();
    public function commit();
    public function rollBack();
}
