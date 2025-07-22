package com.unsia.project3.repository

import com.unsia.project3.model.SensorData
import org.springframework.data.domain.Pageable
import org.springframework.data.jpa.repository.JpaRepository
import org.springframework.data.jpa.repository.Modifying
import org.springframework.data.jpa.repository.Query
import org.springframework.data.repository.query.Param
import org.springframework.stereotype.Repository

@Repository
interface SensorDataRepository : JpaRepository<SensorData, Long> {
    fun findByDeviceId(deviceId: String): List<SensorData>
    fun findByDeviceIdOrderByTimestampDesc(deviceId: String, pageable: Pageable): List<SensorData>
    fun findAllByOrderByTimestampDesc(): List<SensorData>
    fun findTopByDeviceIdOrderByTimestampDesc(deviceId: String): SensorData?
    
    @Modifying
    @Query("DELETE FROM SensorData s WHERE s.deviceId = :deviceId")
    fun deleteByDeviceId(@Param("deviceId") deviceId: String)
    
    @Query("SELECT DISTINCT s.deviceId FROM SensorData s WHERE s.deviceId IS NOT NULL")
    fun findDistinctDeviceIds(): List<String>
}
