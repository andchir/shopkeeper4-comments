# Reviews and ratings for Symfony4

![Comments - screenshot #1](https://github.com/andchir/shopkeeper4-comments/blob/master/Resources/docs/screenshots/screenshot001.png?raw=true "Comments - screenshot #1")

Can be used in Shopkeeper4 and in other applications using Symfony 4.

## Installing

Create classes for your entities or use these examples:
~~~
vendor/andchir/shopkeeper4-comments/Document/Comment.php.dist
vendor/andchir/shopkeeper4-comments/Repository/CommentRepository.php.dist
~~~

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

### Installing component for Shopkeeper4

Add to config/resources/admin_menu.yaml
~~~
- { title: 'COMMENTS', route: '/module/comments', icon: 'icon-message-circle' }
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

### Development

Build for development mode:
~~~
ng build comments --baseHref="/admin/module/comments/" \
--deployUrl="/bundles/comments/admin/bundle-dev/" \
--outputPath="../public/bundles/comments/admin/bundle-dev" --watch=true
~~~

Build for production:
~~~
ng build comments --prod --baseHref="/admin/module/comments/" \
--deployUrl="/bundles/comments/admin/bundle/" \
--outputPath="../public/bundles/comments/admin/bundle"
~~~
