# REDCap Docker
This docker implementation was built for the [REDCap Cypress Developer Toolkit](https://github.com/vanderbilt-redcap/redcap_cypress_docker/blob/main/README.md).

The current implementation keeps with the Docker design principles of being:

* **simple** - installs only what is needed

* **composable** - web server and database in separate containers

* **automateable** - requires no manual build steps

# Changes Needed for ARM64 (Apple Silicon, Raspberry Pi, etc.) Host Machines
You will need set the following in your `env/app.env` file:

```env
PLATFORM=arm64
REDCAP_APP_INTERNAL_PORT=8080
```

Before running the `build` and `up` commands, this needs to be part of the compose configuration:
```bash
cp env/app.env ./.env
```

# How to Use

Update the environment variable files located underneath /env with your preferences.

Update the docker-compose.yml file with the ports you plan on using (if non-standard).

When you are ready, installation is as easy as:
```bash
docker-compose build && docker-compose up -d
```
