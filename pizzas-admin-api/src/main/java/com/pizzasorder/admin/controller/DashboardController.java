package com.pizzasorder.admin.controller;

import com.pizzasorder.admin.dto.DashboardResponse;
import com.pizzasorder.admin.repository.ProductRepository;
import com.pizzasorder.admin.repository.TransactionRepository;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.Map;

@RestController
@RequestMapping("/api/admin")
public class DashboardController {

    private final ProductRepository productRepository;
    private final TransactionRepository transactionRepository;

    public DashboardController(ProductRepository productRepository,
                               TransactionRepository transactionRepository) {
        this.productRepository = productRepository;
        this.transactionRepository = transactionRepository;
    }

    @GetMapping("/dashboard")
    public ResponseEntity<?> getDashboard() {
        DashboardResponse dashboard = DashboardResponse.builder()
                .totalProducts(productRepository.count())
                .totalTransactions(transactionRepository.count())
                .pendingOrders(transactionRepository.countByStatus("Pending"))
                .processingOrders(transactionRepository.countByStatus("Processing"))
                .completedOrders(transactionRepository.countByStatus("Completed"))
                .build();

        return ResponseEntity.ok(Map.of("data", dashboard));
    }
}
