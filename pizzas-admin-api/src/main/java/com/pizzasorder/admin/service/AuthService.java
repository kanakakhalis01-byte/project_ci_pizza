package com.pizzasorder.admin.service;

import com.pizzasorder.admin.dto.LoginRequest;
import com.pizzasorder.admin.dto.LoginResponse;
import com.pizzasorder.admin.entity.Admin;
import com.pizzasorder.admin.repository.AdminRepository;
import com.pizzasorder.admin.security.JwtTokenProvider;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

@Service
public class AuthService {

    private final AdminRepository adminRepository;
    private final PasswordEncoder passwordEncoder;
    private final JwtTokenProvider jwtTokenProvider;

    public AuthService(AdminRepository adminRepository,
                       PasswordEncoder passwordEncoder,
                       JwtTokenProvider jwtTokenProvider) {
        this.adminRepository = adminRepository;
        this.passwordEncoder = passwordEncoder;
        this.jwtTokenProvider = jwtTokenProvider;
    }

    public LoginResponse login(LoginRequest request) {
        Admin admin = adminRepository.findByUsername(request.getUsername())
                .orElseThrow(() -> new RuntimeException("Username atau Password Admin salah!"));

        if (!passwordEncoder.matches(request.getPassword(), admin.getPassword())) {
            throw new RuntimeException("Username atau Password Admin salah!");
        }

        String token = jwtTokenProvider.generateToken(admin.getUsername());

        return LoginResponse.builder()
                .token(token)
                .username(admin.getUsername())
                .message("Login berhasil")
                .build();
    }
}
