# REDCap Docker
This docker implementation was built for the [REDCap Cypress Developer Toolkit](https://github.com/vanderbilt-redcap/redcap_cypress_docker/blob/main/README.md).

The current implementation keeps with the Docker design principles of being:

* **simple** - installs only what is needed

* **composable** - web server and database in separate containers

* **automateable** - requires no manual build steps

# How to Use

Update the environment variable files located underneath /env with your preferences.

Update the docker-compose.yml file with the ports you plan on using (if non-standard).

When you are ready, installation is as easy as:
```
$ docker-compose build
$ docker-compose up
```