Cache
=======================
#### The Epiphany PHP Framework

----------------------------------------

### Understanding and using the cache module

When using the cache module you can select between APC or Memcached. The interface to both are identical so you can switch between them at anytime and in different environments.

    Epi::init('cache');
    EpiCache::employ(EpiCache::APC);
    getCache()->set('name', 'value');
    $name = getCache()->get('name');

First you'll need to include the cache module and specify which caching engine you'd like to use. You can get a singleton instance of the caching object by calling `EpiCache::getInstance()` which takes either `EpiCache::APC`, `EpiCache::MEMCACHE` or `EpiCache::MEMCACHED` as a parameter.

----------------------------------------

### Available methods

The available methods are `get`, `set` and `delete`.

    get($name);
    set($name, $value[, $ttl]);
    delete($name);

The default value for `$ttl` is 0 which means it will be stored forever. For the Memcached engine the `$ttl` can be seconds from the current time as long as it is less than `60*60*24*30` (seconds in 30 days) otherwise it needs to be a Unix timestamp.

----------------------------------------

### Memcache vs Memcached

The original fork of epiphany only supported the `Memcached` class for memcache handling. This requirement has now been lifted. Depending on the Module you have enabled you can choose which class to use:

    EpiCache::employ(EpiCache::MEMCACHE); // for Memcache
    EpiCache::employ(EpiCache::MEMCACHED); // for Memcached

Also, as with the [Database][database] module the settings for the memcache server are now passed on when emplying the `EpiCache`.

    EpiCache::employ(EpiCache::MEMCACHE, 'localhost', 11211, 3600); // for Memcache

[database]: https://github.com/mwidmann/epiphany/blob/master/docs/Database.markdown