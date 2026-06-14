package com.pizzasorder.admin.config;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Configuration;

import jakarta.annotation.PostConstruct;
import java.io.File;

@Configuration
public class FileStorageConfig {

    @Value("${file.upload-dir}")
    private String uploadDir;

    @PostConstruct
    public void init() {
        File dir = new File(uploadDir);
        if (!dir.exists()) {
            dir.mkdirs();
        }
        // Ensure payments subdirectory exists
        File paymentsDir = new File(uploadDir + "/payments");
        if (!paymentsDir.exists()) {
            paymentsDir.mkdirs();
        }
    }

    public String getUploadDir() {
        return uploadDir;
    }
}
