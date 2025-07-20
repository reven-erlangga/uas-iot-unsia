package com.unsia.project3.service

import org.springframework.stereotype.Service

@Service
class HomeService {
    fun getWelcomeMessage(): String {
        return "Welcome to UNSIA IoT Dashboard!"
    }
}
