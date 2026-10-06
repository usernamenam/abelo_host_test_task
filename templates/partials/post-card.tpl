<article class="card" data-post-id="{$post->id}">
    {if $post->image}<img class="card__image" src="{$post->image|escape}" alt="" loading="lazy">{/if}

    <div class="card__body">
        <h3 class="card__title">
            <a class="card__link" href="/post?slug={$post->slug|escape:'url'}">{$post->title|escape}</a>
        </h3>
        <p class="card__description">{$post->description|escape}</p>
    </div>

    <p class="card__record">
        <time datetime="{$post->publishedAt->format('c')|escape}">{$post->publishedAt->format('d.m.Y')}</time>
        <span>{$post->getViews()} просмотров</span>
    </p>
</article>
