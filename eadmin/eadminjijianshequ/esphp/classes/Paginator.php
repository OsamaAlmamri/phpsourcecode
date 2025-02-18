<?php

namespace esclass;


abstract class Paginator
{
    /** @var bool 是否为简洁模式 */
    protected $simple = false;

    /** @var Collection 数据集 */
    protected $items;

    /** @var integer 当前页 */
    protected $currentPage;

    /** @var  integer 最后一页 */
    protected $lastPage;

    /** @var integer|null 数据总数 */
    protected $total;

    /** @var  integer 每页的数量 */
    protected $listRows;

    /** @var bool 是否有下一页 */
    protected $hasMore;

    /** @var array 一些配置 */
    protected $options = [
        'var_page' => 'page',
        'path'     => '/',
        'query'    => [],
        'fragment' => '',
    ];

    public function __construct($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {


        $this->options = array_merge($this->options, $options);

        $this->options['path'] = '/' != $this->options['path'] ? rtrim($this->options['path'], '/') : $this->options['path'];

        $this->simple   = $simple;
        $this->listRows = $listRows;


        if ($simple) {
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = count($items) > ($this->listRows);
            $items             = $items->slice(0, $this->listRows);
        } else {
            $this->total       = $total;
            $this->lastPage    = (int)ceil($total / $listRows);
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = $this->currentPage < $this->lastPage;
        }

        $this->items = $items;


    }

    /**
     * @param       $items
     * @param       $listRows
     * @param null  $currentPage
     * @param bool  $simple
     * @param null  $total
     * @param array $options
     * @return Paginator
     */
    public static function make($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {

        return new static($items, $listRows, $currentPage, $total, $simple, $options);
    }

    protected function setCurrentPage($currentPage)
    {
        if (!$this->simple && $currentPage > $this->lastPage) {
            return $this->lastPage > 0 ? $this->lastPage : 1;
        }

        return $currentPage;
    }

    /**
     * 获取页码对应的链接
     *
     * @param $page
     * @return string
     */
    protected function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        if (strpos($this->options['path'], '[PAGE]') === false) {
            $parameters = [$this->options['var_page'] => $page];
            $path       = $this->options['path'];
        } else {
            $parameters = [];
            $path       = str_replace('[PAGE]', $page, $this->options['path']);
        }
        if (count($this->options['query']) > 0) {
            $parameters = array_merge($this->options['query'], $parameters);
        }

        //   $url = $path;

        //得到路径和请求字符串
        $url = $_SERVER['REQUEST_URI'];

        if (!empty($parameters)) {
            $uriArray = parse_url($url);


            if (webconfig('OPEN_ROUTER') == 0) {


                if ($uriArray['query'] != '') {
                    parse_str($uriArray['query'], $newp);
                    $parameters = array_merge($newp, $parameters);


                }

                $url = $uriArray['path'] . '?' . urldecode(http_build_query($parameters, null, '&'));
            } else {

                $url = $uriArray['path'] . '?' . urldecode(http_build_query($parameters, null, '&'));
            }

        }

        return $url . $this->buildFragment();
    }

    /**
     * 自动获取当前页码
     *
     * @param string $varPage
     * @param int    $default
     * @return int
     */
    public static function getCurrentPage($varPage = 'page', $default = 1)
    {
        $page = Request::instance()->request($varPage);

        if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int)$page >= 1) {
            return $page;
        }

        return $default;
    }

    /**
     * 自动获取当前的path
     *
     * @return string
     */
    public static function getCurrentPath()
    {
        return Request::instance()->baseUrl();
    }

    public function total()
    {
        if ($this->simple) {
            throw new \DomainException('not support total');
        }
        return $this->total;
    }

    public function listRows()
    {
        return $this->listRows;
    }

    public function currentPage()
    {
        return $this->currentPage;
    }

    public function lastPage()
    {
        if ($this->simple) {
            throw new \DomainException('not support last');
        }
        return $this->lastPage;
    }

    /**
     * 数据是否足够分页
     *
     * @return boolean
     */
    public function hasPages()
    {
        return !(1 == $this->currentPage && !$this->hasMore);
    }

    /**
     * 创建一组分页链接
     *
     * @param  int $start
     * @param  int $end
     * @return array
     */
    public function getUrlRange($start, $end)
    {
        $urls = [];

        for ($page = $start; $page <= $end; $page++) {
            $urls[$page] = $this->url($page);
        }

        return $urls;
    }

    /**
     * 设置URL锚点
     *
     * @param  string|null $fragment
     * @return $this
     */
    public function fragment($fragment)
    {
        $this->options['fragment'] = $fragment;
        return $this;
    }

    /**
     * 添加URL参数
     *
     * @param  array|string $key
     * @param  string|null  $value
     * @return $this
     */
    public function appends($key, $value = null)
    {
        if (!is_array($key)) {
            $queries = [$key => $value];
        } else {
            $queries = $key;
        }

        foreach ($queries as $k => $v) {
            if ($k !== $this->options['var_page']) {
                $this->options['query'][$k] = $v;
            }
        }

        return $this;
    }

    /**
     * 构造锚点字符串
     *
     * @return string
     */
    protected function buildFragment()
    {
        return $this->options['fragment'] ? '#' . $this->options['fragment'] : '';
    }

    /**
     * 渲染分页html
     *
     * @return mixed
     */
    abstract public function render();


    public function __toString()
    {
        return (string)$this->render();
    }

    public function toArray()
    {
        try {
            $total = $this->total();
        } catch (\DomainException $e) {
            $total = null;
        }

        return [
            'total'        => $total,
            'per_page'     => $this->listRows(),
            'current_page' => $this->currentPage(),
            'last_page'    => $this->lastPage,
            'data'         => $this->items,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }


}
