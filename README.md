# Reviews and ratings for Symfony4

![Comments - screenshot #1](https://github.com/andchir/shopkeeper4-comments/blob/master/Resources/docs/screenshots/screenshot001.png?raw=true "Comments - screenshot #1")

## Installing

Add to templates/default/content-page.html.twig:
~~~
{% block stylesheets -%}
    {{ parent() }}
    <link href="{{ asset('bundles/comments/css/comments.css') }}" rel="stylesheet">
{% endblock -%}
~~~

~~~
{% include '@Comments/Default/async.html.twig' with {'threadId': currentCategory.contentTypeName ~ '_' ~ currentId} only %}
~~~

### Manual installing

composer.json
~~~
"autoload": {
    "psr-4": {
        ...
        "Andchir\\CommentsBundle\\": "vendor/andchir/shopkeeper4-comments/"
    }
},
~~~

config/bundles.php
~~~
Andchir\CommentsBundle\CommentsBundle::class => ['all' => true]
~~~


