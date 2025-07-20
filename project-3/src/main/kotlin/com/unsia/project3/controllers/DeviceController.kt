package com.unsia.project3.controller

import org.springframework.stereotype.Controller
import org.springframework.ui.Model
import org.springframework.web.bind.annotation.GetMapping
import org.springframework.web.bind.annotation.RequestMapping

@Controller
@RequestMapping("/spring")
class DeviceController {

    @GetMapping("/device01")
    fun device01(model: Model): String {
        model.addAttribute("message", "Hello from Spring Boot Kotlin!")
        return "home"
    }
}
