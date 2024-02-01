## Pastebin

Simple pastebin inspired by **[@kadet1090](https://github.com/kadet1090)**'s project. Features
[KeyLighter][keylighter] for highlighting code snippets.

For live demo please visit [paste.sobak.pl](http://paste.sobak.pl)

![Screenshot](https://i.imgur.com/mcwzkPf.png)

### Features

- the best PHP syntax highlighter out there: [KeyLighter][keylighter]
  - support for over 35 languages
  - context-aware syntax highlighting
  - nested (e.g. PHP nested in HTML) highlighting support
- creating, updating and removing pastes
- uploading pastes via multipart POST (e.g. for integrating with ShareX, see below)
- admin panel
- downloading pastes
- showing raw paste representation

### Setup

In order to set up the project you have to:

1. Install the dependencies with `composer install`
2. Fill in `.env` file (or set these env variables in the system) based on `.env.example`.
   **Make sure** to always set `ADMIN_USERNAME` & `ADMIN_PASSWORD` envs for production,
   otherwise your admin panel will be publicly accessible!
3. Run database migrations with `php artisan migrate`

### Local development

You need to perform setup similar to the one described above (you can omit the `ADMIN_*` env
vars, though) but you can also

- use local Docker containers setup (`docker-compose up -d`)
- generate IDE helpers for Laravel's magic (`composer ide-helpers`)

Tests can be run with `./vendor/bin/phpunit`

### ShareX support

You can use this project together with [ShareX](https://getsharex.com) by providing
configuration similar to the below:

![ShareX config](http://i.imgur.com/It9I8fa.png)


[keylighter]: https://keylighter.kadet.net
