package com.pizzasorder.admin.dto;

import jakarta.validation.constraints.NotBlank;
import lombok.Data;

@Data
public class UpdateStatusRequest {
    @NotBlank(message = "Status tidak boleh kosong")
    private String status;
}
