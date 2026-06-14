package com.pizzasorder.admin.dto;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;

import java.math.BigDecimal;

@Data
@Builder
@AllArgsConstructor
public class ProductResponse {
    private Integer id;
    private String name;
    private BigDecimal price;
    private String description;
    private String imageUrl;
}
