package com.pizzasorder.admin.service;

import com.pizzasorder.admin.dto.TransactionItemResponse;
import com.pizzasorder.admin.dto.TransactionResponse;
import com.pizzasorder.admin.entity.Transaction;
import com.pizzasorder.admin.entity.TransactionItem;
import com.pizzasorder.admin.repository.TransactionRepository;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.stream.Collectors;

@Service
public class TransactionService {

    private final TransactionRepository transactionRepository;

    public TransactionService(TransactionRepository transactionRepository) {
        this.transactionRepository = transactionRepository;
    }

    public List<TransactionResponse> getAllTransactions(String baseUrl) {
        List<Transaction> transactions = transactionRepository.findAllByOrderByTransactionTimeDesc();
        return transactions.stream()
                .map(t -> toResponse(t, baseUrl))
                .collect(Collectors.toList());
    }

    public TransactionResponse updateStatus(Integer id, String status) {
        Transaction transaction = transactionRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Transaksi tidak ditemukan"));

        transaction.setStatus(status);
        transaction = transactionRepository.save(transaction);
        return toResponse(transaction, null);
    }

    private TransactionResponse toResponse(Transaction t, String baseUrl) {
        List<TransactionItemResponse> items = List.of();
        if (t.getItems() != null) {
            items = t.getItems().stream()
                    .map(this::toItemResponse)
                    .collect(Collectors.toList());
        }

        String username = "Guest/User Terhapus";
        if (t.getUser() != null) {
            username = t.getUser().getUsername();
        }

        String paymentProofUrl = null;
        if (t.getPaymentProof() != null && baseUrl != null) {
            String filename = t.getPaymentProof().replace("img/", "");
            paymentProofUrl = baseUrl + "/api/admin/images/" + filename;
        }

        return TransactionResponse.builder()
                .id(t.getId())
                .transactionTime(t.getTransactionTime())
                .username(username)
                .total(t.getTotal())
                .status(t.getStatus())
                .paymentProofUrl(paymentProofUrl)
                .items(items)
                .build();
    }

    private TransactionItemResponse toItemResponse(TransactionItem item) {
        String productName = "Produk Terhapus";
        if (item.getProduct() != null) {
            productName = item.getProduct().getName();
        }
        return TransactionItemResponse.builder()
                .productName(productName)
                .quantity(item.getQuantity())
                .price(item.getPrice())
                .build();
    }
}
