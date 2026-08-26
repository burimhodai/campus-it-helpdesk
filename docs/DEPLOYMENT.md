# GitHub and Optional Cloud Deployment

Local XAMPP is the primary demonstration environment. A GitHub repository is still useful for submission and backup, and Railway is a practical optional host because it can deploy Laravel from GitHub and attach a MySQL service.

## Publish the repository to GitHub

The project already excludes `.env`, local databases, installed dependencies, build output, and logs. Never add `.env` to GitHub because it contains the application key and database credentials.

If the repository has not already been created online:

1. Create an empty GitHub repository named `campus-it-helpdesk`. Do not add a remote README or `.gitignore` because both already exist here.
2. In the project folder, initialize and commit the local repository if needed:

```powershell
git init
git add .
git commit -m "Complete campus IT help desk project"
git branch -M main
```

3. Connect the repository URL shown by GitHub and push:

```powershell
git remote add origin https://github.com/YOUR-USERNAME/campus-it-helpdesk.git
git push -u origin main
```

VS Code may show the existing GitHub sign-in when the push asks for authorization. The **Publish Branch** button in Source Control is an equivalent graphical workflow.

## Optional Railway deployment

This project does not require a worker or scheduler, so it can use one web service plus one MySQL service.

1. In Railway, choose **New Project**, then deploy from the GitHub repository.
2. Add a MySQL service to the same project.
3. Add the application variables below. Use Railway's MySQL variable references for the database values rather than copying public credentials.

```dotenv
APP_NAME="Campus IT Help Desk"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR-GENERATED-DOMAIN
APP_KEY=base64:GENERATED_VALUE

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

Generate `APP_KEY` locally with `php artisan key:generate --show`; store the result only in Railway variables.

4. Set the service build command to:

```sh
npm ci && npm run build
```

5. Set the pre-deploy command to:

```sh
php artisan migrate --force && php artisan optimize
```

6. Deploy, then generate a public domain in the service's networking settings.
7. Check `/up` on the generated domain. Laravel returns HTTP 200 when the application boots successfully.

### Loading demonstration data online

For a private examiner-only deployment, the sample accounts can be added once with:

```sh
php artisan db:seed --force
```

Do not expose the documented demonstration passwords on a long-running public site. Change them immediately or create new accounts after the presentation.

## Production checks

- `APP_DEBUG` is `false`.
- `.env` is not tracked by Git.
- The application URL uses HTTPS.
- The web root is Laravel's `public` directory.
- Database migrations have completed.
- `php artisan optimize` has completed.
- A fresh administrator password replaces the local demo password.
- The `/up` health page responds successfully.

## References

- [Laravel 12 deployment documentation](https://laravel.com/docs/12.x/deployment)
- [Railway Laravel guide](https://docs.railway.com/guides/laravel)
- [Railway MySQL documentation](https://docs.railway.com/databases/mysql)
- [GitHub guide for adding local code](https://docs.github.com/en/migrations/importing-source-code/using-the-command-line-to-import-source-code/adding-locally-hosted-code-to-github)

