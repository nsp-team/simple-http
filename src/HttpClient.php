<?php

namespace NspTeam;

use Yurun\Util\HttpRequest;
use Yurun\Util\YurunHttp\Http\Response;

class HttpClient
{
    /**
     * @var HttpRequest
     */
    protected $httpRequest;

    /**
     * 请求头.
     * @var array
     */
    protected $headers = [];

    /**
     * cookie.
     * @var array
     */
    protected $cookies = [];

    /**
     * 失败重试次数，默认为0.
     * @var int
     */
    protected $retry = 0;

    /**
     * 请求体
     * @var string
     */
    protected $content = '';

    /**
     * 总超时时间，单位：毫秒.
     * @var int
     */
    protected $timeout = 5000;

    /**
     * 连接时间 单位：毫秒.
     * @var int
     */
    protected $connectTimeout = 1000;


    public function __construct()
    {
        $this->httpRequest = new HttpRequest();
    }

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * 设置请求头.
     * @param string $header 请求头名称
     * @param string $value 值
     * @return static
     */
    public function withHeader(string $header, string $value): self
    {
        $this->headers[$header] = $value;
        return $this;
    }

    /**
     * 批量设置请求头.
     * @param array $headers 键值数组
     * @return static
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * 批量设置Cookies.
     * @param array $cookies 键值对应数组
     * @return static
     */
    public function withCookies(array $cookies): self
    {
        $this->cookies = array_merge($this->cookies, $cookies);
        return $this;
    }

    /**
     * 设置Cookie.
     * @param string $name 名称
     * @param string $value 值
     * @return static
     */
    public function withCookie(string $name, string $value): self
    {
        $this->cookies[$name] = $value;
        return $this;
    }

    /**
     * 失败重试.
     * @param string $retry
     * @return static
     */
    public function withRetry(string $retry): self
    {
        $this->retry = $retry;
        return $this;
    }

    /**
     * 设置请求主体.
     * @param string|array $requestBody 发送内容，可以是字符串、数组
     * @return static
     */
    public function withContent($requestBody): self
    {
        $this->content = $requestBody;
        return $this;
    }

    /**
     * 超时设置
     * @param int $timeout 总时长
     * @param int $connectTimeout 连接时长
     * @return $this
     */
    public function withTimeout(int $timeout, int $connectTimeout=1000): self
    {
        $this->timeout = $timeout;
        $this->connectTimeout = $connectTimeout;
        return $this;
    }


    /**
     * @param string $url
     * @param string $method
     * @param mixed $body 发送内容，可以是字符串、数组，如果为空则取content属性值
     * @param null|string $contentType 内容类型，支持null/json，为null时不处理
     * @return Response|null
     */
    protected function request(string $url, string $method, $body = null, string $contentType = null): ?Response
    {
        $http = &$this->httpRequest;
        $http = $http->method($method);
        if (!empty($this->headers)) {
            $http = $http->headers($this->headers);
        }
        if (!empty($this->timeout)) {
            $http = $http->timeout($this->timeout, $this->connectTimeout);
        }
        if (!empty($this->retry)) {
            $http = $http->retry($this->retry);
        }
        if (!empty($this->cookies)) {
            $http = $http->cookies($this->cookies);
        }
        if (!empty($body)) {
            $http = $http->content($body);
        } elseif (!empty($this->content)) {
            $http = $http->content($this->content);
        }

        switch (strtolower($method)) {
            case 'post':
                $response = $http->post($url, $body, $contentType);
                break;
            case 'get':
                $response = $http->get($url, $body);
                break;
            case 'put':
                $response = $http->put($url, $body, $contentType);
                break;
            case 'head':
                $response = $http->head($url, $body);
                break;
            case 'patch':
                $response = $http->patch($url, $body, $contentType);
                break;
            case 'delete':
                $response = $http->delete($url, $body, $contentType);
                break;
            default:
                $response = $http->get($url, $body);
        }

        return $response;
    }

    /**
     * post 请求
     * @param string $url
     * @param string|array|null $requestBody 发送内容，可以是字符串、数组，如果为空则取content属性值
     *  [
     *     'id'    =>    3,
     *     'name'    =>    4,
     *      new UploadedFile('1.txt', MediaType::TEXT_PLAIN, __FILE__),
     *  ]
     * @param string|null $contentType 内容类型，支持null/json，为null时不处理
     * @return Response|null
     */
    public function post(string $url, $requestBody = null, string $contentType = null): ?Response
    {
        return $this->request($url, 'POST', $requestBody, $contentType);
    }

    /**
     * get 请求
     * @param string $url
     * @param null $requestBody
     * @return Response|null
     */
    public function get(string $url, $requestBody = null): ?Response
    {
        return $this->request($url, 'GET', $requestBody);
    }

    /**
     * head 请求
     * @param string $url
     * @param null $requestBody
     * @return Response|null
     */
    public function head(string $url, $requestBody = null): ?Response
    {
        return $this->request($url, 'HEAD', $requestBody);
    }

    /**
     * put 请求
     * @param string $url
     * @param null $requestBody
     * @param string|null $contentType
     * @return Response|null
     */
    public function put(string $url, $requestBody = null, string $contentType = null): ?Response
    {
        return $this->request($url, 'PUT', $requestBody, $contentType);
    }

    /**
     * patch 请求
     * @param string $url
     * @param null $requestBody
     * @param string|null $contentType
     * @return Response|null
     */
    public function patch(string $url, $requestBody = null, string $contentType = null): ?Response
    {
        return $this->request($url, 'PATCH', $requestBody, $contentType);
    }

    /**
     * delete 请求
     * @param string $url
     * @param null $requestBody
     * @param string|null $contentType
     * @return Response|null
     */
    public function delete(string $url, $requestBody = null, string $contentType = null): ?Response
    {
        return $this->request($url, 'DELETE', $requestBody, $contentType);
    }

    /**
     * 下载文件
     * @param string $fileNamePath 保存路径，如果以 .* 结尾，则根据 Content-Type 自动决定扩展名
     * @param string|null $url 下载文件地址
     * @param string|array|null $requestBody
     * @param string $method 请求方法，GET | POST等，一般用GET
     * @return Response|null
     */
    public function downloadFile(string $fileNamePath, string $url = null, $requestBody = null, string $method = 'GET'): ?Response
    {
        return $this->httpRequest->download($fileNamePath, $url, $requestBody, $method);
    }
}