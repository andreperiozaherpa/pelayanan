---
name: lerd
description: Manage the lerd local PHP development environment — run framework console commands (artisan, bin/console, etc.), manage services, start/stop queue workers, run composer, manage Node.js versions, and inspect site status via MCP tools.
---
# Lerd — Laravel Local Dev Environment

This project runs on **lerd**, a Podman-based Laravel development environment for Linux (similar to Laravel Herd). The `lerd` MCP server exposes tools to manage it directly from your AI assistant.

## Path resolution

Tools that accept a `path` argument (`artisan`, `composer`, `env_setup`, `env_check`, `db_set`, `site_link`, `site_unlink`, `site_domain_add`, `site_domain_remove`, `db_export`, `db_import`, `db_create`, etc.) resolve it in this order:
1. Explicit `path` argument
2. `LERD_SITE_PATH` env var (set when using project-scoped `mcp:inject`)
3. **Current working directory** — the directory Claude was opened in

In practice, you can almost always omit `path` — just open Claude in the project directory.

## Architecture

- PHP runs inside Podman containers named `lerd-php<version>-fpm` (e.g. `lerd-php84-fpm`)
- Each PHP-FPM container includes **composer** and **node/npm** so you can run all tooling without leaving the container
- Nginx routes `*.test` domains to the appropriate FPM container
- Services (MySQL, Redis, PostgreSQL, etc.) run as Podman containers via systemd quadlets
- Custom services (MongoDB, RabbitMQ, …) can be added with `service_add` and managed identically to built-in ones
- Node.js versions are managed by **fnm** (Fast Node Manager); pin per-project with a `.node-version` file
- Framework workers (queue, schedule, reverb, messenger, etc.) run as systemd user services named `lerd-<worker>-<sitename>` (e.g. `lerd-queue-myapp`, `lerd-messenger-myapp`)
- Worker commands are defined per-framework in YAML definitions; Laravel has built-in queue/schedule/reverb workers; custom frameworks can add any workers; both workers and setup commands support an optional `check` field (`file` or `composer`) to conditionally show them based on project dependencies
- Framework definitions can include `setup` commands (one-off bootstrap steps like migrations, storage links) shown in `lerd setup`; Laravel has built-in storage:link/migrate/db:seed
- **Custom containers**: non-PHP sites (Node.js, Python, Go, etc.) can define a `Containerfile.lerd` and a `container:` section in `.lerd.yaml` with a port. Lerd builds a per-project image (`lerd-custom-<sitename>:local`), runs it as `lerd-custom-<sitename>`, and nginx reverse-proxies to it. Workers exec into the custom container. Services are accessible by name (`lerd-mysql`, `lerd-redis`, etc.) on the shared `lerd` Podman network.
- Git worktrees automatically get a `<branch>.<site>.test` subdomain; `vendor/`, `node_modules/`, and `.env` are symlinked/copied from the main checkout
- DNS resolves `*.test` to `127.0.0.1`

## Available MCP Tools

### `sites`
List all registered lerd sites with domains, paths, PHP versions, Node versions, and queue status. **Call this first** to find site names and paths needed by other tools.

### `runtime_versions`
List all installed PHP and Node.js versions and the configured defaults. Call this to check what runtimes are available before running commands.

### `php_list`
List all PHP versions installed by lerd as JSON, with each version's `default` flag. Use this to confirm which versions are available before calling `site_php`, `php_ext_add`, or `xdebug_on`.

### `php_ext_list` / `php_ext_add` / `php_ext_remove`
Manage custom PHP extensions for a PHP version. Extensions are added on top of the bundled lerd FPM image. Adding or removing an extension rebuilds the image and restarts the FPM container (may take a minute).

Optional `version` argument on all three — defaults to the project or global PHP version.

`php_ext_add` and `php_ext_remove` take `extension` (required).

Examples:
```
php_ext_list()                              // list extensions for current project's PHP version
php_ext_list(version: "8.4")               // list extensions for 8.4
php_ext_add(extension: "imagick")          // add imagick to current project's PHP version
php_ext_add(extension: "redis", version: "8.3")
php_ext_remove(extension: "imagick")
```

### `artisan` (Laravel only)
Run `php artisan` inside the PHP-FPM container for the project. Only available when the site is detected as Laravel. Arguments:
- `path` (optional): absolute path to the Laravel project root — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)
- `args` (required): artisan arguments as an array

Examples:
```
artisan(args: ["migrate"])
artisan(args: ["make:model", "Post", "-m"])
artisan(args: ["db:seed", "--class=UserSeeder"])
artisan(args: ["cache:clear"])
artisan(args: ["tinker", "--execute=echo App\\Models\\User::count();"])
```

> **Note:** `tinker` requires `--execute=<code>` for non-interactive use.

### `console` (non-Laravel frameworks)
Run the framework's console command (e.g. `php bin/console` for Symfony) inside the PHP-FPM container. Only available for non-Laravel frameworks that define a `console` field in their YAML definition. Arguments:
- `path` (optional): absolute path to the project root — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)
- `args` (required): console arguments as an array

Example — Symfony:
```
console(args: ["cache:clear"])
console(args: ["doctrine:migrations:migrate"])
console(args: ["messenger:consume", "async", "--time-limit=60"])
```

### `composer`
Run `composer` inside the PHP-FPM container for the project. Arguments:
- `path` (optional): absolute path to the Laravel project root — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)
- `args` (required): composer arguments as an array

Examples:
```
composer(args: ["install"])
composer(args: ["require", "laravel/sanctum"])
composer(args: ["dump-autoload"])
composer(args: ["update", "laravel/framework"])
```

### `vendor_bins` / `vendor_run`
Discover and execute composer-installed binaries from the project's `vendor/bin` directory inside the PHP-FPM container. Use `vendor_bins` first to see what tooling is available (pest, phpunit, pint, phpstan, rector, paratest, psalm, etc.), then `vendor_run` to invoke one. Both accept an optional `path` argument that defaults to the current site.

Arguments:
- `vendor_bins(path?)` — returns the sorted list of executables in `vendor/bin`
- `vendor_run(path?, bin, args?)` — runs `php vendor/bin/<bin> [args]` inside the FPM container; `bin` must be a plain filename, not a path

Examples:
```
vendor_bins()                                      // list available tools
vendor_run(bin: "pest")                            // run the full pest suite
vendor_run(bin: "pest", args: ["--filter", "UserTest"])
vendor_run(bin: "phpunit", args: ["--testsuite", "Feature"])
vendor_run(bin: "pint", args: ["--test"])          // dry-run pint
vendor_run(bin: "phpstan", args: ["analyse", "--memory-limit=2G"])
vendor_run(bin: "rector", args: ["process", "--dry-run"])
```

Prefer `vendor_run` over `composer(args: ["exec", ...])` — it's faster, doesn't go through composer's plugin pipeline, and the same shortcut is available on the CLI as `lerd <bin>` (e.g. `lerd pest`, `lerd pint`).

### `node_install` / `node_uninstall`
Install or uninstall a Node.js version via fnm. Accepts a version number or alias:
```
node_install(version: "20")
node_install(version: "20.11.0")
node_install(version: "lts")
node_uninstall(version: "18.20.0")
```

After installing a version you can pin it to a project by writing a `.node-version` file in the project root (or run `lerd isolate:node <version>` from a terminal).

### `service_start` / `service_stop`
Start or stop any service — built-in or custom. `service_stop` marks the service as **paused** — `lerd start` and autostart on login will skip it until you explicitly start it again.

**Dependency cascade:** if a custom service has `depends_on` set, starting its dependency also starts it; stopping the dependency stops it first. Starting the custom service directly ensures its dependencies start first.

Built-in names: `mysql`, `redis`, `postgres`, `meilisearch`, `rustfs`, `mailpit`. Custom service names (registered with `service_add`) are also accepted — just pass the same name used in `service_add`.

**.env values for built-in lerd services:**

| Service | Host | Key vars |
|---------|------|----------|
| mysql | `lerd-mysql` | `DB_CONNECTION=mysql`, `DB_PASSWORD=lerd` |
| postgres | `lerd-postgres` | `DB_CONNECTION=pgsql`, `DB_PASSWORD=lerd` |
| redis | `lerd-redis` | `REDIS_PASSWORD=null` |
| mailpit | `lerd-mailpit:1025` | web UI: http://localhost:8025 |
| meilisearch | `lerd-meilisearch:7700` | |
| rustfs | `lerd-rustfs:9000` | `AWS_USE_PATH_STYLE_ENDPOINT=true` |

### `service_expose`
Add or remove an extra published port on a built-in service. The mapping is persisted in `~/.config/lerd/config.yaml` and applied on every start. The service is restarted automatically if running.

Arguments:
- `name` (required): built-in service name (`mysql`, `redis`, `postgres`, `meilisearch`, `rustfs`, `mailpit`)
- `port` (required): mapping as `"host:container"`, e.g. `"13306:3306"`
- `remove` (optional): set to `true` to remove the mapping instead of adding it

Examples:
```
service_expose(name: "mysql", port: "13306:3306")
service_expose(name: "mysql", port: "13306:3306", remove: true)
```

### `service_add` / `service_remove`
Register or remove a custom OCI-based service. Arguments for `service_add`:
- `name` (required): slug, e.g. `"mongodb"`
- `image` (required): OCI image, e.g. `"docker.io/library/mongo:7"`
- `ports` (optional): array of `"host:container"` mappings
- `environment` (optional): array of `"KEY=VALUE"` strings for the container
- `env_vars` (optional): array of `"KEY=VALUE"` strings shown in `lerd env` suggestions
- `data_dir` (optional): mount path inside container for persistent data
- `description` (optional): human-readable description
- `dashboard` (optional): URL for the service's web UI
- `depends_on` (optional): array of service names that must be running before this service starts, e.g. `["mysql"]`

When `depends_on` is set:
- Starting this service automatically starts its dependencies first
- Starting a dependency automatically starts this service afterwards
- Stopping a dependency automatically stops this service first (cascade stop)

Example — add MongoDB:
```
service_add(
  name: "mongodb",
  image: "docker.io/library/mongo:7",
  ports: ["27017:27017"],
  data_dir: "/data/db",
  env_vars: ["MONGODB_URL=mongodb://lerd-mongodb:27017"]
)
service_start(name: "mongodb")
```

Example — add phpMyAdmin depending on MySQL:
```
service_add(
  name: "phpmyadmin",
  image: "docker.io/phpmyadmin:latest",
  ports: ["8080:80"],
  depends_on: ["mysql"],
  dashboard: "http://localhost:8080"
)
service_start(name: "phpmyadmin")   // starts mysql first, then phpmyadmin
```

`service_remove` stops and deregisters a custom service. Persistent data is NOT deleted.

### `service_preset_list` / `service_preset_install`
Lerd ships a small catalogue of opt-in **service presets** — bundled YAML definitions for common dev services that become normal custom services once installed. Use `service_preset_list` to see what's available and `service_preset_install` to install one. Prefer this over hand-rolling `service_add` for anything in the catalogue: presets ship sane defaults, dependency wiring, dashboard URLs, and (where relevant) rendered config files.

Current catalogue: `phpmyadmin` (depends on built-in mysql), `pgadmin` (depends on built-in postgres, ships a pre-loaded servers.json + pgpass), `mongo`, `mongo-express` (depends on the `mongo` preset), `selenium` (Chromium for browser testing — Dusk, Panther, etc.), `stripe-mock`. Some presets (e.g. `mysql`, `mariadb`) declare multiple versions in a single family — pass `version` to pick one, otherwise lerd installs the family default.

Arguments:
- `service_preset_list()` — returns each preset with its image, declared versions, dependencies, dashboard URL, and an `installed` flag
- `service_preset_install(name, version?)` — installs a preset by name; `version` is required only for multi-version families when you want a specific tag

Examples:
```
service_preset_list()
service_preset_install(name: "phpmyadmin")           // adds phpmyadmin, mysql is built-in
service_preset_install(name: "mongo")                // install mongo first…
service_preset_install(name: "mongo-express")        // …then mongo-express (gated otherwise)
service_preset_install(name: "mysql", version: "8.4")
service_start(name: "phpmyadmin")                    // mysql is started automatically
```

**Dependency gating:** installing a preset whose dependency is another *custom* service (e.g. `mongo-express` on `mongo`) is rejected with a clear error until the dependency is installed first. Built-in deps (mysql, postgres) are auto-satisfied.

Once installed, presets are normal custom services — manage them with `service_start`, `service_stop`, `service_remove`, `service_expose`, `service_pin`.

### `service_env`
Return the recommended Laravel `.env` connection variables for a service — built-in or custom — as a key/value map. Use this when you need to inspect or manually apply connection settings without running `env_setup`.

### `env_setup`
Configure the project's `.env` for lerd in one call:
- Creates `.env` from `.env.example` if it doesn't exist
- Detects which services (MySQL, Redis, …) the project uses and sets lerd connection values
- Starts any referenced services that aren't running
- Creates the project database (and `<name>_testing` database)
- Generates `APP_KEY` if missing
- Sets `APP_URL` (or the framework's URL key) using the precedence chain: `.lerd.yaml` `app_url` → `sites.yaml` `app_url` → default `<scheme>://<primary-domain>` — see "Custom APP_URL" below

Arguments:
- `path` (optional): absolute path to the Laravel project root — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)

> Run this right after `site_link` when setting up a fresh project.
>
> **Database default:** on a fresh Laravel clone where `.env` still says `DB_CONNECTION=sqlite`, `env_setup` leaves the database choice alone. Call `db_set` first to pick `mysql` / `postgres` / `sqlite` deliberately, then `env_setup` (or just `db_set` alone — it already runs the env step).

### `db_set`
Pick the database for a Laravel project. Persists the choice to `.lerd.yaml` (replacing any prior database entry — the choice is exclusive), rewrites the relevant `DB_` keys in `.env`, and provisions the backing storage:
- `sqlite` — sets `DB_CONNECTION=sqlite` and `DB_DATABASE=database/database.sqlite`, creates the file if missing. No service is started.
- `mysql` — sets `DB_CONNECTION=mysql` and the `lerd-mysql` connection vars, starts `lerd-mysql` if needed, creates `<project>` and `<project>_testing` databases.
- `postgres` — sets `DB_CONNECTION=pgsql` and the `lerd-postgres` connection vars, starts `lerd-postgres` if needed, creates the project databases.

Arguments:
- `path` (optional): absolute path to the Laravel project root — defaults to `LERD_SITE_PATH` / cwd
- `database` (required): one of `"sqlite"`, `"mysql"`, `"postgres"`

Examples:
```
db_set(database: "mysql")        // fresh Laravel clone, switch to MySQL
db_set(database: "postgres")     // switch from MySQL → PostgreSQL (removes mysql)
db_set(database: "sqlite")       // explicitly keep SQLite (and create the file)
```

> Use this **before** `env_setup` on a fresh Laravel project so the database lands in `.env` deliberately. Switching databases later via `db_set` removes the previous database entry from `.lerd.yaml` automatically.

### Custom `APP_URL`
By default `env_setup` writes `APP_URL=<scheme>://<primary-domain>` (e.g. `http://myapp.test`) on every run. Three-tier override chain when you need a different value:

1. `.lerd.yaml` `app_url` field — committed to the repo, applies to every machine. Use for path prefixes, ports, or unrelated hostnames the whole team should share.
2. `~/.local/share/lerd/sites.yaml` `app_url` field on the site entry — per-machine override, not committed.
3. The default `<scheme>://<primary-domain>` generator — used when neither override is set.

There is no MCP tool to set `app_url` programmatically; the user (or you) edit `.lerd.yaml` directly and re-run `env_setup` (or any command that runs `lerd env` internally) to apply it.

Example `.lerd.yaml`:
```yaml
domains:
  - myapp
app_url: http://myapp.test/api
```

If the configured `app_url` happens to point at a domain that the conflict filter dropped, lerd silently falls through to the next precedence level so `.env` doesn't end up writing a hostname owned by another site.

### `env_check`
Compare all `.env` files (`.env`, `.env.testing`, `.env.local`, …) against `.env.example` and return structured JSON with missing or extra keys. Useful for catching "works on my machine" bugs caused by env drift after pulling new code.

Returns: `{"in_sync": bool, "keys": [{key, in_example, files: {filename: bool}}], "out_of_sync_count": N}`

Arguments:
- `path` (optional): absolute path to the project root — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)

### `site_link` / `site_unlink`
Register or unregister a directory as a lerd site. Arguments for `site_link`:
- `path` (optional): absolute path to the project directory — defaults to `LERD_SITE_PATH` set by `mcp:inject`
- `name` (optional): domain name without TLD (e.g. `"myapp"` becomes `myapp.test`; defaults to directory name, cleaned up)

> **Non-PHP projects (Node.js, Python, Go, etc.):** a Containerfile and `.lerd.yaml` with a `container: {port: <N>}` section must exist **before** calling `site_link`. The Containerfile can be named anything (`Containerfile.lerd` is the default; set `container.containerfile` to point at a different name like `Dockerfile`). Write `.lerd.yaml` directly (there is no MCP tool for this — see the custom container setup workflow in the Workflows section below), or ask the user to run `lerd init` which runs an interactive wizard and writes the file. Calling `site_link` without this config registers the site as a PHP-FPM site, which is wrong. If that happened, call `site_unlink` first, set up the files, then `site_link` again.

`site_unlink` takes `path` (optional, same resolution as `site_link`). Removes the site and all its domains. Project files are NOT deleted.

### `site_domain_add` / `site_domain_remove`
Add or remove additional domains for a site. Each site can have multiple domains (all served by the same nginx vhost).
- `path` (optional): project directory
- `domain` (required): domain name without TLD (e.g. `"api"` becomes `api.test`)

Cannot remove the last domain. When a site is secured, the TLS certificate is automatically reissued to cover all domains.

### `park` / `unpark`
`park` registers a parent directory: it scans every immediate subdirectory and auto-registers any PHP projects found as lerd sites. Use this when you keep many projects under one folder.

`unpark` removes the registration and unlinks all sites whose paths are under that directory. Project files are NOT deleted.

Both take `path` (optional, defaults to LERD_SITE_PATH or cwd).

### `secure` / `unsecure`
Enable or disable HTTPS for a site using a locally-trusted mkcert certificate. Both take `site` (site name). `APP_URL` in `.env` is updated automatically.

### `xdebug_on` / `xdebug_off` / `xdebug_status`
Toggle Xdebug for a PHP version (restarts the FPM container). Optional `version` argument — defaults to the project or global PHP version. Xdebug listens on port `9003` at `host.containers.internal`.

`xdebug_on` accepts an optional `mode` argument (default `debug`). Valid values: `debug`, `coverage`, `develop`, `profile`, `trace`, `gcstats`, or a comma-separated combo such as `debug,coverage`. Use `coverage` for `phpunit --coverage` / `pest --coverage` when PCOV isn't available or is disabled. Calling `xdebug_on` with a different mode on an already-enabled version swaps modes without needing `xdebug_off` first.

`xdebug_status` returns the enabled/disabled state and the active `mode` for all installed PHP versions.

### `queue_start` / `queue_stop`
Start or stop a queue worker for a site. Available for any framework that defines a `queue` worker (Laravel has it built-in). Runs the framework-defined command in the FPM container as a systemd service.

> **Redis queues:** if the project's `.env` has `QUEUE_CONNECTION=redis`, lerd will refuse to start the worker unless `lerd-redis` is running. Call `service_start(name: "redis")` first.

Arguments for `queue_start`:
- `site` (required): site name from `sites` tool
- `queue` (optional): queue name, default `"default"`
- `tries` (optional): max job attempts, default `3`
- `timeout` (optional): job timeout in seconds, default `60`

### `horizon_start` / `horizon_stop`
Start or stop Laravel Horizon for a site. Horizon is a queue manager that replaces `queue:work` — use `horizon_start` instead of `queue_start` for projects that have `laravel/horizon` in `composer.json`. Takes `site` (required, site name from `sites` tool). Returns an error if `laravel/horizon` is not installed.

> **Horizon vs queue worker:** The `sites` tool returns `has_horizon: true` when a site has Horizon installed. In that case prefer `horizon_start` over `queue_start`.

### `reverb_start` / `reverb_stop`
Start or stop the Reverb WebSocket server for a site. Available for any framework that defines a `reverb` worker. Takes `site` (required, site name from `sites` tool).

### `schedule_start` / `schedule_stop`
Start or stop the task scheduler for a site. Available for any framework that defines a `schedule` worker. Takes `site` (required, site name from `sites` tool).

### `worker_start` / `worker_stop`
Start or stop any named framework worker for a site. Use this for workers that don't have a dedicated shortcut (e.g. `messenger` for Symfony, `horizon` or `pulse` for Laravel). The worker command is taken from the framework definition.

Arguments:
- `site` (required): site name from `sites` tool
- `worker` (required): worker name as defined in the framework (e.g. `"messenger"`, `"horizon"`)

### `worker_list`
List all workers defined for a site's framework, with their running status, command, unit name, and restart policy. Use this to discover available workers before calling `worker_start`.

Arguments:
- `site` (required): site name from `sites` tool

### `worker_add`
Add or update a custom worker for a project. Saves to `.lerd.yaml` `custom_workers` by default, or to the global framework overlay (`~/.config/lerd/frameworks/`) with `global: true`. Does not auto-start — use `worker_start` afterwards.

Arguments:
- `site` (required): site name from `sites` tool
- `name` (required): worker name (slug, e.g. `"pdf-generator"`)
- `command` (required): command to run inside the PHP-FPM container
- `label`: human-readable label
- `restart`: `"always"` or `"on-failure"` (default: always)
- `check_file`: only show worker when this file exists
- `check_composer`: only show worker when this Composer package is installed
- `conflicts_with`: array of workers to stop before starting this one
- `global`: save to global framework overlay instead of `.lerd.yaml`

### `worker_remove`
Remove a custom worker from a project's `.lerd.yaml` or global framework overlay. Stops the worker if running.

Arguments:
- `site` (required): site name from `sites` tool
- `name` (required): worker name to remove
- `global`: remove from global framework overlay instead of `.lerd.yaml`

### `project_new`
Scaffold a new PHP project using a framework's create command. For Laravel, runs `composer create-project --no-install --no-plugins --no-scripts laravel/laravel <path>`. Other frameworks must have a `create` field in their YAML definition.

Arguments:
- `path` (required): absolute path for the new project directory (e.g. `/home/user/code/myapp`)
- `framework` (optional): framework name (default: `"laravel"`)
- `args` (optional): extra arguments passed to the scaffold command

After creation, register and configure the project:
```
project_new(path: "/home/user/code/myapp")
site_link(path: "/home/user/code/myapp")
env_setup(path: "/home/user/code/myapp")
```

From the terminal you can also run:
```
lerd new myapp
cd myapp && lerd link && lerd setup
```

### `framework_list`
List all available framework definitions (Laravel built-in plus any user-defined YAMLs at `~/.config/lerd/frameworks/`), including their defined workers and setup commands. Call this before `framework_add` to see what already exists.

### `framework_add`
Create or update a framework definition. For `laravel`, only the `workers` and `setup` fields are accepted (built-in settings are always preserved). For other frameworks, creates a full definition.

Arguments:
- `name` (required): framework slug (e.g. `"symfony"`). Use `"laravel"` to add custom workers to the built-in Laravel definition (e.g. `horizon`, `pulse`)
- `label` (optional): display name, e.g. `"Symfony"`
- `public_dir` (optional): document root relative to project (default: `"public"`)
- `detect_files` (optional): array of filenames that signal this framework
- `detect_packages` (optional): array of Composer packages that signal this framework
- `env_file` (optional): primary env file path (default: `".env"`)
- `env_format` (optional): `"dotenv"` or `"php-const"`
- `workers` (optional): map of worker name → `{label, command, restart, check}` — `check` is optional (`{file}` or `{composer}`), worker only shown when check passes
- `setup` (optional): array of one-off setup commands shown in `lerd setup` wizard, each with `{label, command, default, check}` — `check` is optional, same format as workers

Example — add Horizon to Laravel:
```
framework_add(name: "laravel", workers: {
  "horizon": {"label": "Horizon", "command": "php artisan horizon", "restart": "always"}
})
```

Example — define a new framework:
```
framework_add(
  name: "wordpress",
  label: "WordPress",
  public_dir: ".",
  detect_files: ["wp-login.php"],
  workers: {
    "cron": {"label": "WP Cron", "command": "wp cron event run --due-now --allow-root", "restart": "always"}
  }
)
```

### `framework_remove`
Delete a user-defined framework YAML. For `laravel`, removes only custom worker and setup command additions (built-in queue/schedule/reverb workers and storage:link/migrate/db:seed setup remain). Takes `name` (required).

### `site_php` / `site_node`
Change the PHP or Node.js version for a registered site. Both take `site` (required) and `version` (required).

`site_php` writes a `.php-version` pin file to the project root, updates the site registry, and regenerates the nginx vhost. The FPM container for the target PHP version must be running — start it with `service_start(name: "php<version>")` if needed.

`site_node` writes a `.node-version` pin file and installs the version via fnm if it isn't already installed. Run `npm install` inside the project if dependencies need rebuilding against the new version.

### `site_pause` / `site_unpause`
Pause or resume a site. Both take `site` (required, site name from `sites` tool).

`site_pause` stops all running workers for the site, stops the custom container (for custom container sites), and replaces its nginx vhost with a landing page that includes a **Resume** button. Services no longer needed by any active site are auto-stopped. The paused state is persisted.

`site_unpause` starts the custom container (if applicable), restores the nginx vhost, ensures required services are running, and restarts any workers that were running when the site was paused.

Use this to free up resources for sites you're not actively working on without fully unlinking them.

### `site_restart`
Restart the container for a site without rebuilding the image. Takes `site` (required). For custom container sites this restarts the dedicated container; for PHP sites it restarts the shared FPM container.

### `site_rebuild`
Rebuild the custom container image from the Containerfile and restart the container. Takes `site` (required). Use after changing the Containerfile. `site_link` reuses the cached image; `site_rebuild` forces a fresh build. Only works for custom container sites.

### `service_pin` / `service_unpin`
Pin or unpin a service. Both take `name` (required).

`service_pin` marks a service so it is **never auto-stopped**, even when no active sites reference it in their `.env`. Starts the service if it isn't already running. Use this for services you want always available regardless of which site is active (e.g. a shared Redis or MySQL).

`service_unpin` removes the pin so the service can be auto-stopped when no sites use it.

### `stripe_listen` / `stripe_listen_stop`
Start or stop a Stripe webhook listener for a site using the Stripe CLI container. Reads `STRIPE_SECRET` from the site's `.env` and forwards webhooks to `/stripe/webhook` by default.

Arguments for `stripe_listen`:
- `site` (required): site name from `sites` tool
- `api_key` (optional): Stripe secret key (defaults to `STRIPE_SECRET` in the site's `.env`)
- `webhook_path` (optional): webhook route path (default: `"/stripe/webhook"`)

### `db_export`
Export a database to a SQL dump file. Works with any project type — service and database are auto-detected. Arguments:
- `path` (optional): absolute path to the project root — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)
- `service` (optional): lerd service name to target (e.g. `mysql`, `postgres`) — overrides auto-detection
- `database` (optional): database name to export — overrides auto-detection
- `output` (optional): output file path (defaults to `<database>.sql` in the project root)

### `db_import`
Import a SQL dump file into the project database. Service and database are auto-detected; the service is started if not already running. Arguments:
- `file` (required): absolute path to the SQL file to import
- `path` (optional): absolute path to the project root — defaults to the current working directory
- `service` (optional): lerd service name to target — overrides auto-detection
- `database` (optional): database name to import into — overrides auto-detection

### `db_create`
Create a database and a `<name>_testing` variant for the project. Service and database name are auto-detected; the service is started if not already running. Arguments:
- `path` (optional): absolute path to the project root
- `service` (optional): lerd service name to target — overrides auto-detection
- `name` (optional): database name — overrides auto-detection

### `logs`
Fetch recent container logs. `target` is optional — when omitted, returns logs for the current site's PHP-FPM container (resolved from `LERD_SITE_PATH`). Specify `target` only when you want a different container:
- `"nginx"` — nginx proxy logs
- Service name: `"mysql"`, `"redis"`, or any custom service name
- PHP version: `"8.4"` — logs for that PHP-FPM container
- Site name — logs for a different site's PHP-FPM container

Optional `lines` parameter (default: 50).

### `status`
Return the health status of core lerd services as structured JSON: DNS resolution (ok + tld), nginx (running), PHP-FPM containers (running per version), and the file watcher (running). **Call this first when a site isn't loading** — it pinpoints which service is down before suggesting fixes.

### `which`
Show the resolved PHP version, Node version, document root, and nginx config path for the current site. Call this to confirm which runtime versions a project will use before running commands.

Arguments:
- `path` (optional): absolute path to the project root — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)

### `check`
Validate a project's `.lerd.yaml` file. Returns structured JSON with per-field status (ok/warn/fail). Checks PHP version format and installation, service definitions (built-in, custom, inline), framework references, and worker configuration.

Returns: `{"valid": bool, "errors": N, "warnings": N, "items": [{name, status, detail}]}`

Arguments:
- `path` (optional): absolute path to the project root containing `.lerd.yaml` — defaults to the current working directory (or `LERD_SITE_PATH` if set by `mcp:inject`)

> **Use this before** `env_setup` or `site_link` to catch configuration errors early.

### `doctor`
Run a full environment diagnostic. Returns structured JSON with per-check status (ok/warn/fail): podman, systemd, linger, dir writability, config validity, DNS resolution, nginx, PHP images, and update availability.

Returns: `{"version": "...", "checks": [{name, status, detail}], "failures": N, "warnings": N, "php_installed": [...], "php_default": "...", "node_default": "..."}`

**Use this when the user reports setup issues or unexpected behaviour.**

## Common Workflows

**Check installed runtimes before starting:**
```
runtime_versions()   // see PHP and Node.js versions available
```

**Create a new Laravel project from scratch (global session, empty directory):**
```
composer(args: ["create-project", "laravel/laravel", "."])
site_link()           // registers the cwd as a lerd site
env_setup()           // configures .env, starts services, creates DB, generates APP_KEY (even before composer install)
artisan(args: ["migrate"])
```

**Set up a cloned project (full flow):**
```
site_link()                          // registers the cwd as a lerd site
env_setup()                          // auto-configures .env, starts services, creates DB
composer(args: ["install"])
artisan(args: ["migrate", "--seed"])
```

**Enable HTTPS for a site:**
```
secure(site: "myapp")
```

**Enable Xdebug for a debugging session:**
```
xdebug_status()                                 // check current state and mode
xdebug_on(version: "8.4")                       // default mode=debug, restarts FPM
// ... debug ...
xdebug_off(version: "8.4")                      // disable when done (Xdebug adds overhead)
```

**Enable Xdebug coverage mode for phpunit/pest:**
```
xdebug_on(version: "8.4", mode: "coverage")     // swap mode without xdebug_off first
vendor_run(name: "pest", args: ["--coverage"])
xdebug_off(version: "8.4")
```

**Run migrations after schema changes:**
```
artisan(args: ["migrate"])
```

**Install and configure a service:**
```
service_start(name: "mysql")
service_start(name: "redis")   // if needed
composer(args: ["install"])
artisan(args: ["key:generate"])
artisan(args: ["migrate", "--seed"])
```

**Install a new package:**
```
composer(args: ["require", "spatie/laravel-permission"])
artisan(args: ["vendor:publish", "--provider=Spatie\\Permission\\PermissionServiceProvider"])
artisan(args: ["migrate"])
```

**Install a Node.js version and pin it to the project:**
```
node_install(version: "20")
// Then in a terminal: lerd isolate:node 20
```

**Add a custom service (e.g. MongoDB):**
```
service_add(name: "mongodb", image: "docker.io/library/mongo:7", ports: ["27017:27017"], data_dir: "/data/db")
service_start(name: "mongodb")
```

**Back up the database before a risky migration:**
```
db_export(output: "/tmp/myapp-backup.sql")
artisan(args: ["migrate"])
```

**Restore a database from a dump:**
```
db_import(file: "/tmp/myapp-backup.sql")
```

**Create databases for a new project manually:**
```
db_create()   // creates myapp + myapp_testing based on .env DB_DATABASE
```

**Check and manage PHP extensions:**
```
php_list()                           // see installed PHP versions
php_ext_list()                       // see custom extensions for current project's PHP version
php_ext_add(extension: "imagick")    // install imagick (rebuilds FPM image)
```

**Park a directory of projects:**
```
park(path: "/home/user/code")   // registers all PHP projects under ~/code as sites
```

**Diagnose PHP errors:**
```
logs()                  // current site's PHP-FPM errors (no target needed)
logs(target: "nginx")   // nginx errors
```

**Site isn't loading — check service health first:**
```
status()    // see which of DNS / nginx / PHP-FPM / watcher is down
```

**Free up resources — pause sites you're not using:**
```
sites()                          // see all sites
site_pause(site: "old-project")  // stop workers + replace vhost with landing page
// ... later ...
site_unpause(site: "old-project")  // restore and restart
```

**Restart a site's container (e.g. after changing Containerfile):**
```
site_restart(site: "nestjs-app")  // restarts container (no rebuild)
site_rebuild(site: "nestjs-app")  // rebuilds image from Containerfile + restarts
```

**Keep a service always running regardless of active site:**
```
service_pin(name: "mysql")    // never auto-stopped
service_pin(name: "redis")
```

**User reports setup issues or something unexpected:**
```
doctor()    // full diagnostic: podman, systemd, DNS, ports, images, config
```

**Start a framework worker (Symfony Messenger, Laravel Horizon, etc.):**
```
worker_list(site: "myapp")            // see what workers are available and their status
worker_start(site: "myapp", worker: "messenger")  // start by name
worker_stop(site: "myapp", worker: "messenger")
```

**Add a custom worker to Laravel (e.g. Horizon):**
```
framework_add(name: "laravel", workers: {
  "horizon": {"label": "Horizon", "command": "php artisan horizon", "restart": "always"}
})
worker_start(site: "myapp", worker: "horizon")
```

**Work with failed queue jobs:**
```
artisan(args: ["queue:failed"])
artisan(args: ["queue:retry", "all"])
```

**Generate and run a new migration:**
```
artisan(args: ["make:migration", "add_status_to_orders"])
// ... edit the migration file ...
artisan(args: ["migrate"])
```

**Check which PHP and Node versions a site will use:**
```
which()   // shows resolved PHP, Node, document root, nginx config
```

**Validate project config before setup:**
```
check()   // validates .lerd.yaml syntax, services, PHP version
```

**Set up a custom container site (Node.js, Python, Go, etc.):**

1. Create a `Containerfile.lerd` in the project root (do NOT add WORKDIR or COPY — lerd volume-mounts the project directory at its host path and sets --workdir automatically):
```dockerfile
FROM node:20-alpine
RUN npm install -g nodemon
CMD ["npm", "run", "start:dev"]
```

   > **Hot-reload on macOS**: inotify events do not fire across Podman Machine's virtiofs mount. Use polling: nodemon needs `--legacy-watch`, Vite needs `server.watch.usePolling: true`, webpack needs `watchOptions: { poll: 1000 }`. Example `package.json`: `"start:dev": "nodemon --legacy-watch src/main.js"`.

2. Write `.lerd.yaml` with the container section (there is no MCP tool for this — write the file directly, or ask the user to run `lerd init` which runs an interactive wizard and writes it):
```yaml
domains:
  - myapp
container:
  port: 3000
services:
  - mysql
  - redis
custom_workers:
  queue:
    label: Queue Worker
    command: node dist/queue.js
    restart: always
```

3. **Configure environment variables BEFORE linking.** The container starts immediately on `site_link`, so the app's `.env` (or equivalent config) must already have the correct service connection strings. Lerd services are reachable by container name on the `lerd` network:
```
DB_HOST=lerd-mysql          # or lerd-postgres
DB_PORT=3306                # 5432 for postgres
DB_USERNAME=root            # postgres for postgres
DB_PASSWORD=lerd
REDIS_HOST=lerd-redis
REDIS_PORT=6379
```
   Start the services first if they're not running:
```
service_start(name: "mysql")
service_start(name: "redis")
```

4. Link and verify:
```
site_link()            // builds image, creates container, generates nginx vhost
sites()                // verify the site is listed with custom_container: true
```

The `container.port` field is required — it's the port the app listens on inside the container. `container.containerfile` defaults to `Containerfile.lerd`. Workers defined in `custom_workers` exec into the custom container.

## .lerd.yaml Reference

`.lerd.yaml` is the per-project config file, committed to the repo. `lerd link` and `lerd init` apply it automatically.

### PHP site fields

| Field | Description |
|-------|-------------|
| `domains` | Site hostnames without TLD (e.g. `[myapp, api]`). First is primary. |
| `php_version` | PHP version for this project (e.g. `"8.4"`) |
| `node_version` | Node version (e.g. `"22"`) |
| `framework` | Framework name (e.g. `laravel`, `symfony`, `wordpress`) |
| `secured` | `true` to enable HTTPS |
| `services` | Services to start (e.g. `[mysql, redis]`) |
| `workers` | Active worker names (e.g. `[queue, schedule]`) — auto-synced by start/stop |
| `app_url` | Override for APP_URL in `.env` |

### Custom container fields

| Field | Required | Default | Description |
|-------|----------|---------|-------------|
| `container.port` | yes | | Port the app listens on inside the container |
| `container.containerfile` | no | `Containerfile.lerd` | Path to the Containerfile (relative to project root) |
| `container.build_context` | no | `.` | Build context directory |
| `custom_workers` | no | | Worker definitions — see below |
| `domains` | no | | Same as PHP sites |
| `secured` | no | | Same as PHP sites |
| `services` | no | | Same as PHP sites |

When `container` is present, `php_version`, `framework`, and `node_version` are ignored — the container defines its own runtime.

### custom_workers fields

Each entry under `custom_workers` is a name-to-config map. Works for both PHP and custom container sites.

```yaml
custom_workers:
  queue:
    label: Queue Worker
    command: node dist/queue.js
    restart: always
  cron:
    label: Cron
    command: node dist/cron.js
    restart: on-failure
```

| Field | Required | Description |
|-------|----------|-------------|
| `label` | no | Display name in the UI |
| `command` | yes | Shell command to run inside the container |
| `restart` | no | `always` (default) or `on-failure` |
| `schedule` | no | systemd OnCalendar expression for cron-style workers (e.g. `minutely`) |
| `conflicts_with` | no | List of worker names to stop before starting this one |
