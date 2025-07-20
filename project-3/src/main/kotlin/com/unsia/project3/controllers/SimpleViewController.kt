package com.unsia.project3.controller

import com.unsia.project3.model.SensorData
import com.unsia.project3.repository.SensorDataRepository
import org.springframework.stereotype.Controller
import org.springframework.ui.Model
import org.springframework.web.bind.annotation.GetMapping
import org.springframework.web.bind.annotation.PathVariable

@Controller
class SimpleViewController(private val repository: SensorDataRepository) {

    @GetMapping("/sensors/{deviceId}")
    fun viewSensorData(@PathVariable deviceId: String, model: Model): String {
        val dataList: List<SensorData> = repository.findByDeviceId(deviceId)
        model.addAttribute("deviceId", deviceId)
        model.addAttribute("dataList", dataList)
        return "sensordata" // render templates/sensordata.html
    }
}
