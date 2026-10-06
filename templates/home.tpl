{extends file='layout.tpl'}

{block name="title"}Блог{/block}

{block name="content"}
    <h1 class="index__title">Свежее в каждом разделе</h1>

    {foreach $categories as $rubric}
        <section class="rubric" data-category-id="{$rubric.category->id}">
            <header class="rubric__head">
                <div class="rubric__identity">
                    <p class="rubric__eyebrow">Раздел</p>

                    <h2 class="rubric__name">
                        <a class="rubric__link"
                           href="/category?slug={$rubric.category->slug|escape:'url'}">{$rubric.category->name|escape}</a>
                    </h2>
                </div>

                <a class="button rubric__all" href="/category?slug={$rubric.category->slug|escape:'url'}">Все статьи</a>
            </header>

            <ol class="rubric__posts">
                {foreach $rubric.posts as $post}
                    <li>
                        <article class="teaser" data-post-id="{$post->id}">
                            {if $post->image}<img class="teaser__image" src="{$post->image|escape}" alt="" loading="lazy">{/if}

                            <p class="teaser__date">
                                <time datetime="{$post->publishedAt->format('c')|escape}">{$post->publishedAt->format('d.m.Y')}</time>
                            </p>

                            <h3 class="teaser__title">
                                <a class="teaser__link" href="/post?slug={$post->slug|escape:'url'}">{$post->title|escape}</a>
                            </h3>

                            <p class="teaser__description">{$post->description|escape}</p>
                        </article>
                    </li>
                {/foreach}
            </ol>
        </section>
    {foreachelse}
        <div class="empty-plate">
            <p class="empty-plate__title">Пока ничего не опубликовано.</p>
        </div>
    {/foreach}
{/block}
