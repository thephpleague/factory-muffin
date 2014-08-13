---
layout: layout
title: Usage - The Facade
permalink: usage/facade/
---

# Usage - The Facade

The facade class (`League\FactoryMuffin\Facade`) should always be your main point of entry for communicating with Factory Muffin. It will dynamically proxy static method calls to the underlying factory instance. The other classes, including the factory class (`League\FactoryMuffin\Factory`), are not intended for direct public use. Also, note that all public methods that would have returned void, return the factory instance in order to support method chaining.
