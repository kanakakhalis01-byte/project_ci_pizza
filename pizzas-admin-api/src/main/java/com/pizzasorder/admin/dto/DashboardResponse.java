package com.pizzasorder.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;

@Data
@Builder
@AllArgsConstructor
public class DashboardResponse {
    private long totalProducts;
    private long totalTransactions;
    private long pendingOrders;
    private long processingOrders;
    private long completedOrders;
}
