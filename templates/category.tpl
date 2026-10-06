{extends file='layout.tpl'}

{block name="title"}{$category->name|escape}{/block}

{block name="content"}
    <section class="category" data-category-id="{$category->id}">
        <header class="category__plate">
            <p class="category__eyebrow">Категория</p>
            <h1 class="category__name">{$category->name|escape}</h1>
            {if $category->description}
                <p class="category__description">{$category->description|escape}</p>
            {/if}
        </header>

        <div class="catalog-bar">
            <p class="catalog-bar__count">
                Материалов: <span class="catalog-bar__value">{$pagination->totalItems}</span>
            </p>
            <form class="sorter" method="get" action="/category" data-auto-submit>
                <input type="hidden" name="slug" value="{$category->slug|escape}">
                <label class="visually-hidden" for="post-sort">Порядок</label>

                <span class="sorter__field">
                    <select class="sorter__select" id="post-sort" name="sort">
                        {foreach $sortOptions as $value => $label}
                            <option value="{$value|escape}"{if $value === $sort} selected{/if}>{$label|escape}</option>
                        {/foreach}
                    </select>

                    <svg class="sorter__chevron" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m4 6.5 4 4 4-4"/>
                    </svg>
                </span>

                <button class="sorter__apply" type="submit">Показать</button>
            </form>
        </div>

        {if $posts}
            <ol class="catalog">
                {foreach $posts as $post}
                    <li>{include file='partials/post-card.tpl' post=$post}</li>
                {/foreach}
            </ol>

            {if $pagination->totalPages > 1}
                <nav class="pager" aria-label="Страницы категории">
                    {if $pagination->previousPage}
                        <a class="pager__step" rel="prev" href="/category?slug={$category->slug|escape:'url'}&amp;sort={$sort|escape:'url'}&amp;page={$pagination->previousPage}">← Назад</a>
                    {else}
                        <span class="pager__step pager__step--off" aria-disabled="true">← Назад</span>
                    {/if}
                    <p class="pager__position">Страница {$pagination->currentPage} из {$pagination->totalPages}</p>
                    {if $pagination->nextPage}
                        <a class="pager__step" rel="next" href="/category?slug={$category->slug|escape:'url'}&amp;sort={$sort|escape:'url'}&amp;page={$pagination->nextPage}">Вперёд →</a>
                    {else}
                        <span class="pager__step pager__step--off" aria-disabled="true">Вперёд →</span>
                    {/if}
                </nav>
            {/if}
        {else}
            <div class="empty-plate">
                <p class="empty-plate__title">В этой категории пока нет статей.</p>
                <a class="button" href="/">Вернуться на главную</a>
            </div>
        {/if}
    </section>
{/block}

{block name="scripts"}
    <script>
        document.querySelectorAll('[data-auto-submit]').forEach((form) => {
            form.classList.add('sorter--auto');

            form.querySelector('select').addEventListener('change', () => form.submit());
        });
    </script>
{/block}
