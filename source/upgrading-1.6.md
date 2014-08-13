---
layout: layout
title: Upgrading from 1.5.x to 1.6.x
permalink: upgrading/1.6/
---

# Upgrading from 1.5.x to 1.6.x

## Faker Usage

We now use the faker package, so our `Zizaco\FactoryMuff\Wordlist` class has been removed. All your previous definitions should still work in as close to the same way as possible, but watch out for any minor differences. With the addition of the faker library, far more definitions are now possible since any definitions not natively provided by us, fall back to the faker package. Also, it should be noted you may use closures now to generate completely custom attributes. The new classes can be found under the `Zizaco\FactoryMuff\Kind` namespace.

## Installing This Version

In your composer.json, add:
```json
{
    "require-dev": {
        "league/factory-muffin": "1.6.*"
    }
}
```
