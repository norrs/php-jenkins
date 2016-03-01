<?php
/**
 * Created by PhpStorm.
 * User: rockj
 * Date: 2/29/16
 * Time: 2:27 PM
 */

namespace example;


use Psr\Http\Message\ServerRequestInterface;

class LocaleRule implements Rule
{
    private $request;
    private $match;
    const HTTP_HEADER_ACCEPT_LANGUAGE = "Accept-Language";


    /**
     * LocaleRule constructor.
     */
    public function __construct(ServerRequestInterface $request, $match)
    {
        $this->request = $request;
        $this->match = $match;
    }

    public function evaluate()
    {
        return mb_strpos($this->request->getHeaderLine(self::HTTP_HEADER_ACCEPT_LANGUAGE), $this->match) === 0;
    }
}