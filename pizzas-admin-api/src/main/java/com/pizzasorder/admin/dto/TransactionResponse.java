package com.pizzasorder.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;

import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.List;

@Data
@Builder
@AllArgsConstructor
public class TransactionResponse {
    private Integer id;
    private LocalDateTime transactionTime;
    private String username;
    private BigDecimal total;
    private String status;
    private String paymentProofUrl;
    private List<TransactionItemResponse> items;
}
