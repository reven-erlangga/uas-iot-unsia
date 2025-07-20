package com.unsia.project3.service

import com.unsia.project3.model.SensorData
import com.unsia.project3.repository.SensorDataRepository
import org.springframework.data.domain.PageRequest
import org.springframework.data.domain.Pageable
import org.springframework.data.domain.Sort
import org.springframework.stereotype.Service

@Service
class SensorDataService(private val repository: SensorDataRepository) {

    fun save(data: SensorData): SensorData {
        return repository.save(data)
    }

    fun getByDeviceId(deviceId: String): List<SensorData> {
        val limit: Pageable = PageRequest.of(0, 20, Sort.by(Sort.Direction.DESC, "timestamp"))
        return repository.findByDeviceIdOrderByTimestampDesc(deviceId, limit)
    }
}
