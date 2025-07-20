package com.unsia.project3.controller

import com.unsia.project3.model.SensorData
import com.unsia.project3.service.SensorDataService
import org.springframework.stereotype.Controller
import org.springframework.ui.Model
import org.springframework.web.bind.annotation.GetMapping
import org.springframework.web.bind.annotation.PathVariable

@Controller
class HomeController(private val service: SensorDataService) {

    @GetMapping("/")
    fun home(model: Model): String {
        val message = "Welcome to UNSIA"
        model.addAttribute("message", message)
        return "home"  // render templates/home.html
    }

    @GetMapping("/{deviceId}")
    fun showData(@PathVariable deviceId: String, model: Model): String {
        val dataList: List<SensorData> = service.getByDeviceId(deviceId)
        model.addAttribute("deviceId", deviceId)
        model.addAttribute("dataList", dataList)
        return "sensorchart"  // render templates/sensorchart.html
    }
}
