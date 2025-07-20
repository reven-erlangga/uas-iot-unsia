package com.unsia.project3.repository

import com.unsia.project3.model.SensorData
import org.springframework.data.domain.Pageable
import org.springframework.data.jpa.repository.JpaRepository
import org.springframework.stereotype.Repository

@Repository
interface SensorDataRepository : JpaRepository<SensorData, Long> {
    fun findByDeviceId(deviceId: String): List<SensorData>
    fun findByDeviceIdOrderByTimestampDesc(deviceId: String, pageable: Pageable): List<SensorData>
}
