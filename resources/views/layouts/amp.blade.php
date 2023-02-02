<!doctype html>
<html amp lang="ru">

<head>
    <meta charset="utf-8">
    <title>@yield('title', nova_get_setting('title'))@hasSection('title') | {{ nova_get_setting('title') }}@endif</title>
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="canonical" href="{{ url("post/{$post->slug}") }}">

    <style amp-custom>
        body {background-color: #fff;font-family: Roboto,sans-serif;}.container {padding: 20px 10px 0 10px;max-width: 600px;margin: 0 auto;}.post_info {padding-top: 60px;font-size: 12px;}.image_copyright {font-size: 12px;}.description {padding-top: 10px;font-weight: bold;text-align: justify;}.content {padding-top: 10px; text-align: justify} .content img {width: 100%; object-fit: contain; height: 50%;}.tags {padding-top: 5px;margin-bottom: 20px;}
        .btn {
    background-color: #336cda;
    display: block;
    text-align: center;
    color: #fff;
    font-weight: 700;
    font-size: 18px;
    padding: 14px 0;
    border-radius: 10px;
    margin-top: 15px;
    margin-bottom: 15px;
    text-decoration: underline;
        }
        .hamburger {
            margin: 0 10px 0 auto;
}
.sidebar {
  padding: 10px;
  margin: 0;
  width: 120px;
}
.sidebar > li {
  list-style: none;
  margin-bottom:10px;
}
.sidebar a {
  text-decoration: none;
  color: #000;
}
.close-sidebar {
  font-size: 1.5em;
  padding-left: 5px;
}
.home-button {
  margin-top: 8px;
}
.headerbar {
  height: 50px;
  position: fixed;
  z-index: 999;
  top: 0;
  width: 100%;
  display: flex;
  align-items: center;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0,0,0,0.5);
}
.site-name {
  margin-left: 20px;
}

.social {
    padding-top: 10px;
    margin: 0 auto;
}


  /* a custom sharing icon */
  amp-social-share.custom-style {
    /* background-color: #008080;*/
    background-image: url('https://raw.githubusercontent.com/google/material-design-icons/master/social/1x_web/ic_share_white_48dp.png');
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
  }
  /* blue icons with rounded corners  */
  amp-social-share.rounded {
    border-radius: 50%;
    background-size: 60%;
  }
  
  
    </style>
    @stack('header')
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
</head>

<body>
    @yield('content')
    
<!-- ga -->
<amp-analytics type="googleanalytics" id="analytics">
    <script type="application/json">
    {
      "vars": {
        "account": "UA-209786162-1"
      },
      "triggers": {
        "trackPageview": {
          "on": "visible",
          "request": "pageview"
        }
      }
    }
    </script>
</amp-analytics>
<!-- end ga -->

<!-- ya.metrika -->
<amp-analytics type="metrika">
    <script type="application/json">
        {
            "vars": {
                "counterId": "86241754"
            }
        }
    </script>
</amp-analytics>
<!-- end ya.metrika -->
</body>
</html>