<?php

namespace MyProject\View;

/**
 * Class View
 * @package MyProject\View
 */
class View
{
    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var array
     */
    private $extraVar = [];

    /**
     * View constructor.
     * @param string $templatePath
     */
    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @param string $varName
     * @param $varValue
     */
    public function setAdditionData(string $varName, $varValue)
    {
        $this->extraVar[$varName] = $varValue;
    }

    /**
     * @param string $templateName
     * @param array $vars
     * @param int $code
     */
    public function renderHtml(string $templateName, array $vars = [], $code = 200)
    {
        http_response_code($code);
        extract($this->extraVar);
        extract($vars);
        ob_start();
        include_once $this->templatePath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }
}