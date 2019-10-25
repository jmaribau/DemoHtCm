CREATE TABLE coffee (
        id INT AUTO_INCREMENT NOT NULL, 
        name VARCHAR(255) NOT NULL, 
        intensity INT NOT NULL, 
        price INT NOT NULL, 
        stock INT NOT NULL, 
        PRIMARY KEY(id)
    ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

CREATE TABLE shop_order (
        id INT AUTO_INCREMENT NOT NULL, 
        user_id INT NOT NULL, 
        coffee_id INT NOT NULL, 
        amount INT NOT NULL, 
        quantity INT NOT NULL, 
        INDEX IDX_323FC9CAA76ED395 (user_id), 
        INDEX IDX_323FC9CA78CD6D6E (coffee_id), 
        PRIMARY KEY(id)
    ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

CREATE TABLE user (
        id INT AUTO_INCREMENT NOT NULL, 
        username VARCHAR(180) NOT NULL, 
        roles JSON NOT NULL, 
        password VARCHAR(255) NOT NULL, 
        UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), 
        PRIMARY KEY(id)
    ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CAA76ED395 
    FOREIGN KEY (user_id) REFERENCES user (id);

ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA78CD6D6E 
    FOREIGN KEY (coffee_id) REFERENCES coffee (id);