# Security Deployment Notes

## Environment file permissions

On Linux production servers, restrict the Laravel environment file after deployment:

```bash
chmod 600 .env
```

If the web server process requires group read access, use:

```bash
chmod 640 .env
```

Never expose `.env` through the web root, logs, backups, or source control.

## PHP runtime

PHP 8.2 is approaching end of life. Plan a staging compatibility check and upgrade path to PHP 8.3 or newer before the production runtime reaches EOL.

## CORS origins

Set `CORS_ALLOWED_ORIGINS` to a comma-separated list of real frontend/admin origins, for example:

```env
CORS_ALLOWED_ORIGINS=https://doctorsprofile.xyz,https://www.doctorsprofile.xyz
```
