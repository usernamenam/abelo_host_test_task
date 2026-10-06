{extends file='layout.tpl'}

{block name="title"}{$heading|escape}{/block}

{block name="content"}
    <section class="empty-plate">
        <h1 class="empty-plate__title">{$heading|escape}</h1>
        <p>{$message|escape}</p>
        <a class="button" href="/">На главную</a>
    </section>
{/block}
