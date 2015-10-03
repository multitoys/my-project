AddDefaultCharset UTF-8
RewriteEngine On

RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule ^shop/(repo_themes|js|3rdparty|images_common|products_pictures|images|themes|css)/(.*)$ published/SC/html/scripts/$1/$2?frontend=1 [L]

RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule ^(repo_themes|js|3rdparty|images_common|products_pictures|images|themes|css)/(.*)$ published/SC/html/scripts/$1/$2?frontend=1 [L]


RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule ^shop/(imgval.php|wbs_messageserserver.php|get_file.php) published/SC/html/scripts/$1 [L]

RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule ^shop(.*) published/SC/html/scripts/$1&frontend=1 [L]


RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule ^login/(.*) login/index.php [L]

RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule ^installer/(.*) installer/index.php [L]

RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule ^()$ published/login.php [L]

RewriteCond  %{REQUEST_FILENAME} !-f
RewriteRule (.*) published/$1 [L]