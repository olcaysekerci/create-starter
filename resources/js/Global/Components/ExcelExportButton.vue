<template>
    <Button 
        variant="secondary" 
        size="sm"
        @click="exportToExcel"
        :disabled="loading || !data || data.length === 0"
        :class="{ 'opacity-50 cursor-not-allowed': loading }"
    >
        <svg v-if="!loading" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <svg v-else class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ loading ? 'İndiriliyor...' : 'Excel' }}
    </Button>
</template>

<script setup>
import { ref } from 'vue'
import Button from './Button.vue'

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    filename: {
        type: String,
        default: 'export'
    },
    columns: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['export-start', 'export-complete', 'export-error'])

const loading = ref(false)

const exportToExcel = async () => {
    if (!props.data || props.data.length === 0) {
        alert('İndirilecek veri bulunmuyor.')
        return
    }

    loading.value = true
    emit('export-start')

    try {
        // Dinamik import ile xlsx kütüphanesini yükle
        const XLSX = await import('xlsx')
        
        // Veriyi hazırla
        let exportData = props.data
        
        // Eğer columns tanımlıysa, sadece o kolonları al
        if (props.columns.length > 0) {
            exportData = props.data.map(row => {
                const newRow = {}
                props.columns.forEach(col => {
                    if (typeof col === 'string') {
                        newRow[col] = row[col]
                    } else if (col.key) {
                        newRow[col.title || col.key] = getNestedProperty(row, col.key)
                        
                        // Tarih formatlaması
                        if (col.type === 'date' && newRow[col.title || col.key]) {
                            newRow[col.title || col.key] = new Date(newRow[col.title || col.key]).toLocaleDateString('tr-TR')
                        }
                    }
                })
                return newRow
            })
        }

        // Workbook ve worksheet oluştur
        const wb = XLSX.utils.book_new()
        const ws = XLSX.utils.json_to_sheet(exportData)

        // Kolon genişliklerini ayarla
        const colWidths = []
        if (props.columns.length > 0) {
            props.columns.forEach(() => {
                colWidths.push({ wch: 20 })
            })
        } else {
            Object.keys(exportData[0] || {}).forEach(() => {
                colWidths.push({ wch: 20 })
            })
        }
        ws['!cols'] = colWidths

        // Worksheet'i workbook'a ekle
        XLSX.utils.book_append_sheet(wb, ws, 'Veri')

        // Dosya adını hazırla
        const timestamp = new Date().toISOString().slice(0, 10)
        const fileName = `${props.filename}_${timestamp}.xlsx`

        // Dosyayı indir
        XLSX.writeFile(wb, fileName)

        emit('export-complete', fileName)
    } catch (error) {
        console.error('Excel export error:', error)
        alert('Excel dosyası oluşturulurken bir hata oluştu.')
        emit('export-error', error)
    } finally {
        loading.value = false
    }
}

const getNestedProperty = (obj, path) => {
    return path.split('.').reduce((current, key) => current?.[key], obj)
}
</script> 