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
    public function one($sql, array $params = array(), $assoc = false);
    public function all($sql, array $params = array(), $assoc = false);
    public function getLastInsertId();
    public function getLastError();
    public function getLastSql();

    public function beginTransaction();
    public function commit();
    public function rollBack();
}
