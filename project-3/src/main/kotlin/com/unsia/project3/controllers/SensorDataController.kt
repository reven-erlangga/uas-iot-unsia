package com.unsia.project3.controller

import com.unsia.project3.model.SensorData
import com.unsia.project3.service.SensorDataService
import org.springframework.web.bind.annotation.*

@RestController
@RequestMapping("/api/sensors")
class SensorDataController(private val service: SensorDataService) {

    @PostMapping
    fun receiveSensorData(@RequestBody data: SensorData): SensorData {
        println("Received Data: $data")
        return service.save(data)
    }

    @GetMapping("/{deviceId}")
    fun getSensorData(@PathVariable deviceId: String): List<SensorData> {
        return service.getByDeviceId(deviceId)
    }
}
