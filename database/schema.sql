-- ============================================================
--  RBAC Schema for absensi-php-native
--  Run this file once to set up the database.
-- ============================================================

CREATE DATABASE IF NOT EXISTS absensi_rbac
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE absensi_rbac;

-- ── Tables ───────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS roles (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS permissions (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) UNIQUE NOT NULL,
    description VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id       INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id)       REFERENCES roles(id)       ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS attendances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    check_in TIMESTAMP NULL,
    check_out TIMESTAMP NULL,
    status ENUM('Hadir', 'Izin', 'Sakit', 'Alpa') DEFAULT 'Hadir',
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Seed Data ─────────────────────────────────────────────────────────────────

-- Default role
INSERT IGNORE INTO roles (id, name, description) VALUES
    (1, 'admin', 'Administrator with full access'),
    (2, 'dosen', 'Dosen pengajar'),
    (3, 'mahasiswa', 'Mahasiswa yang melakukan absensi');

-- Default permissions
INSERT IGNORE INTO permissions (name, description) VALUES
    ('user.view',        'View users'),
    ('user.create',      'Create users'),
    ('user.edit',        'Edit users'),
    ('user.delete',      'Delete users'),
    ('role.view',        'View roles'),
    ('role.create',      'Create roles'),
    ('role.edit',        'Edit roles'),
    ('role.delete',      'Delete roles'),
    ('permission.view',  'View permissions'),
    ('permission.create','Create permissions'),
    ('permission.edit',  'Edit permissions'),
    ('permission.delete','Delete permissions'),
    ('absensi.view_all', 'Melihat semua data absensi mahasiswa'),
    ('absensi.view_own', 'Melihat riwayat absensi diri sendiri'),
    ('absensi.create',   'Melakukan absensi masuk/pulang');

-- Assign ALL permissions to the admin role (role id = 1)
INSERT IGNORE INTO role_permissions (role_id, permission_id)
    SELECT 1, id FROM permissions;

-- Assign Dosen permissions (role id = 2) - bisa melihat semua absen
INSERT IGNORE INTO role_permissions (role_id, permission_id)
    SELECT 2, id FROM permissions WHERE name IN ('absensi.view_all');

-- Assign Mahasiswa permissions (role id = 3) - bisa absen & melihat absennya sendiri
INSERT IGNORE INTO role_permissions (role_id, permission_id)
    SELECT 3, id FROM permissions WHERE name IN ('absensi.view_own', 'absensi.create');

-- Default admin user
-- Password: aku233  (bcrypt hash generated with PHP password_hash('aku233', PASSWORD_DEFAULT))
INSERT IGNORE INTO users (id, name, email, password) VALUES (
    1,
    'Administrator',
    'admin@example.com',
    '$2y$10$jLToR.wFqV9x.RB2j/CrneTAX9cs9rRsxsNUanDSdrLyPig037rNO'
);

-- Assign admin role to the admin user
INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (1, 1);
