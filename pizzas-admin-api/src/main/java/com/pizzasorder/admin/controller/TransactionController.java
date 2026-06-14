package com.pizzasorder.admin.controller;

import com.pizzasorder.admin.dto.TransactionResponse;
import com.pizzasorder.admin.dto.UpdateStatusRequest;
import com.pizzasorder.admin.service.TransactionService;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api/admin")
public class TransactionController {

    private final TransactionService transactionService;

    public TransactionController(TransactionService transactionService) {
        this.transactionService = transactionService;
    }

    @GetMapping("/transactions")
    public ResponseEntity<?> getAllTransactions(HttpServletRequest request) {
        String baseUrl = request.getScheme() + "://" + request.getServerName() + ":" + request.getServerPort();
        List<TransactionResponse> transactions = transactionService.getAllTransactions(baseUrl);
        return ResponseEntity.ok(Map.of("data", transactions));
    }

    @PutMapping("/transactions/{id}/status")
    public ResponseEntity<?> updateStatus(@PathVariable Integer id,
                                          @Valid @RequestBody UpdateStatusRequest request) {
        try {
            TransactionResponse transaction = transactionService.updateStatus(id, request.getStatus());
            return ResponseEntity.ok(Map.of(
                    "message", "Status pesanan #" + id + " berhasil diperbarui!",
                    "data", transaction
            ));
        } catch (RuntimeException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND)
                    .body(Map.of("error", e.getMessage()));
        }
    }
}
