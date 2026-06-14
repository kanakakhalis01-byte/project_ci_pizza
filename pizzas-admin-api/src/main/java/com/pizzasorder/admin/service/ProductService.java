package com.pizzasorder.admin.service;

import com.pizzasorder.admin.config.FileStorageConfig;
import com.pizzasorder.admin.dto.ProductResponse;
import com.pizzasorder.admin.entity.Product;
import com.pizzasorder.admin.repository.ProductRepository;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import java.io.File;
import java.io.IOException;
import java.util.List;
import java.util.UUID;
import java.util.stream.Collectors;

@Service
public class ProductService {

    private final ProductRepository productRepository;
    private final FileStorageConfig fileStorageConfig;

    public ProductService(ProductRepository productRepository, FileStorageConfig fileStorageConfig) {
        this.productRepository = productRepository;
        this.fileStorageConfig = fileStorageConfig;
    }

    public List<ProductResponse> getAllProducts(String baseUrl) {
        return productRepository.findAll().stream()
                .map(p -> toResponse(p, baseUrl))
                .collect(Collectors.toList());
    }

    public ProductResponse getProduct(Integer id, String baseUrl) {
        Product product = productRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Produk tidak ditemukan"));
        return toResponse(product, baseUrl);
    }

    public ProductResponse createProduct(String name, String price, String description,
                                         MultipartFile image, String baseUrl) throws IOException {
        Product product = new Product();
        product.setName(name);
        product.setPrice(new java.math.BigDecimal(price));
        product.setDescription(description);

        if (image != null && !image.isEmpty()) {
            String fileName = saveImage(image);
            product.setImg("img/" + fileName);
        }

        product = productRepository.save(product);
        return toResponse(product, baseUrl);
    }

    public ProductResponse updateProduct(Integer id, String name, String price,
                                         String description, MultipartFile image,
                                         String baseUrl) throws IOException {
        Product product = productRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Produk tidak ditemukan"));

        product.setName(name);
        product.setPrice(new java.math.BigDecimal(price));
        product.setDescription(description);

        if (image != null && !image.isEmpty()) {
            String fileName = saveImage(image);
            product.setImg("img/" + fileName);
        }

        product = productRepository.save(product);
        return toResponse(product, baseUrl);
    }

    public void deleteProduct(Integer id) {
        if (!productRepository.existsById(id)) {
            throw new RuntimeException("Produk tidak ditemukan");
        }
        productRepository.deleteById(id);
    }

    private String saveImage(MultipartFile file) throws IOException {
        String originalFilename = file.getOriginalFilename();
        String extension = "";
        if (originalFilename != null && originalFilename.contains(".")) {
            extension = originalFilename.substring(originalFilename.lastIndexOf("."));
        }
        String newFileName = UUID.randomUUID().toString() + extension;

        File dest = new File(fileStorageConfig.getUploadDir() + "/" + newFileName);
        file.transferTo(dest);

        return newFileName;
    }

    private ProductResponse toResponse(Product product, String baseUrl) {
        String imageUrl = null;
        if (product.getImg() != null) {
            // Strip the "img/" prefix and serve via our images endpoint
            String filename = product.getImg().replace("img/", "");
            imageUrl = baseUrl + "/api/admin/images/" + filename;
        }
        return ProductResponse.builder()
                .id(product.getId())
                .name(product.getName())
                .price(product.getPrice())
                .description(product.getDescription())
                .imageUrl(imageUrl)
                .build();
    }
}
