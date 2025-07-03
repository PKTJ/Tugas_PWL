-- Database untuk Payment Gateway & API External
CREATE DATABASE IF NOT EXISTS payment_gateway;
USE payment_gateway;

-- Tabel untuk menyimpan data transaksi
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) UNIQUE NOT NULL,
    nama_barang VARCHAR(255) NOT NULL,
    berat INT NOT NULL,
    provinsi_id INT NOT NULL,
    provinsi_nama VARCHAR(100) NOT NULL,
    kota_id INT NOT NULL,
    kota_nama VARCHAR(100) NOT NULL,
    ongkir INT DEFAULT 0,
    total_amount INT NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    payment_type VARCHAR(50),
    snap_token VARCHAR(255),
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan log API calls
CREATE TABLE api_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_type ENUM('rajaongkir', 'midtrans') NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    request_data TEXT,
    response_data TEXT,
    status_code INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan konfigurasi
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('rajaongkir_api_key', 'ISI_DENGAN_API_KEY_ANDA', 'API Key untuk RajaOngkir'),
('midtrans_server_key', 'SB-Mid-server-xxxxxxxxxxxxxxxxxxxx', 'Server Key untuk Midtrans'), --Ganti dengan API KEY anda
('midtrans_client_key', 'SB-Mid-client-xxxxxxxxxxxxxxxxxxxx', 'Client Key untuk Midtrans'), --Ganti dengan API KEY anda
('midtrans_is_production', 'false', 'Mode production Midtrans (true/false)');
