<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f3f4f7">
    <title>{block name="title"}Блог{/block}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    {block name="styles"}{/block}
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
</head>
<body>
{include file='partials/header.tpl'}

<main class="page-main">
    <div class="shell">
        {block name="content"}{/block}
    </div>
</main>

{block name="scripts"}{/block}
</body>
</html>
