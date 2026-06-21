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

PHP 8.2 is in security-only support and approaching end of life. Plan a PHP 8.3+ upgrade after checking the Laravel version, Composer dependencies, hosting compatibility, and payment/cPanel integrations in staging.

## npm supply-chain protection

For npm package-install protection, install and configure one trusted tool on developer and deployment machines:

```bash
npm install -g @aikidosec/safe-chain
safe-chain setup
```

Alternatively:

```bash
npm install -g socket
```

## CORS origins

Set `CORS_ALLOWED_ORIGINS` to a comma-separated list of real frontend/admin origins, for example:

```env
CORS_ALLOWED_ORIGINS=https://doctorsprofile.xyz,https://www.doctorsprofile.xyz
```
