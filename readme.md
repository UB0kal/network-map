### php querry for database creation:
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);

CREATE TABLE devices (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,

    name VARCHAR(255),
    network_name VARCHAR(100),
    type VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45),
    description TEXT,

    first_seen_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_seen_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_devices_user_id ON devices(user_id);
CREATE INDEX idx_devices_name ON devices(name);
CREATE INDEX idx_devices_type ON devices(type);
CREATE INDEX idx_devices_ip_address ON devices(ip_address);
CREATE INDEX idx_devices_network_name ON devices(network_name);


CREATE TABLE connections (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    device_from_id BIGINT NULL,
    device_to_id BIGINT NULL,
    connected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    disconnected_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (device_from_id) REFERENCES devices(id) ON DELETE SET NULL,
    FOREIGN KEY (device_to_id) REFERENCES devices(id) ON DELETE SET NULL
);

CREATE INDEX idx_connections_user_id ON connections(user_id);
CREATE INDEX idx_connections_device_from_id ON connections(device_from_id);
CREATE INDEX idx_connections_device_to_id ON connections(device_to_id);
CREATE INDEX idx_connections_connected_at ON connections(connected_at);
```
