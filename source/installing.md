---
layout: layout
title: Installing
permalink: installing/
---

# Installing

[PHP](https://php.net) 5.4+ and [Composer](https://getcomposer.org) are required.

In your composer.json, simply add `"league/factory-muffin": "^3.0"` to your `"require-dev"` section:
```json
{
    "require-dev": {
        "league/factory-muffin": "^3.0"
    }
}
```

[Faker](https://github.com/fzaninotto/Faker) support is provided by [Factory Muffin Faker](https://github.com/thephpleague/factory-muffin-faker). If you want to enable faker support, then you need to add `"league/factory-muffin-faker": "^2.0"` too:
```json
{
    "require-dev": {
        "league/factory-muffin": "^3.0",
        "league/factory-muffin-faker": "^2.0"
    }
}
```
