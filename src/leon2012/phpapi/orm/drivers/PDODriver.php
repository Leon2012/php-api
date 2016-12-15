<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi\orm\drivers;
use leon2012\phpapi\orm\DriverInterface;
use leon2012\phpapi\orm\exceptions\ConnectionException;

class PDODriver extends Base implements DriverInterface
{

    public function __construct()
    {
        parent::__construct();
    }

    public function open(array $config)
    {
        if (null == $this->_connection) {
            try {
                if (isset($config['charset'])) {
                    $params = [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$config['charset']];
                } else {
                    $params = [];
                }
                $dsn = sprintf("%s:dbname=%s;host=%s;port=%s", $config['type'], $config['name'], $config['host'], $config['port']);
                $this->_connection = new \PDO($dsn, $config['username'], $config['password'], $params);
                $this->_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                throw new ConnectionException($e->getMessage());
            }
        }

        return $this;
    }

    public function close()
    {
        $this->_connection = null;
    }

    public function exec($sql)
    {
        $this->setLastSql($sql);

        return $this->_connection->exec($sql);
    }

    public function query($sql, $assoc = false)
    {
        $this->setLastSql($sql);
        if ($assoc) {
            $rs = $this->_connection->query($sql);
        } else {

        }
    }

    public function one($tableName, array $params = [], array $fields = [], $assoc = false)
    {
        $sth = $this->buildStatement($tableName, $params, $fields);
        if ($assoc) {
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        } else {
            $result = $sth->fetch(\PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function all($tableName, array $params = [], array $fields = [], $assoc = false)
    {
        $sth = $this->buildStatement($tableName, $params, $fields);
        if ($assoc) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = $sth->fetchAll(\PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function getLastInsertId()
    {
        return $this->_connection->lastInsertId();
    }

    public function getLastError()
    {
        $errorInfo = $this->_connection->errorInfo();
        if ($errorInfo) {
           return [
                'code' => $errorInfo[1],
                'message' => $errorInfo[2]
            ];
        }

        return null;
    }

    public function quote($string)
    {
        return $this->_connection->quote($string);
    }

    public function beginTransaction()
    {
        return $this->_connection->beginTransaction();
    }

    public function commit()
    {
        return $this->_connection->commit();
    }

    public function rollBack()
    {
        return $this->_connection->rollBack();
    }

    private function buildStatement($tableName, array $params = [], array $fields = [])
    {
        $sql = '';
        $sql .= ' SELECT ';
        if (!empty($fields)) {
            $sq .= implode(",", $fields);
        } else {
            $sql .= " * ";
        }
        $sql .= ' FROM '.$tableName . ' WHERE ';
        $values = [];
        if (!empty($params)) {
            $keys = [];
            foreach ($params as $k => $v) {
                $kk = ':'.$k;
                $keys[] = $k.' = '.$kk;
                $values[$kk] = $v;
            }
            $where = implode(" AND ", $keys);
            $sql .= $where;
        } else {
            $sql .= ' 1=1 ';
        }
        $this->setLastSql($sql);
        $sth = $this->_connection->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $sth->execute($values);

        return $sth;
    }
}
