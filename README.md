# SWeTE Server

SWeTE (Simple Website Translation Engine) is an open source translation proxy written in PHP, using MySQL for its translation memory.

* [SWeTE Homepage](http://swete.weblite.ca)
* [Introductory Tutorial](docs/phparch_article/page.markdown) - (An article originally published in PHP Architect)
* [Users Guide](https://shannah.github.com/swete)

## Quick Start (Docker)

The easiest way to run SWeTE is with Docker:

```bash
git clone https://github.com/shannah/swete.git
cd swete
docker compose up
```

Then open http://localhost:8080/swete-admin/ in your browser.

To use a different port:

```bash
SWETE_PORT=9090 docker compose up
```

To run multiple instances on the same machine, use different ports and project names:

```bash
SWETE_PORT=8080 docker compose -p swete1 up
SWETE_PORT=9090 docker compose -p swete2 up
```

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `SWETE_PORT` | `8080` | Host port for the web interface |
| `DB_HOST` | `db` | MySQL/MariaDB hostname |
| `DB_NAME` | `swete` | Database name |
| `DB_USER` | `swete` | Database user |
| `DB_PASSWORD` | `swete` | Database password |

## Manual Installation

### Requirements

* PHP 7.0+
* MySQL 5+ or MariaDB 10+
* Apache 1.3+ with mod_rewrite

See the [Users Guide](https://shannah.github.com/swete) for manual installation instructions.

## License

GPLv2

## Credits

SWeTE Server is developed by [Web Lite Translation Corp.](http://translate.weblite.ca). 