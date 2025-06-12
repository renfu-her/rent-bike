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
| user_id        | BIGINT   | PK         | 使用者主鍵                             |
| role           | ENUM     |            | super_admin / store_manager / staff / user |
| name           | VARCHAR  |            | 使用者名稱                             |
| email          | VARCHAR  | UQ         | 登入帳號（唯一）                        |
| password       | VARCHAR  |            | 密碼（加密儲存）                        |
| remember_token | VARCHAR  |            | Token                                  |
| created_at     | DATETIME |            | 建立時間                               |
| updated_at     | DATETIME |            | 更新時間                               |

---

### 3.2 Stores（商店）

| 欄位        | 型別     | PK/FK       | 說明            |
|-------------|----------|-------------|-----------------|
| store_id    | BIGINT   | PK          | 商店主鍵         |
| name        | VARCHAR  |             | 商店名稱         |
| address     | VARCHAR  |             | 商店地址         |
| manager_id  | BIGINT   | FK → Users  | 負責人（Store Manager） |
| image       | VARCHAR  |             | 店面照片         |
| created_at  | DATETIME |             | 建立時間         |
| updated_at  | DATETIME |             | 更新時間         |

---

### 3.3 Bikes（機車）

| 欄位           | 型別     | PK/FK       | 說明                            |
|----------------|----------|-------------|---------------------------------|
| bike_id        | BIGINT   | PK          | 機車主鍵                         |
| store_store_id | BIGINT   | FK → Stores | 所屬商店                         |
| plate_no       | VARCHAR  | UQ          | 車牌號碼（唯一）                  |
| model          | VARCHAR  |             | 機車型號                         |
| status         | ENUM     |             | 待出租 / 已出租 / 維修中 / 停用  |
| created_at     | DATETIME |             | 建立時間                         |
| updated_at     | DATETIME |             | 更新時間                         |

---

### 3.4 Accessories（配件）

| 欄位         | 型別     | PK/FK       | 說明                     |
|--------------|----------|-------------|--------------------------|
| accessory_id | BIGINT   | PK          | 配件主鍵                  |
| bike_id      | BIGINT   | FK → Bikes  | 綁定的機車                |
| helmet_count | INT      |             | 安全帽數量（預設為 2）    |
| has_lock     | BOOLEAN  |             | 是否附鎖                  |
| has_toolkit  | BOOLEAN  |             | 是否附維修工具            |
| created_at   | DATETIME |             | 建立時間                  |
| updated_at   | DATETIME |             | 更新時間                  |

---

### 3.5 BikePrices（機車價格方案）

| 欄位         | 型別     | PK/FK         | 說明                          |
|--------------|----------|---------------|-------------------------------|
| price_id     | BIGINT   | PK            | 價格主鍵                       |
| bike_id      | BIGINT   | FK → Bikes    | 所屬機車                       |
| rental_days  | INT      |               | 租借天數（如：1、3、7）        |
| price_type   | ENUM     |               | fixed / discount               |
| original_price | DECIMAL |               | 原價（僅折扣時需填）           |
| price_amount | DECIMAL  |               | 金額或折扣（如：300、95）      |
| created_at   | DATETIME |               | 建立時間                       |
| updated_at   | DATETIME |               | 更新時間                       |

---

### 3.6 Orders（租借訂單）

| 欄位        | 型別     | PK/FK        | 說明                              |
|-------------|----------|--------------|-----------------------------------|
| order_id    | BIGINT   | PK           | 訂單主鍵                           |
| bike_id     | BIGINT   | FK → Bikes   | 租借的機車                         |
| user_id     | BIGINT   | FK → Users   | 下訂單的使用者                     |
| start_time  | DATETIME |              | 租借起始時間                       |
| end_time    | DATETIME |              | 租借結束時間（尚未還車為 NULL）    |
| status      | ENUM     |              | active / completed / cancelled    |
| total_price | DECIMAL  |              | 訂單總金額                         |
| created_at  | DATETIME |              | 建立時間                           |
| updated_at  | DATETIME |              | 更新時間                           |

---

### 3.7 Tickets（紅單）

| 欄位              | 型別     | PK/FK          | 說明                               |
|-------------------|----------|----------------|------------------------------------|
| ticket_id         | BIGINT   | PK             | 紅單主鍵                            |
| bike_id           | BIGINT   | FK → Bikes     | 被開單的機車                        |
| issued_time       | DATETIME |                | 開立時間                            |
| amount            | DECIMAL  |                | 罰款金額                            |
| is_resolved       | BOOLEAN  |                | 是否處理完成                        |
| related_order_id  | BIGINT   | FK → Orders    | 關聯訂單（若開單期間為租借中）       |
| created_at        | DATETIME |                | 建立時間                            |
| updated_at        | DATETIME |                | 更新時間                            |

---

## 4. 系統特色與擴充性

- ✅ **角色分明**：支援管理員、主管、員工與使用者的分權設計
- 🛵 **機車管理**：機車狀態、配件、租借價格可細緻設定
- 🧾 **彈性租借費率**：不同天數有不同價格，自動對應計費
- 🚨 **紅單追蹤**：自動關聯訂單與責任人，避免糾紛
- 📈 **擴充方向**：
  - 統計報表（收入、紅單率、租借率）
  - GPS 定位、車輛維修排程
  - 線上付款與通知整合

---

## 5. 系統總覽表

| 模組         | 說明 |
|--------------|------|
| 使用者管理   | 支援註冊、登入、角色設定與指派 |
| 商店管理     | 支援多商店與多角色指派功能 |
| 機車管理     | 管理車輛資料、狀態、配件、價格 |
| 價格模組     | 每台車可有多種天數與對應金額 |
| 租借模組     | 建立訂單、狀態控管、結帳紀錄 |
| 紅單模組     | 關聯訂單與租借者，並可追蹤處理狀態 |

---

## ✅ 建議技術架構

- **後端**：Laravel + Filament（管理後台）
- **前端**：Flutter 或 Vue.js（使用者租借介面）
- **資料庫**：MySQL 或 PostgreSQL
- **部署建議**：使用 CloudPanel + Nginx + SSL，並搭配 Cloudflare 防護
