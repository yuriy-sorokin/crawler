# Crawler
This package downloads a HTML part of a web page and follows its links to download related pages into ./storage directory.

## Usage
```bash
php ./composer.phar install

php bin/app.php --url=https://spiegel.de
```

By default, it downloads 100 pages with 5 parallel processes.

To change that, provide additional CLI arguments:

```bash
php bin/app.php --url=https://spiegel.de --max-pages=500 --max-processes=30
```
