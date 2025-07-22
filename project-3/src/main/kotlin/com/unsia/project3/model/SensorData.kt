package com.unsia.project3.model

import jakarta.persistence.*
import java.time.LocalDateTime

@Entity
@Table(name = "sensor_data")
data class SensorData(

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    val id: Long? = null,

    @Column(name = "apikey")
    val apikey: String? = null,

    @Column(name = "device_id")
    val deviceId: String? = null,

    @Column(name = "device_name")
    val deviceName: String? = null,

    @Column(name = "temperature")
    val temperature: Float? = null,

    @Column(name = "humidity")
    val humidity: Float? = null,

    @Column(name = "timestamp")
    val timestamp: LocalDateTime = LocalDateTime.now()
)
