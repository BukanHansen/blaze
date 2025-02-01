<?php

namespace Blaze\Render;

class View
{
    protected $config;
    protected $viewPath;
    protected $layout;
    protected $sections = [];
    protected $currentSection;

    public function __construct($file, array $config = [])
    {
        $this->viewPath = __DIR__ . "/../../app/Views/$file.php";
        $this->config = $config;
    }

    public function render()
    {
        if (!file_exists($this->viewPath)) {
            throw new \Exception("View file not found: {$this->viewPath}");
        }

        ob_start();
        extract($this->config);
        include $this->viewPath;
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutPath = __DIR__ . "/../../app/Views/{$this->layout}.php";
            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout file not found: {$layoutPath}");
            }

            ob_start();
            include $layoutPath;
            $content = ob_get_clean();
        }

        echo $content;
    }

    public function layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function section($name)
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection()
    {
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = null;
    }

    public function renderSection($name)
    {
        if (isset($this->sections[$name])) {
            echo $this->sections[$name];
        }
    }

    public function html($string)
    {
        return htmlspecialchars($string);
    }

    public function components(string $path)
    {
        $components = scandir(__DIR__ . "/../../app/Views/$path");
        $script = "";

        foreach ($components as $component) {
            if (str_ends_with($component, ".php")) {
                $componentName = explode(".", $component)[0];
                $componentContent = file_get_contents(__DIR__ . "/../../app/Views/$path/$component");

                $className = str_replace("=", "", hash("sha256", base64_encode(rand(1000000000, 99999999999999) * 1.5 / 10) . md5(rand(1023123, 121243145815))));
                $script .= "class _$className extends HTMLElement {constructor(){super();this.attachShadow({ mode: 'open' });this.shadowRoot.innerHTML = `$componentContent`;}}window.customElements.define('$componentName', _$className);";
            }
        }

        $script = base64_encode($script);
        $script = "<script>eval(atob('$script'));</script>";
        return $script;
    }
}
