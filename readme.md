# NetworkMap    

## Introduction
I have chosen this theme because I wanted to play with <b><a href="https://github.com/vasturiano/3d-force-graph">3d-force-graph</a></b>. The application is written in PHP and it includes a JS library for the 3D graph. In the application, you can recreate networks, add devices, and their connections.

## Consept

In the app, you should be able to create networks, their devices, and connections. You should also be able to update properties and easily view them.

For the database, I have used MySQL with phpMyAdmin.

<b>NOTE: App was tested only with XAMPP</b> 

## AI ussage

I have used AI to get ideas on how to do different things, and I have written repetitive code with it. I also used it for grammar checks.

I have used Google's Gemini 2.5 Pro (preview) with Canvas.

## Features

- [x] create users 
    > users can login and register
- [x] create networks
    > users can create new networks
- [x] delete networks
    > users can delete their networks
- [x] create devices
    > users can create new devices
- [x] delete devices
    > users can delete their devices
- [x] connect devices
    > users can create relations between devices

## Features to add

- [ ] modify networks
    > users should be able to modify network properties 
- [ ] modify devices
    > users should be able to modify devices properties
- [ ] modify connections
    > users should be able to modify connections
- [ ] delete connections
    > users should be able to dečete connections between devices
- [ ] <b style="color: red">Improve sql injection security</b>
    > code where the db calls are perforemd is vonareble to sql injection

## usage

project was only tested with XAMPP!!!

put this project folder inside: <b>xampp\htdocs</b>

### php querry for database creation
run this querry for database creation:


```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);

CREATE TABLE networks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE (user_id, name)
);

CREATE INDEX idx_networks_user_id ON networks(user_id);

CREATE TABLE devices (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    network_id BIGINT NOT NULL,
    name VARCHAR(255),
    type VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45),
    description TEXT,
    first_seen_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_seen_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (network_id) REFERENCES networks(id) ON DELETE CASCADE
);

CREATE INDEX idx_devices_user_id ON devices(user_id);
CREATE INDEX idx_devices_network_id ON devices(network_id);
CREATE INDEX idx_devices_name ON devices(name);
CREATE INDEX idx_devices_type ON devices(type);
CREATE INDEX idx_devices_ip_address ON devices(ip_address);

CREATE TABLE connections (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    network_id BIGINT NOT NULL,
    source BIGINT NULL,
    target BIGINT NULL,
    connected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    disconnected_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (source) REFERENCES devices(id) ON DELETE SET NULL,
    FOREIGN KEY (target) REFERENCES devices(id) ON DELETE SET NULL,
    FOREIGN KEY (network_id) REFERENCES networks(id) ON DELETE CASCADE
);

CREATE INDEX idx_connections_user_id ON connections(user_id);
CREATE INDEX idx_connections_device_from_id ON connections(device_from_id);
CREATE INDEX idx_connections_device_to_id ON connections(device_to_id);
CREATE INDEX idx_connections_connected_at ON connections(connected_at);
```


## Conclusion

- [x] I have learend how to use <b>3d-forceGraph</b>
- [x] have partaly learend how to  connect php with js

for features that i added see <a href="#features">features</a>
and for features i didn't manage to ad <a href="#features-to-add">features to add</a>