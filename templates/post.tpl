{extends file='layout.tpl'}

{block name="title"}{$post->title|escape}{/block}

{block name="content"}
    <article class="post" data-post-id="{$post->id}">
        <header class="post__header">
            <div class="post__meta">
                <div class="post__filing">
                    {if $categories}
                        <ul class="categories">
                            {foreach $categories as $category}
                                <li class="categories__item" data-category-id="{$category->id}">
                                    <a class="categories__link" href="/category?slug={$category->slug|escape:'url'}"
                                       title="{$category->description|escape}">{$category->name|escape}</a>
                                </li>
                            {/foreach}
                        </ul>
                    {/if}

                    <time class="post__date" datetime="{$post->publishedAt->format('c')|escape}">{$post->publishedAt->format('d.m.Y')}</time>
                </div>

                <div class="post__actions">
                    <p class="tally">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M1.5 12S5 5.5 12 5.5 22.5 12 22.5 12 19 18.5 12 18.5 1.5 12 1.5 12Z"/>
                            <circle cx="12" cy="12" r="3.2"/>
                        </svg>
                        {$post->getViews()}<span class="visually-hidden"> просмотров</span>
                    </p>

                    <button class="share" type="button" data-share>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 15.5V3.5"/>
                            <path d="m8 7.5 4-4 4 4"/>
                            <path d="M5 13v6.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V13"/>
                        </svg>
                        <span data-share-label>Поделиться</span>
                    </button>
                </div>
            </div>

            <h1>{$post->title|escape}</h1>
            <p class="post__lead">{$post->description|escape}</p>
        </header>

        {if $post->image}
        <figure class="post__cover">
            <img src="{$post->image|escape}" alt="{$post->title|escape}">
        </figure>
        {/if}

        <div class="post__content">{$post->content|escape}</div>
    </article>

    {if $similar_posts}
        <section class="related" aria-labelledby="similar-posts-title">
            <div class="related__heading">
                <h2 class="label" id="similar-posts-title">Похожие статьи</h2>
            </div>

            <ol class="related__list">
                {foreach $similar_posts as $similarPost}
                    <li>{include file='partials/post-card.tpl' post=$similarPost}</li>
                {/foreach}
            </ol>
        </section>
    {/if}
{/block}

{block name="scripts"}
    <script>
        document.querySelectorAll('[data-share]').forEach((button) => {
            const label = button.querySelector('[data-share-label]');
            const initialLabel = label.textContent;

            button.addEventListener('click', async () => {
                const url = window.location.href;

                if (navigator.share) {
                    try {
                        await navigator.share({ title: document.title, url });
                    } catch {
                        // Dismissing the native share sheet needs no feedback.
                    }

                    return;
                }

                try {
                    await navigator.clipboard.writeText(url);
                } catch {
                    return;
                }

                label.textContent = 'Ссылка скопирована';
                button.classList.add('share--copied');

                setTimeout(() => {
                    label.textContent = initialLabel;
                    button.classList.remove('share--copied');
                }, 2000);
            });
        });
    </script>
{/block}
