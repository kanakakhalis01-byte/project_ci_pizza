package com.pizzasorder.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;

import java.math.BigDecimal;

@Data
@Builder
@AllArgsConstructor
public class TransactionItemResponse {
    private String productName;
    private Integer quantity;
    private BigDecimal price;
}
