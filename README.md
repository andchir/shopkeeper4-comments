
## Installing

Add to templates/default/content-page.html.twig:
~~~
{% block stylesheets -%}
    {{ parent() }}
    <link href="{{ asset('bundles/comments/css/comments.css') }}" rel="stylesheet">
{% endblock -%}
~~~

~~~
{% include '@Comments/Default/async.html.twig' with {'id': currentId} only %}
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


