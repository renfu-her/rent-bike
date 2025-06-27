# 安裝說明（Installation Guide）

## 系統需求
- PHP 8.1 以上
- Composer
- MySQL 或 PostgreSQL
- Node.js & npm（如需前端編譯）
- GD 或 Imagick 擴充（圖片處理）

## 安裝步驟

1. 安裝的步驟
```bash
git clone <專案網址>
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

2. 清理 Filament cache
``` bash
php artisan vendor:publish --force --tag=livewire:assets
php artisan filament:assets
php artisan filament:cache-components
```

3. **預設管理後台路徑**
    - 通常為 `/backend`，可依專案設定調整

## 其他注意事項
- 請確認 `storage` 及 `bootstrap/cache` 目錄有正確寫入權限
- 若有使用第三方套件（如 Intervention Image、Filament 外掛），請依需求安裝
- 若有特殊安裝步驟，請補充於本文件

---

如有問題，請參考 README.md 或聯絡專案維護者。 