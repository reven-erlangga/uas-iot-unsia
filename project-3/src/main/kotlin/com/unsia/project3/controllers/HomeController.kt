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

    @GetMapping("/sensor/chart")
    fun showChartPage(model: Model): String {
        val deviceIds = service.getDeviceIds()
        model.addAttribute("deviceIds", deviceIds)
        return "sensorchart"  // render templates/sensorchart.html
    }

    @GetMapping("/sensor/data")
    fun showDataPage(model: Model): String {
        val deviceIds = service.getDeviceIds()
        model.addAttribute("deviceIds", deviceIds)
        return "sensordata"  // render templates/sensordata.html
    }

    @GetMapping("/sensor/chart/{deviceId}")
    fun showChartWithData(@PathVariable deviceId: String, model: Model): String {
        val dataList: List<SensorData> = service.getByDeviceId(deviceId)
        val deviceIds = service.getDeviceIds()
        model.addAttribute("deviceId", deviceId)
        model.addAttribute("dataList", dataList)
        model.addAttribute("deviceIds", deviceIds)
        return "sensorchart"  // render templates/sensorchart.html
    }

    @GetMapping("/sensor/data/{deviceId}")
    fun showDataWithDevice(@PathVariable deviceId: String, model: Model): String {
        val dataList: List<SensorData> = service.getByDeviceId(deviceId)
        val deviceIds = service.getDeviceIds()
        model.addAttribute("deviceId", deviceId)
        model.addAttribute("dataList", dataList)
        model.addAttribute("deviceIds", deviceIds)
        return "sensordata"  // render templates/sensordata.html
    }
}
