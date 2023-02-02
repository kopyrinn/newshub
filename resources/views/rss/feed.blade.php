<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title>Последние новости Newshub.kz</title>
        <link>https://newshub.kz/</link>
        <description>Новости Казахстана и пресс служб</description>
        <language>ru</language>
        <pubDate>{{ $now = date('r'); }}</pubDate>
        <link xmlns="http://www.w3.org/2005/Atom" rel="self" href="https://newshub.kz/rss"/>
            

        @foreach($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ url("post/{$post->slug}") }}</link>
                <description><![CDATA[
                <img src="{!! url("storage/{$post->image}") !!}"> {!! $post->content !!}
                ]]></description>
                <category>{{ $post->categories()->first()->name }}</category>
                <managingEditor>{{ $post->user->email  }} ({{ $post->user->name  }})></managingEditor>
                <guid>{{ url("post/{$post->slug}") }}</guid>
                <pubDate>{{ $post->created_at->toRssString() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>