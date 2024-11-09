<?xml version="1.0"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:media="http://search.yahoo.com/mrss/"
xmlns:georss="http://www.georss.org/georss" version="2.0">
    <channel>
        <atom:link href="{{ url('feed') }}" rel="self" type="application/rss+xml"/>
        <title>NewsHub.kz</title>
        <link>{{ config('app.origin') }}</link>
        <description>Информационный хаб NewsHub.kz  —  это интернет-площадка для эффективного взаимодействия пресс-служб организаций со средствами массовой информации.</description>
        <language>ru</language>
        <lastBuildDate>{{ now()->toAtomString() }}</lastBuildDate>
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ config('app.origin') }}/post/{{ $post->slug }}</link>
                <guid>{{ config('app.origin') }}/post/{{ $post->slug }}</guid>
                <category>{{ $post->categories()->first()?->name ?? '' }}</category>
                <pubDate>{{ $post->created_at->toAtomString() }}</pubDate>
                <description>{{ $post->summary }}</description>
                <content type="html">
                    <![CDATA[{!! $post->content !!}]]>
                </content>
                <author>{{ $post->user?->name ?? '' }}</author>
                <media:thumbnail url="{{ asset("storage/{$post->image}")}}"/>
            </item>
        @endforeach
    </channel>
</rss>