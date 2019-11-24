

~~~
"autoload": {
    "psr-4": {
        ...
        "Andchir\\CommentsBundle\\": "vendor/andchir/shopkeeper4-comments/"
    }
},
~~~

Add to templates/default/content-page.html.twig:
~~~
{% include '@Comments/Default/async.html.twig' with {'id': currentId} only %}
~~~


