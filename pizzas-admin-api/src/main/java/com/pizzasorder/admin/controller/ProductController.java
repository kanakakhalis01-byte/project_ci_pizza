package com.pizzasorder.admin.controller;

import com.pizzasorder.admin.config.FileStorageConfig;
import com.pizzasorder.admin.dto.ProductResponse;
import com.pizzasorder.admin.service.ProductService;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.core.io.FileSystemResource;
import org.springframework.core.io.Resource;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api/admin")
public class ProductController {

    private final ProductService productService;
    private final FileStorageConfig fileStorageConfig;

    public ProductController(ProductService productService, FileStorageConfig fileStorageConfig) {
        this.productService = productService;
        this.fileStorageConfig = fileStorageConfig;
    }

    @GetMapping("/products")
    public ResponseEntity<?> getAllProducts(HttpServletRequest request) {
        String baseUrl = getBaseUrl(request);
        List<ProductResponse> products = productService.getAllProducts(baseUrl);
        return ResponseEntity.ok(Map.of("data", products));
    }

    @PostMapping(value = "/products", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public ResponseEntity<?> createProduct(
            @RequestParam("name") String name,
            @RequestParam("price") String price,
            @RequestParam("desc") String description,
            @RequestParam(value = "image", required = false) MultipartFile image,
            HttpServletRequest request) {
        try {
            String baseUrl = getBaseUrl(request);
            ProductResponse product = productService.createProduct(name, price, description, image, baseUrl);
            return ResponseEntity.status(HttpStatus.CREATED)
                    .body(Map.of("message", "Produk berhasil ditambahkan", "data", product));
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("error", "Gagal mengupload gambar: " + e.getMessage()));
        }
    }

    @PutMapping(value = "/products/{id}", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public ResponseEntity<?> updateProduct(
            @PathVariable Integer id,
            @RequestParam("name") String name,
            @RequestParam("price") String price,
            @RequestParam("desc") String description,
            @RequestParam(value = "image", required = false) MultipartFile image,
            HttpServletRequest request) {
        try {
            String baseUrl = getBaseUrl(request);
            ProductResponse product = productService.updateProduct(id, name, price, description, image, baseUrl);
            return ResponseEntity.ok(Map.of("message", "Produk berhasil diupdate", "data", product));
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body(Map.of("error", "Gagal mengupload gambar: " + e.getMessage()));
        } catch (RuntimeException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND)
                    .body(Map.of("error", e.getMessage()));
        }
    }

    @DeleteMapping("/products/{id}")
    public ResponseEntity<?> deleteProduct(@PathVariable Integer id) {
        try {
            productService.deleteProduct(id);
            return ResponseEntity.ok(Map.of("message", "Produk berhasil dihapus"));
        } catch (RuntimeException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND)
                    .body(Map.of("error", e.getMessage()));
        }
    }

    // Serve images from the CI4 public/img directory
    @GetMapping("/images/**")
    public ResponseEntity<Resource> serveImage(HttpServletRequest request) {
        try {
            String fullPath = request.getRequestURI();
            String filename = fullPath.substring(fullPath.indexOf("/images/") + "/images/".length());
            File file = new File(fileStorageConfig.getUploadDir() + "/" + filename);

            if (!file.exists()) {
                return ResponseEntity.notFound().build();
            }

            Resource resource = new FileSystemResource(file);
            String contentType = Files.probeContentType(file.toPath());
            if (contentType == null) {
                contentType = "application/octet-stream";
            }

            return ResponseEntity.ok()
                    .contentType(MediaType.parseMediaType(contentType))
                    .body(resource);
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).build();
        }
    }

    private String getBaseUrl(HttpServletRequest request) {
        return request.getScheme() + "://" + request.getServerName() + ":" + request.getServerPort();
    }
}
