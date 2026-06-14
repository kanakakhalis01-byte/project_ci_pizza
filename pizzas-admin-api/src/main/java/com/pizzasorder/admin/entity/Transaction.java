package com.pizzasorder.admin.entity;

import jakarta.persistence.*;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.List;

@Entity
@Table(name = "transactions")
@Data
@NoArgsConstructor
@AllArgsConstructor
public class Transaction {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer id;

    @Column(name = "user_id")
    private Integer userId;

    @Column(name = "transaction_time")
    private LocalDateTime transactionTime;

    @Column(precision = 10, scale = 2)
    private BigDecimal total;

    @Column(length = 50)
    private String status;

    @Column(name = "payment_proof", length = 255)
    private String paymentProof;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "user_id", insertable = false, updatable = false)
    private User user;

    @OneToMany(mappedBy = "transaction", fetch = FetchType.EAGER)
    private List<TransactionItem> items;
}
