from fabric import Connection
from invoke import task
from datetime import datetime
import subprocess
import shlex

APP_NAME = "homepage"
SSH_HOST = "homepage@51.250.85.23"
DOCKER_REGISTRY = "cr.yandex/crplfk0168i4o8kd7ade"


def run(args):
    return subprocess.run(args, check=True, capture_output=True).stdout


@task
def deploy(c):
    timestamp = int(datetime.now().timestamp())
    commit = run(["git", "rev-parse", "--short", "HEAD"]).decode("utf-8").strip()
    nginx_image_tag = f"{DOCKER_REGISTRY}/homepage-nginx:{commit}-{timestamp}"

    print(f"Build nginx image {nginx_image_tag}")

    run(
        [
            "docker",
            "build",
            "--file",
            "docker/Dockerfile.nginx.prod",
            "--tag",
            nginx_image_tag,
            ".",
        ]
    )

    print("Push nginx image")

    run(["docker", "push", nginx_image_tag])

    print("Ready to setup remote host")

    with Connection(SSH_HOST) as c:
        c.put(
            "./docker/docker-compose.prod.yml",
            remote="/home/homepage/docker-compose.yml",
        )
        c.run("cp .env .env.prod")
        c.run(f"echo NGINX_IMAGE={shlex.quote(nginx_image_tag)} >> .env.prod")
        c.run(f"docker-compose --project-name {shlex.quote(APP_NAME)} --env-file=.env.prod up --detach")
