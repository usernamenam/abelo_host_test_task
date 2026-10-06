<?php

declare(strict_types=1);

namespace App\Framework;

use Smarty;

class View
{
    private Smarty $engine;

    /** @param array{templates: string, compile: string, cache: string} $paths */
    public function __construct(array $paths)
    {
        $this->engine = new Smarty();
        $this->engine->setTemplateDir($paths['templates']);
        $this->engine->setCompileDir($paths['compile']);
        $this->engine->setCacheDir($paths['cache']);
    }

    public function assignVar(string $key, mixed $value): void
    {
        $this->engine->assign($key, $value);
    }

    /**
     * @param array<string, mixed> $vars
     * @throws \SmartyException
     */
    public function render(string $template, array $vars = []): string
    {
        foreach ($vars as $key => $value) {
            $this->engine->assign($key, $value);
        }

        return $this->engine->fetch($template);
    }

    public function error(int $code, string $message): string
    {
        http_response_code($code);

        return $this->render('error.tpl', [
            'code' => $code,
            'heading' => $code === 404 ? 'Страница не найдена' : 'Сбой сервера',
            'message' => $message,
        ]);
    }
}
