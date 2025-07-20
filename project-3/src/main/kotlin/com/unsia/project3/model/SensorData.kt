package com.unsia.project3.model

import jakarta.persistence.*
import java.time.LocalDateTime

@Entity
@Table(name = "sensor_data")
data class SensorData(

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    val id: Long? = null,

    val apikey: String? = null,

    val deviceId: String? = null,

    val temperature: Float? = null,

    val humidity: Float? = null,

    val timestamp: LocalDateTime = LocalDateTime.now()
)
