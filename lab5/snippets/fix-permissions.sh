#!/bin/bash
# ============================================================
#  fix-permissions.sh
#  Устанавливает правильные права на файлы и папки WordPress
#  Лабораторная работа №5 · Безопасность WordPress
#
#  ИСПОЛЬЗОВАНИЕ:
#    chmod +x fix-permissions.sh
#    sudo bash fix-permissions.sh /var/www/html/wordpress www-data
# ============================================================

WP_ROOT="${1:-/var/www/html/wordpress}"
WEB_USER="${2:-www-data}"

if [ ! -d "$WP_ROOT" ]; then
    echo "❌  Директория $WP_ROOT не найдена."
    exit 1
fi

echo "📁  WordPress root : $WP_ROOT"
echo "👤  Web user       : $WEB_USER"
echo ""

# Владелец всех файлов
echo "🔧  Устанавливаю владельца..."
chown -R "$WEB_USER":"$WEB_USER" "$WP_ROOT"

# Папки: 755 (rwxr-xr-x)
echo "🔧  Права на папки: 755..."
find "$WP_ROOT" -type d -exec chmod 755 {} \;

# Файлы: 644 (rw-r--r--)
echo "🔧  Права на файлы: 644..."
find "$WP_ROOT" -type f -exec chmod 644 {} \;

# wp-config.php: 600 (только владелец)
if [ -f "$WP_ROOT/wp-config.php" ]; then
    echo "🔒  wp-config.php -> 600"
    chmod 600 "$WP_ROOT/wp-config.php"
fi

# .htaccess: 644
if [ -f "$WP_ROOT/.htaccess" ]; then
    echo "🔒  .htaccess -> 644"
    chmod 644 "$WP_ROOT/.htaccess"
fi

echo ""
echo "✅  Готово! Проверка:"
ls -la "$WP_ROOT/wp-config.php" "$WP_ROOT/.htaccess" 2>/dev/null
