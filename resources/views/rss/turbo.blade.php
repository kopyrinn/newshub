<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">
    <channel>
        <title>Newshub.kz</title>
        <link>https://newshub.kz/</link>
        <description>Новости Казахстана и пресс служб</description>
        <language>ru</language>
        <turbo:analytics type="Yandex" id="86241754"></turbo:analytics>
        <turbo:analytics type="Google" id="UA-209786162-1"></turbo:analytics>
        <yandex:related>
	    @foreach( $randoms as $random )
	        <link url="{{ url('post', $random->slug) }}" img="{{ asset('storage/' . $random->image) }}">{{ $random->heading }}</link>
	    @endforeach
        </yandex:related>
        @foreach($posts as $post)
            <item turbo="true">
                <turbo:extendedHtml>true</turbo:extendedHtml>
                <link>{{ url("post/{$post->slug}") }}</link>
                <pubDate>{{ $post->created_at->toRssString() }}</pubDate>
                <author>{{ $post->user->name  }}</author>
                <turbo:content>
                    <![CDATA[
                        <header>
                            <h1><title>{{ $post->title }}</title></h1>
                            <figure><img src="{!! url("storage/{$post->image}") !!}"></figure>
                            
                        </header>
                        {!! $post->content !!}
                    ]]>
                
                </turbo:content>
            </item>
        @endforeach
    </channel>
</rss>