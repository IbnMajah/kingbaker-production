<template>
    <div>
      <Head title="Create Daily Record" />
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">
          <Link class="text-brand-400 hover:text-brand-600" href="/daily-records">Daily Records</Link>
          <span class="text-brand-400 font-medium">/</span>
          Create
        </h1>
        <!-- <div class="flex items-center">
          <label for="file-upload" class="btn-kingbaker mr-2 cursor-pointer">
            <input
              id="file-upload"
              type="file"
              class="hidden"
              accept=".csv,.xlsx,.xls"
              @change="handleFileUpload"
            >
            <span class="flex items-center">
              <icon name="upload" class="w-4 h-4 mr-2" />
              Import
            </span>
          </label>
          <button class="btn-kingbaker" @click="submit">
            <span class="hidden md:inline">Create Daily Record</span>
            <span class="md:hidden">Create</span>
          </button>
        </div> -->
      </div>
  
      <div class="bg-white rounded-md shadow overflow-x-auto p-6">
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="record_date">
            Record Date
          </label>
          <input
            id="record_date"
            v-model="form.record_date"
            type="date"
            class="form-input w-full"
          >
        </div>
  
        <div class="mb-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Products</h2>
            <div class="flex gap-2">
              <label for="file-upload" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                <input
                  id="file-upload"
                  type="file"
                  class="hidden"
                  accept=".csv,.xlsx,.xls"
                  @change="handleFileUpload"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                Import Products
              </label>
              <button
                type="button"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="addRow"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Product
              </button>
            </div>
          </div>
  
          <table class="w-full">
            <thead>
              <tr>
                <th class="text-left pb-4">Product Code</th>
                <th class="text-left pb-4">Product</th>
                <th class="text-left pb-4">Quantity</th>
                <th class="text-left pb-4">Revenue</th>
                <th class="w-8 pb-4"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in form.products" :key="index" class="border-t">
                <td class="py-4 pr-4">
                  <span class="text-gray-600">
                    {{ getProductCode(row.product_id) }}
                  </span>
                </td>
                <td class="py-4 pr-4">
                  <select 
                    v-model="row.product_id"
                    class="form-select w-full"
                    @change="updateRevenue(index)"
                  >
                    <option value="">Select a product</option>
                    <option v-for="product in products" :key="product.id" :value="product.id">
                      {{ product.name }}
                    </option>
                  </select>
                </td>
                <td class="py-4 pr-4">
                  <input
                    v-model.number="row.quantity"
                    type="number"
                    min="0"
                    class="form-input w-full"
                    @input="updateRevenue(index)"
                  >
                </td>
                <td class="py-4 pr-4">
                  <span class="text-gray-600">
                    GMD {{ calculateRevenue(row) }}
                  </span>
                </td>
                <td class="py-4">
                  <button
                    type="button"
                    class="text-red-600 hover:text-red-800"
                    @click="removeRow(index)"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </td>
              </tr>
              <tr v-if="form.products.length === 0">
                <td colspan="5" class="py-4 text-center text-gray-500">
                  No products added. Click "Add Product" to start.
                </td>
              </tr>
            </tbody>
          </table>

          <div class="flex justify-end pt-6 border-t">
        <button class="btn-kingbaker" @click="submit">
          <span>Create Daily Record</span>
        </button>
      </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/vue3'
  import Icon from '@/Shared/Icon.vue'
  import Layout from '@/Shared/Layout.vue'
  import Papa from 'papaparse'
  import * as XLSX from 'xlsx'
  
  export default {
    components: {
      Head,
      Icon,
      Link,
    },
    layout: Layout,
    props: {
      products: Array,
    },
    data() {
      return {
        form: {
          record_date: new Date().toISOString().split('T')[0],
          products: [],
        },
      }
    },
    methods: {
      addRow() {
        this.form.products.push({
          product_id: '',
          quantity: 0,
          revenue: 0,
        })
      },
      removeRow(index) {
        this.form.products.splice(index, 1)
      },
      calculateRevenue(row) {
        if (!row.product_id || !row.quantity) return 0
        const product = this.products.find(p => p.id === row.product_id)
        return product ? row.quantity * product.selling_price : 0
      },
      updateRevenue(index) {
        const row = this.form.products[index]
        row.revenue = this.calculateRevenue(row)
      },
      handleFileUpload(event) {
        const file = event.target.files[0]
        if (!file) return

        // Reset file input for future uploads
        event.target.value = ''

        const fileType = file.name.split('.').pop().toLowerCase()

        if (fileType === 'csv') {
          this.handleCSV(file)
        } else if (['xlsx', 'xls'].includes(fileType)) {
          this.handleExcel(file)
        } else {
          // Show error message for invalid file type
          alert('Please upload a valid CSV or Excel file')
        }
      },
      handleCSV(file) {
        Papa.parse(file, {
          header: true,
          complete: (results) => {
            this.processImportedData(results.data)
          },
          error: (error) => {
            console.error('Error parsing CSV:', error)
            alert('Error parsing CSV file')
          }
        })
      },
      handleExcel(file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          try {
            const data = new Uint8Array(e.target.result)
            const workbook = XLSX.read(data, { type: 'array' })
            
            // Get the first worksheet
            const worksheetName = workbook.SheetNames[0]
            const worksheet = workbook.Sheets[worksheetName]
            
            // Convert to JSON
            const jsonData = XLSX.utils.sheet_to_json(worksheet)
            this.processImportedData(jsonData)
          } catch (error) {
            console.error('Error parsing Excel:', error)
            alert('Error parsing Excel file')
          }
        }
        reader.readAsArrayBuffer(file)
      },
      processImportedData(data) {
        data.forEach(row => {
          // Handle both possible column names (product_code or "Product Code")
          const productCode = row.product_code || row['Product Code'] || row.PRODUCT_CODE
          const quantity = row.quantity || row.Quantity || row.QUANTITY

          if (!productCode || !quantity) return

          const product = this.products.find(p => p.code === productCode)
          if (!product) {
            console.warn(`Product with code ${productCode} not found`)
            return
          }

          this.form.products.push({
            product_id: product.id,
            quantity: parseInt(quantity),
            revenue: this.calculateRevenue({
              product_id: product.id,
              quantity: parseInt(quantity)
            })
          })
        })
      },
      submit() {
        this.$inertia.post('/daily-records', this.form)
      },
      getProductCode(productId) {
        if (!productId) return '—'
        const product = this.products.find(p => p.id === productId)
        return product ? product.code : '—'
      },
    },
  }
  </script>