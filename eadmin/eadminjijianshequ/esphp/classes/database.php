<?php

namespace esclass;

use PDO;
use PDOStatement;
use PDOException;
use RuntimeException;
use InvalidArgumentException;

class database
{

    const VERSION = '1.0.1';
    private static $_instance;
    /**
     * @var PDO
     */
    protected $pdo;
    protected $pk;

    /**
     * @var PDOStatement
     */
    protected $statement;

    /**
     * Database config
     *
     * @var array
     */
    protected $dbConfig = [];

    /**
     * SQL statement options
     *
     * @var array
     */
    protected $options = [];

    /**
     * SQL binding data
     *
     * @var array
     */
    protected $bindData = [];

    protected $model = [];
    /**
     * Fetch the SQL statement?
     *
     * @var boolean
     */
    protected $fetchSql = false;


    public $render = '';
    /**
     * Is escape identifiers?
     *
     * @var boolean
     */
    protected $escape = true;

    /**
     * SQL query logs
     *
     * @var array
     */
    protected $queryLog = [];

    /**
     * Constructor
     * Connect to the database server
     *
     * @param  array $config
     * @throws PDOException
     */
    private function __construct(array $config = [])
    {

        if (empty($config)) {
            $config = config('database');
        }

        if (!isset($config['options'])) {
            $config['options'] = [];
        }

        if (isset($config['socket']) && $config['socket']) {
            $dsn = 'mysql:unix_socket=' . trim($config['socket']);
        } else {
            $dsn = 'mysql:host=' . trim($config['host']);
            if (isset($config['port']) && is_int($config['port'] * 1)) {
                $dsn .= ';port=' . $config['port'];
            }
        }

        if (isset($config['dbname']) && $config['dbname']) {
            $dsn .= ';dbname=' . trim($config['dbname']);
        }
        if (isset($config['charset']) && $config['charset']) {
            $dsn .= ';charset=' . trim($config['charset']);
        }

        try {


            $this->pdo = new PDO($dsn, trim($config['username']), $config['password'], $config['options']);

        } catch (PDOException $e) {
            throw $e;
        }
        $this->dbConfig = $config;

    }

    private function __clone()
    {
    }//覆盖__clone()方法，禁止克隆

    public static function getInstance()
    {

        if (!(self::$_instance instanceof self)) {

            self::$_instance = new self();
        }

        return self::$_instance;

    }

    /**
     * Config database
     *
     * @param  mixed  $config
     * @param  string $value
     * @return mixed
     */
    public function dbConfig($config = null, $value = null)
    {
        if (!$config) {
            return $this->dbConfig;
        } elseif (is_array($config)) {
            $this->dbConfig = array_merge($this->dbConfig, $config);
        } elseif (is_string($config)) {
            if (is_null($value)) {
                return isset($this->dbConfig[$config]) ? $this->dbConfig[$config] : null;
            } else {
                $this->dbConfig[$config] = $value;

                return true;
            }
        }

        return null;
    }

    /**
     * Sets the database name
     *
     * @param  string $dbname
     * @return self
     */
    public function dbname($dbname)
    {
        $this->dbConfig['dbname'] = $dbname;

        return $this;
    }

    public function render($render = '')
    {

        if (!empty($render)) {
            $this->render = $render;
        } else {
            return $this->render;
        }


    }

    /**
     * Sets whether the distinct
     *
     * @param  boolean $distinct
     * @return self
     */
    public function distinct($distinct)
    {
        $this->options['distinct'] = (bool)$distinct;

        return $this;
    }

    /**
     * Parse the `DISTINCT` statement
     *
     * @access protected
     * @param  boolean $distinct
     * @return string
     */
    protected function parseDistinct($distinct)
    {
        return $distinct ? ' DISTINCT' : '';
    }

    /**
     * Sets the select expression
     *
     * @param  mixed $field The select expressions
     * @return self
     */
    public function field($field)
    {
        if (is_string($field)) {
            $this->options['field'] = trim($field);
        } elseif (is_array($field)) {
            if (isset($this->options['field']) && is_array($this->options['field'])) {
                $this->options['field'] = array_merge($this->options['field'], $field);
            } else {
                $this->options['field'] = $field;
            }
        } elseif (is_scalar($field)) {
            $this->options['field'] = $field;
        } else {
            $this->options['field'] = '*';
        }

        return $this;
    }

    /**
     * Parse the `SELECT EXPR`
     *
     * @access protected
     * @param  mixed $fields Select expressions
     * @return string
     */
    protected function parseField($fields)
    {
        $fieldsStr = '*';
        if ('*' == $fields || !$fields) {
            $fieldsStr = '*';
        } elseif (is_string($fields)) {
            // Ignore the special characters
            $chars = ['(', ')', ' AS ', '='];
            foreach ($chars as $char) {
                if (stripos($fields, $char) !== false) {
                    return $fields;
                }

            }

            $fieldsStr = $this->quoteKey($fields);
        } elseif (is_array($fields)) {
            $array = [];
            foreach ($fields as $key => $field) {
                if (!is_numeric($key) && $key) {
                    $array[] = (strpos($field, '*') !== false ? $field : $this->quoteKey($field)) . ' AS ' . $this->quoteKey($key);
                } else {
                    $array[] = $this->quoteKey($field);
                }
            }
            $fieldsStr = implode(', ', $array);
        } elseif (is_scalar($fields)) {
            $fieldsStr = $fields;
        }

        return $fieldsStr;
    }

    /**
     * Sets the table name for current statement
     *
     * @param  mixed   $table  Table name
     * @param  string  $alias  Table alias
     * @param  boolean $prefix The prefix of table name
     * @return self
     */
    public function table($table, $alias = null, $prefix = true)
    {

        if (is_string($table) && $table) {
            $tableName = trim($table);
        } elseif (is_array($table)) {
            $tableName = array_shift($table);
            if (!$alias && count($table) >= 1) {
                $alias = array_shift($table);
            }
            if (empty($tableName)) {
                throw new InvalidArgumentException('Unexpected empty value for "table"');
            }
        } else {
            throw new InvalidArgumentException('Invalid argument for "table"');
        }

        // Global prefix
        if (is_bool($prefix) && $prefix && !empty($this->dbConfig['prefix'])) {
            $tableName = $this->dbConfig['prefix'] . $tableName;
        } elseif (is_string($prefix) && $prefix) {
            // Custom prefix
            $tableName = trim($prefix) . $tableName;
        }

        // Filter alias
        if ($alias) {
            $alias = trim($alias);
        }
        if (!preg_match('/^([a-zA-Z0-9_]+)$/i', $alias)) {
            $alias = null;
        }

        $this->options['table'] = [$tableName, $alias];

        return $this;
    }

    /**
     * Parse the real table name that contains database
     *
     * @access protected
     * @param  array $table
     * @return string
     */
    protected function parseTable($table)
    {
        list($tableName, $alias) = $table;
        $tableName = camelcase2underline($tableName);
        if (!empty($this->dbConfig['dbname']) && strpos($tableName, '.') !== false) {
            $tableName = $this->dbConfig['dbname'] . '.' . $tableName;
        }
        $tableName = $this->quoteKey($tableName);

        if (!empty($alias)) {
            $tableName = $tableName . ' AS ' . $this->quoteKey($alias);
        }

        return $tableName;
    }

    /**
     * @param  string|array $table     Join table(s)
     * @param  string|array $condition Join conditions
     * @param  string       $type      Join type
     * @param  boolean      $prefix    The prefix of table name
     * @return self
     */
    public function join($condition, $prefix = true)
    {//$table


        $table     = $condition[0];
        $type      = empty($condition[2]) ? 'LEFT' : $condition[2];
        $condition = $condition[1];


        $alias = null;
        if (is_string($table) && $table) {

            if (strpos($table, '|')) {
                $arr       = explode('|', $table);
                $tableName = trim($arr[0]);
                $alias     = $arr[1];
            } else {
                $tableName = trim($table);
            }

        } else {
            throw new InvalidArgumentException('Invalid argument for "table"');
        }

        if (!is_string($condition) && !is_array($condition) || empty($condition)) {
            throw new InvalidArgumentException('Invalid argument for "condition"');
        }

        // Global prefix
        if (is_bool($prefix) && $prefix && !empty($this->dbConfig['prefix'])) {
            $tableName = $this->dbConfig['prefix'] . $tableName;
        } elseif (is_string($prefix) && $prefix) {
            // Custom prefix
            $tableName = trim($prefix) . $tableName;
        }

        // Filter alias
        if ($alias) {
            $alias = trim($alias);
        }
        if (!preg_match('/^([a-zA-Z0-9_]+)$/i', $alias)) {
            $alias = null;
        }

        $this->options['join'][] = [$tableName, $alias, strtoupper($type), $condition];

        return $this;
    }

    /**
     * Parse the `JOIN` statement
     *
     * @access protected
     * @param  array $join
     * @return string
     */
    protected function parseJoin(array $join)
    {
        $joinOn = [];
        if (!empty($join)) {
            foreach ($join as $item) {
                list($table, $alias, $type, $condition) = $item;

                $table    = $this->parseTable([$table, $alias]);
                $joinOn[] = $type . ' JOIN ' . $table . ' ON ' . $this->buildOn($condition);
            }
        }

        return $joinOn ? ' ' . implode(' ', $joinOn) : '';
    }

    /**
     * Parse the `ON` statement
     *
     * @access protected
     * @param  string|array $condition
     * @return string
     */
    protected function buildOn($condition)
    {
        if (is_string($condition)) {
            return $condition;
        }

        $on = [];
        foreach ($condition as $left => $right) {
            $on[] = $this->quoteKey($left) . ' = ' . $this->quoteKey($right);
        }

        return implode(' AND ', $on);
    }

    /**
     * Sets the `ORDER BY` statement
     *
     * @param  string|array $field Field name, supports multiple definitions
     * @param  string       $order ASC or DESC, not case-sensitive
     * @return self
     */
    public function order($order)
    {


        if (!empty($order) && is_string($order)) {


            $this->options['order'] = $order;
        }

        return $this;
    }

    /**
     * Parse the `ORDER BY` statement
     *
     * @access protected
     * @param  string|array $order
     * @return string
     */
    protected function parseOrder($order)
    {
        if (is_array($order)) {
            $array = [];
            foreach ($order as $key => $val) {
                if (is_numeric($key)) {
                    $array[] = $this->quoteKey($val);
                } else {
                    $array[] = $this->quoteKey($key) . (strtoupper(trim($val)) == 'DESC' ? ' DESC' : ' ASC');
                }
            }

            $order = implode(', ', $array);
        } elseif (is_string($order) && preg_match('/^[a-z0-9_\.]+$/i', $order)) {
            $order = $this->quoteKey($order);
        }

        return $order ? ' ORDER BY ' . trim($order) : '';
    }

    /**
     * Sets the `GROUP BY` statement
     *
     * @param  string|array $group
     * @param  string       $order ASC or DESC, not case-sensitive
     * @return self
     */
    public function group($group, $order = null)
    {
        if (!empty($order) && !in_array(strtoupper($order), ['DESC', 'ASC'])) {
            throw new InvalidArgumentException('Invalid param type for GROUP BY');
        }

        if ($group) {
            if (is_string($group)) {
                $group = $order ? [$group => $order] : $group;
            } elseif (!is_array($group)) {
                throw new InvalidArgumentException('Invalid param type for GROUP BY');
            }

            $this->options['group'] = $group;
        }

        return $this;
    }

    /**
     * Parse the `GROUP BY` statement
     *
     * @access protected
     * @param  string|array $group
     * @return string
     */
    protected function parseGroup($group)
    {
        if (is_array($group)) {
            $array = [];
            foreach ($group as $key => $val) {
                if (is_numeric($key)) {
                    $array[] = $this->quoteKey($val);
                } else {
                    $array[] = $this->quoteKey($key) . (strtoupper(trim($val)) == 'DESC' ? ' DESC' : ' ASC');
                }
            }

            $group = implode(', ', $array);
        } elseif (is_string($group) && preg_match('/^[a-z0-9_\.]+$/i', $group)) {
            $group = $this->quoteKey($group);

        }

        return $group ? ' GROUP BY ' . $group : '';
    }

    /**
     * Sets the `LIMIT` statement
     *
     * @param  integer|string $offset
     * @param  integer        $length
     * @return self
     */
    public function limit($offset, $length = null)
    {
        if (is_null($length) && strpos($offset, ',')) {
            list($offset, $length) = explode(',', $offset);
        }
        $this->options['limit'] = intval($offset) . ($length ? ', ' . intval($length) : '');

        return $this;
    }

    /**
     * Parse the `LIMIT` statement
     *
     * @access protected
     * @param  string $limit
     * @return string
     */
    protected function parseLimit($limit)
    {
        return $limit ? ' LIMIT ' . $limit : '';
    }

    /**
     * Sets the page of the data
     * A more vivid way of get range values
     *
     * @param  integer|string $page     Page number
     * @param  integer        $listRows Page size
     * @return self
     */
    public function page($page, $listRows = null)
    {
        if (is_null($listRows) && strpos($page, ',')) {
            list($page, $listRows) = explode(',', $page);
        }
        $this->options['page'] = [intval($page), intval($listRows)];

        return $this;
    }

    /**
     * 分页查询
     *
     * @param int|array $listRows 每页数量 数组表示配置参数
     * @param int|bool  $simple   是否简洁模式或者总记录数
     * @param array     $config   配置参数
     *                            page:当前页,
     *                            path:url路径,
     *                            query:url额外参数,
     *                            fragment:url锚点,
     *                            var_page:分页变量,
     *                            list_rows:每页数量
     *                            type:分页类名
     */
    public function paginate($listRows = null, $simple = false, $config = [])
    {

        if (is_int($simple)) {
            $total  = $simple;
            $simple = false;
        }
        if (is_array($listRows)) {
            $config   = array_merge(config('', 'paginate'), $listRows);
            $listRows = $config['list_rows'];
        } else {
            $config   = array_merge(config('', 'paginate'), $config);
            $listRows = $listRows ?: $config['list_rows'];
        }


        /** @var Paginator $class */
        $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\esclass\\paginator\\driver\\' . ucwords($config['type']);

        $page = isset($config['page']) ? (int)$config['page'] : call_user_func([
            $class,
            'getCurrentPage',
        ], $config['var_page']);

        $page = $page < 1 ? 1 : $page;

        $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);

        if (!isset($total) && !$simple) {

            $options = $this->getOptions();

            unset($this->options['order'], $this->options['limit'], $this->options['page'], $this->options['field']);


            $total   = $this->count();
            $results = $this->setOptions($options)->page($page, $listRows)->getList();
        } elseif ($simple) {
            $results = $this->limit(($page - 1) * $listRows, $listRows + 1)->getList();
            $total   = null;
        } else {
            $results = $this->page($page, $listRows)->getList();
        }

        $pageclass = $class::make($results, $listRows, $page, $total, $simple, $config);
        $render    = $pageclass->render();
        $this->render($render);
        return [$results, $render, $total];
    }

    /**
     * Sets the options
     *
     * @access protected
     * @param  array $options
     * @return self
     */
    protected function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get one or all of the options
     *
     * @param  string $name
     * @return mixed
     */
    public function getOptions($name = null)
    {
        return ($name && isset($this->options[$name])) ? $this->options[$name] : $this->options;
    }

    /**
     * Sets the `WHERE` statement
     *
     * @see    Function `buildWhere`
     * @access protected
     * @param  mixed $condition Supports a variety of formats
     * @param  mixed $value     The value of condition
     * @return self
     */
    public function where($condition, $value = null)
    {
        if (is_string($condition)) {
            if (!is_null($value)) {
                $this->options['where'][$condition] = $value;
            } else {
                $this->options['where']['SQL'] = $condition; // Custom SQL
            }
        } elseif (is_array($condition)) {


            if (isset($this->options['where']) && is_array($this->options['where'])) {
                $this->options['where'] = array_merge($this->options['where'], $condition);

            } else {
                $this->options['where'] = $condition;

            }
        }

        return $this;
    }

    /**
     * Parse the `WHERE` sub statement
     *
     * @access protected
     * @param  array $condition
     * @return string
     */
    protected function parseWhere($condition)
    {
        $whereString = $this->buildWhere($condition, 'AND', true);
        if (empty($condition) || empty($whereString)) {
            return '';
        }

        return $whereString ? ' WHERE ' . $whereString : '';
    }

    /**
     * Build the `WHERE` sub statement of SQL
     *
     * @access protected
     * @param  array   $condition
     * @param  string  $implodeType The implode type of sub statement, supports AND and OR
     * @param  boolean $root
     * @return string
     */
    protected function buildWhere($condition, $implodeType = 'AND', $root = false)
    {
        $func  = __FUNCTION__;
        $where = [];

        // Is index array?
        $isIndexArray = array_values($condition) === $condition;

        foreach ($condition as $key => $value) {
            $type = gettype($value);

            // SQL
            if ($key === 'SQL' && $type === 'string') {
                $where[] = $value;
                continue;
            }

            // Multi custom SQL
            if ($isIndexArray) {
                $where[] = $value;
                continue;
            }

            // AND
            if (($key === 'AND' || is_int($key)) && $type === 'array') {
                $where[] = $this->$func($value, 'AND');
                continue;
            }

            // OR
            if ($key === 'OR' && $type === 'array') {
                $where[] = $this->$func($value, 'OR');
                continue;
            }

            // Multi custom SQL for sub statement
            if (strval(intval($key)) == strval($key)) {
                if ($type === 'array' && array_values($value) === $value) {
                    if (count($value) <= 0) {
                        throw new InvalidArgumentException('Invalid param value for WHERE');
                    }
                    $where[] = $this->$func($value, $implodeType);
                    continue;
                } elseif ($type === 'string') {
                    $where[] = $value;
                    continue;
                }
            }

            $field    = $key;
            $operator = null;
            if (strpos($key, '|')) {
                list($field, $operator) = explode('|', $key);
                $operator = trim($operator);
            }
            $field = $this->quoteKey(trim($field));

            // =, IN, IS NULL
            if (!isset($operator) || empty($operator)) {
                switch ($type) {
                    case 'NULL':
                        $where[] = $field . ' IS NULL';
                        break;

                    case 'integer':
                    case 'double':
                        $where[] = $field . ' = ' . $value;
                        break;

                    case 'boolean':
                        $where[] = $field . ' = ' . ($value ? 1 : 0);
                        break;

                    case 'string':
                        $where[] = $field . ' = ' . $this->quoteValue($value);
                        break;

                    case 'array':

                        if (count($value) <= 0) {

                            throw new EsException('无效的查询数组条件');
                        }
                        $where[] = $field . ' IN (' . $this->quoteValue($value) . ')';

                        break;
                    default:
                        throw new EsException('无效的查询条件类型');

                }
            } elseif ($operator == '!') {
                // !=, NOT IN, IS NOT NULL
                switch ($type) {
                    case 'NULL':
                        $where[] = $field . ' IS NOT NULL';
                        break;

                    case 'integer':
                    case 'double':
                        $where[] = $field . ' != ' . $value;
                        break;

                    case 'boolean':
                        $where[] = $field . ' != ' . ($value ? 1 : 0);
                        break;

                    case 'string':
                        $where[] = $field . ' != ' . $this->quoteValue($value);
                        break;

                    case 'array':
                        if (count($value) <= 0) {
                            throw new EsException('无效的查询数组条件');
                        }
                        $where[] = $field . ' NOT IN (' . $this->quoteValue($value) . ')';
                        break;
                    default:
                        throw new EsException('无效的查询条件类型');
                }
            } elseif ($operator == '<>' || $operator == '><') {
                // BETWEEN(<>), NOT BETWEEN(><)
                if ($type != 'array' || count($value) < 2) {
                    throw new EsException('无效的查询条件类型');
                }

                if ($operator == '><') {
                    $field .= ' NOT';
                }

                list($beforeValue, $afterValue) = $value;
                $where[] = '(' . $field . ' BETWEEN ' . $this->quoteValue($beforeValue) . ' AND ' . $this->quoteValue($afterValue) . ')';
            } elseif ($operator == '~' || $operator == '!~') {
                // LIKE, NOT LIKE
                if ($type != 'array') {
                    if (!is_scalar($value)) {
                        throw new InvalidArgumentException('Invalid param type for WHERE');
                    }
                    $value = [$value];
                }

                $like = [];
                foreach ($value as $item) {
                    if (!is_scalar($item) || empty($item)) {
                        throw new InvalidArgumentException('Invalid param type for WHERE');
                    }

                    $item      = strval($item);
                    $suffix    = mb_substr($item, -1, 1);
                    $likeValue = $item;
                    // Bind placeholder
                    if ($this->isBindParam($item)) {
                        $likeValue = $item;
                    } elseif ($suffix != '%' && $item{0} != '%') {
                        $likeValue = '%' . $item . '%';
                    }
                    $like[] = $field . ($operator == '!~' ? ' NOT' : '') . ' LIKE ' . $this->quoteValue($likeValue);
                }

                $likeStr = implode(' OR ', $like);
                if (count($like) >= 2) {
                    $likeStr = '(' . $likeStr . ')';
                }
                $where[] = $likeStr;

            } elseif (in_array($operator, ['>', '>=', '<', '<='])) {
                // >, >=, <, <=
                if (is_numeric($value)) {
                    $where[] = $field . ' ' . $operator . ' ' . $value;
                } else {
                    $where[] = $field . ' ' . $operator . ' ' . $this->quoteValue($value);
                }
            } else {
                throw new InvalidArgumentException('Invalid param type for WHERE');
            }
        }

        $whereString = implode(' ' . $implodeType . ' ', $where);

        if (!$root && $whereString) {
            $whereString = '(' . $whereString . ')';
        }

        return $whereString;
    }

    /**
     * Quote the security value
     *
     * @access protected
     * @param  mixed $data
     * @return string
     */
    protected function quoteValue($data)
    {
        if (is_null($data)) {
            return 'NULL';
        } elseif (is_bool($data)) {
            $data = $data ? 1 : 0;
        } elseif (is_string($data)) {
            // Determine whether a placeholder for binding
            if ($this->isBindParam($data)) {
                return $data;
            }

            return $this->pdo->quote($data);
        } elseif (is_array($data)) {
            $temp = [];
            foreach ($data as $row) {
                if (!is_scalar($row) && !is_null($data)) {
                    throw new InvalidArgumentException('Invalid data type');
                }
                $temp[] = $this->quoteValue($row);
            }

            return implode($temp, ', ');
        } elseif (!is_scalar($data)) {
            throw new RuntimeException("Quote value must be a scalar variable");
        }

        return $data;
    }

    /**
     * Quote the keyword
     *
     * @access protected
     * @param  string $key
     * @return string
     */
    protected function quoteKey($key)
    {
        // Is escape identifiers?
        if (!$this->escape) {
            return $key;
        }

        if (empty($key) || is_numeric($key) || !is_string($key)) {
            return $key;
        }

        // Ignore the special characters
        if (preg_match('#[\(|\)|\+|\-|\/|%]#i', $key)) {
            return $key;
        }

        $key = str_replace([' ', '`'], '', $key);

        if (strpos($key, ',') !== false) {
            $key    = explode(',', $key);
            $fields = [];
            foreach ($key as $val) {
                if (empty($val)) {
                    throw new InvalidArgumentException('Unexpected null values');
                }

                $fields[] = $this->quoteKey($val);
            }

            return $fields ? implode(', ', $fields) : '*';
        } elseif (strpos($key, '.') !== false) {
            $key = explode('.', $key, 2);
        } else {
            $key = [$key];
        }

        foreach ($key as &$item) {
            $item = trim($item);
            if ($item != '*') {
                $item = '`' . $item . '`';
            }
        }

        return implode('.', $key);
    }

    /**
     * Sets the binding data
     *
     * @param  mixed  $key
     * @param  string $value
     * @return self
     */
    public function bind($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->bind($k, $v);
            }
        } else {
            $this->bindData[$key] = $value;
        }

        return $this;
    }

    /**
     * Get binding data
     *
     * @return array
     */
    public function getBindData()
    {
        return (array)$this->bindData;
    }

    /**
     * Clear binding data
     *
     * @return self
     */
    public function clearBindData()
    {
        $this->bindData = [];

        return $this;
    }

    /**
     * Is a bind param?
     *
     * @access protected
     * @param  string $data
     * @return boolean
     */
    protected function isBindParam($data)
    {
        if (!is_string($data)) {
            return false;
        }

        if ($data == '?') {
            // positional placeholder
            return true;
        } elseif (substr($data, 0, 1) == ':' && preg_match('/^\:[a-zA-Z_][a-zA-Z_0-9_]+$/i', $data)) {
            // named placeholder
            return true;
        }

        return false;
    }

    /**
     * Binds a value to a parameter
     *
     * Notice: Binding value do not support hybrid named and positional mark placeholder
     *
     * @access protected
     * @param  array $bindData
     * @throws RuntimeException
     */
    protected function bindValue(array $bindData = [])
    {
        foreach ($bindData as $key => $val) {
            $type = PDO::PARAM_STR;
            if (is_int($val)) {
                $type = PDO::PARAM_INT;
            } elseif (is_bool($val)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_null($val)) {
                $type = PDO::PARAM_NULL;
            } elseif (is_string($val)) {
                $type = PDO::PARAM_STR;
            }

            $param  = is_numeric($key) ? $key + 1 : ':' . $key;
            $result = $this->statement->bindValue($param, $val, $type);
            if (!$result) {
                throw new RuntimeException("Error occurred when binding parameters \"{$param}\"");
            }
        }
    }

    /**
     * Gets the bound SQL
     *
     * @access protected
     * @param  string $sql
     * @param  array  $bindData
     * @return string
     */
    protected function getBindSql($sql, array $bindData = [])
    {
        if ($bindData) {
            foreach ($bindData as $key => $val) {
                $value = $this->quoteValue($val);
                // Placeholder
                if (is_numeric($key)) {
                    $pos = strpos($sql, '?');
                    if ($pos !== false) {
                        $sql = substr_replace($sql, $value, $pos, 1);
                    }
                } else {
                    $sql = str_replace(
                        [
                            ':' . $key . ')',
                            ':' . $key . ',',
                            ':' . $key . ' ',
                        ],
                        [
                            $value . ')',
                            $value . ',',
                            $value . ' ',
                        ],
                        $sql . ' ');
                }
            }
        }

        return trim($sql);
    }

    /**
     * Parse the options of statement
     *
     * @access protected
     * @return array
     * @throws RuntimeException
     */
    protected function parseOptions()
    {
        $options = $this->options;

        if (!isset($options['table']) || empty($options['table'])) {
            throw new RuntimeException('Parameter "table" is missing');
        }

        if (!isset($options['where'])) {
            $options['where'] = [];
        }

        if (!isset($options['join'])) {
            $options['join'] = [];
        }

        if (!isset($options['field'])) {
            $options['field'] = '*';
        }

        if (!isset($options['distinct'])) {
            $options['distinct'] = false;
        }

        foreach (['limit', 'order', 'group'] as $name) {
            if (!isset($options[$name])) {
                $options[$name] = '';
            }
        }

        if (isset($options['page'])) {
            list($page, $listRows) = $options['page'];
            $page             = $page >= 1 ? $page : 1;
            $listRows         = $listRows > 0 ? $listRows : (is_numeric($options['limit']) ? $options['limit'] : 20);
            $offset           = $listRows * ($page - 1);
            $options['limit'] = $offset . ', ' . $listRows;
        }

        // Reset options
        $this->options = [];

        return $options;
    }

    /**
     * Determine whether the target data existed
     *
     * @param  array $where
     * @return boolean
     */
    public function has($where = [])
    {
        if ($where === true) {
            $this->fetch(true);
        } elseif ($where) {
            $this->where($where);
        }

        $options   = $this->parseOptions();
        $selectSql = 'SELECT EXISTS(SELECT 1 FROM %TABLE%%WHERE%) AS `tmp`';
        $sql       = str_replace(
            ['%TABLE%', '%WHERE%'],
            [
                $this->parseTable($options['table']),
                $this->parseWhere($options['where']),
            ], $selectSql);

        $result = $this->query($sql);
        if (is_array($result) && !empty($result[0]['tmp'])) {
            return true;
        } elseif (is_string($result)) {
            return $result;
        }

        return false;
    }

    /**
     * Counts the number of rows
     *
     * @param  string $field
     * @return integer
     */
    public function count($field = '*')
    {
        if ($field != '*' && !is_numeric($field)) {
            $field = $this->quoteKey($field);
        }

        $result = $this->value('COUNT(' . $field . ') AS `count_total`');

        return $result !== false ? $result : false;
    }

    /**
     * Get the total value for the column
     *
     * @param  string $field
     * @return integer
     */
    public function sum($field)
    {
        $result = $this->value('SUM(' . $this->quoteKey($field) . ') AS `sum_total`');

        return $result !== false ? $result : false;
    }

    /**
     * Get the maximum value for the column
     *
     * @param  string $field
     * @return integer
     */
    public function max($field)
    {
        $result = $this->value('MAX(' . $this->quoteKey($field) . ') AS `max_tmp`');

        return $result !== false ? $result : false;
    }

    /**
     * Get the minimum value for the column
     *
     * @param  string $field
     * @return integer
     */
    public function min($field)
    {
        $result = $this->value('MIN(' . $this->quoteKey($field) . ') AS `min_tmp`');

        return $result !== false ? $result : false;
    }

    /**
     * Get the average value for the column
     *
     * @param  string $field
     * @return integer
     */
    public function avg($field)
    {
        $result = $this->value('AVG(' . $this->quoteKey($field) . ') AS avg_tmp');

        return $result !== false ? $result : false;
    }

    /**
     * Gets the value of a field
     *
     * @param  string $field
     * @return mixed
     */
    public function value($field)
    {

        $data = $this->limit(1)->column($field);
        if (is_array($data) && isset($data[0])) {
            return $data[0];
        } elseif (is_string($data)) {
            // Fetch SQL
            return $data;
        }

        return false;
    }

    /**
     * Gets the value of the specified column
     *
     * @param  string $field
     * @return array
     */
    public function column($field, $key = '')
    {
        if (!is_string($field)) {
            return false;
        }
        if (isset($this->options['field'])) {
            unset($this->options['field']);
        }

        $data = $this->field($field)->getList();

        if ($data === false) {
            return false;
        } elseif (!$data) {
            return [];
        }


        $result = [];


        $fields = array_keys($data[0]);

        $count = count($fields);
        $key1  = array_shift($fields);
        $key2  = $fields ? array_shift($fields) : '';
        $key   = !empty($key) ? $key : $key1;

        foreach ($data as $val) {
            if ($count > 2) {
                $result[$val[$key]] = $val;
            } elseif (2 == $count) {
                $result[$val[$key]] = $val[$key2];
            } elseif (1 == $count) {
                $result[] = $val[$key1];
            }
        }
        return $result;
    }

    /**
     * Gets all values under the specified conditions
     *
     * @param  array|true $data
     * @param  boolean    $unbuffered
     * @return array
     */
    public function getList($data = null, $unbuffered = false)
    {
        // Set options
        if (is_array($data)) {
            foreach ($data as $method => $val) {
                if (in_array($method, ['table', 'distinct', 'field', 'where', 'order', 'limit'])) {
                    call_user_func_array([$this, $method], $val);
                }
            }
        }

        $options = $this->parseOptions();

        $selectSql = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%ORDER%%LIMIT%';
        $sql       = str_replace(
            ['%DISTINCT%', '%FIELD%', '%TABLE%', '%JOIN%', '%WHERE%', '%GROUP%', '%ORDER%', '%LIMIT%'],
            [
                $this->parseDistinct($options['distinct']),
                $this->parseField($options['field']),
                $this->parseTable($options['table']),
                $this->parseJoin($options['join']),
                $this->parseWhere($options['where']),
                $this->parseGroup($options['group']),
                $this->parseOrder($options['order']),
                $this->parseLimit($options['limit']),
            ], $selectSql);


        $result = $this->query($sql, $this->getBindData(), $data === true ? true : false, (bool)$unbuffered);


        return $result;
    }

    /**
     * Get a row of result set
     *
     * @param  array $data
     * @return array
     */
    public function getRow($data = null)
    {

        $this->limit(1);

        if (isset($data['limit'])) {
            unset($data['limit']);
        }

        $data = $this->getList($data);

        if (is_array($data) && isset($data[0])) {

            return $data[0];
        }

        return $data;
    }

    /**
     * Conditionally delete data
     *
     * @param  array   $where
     * @param  mixed   $table
     * @param  boolean $fetch
     * @return integer
     */
    public function delete(array $where = [], $table = null, $fetch = false)
    {
        if ($table) {
            $this->table($table);
        }
        if ($where) {
            $this->where($where);
        }

        $options   = $this->parseOptions();
        $deleteSql = 'DELETE FROM %TABLE%%WHERE%';
        $sql       = str_replace(
            ['%TABLE%', '%WHERE%'],
            [
                $this->parseTable($options['table']),
                $this->parseWhere($options['where']),
            ], $deleteSql);

        if ($options['limit']) {
            $extSql = $this->parseLimit($options['limit']);
            if ($extSql && $options['order']) {
                $extSql = $this->parseOrder($options['order']) . $extSql;
            }
            $sql .= $extSql;
        }

        return $this->exec($sql, $this->getBindData(), $fetch);
    }

    /**
     * Update data
     *
     * @param  array   $data
     * @param  array   $where
     * @param  mixed   $table
     * @param  boolean $fetch
     * @return integer
     */
    public function update(array $data, array $where = [], $table = null, $fetch = false)
    {
        // Parse update data
        if (!is_array(reset($data))) {
            $data = [$data];
        }

        $cachedata = $this->checkAllowField($data);

        $data = $cachedata[0];

        $data = $this->parseData($data);


        foreach ($data as $field => $value) {
            $data[$field] = $field . ' = ' . $value;
        }

        if (!$data) {
            throw new InvalidArgumentException('Unexpected data for UPDATE');
        }

        if ($table) {
            $this->table($table);
        }

        if ($where) {
            $this->where($where);
        }

        $options = $this->parseOptions();

        $updateSql = 'UPDATE %TABLE% SET %SET%%WHERE%';
        $sql       = str_replace(
            ['%TABLE%', '%SET%', '%WHERE%'],
            [
                $this->parseTable($options['table']),
                implode(', ', $data),
                $this->parseWhere($options['where']),
            ], $updateSql);

        if ($options['limit']) {
            $extSql = $this->parseLimit($options['limit']);
            if ($extSql && $options['order']) {
                $extSql = $this->parseOrder($options['order']) . $extSql;
            }
            $sql .= $extSql;
        }

        return $this->exec($sql, $this->getBindData(), $fetch);
    }

    public function setIncOrDec($where, $field, $value = '', $type = '+')
    {
        if ($value == '') {
            $value = 1;
        }
        $updateSql = 'UPDATE %TABLE% SET %SET%%WHERE%';
        $sql       = str_replace(
            ['%TABLE%', '%SET%', '%WHERE%'],
            [
                $this->parseTable($this->options['table']),
                '`' . $field . '` = ' . $field . $type . $value,
                $this->parseWhere($where),
            ], $updateSql);


        return $this->query($sql);
    }

    /**
     * Insert new data
     *
     * Support for bulk inserts, or updates while a duplicate value in a UNIQUE index or PRIMARY KEY
     *
     * @param  array   $data    The data to insert
     * @param  mixed   $replace Use sub statement `ON DUPLICATE KEY UPDATE` to replace existed data
     * @param  boolean $fetch   Is fetch the SQL statement?
     * @return integer
     */
    public function insert(array $data, $replace = null, $fetch = false)
    {
        // One insert data
        if (!is_array(reset($data))) {
            $data = [$data];
        }

        $data = $this->checkAllowField($data);

        $diffRow = reset($data);

        ksort($diffRow);

        $keyCount = count($diffRow);

        $fields = array_keys($diffRow);

        $insertFields = array_map(function ($val) {
            return $this->quoteKey($val);
        }, $fields);


        $insertValues = [];
        foreach ($data as $rowData) {

            if (count($rowData) != $keyCount) {
                throw new InvalidArgumentException('Unexpected data for INSERT');
            }

            $rowData = array_intersect_key($rowData, $diffRow);
            if (count($rowData) < $keyCount) {
                throw new InvalidArgumentException('Unexpected data for INSERT');
            }

            ksort($rowData);

            $rowData = $this->parseData($rowData);

            if (!$rowData) {
                throw new InvalidArgumentException('Unexpected data for INSERT');
            }

            $insertValues[] = '(' . implode(', ', array_values($rowData)) . ')';
        }

        // Building `ON DUPLICATE KEY UPDATE` statement
        $replaceStr = '';
        if (is_array($replace) && $replace) {
            $replaceStr = [];
            foreach ($replace as $field) {
                if (!in_array($field, $fields)) {
                    throw new InvalidArgumentException('Invalid update field');
                }

                $replaceStr[] = $this->quoteKey($field) . '=VALUES(' . $this->quoteKey($field) . ')';
            }
            $replaceStr = implode(', ', $replaceStr);
        } elseif (is_string($replace) && $replace) {
            $replaceStr = trim($replace);
        } elseif ($replace === true) {
            $replaceStr = [];
            foreach ($fields as $field) {
                $replaceStr[] = $this->quoteKey($field) . '=VALUES(' . $this->quoteKey($field) . ')';
            }
            $replaceStr = implode(', ', $replaceStr);
        }

        // Formatting SQL statements
        $options   = $this->parseOptions();
        $insertSql = 'INSERT INTO %TABLE% (%FIELD%) VALUES %DATA%%REPLACE%';
        $sql       = str_replace(
            ['%TABLE%', '%FIELD%', '%DATA%', '%REPLACE%'],
            [
                $this->parseTable($options['table']),
                implode(', ', $insertFields),
                implode(', ', $insertValues),
                $replaceStr ? ' ON DUPLICATE KEY UPDATE ' . $replaceStr : '',
            ], $insertSql);

        // Execute insert sql
        $result = $this->exec($sql, $this->getBindData(), $fetch);


        if (is_string($result) && substr($result, 0, 6) == 'INSERT') {
            // If fetch the sql string
            return $result;
        } elseif ($result !== false) {
            // If the number of rows affected equals 0, return `true`;
            // else, return the last insert id.


            return $result === 0 ? true : $this->pdo->lastInsertId();
        }

        return false;
    }

    protected function checkAllowField($data)
    {
        $sql = 'SHOW FULL COLUMNS FROM ' . $this->parseTable($this->options['table']);

        $result = $this->query($sql);
        $info   = [];
        $fields = array_keys(reset($data));

        if (is_array($result) && !empty($result)) {
            foreach ($result as $val) {

                $val = array_change_key_case($val);

                array_push($info, $val['field']);

            }

            $n = array_intersect($info, $fields);

            $m = [];
            foreach ($data as $k => $v) {
                foreach ($n as $ko => $vo) {
                    $m[$k][$vo] = $v[$vo];
                }


            }


        }
        return $m;
    }

    /**
     * Parse the data of insert or update
     *
     * @access protected
     * @param  array $data
     * @return array
     */
    protected function parseData(array $data)
    {
        $return = [];

        foreach ($data as $key => $val) {
            if (is_array($val)) {
                // Convert array to a JSON string
                if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
                    $val = json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    $val = json_encode($val);
                }
            } elseif (is_object($val)) {
                // Serialize a object to string
                $val = serialize($val);
            } elseif (is_bool($val)) {
                // Convert a Boolean value to a string of 1 or 0
                $val = $val ? 1 : 0;
            } elseif (is_null($val)) {
                // Converting NULL
                $val = null;
            } elseif (!is_scalar($val)) {
                throw new InvalidArgumentException('Unexpected data type');
            }

            $return[$this->quoteKey($key)] = $this->quoteValue($val);
        }

        return $return;
    }

    /**
     * Is escape identifiers?
     *
     * @param  boolean $escape
     * @return self
     */
    public function escape($escape = true)
    {
        $this->escape = $escape ? true : false;

        return $this;
    }

    /**
     * Fetch the SQL statement
     *
     * @param  boolean $fetch
     * @return self
     */
    public function fetch($fetch = true)
    {
        $this->fetchSql = $fetch ? true : false;

        return $this;
    }

    /**
     * Execute an SQL statement and return the result
     *
     * @param  string  $sql
     * @param  array   $bindData
     * @param  boolean $fetch
     * @param  boolean $unbuffered
     * @return mixed
     */
    public function query($sql, array $bindData = [], $fetch = false, $unbuffered = false)
    {
        $sql = $this->parseSqlTable($sql);

        if (empty($bindData)) {
            $bindData = $this->getBindData();
        }
        $this->clearBindData();

        // Reset escape status
        $this->escape(true);

        // Fetch the query sql
        $querySql = $this->getBindSql($sql, $bindData);
        if ($fetch || $this->fetchSql) {
            $this->fetch(false);

            return $querySql;
        }

        // Setting buffered query param
        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, !((bool)$unbuffered));

        // Prepare and execute sql
        $this->statement = $this->pdo->prepare($sql);
        $this->bindValue($bindData);
        $result = $this->statement->execute();
        $this->addQueryLog($bindData ? $querySql : $this->statement->queryString);
        if (!$result) {
            return false;
        }

        // Support unbuffered query
        if ($unbuffered) {
            return $this->statement;
        }

        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Execute an SQL statement and return the number of affected rows
     *
     * @param  string  $sql
     * @param  array   $bindData
     * @param  boolean $fetch
     * @return mixed
     */
    public function exec($sql, array $bindData = [], $fetch = false)
    {
        $sql = $this->parseSqlTable($sql);
        if (empty($bindData)) {
            $bindData = $this->getBindData();
        }
        $this->clearBindData();

        // Reset escape status
        $this->escape(true);

        // Fetch the query sql
        $querySql = $this->getBindSql($sql, $bindData);
        if ($fetch || $this->fetchSql) {
            $this->fetch(false);

            return $querySql;
        }

        // Initialize buffered query param
        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        // Prepare and execute sql
        $this->statement = $this->pdo->prepare($sql);
        $this->bindValue($bindData);
        $result = $this->statement->execute();

        $this->addQueryLog($bindData ? $querySql : $this->statement->queryString);
        if (!$result) {
            return false;
        }

        return $this->statement->rowCount();
    }

    /**
     * Parse the table name in the SQL
     *
     * @access protected
     * @param  string $sql
     * @return string
     */
    protected function parseSqlTable($sql)
    {
        $prefix = isset($this->dbConfig['prefix']) ? $this->dbConfig['prefix'] : '';

        return preg_replace_callback("/#([A-Z0-9_-]+)#/sU", function ($match) use ($prefix) {
            return $prefix . strtolower($match[1]);
        }, (string)$sql);
    }

    /**
     * Add a query log
     *
     * @access protected
     * @param string $sql
     */
    protected function addQueryLog($sql)
    {

        array_push($this->queryLog, $sql);


    }

    /**
     * Gets the last or all of the query logs
     *
     * @param  boolean $isLast
     * @return mixed
     */
    public function getQueryLog($isLast = false)
    {
        if ($isLast) {
            return end($this->queryLog);
        }

        return $this->queryLog;
    }

    /**
     * Fetch extended error information with the last operation
     *
     * @return array
     */
    public function errorInfo()
    {

        $errMS = $this->statement->errorInfo();
        return '错误码：' . $errMS[0] . '<br/>' . '错误编号：' . $errMS[1] . '<br/>' . '错误信息：' . $errMS[2] . '<br/>';


    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Initiates a transaction
     *
     * @return boolean
     */
    public function beginTransaction()
    {
        if ($this->inTransaction()) {
            return true;
        }

        return $this->pdo->beginTransaction();
    }

    /**
     * Commits a transaction
     *
     * @return boolean
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Rolls back a transaction
     *
     * @return boolean
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Checks if inside a transaction
     *
     * @return boolean
     */
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Execute a transaction of callback
     *
     * @param  callable $callback
     * @param  mixed    $params
     * @return boolean
     */
    public function action(callable $callback, $params = null)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('$callback is a invalid callback');
        }

        try {
            $this->pdo->beginTransaction();
            $result = call_user_func($callback, $params);
            if ($result === false) {
                $this->pdo->rollBack();

                return false;
            }
            $this->pdo->commit();

            return $result;
        } catch (PDOException $e) {
            $this->pdo->rollBack();

            return false;
        }
    }

    /**
     * Bulk processing of data chunk
     *
     * @param  integer  $size Each batch number
     * @param  callable $callback
     * @param  array    $where
     * @param  array    $order
     * @return boolean
     */
    public function chunk($size, callable $callback, array $where = [], array $order = [])
    {
        if ($where) {
            $this->where($where);
        }
        if ($order) {
            $this->order($order);
        }

        $options = $this->getOptions();

        // Starting process
        $page = 1;
        do {
            // If no data, return TRUE
            $data = $this->setOptions($options)->page($page, $size)->getList();
            if (!$data) {
                return true;
            }

            if (false === call_user_func($callback, $data)) {
                return false;
            }

            $page += 1;
        } while (count($data) >= $size); // Determine whether there is any available data

        return true;
    }

    /**
     * Unbuffered MySQL queries
     *
     * @see http://php.net/manual/zh/mysqlinfo.concepts.buffering.php
     *
     * @param  callable $callback
     * @param  string   $sql
     * @param  array    $bindData
     * @return mixed
     */
    public function unbufferedQuery(callable $callback = null, $sql = null, array $bindData = [])
    {
        if (is_null($sql)) {
            $result = $this->getList(null, true);
        } else {
            $result = $this->query($sql, $bindData, $this->fetchSql, true);
        }

        // Fetch sql
        if (is_string($result)) {
            return $result;
        }

        // Query failed
        if (!$result) {
            return false;
        }

        if (!is_null($callback) && is_callable($callback)) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                call_user_func($callback, $row);
            }
        }

        return $result;
    }

    /**
     * 获取当前数据表的主键
     *
     * @access public
     * @param string|array $options 数据表名或者查询参数
     * @return string|array
     */
    public function getPk($table, $prefix = true)
    {
        if ($prefix) {
            $table = $this->dbConfig['prefix'] . $table;
        }


        if (!empty($this->pk)) {
            $pk = $this->pk;
        } else {


            $sql    = 'SHOW FULL COLUMNS FROM ' . $table;
            $result = $this->query($sql);

            $row = [];
            if (is_array($result) && !empty($result)) {
                foreach ($result as $val) {
                    $val = array_change_key_case($val);

                    if (strtolower($val['key']) == 'pri') {
                        return $val['field'];
                    }
                }
            }


        }

    }


    /**
     * Gets the field information of a table
     *
     * @param  mixed $table
     * @return array
     */
    public function getFields($table = null)
    {
        // Parse table name
        if ($table) {
            $this->table($table);
        }
        $options = $this->parseOptions();

        $tableName = $this->parseTable($options['table']);


        $sql    = 'SHOW FULL COLUMNS FROM ' . $tableName;
        $result = $this->query($sql);

        $info = [];
        if (is_array($result) && !empty($result)) {
            foreach ($result as $val) {
                $val                 = array_change_key_case($val);
                $info[$val['field']] = [
                    'name'    => $val['field'],
                    'type'    => $val['type'],
                    'notnull' => strtolower($val['null']) == 'no',
                    'default' => $val['default'],
                    'primary' => strtolower($val['key']) == 'pri',
                    'extra'   => $val['extra'],
                    'comment' => $val['comment'],
                ];
            }
        }

        return $info;
    }

    /**
     * Get table list
     *
     * @param  string $dbName
     * @return array
     */
    public function getTables($dbName = null)
    {
        if (empty($dbName) && !empty($this->dbConfig['dbname'])) {
            $dbName = $this->dbConfig['dbname'];
        }

        $sql    = !empty($dbName) ? 'SHOW TABLES FROM ' . $this->quoteKey($dbName) : 'SHOW TABLES';
        $result = $this->query($sql);

        $tables = [];
        if (is_array($result) && !empty($result)) {
            foreach ($result as $row) {
                $tables[] = current($row);
            }
        }

        return $tables;
    }

    /**
     * Gets the database server info
     *
     * @return array
     */
    public function getServerInfo()
    {
        $output = [
            'server'     => PDO::ATTR_SERVER_INFO,
            'driver'     => PDO::ATTR_DRIVER_NAME,
            'client'     => PDO::ATTR_CLIENT_VERSION,
            'version'    => PDO::ATTR_SERVER_VERSION,
            'connection' => PDO::ATTR_CONNECTION_STATUS,
        ];

        foreach ($output as $key => $value) {
            $output[$key] = $this->pdo->getAttribute($value);
        }

        return $output;
    }

}