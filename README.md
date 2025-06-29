# PHP Singleton Container
A minimal and testable singleton container for managing and retrieving shared instances in PHP.

---

## 📦 Installation

```bash
composer require welbertymartins/php-singleton
````

---

## 🚀 Quick Start

```php
use WelbertyMartins\Singleton\Singleton;

// Store a shared object in the global singleton container
Singleton::root()->remember(fn() => new MyService());

// Retrieve it later
$service = Singleton::root()->make(MyService::class);
```

Or use an isolated container:

```php
$container = Singleton::local();

$container->remember(fn() => new MyService(), 'custom_service');

$service = $container->make('custom_service');
```

---

## 🧪 Running Tests

```bash
composer install
composer test
```

> Includes PHPUnit tests, PSR-12 code style checking, and static analysis via Psalm.

---

## 📁 Project Structure

```
src/     → Main singleton implementation  
test/   → PHPUnit test suite  
```

---

## ✅ Features

* 🧩 Global and isolated singleton instances
* 🛡️ Strict type safety
* ⚡ Lightweight, zero-dependency
* ✅ PSR-4 autoloading
* 🧪 Fully tested

---

## 📘 API Overview

### `Singleton::root(): Singleton`

Returns the globally shared singleton instance.

### `Singleton::local(): Singleton`

Returns a new isolated singleton instance.

### `remember(callable $factory, string $name = '', bool $force = false): self`

Stores an object returned by a factory under a given name (or class name by default).

### `make(string $name = ''): ?object`

Retrieves a stored instance by name, or the last remembered instance.

---

## 📄 License

This project is open-sourced under the [MIT license](LICENSE).

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

1. Fork this repository
2. Create a feature branch (`git checkout -b feature/your-feature`)
3. Submit a pull request 🚀

---

## 🔗 Useful Links

* 📦 [Packagist](https://packagist.org/packages/welbertymartins/php-singleton)

```

---

Would you like me to create the `LICENSE`, `.gitignore`, `.gitattributes`, and `GitHub Actions` workflow file (`test.yml`) next?
```
