```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} /_processed_/
    RewriteRule ^.*$ %{ENV:CWD}index.php?eID=image_async_process [QSA,L]
</IfModule>
```