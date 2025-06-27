# 🛵 出租機車系統設計文件

## 安裝方式

請參考 [安裝說明（installation.md）](./installation.md)

## 1. 系統角色與權限

- **Super Admin（最高管理者）**
  - 建立商店
  - 指派使用者成為 Store Manager 或 Staff
  - 檢視所有資料與統計報表

- **Store Manager（商店主管）**
  - 維護店內機車、配件、價格、員工
  - 管理租借流程與紅單處理

- **Staff（店內員工）**
  - 協助客戶處理租借與還車
  - 檢查車況與設備

- **User（一般使用者）**
  - 註冊與登入系統
  - 選擇機車與店家，建立租借訂單
  - 查詢租借紀錄與罰單

---

## 2. 系統流程概述

1. Super Admin 建立商店，指派 Store Manager
2. Store Manager 新增機車、價格、配件與員工
3. User 註冊後選擇商店與機車，建立租借訂單
4. Staff 處理還車與狀態更新
5. 若機車遭開罰單，系統自動關聯到當時的訂單與使用者
6. Store Manager 或 Super Admin 處理紅單並標記已解決

---

## 3. 資料表設計（第三正規化）

### 3.1 Users（使用者／帳號）

| 欄位           | 型別     | PK/FK      | 說明                                   |
|----------------|----------|------------|----------------------------------------|
| id             | BIGINT   | PK         | 使用者主鍵                             |
| role           | VARCHAR  |            | super_admin / store_manager / staff / user |
| name           | VARCHAR  |            | 使用者名稱                             |
| email          | VARCHAR  | UQ         | 登入帳號（唯一）                        |
| password       | VARCHAR  |            | 密碼（加密儲存）                        |
| remember_token | VARCHAR  |            | Token                                  |
| created_at     | DATETIME |            | 建立時間                               |
| updated_at     | DATETIME |            | 更新時間                               |

---

### 3.2 Members（會員）

| 欄位           | 型別     | PK/FK      | 說明                                   |
|----------------|----------|------------|----------------------------------------|
| id             | BIGINT   | PK         | 會員主鍵                               |
| name           | VARCHAR  |            | 會員姓名                               |
| email          | VARCHAR  | UQ         | 電子郵件（唯一）                        |
| password       | VARCHAR  |            | 密碼（加密儲存）                        |
| phone          | VARCHAR  |            | 電話號碼                               |
| address        | VARCHAR  |            | 地址                                   |
| status         | TINYINT  |            | 狀態（1=啟用，0=停用）                  |
| id_number      | VARCHAR  |            | 身份證字號                             |
| gender         | VARCHAR  |            | 性別（男/女）                          |
| remember_token | VARCHAR  |            | Token                                  |
| created_at     | DATETIME |            | 建立時間                               |
| updated_at     | DATETIME |            | 更新時間                               |

---

### 3.3 Stores（商店）

| 欄位        | 型別     | PK/FK       | 說明            |
|-------------|----------|-------------|-----------------|
| store_id    | BIGINT   | PK          | 商店主鍵         |
| name        | VARCHAR  |             | 商店名稱         |
| address     | VARCHAR  |             | 商店地址         |
| phone       | VARCHAR  |             | 商店電話         |
| manager_id  | BIGINT   | FK → Users  | 負責人（Store Manager） |
| image       | VARCHAR  |             | 店面照片         |
| created_at  | DATETIME |             | 建立時間         |
| updated_at  | DATETIME |             | 更新時間         |

---

### 3.4 Bikes（機車）

| 欄位           | 型別     | PK/FK       | 說明                            |
|----------------|----------|-------------|---------------------------------|
| bike_id        | BIGINT   | PK          | 機車主鍵                         |
| store_store_id | BIGINT   | FK → Stores | 所屬商店                         |
| plate_no       | VARCHAR  | UQ          | 車牌號碼（唯一）                  |
| model          | VARCHAR  |             | 機車型號                         |
| image          | VARCHAR  |             | 機車照片                         |
| status         | VARCHAR  |             | 狀態（available/rented/maintenance/disabled/pending） |
| accessories    | JSON     |             | 配件資訊（JSON格式）              |
| created_at     | DATETIME |             | 建立時間                         |
| updated_at     | DATETIME |             | 更新時間                         |

---

### 3.5 Accessories（配件）

| 欄位         | 型別     | PK/FK       | 說明                     |
|--------------|----------|-------------|--------------------------|
| accessory_id | BIGINT   | PK          | 配件主鍵                  |
| name         | VARCHAR  |             | 配件名稱                  |
| price        | DECIMAL  |             | 配件價格                  |
| qty          | INT      |             | 配件數量                  |
| created_at   | DATETIME |             | 建立時間                  |
| updated_at   | DATETIME |             | 更新時間                  |

---

### 3.6 AccessoryBike（機車配件關聯表）

| 欄位         | 型別     | PK/FK         | 說明                          |
|--------------|----------|---------------|-------------------------------|
| bike_id      | BIGINT   | FK → Bikes    | 機車ID                         |
| accessory_id | BIGINT   | FK → Accessories | 配件ID                        |
| qty          | INT      |               | 數量                           |
| price        | DECIMAL  |               | 價格                           |
| status       | VARCHAR  |               | 狀態                           |
| created_at   | DATETIME |               | 建立時間                       |
| updated_at   | DATETIME |               | 更新時間                       |

---

### 3.7 BikePrices（機車價格方案）

| 欄位         | 型別     | PK/FK         | 說明                          |
|--------------|----------|---------------|-------------------------------|
| price_id     | BIGINT   | PK            | 價格主鍵                       |
| bike_id      | BIGINT   | FK → Bikes    | 所屬機車                       |
| rental_days  | INT      |               | 租借天數（如：1、3、7）        |
| price_type   | VARCHAR  |               | fixed / discount               |
| original_price | DECIMAL |               | 原價（僅折扣時需填）           |
| price_amount | DECIMAL  |               | 金額或折扣（如：300、95）      |
| image        | VARCHAR  |               | 價格方案圖片                   |
| created_at   | DATETIME |               | 建立時間                       |
| updated_at   | DATETIME |               | 更新時間                       |

---

### 3.8 Orders（租借訂單）

| 欄位        | 型別     | PK/FK        | 說明                              |
|-------------|----------|--------------|-----------------------------------|
| order_id    | BIGINT   | PK           | 訂單主鍵                           |
| bike_id     | BIGINT   | FK → Bikes   | 租借的機車                         |
| user_id     | BIGINT   | FK → Users   | 下訂單的使用者（可為NULL）          |
| member_id   | BIGINT   | FK → Members | 下訂單的會員（可為NULL）            |
| rental_plan | VARCHAR  |              | 租借方案                           |
| booking_date| DATE     |              | 預訂日期                           |
| start_time  | DATETIME |              | 租借起始時間（可為NULL）           |
| end_time    | DATETIME |              | 租借結束時間（可為NULL）           |
| status      | VARCHAR  |              | 狀態（pending/active/completed/cancelled） |
| total_price | DECIMAL  |              | 訂單總金額                         |
| order_number| VARCHAR  |              | 訂單編號（自動生成）               |
| created_at  | DATETIME |              | 建立時間                           |
| updated_at  | DATETIME |              | 更新時間                           |

---

### 3.9 Tickets（紅單）

| 欄位              | 型別     | PK/FK          | 說明                               |
|-------------------|----------|----------------|------------------------------------|
| ticket_id         | BIGINT   | PK             | 紅單主鍵                            |
| ticket_number     | VARCHAR  |                | 罰單號碼                            |
| bike_id           | BIGINT   | FK → Bikes     | 被開單的機車                        |
| issued_time       | DATETIME |                | 開立時間                            |
| violation_location| VARCHAR  |                | 違規地點                            |
| violation_description | VARCHAR |                | 違規描述                            |
| due_date          | DATE     |                | 繳費期限                            |
| fined_person_name | VARCHAR  |                | 被罰人姓名                          |
| fined_person_id_number | VARCHAR |                | 被罰人身份證號                      |
| amount            | DECIMAL  |                | 罰款金額                            |
| issuer_name       | VARCHAR  |                | 開單人員姓名                        |
| issuing_authority | VARCHAR  |                | 開單機關                            |
| image             | VARCHAR  |                | 罰單圖片                            |
| is_resolved       | BOOLEAN  |                | 是否處理完成                        |
| status            | VARCHAR  |                | 狀態                               |
| handler_id        | BIGINT   | FK → Users     | 處理人員                            |
| related_order_id  | BIGINT   | FK → Orders    | 關聯訂單（若開單期間為租借中）       |
| created_at        | DATETIME |                | 建立時間                            |
| updated_at        | DATETIME |                | 更新時間                            |

---

### 3.10 Carousels（輪播圖）

| 欄位        | 型別     | PK/FK      | 說明            |
|-------------|----------|------------|-----------------|
| id          | BIGINT   | PK         | 輪播圖主鍵       |
| title       | VARCHAR  |            | 標題             |
| image       | VARCHAR  |            | 圖片路徑         |
| url         | VARCHAR  |            | 連結網址         |
| sort_order  | INT      |            | 排序順序         |
| is_active   | BOOLEAN  |            | 是否啟用         |
| created_at  | DATETIME |            | 建立時間         |
| updated_at  | DATETIME |            | 更新時間         |

---

## 4. 系統特色與擴充性

- ✅ **角色分明**：支援管理員、主管、員工與使用者的分權設計
- 👥 **雙重用戶系統**：支援一般使用者（Users）與會員（Members）兩種身份
- 🛵 **機車管理**：機車狀態、配件、租借價格可細緻設定
- 🧾 **彈性租借費率**：不同天數有不同價格，支援固定價格與折扣模式
- 🚨 **紅單追蹤**：自動關聯訂單與責任人，避免糾紛
- 🎯 **輪播圖管理**：支援首頁輪播圖的動態管理
- 📈 **擴充方向**：
  - 統計報表（收入、紅單率、租借率）
  - GPS 定位、車輛維修排程
  - 線上付款與通知整合

---

## 5. 系統總覽表

| 模組         | 說明 |
|--------------|------|
| 使用者管理   | 支援註冊、登入、角色設定與指派 |
| 會員管理     | 支援會員註冊、資料管理與租借 |
| 商店管理     | 支援多商店與多角色指派功能 |
| 機車管理     | 管理車輛資料、狀態、配件、價格 |
| 配件管理     | 獨立配件管理與機車關聯 |
| 價格模組     | 每台車可有多種天數與對應金額 |
| 租借模組     | 建立訂單、狀態控管、結帳紀錄 |
| 紅單模組     | 關聯訂單與租借者，並可追蹤處理狀態 |
| 輪播圖管理   | 首頁輪播圖的動態管理 |

---

## ✅ 建議技術架構

- **後端**：Laravel + Filament（管理後台）
- **前端**：Flutter 或 Vue.js（使用者租借介面）
- **資料庫**：MySQL 或 PostgreSQL
- **部署建議**：使用 CloudPanel + Nginx + SSL，並搭配 Cloudflare 防護
