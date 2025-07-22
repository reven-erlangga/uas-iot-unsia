package com.unsia.project3.controller

import com.unsia.project3.model.SensorData
import com.unsia.project3.service.SensorDataService
import org.springframework.http.ResponseEntity
import org.springframework.web.bind.annotation.*

@RestController
@RequestMapping("/api/sensors")
class SensorDataController(private val service: SensorDataService) {

    @PostMapping
    fun receiveSensorData(@RequestBody data: SensorData): ResponseEntity<SensorData> {
        println("Received Data: $data")
        val savedData = service.save(data)
        return ResponseEntity.ok(savedData)
    }

    @GetMapping("/{deviceId}")
    fun getSensorData(@PathVariable deviceId: String): ResponseEntity<List<SensorData>> {
        val data = service.getByDeviceId(deviceId)
        return ResponseEntity.ok(data)
    }

    @GetMapping
    fun getAllSensorData(): ResponseEntity<List<SensorData>> {
        val allData = service.getAll()
        return ResponseEntity.ok(allData)
    }

    @GetMapping("/devices")
    fun getDeviceList(): ResponseEntity<List<String>> {
        val deviceIds = service.getDeviceIds()
        return ResponseEntity.ok(deviceIds)
    }

    @GetMapping("/{deviceId}/latest")
    fun getLatestSensorData(@PathVariable deviceId: String): ResponseEntity<SensorData?> {
        val latestData = service.getLatestByDeviceId(deviceId)
        return ResponseEntity.ok(latestData)
    }

    @DeleteMapping("/{deviceId}")
    fun deleteSensorData(@PathVariable deviceId: String): ResponseEntity<String> {
        service.deleteByDeviceId(deviceId)
        return ResponseEntity.ok("Data for device $deviceId deleted successfully")
    }
}
