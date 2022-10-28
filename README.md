## 0.Prerequisites

### Installing

In order to run this project you will need to make sure that the following
dependencies are installed on your system :
- Docker: https://docs.docker.com/get-docker/
- Docker Compose: https://docs.docker.com/compose/install/
- I will assume that we are using linux for this guide, and you have experience with linux and docker 

### Folder structure
```
/source-folder/       # Root directory.
|- .env.example       # Environment file example
|- /docker            # Docker files
|- app/Http/Controllers/MailboxController.php  # Mailbox Controller
|-/app/Services/      # Contain mail service file
|-/app/Services/Mail  # Classes support mail service
│   ├── Builders
│   │   ├── MailerBuilderInterface.php
│   │   └── MailerBuilder.php
│   ├── MailerInterface.php
│   ├── Mailer.php
│   └── Worker
│       ├── MailerWorkerInterface.php
│       ├── MailgunMailerWorker.php
│       ├── SesMailerWorker.php
│       └── SparkpostMailerWorker.php
|-/app/Services/MailerServiceInterface.php # Mail service interface
|-/app/Services/MailerService.php # Mail service we are using
|-/tests/Feature/Services/MailerServiceTest.php # Mail service testcase
|- ...# Other files
```

## I.Build & Deploy

In this demo we have 3 mailer service that are using:
- Mailgun - http://www.mailgun.com/
- SparkPost - https://www.sparkpost.com/
- Amazon SES - http://aws.amazon.com/ses/

>I know that we can use `failover` in laravel to achieve the goal, it's very simple but in this demo, I use only laravel for configuration and implementing the mailbox screen

Laravel Failover: https://laravel.com/docs/9.x/mail#failover-configuration

1. For the first time, please copy `.env.example` to `.env` file

```
cp .env.example .env
```
And replace the mailer config keys:

#### Mailgun
```bash
MAILGUN_DOMAIN=xxxxxxxx
MAILGUN_SECRET=xxxxxxxxxxx
```

#### SparkPost
```bash
SPARKPOST_DOMAIN=xxxxxxxxxx
SPARKPOST_SECRET=xxxxxxxxxxxxx
```

#### SES
```bash
AWS_ACCESS_KEY_ID=xxxxxxxxx
AWS_SECRET_ACCESS_KEY=xxxxxxxxxxxxxxxxx
```

Edit the contents of `.env` if necessary:
- APP_NAME: Application name
- APP_ENV: Specify one of local, develop, staging, production
- APP_DEBUG: Must be set to `false` for production
- APP_URL: The domain of your application, in the local you can set value is http://localhost
- 
2. Run this command to build docker environment
```bash
docker compose up -d
```

3. Download components/packages and build project
```bash
docker compose exec app bash -c './build.sh'
```

Now, we move to next step is running

## II. How to run this demo

1. Open web browser and access root domain http://<domain.com> (http://localhost for local)
2. Follow instruction on mailbox screen to test the sending mail
3. To showing logs on UI, please access http://<domain.com>/logs domain  (http://localhost/logs for local)
4. To run testcase, please run this command below:
```bash
docker compose exec app bash -c './vendor/bin/phpunit --filter MailerServiceTest'
```
5. For saving your time, you also run this demo on my host: https://gaonho.com and https://gaonho.com/logs (for debug logs)
> For some reason, AWS not accepted my request to move out SES to production mode, please give me your email, and I will add it to the verified email of SES and you can receive the testing email after that

## III. Note

Below is some errors you may be got during the installation time:
>Error starting userland proxy: listen tcp4 0.0.0.0:80: bind: address already in use

Please open `docker-compose.yml` file and change your expose port (webserver/database)

>Error in exception handler: The stream or file "/var/www/app/storage/logs/laravel.log" could not be opened: failed to open stream:

Or

> The stream or file xxxxxx could not be opened in append mode

Any errors related to `/storage/` folder please run this command to fix permission:
```bash
docker compose exec app bash -c 'chmod -R 777 storage/'
```
I know that above command is danger, but it uses only in this demo for saving time

Thanks!
