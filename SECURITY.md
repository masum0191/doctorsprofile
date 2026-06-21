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

## WHM host TLS

Set `WHM_HOST` to the WHM/cPanel server hostname that appears on the server's SSL certificate, not the server IP address. Example:

```env
WHM_HOST=https://server.example.com:2087
```

Do not disable TLS verification to work around certificate hostname errors; fix the hostname or server certificate instead.
