# 安裝說明（Installation Guide）

## 系統需求
- PHP 8.1 以上
- Composer
- MySQL 或 PostgreSQL
- Node.js & npm（如需前端編譯）
- GD 或 Imagick 擴充（圖片處理）

## 安裝步驟

1. **下載專案原始碼**
   ```bash
   git clone <專案網址>
   cd rent-bike
   ```

2. **安裝 PHP 套件**
   ```bash
   composer install
   ```

3. **複製環境設定檔**
   ```bash
   cp .env.example .env
   ```

4. **設定 .env 檔案**
   - 設定資料庫連線資訊（DB_DATABASE、DB_USERNAME、DB_PASSWORD）
   - 設定其他必要參數

5. **產生應用程式金鑰**
   ```bash
   php artisan key:generate
   ```

6. **執行資料庫遷移**
   ```bash
   php artisan migrate
   ```

7. **建立 storage 連結**
   ```bash
   php artisan storage:link
   ```

8. **執行 Filament 重新架構 & cache**
   ```base
   php artisan vendor:publish --force --tag=livewire:assets
   php artisan filament:assets
   php artisan filament:cache-components
   ```   

10. **預設管理後台路徑**
    - 通常為 `/backend`，可依專案設定調整

## 其他注意事項
- 請確認 `storage` 及 `bootstrap/cache` 目錄有正確寫入權限
- 若有使用第三方套件（如 Intervention Image、Filament 外掛），請依需求安裝
- 若有特殊安裝步驟，請補充於本文件

---

如有問題，請參考 README.md 或聯絡專案維護者。 